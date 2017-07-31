<?php

use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Repositories\Project::create([
            'name'        => '测试平台',
            'description' => '测试平台描述',
            'visibility'  => \App\Repositories\Project::VISIBILITY_PUBLIC,
            'user_id'     => 1,
        ]);
    }
}
