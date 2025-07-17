<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
     

     use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     public $timestamps = false;
    protected $fillable = [
        'user_name',
        'user_type',
        'area_id',
        'account_id',
        'number_in_book',
        'mobile',
        'password'

    ];




    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];


    public function account()
    {
        // return $this->hasOne(Account::class, 'account_id', 'id');

        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function userType()
    {

        return $this->belongsTo(UserType::class, 'user_type', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }


     public function sells(){

          return $this->hasMany(Sell::class, 'user_id', 'id');

        
    }
}
