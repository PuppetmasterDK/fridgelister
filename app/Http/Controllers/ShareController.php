<?php

namespace App\Http\Controllers;

use App\Models\Fridge;
use App\Models\Share;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShareController extends Controller
{
    public function store(Request $request, Fridge $fridge)
    {
        if ($fridge->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = strtolower($request->input('email'));

        if ($email === strtolower($request->user()->email)) {
            return back()->withErrors(['email' => 'You already have this fridge.']);
        }

        $existing = $fridge->shares()->where('email', $email)->whereIn('status', ['pending', 'accepted'])->first();
        if ($existing) {
            return back()->withErrors(['email' => 'This fridge is already shared with '.$email.'.']);
        }

        $fridge->shares()->create([
            'email' => $email,
            'user_id' => User::where('email', $email)->value('id'),
            'status' => 'pending',
        ]);

        Log::info('Fridge shared', ['fridge' => $fridge->id, 'email' => $email]);

        return back()->with('status', 'Invitation sent to '.$email.'.');
    }

    public function respond(Request $request, Share $share)
    {
        if (strtolower($share->email) !== strtolower($request->user()->email)) {
            abort(403);
        }

        if ($request->input('answer') === 'accept') {
            $share->update(['status' => 'accepted', 'user_id' => $request->user()->id, 'responded_at' => now()]);

            return redirect()->route('fridges.show', $share->fridge_id)->with('status', 'Thank you!');
        }

        $share->update(['status' => 'declined', 'responded_at' => now()]);

        return redirect()->route('fridges.index')->with('status', 'Invitation declined.');
    }
}
