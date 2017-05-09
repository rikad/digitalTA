<?php

use Illuminate\Database\Seeder;
use App\Form;
use App\Organization;

class OrganizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Form::create(['form' => 'institution','description' => 'Institution, university or institute']);
        Form::create(['form' => 'faculty','description' => 'faculty ']);
        Form::create(['form' => 'program','description' => 'study program']);

        Organization::create(['organization' => 'Bandung Institute of Technology','address' => 'ganesa','parent_id' => 0,'form_id' => 1]);
        Organization::create(['organization' => 'Faculty of Industrial Technology','address' => 'ganesa','parent_id' => 1,'form_id' => 2]);
        Organization::create(['organization' => 'Engineering Physics','address' => 'ganesa','parent_id' => 3,'form_id' => 2]);
    }
}
