<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    //


     public $timestamps = false;
    
    protected $fillable = [

        'message',
        'trace',
        'file',
        'line',
        'type',
        'user',
        'date'

    ];
}
