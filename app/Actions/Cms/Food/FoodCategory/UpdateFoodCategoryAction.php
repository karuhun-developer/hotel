<?php

namespace App\Actions\Cms\Food\FoodCategory;

use App\Models\Tenant\Food\FoodCategory;

class UpdateFoodCategoryAction
{
    /**
     * Handle the action.
     */
    public function handle(FoodCategory $foodCategory, array $data): bool
    {
        return $foodCategory->update($data);
    }
}
