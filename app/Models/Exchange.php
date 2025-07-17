<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Exchange extends Model  implements Auditable
{
    use HasFactory;
     use \OwenIt\Auditing\Auditable;

    public $timestamps = false;

    protected $table = 'exchange';

    protected $fillable = [
       'value' , 'name' , 'date','code'
    ];
}
