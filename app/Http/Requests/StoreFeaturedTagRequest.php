<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeaturedTagRequest extends FormRequest
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
            'featured_tag_name' => 'required|max:30',
        ];
    }
    public function messages()
    {
        return  [
            'featured_tag_name.max' => 'キーワードは30文字以下入力してください。', 
            'featured_tag_name.required' => 'キーワード項目は必須です。',

        ];
    }
}
