<?php

namespace App\Http\Controllers\API;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|numeric|min:11|unique:App\Models\User',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password'
        ],
            [
                'first_name.required' => 'لطفا نام را وارد نماييد',
                'first_name.string' => 'لطفا نام را به صورت رشته وارد نماييد',
                'last_name.required' => 'لطفا نام خانوادگی را وارد نماييد',
                'last_name.string' => 'لطفا نام خانوادگی را به صورت رشته وارد نماييد',
                'phone.required' => 'لطفا شماره تلفن را وارد نماييد',
                'phone.numeric' => 'لطفا شماره تلفن را به صورت عددی وارد نماييد',
                'phone.min' => 'شماره تلفن نباید کمتر از 10 رقم باشد',
                'phone.unique' => 'شماره تلفن قبلا ثبت شده است',
                'password.required' => 'لطفا رمز عبور را وارد نماييد',
                'password.min' => 'رمز عبور نبايد كمتر از 8 كاراكتر باشد',
                'confirm_password.required' => 'لطفا تكرار رمز عبور را وارد نماييد',
                'confirm_password.same' => 'رمز عبور مطابقت ندارد',
            ]);

        if ($validator->fails()) {
            return $this->sendError('خطا اعتبارسنجی', $validator->errors());
        }
        $register = $request->all();
        $register['password'] = bcrypt($register['password']);
        $user = User::create($register);
        $success['token'] =  $user->createToken('register')->accessToken;
        $success['name'] =  $user->first_name.' '.$user->last_name;

        return $this->sendResponse($success, 'کاربر '. $success['name'] .' ثبت شد');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ],
            [
                'phone.required' => 'لطفا شماره تلفن را وارد نماييد',
                'password.required' => 'لطفا رمز عبور را وارد نماييد',
            ]);

        if ($validator->fails()) {
            return $this->sendError('خطا اعتبارسنجی', $validator->errors());
        }

        if(Auth::attempt(['phone' => $request['phone'], 'password' => $request['password']])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('register')->accessToken;
            $success['name'] =  $user['first_name'].' '.$user['last_name'];
            return $this->sendResponse($success, 'ورود با موفقیت انجام شد');
        }
        else {
            return $this->sendError('کاربر یافت نشد', ['error' => 'کاربر یافت نشد']);
        }
    }

    public function changeProfile(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if(isset($user)) {
            $id = $user['id'];
            if (isset($request->first_name))
                $user['first_name'] = $request->first_name;
            if (isset($request->last_name))
                $user['last_name'] = $request->last_name;
            if (isset($request->new_phone))
                $user['phone'] = $request->new_phone;
            $edit = User::whereId($id)->update([
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'phone' => $user['phone']
            ]);
            return $this->sendResponse($user, 'ویرایش با موفقیت انجام شد');
        }
        else {
            return $this->sendError('کاربر یافت نشد', ['error' => 'کاربر یافت نشد']);
        }
    }

    public function changePassword(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if(isset($user)) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password'
            ],
                [
                    'password.required' => 'لطفا رمز عبور جدید را وارد نماييد',
                    'password.min' => 'رمز عبور نبايد كمتر از 8 كاراكتر باشد',
                    'confirm_password.required' => 'لطفا تكرار رمز عبور را وارد نماييد',
                    'confirm_password.same' => 'رمز عبور مطابقت ندارد',
                ]);

            if($validator->fails()){
                return $this->sendError('خطا اعتبارسنجی', $validator->errors());
            }

            $user = User::whereId($user['id'])->update([
                'password' => bcrypt($request['password']),
            ]);
            return $this->sendResponse($user, 'تغییر رمز عبور با موفقیت انجام شد');
        }
        else {
            return $this->sendError('کاربر یافت نشد', ['error' => 'کاربر یافت نشد']);
        }
    }

    public function filter(Request $request)
    {
        if (isset($request['name']))
        {
            $doctor = Doctor::where('name','LIKE','%'.$request['name'].'%')->first();
            if (isset($doctor))
                return $this->sendResponse($doctor, 'دکتر '. $doctor['name'] .' یافت شد.');
            else
                return $this->sendError('پزشکی با نام '. $request['name'] .' یافت نشد.', 'پزشکی با نام '. $request['name'] .' یافت نشد.');
        }
        elseif (isset($request['nezam_number']))
        {
            $doctor = Doctor::where('nezam_number', $request['nezam_number'])->first();
            if (isset($doctor))
                return $this->sendResponse($doctor, 'دکتر '. $doctor['name'] .' با شماره نظام '. $request['nezam_number'] .' یافت شد.');
            else
                return $this->sendError('پزشکی با شماره نظام '. $request['nezam_number'] .' یافت نشد.', 'پزشکی با شماره نظام '. $request['nezam_number'] .' یافت نشد.');
        }
        elseif (isset($request['city']))
        {
            $doctor = Doctor::whereCity($request['city'])->get();
            if (isset($doctor))
                return $this->sendResponse($doctor, 'پزشک در شهر '. $request['city'] .' یافت شد.');
            else
                return $this->sendError('پزشکی در شهر '. $request['city'] .' یافت نشد.', 'پزشکی در شهر '. $request['city'] .' یافت نشد.');
        }
        elseif (isset($request['speciality']))
        {
            $doctor = Doctor::where('speciality','LIKE','%'.$request['speciality'].'%')->get();
            if (isset($doctor))
                return $this->sendResponse($doctor, 'پزشک با تخصص کاری '. $request['speciality'] .' یافت شد.');
            else
                return $this->sendError('پزشکی با تخصص کاری '. $request['speciality'] .' یافت نشد.', 'پزشکی با تخصص کاری '. $request['speciality'] .' یافت نشد.');
        }
        elseif (isset($request['degree']))
        {
            $doctor = Doctor::where('degree','LIKE','%'.$request['degree'].'%')->get();
            if (isset($doctor))
                return $this->sendResponse($doctor, 'پزشک با مدرک تحصیلی '. $request['degree'] .' یافت شد.');
            else
                return $this->sendError('پزشکی با مدرک تحصیلی '. $request['degree'] .' یافت نشد.', 'پزشکی با مدرک تحصیلی '. $request['degree'] .' یافت نشد.');
        }
    }
}
