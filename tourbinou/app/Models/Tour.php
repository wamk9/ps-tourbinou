<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'hour_id',
        'destination_id',
        'description'
    ];
}
