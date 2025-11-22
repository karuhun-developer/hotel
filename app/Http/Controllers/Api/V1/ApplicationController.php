<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Application;
use App\Traits\WithGetFilterDataApi;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    use WithGetFilterDataApi;

    public function index(Request $request)
    {
        $model = Application::query()
            ->with('media')
            ->where('tenant_id', auth()->user()->tenant?->tenant_id);

        if ($request->has('ids')) {
            $ids = is_string($request->ids) ? explode(',', $request->ids) : $request->ids;

            $model->whereIn('id', $ids);
        }

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'name',
                'package_name',
            ],
            orderBy: $request?->orderBy ?? 'package_name',
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
