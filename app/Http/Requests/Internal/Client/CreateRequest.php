<?php

namespace App\Http\Requests\Internal\Client;

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
            'name' => 'required|string|max:191',
            'code' => ['required','string','max:6',Rule::unique('clients')],
            'cvars' => 'required|string|max:191',
            'status' => ['required'],

        ];
    }
}
