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

class ContentItem extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $fillable = [
        'content_id',
        'tenant_id',
        'name',
        'description',
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

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
