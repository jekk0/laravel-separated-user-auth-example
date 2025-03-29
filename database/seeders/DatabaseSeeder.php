<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            Admin::class => ['admin@example.com', 'admin2@example.com', 'admin3@example.com'],
            Company::class => ['company@example.com', 'company2@example.com', 'company3@example.com'],
            User::class => ['user@example.com', 'user2@example.com', 'user3@example.com'],
        ];

        foreach ($users as $model => $emails) {
            foreach ($emails as $email) {
                if ($model::where(['email' => $email])->first()) {
                    continue;
                }
                $model::firstOrCreate([
                    'name' => Str::random(),
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);
            }
        }
    }
}
