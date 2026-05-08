<?php

namespace App\Actions\Api\V1\Room;

use App\Models\Tenant\Room;

class ShowRoomAction
{
    /**
     * Handle the action.
     */
    public function handle(string $no, int $tenantId): Room
    {
        return Room::where('tenant_id', $tenantId)
            ->where('no', $no)
            ->firstOrFail();
    }
}
