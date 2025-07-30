<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        $user =  Auth::user();
        return   $user ? $user->can('create_product') || $user->hasRole('SuperAdmin|Admin') : false;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'code' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'invoice_id' => ['required', 'integer', 'exists:purchases,id'],
            'category_id' => [ 'nullable', 'integer', 'exists:categories,id'],
            'date' => ['nullable', 'date'],
            'price_in_dollar' => ['nullable', 'numeric', 'min:0'],
            'price_in_sp' => ['nullable', 'numeric', 'min:0'],
            'profit' => ['nullable', 'numeric'],
            'sell_in_sp' => ['nullable', 'numeric', 'min:0'],
            'sell_in_dollar' => ['nullable', 'numeric', 'min:0'],
            'photo' => ['image', 'max:2048'],
        ];
    }

   public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $data = $validator->getData();

        $priceInDollar = $data['price_in_dollar'] ?? null;
        $priceInSp = $data['price_in_sp'] ?? null;
        $sellInDollar = $data['sell_in_dollar'] ?? null;
        $sellInSp = $data['sell_in_sp'] ?? null;

        // شرط السعر في الشراء لازم يكون موجود إما دولار أو ليرة
        if (empty($priceInDollar) && empty($priceInSp)) {
            $validator->errors()->add('price_in_dollar', 'يجب إدخال سعر الشراء بالدولار أو الليرة.');
            $validator->errors()->add('price_in_sp', 'يجب إدخال سعر الشراء بالدولار أو الليرة.');
        }

        // شرط السعر في البيع لازم يكون موجود إما دولار أو ليرة
        if (empty($sellInDollar) && empty($sellInSp)) {
            $validator->errors()->add('sell_in_dollar', 'يجب إدخال سعر البيع بالدولار أو الليرة حسب العملة التي ادخلتها بسعر الشراء');
            $validator->errors()->add('sell_in_sp', 'يجب إدخال سعر البيع بالدولار أو الليرة حسب العملة التي ادخلتها بسعر الشراء');
        }

        // التحقق من تطابق العملة بين سعر الشراء وسعر البيع
        if ($priceInDollar !== null && $priceInDollar !== '' && ($sellInSp !== null && $sellInSp !== '')) {
            $validator->errors()->add('sell_in_sp', 'يجب أن يكون سعر البيع بالدولار لأن سعر الشراء بالدولار.');
        }

        if ($priceInSp !== null && $priceInSp !== '' && ($sellInDollar !== null && $sellInDollar !== '')) {
            $validator->errors()->add('sell_in_dollar', 'يجب أن يكون سعر البيع بالليرة لأن سعر الشراء بالليرة.');
        }
    });
}



 

}
