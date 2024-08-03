<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'price',
        'notes',
        'sell',
        'img',
        'invoice_id',
        'category_id',
        'file_url',
        'file_name',
        'file_path',

    ];




    public function invoice()
    { // فاتورة شراء


    }

    public function category()
    {
    }
}
