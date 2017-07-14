<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name' => 'Gadget Geek',
            'description' => 'Gadgets for all',
            'thankyoumessage' => 'Hi Gadget Geek, thank you for signing up!',
        ]);
		
		DB::table('categories')->insert([
            'name' => 'Beauty Babe',
            'description' => 'Beauty products for all',
            'thankyoumessage' => 'Hi Beauty Babe, thank you for signing up!',
        ]);
		
		DB::table('categories')->insert([
            'name' => 'Fashionista',
            'description' => 'Fashion style for all',
            'thankyoumessage' => 'Hi Fashionista, thank you for signing up!',
        ]);

		DB::table('categories')->insert([
            'name' => 'Foodie',
            'description' => 'Food stuff for all',
            'thankyoumessage' => 'Hi Foodie, thank you for signing up!',
        ]);

		DB::table('categories')->insert([
            'name' => 'Fun Seeker',
            'description' => 'Fun things for all',
            'thankyoumessage' => 'Hi Fun Seeker, thank you for signing up!',
        ]);
    }
}
