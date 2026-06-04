@extends('layouts.app')

@section('title', 'Log in · FridgeLister')

@section('content')
<h1>FridgeLister</h1>
<p class="muted">Know what is in the fridge, and who is doing the shopping.</p>
<div class="card">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <p><label>Email<br><input type="email" name="email" value="{{ old('email') }}" required autofocus></label></p>
        <p><label>Password<br><input type="password" name="password" required></label></p>
        <button>Log in</button>
    </form>
</div>
@endsection
