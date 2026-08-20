<?php

namespace App\Http\Controllers;

use App\Models\Fridge;
use App\Services\RestockSuggester;
use Illuminate\Http\Request;

class RestockController extends Controller
{
    public function __invoke(Request $request, Fridge $fridge, RestockSuggester $suggester)
    {
        if ($fridge->user_id !== $request->user()->id && ! $fridge->isSharedWith($request->user())) {
            abort(403);
        }

        return view('fridges.restock', [
            'fridge' => $fridge,
            'suggestions' => $suggester->suggest($fridge),
        ]);
    }
}
