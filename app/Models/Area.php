<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Area extends Model  implements Auditable
{
    use HasFactory;
     use \OwenIt\Auditing\Auditable;

     public $timestamps = false;

    protected $fillable = [
        'name',
       
    ];


    public function users(){

        return $this->hasMany(User::class, 'area_id', 'id');

    }
}
