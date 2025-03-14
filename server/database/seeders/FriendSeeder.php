<?php

namespace Database\Seeders;

use App\Models\Friend;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/friends.json');
        if (!File::exists($jsonPath)) {
            $this->command->error("Error: friends.json file not found!");
            return;
        }

        $jsonData = File::get($jsonPath);
        $friends = json_decode($jsonData, true);

        foreach ($friends as $friend) {
            Friend::create([
                'sender_id' => $friend['sender_id'],
                'receiver_id' => $friend['receiver_id'],
                'status' => $friend['status'],
            ]);
        }

        $this->command->info('Friends seeded successfully!');
    }
}
