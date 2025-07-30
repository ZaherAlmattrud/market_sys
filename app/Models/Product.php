<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Product extends Model  implements Auditable
{
    use HasFactory;
     use \OwenIt\Auditing\Auditable;

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
        'photo',
          'photo_name'


    ];

   public function setCategoryIdAttribute($value)
    {
        $this->attributes['category_id'] = ($value === 'null' || $value === '') ? null : $value;
    }

     public function setPriceInSpAttribute($value)
    {
        $this->attributes['price_in_sp'] = ($value === 'null' || $value === '') ? null : $value;
    }

      public function setPriceInDollarAttribute($value)
    {
        $this->attributes['price_in_dollar'] = ($value === 'null' || $value === '') ? null : $value;
    }

      public function setSellInSpAttribute($value)
    {
        $this->attributes['sell_in_sp'] = ($value === 'null' || $value === '') ? null : $value;
    }
      public function setSellInDollarAttribute($value)
    {
        $this->attributes['sell_in_dollar'] = ($value === 'null' || $value === '') ? null : $value;
    }


    public function invoice()
    { // فاتورة شراء


    }

    public function category() {}
}
