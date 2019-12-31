<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_widgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->comment('控件标题');
            $table->string('ref_id', 64)->comment('UUID，用于唯一标识控件');
            $table->tinyInteger('type', false, true)->comment('控件类型: 1-思维导图');
            $table->text('description')->nullable()->comment('控件描述');
            $table->longText('content')->nullable()->comment('空间内容');

            $table->integer('user_id', false, true)->nullable()->comment('用户ID');
            $table->integer('operator_id', false, true)->nullable()->comment('操作用户ID');
            $table->timestamps();
            $table->softDeletes();

            $table->index('ref_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wz_widgets');
    }
}
