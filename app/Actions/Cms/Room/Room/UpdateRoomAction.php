<?php

namespace App\Actions\Cms\Room\Room;

use App\Models\Tenant\Room;

class UpdateRoomAction
{
    /**
     * Handle the action.
     */
    public function handle(Room $room, array $data): bool
    {
        return $room->update($data);
    }
}
