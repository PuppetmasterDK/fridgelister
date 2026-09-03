<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FridgeLister')</title>
    <style>
        :root { --ink:#1f2933; --muted:#616e7c; --line:#d9e2ec; --bg:#f8fafc; --accent:#2f6f5e; --warn:#b7791f; --bad:#c53030; }
        * { box-sizing: border-box; }
        body { margin:0; font: 17px/1.5 system-ui, sans-serif; color:var(--ink); background:var(--bg); }
        header { background:#fff; border-bottom:1px solid var(--line); padding:12px 20px; display:flex; gap:20px; align-items:center; }
        header strong { color:var(--accent); font-size:20px; }
        header nav { display:flex; gap:16px; flex:1; }
        main { max-width:860px; margin:0 auto; padding:24px 20px 60px; }
        a { color:var(--accent); }
        h1 { font-size:28px; margin:0 0 16px; }
        .card { background:#fff; border:1px solid var(--line); border-radius:8px; padding:16px; margin-bottom:16px; }
        .flash { background:#e6f4ef; border:1px solid #b5dccd; padding:10px 14px; border-radius:6px; margin-bottom:16px; }
        .errors { background:#fdecec; border:1px solid #f5bcbc; padding:10px 14px; border-radius:6px; margin-bottom:16px; }
        table { width:100%; border-collapse:collapse; }
        th, td { text-align:left; padding:8px 6px; border-bottom:1px solid var(--line); vertical-align:middle; }
        .badge { display:inline-block; padding:2px 10px; border-radius:99px; font-size:14px; font-weight:600; }
        .badge.expired { background:#fdecec; color:var(--bad); }
        .badge.use_soon { background:#fdf3e1; color:var(--warn); }
        .badge.fresh { background:#e6f4ef; color:var(--accent); }
        input, select, button { font:inherit; padding:6px 10px; border:1px solid var(--line); border-radius:6px; }
        button { background:var(--accent); color:#fff; border-color:var(--accent); cursor:pointer; }
        button.plain { background:#fff; color:var(--ink); border-color:var(--line); }
        form.inline { display:inline; }
        .row { display:flex; gap:8px; flex-wrap:wrap; align-items:end; }
        .muted { color:var(--muted); }
        .stats { display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:12px; }
        .stats .card strong { display:block; font-size:32px; }
    </style>
</head>
<body>
@auth
<header>
    <strong>FridgeLister</strong>
    <nav>
        <a href="{{ route('dashboard') }}">Overview</a>
        <a href="{{ route('fridges.index') }}">Fridges</a>
    </nav>
    <span class="muted">{{ auth()->user()->name }}</span>
    <form class="inline" method="POST" action="{{ route('logout') }}">@csrf<button class="plain">Log out</button></form>
</header>
@endauth
<main>
    @if (session('status'))
        <div class="flash">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="errors">@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
    @endif
    @yield('content')
</main>
</body>
</html>
