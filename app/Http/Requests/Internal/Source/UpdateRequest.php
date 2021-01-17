<?php

namespace App\Http\Requests\Internal\Source;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'source_type_id' => ['required'],
            'name' =>['required','max:191'],
            'code' => ['required','max:6'],
            'vvars' => ['required','max:191'],
        ];
    }
}
