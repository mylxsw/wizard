<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id', false, true)->comment('页面ID');
            $table->integer('user_id', false, true)->comment('评论人');
            $table->text('content')->nullable()->comment('评论内容');
            $table->integer('reply_to_id', false, true)->comment('回复的ID');
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
        Schema::dropIfExists('wz_comments');
    }
}
