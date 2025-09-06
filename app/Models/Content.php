<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    public static $FILE_PATH = 'contents';

    protected $fillable = [
        'name',
        'image',
        'hotel_id',
        'is_active',
        'is_deleted',
        'version',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'is_active' => 'boolean',
        'version' => 'integer',
    ];

    // Relationship
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function contentItems()
    {
        return $this->hasMany(ContentItem::class);
    }

    // Get version count
    public function version()
    {
        return $this->version ?? 1;
    }

    public function getImageAttribute($value)
    {
        if (!$value) return null;
        if (str_contains($value, 'storage/' . self::$FILE_PATH)) return $value;
        return asset('storage/' . self::$FILE_PATH . '/' . $value);
    }
}
