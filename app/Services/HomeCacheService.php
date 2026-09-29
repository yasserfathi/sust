<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class HomeCacheService
{
    public const CACHE_KEY_HOME_EN = 'home_page_data_en';
    public const CACHE_KEY_HOME_AR = 'home_page_data_ar';
    public const CACHE_KEY_SHARED_COLLEGES = 'home_shared_colleges';

    /**
     * Clear all home page related caches.
     */
    public static function clearHomeCache(): void
    {
        Cache::forget(self::CACHE_KEY_HOME_EN);
        Cache::forget(self::CACHE_KEY_HOME_AR);
        Cache::forget(self::CACHE_KEY_SHARED_COLLEGES);
    }
}
