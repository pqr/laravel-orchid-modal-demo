<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            
            array (
                'created_at' => '2020-06-02 13:06:57',
                'email' => 'admin@admin.com',
                'email_verified_at' => NULL,
                'id' => 1,
                'last_login' => NULL,
                'name' => 'admin',
                'password' => '$2y$10$Ofp3/F/8a13aXejuxPVbUuxPXQldMkwpMeaMmEXW7y9fv3jiUzhjS',
                'permissions' => '{"platform.index": true, "platform.systems.index": true, "platform.systems.roles": true, "platform.systems.users": true, "platform.systems.attachment": true}',
                'remember_token' => NULL,
                'updated_at' => '2020-06-02 13:06:57',
            ),
        ));
        
        
    }
}