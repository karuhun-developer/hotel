<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Api\V1\Food\ChangeListFoodAction;
use App\Actions\Api\V1\Food\ChangeListFoodCategoryAction;
use App\Actions\Api\V1\Food\IndexFoodAction;
use App\Actions\Api\V1\Food\IndexFoodCategoryAction;
use App\Actions\Api\V1\Food\ShowFoodAction;
use App\Actions\Api\V1\Food\ShowFoodCategoryAction;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Food\Food;
use App\Models\Tenant\Food\FoodCategory;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * List food categories.
     */
    public function categories(Request $request, IndexFoodCategoryAction $action)
    {
        $data = $action->handle($request, auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }

    /**
     * Show a food category.
     */
    public function categoryShow(FoodCategory $model, ShowFoodCategoryAction $action)
    {
        $data = $action->handle($model);

        return $this->responseWithSuccess($data);
    }

    /**
     * Food categories change list.
     */
    public function categoryChangeList(Request $request, ChangeListFoodCategoryAction $action)
    {
        $data = $action->handle($request, auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }

    /**
     * List food items.
     */
    public function items(Request $request, IndexFoodAction $action)
    {
        $data = $action->handle($request, auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }

    /**
     * Show a food item.
     */
    public function itemShow(Food $model, ShowFoodAction $action)
    {
        $data = $action->handle($model);

        return $this->responseWithSuccess($data);
    }

    /**
     * Food items change list.
     */
    public function itemChangeList(Request $request, ChangeListFoodAction $action)
    {
        $data = $action->handle($request, auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }
}
