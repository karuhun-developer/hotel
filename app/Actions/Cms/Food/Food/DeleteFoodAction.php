<?php

namespace App\Actions\Cms\Food\Food;

use App\Models\Tenant\Food\Food;

class DeleteFoodAction
{
    /**
     * Handle the action.
     */
    public function handle(Food $food): bool
    {
        $food->version = Food::max('version') + 1;
        $food->save();

        return $food->delete();
    }
}
