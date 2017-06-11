<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
	protected $fillable = ['position','organization_id','type','user_id','start_date','end_date'];
}
