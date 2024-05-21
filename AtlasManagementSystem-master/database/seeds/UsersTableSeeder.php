<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'over_name'=>'国語',
            'under_name'=>'教師',
            'over_name_kana'=>'コクゴ',
            'under_name_kana'=>'キョウシ',
            'mail_address'=>'kokugo@gmail.com',
            'sex'=>'1',
            'birth_day'=>'2024-01-01',//日付形式は「-」で繋げる
            'role'=>'1',//1で教師の権限付与
            'password'=> bcrypt('kokugokokugo'),//パスワードハッシュ化
        ]);

        User::create([
            'over_name'=>'生徒',
            'under_name'=>'1',
            'over_name_kana'=>'セイト',
            'under_name_kana'=>'イチ',
            'mail_address'=>'seito1@gmail.com',
            'sex'=>'1',
            'birth_day'=>'2024-01-01',//日付形式は「-」で繋げる
            'role'=>'2',//2で生徒の権限付与
            'password'=> bcrypt('seitoseito'),//パスワードハッシュ化
        ]);
    }
}
