<?php

namespace App\Actions\Api\V1\Food;

use App\Models\Tenant\Food\Food;

class ShowFoodAction
{
    /**
     * Handle the action.
     */
    public function handle(Food $food): Food
    {
        $food->image = $food->getFirstMediaUrl('image');

        return $food;
    }
}
