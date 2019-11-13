<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShowInHomeToCatalogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wz_project_catalogs', function (Blueprint $table) {
            $table->tinyInteger('show_in_home', false, false)->default(1)->comment('是否在首页展示');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wz_project_catalogs', function (Blueprint $table) {
            //
        });
    }
}
