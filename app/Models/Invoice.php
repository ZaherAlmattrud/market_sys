<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Invoice extends Model  implements Auditable
{
    use HasFactory;
     use \OwenIt\Auditing\Auditable;


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

        return $this->belongsTo(Account::class, 'account_id', 'id');


    }

    public function user()
    {
    }
}
