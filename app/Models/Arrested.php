<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arrested extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'total',
        'date',
        'notes',
        'account_id',
    ];



    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }
}
