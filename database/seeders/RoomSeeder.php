<?php

namespace Database\Seeders;

use App\Models\Tenant\Room;
use App\Models\Tenant\RoomType;
use App\Models\Tenant\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = Tenant::where('name', 'Haris Hotel Bandung')->first();
        $tenant2 = Tenant::where('name', 'Santosa Hospital Bandung Central')->first();

        $roomTypes = [
            [
                'name' => 'Standard Room',
                'description' => 'A standard room with basic amenities.',
            ],
            [
                'name' => 'Deluxe Room',
                'description' => 'A deluxe room with additional amenities and better view.',
            ],
            [
                'name' => 'Suite Room',
                'description' => 'A luxurious suite room with premium amenities and spacious layout.',
            ],
        ];

        // Create room types for each tenant
        foreach ([$tenant, $tenant2] as $t) {
            if (! $t) {
                continue;
            }

            foreach ($roomTypes as $typeData) {
                $roomType = RoomType::firstOrCreate(
                    [
                        'tenant_id' => $t->id,
                        'name' => $typeData['name'],
                    ],
                    [
                        'description' => $typeData['description'],
                    ]
                );

                // Create 10 rooms for each room type
                for ($i = 1; $i <= 10; $i++) {
                    Room::firstOrCreate(
                        [
                            'tenant_id' => $t->id,
                            'room_type_id' => $roomType->id,
                            'no' => $i,
                        ],
                        [
                            'greeting' => 'Welcome to '.$roomType->name.' '.$i,
                            'device_name' => 'Device '.$i,
                        ]
                    );
                }
            }
        }
    }
}
