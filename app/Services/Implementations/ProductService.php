<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\Interfaces\ProductServiceInterface;
use  App\DataTransferObjects\BaseDto;
use  App\DataTransferObjects\CreateProductDto;
use  App\Helpers\helper;
use  App\Models\Invoice;
use  App\Models\Category;
use Illuminate\Http\UploadedFile;

class ProductService implements ProductServiceInterface
{
    public function __construct(

        protected ProductRepositoryInterface $productRepo
    ) {}

    public function index(array $filters = [], int $perPage = 15)
    {

        return $this->productRepo->index($filters, $perPage);
    }

    public function show(int $id)
    {

        return $this->productRepo->show($id);
    }

    public function store(BaseDto $data)
    {


        if (! $data instanceof CreateProductDto) {
            throw new \InvalidArgumentException('Expected CreateProductDto');
        }

        $dollarValueNow = Helper::getDollarValue();

        // نكمل حساب الأسعار المفقودة
        $data->computeMissingPrices($dollarValueNow);

        // نجيب الخصم حسب التصنيف
        $discount = 0;
        if ($data->category_id) {
            $discount = Category::find($data->category_id)?->descount ?? 0;
        }

        // نحسب الربح
        $data->calculateProfit($discount);

        // نحدد التاريخ
        if ($data->invoice_id) {
            $data->date = Invoice::find($data->invoice_id)?->date ?? now()->toDateString();
        } else {
            $data->date = now()->toDateString();
        }

        // نرفع الصورة لو موجودة
        if ($data->photo instanceof UploadedFile) {
            $data->photo_name = helper::uploadImage($data->photo, 'products');
        }

        return $this->productRepo->store($data);
    }

    public function update(BaseDto $data, $id)
    {

        return $this->productRepo->update($data, $id);
    }

    public function destroy(int $id)
    {

        return $this->productRepo->destroy($id);
    }
}
