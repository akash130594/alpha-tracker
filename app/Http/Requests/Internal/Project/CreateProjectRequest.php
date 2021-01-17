<?php

namespace App\Http\Requests\Internal\Project;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
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
            'client_link' => 'sometimes|required_if:unique_ids_flag,0',
            'unique_ids_file' => 'sometimes|required_if:unique_ids_flag,1',
            'end_date' => 'required',
            'cpi' => 'required',
            'quota' => 'required|integer',
            'language_id' => 'required',
            'dedupe.*' => 'sometimes|required_if:survey_dedupe_flag,1',
        ];
    }
}
