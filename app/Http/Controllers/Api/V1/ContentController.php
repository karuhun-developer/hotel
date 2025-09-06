<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\ContentItem;
use Illuminate\Http\Request;
use App\Traits\WithGetFilterDataApi;

class ContentController extends Controller
{
    use WithGetFilterDataApi;
    public function contents(Request $request)
    {
        $query = Content::where('hotel_id', $this->getHotel());
    
        if ($request->has('after')) {
            $query->where('version', '>', $request->after ?? 0);
        }

        if ($request->has('ids') && !empty($request->ids)) {

            $ids = is_string($request->ids)
                ? explode(',', $request->ids)
                : (array) $request->ids;

            
            $ids = array_filter(array_map('intval', $ids));
            
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }
        $data = $this->getDataWithFilter(
            model: $query,
            searchBy: [
                'name',
            ],
            orderBy: $request?->orderBy ?? 'id',
            order: $request?->order ?? 'asc',
            paginate: $request?->paginate ?? 10,
            searchBySpecific: $request?->searchBySpecific ?? '',
            s: $request?->search ?? '',
        );
        return $this->respondWithSuccess($data);
    }
    public function contentsChangeList(Request $request) {
        $data = $this->getDataWithFilter(
            model: Content::where('hotel_id', $this->getHotel())
                    ->select('id', 'version','is_deleted')
                    ->where('version', '>', $request->after ?? 0),
            searchBy: [
                'name',
            ],
            orderBy: $request?->orderBy ?? 'id',
            order: $request?->order ?? 'asc',
            paginate: $request?->paginate ?? 10,
            searchBySpecific: $request?->searchBySpecific ?? '',
            s: $request?->search ?? '',
        );
        return $this->respondWithSuccess($data);
    }

    public function contentItems(Request $request)
    {
        $query = ContentItem::where('hotel_id', $this->getHotel());
    
        if ($request->has('after')) {
            $query->where('version', '>', $request->after ?? 0);
        }

        if ($request->has('ids') && !empty($request->ids)) {

            $ids = is_string($request->ids)
                ? explode(',', $request->ids)
                : (array) $request->ids;

            
            $ids = array_filter(array_map('intval', $ids));
            
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }
        $data = $this->getDataWithFilter(
            model: $query,
            searchBy: [
                'name',
            ],
            orderBy: $request?->orderBy ?? 'id',
            order: $request?->order ?? 'asc',
            paginate: $request?->paginate ?? 10,
            searchBySpecific: $request?->searchBySpecific ?? '',
            s: $request?->search ?? '',
        );
        return $this->respondWithSuccess($data);
    }

    public function contentItemChangeList(Request $request) {
        $data = $this->getDataWithFilter(
            model: ContentItem::where('hotel_id', $this->getHotel())
                    ->select('id', 'version','is_deleted')
                    ->where('version', '>', $request->after ?? 0),
            searchBy: [
                'name',
            ],
            orderBy: $request?->orderBy ?? 'id',
            order: $request?->order ?? 'asc',
            paginate: $request?->paginate ?? 10,
            searchBySpecific: $request?->searchBySpecific ?? '',
            s: $request?->search ?? '',
        );
        return $this->respondWithSuccess($data);
    }
}
