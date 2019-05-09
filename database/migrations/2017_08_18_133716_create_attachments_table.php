<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('附件名称');
            $table->string('path')->comment('存储路径');
            $table->integer('user_id', false, true)->comment('上传人ID');
            $table->integer('page_id', false, true)->nullable()->comment('附件对应的文档ID');
            $table->integer('project_id', false, true)->nullable()->comment('附件对应的项目ID');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wz_attachments');
    }
}
