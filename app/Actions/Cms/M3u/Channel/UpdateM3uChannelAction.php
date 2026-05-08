<?php

namespace App\Actions\Cms\M3u\Channel;

use App\Models\M3u\M3uChannel;

class UpdateM3uChannelAction
{
    /**
     * Handle the action.
     */
    public function handle(M3uChannel $m3uChannel, array $data): bool
    {
        return $m3uChannel->update($data);
    }
}
