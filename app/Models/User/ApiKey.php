<?php

namespace App\Models\User;

use App\Enums\CommonStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ApiKey extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'name',
        'salt',
        'expired_at',
        'last_used_at',
        'status',
    ];

    protected $casts = [
        'status' => CommonStatusEnum::class,
        'expired_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly([
            'user_id',
            'name',
            'expired_at',
            'last_used_at',
            'status',
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
