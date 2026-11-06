<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        $all = self::allKeyValues();

        return $all[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        Cache::forget('site_settings_cache');

        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function allKeyValues()
    {
        return Cache::remember('site_settings_cache', 3600, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }
}
