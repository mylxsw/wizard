<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectCatalogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_project_catalogs', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 100)->comment('项目目录名称');
            $table->integer('sort_level')->default(1000)->comment('排序，排序值越大越靠后');
            $table->integer('user_id', false, true)->comment('创建用户ID');

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
        Schema::dropIfExists('wz_project_catalogs');
    }
}
