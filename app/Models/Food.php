<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';
    public static $FILE_PATH = 'foods';
    protected $fillable = [
        'hotel_id',
        'food_category_id',
        'name',
        'description',
        'image',
        'price',
        'version',
        'is_deleted',
        'is_active'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'is_active' => 'boolean',
        'version' => 'integer',
    ];

    // Set image url
    public function getImageAttribute($value)
    {
        if (!$value) return null;
        if (str_contains($value, 'storage/' . self::$FILE_PATH)) return $value;
        return asset('storage/' . self::$FILE_PATH . '/' . $value);
    }

    public function hotel() {
        return $this->belongsTo(Hotel::class);
    }

    public function foodCategory() {
        return $this->belongsTo(FoodCategory::class);
    }
    public static function version() {
        return static::max('version');
    }
}
