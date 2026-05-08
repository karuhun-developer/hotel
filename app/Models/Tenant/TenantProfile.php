<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TenantProfile extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'tenant_id',
        'running_text',
        'primary_color',
        'description',
        'welcome_text',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
