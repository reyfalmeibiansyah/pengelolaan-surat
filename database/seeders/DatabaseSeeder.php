<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            "name" => "staff",
            "email" => "staffwk@gmail.com",
            "password" => Hash::make("staff123"),
            "role" => "staff",
        ]);
        User::create([
            "name" => "guru",
            "email" => "guruwk@gmail.com",
            "password" => Hash::make("guru123"),
            "role" => "guru",
        ]);
    }
}
