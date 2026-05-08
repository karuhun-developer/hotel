<?php

namespace App\Actions\Cms\M3u\Source;

use App\Models\M3u\M3uSource;

class DeleteM3uSourceAction
{
    /**
     * Handle the action.
     */
    public function handle(M3uSource $m3uSource): bool
    {
        return $m3uSource->delete();
    }
}
