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
        User::query()->updateOrCreate([
            'email' => '000001@admin.com',
        ], [
            'name' => 'admin01',
            'password' => Hash::make('123456'),
            'role' => UserRole::Admin,
        ]);

        User::query()->updateOrCreate([
            'email' => '000001@user.com',
        ], [
            'name' => 'user01',
            'password' => Hash::make('123456'),
            'role' => UserRole::User,
        ]);

        User::query()->updateOrCreate([
            'email' => '000002@user.com',
        ], [
            'name' => 'user02',
            'password' => Hash::make('123456'),
            'role' => UserRole::User,
        ]);
    }
}
