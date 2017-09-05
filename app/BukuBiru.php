<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BukuBiru extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'buku_biru';

    protected $fillable = [
        'group_id', 'tanggal_bimbingan', 'kegiatan', 'note', 'attachment'
    ];
}
