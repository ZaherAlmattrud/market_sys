<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellDetail extends Model
{
    use HasFactory;

  

    public $timestamps = false;

    protected $fillable = [
        'sell_id',
        'total',
        'description',
        'quantity',
        'price',
        'date',
        
    ];
}
