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
}
