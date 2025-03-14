<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/messages.json');
        if (!File::exists($jsonPath)) {
            $this->command->error("Error: messages.json file not found!");
            return;
        }

        $jsonData = File::get($jsonPath);
        $messages = json_decode($jsonData, true);

        foreach ($messages as $message) {
            Message::create([
                'room_id' => $message['room_id'],
                'user_id' => $message['user_id'],
                'content' => $message['content'],
            ]);
        }

        $this->command->info('Messages seeded successfully!');
    }
}
