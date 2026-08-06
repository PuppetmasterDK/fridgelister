<?php

namespace App\Support;

use Carbon\CarbonInterface;

class Dates
{
    /**
     * A friendly date for people who don't read dates all day:
     * "today", "tomorrow", "in 3 days", or "12 Oct".
     */
    public static function friendly(?CarbonInterface $date): string
    {
        if ($date === null) {
            return '';
        }

        $days = (int) now()->startOfDay()->diffInDays($date->copy()->startOfDay(), false);

        return match (true) {
            $days === 0 => 'today',
            $days === 1 => 'tomorrow',
            $days === -1 => 'yesterday',
            $days > 1 && $days < 7 => "in {$days} days",
            default => $date->format('j M'),
        };
    }
}
