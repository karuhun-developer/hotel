<?php

namespace App\Actions\Cms\M3u\Source;

use App\Models\M3u\M3uSource;

class StoreM3uSourceAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): M3uSource
    {
        return M3uSource::create($data);
    }
}
