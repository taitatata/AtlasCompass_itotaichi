<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;


class RegisterFormRequest extends FormRequest
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
            'over_name' => ['required','string','max:10'],
            'under_name' => ['required','string','max:10'],
            'over_name_kana' => ['required','string','regex:/^[ァ-ヶー]+$/u','max:30'],
            'under_name_kana' => ['required','string','regex:/^[ァ-ヶー]+$/u','max:30'],
            'mail_address' => ['required','email','unique:users,mail_address','max:100'],
            'sex' => ['required'],
            'old_year' => ['required'],
            'old_month' => ['required'],
            'old_day' => ['required'],
            'role' => ['required'],
            'password' => ['required','min:8','max:30','confirmed'],
        ];
    }

    //追記
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $year = $this->input('old_year');
            $month = $this->input('old_month');
            $day = $this->input('old_day');

            // Check if the year, month, and day are integers
            if (!is_numeric($year) || !is_numeric($month) || !is_numeric($day)) {
                $validator->errors()->add('old_day', '年、月、日を選択してください。');
                return;
            }

            // Convert to integers
            $year = intval($year);
            $month = intval($month);
            $day = intval($day);

            // Check if the date is valid
            if (!checkdate($month, $day, $year)) {
                $validator->errors()->add('old_day', '正確な日付を入力してください。');
            } else {
                $date = Carbon::createFromDate($year, $month, $day);
                $start_date = Carbon::create(2000, 1, 1);
                $end_date = Carbon::now();

                // Check if the date is between 2000-01-01 and today
                if ($date->lt($start_date) || $date->gt($end_date)) {
                    $validator->errors()->add('old_day', '2000年1月1日から今日までの日付を入力してください。');
                }
            }
        });
    }

    public function messages(){
        return [
            'over_name.required' => '姓を入力してください',
            'over_name.string' => '姓は文字列である必要があります',
            'over_name.max' => '姓は10文字以内で入力してください',
            'under_name.required' => '名を入力してください',
            'under_name.string' => '名は文字列である必要があります',
            'under_name.max' => '名は10文字以内で入力してください',
            'over_name_kana.required' => 'セイを入力してください',
            'over_name_kana.string' => 'セイは文字列である必要があります',
            'over_name_kana.regex' => 'セイはカタカナで入力してください',
            'over_name_kana.max' => 'セイは10文字以内で入力してください',
            'under_name_kana.required' => 'メイを入力してください',
            'under_name_kana.string' => 'メイは文字列である必要があります',
            'under_name_kana.regex' => 'メイはカタカナで入力してください',
            'under_name_kana.max' => 'メイは10文字以内で入力してください',
            'mail_address.required' => 'メールアドレスを入力してください',
            'mail_address.email' => 'メールアドレスの形式で入力してください',
            'mail_address.unique' => '入力されたメールアドレスは既に登録されています',
            'mail_address.max' => 'メールアドレスは100文字以内で入力してください',
            'sex.required' => '性別を選択してください',
            'old_year.required' => '生年月日の年を選択してください',
            'old_year.between' => '生年月日の年は2000年から今年まで範囲で選択してください',
            'old_month.required' => '生年月日の月を選択してください',
            'old_month.between' => '生年月日の月は1月から12月までの範囲で選択してください',
            'old_day.required' => '生年月日の日を選択してください',
            'old_day.between' => '生年月日の日は1日から31日までの範囲で選択してください',
            'role.required' => '役職を選択してください',
            'password.required' => 'パスワードは必須です',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.max' => 'パスワードは30文字以内で入力してください',
            'password.confirmed' => 'パスワードが確認用パスワードと一致していません',
        ];
    }
}
