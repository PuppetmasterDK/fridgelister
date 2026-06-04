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

        $items = $fridge->items()->whereNull('used_at')->orderBy('best_before')->get();

        return view('fridges.show', [
            'fridge' => $fridge,
            'items' => $items,
        ]);
    }
}
