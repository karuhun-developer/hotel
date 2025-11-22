<?php

namespace App\Models\Tenant\Food;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Food extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'food_category_id',
        'name',
        'price',
        'description',
        'version',
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

    public function foodCategory()
    {
        return $this->belongsTo(FoodCategory::class);
    }
}
