<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Arrested extends Model  implements Auditable
{
    use HasFactory;
     use \OwenIt\Auditing\Auditable;

    public $timestamps = false;

    protected $fillable = [
        'total',
        'date',
        'notes',
        'account_id',
        'currency'
    ];



    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }
}
