<?php

namespace App\Http\Controllers;

use App\Models\Fridge;
use Illuminate\Http\Request;

class FridgeController extends Controller
{
    public function index(Request $request)
    {
        $own = $request->user()->fridges()->withCount(['items' => fn ($q) => $q->whereNull('used_at')])->get();

        return view('fridges.index', ['own' => $own, 'shared' => collect(), 'invitations' => collect()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $fridge = $request->user()->fridges()->create($data);

        return redirect()->route('fridges.show', $fridge)->with('status', 'Fridge added.');
    }

    public function show(Request $request, Fridge $fridge)
    {
        if ($fridge->user_id !== $request->user()->id) {
            abort(403);
        }

        $items = $fridge->items()->whereNull('used_at')->with('addedBy')->orderBy('best_before')->get();

        // Work out the status of each item for the badges.
        $counts = ['expired' => 0, 'use_soon' => 0, 'fresh' => 0];
        foreach ($items as $item) {
            if ($item->best_before === null) {
                $item->status = 'fresh';
            } elseif ($item->best_before->isPast() && ! $item->best_before->isToday()) {
                $item->status = 'expired';
            } elseif ($item->best_before->lte(now()->addDays(2))) {
                $item->status = 'use_soon';
            } else {
                $item->status = 'fresh';
            }
            $counts[$item->status]++;
        }

        return view('fridges.show', [
            'fridge' => $fridge,
            'items' => $items,
            'counts' => $counts,
        ]);
    }

    public function history(Request $request, Fridge $fridge)
    {
        if ($fridge->user_id !== $request->user()->id && ! $fridge->isSharedWith($request->user())) {
            abort(403);
        }

        $items = $fridge->items()->whereNotNull('used_at')->latest('used_at')->limit(100)->get();

        return view('fridges.history', compact('fridge', 'items'));
    }
}
