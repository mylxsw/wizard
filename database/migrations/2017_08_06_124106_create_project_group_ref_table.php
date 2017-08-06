<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectGroupRefTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_project_group_ref', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id', false, true)->comment('项目ID');
            $table->integer('group_id', false, true)->comment('组ID');
            $table->tinyInteger('privilege', false, true)->comment('组权限：1-wr/2-r');

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
        Schema::dropIfExists('wz_project_group_ref');
    }
}
