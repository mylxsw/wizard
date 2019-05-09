<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_page_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id', false, true)->comment('文档ID');
            $table->integer('pid', false, true)->nullable()->comment('上级页面ID');
            $table->string('title')->nullable()->comment('页面标题');
            $table->text('description')->nullable()->comment('页面描述');
            $table->longText('content')->nullable()->comment('页面内容');
            $table->integer('project_id', false, true)->nullable()->comment('项目ID');
            $table->tinyInteger('type')->nullable()->comment('页面内容');
            $table->tinyInteger('status')->nullable()->comment('页面状态');
            $table->integer('user_id', false, true)->nullable()->comment('用户ID');
            $table->integer('operator_id', false, true)->nullable()->comment('操作用户ID');
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
        Schema::dropIfExists('wz_page_histories');
    }
}
