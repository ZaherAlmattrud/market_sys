<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Currency extends Model  implements Auditable
{
    use HasFactory;
     use \OwenIt\Auditing\Auditable;

    public $timestamps = false;

    protected $table = 'currencies';

    protected $fillable = [
       'value' , 'name' , 'date','code'
    ];
}
