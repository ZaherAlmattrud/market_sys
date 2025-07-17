<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserType extends Model implements Auditable
{
    use HasFactory;
     use \OwenIt\Auditing\Auditable;


    protected $table = 'usertypes';


    public $timestamps = false;  

    protected $fillable = [
        'type_name',
    ];


    public function users()
    {

        return $this->hasMany(User::class, 'user_type', 'id');
    }
}
