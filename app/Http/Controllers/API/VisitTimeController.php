<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitTimeController extends BaseController
{
    public function create_visit_time(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|numeric',
            'month' => 'required|numeric',
            'day' => 'required|numeric',
            'hour' => 'required|numeric',
            'doctor_name' => 'required|string',
        ],
            [
                'year.required' => 'لطفا سال را وارد نماييد',
                'year.numeric' => 'لطفا سال را به صورت عددی وارد نماييد',
                'month.required' => 'لطفا ماه را وارد نماييد',
                'month.numeric' => 'لطفا ماه را به صورت عددی وارد نماييد',
                'day.required' => 'لطفا روز را وارد نماييد',
                'day.numeric' => 'لطفا روز را به صورت عددی وارد نماييد',
                'hour.required' => 'لطفا ساعت را وارد نماييد',
                'hour.numeric' => 'لطفا ساعت را به صورت عددی وارد نماييد',
                'doctor_name.required' => 'لطفا نام پزشک را وارد نماييد',
                'doctor_name.string' => 'لطفا نام کامل پزشک را به صورت رشته وارد نماييد',
            ]);
    }
}
