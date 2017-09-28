<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equivalency extends Model
{
    //
    protected $table = 'pc_equivalencies';

    protected $fillable = [
        'course2008_id', 'course2013_id'
    ];
}
