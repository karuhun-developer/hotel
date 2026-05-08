<?php

namespace App\Actions\Cms\Content\Content;

use App\Models\Tenant\Content\Content;

class UpdateContentAction
{
    /**
     * Handle the action.
     */
    public function handle(Content $content, array $data): bool
    {
        return $content->update($data);
    }
}
