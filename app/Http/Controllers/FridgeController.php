<?php

namespace App\Http\Controllers;

use App\Models\Fridge;
use App\Models\Share;
use Illuminate\Http\Request;

class FridgeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $own = $user->fridges()->withCount(['items' => fn ($q) => $q->whereNull('used_at')])->get();

        $sharedIds = Share::where('user_id', $user->id)->where('status', 'accepted')->pluck('fridge_id');
        $shared = Fridge::whereIn('id', $sharedIds)
            ->withCount(['items' => fn ($q) => $q->whereNull('used_at')])
            ->with('owner')
            ->get();

        $invitations = Share::with('fridge.owner')
            ->where('email', $user->email)
            ->where('status', 'pending')
            ->get();

        return view('fridges.index', compact('own', 'shared', 'invitations'));
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
        $user = $request->user();

        // Only the owner and people the fridge is shared with may see it.
        if ($fridge->user_id !== $user->id) {
            $share = $fridge->shares()->where('user_id', $user->id)->first();
            if (! $share || $share->status !== 'accepted') {
                abort(403);
            }
        }

        $items = $fridge->items()->whereNull('used_at')->with('addedBy')->get();

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

        // Filter by status if the user picked one.
        $filter = $request->query('status');
        if ($filter && in_array($filter, ['expired', 'use_soon', 'fresh'])) {
            $items = $items->filter(fn ($item) => $item->status === $filter);
        }

        // Sorting
        $sort = $request->query('sort', 'best_before');
        if ($sort === 'name') {
            $items = $items->sortBy(fn ($item) => mb_strtolower($item->name));
        } elseif ($sort === 'added') {
            $items = $items->sortByDesc('created_at');
        } else {
            $items = $items->sortBy('best_before');
        }

        $isOwner = $fridge->user_id === $user->id;
        $shares = $isOwner ? $fridge->shares()->latest()->get() : collect();

        return view('fridges.show', [
            'fridge' => $fridge,
            'items' => $items->values(),
            'counts' => $counts,
            'filter' => $filter,
            'sort' => $sort,
            'isOwner' => $isOwner,
            'shares' => $shares,
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
