<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;


    public $table = 'purchases';
    
    public $timestamps = false;

    protected $fillable = [

        'account_id',
        'total',
        'date',
        'photo',
        'currency'
         
    ];




    public function account()
    {
    }

    public function user()
    {
    }
}
