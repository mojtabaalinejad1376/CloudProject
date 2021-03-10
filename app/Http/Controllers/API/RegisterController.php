<?php

namespace App\Http\Controllers\API;

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
}
