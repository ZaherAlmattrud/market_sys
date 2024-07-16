<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_type',
        'account_id',
        'total',
        'file',
        'file_name',
        'file_path',
        'file_url',
        'date',
        'num',
    ];




    public function account()
    {
    }

    public function user()
    {
    }
}
