<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid', false, true)->nullable()->comment('上级页面ID');
            $table->string('title', 255)->comment('页面标题');
            $table->text('description')->nullable()->comment('页面描述');
            $table->longText('content')->comment('页面内容');
            $table->integer('project_id', false, true)->nullable()->comment('项目ID');
            $table->integer('user_id', false, true)->nullable()->comment('用户ID');
            $table->tinyInteger('type')->comment('页面内容');
            $table->tinyInteger('status')->comment('页面状态');
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
        Schema::dropIfExists('wz_pages');
    }
}
