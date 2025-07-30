<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\Implementations\ProductService;

class ServiceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ProductServiceInterface::class, ProductService::class);

        // سجل باقي الخدمات هنا بنفس الطريقة
    }

    public function boot()
    {
        //
    }
}
