<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TenantUser extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'tenant_id',
    ];

    // Get the activity log options.
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
