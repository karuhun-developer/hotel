<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    public static $FILE_PATH = 'applications';
    protected $fillable = [
        'hotel_id',
        'name',
        'image',
        'package_name',
        'is_deleted',
        'version'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'version' => 'integer'
    ];

    public static function version() {
        return static::max('version');
    }
    public function getImageAttribute($value)
    {
        if (!$value) return null;
        if (str_contains($value, 'storage/' . self::$FILE_PATH)) return $value;
        return asset('storage/' . self::$FILE_PATH . '/' . $value);
    }

    public function hotel() {
        return $this->belongsTo(Hotel::class);
    }
}
