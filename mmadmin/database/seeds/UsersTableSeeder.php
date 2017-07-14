<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'fuad@digitalsymphony.it',
            'password' => 'melawatimall2017',
            'enabled' => 1,
        ]);
    }
}
