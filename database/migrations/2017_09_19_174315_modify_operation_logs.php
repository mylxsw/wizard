<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyOperationLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wz_operation_logs', function (Blueprint $table) {
            $table->integer('project_id', false, true)->nullable()->comment('关联的项目ID');
            $table->integer('page_id', false, true)->nullable()->comment('关联的文档ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
