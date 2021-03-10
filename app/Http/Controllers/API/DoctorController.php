<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorController extends BaseController
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'nezam_number' => 'required|numeric|unique:App\Models\Doctor',
            'city' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|numeric',
            'speciality' => 'required|string',
            'degree' => 'required|string'
        ],
            [
                'name.required' => 'لطفا نام را وارد نماييد',
                'name.string' => 'لطفا نام را به صورت رشته وارد نماييد',
                'nezam_number.required' => 'لطفا شماره نظام را وارد نماييد',
                'nezam_number.numeric' => 'لطفا شماره نظام را به صورت عددی وارد نماييد',
                'nezam_number.unique' => 'شماره نظام قبلا ثبت شده است',
                'city.required' => 'لطفا شهر را وارد نماييد',
                'city.string' => 'لطفا شهر را به صورت رشته وارد نماييد',
                'address.required' => 'لطفا آدرس را وارد نماييد',
                'address.string' => 'لطفا آدرس را به صورت رشته وارد نماييد',
                'phone.required' => 'لطفا شماره تلفن را وارد نماييد',
                'phone.numeric' => 'لطفا شماره تلفن را به صورت عددی وارد نماييد',
                'speciality.required' => 'لطفا تخصص کاری را وارد نماييد',
                'speciality.string' => 'لطفا تحصص کاری را به صورت رشته وارد نماييد',
                'degree.required' => 'لطفا مدرک تحصیلی را وارد نماييد',
                'degree.string' => 'لطفا مدرک تحصیلی را به صورت رشته وارد نماييد',
            ]);

        if ($validator->fails()) {
            return $this->sendError('خطا اعتبارسنجی', $validator->errors());
        }

        $doctor = Doctor::create($request->all());
        return $this->sendResponse($doctor, 'دکتر '. $doctor['name'] .' ثبت شد');
    }

    public function filter(Request $request)
    {
        if (isset($request['name']))
        {
            $doctor = Doctor::whereName($request['name'])->first();
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
            $doctor = Doctor::whereSpeciality($request['speciality'])->get();
            if (isset($doctor))
                return $this->sendResponse($doctor, 'پزشک با تخصص کاری '. $request['speciality'] .' یافت شد.');
            else
                return $this->sendError('پزشکی با تخصص کاری '. $request['speciality'] .' یافت نشد.', 'پزشکی با تخصص کاری '. $request['speciality'] .' یافت نشد.');
        }
    }
}
