<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //created_atのnullableを削除、DefaultとExtraを追加
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('id');
            $table->integer('main_category_id')->index()->comment('メインカテゴリーid');
            $table->string('sub_category', 60)->index()->comment('サブカテゴリー');
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
        Schema::dropIfExists('sub_categories');
    }
}
