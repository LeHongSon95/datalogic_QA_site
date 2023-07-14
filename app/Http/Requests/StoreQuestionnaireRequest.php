<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionnaireRequest extends FormRequest
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
        $rule = ['status' => 'required'];
        if (request()->get('status') == config('constants.questionnaire_status.not_resolved')) {
            $rule['content'] = 'required';
        }
        return $rule;
    }
}
