<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReserveSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // updated_atを追加、created_atのnullableを削除、DefaultとExtraを追加
        Schema::create('reserve_settings', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('id');
            $table->date('setting_reserve')->comment('開講日');
            $table->integer('setting_part')->comment('部');
            $table->integer('limit_users')->default(20)->comment('人数');
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
        Schema::dropIfExists('reserve_settings');
    }
}
