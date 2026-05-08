<?php

namespace App\Actions\Cms\Food\Food;

use App\Models\Tenant\Food\Food;

class StoreFoodAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): Food
    {
        return Food::create($data);
    }
}
