<?php

namespace App\Actions\Api\V1\M3u;

use App\Enums\CommonStatusEnum;
use App\Models\Tenant\TenantChannel;
use Illuminate\Support\Facades\DB;

class IndexM3uChannelAction
{
    /**
     * Handle the action.
     */
    public function handle(int $tenantId)
    {
        $model = TenantChannel::query()
            ->join('m3u_channels', 'tenant_channels.m3u_channel_id', '=', 'm3u_channels.id')
            ->join('m3u_sources', 'm3u_channels.m3u_source_id', '=', 'm3u_sources.id')
            ->where('tenant_channels.tenant_id', $tenantId)
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

        $model->each(function ($item) {
            $item->image = $item->m3uChannel?->getFirstMediaUrl('image');
        });

        return $model;
    }
}
