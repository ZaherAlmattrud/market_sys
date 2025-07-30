<?php


namespace App\Helpers;

use App\Models\Currency;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class Helper
{
    public static function getDollarValue(): float
    {
        return Currency::where('code', 'USD')->value('value') ?? 0;
    }

    public static function getCurrencyValue(string $code): float
    {
        return Currency::where('code', $code)->value('value') ?? 0;
    }

    public static function uploadImage(UploadedFile $file,  string $entity = 'product'): string
    {
        $folder = 'uploads/' . strtolower($entity);
        $fileName = time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($folder), $fileName);

        return $folder . '/' . $fileName;
    }
}
