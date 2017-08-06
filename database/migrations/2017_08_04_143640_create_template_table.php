<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('模板标题');
            $table->string('description')->nullable()->comment('模板简述');
            $table->text('content')->nullable()->comment('文档模板内容');
            $table->integer('user_id', false, true)->nullable()->comment('创建用户ID');
            $table->tinyInteger('type')->default(2)->comment('模板类型：1-swagger 2-markdown');
            $table->tinyInteger('status')->default(1)->comment('模板状态: 1-正常；2-禁用');
            $table->tinyInteger('scope')->default(1)->comment('可用范围：1-全局可用；2-个人');
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
        Schema::dropIfExists('wz_templates');
    }
}
