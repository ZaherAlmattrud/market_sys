<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserType;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
           $types = ['زبون', 'تاجر', 'مستخدم'];

        foreach ($types as $typeName) {

           UserType::firstOrCreate(['type_name' => $typeName]);
        }

    }
}
