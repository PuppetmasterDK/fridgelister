<?php

namespace App\Support;

use App\Models\Item;

class Freshness
{
    public const USED = 'used';

    public const EXPIRED = 'expired';

    public const USE_SOON = 'use_soon';

    public const FRESH = 'fresh';

    /**
     * Work out how fresh an item is, based on its best-before date.
     */
    public static function for(Item $item): string
    {
        if ($item->used_at !== null) {
            return self::USED;
        }

        if ($item->best_before === null) {
            return self::FRESH;
        }

        $days = (int) now()->startOfDay()->diffInDays($item->best_before->copy()->startOfDay(), false);

        if ($days < 0) {
            return self::EXPIRED;
        }

        if ($days < 3) {
            return self::USE_SOON;
        }

        return self::FRESH;
    }

    public static function label(string $status): string
    {
        return match ($status) {
            self::USED => 'Used',
            self::EXPIRED => 'Past best before',
            self::USE_SOON => 'Use soon',
            default => 'Fresh',
        };
    }
}
