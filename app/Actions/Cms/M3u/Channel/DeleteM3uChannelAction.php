<?php

namespace App\Actions\Cms\M3u\Channel;

use App\Models\M3u\M3uChannel;

class DeleteM3uChannelAction
{
    /**
     * Handle the action.
     */
    public function handle(M3uChannel $m3uChannel): bool
    {
        return $m3uChannel->delete();
    }
}
