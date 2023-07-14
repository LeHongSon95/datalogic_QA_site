<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
        $rules = [
           
        ];
        if (request()->has('question_characters')) {
            $rules['question_characters'] = 'numeric|required|max:255';
        }
        if (request()->has('answer_characters')) {
            $rules['answer_characters'] = 'numeric|required|max:4294967295';
        }
        if (request()->has('number_recently_viewed_keywords')) {
            $rules['number_recently_viewed_keywords'] = 'numeric|required|max:25';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'question_characters.max' => '質問は255文字を超えてはいけない',
            'question_characters.numeric' => '質問入力フォームの文字数目は必須です。',
            'question_characters.required' => '質問入力フォームの文字数目は必須です。',
            
            'answer_characters.max' => '回答には 4294967295文字を超えてはいけない',
            'answer_characters.numeric' => '回答入力フォームの文字数目は必須です。',
            'answer_characters.required' => '回答入力フォームの文字数目は必須です。',

            'number_recently_viewed_keywords.max' => '最近見たキーワードには25文字を超えてはいけない',
            'number_recently_viewed_keywords.numeric' => '最近見たキーワードの数目は必須です。',
            'number_recently_viewed_keywords.required' => '最近見たキーワードの数目は必須です。',
            
        ];
       
    }
}
