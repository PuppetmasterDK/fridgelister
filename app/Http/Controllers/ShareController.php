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
        $user = $request->user();

        if (strtolower($share->email) !== strtolower($user->email)) {
            abort(403);
        }

        if ($share->status !== 'pending') {
            return redirect()->route('fridges.index')->withErrors(['share' => 'You have already answered this invitation.']);
        }

        $answer = $request->input('answer');

        if ($answer === 'accept') {
            $shopBy = $request->input('shop_by');
            if (! $shopBy) {
                return back()->withErrors(['shop_by' => 'Please choose the date you will do the shopping by.']);
            }

            try {
                $date = Carbon::parse($shopBy);
            } catch (\Exception $e) {
                return back()->withErrors(['shop_by' => 'That is not a date we understand.']);
            }

            if ($date->isPast() && ! $date->isToday()) {
                return back()->withErrors(['shop_by' => 'The shopping date cannot be in the past.']);
            }

            if ($date->gt(now()->addDays(14))) {
                return back()->withErrors(['shop_by' => 'Please choose a date within the next two weeks.']);
            }

            $share->update([
                'status' => 'accepted',
                'user_id' => $user->id,
                'shop_by' => $date,
                'responded_at' => now(),
            ]);

            return redirect()->route('fridges.show', $share->fridge_id)
                ->with('status', 'Thank you! You will shop for this fridge by '.$this->formatShopBy($date).'.');
        } elseif ($answer === 'decline') {
            $share->update([
                'status' => 'declined',
                'responded_at' => now(),
            ]);

            return redirect()->route('fridges.index')->with('status', 'Invitation declined.');
        }

        return back()->withErrors(['answer' => 'Please accept or decline.']);
    }

    private function formatShopBy(Carbon $date): string
    {
        $days = (int) now()->startOfDay()->diffInDays($date->copy()->startOfDay(), false);

        if ($days === 0) {
            return 'today';
        }
        if ($days === 1) {
            return 'tomorrow';
        }
        if ($days < 7) {
            return 'in '.$days.' days';
        }

        return $date->format('j M');
    }
}
