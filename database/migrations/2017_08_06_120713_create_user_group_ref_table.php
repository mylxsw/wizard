<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGroupRefTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_user_group_ref', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true)->comment('用户ID');
            $table->integer('group_id', false, true)->comment('组ID');
            $table->tinyInteger('privilege', false, true)->nullable()->comment('组权限，预留');

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
        Schema::dropIfExists('wz_user_group_ref');
    }
}
