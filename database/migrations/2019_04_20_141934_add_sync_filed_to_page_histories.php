<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSyncFiledToPageHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wz_page_histories', function (Blueprint $table) {
            $table->string('sync_url')->nullable()->comment('文档同步地址：swagger专用');
            $table->timestamp('last_sync_at')->nullable()->comment('文档最后同步时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wz_page_histories', function (Blueprint $table) {
            //
        });
    }
}
