<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InvitationCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wz_invitation_code', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 128)->comment('注册邀请码')->unique();
            $table->dateTime('expired_at')->nullable()->comment('邀请码有效期限');
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
        Schema::dropIfExists('wz_invitation_code');
    }
}
