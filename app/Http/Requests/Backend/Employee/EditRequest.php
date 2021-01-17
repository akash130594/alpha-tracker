<?php

namespace App\Http\Requests\Backend\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:employees,email,'.request()->id.',id',
            'dob' => 'required|date_format:Y-m-d',
            'doj' => 'required|date_format:Y-m-d',
            'salary' => 'required',
            'position' => 'required',
            'mobile_no' => 'required',
            'empid' => 'required|unique:employees,empid,'.request()->id.',id'
        ];
    }
}
