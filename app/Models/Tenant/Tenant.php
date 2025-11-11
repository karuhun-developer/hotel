<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Tenant extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'type',
        'branch',
        'address',
        'phone',
        'email',
        'website',
        'default_greeting',
        'password_setting',
        'status',
    ];

    protected $casts = [
        'type' => \App\Enums\TenantTypeEnum::class,
        'status' => \App\Enums\CommonStatusEnum::class,
    ];

    // Get the activity log options.
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function profile()
    {
        return $this->hasOne(TenantProfile::class);
    }

    public function users()
    {
        return $this->hasMany(TenantUser::class);
    }

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function contents()
    {
        return $this->hasMany(\App\Models\Tenant\Content\Content::class);
    }

    public function contentItems()
    {
        return $this->hasMany(\App\Models\Tenant\Content\ContentItem::class);
    }

    public function frontDesks()
    {
        return $this->hasMany(FrontDesk::class);
    }
}
