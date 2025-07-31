<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SystemUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {



        $userInforamtions =  [

            [
                'user_name'      => 'SuperAdmin',
                'user_type'      => 3,
                'area_id'        => null,
                'account_id'     => null,
                'number_in_book' => null,
                'mobile'          => '0999999999',
                'password'       => Hash::make('123Admin890'),
                'role' => 'SuperAdmin'
            ],

            [
                'user_name'      => 'Admin',
                'user_type'      => 3,
                'area_id'        => null,
                'account_id'     => null,
                'number_in_book' => null,
                'mobile'          => '0988888888',
                'password'       => Hash::make('456user890'),
                'role' => 'Admin'
            ],


        ];


        foreach ($userInforamtions as $userInforamtion) {

            $role = $userInforamtion['role'] ;
            unset($userInforamtion['role']);
            $user = User::firstOrCreate($userInforamtion);
            $user->assignRole($role );
        }
    }
}
