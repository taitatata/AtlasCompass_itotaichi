<?php

use Illuminate\Database\Seeder;
use App\Models\Users\Subjects;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 国語、数学、英語を追加
        subjects::create([
            'subject'=>'国語',
        ]);

        subjects::create([
            'subject'=>'数学',
        ]);

        subjects::create([
            'subject'=>'英語',
        ]);
    }
}
