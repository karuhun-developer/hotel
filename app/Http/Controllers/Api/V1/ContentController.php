<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Content\Content;
use App\Models\Tenant\Content\ContentItem;
use App\Traits\WithGetFilterDataApi;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    use WithGetFilterDataApi;

    public function contents(Request $request)
    {
        $model = Content::query()
            ->with('media')
            ->where('tenant_id', auth()->user()->tenant?->tenant_id);

        if ($request->has('after')) {
            $model->where('version', '>', $request->after ?? 0);
        }

        if ($request->has('ids')) {
            $ids = is_string($request->ids) ? explode(',', $request->ids) : $request->ids;

            $model->whereIn('id', $ids);
        }

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'name',
            ],
            orderBy: $request?->orderBy ?? 'id',
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

    public function contentsChangeList(Request $request)
    {
        $model = Content::query()
            ->with('media')
            ->withTrashed()
            ->where('tenant_id', auth()->user()->tenant?->tenant_id)
            ->where('version', '>', $request->after ?? 0);

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'name',
            ],
            orderBy: $request?->orderBy ?? 'id',
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

    public function contentItems(Request $request)
    {
        $model = ContentItem::query()
            ->with('media')
            ->where('tenant_id', auth()->user()->tenant?->tenant_id);

        if ($request->has('after')) {
            $model->where('version', '>', $request->after ?? 0);
        }

        if ($request->has('ids')) {
            $ids = is_string($request->ids) ? explode(',', $request->ids) : $request->ids;

            $model->whereIn('id', $ids);
        }

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'name',
            ],
            orderBy: $request?->orderBy ?? 'id',
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

    public function contentItemChangeList(Request $request)
    {
        $model = ContentItem::query()
            ->with('media')
            ->withTrashed()
            ->where('tenant_id', auth()->user()->tenant?->tenant_id)
            ->where('version', '>', $request->after ?? 0);

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'name',
            ],
            orderBy: $request?->orderBy ?? 'id',
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
