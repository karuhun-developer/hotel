<?php

namespace App\Actions\Cms\FrontDesk;

use App\Models\Tenant\FrontDesk;

class StoreFrontDeskAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): FrontDesk
    {
        return FrontDesk::create($data);
    }
}
