<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paid extends Model
{
    use HasFactory;

    protected $table = 'paids';
    protected $fillable = [

        'total',
        'date',
        'account_id',
        'notes',
    ];



    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function user()
    {
    }
}
