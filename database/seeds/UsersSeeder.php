<?php

use Illuminate\Database\Seeder;

use App\Role;
use App\User;

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
		$adminRole->display_name = "Administrator";
		$adminRole->save();

		// Membuat role member
		$profRole = new Role();
		$profRole->name = "professor";
		$profRole->display_name = "Professor";
		$profRole->save();

		// Membuat role lecture
		$lecturerRole = new Role();
		$lecturerRole->name = "lecture";
		$lecturerRole->display_name = "Lecture";
		$lecturerRole->save();

		// Membuat role academic assistant
		$academicAssistantRole = new Role();
		$academicAssistantRole->name = "academic_assistant";
		$academicAssistantRole->display_name = "Academic Assistant";
		$academicAssistantRole->save();

		// Membuat role assistant proffesor
		$profAssistantRole = new Role();
		$profAssistantRole->name = "assistant_proffesor";
		$profAssistantRole->display_name = "Assistant Professor";
		$profAssistantRole->save();

		// Membuat role asociate proffesor
		$associateProfRole = new Role();
		$associateProfRole->name = "associate_proffesor";
		$associateProfRole->display_name = "Associate Professor";
		$associateProfRole->save();

		// Membuat sample admin
		$admin = new User();
		$admin->name = 'admin';
		$admin->email = 'admin@gmail.com';
		$admin->password = bcrypt('rahasia');
		$admin->save();
		$admin->attachRole($adminRole);

		// Membuat sample member
		$member = new User();
		$member->name = "proffesor";
		$member->email = 'prof@gmail.com';
		$member->password = bcrypt('rahasia');
		$member->save();
		$member->attachRole($profRole);

		// Membuat sample member
		$member = new User();
		$member->name = "dosen";
		$member->email = 'dosen@gmail.com';
		$member->password = bcrypt('rahasia');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "rikad";
		$member->email = 'rikad@gmail.com';
		$member->password = bcrypt('rahasia');
		$member->save();
		$member->attachRole($academicAssistantRole);

		// Membuat sample member
		$member = new User();
		$member->name = "rafa";
		$member->email = 'rafa@gmail.com';
		$member->password = bcrypt('rahasia');
		$member->save();
		$member->attachRole($profAssistantRole);

		// Membuat sample member
		$member = new User();
		$member->name = "reza";
		$member->email = 'reza@gmail.com';
		$member->password = bcrypt('rahasia');
		$member->save();
		$member->attachRole($associateProfRole);

    }
}
