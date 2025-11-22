<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Food\Food;
use App\Models\Tenant\Food\FoodCategory;
use App\Traits\WithGetFilterDataApi;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    use WithGetFilterDataApi;

    public function categories(Request $request)
    {
        $model = FoodCategory::query()
            ->with('media')
            ->where('tenant_id', auth()->user()->tenant?->tenant_id)
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            });

        if ($request->has('ids')) {
            $ids = is_string($request->ids) ? explode(',', $request->ids) : $request->ids;

            $model->whereIn('id', $ids);
        }

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'name',
                'description',
            ],
            orderBy: $request?->orderBy ?? 'created_at',
            order: $request?->order ?? 'asc',
            paginate: $request?->paginate ?? 10,
            searchBySpecific: $request?->searchBySpecific ?? '',
            s: $request?->search ?? '',
        );

        // Load images
        $model->each(function ($item) {
            $item->image = $item->getFirstMediaUrl('image');
        });

        return $this->responseWithSuccess($model);
    }

    public function categoryShow(FoodCategory $model)
    {
        // Load image
        $model->image = $model->getFirstMediaUrl('image');

        return $this->responseWithSuccess($model);
    }

    public function categoryChangeList(Request $request)
    {
        $model = FoodCategory::query()
            ->with('media')
            ->withTrashed()
            ->where('tenant_id', auth()->user()->tenant?->tenant_id)
            ->where('version', '>', $request->after ?? 0);

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'name',
                'description',
            ],
            orderBy: $request?->orderBy ?? 'created_at',
            order: $request?->order ?? 'asc',
            paginate: $request?->paginate ?? 10,
            searchBySpecific: $request?->searchBySpecific ?? '',
            s: $request?->search ?? '',
        );

        // Load images
        $model->each(function ($item) {
            $item->image = $item->getFirstMediaUrl('image');
        });

        return $this->responseWithSuccess($model);
    }

    public function items(Request $request)
    {
        $model = Food::query()
            ->with('media')
            ->where('tenant_id', auth()->user()->tenant?->tenant_id)
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('food_category_id'), function ($query) use ($request) {
                $query->where('food_category_id', $request->food_category_id);
            });

        if ($request->has('ids')) {
            $ids = is_string($request->ids) ? explode(',', $request->ids) : $request->ids;

            $model->whereIn('id', $ids);
        }

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'name',
                'price',
                'description',
            ],
            orderBy: $request?->orderBy ?? 'created_at',
            order: $request?->order ?? 'asc',
            paginate: $request?->paginate ?? 10,
            searchBySpecific: $request?->searchBySpecific ?? '',
            s: $request?->search ?? '',
        );

        // Load images
        $model->each(function ($item) {
            $item->image = $item->getFirstMediaUrl('image');
        });

        return $this->responseWithSuccess($model);
    }

    public function itemShow(Food $model)
    {
        // Load image
        $model->image = $model->getFirstMediaUrl('image');

        return $this->responseWithSuccess($model);
    }

    public function itemChangeList(Request $request)
    {
        $model = Food::query()
            ->with('media')
            ->withTrashed()
            ->where('tenant_id', auth()->user()->tenant?->tenant_id)
            ->where('version', '>', $request->after ?? 0);

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'name',
                'price',
                'description',
            ],
            orderBy: $request?->orderBy ?? 'created_at',
            order: $request?->order ?? 'asc',
            paginate: $request?->paginate ?? 10,
            searchBySpecific: $request?->searchBySpecific ?? '',
            s: $request?->search ?? '',
        );

        // Load images
        $model->each(function ($item) {
            $item->image = $item->getFirstMediaUrl('image');
        });

        return $this->responseWithSuccess($model);
    }
}
