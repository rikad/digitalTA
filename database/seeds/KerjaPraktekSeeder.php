<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;


class KerjaPraktekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Membuat role admin
		$adminRole = new Role();
		$adminRole->name = "administration";
		$adminRole->display_name = "Tata Usaha";
		$adminRole->save();

		// Membuat sample admin
		$admin = new User();
		$admin->name = 'Riesa';
		$admin->username = 'riesa';
		$admin->password = bcrypt('riesa');
		$admin->save();
		$admin->attachRole($adminRole);

		// Membuat sample admin
		$admin = new User();
		$admin->name = 'Dahuri';
		$admin->username = 'dahuri';
		$admin->password = bcrypt('dahuri');
		$admin->save();
		$admin->attachRole($adminRole);

		// Membuat sample admin
		$admin = new User();
		$admin->name = 'Tanti';
		$admin->username = 'tanti';
		$admin->password = bcrypt('tanti');
		$admin->save();
		$admin->attachRole($adminRole);

    }
}
