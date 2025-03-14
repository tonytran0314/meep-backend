<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RoomSeeder::class,
            RoomUserSeeder::class,
            NotificationTypesSeeder::class,
            FriendSeeder::class,
            MessageSeeder::class,
        ]);
    }
}
