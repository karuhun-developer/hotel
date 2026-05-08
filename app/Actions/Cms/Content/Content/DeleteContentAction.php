<?php

namespace App\Actions\Cms\Content\Content;

use App\Models\Tenant\Content\Content;

class DeleteContentAction
{
    /**
     * Handle the action.
     */
    public function handle(Content $content): bool
    {
        $content->version = Content::max('version') + 1;
        $content->save();

        return $content->delete();
    }
}
