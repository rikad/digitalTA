<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranscriptInfo extends Model
{
    //
    protected $table = 'tr_infos';

    protected $fillable = [
        'student_id','advisor_id','yudisium_date', 'graduation_date', 'final_exam'
    ];
}
