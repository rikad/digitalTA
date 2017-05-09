<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
	protected $fillable = ['title', 'cover', 'description', 'published', 'tag', 'file'];
}
