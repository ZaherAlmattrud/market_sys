<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $timestamps = false;
    protected $fillable = [

        'account_num',
        'user_id',
    ];




    public function accountDetails()
    {
        return $this->hasMany(AccountDetail::class, 'account_id', 'id');
    }

    public function invoices()
    {

        return $this->hasMany(Invoice::class, 'account_id', 'id');
    }

    public function paids()
    {
        return $this->hasMany(Paid::class, 'account_id', 'id');
    }

    public function arresteds()
    {

        return $this->hasMany(Arrested::class, 'account_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
