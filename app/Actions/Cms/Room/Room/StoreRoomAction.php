<?php

namespace App\Actions\Cms\Room\Room;

use App\Models\Tenant\Room;

class StoreRoomAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): Room
    {
        return Room::create($data);
    }
}
