<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Room extends Model
{
    use LogsActivity;

    protected $fillable = [
        'tenant_id',
        'room_type_id',
        'no',
        'guest_name',
        'greeting',
        'device_name',
        'is_birthday',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function frontDesks()
    {
        return $this->hasMany(FrontDesk::class);
    }
}
