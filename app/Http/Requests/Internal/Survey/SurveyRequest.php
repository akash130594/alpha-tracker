<?php

namespace App\Http\Requests\Internal\Survey;

use Illuminate\Foundation\Http\FormRequest;

class SurveyRequest extends FormRequest
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
            'vendor_survey_code' => 'sometimes|required_if:creation_type,manual',
            'collection_dedupe' => 'required',
            'dedupe_status' => 'required_if:collection_dedupe,1',
            'collection_ids' => 'required_if:collection_dedupe,1',
            'sy_excl_link_flag' => 'required',
        ];
    }
}
