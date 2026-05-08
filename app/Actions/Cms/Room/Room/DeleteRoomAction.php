<?php

namespace App\Actions\Cms\Room\Room;

use App\Models\Tenant\Room;

class DeleteRoomAction
{
    /**
     * Handle the action.
     */
    public function handle(Room $room): bool
    {
        return $room->delete();
    }
}
