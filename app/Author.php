<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
	protected $fillable = ['nip', 'prefix', 'name', 'suffix', 'birthdate', 'user_id' ];
}
