<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'nezam_number' => 'required|numeric',
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctor $doctor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        //
    }
}
