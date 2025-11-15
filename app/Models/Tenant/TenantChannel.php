<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class TenantChannel extends Model
{
    protected $fillable = [
        'tenant_id',
        'm3u_source_id',
        'm3u_channel_id',
        'alias',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function m3uSource()
    {
        return $this->belongsTo(\App\Models\M3u\M3uSource::class, 'm3u_source_id');
    }

    public function m3uChannel()
    {
        return $this->belongsTo(\App\Models\M3u\M3uChannel::class, 'm3u_channel_id');
    }
}
