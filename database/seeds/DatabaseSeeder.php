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
        // $this->call(UsersTableSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(KerjaPraktekSeeder::class);
        //$this->call(OrganizationsSeeder::class);
	//$this->call(Countries::class);
    }
}
