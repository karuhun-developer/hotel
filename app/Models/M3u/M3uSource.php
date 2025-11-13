<?php

namespace App\Models\M3u;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class M3uSource extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'url',
        'type',
        'headers',
        'body',
        'status',
    ];

    protected $casts = [
        'status' => \App\Enums\CommonStatusEnum::class,
    ];

    // Get the activity log options.
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function channels()
    {
        return $this->hasMany(M3uChannel::class, 'm3u_source_id');
    }
}
