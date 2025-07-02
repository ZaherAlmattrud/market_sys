<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

Route::get('/add', function () {



Log::info("start add products Now ^_*");

    for ($inch = 20; $inch <= 25; $inch++) {
        $meter = round($inch * 0.0254, 2);
        $name = "قشاط 13 * {$inch} / {$meter}";

        $product = [
            'name' => $name,
            'code' => '',
            'notes' => '',
            'invoice_id' => null,
            'category_id' => null,
            'date' => '',
            'price_in_dollar' => null,
            'price_in_sp' => $inch * 350,
            'profit' => null,
            'sell_in_sp' =>  $inch * 400,
            'sell_in_dollar' => null,
            'photo' => ''
        ];

        // إرسال المنتج إلى API
        $response = Http::post('http://192.168.1.114:8000/api/products', $product);

        if ($response->successful()) {
            echo "✅ تم إضافة: {$name}\n";
        } else {
            echo "❌ فشل في: {$name} - " . $response->status() . "\n";
        }
    }
});

Route::get('/', function () {
    return view('welcome');
});


Route::get('fix', function () {


    $res =   Account::with(['user'])->orderBy('id', 'desc')->get();

    $data =  $res->map(function ($item) {});
});
