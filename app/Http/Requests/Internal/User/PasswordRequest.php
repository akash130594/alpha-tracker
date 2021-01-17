<?php

namespace App\Http\Requests\Internal\User;

use Illuminate\Foundation\Http\FormRequest;
use Validator;

class PasswordRequest extends FormRequest
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
              'current_password' => 'required',
              'new_password' => 'required',
              're-confirm_password' => 'required|same:new password',
      ];

    }

}
