<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Categories\SubCategory;
use Illuminate\Validation\Rule;

class SubCategoryRequest extends FormRequest
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
            'sub_category_name' => ['required', 'max:100', 'string'],
            'main_category_id' => ['required', 'exists:main_categories,id'],
        ];
    }

    public function messages()
    {
        return [
            'sub_category_name.required' => 'サブカテゴリーを入力してください',
            'sub_category_name.max' => 'サブカテゴリーは100文字以内で入力してください',
            'sub_category_name.string' => 'サブカテゴリーは文字列である必要があります',
            'main_category_id.required' => 'メインカテゴリーを選択してください',
            'main_category_id.exists' => '選択されたメインカテゴリーは存在しません',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('sub_category_name') && $this->has('main_category_id')) {
                $exists = SubCategory::where('sub_category', $this->input('sub_category_name'))
                    ->where('main_category_id', $this->input('main_category_id'))
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('sub_category_name', 'このメインカテゴリーに同じサブカテゴリー名が既に存在しています');
                }
            }
        });
    }
}
