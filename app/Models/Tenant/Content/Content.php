<?php

namespace App\Models\Tenant\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Content extends Model implements HasMedia
{
    use LogsActivity, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'tenant_id',
        'name',
        'status',
        'version',
    ];

    protected $casts = [
        'status' => \App\Enums\CommonStatusEnum::class,
    ];

    // Get the activity log options.
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant\Tenant::class);
    }

    public function items()
    {
        return $this->hasMany(ContentItem::class);
    }
}
