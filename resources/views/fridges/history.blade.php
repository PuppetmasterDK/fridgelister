@extends('layouts.app')

@section('content')
<h1>{{ $fridge->name }}: history</h1>
<p><a href="{{ route('fridges.show', $fridge) }}">← Back to the fridge</a></p>
<div class="card">
    <table>
        <thead><tr><th>Item</th><th>Used</th></tr></thead>
        <tbody>
        @forelse ($items as $item)
            <tr><td>{{ $item->name }}</td><td>{{ $item->used_at->format('j M, H:i') }}</td></tr>
        @empty
            <tr><td colspan="2" class="muted">Nothing used yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
