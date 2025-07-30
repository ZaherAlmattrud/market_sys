<?php

namespace App\Repositories\Implementations;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use  App\DataTransferObjects\BaseDto;
use  App\Http\Resources\ProductResource;
 



class ProductRepository implements ProductRepositoryInterface
{


    public function index(array $filters = [], int $perPage = 15) {

    }

    public function show(int $id) {}

    public function store(BaseDto $data) {


         
          $product = Product::create((array) $data);

          $data =  new ProductResource($product);


          return $data ;

    }

    public function update(BaseDto $data, $id) {}

    public function destroy(int $id) {}

    public function getDropdownList() {}
}
