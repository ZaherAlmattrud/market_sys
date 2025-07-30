<?php



namespace App\DataTransferObjects;

use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\UploadedFile;

class CreateProductDto extends BaseDto
{
    public function __construct(
        public string $name,
        public ?string $code,
        public ?string $notes,
        public ?int $invoice_id,
        public ?int $category_id,
        public ?string $date,
        public ?float $price_in_dollar,
        public ?float $price_in_sp,
        public ?float $profit,
        public ?float $sell_in_sp,
        public ?float $sell_in_dollar,
         public ?UploadedFile $photo ,
         public ?string $photo_name = null

   ) {}

    public static function fromRequest(StoreProductRequest $request): self
    {
        $data = $request->validated();

        return new self(
            name: array_key_exists('name', $data) ? $data['name'] : null,
            code: array_key_exists('code', $data) ? $data['code'] : null,
            notes: array_key_exists('notes', $data) ? $data['notes'] : null,
            invoice_id: array_key_exists('invoice_id', $data) ? $data['invoice_id'] : null,
            category_id: array_key_exists('category_id', $data) ? $data['category_id'] : null,
            date: array_key_exists('date', $data) ? $data['date'] : null,
            price_in_dollar: array_key_exists('price_in_dollar', $data) ? $data['price_in_dollar'] : null,
            price_in_sp: array_key_exists('price_in_sp', $data) ? $data['price_in_sp'] : null,
            profit: array_key_exists('profit', $data) ? $data['profit'] : null,
            sell_in_sp: array_key_exists('sell_in_sp', $data) ? $data['sell_in_sp'] : null,
            sell_in_dollar: array_key_exists('sell_in_dollar', $data) ? $data['sell_in_dollar'] : null,
           photo: $request->hasFile('photo') ? $request->file('photo') : null
         );
    }

    public function toArray(): array
{
    return [
        'name' => $this->name,
        'code' => $this->code,
        'notes' => $this->notes,
        'invoice_id' => $this->invoice_id,
        'category_id' => $this->category_id,
        'date' => $this->date,
        'price_in_dollar' => $this->price_in_dollar,
        'price_in_sp' => $this->price_in_sp,
        'profit' => $this->profit,
        'sell_in_sp' => $this->sell_in_sp,
        'sell_in_dollar' => $this->sell_in_dollar,
        'photo' => $this->photo,
    ];
}

 public function computeMissingPrices(float $dollarValueNow): void
    {
        if ($this->price_in_dollar !== null && $this->price_in_sp === null) {
            $this->price_in_sp = $this->price_in_dollar * $dollarValueNow;
        } elseif ($this->price_in_sp !== null && $this->price_in_dollar === null) {
            $this->price_in_dollar = $this->price_in_sp / $dollarValueNow;
        }

        if ($this->sell_in_sp === null && $this->sell_in_dollar !== null) {
            $this->sell_in_sp = $this->sell_in_dollar * $dollarValueNow;
        } elseif ($this->sell_in_dollar === null && $this->sell_in_sp !== null) {
            $this->sell_in_dollar = $this->sell_in_sp / $dollarValueNow;
        }
    }

    public function calculateProfit(?float $discount = 0): void
    {
        if ($this->sell_in_dollar !== null && $this->price_in_dollar !== null) {
            $this->profit = $this->sell_in_dollar - ($this->price_in_dollar + ($this->price_in_dollar * $discount));
        } else {
            $this->profit = null;
        }
    }

}
