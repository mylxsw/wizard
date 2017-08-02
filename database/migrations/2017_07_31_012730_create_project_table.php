<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('项目名称');
            $table->text('description')->nullable()->comment('项目描述');
            $table->tinyInteger('visibility', false, true)->comment('可见性');
            $table->integer('user_id', false, true)->comment('创建用户ID');
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
        Schema::dropIfExists('wz_projects');
    }
}
