@extends('layouts.app')

@section('content')
<h1>What to buy for {{ $fridge->name }}</h1>
<p><a href="{{ route('fridges.show', $fridge) }}">← Back to the fridge</a></p>
<div class="card">
    @forelse ($suggestions as $suggestion)
        <p><strong>{{ $suggestion['name'] }}</strong> <span class="muted">· used {{ $suggestion['times_used'] }} times in the last month · {{ $suggestion['reason'] }}</span></p>
    @empty
        <p class="muted">Nothing to buy right now.</p>
    @endforelse
</div>
@endsection
