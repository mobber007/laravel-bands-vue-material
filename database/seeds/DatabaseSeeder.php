<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//Set the amount of users/bands per user you want here
		$user_count = 10;
		$band_count = 15;
	
    	//create my account
    	\App\User::create([
    		'name' => 'Literacy Pro',
		    'email' => 'laravel.php.engineer@literacypro.com',
		    'password' => bcrypt('bandsapp'),
		    'remember_token' => str_random(10)
	    ]);

    	//create test data
	    factory(App\User::class, $user_count)
		    ->create()
		    ->each(function(App\User $user) use (&$band_count){

			    $user->bands()
			         ->saveMany(factory(App\Band::class, $band_count)
				         ->create(['user_id' => $user->id])
				         ->each(function(App\Band $band){

					         $band->albums()->saveMany(factory(App\Album::class, rand(2,6))->make());
				         })
			         );
		    });
    }
}
