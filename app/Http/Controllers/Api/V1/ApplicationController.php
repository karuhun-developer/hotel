<?php

namespace App\Http\Controllers\Api\V1;

use App\Traits\WithGetFilterDataApi;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    use WithGetFilterDataApi;
    public function get(Request $request) {
        $query = Application::where('hotel_id', $this->getHotel());
    
        if ($request->has('after')) {
            $query->where('version', '>', $request->after ?? 0);
        }
        
        if ($request->has('id') && !empty($request->id)) {

            $ids = is_string($request->id) 
                ? explode(',', $request->id) 
                : (array) $request->id;
            
            
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

    public function changelist(Request $request) {
        $data = $this->getDataWithFilter(
            model: Application::where('hotel_id', $this->getHotel())
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
