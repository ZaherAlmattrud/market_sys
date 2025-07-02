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
        'notes',
        'invoice_id',
        'category_id',
        'price_in_sp',
        'price_in_dollar',
        'sell_in_sp',
        'sell_in_dollar',
        'code',
        'profit',
        'date',
        'photo'


    ];

   public function setCategoryIdAttribute($value)
    {
        $this->attributes['category_id'] = ($value === 'null' || $value === '') ? null : $value;
    }


    public function invoice()
    { // فاتورة شراء


    }

    public function category() {}
}
