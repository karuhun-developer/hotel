<?php

namespace App\Actions\Api\V1\Room;

use App\Models\Tenant\RoomType;

class ShowRoomTypeAction
{
    /**
     * Handle the action.
     */
    public function handle(RoomType $roomType): RoomType
    {
        $roomType->image = $roomType->getFirstMediaUrl('image');

        return $roomType;
    }
}
