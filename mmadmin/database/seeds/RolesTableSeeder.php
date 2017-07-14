<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('roles')->insert([
            'name' => 'Super Admin',
            'description' => 'Super admin of the system',
        ]);
		
        DB::table('roles')->insert([
            'name' => 'CSA',
            'description' => 'Customer service assistant',
        ]);
		
		DB::table('roles')->insert([
            'name' => 'Public User',
            'description' => 'Public users or registrants',
        ]);
    }
}
