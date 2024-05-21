<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // created_atのnullableを削除、DefaultとExtraを追加
        Schema::create('post_comments', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('id');
            $table->integer('post_id')->comment('投稿のid');
            $table->integer('user_id')->comment('投稿した人のid');
            $table->string('comment')->comment('コメント');
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
        Schema::dropIfExists('post_comments');
    }
}
