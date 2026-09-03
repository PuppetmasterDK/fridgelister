<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $userId = $request->user()->id;

        // Quick overview across all the user's fridges.
        $stats = DB::table('items')
            ->join('fridges', 'fridges.id', '=', 'items.fridge_id')
            ->where('fridges.user_id', $userId)
            ->whereNull('items.used_at')
            ->selectRaw('count(*) as total')
            ->selectRaw("sum(case when items.best_before < date('now') then 1 else 0 end) as expired")
            ->first();

        $usedThisWeek = DB::table('items')
            ->join('fridges', 'fridges.id', '=', 'items.fridge_id')
            ->where('fridges.user_id', $userId)
            ->where('items.used_at', '>=', now()->subWeek())
            ->count();

        $pendingShares = DB::table('shares')
            ->where('email', $request->user()->email)
            ->where('status', 'pending')
            ->count();

        return view('dashboard', [
            'total' => (int) ($stats->total ?? 0),
            'expired' => (int) ($stats->expired ?? 0),
            'usedThisWeek' => $usedThisWeek,
            'pendingShares' => $pendingShares,
        ]);
    }
}
