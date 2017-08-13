<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_page_share', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 64)->comment('分享标识');
            $table->integer('project_id', false, true)->comment('项目ID');
            $table->integer('page_id', false, true)->comment('页面ID');
            $table->integer('user_id', false, true)->comment('分享人ID');
            $table->timestamp('expired_at')->nullable()->comment('过期时间');
            $table->timestamps();

            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wz_page_share');
    }
}
