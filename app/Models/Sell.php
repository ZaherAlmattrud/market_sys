<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Sell extends Model  implements Auditable
{
    use HasFactory;
     use \OwenIt\Auditing\Auditable;

    public $timestamps = false;

    protected $table = 'sells';
    
  

    protected $fillable = [
        'user_id',
        'total',
        'date',
        'is_paid',
        'notes',
        'currency'
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

     public function details()
    {
        return $this->hasMany(SellDetail::class);
    }
}
