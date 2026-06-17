<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'admin01',
            'email' => '000001@admin.com',
            'password' => Hash::make('123456'),
            'role' => UserRole::Admin,
        ]);

        User::create([
            'name' => 'user01',
            'email' => '000001@user.com',
            'password' => Hash::make('123456'),
            'role' => UserRole::User,
        ]);

        User::create([
            'name' => 'user02',
            'email' => '000002@user.com',
            'password' => Hash::make('123456'),
            'role' => UserRole::User,
        ]);
    }
}
