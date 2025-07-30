<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Implementations\ProductRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        // سجل باقي الريبوز هنا بنفس الطريقة
    }

    public function boot()
    {
        //
    }
}
