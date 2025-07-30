<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
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
            'photo_name' => $this->photo_name ?? $this->photo,  // استخدم الاسم المخزن، أو الحقل الأصلي
        ];
    }
}
