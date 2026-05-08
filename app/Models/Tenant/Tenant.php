<?php

namespace App\Models\Tenant;

use App\Enums\CommonStatusEnum;
use App\Enums\TenantTypeEnum;
use App\Models\Tenant\Content\Content;
use App\Models\Tenant\Content\ContentItem;
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
        'type' => TenantTypeEnum::class,
        'status' => CommonStatusEnum::class,
    ];

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
        return $this->hasMany(Content::class);
    }

    public function contentItems()
    {
        return $this->hasMany(ContentItem::class);
    }

    public function frontDesks()
    {
        return $this->hasMany(FrontDesk::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function channels()
    {
        return $this->hasMany(TenantChannel::class);
    }
}
