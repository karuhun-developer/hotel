<?php

namespace App\Actions\Cms\Content\ContentItem;

use App\Models\Tenant\Content\ContentItem;

class DeleteContentItemAction
{
    /**
     * Handle the action.
     */
    public function handle(ContentItem $contentItem): bool
    {
        $contentItem->version = ContentItem::max('version') + 1;
        $contentItem->save();

        return $contentItem->delete();
    }
}
