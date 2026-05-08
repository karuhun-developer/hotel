<?php

namespace App\Models\Tenant;

use App\Models\M3u\M3uChannel;
use App\Models\M3u\M3uSource;
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
        return $this->belongsTo(M3uSource::class, 'm3u_source_id');
    }

    public function m3uChannel()
    {
        return $this->belongsTo(M3uChannel::class, 'm3u_channel_id');
    }
}
