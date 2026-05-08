<?php

namespace App\Actions\Cms\FrontDesk;

use App\Models\Tenant\FrontDesk;

class UpdateFrontDeskAction
{
    /**
     * Handle the action.
     */
    public function handle(FrontDesk $frontDesk, array $data): bool
    {
        return $frontDesk->update($data);
    }
}
