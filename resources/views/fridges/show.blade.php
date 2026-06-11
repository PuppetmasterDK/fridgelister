@extends('layouts.app')

@section('title', $fridge->name.' · FridgeLister')

@section('content')
<h1>{{ $fridge->name }}</h1>
<p>
</p>

<div class="card">

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

@endsection
