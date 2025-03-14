<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class RoomUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = File::get(database_path('data/room_user.json'));
        $rooms = json_decode($jsonData, true);

        foreach ($rooms as $room) {
            $roomId = $room['room_id'];
            $userIds = $room['user_ids'];

            $roomInstance = Room::find($roomId);

            if ($roomInstance) {
                $roomInstance->users()->attach($userIds);
            }
        }

        $this->command->info('RoomUserSeeder completed!');
    }
}
