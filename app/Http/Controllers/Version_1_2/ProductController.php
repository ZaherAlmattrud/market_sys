<?php

namespace App\Http\Controllers\Version_1_2;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;
use App\DataTransferObjects\CreateProductDto;
use App\Services\Interfaces\ProductServiceInterface;
use App\Helpers\ApiResponse;
use App\Enums\StatusCode;
use Illuminate\Database\Eloquent\Model;


class ProductController extends Controller
{



    public function __construct(

        protected ProductServiceInterface $productServ
    ) {}




    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        //

        try {

            $productDTO = CreateProductDTO::fromRequest($request);
            $product = $this->productServ->store($productDTO);
            return ApiResponse::success($product, 'تم إنشاء المنتج بنجاح', StatusCode::CREATED);
      
        } catch (\Throwable $e) {

            return ApiResponse::error('حدث خطأ غير متوقع', StatusCode::INTERNAL_ERROR, [
                'خطأ' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
