<?php

namespace App\Actions\Cms\Content\ContentItem;

use App\Models\Tenant\Content\ContentItem;

class UpdateContentItemAction
{
    /**
     * Handle the action.
     */
    public function handle(ContentItem $contentItem, array $data): bool
    {
        return $contentItem->update($data);
    }
}
