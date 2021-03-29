<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectMenuControl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wz_projects', function (Blueprint $table) {
            $table->tinyInteger('catalog_fold_style')->default(0)->comment('目录展开样式：0-自动 1-全部展开 2-全部折叠');
            $table->tinyInteger('catalog_sort_style')->default(0)->comment('目录排序样式：0-目录优先 1-自由排序');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wz_projects', function (Blueprint $table) {
            $table->dropColumn(['catalog_fold_style', 'catalog_sort_style']);
        });
    }
}
