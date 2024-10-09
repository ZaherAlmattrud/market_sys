<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('fix',function(){


    $res =   Account::with(['user'])->orderBy('id', 'desc')->get();

    $data =  $res->map(function ($item) {


        


    });



});
