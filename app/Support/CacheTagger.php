<?php

namespace App\Support;

use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;

/**
 * Utility cache dengan dukungan fallback ketika tag tidak tersedia.
 */
class CacheTagger
{
    /**
     * Simpan data cache dengan tag jika driver mendukung.
     *
     * @template TValue
     * @param  string  $key
     * @param  array<int, string>  $tags
     * @param  DateTimeInterface|DateInterval|int  $ttl
     * @param  Closure(): TValue  $callback
     * @return TValue
     */
    public static function remember(string $key, array $tags, DateTimeInterface|DateInterval|int $ttl, Closure $callback)
    {
        $store = Cache::getStore();

        if (method_exists($store, 'tags')) {
            return Cache::tags($tags)->remember($key, $ttl, $callback);
        }

        return Cache::remember(self::fallbackKey($tags, $key), $ttl, $callback);
    }

    /**
     * Hapus entri cache tertentu berdasarkan tag.
     */
    public static function forget(string $key, array $tags): bool
    {
        $store = Cache::getStore();

        if (method_exists($store, 'tags')) {
            return Cache::tags($tags)->forget($key);
        }

        return Cache::forget(self::fallbackKey($tags, $key));
    }

    /**
     * Flush cache untuk tag tertentu atau semua jika tidak didukung.
     */
    public static function flush(array $tags): void
    {
        $store = Cache::getStore();

        if (method_exists($store, 'tags')) {
            Cache::tags($tags)->flush();

            return;
        }

        Cache::flush();
    }

    /**
     * Bentuk kunci fallback agar tetap unik.
     */
    protected static function fallbackKey(array $tags, string $key): string
    {
        return implode(':', $tags).'|'.$key;
    }
}
