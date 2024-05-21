<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //created_atのnullableを削除、DefaultとExtraを追加
        Schema::create('main_categories', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('id');
            $table->string('main_category', 60)->index()->comment('メインカテゴリー');
            $table->timestamp('created_at')->default(DB::raw('current_timestamp on update current_timestamp'))->comment('登録日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('main_categories');
    }
}
