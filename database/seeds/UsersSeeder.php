<?php

use Illuminate\Database\Seeder;

use App\Role;
use App\User;
use App\Profile;
use App\Country;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		// Membuat role admin
		$adminRole = new Role();
		$adminRole->name = "admin";
		$adminRole->display_name = "Admin";
		$adminRole->save();
		// Membuat role member
		$memberRole = new Role();
		$memberRole->name = "author";
		$memberRole->display_name = "Author";
		$memberRole->save();
		// Membuat sample admin
		$admin = new User();
		$admin->name = 'admin';
		$admin->email = 'admin@gmail.com';
		$admin->password = bcrypt('rahasia');
		$admin->save();
		$admin->attachRole($adminRole);
		// Membuat sample member
		$member = new User();
		$member->name = "member";
		$member->email = 'member@gmail.com';
		$member->password = bcrypt('rahasia');
		$member->save();
		$member->attachRole($memberRole);

    }
}
