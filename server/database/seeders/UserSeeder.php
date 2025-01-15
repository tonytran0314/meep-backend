<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User 1',
            'email' => 'test1@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test User 3',
            'email' => 'test3@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test User 4',
            'email' => 'test4@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test User 5',
            'email' => 'test5@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test User 6',
            'email' => 'test6@example.com',
        ]);
        
        User::factory()->create([
            'name' => 'Test User 7',
            'email' => 'test7@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test User 8',
            'email' => 'test8@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test User 9',
            'email' => 'test9@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test User 10',
            'email' => 'test10@example.com',
        ]);
    }
}
