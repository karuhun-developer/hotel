<?php

namespace App\Actions\Cms\Content\ContentItem;

use App\Models\Tenant\Content\ContentItem;

class StoreContentItemAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): ContentItem
    {
        return ContentItem::create($data);
    }
}
