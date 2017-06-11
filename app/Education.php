<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations';

	protected $fillable = ['program_id','institution_id','country_id','user_id','start_date','end_date'];

	public function program() {
		return $this->belongsTo('App\Country','country_id');
	}
}
