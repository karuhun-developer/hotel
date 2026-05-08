<?php

namespace App\Actions\Cms\Content\Content;

use App\Models\Tenant\Content\Content;

class StoreContentAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): Content
    {
        return Content::create($data);
    }
}
