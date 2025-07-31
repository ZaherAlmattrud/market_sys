<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //



        $currencies = [
            [

                'code' => 'USD',
                'name' => 'دولار أمريكي',
                'value' => 10000,
                'date' => now(),

            ],
            [

                'code' => 'SYP',
                'name' => 'ليرة سورية',
                'value' => 1,
                'date' => now(),


            ],
            [

                'code' => 'TRY',
                'name' => 'ليرة تركية',
                'value' => 248,
                'date' => now(),


            ]
        ];

        foreach ($currencies as $currence) {

            Currency::firstOrCreate($currence);
        }
    }
}
