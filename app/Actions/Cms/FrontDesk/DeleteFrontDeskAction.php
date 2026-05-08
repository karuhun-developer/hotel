<?php

namespace App\Actions\Cms\FrontDesk;

use App\Models\Tenant\FrontDesk;

class DeleteFrontDeskAction
{
    /**
     * Handle the action.
     */
    public function handle(FrontDesk $frontDesk): bool
    {
        return $frontDesk->delete();
    }
}
