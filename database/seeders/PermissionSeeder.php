<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $permissions = [

            'show_product',
            'store_product',
            'update_product',
            'destroy_product',

            'show_user',
            'store_user',
            'update_user',
            'destroy_user',


            'show_purchase',
            'store_purchase',
            'update_purchase',
            'destroy_purchase',


            'show_sell',
            'store_sell',
            'update_sell',
            'destroy_sell',

            'show_area',
            'store_area',
            'update_area',
            'destroy_area',


            'show_paid',
            'store_paid',
            'update_paid',
            'destroy_paid',

            'show_arrested',
            'store_arrested',
            'update_arrested',
            'destroy_arrested',


            'show_category',
            'store_category',
            'update_category',
            'destroy_category',


            'show_currency',
            'store_currency',
            'update_currency',
            'destroy_currency',


            'show_account',
            'store_account',
            'update_account',
            'destroy_account',

            'show_account_detail',
            'store_account_detail',
            'update_account_detail',
            'destroy_account_detail',






        ];

        foreach ($permissions as $permissionName) {

            Permission::firstOrCreate(['name' => $permissionName]);
        }
    }
}
