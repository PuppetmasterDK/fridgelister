<?php

namespace App\Services;

use App\Models\Fridge;
use Illuminate\Support\Collection;

/**
 * Suggests what to buy: things the household uses regularly that are
 * running out or have gone off.
 */
class RestockSuggester
{
    public function suggest(Fridge $fridge, int $days = 30): Collection
    {
        $used = $fridge->items()
            ->whereNotNull('used_at')
            ->where('used_at', '>=', now()->subDays($days))
            ->get()
            ->groupBy(fn ($item) => mb_strtolower(trim($item->name)));

        $inFridge = $fridge->items()
            ->whereNull('used_at')
            ->get()
            ->groupBy(fn ($item) => mb_strtolower(trim($item->name)));

        $suggestions = collect();

        foreach ($used as $name => $items) {
            if ($items->count() < 2) {
                continue;
            }

            $current = $inFridge->get($name, collect());
            $usable = $current->filter(fn ($item) => ! $item->best_before || ! $item->best_before->isPast());

            if ($usable->isEmpty()) {
                $suggestions->push([
                    'name' => $items->first()->name,
                    'times_used' => $items->count(),
                    'reason' => $current->isEmpty() ? 'You have run out' : 'What you have is past its best',
                ]);
            }
        }

        return $suggestions->sortByDesc('times_used')->values();
    }
}
