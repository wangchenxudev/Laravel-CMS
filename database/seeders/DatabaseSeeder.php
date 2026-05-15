<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate([
            'email' => '000001@admin.com',
        ], [
            'name' => 'admin01',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::query()->updateOrCreate([
            'email' => '000001@root.com',
        ], [
            'name' => 'root01',
            'password' => 'password',
            'role' => 'root',
        ]);
    }
}
