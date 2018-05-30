<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_page_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_id')->comment('页面 ID');
            $table->unsignedInteger('tag_id')->comment('标签 ID');
            $table->index('page_id', 'idx_page_id');
            $table->index('tag_id', 'idx_tag_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wz_page_tag');
    }
}
