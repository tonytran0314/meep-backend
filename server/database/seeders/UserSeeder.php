<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/users.json');
        if (!File::exists($jsonPath)) {
            $this->command->error("Error: users.json file not found!");
            return;
        }

        $jsonData = File::get($jsonPath);
        $users = json_decode($jsonData, true);

        foreach ($users as $user) {
            User::factory()->create([
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
            ]);
        }

        $this->command->info('Users seeded successfully!');
    }
}
