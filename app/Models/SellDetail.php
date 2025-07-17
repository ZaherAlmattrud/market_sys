<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SellDetail extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;




    public $timestamps = false;

    protected $fillable = [
        'sell_id',
        'total',
        'name',
        'quantity',
        'sell',
        'date',

    ];
}
