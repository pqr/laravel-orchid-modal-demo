<?php

use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('contacts')->delete();
        
        \DB::table('contacts')->insert(array (
            
            array (
                'created_at' => '2020-06-17 16:11:58',
                'id' => 1,
                'name' => 'Petr',
                'updated_at' => '2020-06-17 16:11:58',
            ),
            
            array (
                'created_at' => '2020-06-17 16:12:06',
                'id' => 2,
                'name' => 'Sergey',
                'updated_at' => '2020-06-17 16:12:06',
            ),
        ));
        
        
    }
}