<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transcript extends Model
{
    //
	protected $table = 'pc_transcripts';

    protected $fillable = [
        'student_id','course_id', 'year_taken', 'smt_taken' ,'grade_uni', 'grade_ps', 'is_transcripted'
    ];
}
