<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/rooms.json');
        if (!File::exists($jsonPath)) {
            $this->command->error("Error: rooms.json file not found!");
            return;
        }

        $rooms = json_decode(File::get($jsonPath), true);

        foreach ($rooms as $room) {
            Room::create([
                'avatar' => $room['avatar'],
                'is_group' => $room['is_group'],
                'name' => $room['name']
            ]);
        }

        $this->command->info("RoomSeeder executed successfully!");
    }
}
