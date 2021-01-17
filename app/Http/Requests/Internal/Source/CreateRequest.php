<?php

namespace App\Http\Requests\Internal\Source;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CreateRequest extends FormRequest
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
            'name' => ['required','max:191'],
            'code' => ['required','max:6', Rule::unique('sources')],
            'vvars' => ['required','max:191'],
            'status' => ['required'],
        ];
    }
}
