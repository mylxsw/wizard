<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_page_score', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_id')->comment('页面 ID');
            $table->unsignedInteger('user_id')->comment('用户 ID');
            $table->tinyInteger('score_type', false, true)->comment('评分类型：');
            $table->index('page_id', 'idx_page_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wz_page_score');
    }
}
