<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
	protected $fillable = ['title','authors' ,'description', 'published', 'file'];
}
