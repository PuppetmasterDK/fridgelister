@extends('layouts.app')

@section('content')
<h1>Fridges</h1>

@if ($invitations->isNotEmpty())
<div class="card">
    <h2>Invitations</h2>
    @foreach ($invitations as $invitation)
        <form method="POST" action="{{ route('shares.respond', $invitation) }}" class="row">
            @csrf
            <span>{{ $invitation->fridge->owner->name }} asks you to help with <strong>{{ $invitation->fridge->name }}</strong>.</span>
            <label>Shop by <input type="date" name="shop_by"></label>
            <button name="answer" value="accept">Accept</button>
            <button name="answer" value="decline" class="plain">Decline</button>
        </form>
    @endforeach
</div>
@endif

<div class="card">
    <h2>Your fridges</h2>
    @forelse ($own as $fridge)
        <p><a href="{{ route('fridges.show', $fridge) }}">{{ $fridge->name }}</a> <span class="muted">· {{ $fridge->items_count }} items</span></p>
    @empty
        <p class="muted">No fridges yet.</p>
    @endforelse
    <form method="POST" action="{{ route('fridges.store') }}" class="row">
        @csrf
        <input name="name" placeholder="Kitchen fridge" required>
        <button>Add fridge</button>
    </form>
</div>

@if ($shared->isNotEmpty())
<div class="card">
    <h2>Shared with you</h2>
    @foreach ($shared as $fridge)
        <p><a href="{{ route('fridges.show', $fridge) }}">{{ $fridge->name }}</a> <span class="muted">· {{ $fridge->owner->name }} · {{ $fridge->items_count }} items</span></p>
    @endforeach
</div>
@endif
@endsection
