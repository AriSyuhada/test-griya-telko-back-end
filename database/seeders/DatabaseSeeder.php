<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $users = [
            [
                'username' => 'admin-real-estate',
                'email' => 'admin-real-estate@example.com',
                'password' => bcrypt('12345'),
                'role' => 'admin',
            ],
            [
                'username' => 'sales-real-estate',
                'email' => 'sales-real-estate@example.com',
                'password' => bcrypt('12345'),
                'role' => 'sales',
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }

    }
}
