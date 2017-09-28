<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    protected $table = 'pc_courses';

    protected $fillable = [
        'no','curriculum_id','code','title', 'title_en', 'semester', 'rex', 'sch', 'mbs', 'et', 'ge', 'other'
    ];
}
