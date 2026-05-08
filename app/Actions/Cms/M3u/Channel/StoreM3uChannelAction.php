<?php

namespace App\Actions\Cms\M3u\Channel;

use App\Models\M3u\M3uChannel;

class StoreM3uChannelAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): M3uChannel
    {
        return M3uChannel::create($data);
    }
}
