<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KpCorp extends Model
{
    //
    protected $table = 'kp_corps';

    protected $fillable = [
        'name', 'address', 'bidang', 'phone1', 'phone2', 'mail1', 'mail2', 'site', 'description'
    ];
}
