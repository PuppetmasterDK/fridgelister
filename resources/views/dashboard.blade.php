@extends('layouts.app')

@section('content')
<h1>Hello, {{ auth()->user()->name }}</h1>
<div class="stats">
    <div class="card"><strong>{{ $total }}</strong> things in your fridges</div>
    <div class="card"><strong>{{ $expired }}</strong> past their best</div>
    <div class="card"><strong>{{ $usedThisWeek }}</strong> used this week</div>
    <div class="card"><strong>{{ $pendingShares }}</strong> invitations waiting</div>
</div>
<p><a href="{{ route('fridges.index') }}">Go to your fridges →</a></p>
@endsection
