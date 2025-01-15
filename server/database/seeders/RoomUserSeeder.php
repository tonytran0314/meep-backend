<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $room1 = Room::find(1);
        $room1->users()->attach([2, 3]);

        $room2 = Room::find(2);
        $room2->users()->attach([4, 7]);

        $room3 = Room::find(3);
        $room3->users()->attach([1, 3]);
    }
}
