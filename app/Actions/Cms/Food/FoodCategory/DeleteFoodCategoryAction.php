<?php

namespace App\Actions\Cms\Food\FoodCategory;

use App\Models\Tenant\Food\FoodCategory;

class DeleteFoodCategoryAction
{
    /**
     * Handle the action.
     */
    public function handle(FoodCategory $foodCategory): bool
    {
        $foodCategory->version = FoodCategory::max('version') + 1;
        $foodCategory->save();

        return $foodCategory->delete();
    }
}
