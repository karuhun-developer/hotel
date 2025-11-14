<?php

namespace App\Models\Tenant\Food;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FoodCategory extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
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

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant\Tenant::class);
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }
}
