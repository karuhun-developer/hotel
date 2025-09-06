<?php

namespace App\Models;

use App\Models\FeatureCategory;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentItem extends Model
{
    use HasFactory;

    public static $FILE_PATH = 'content-items';

    protected $fillable = [
        'name',
        'description',
        'image',
        'content_id',
        'is_active',
        'is_deleted',
        'version',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'is_active' => 'boolean',
        'version' => 'integer',
        'hotel_id' => 'integer',
        'content_id' => 'integer',
    ];

    // Relationships
    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    // Get version count
    public function version()
    {
        return $this->version ?? 1;
    }

    // Accessor for image URL
    public function getImageAttribute($value)
    {
        if (!$value) return null;
        if (str_contains($value, 'storage/' . self::$FILE_PATH)) return $value;
        return asset('storage/' . self::$FILE_PATH . '/' . $value);
    }
}
