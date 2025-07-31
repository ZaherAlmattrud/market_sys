<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    //


      public $table = 'error_logs';

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
