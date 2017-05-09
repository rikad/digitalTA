<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
	protected $fillable = ['no','initial','prefix','name','phone','suffix','birthplace','birthdate','user_id'];
}
