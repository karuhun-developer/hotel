<?php

namespace App\Actions\Cms\Room\RoomType;

use App\Models\Tenant\RoomType;

class DeleteRoomTypeAction
{
    /**
     * Handle the action.
     */
    public function handle(RoomType $roomType): bool
    {
        return $roomType->delete();
    }
}
