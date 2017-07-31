<?php

use Illuminate\Database\Seeder;

use \App\Repositories\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id'         => 1,
            'name'       => 'wizard',
            'email'      => 'wizard@aicode.cc',
            'password'   => bcrypt('wizard'),
        ]);
    }
}
