<?php

namespace App\Http\Requests\Internal\Project\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class VendorManagementRequest extends FormRequest
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
            'quota' => 'required|integer',
        ];
    }
}
