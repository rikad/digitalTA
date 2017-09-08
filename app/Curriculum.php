<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    //
    protected $table = 'pc_curricula';

    protected $fillable = [
        'program_id','title', 'description'
    ];
}
