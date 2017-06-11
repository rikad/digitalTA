<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
	protected $fillable = ['title', 'user_id', 'start_date', 'end_date'];
}
