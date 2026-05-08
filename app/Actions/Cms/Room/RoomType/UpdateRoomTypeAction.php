<?php

namespace App\Actions\Cms\Room\RoomType;

use App\Models\Tenant\RoomType;

class UpdateRoomTypeAction
{
    /**
     * Handle the action.
     */
    public function handle(RoomType $roomType, array $data): bool
    {
        return $roomType->update($data);
    }
}
