<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Models\Fridge;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function store(StoreItemRequest $request, Fridge $fridge)
    {
        $fridge->items()->create([
            'name' => $request->input('name'),
            'quantity' => $request->input('quantity', 1),
            'unit' => $request->input('unit'),
            'best_before' => $request->input('best_before'),
            'added_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Added '.$request->input('name').'.');
    }

    public function markUsed(Request $request, Item $item)
    {
        $this->authorizeItem($request, $item);

        $item->update(['used_at' => now()]);

        return back()->with('status', $item->name.' marked as used.');
    }

    public function destroy(Request $request, Item $item)
    {
        $this->authorizeItem($request, $item);

        $item->delete();

        return back()->with('status', $item->name.' removed.');
    }

    private function authorizeItem(Request $request, Item $item): void
    {
        $fridge = $item->fridge;
        if ($fridge->user_id !== $request->user()->id && ! $fridge->isSharedWith($request->user())) {
            abort(403);
        }
    }
}
