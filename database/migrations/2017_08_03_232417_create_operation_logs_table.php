<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_operation_logs', function (Blueprint $table) {
//            $table->engine = 'ARCHIVE';

            $table->increments('id');
            $table->integer('user_id', false, true)->nullable()->comment('操作用户ID');
            $table->string('message')->nullable()->comment('日志消息内容');
            $table->text('context')->nullable()->comment('记录日志时候的上下文信息');
            $table->timestamp('created_at')->useCurrent()->comment('创建时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wz_operation_logs');
    }
}
