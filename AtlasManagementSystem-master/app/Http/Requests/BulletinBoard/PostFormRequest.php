<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
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
            'post_title' => ['required', 'max:100', 'string'],
            'post_body' => ['required', 'max:5000','string'],
        ];
    }

    public function messages(){
        return [
            'post_title.required' => 'タイトルは必須です',
            'post_title.max' => 'タイトルは100文字以内で入力してください',
            'post_title.string' => 'タイトルは文字列で入力してください',
            'post_body.required' => '投稿内容は必須です',
            'post_body.max' => '投稿内容は5000文字以内で入力してください',
            'post_body.string' => '投稿内容は文字列で入力してください',
        ];
    }
}
