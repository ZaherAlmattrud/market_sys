<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'total',
        'description',
        'quantity',
        'price',
        'date',
    ];





    public function account()
    {
    }
}
