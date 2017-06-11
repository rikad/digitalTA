<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
	protected $fillable = ['title', 'cover', 'description', 'published', 'file'];
}
