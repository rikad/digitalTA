<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['organization','address','parent_id','form_id'];
	public $timestamps = false;
}
