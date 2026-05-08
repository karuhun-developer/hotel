<?php

namespace App\Actions\Cms\Food\FoodCategory;

use App\Models\Tenant\Food\FoodCategory;

class StoreFoodCategoryAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): FoodCategory
    {
        return FoodCategory::create($data);
    }
}
