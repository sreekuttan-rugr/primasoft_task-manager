<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'sreekuttan admin',
            'email' => 'sreekuttan@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create regular user
        User::create([
            'name' => 'sreekuttan User',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        //Create sample categories
        Category::create(['name' => 'Development', 'description' => 'Software development tasks']);
        Category::create(['name' => 'Design', 'description' => 'UI/UX design tasks']);
        Category::create(['name' => 'Testing', 'description' => 'Quality assurance tasks']);
        Category::create(['name' => 'Marketing', 'description' => 'Marketing and promotion tasks']);
    }
}