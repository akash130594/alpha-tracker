<?php

namespace App\Http\Requests\Internal\Report;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
            'char' => 'max:3',
            'project_char' => 'max:4',
            'active_archive' => 'required',
        ];
    }
}
