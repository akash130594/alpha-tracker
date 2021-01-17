<?php

namespace App\Http\Requests\Internal\Project\ReviewLaunch;

use Illuminate\Foundation\Http\FormRequest;

class LaunchProjectRequest extends FormRequest
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
            'linktested' => 'required_if:skiptest,',
            'skiptest' => 'required_if:linktested,',
        ];
    }
}
