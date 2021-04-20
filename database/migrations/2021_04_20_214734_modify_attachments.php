<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wz_attachments', function (Blueprint $table) {
            $table->string('preview_path', 255)->nullable()->comment('文件预览文件地址');
            $table->string('file_type', 20)->nullable()->comment('文件类型（扩展名）');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wz_attachments', function (Blueprint $table) {
            //
        });
    }
}
