<?php

namespace App\Models\Tenant\Food;

use App\Enums\CommonStatusEnum;
use App\Models\Tenant\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FoodCategory extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'version',
        'status',
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

    public function foods()
    {
        return $this->hasMany(Food::class);
    }
}
