<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/messages.json');
        if (!File::exists($jsonPath)) {
            $this->command->error("Error: messages.json file not found!");
            return;
        }

        $jsonData = File::get($jsonPath);
        $messages = json_decode($jsonData, true);

        $baseTime = Carbon::now()->subMinutes(10);

        foreach ($messages as $index => $message) {
            Message::create([
                'room_id' => $message['room_id'],
                'user_id' => $message['user_id'],
                'content' => $message['content'],
                'created_at' => $baseTime->addSeconds(10),
                'updated_at' => $baseTime,
            ]);
        }

        $this->command->info('Messages seeded successfully!');
    }
}
