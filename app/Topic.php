<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'dosen1_id', 'dosen2_id', 'period_id', 'is_taken', 'bobot', 'waktu', 'dana'
    ];
}
