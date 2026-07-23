@extends('layouts.app')

@section('title', $fridge->name.' · FridgeLister')

@section('content')
<h1>{{ $fridge->name }}</h1>
<p>
    <a href="{{ route('fridges.history', $fridge) }}">History</a>
</p>

<div class="card">
    <div class="row">
        <span>Show:</span>
        <a href="{{ route('fridges.show', [$fridge, 'sort' => $sort]) }}">All</a>
        <a href="{{ route('fridges.show', [$fridge, 'status' => 'expired', 'sort' => $sort]) }}">Past best before ({{ $counts['expired'] }})</a>
        <a href="{{ route('fridges.show', [$fridge, 'status' => 'use_soon', 'sort' => $sort]) }}">Use soon ({{ $counts['use_soon'] }})</a>
        <a href="{{ route('fridges.show', [$fridge, 'status' => 'fresh', 'sort' => $sort]) }}">Fresh ({{ $counts['fresh'] }})</a>
    </div>
    <div class="row">
        <span>Sort:</span>
        <a href="{{ route('fridges.show', [$fridge, 'status' => $filter, 'sort' => 'best_before']) }}">Best before</a>
        <a href="{{ route('fridges.show', [$fridge, 'status' => $filter, 'sort' => 'name']) }}">Name</a>
        <a href="{{ route('fridges.show', [$fridge, 'status' => $filter, 'sort' => 'added']) }}">Newest</a>
    </div>

    <table>
        <thead><tr><th>Item</th><th>Amount</th><th>Best before</th><th></th><th></th></tr></thead>
        <tbody>
        @forelse ($items as $item)
            <tr>
                <td>{{ $item->name }}<br><span class="muted">added by {{ $item->addedBy?->name }}</span></td>
                <td>{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }} {{ $item->unit }}</td>
                <td>{{ $item->best_before?->format('j M') }}</td>
                <td><span class="badge {{ $item->status }}">{{ \App\Support\Freshness::label($item->status) }}</span></td>
                <td>
                    <form class="inline" method="POST" action="{{ route('items.use', $item) }}">@csrf<button>Used</button></form>
                    <form class="inline" method="POST" action="{{ route('items.destroy', $item) }}">@csrf @method('DELETE')<button class="plain">Remove</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">Nothing here.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h2>Add something</h2>
    <form method="POST" action="{{ route('items.store', $fridge) }}" class="row">
        @csrf
        <label>What<br><input name="name" required></label>
        <label>Amount<br><input name="quantity" type="number" step="0.01" value="1" style="width:6em"></label>
        <label>Unit<br><input name="unit" placeholder="litres" style="width:7em"></label>
        <label>Best before<br><input name="best_before" type="date"></label>
        <button>Add</button>
    </form>
</div>

@if ($isOwner)
<div class="card">
    <h2>Helpers</h2>
    @foreach ($shares as $share)
        <p>{{ $share->email }} <span class="muted">· {{ $share->status }}@if ($share->shop_by) · shops by {{ $share->shop_by->format('j M') }}@endif</span></p>
    @endforeach
    <form method="POST" action="{{ route('shares.store', $fridge) }}" class="row">
        @csrf
        <input type="email" name="email" placeholder="helper@example.com" required>
        <button>Invite</button>
    </form>
</div>
@endif
@endsection
