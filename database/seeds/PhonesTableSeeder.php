<?php

use Illuminate\Database\Seeder;

class PhonesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('phones')->delete();
        
        \DB::table('phones')->insert(array (
            
            array (
                'contact_id' => 1,
                'created_at' => '2020-06-17 16:12:20',
                'description' => 'Work',
                'id' => 220,
                'number' => '12345',
                'updated_at' => '2020-06-17 16:12:20',
            ),
            
            array (
                'contact_id' => 1,
                'created_at' => '2020-06-17 16:12:53',
                'description' => 'Home',
                'id' => 221,
                'number' => '666544',
                'updated_at' => '2020-06-17 16:12:53',
            ),
        ));
        
        
    }
}