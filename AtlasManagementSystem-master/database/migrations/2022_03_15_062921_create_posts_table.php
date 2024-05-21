<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // updated_atを追加、created_atのnullableを削除、DefaultとExtraを追加
        Schema::create('posts', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('id');
            $table->integer('user_id')->comment('ユーザーid');
            $table->string('post_title', 191)->index()->comment('タイトル');
            $table->string('post', 191)->index()->comment('投稿内容');
            $table->timestamp('created_at')->default(DB::raw('current_timestamp on update current_timestamp'))->comment('登録日時');
            $table->timestamp('updated_at')->default(DB::raw('current_timestamp on update current_timestamp'))->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
