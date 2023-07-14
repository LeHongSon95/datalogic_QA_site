<?php

namespace App\Http\Requests;

use App\Models\Setting;
use App\Rules\MaxLengthRule;
use App\Rules\QuestionItemNotNull;
use App\Rules\UniqueRule;
use Illuminate\Foundation\Http\FormRequest;

class QARequest extends FormRequest
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
        $maxLengthQuestion = 255;
        $maxLengthAnswer = 4294967295;
        $setting = Setting::first();
        if ($setting) {
            if($setting->question_characters) {
                $maxLengthQuestion = $setting->question_characters;
            }

            if($setting->answer_characters) {
                $maxLengthAnswer = $setting->answer_characters;
            }
        }
        $question = $this->route('question');
        return [
            'questions' => [new QuestionItemNotNull(), new MaxLengthRule($maxLengthQuestion), new UniqueRule($question)],
            'answer' => ['required', new MaxLengthRule($maxLengthAnswer)],
            'hashtag' => 'nullable|array|min:1',
            'categories' => 'required|array|min:1',
            'note' => ['nullable', new MaxLengthRule(100)],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'questions' => '質問',
            'answer' => '回答',
            'hashtag' => 'ハッシュタグ',
            'categories' => 'カテゴリー',
            'note' => 'メモに',
        ];
    }
}
