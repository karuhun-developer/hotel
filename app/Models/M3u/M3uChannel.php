<?php

namespace App\Models\M3u;

use App\Enums\CommonStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class M3uChannel extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'm3u_source_id',
        'name',
        'alias',
        'url',
        'status',
    ];

    protected $casts = [
        'status' => CommonStatusEnum::class,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function m3uSource()
    {
        return $this->belongsTo(M3uSource::class, 'm3u_source_id');
    }
}
