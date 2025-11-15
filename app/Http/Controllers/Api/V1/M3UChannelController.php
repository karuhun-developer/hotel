<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\CommonStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Tenant\TenantChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class M3UChannelController extends Controller
{
    public function index(Request $request)
    {
        $model = TenantChannel::query()
            ->join('m3u_channels', 'tenant_channels.m3u_channel_id', '=', 'm3u_channels.id')
            ->join('m3u_sources', 'm3u_channels.m3u_source_id', '=', 'm3u_sources.id')
            ->where('tenant_channels.tenant_id', auth()->user()->tenant?->tenant_id)
            // Get only active channels
            ->where('m3u_channels.status', CommonStatusEnum::ACTIVE)
            ->where('m3u_sources.status', CommonStatusEnum::ACTIVE)
            ->select(
                'm3u_channels.*',
                'tenant_channels.m3u_source_id',
                'tenant_channels.m3u_channel_id',
                DB::raw("COALESCE(
                    NULLIF(tenant_channels.alias, ''),
                    NULLIF(m3u_channels.alias, ''),
                    m3u_channels.name
                ) as name")
            )
            ->with('m3uChannel.media')
            ->get();

        // Load images
        $model->each(function ($item) {
            $item->image = $item->m3uChannel?->getFirstMediaUrl('image');
        });

        return $this->responseWithSuccess($model);
    }
}
