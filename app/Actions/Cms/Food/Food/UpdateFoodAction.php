<?php

namespace App\Actions\Cms\Food\Food;

use App\Models\Tenant\Food\Food;

class UpdateFoodAction
{
    /**
     * Handle the action.
     */
    public function handle(Food $food, array $data): bool
    {
        return $food->update($data);
    }
}
