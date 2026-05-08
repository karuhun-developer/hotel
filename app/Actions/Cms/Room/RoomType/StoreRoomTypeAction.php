<?php

namespace App\Actions\Cms\Room\RoomType;

use App\Models\Tenant\RoomType;

class StoreRoomTypeAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): RoomType
    {
        return RoomType::create($data);
    }
}
