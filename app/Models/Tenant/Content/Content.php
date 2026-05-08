<?php

namespace App\Models\Tenant\Content;

use App\Enums\CommonStatusEnum;
use App\Models\Tenant\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Content extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'status',
        'version',
    ];

    protected $casts = [
        'status' => CommonStatusEnum::class,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function items()
    {
        return $this->hasMany(ContentItem::class);
    }
}
