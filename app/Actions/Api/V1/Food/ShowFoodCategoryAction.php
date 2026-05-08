<?php

namespace App\Actions\Api\V1\Food;

use App\Models\Tenant\Food\FoodCategory;

class ShowFoodCategoryAction
{
    /**
     * Handle the action.
     */
    public function handle(FoodCategory $foodCategory): FoodCategory
    {
        $foodCategory->image = $foodCategory->getFirstMediaUrl('image');

        return $foodCategory;
    }
}
