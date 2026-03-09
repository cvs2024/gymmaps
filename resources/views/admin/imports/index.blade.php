<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Import Monitor - Gymmap</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/gymmaps-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/gymmaps-logo.png') }}">
    <style>
        body { margin:0; font-family: "Segoe UI", Roboto, sans-serif; background:#f5f8fc; color:#0e2235; }
        .container { max-width: 1100px; margin: 0 auto; padding: 24px 16px 40px; }
        .card { background:#fff; border:1px solid #d6e1ec; border-radius: 12px; padding: 16px; margin-bottom: 12px; }
        h1 { margin:0 0 8px; }
        .muted { color:#5d6f82; }
        .toolbar { display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
        select, button { border:1px solid #bfd0e1; border-radius:8px; padding:8px 10px; font:inherit; }
        input[type="number"] { border:1px solid #bfd0e1; border-radius:8px; padding:8px 10px; font:inherit; width: 100px; }
        button { background:#0f8a5f; color:#fff; border:none; cursor:pointer; }
        .button-secondary { background:#0d3655; }
        table { width:100%; border-collapse: collapse; font-size: 0.92rem; }
        th, td { text-align:left; padding:10px; border-bottom:1px solid #e6edf5; vertical-align:top; }
        th { color:#44596e; }
        .pill { display:inline-block; border-radius:999px; padding:3px 8px; font-size:0.78rem; }
        .pill.pending { background:#fff5d9; color:#7f6300; }
        .pill.processed { background:#e8f7f0; color:#176647; }
        .pill.failed { background:#ffe8e8; color:#8b1e1e; }
        .pill.skipped { background:#eef0f3; color:#516172; }
        code { background:#f2f6fb; padding:2px 5px; border-radius:6px; }
        pre { margin: 10px 0 0; background:#0f2234; color:#d8ecff; padding:12px; border-radius:8px; overflow:auto; font-size:0.85rem; }
    </style>
</head>
<body>
@include('partials.site-header')
<div class="container">
    @if(session('import_status'))
        <div class="card">
            <strong>{{ session('import_status') }}</strong>
            @if(session('import_output'))
                <pre>{{ session('import_output') }}</pre>
            @endif
        </div>
    @endif

    <div class="card">
        <h1>Import Monitor</h1>
        <p class="muted">Overzicht van KVK-imports, inclusief foutmeldingen en verwerkingsstatus.</p>
        <form method="GET" class="toolbar">
            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="all" @selected($status === 'all')>Alle</option>
                <option value="pending" @selected($status === 'pending')>Pending</option>
                <option value="processed" @selected($status === 'processed')>Processed</option>
                <option value="failed" @selected($status === 'failed')>Failed</option>
                <option value="skipped" @selected($status === 'skipped')>Skipped</option>
            </select>
            <button type="submit">Filter</button>
            <span class="muted">Totaal: {{ $rawImports->total() }}</span>
        </form>
    </div>

    <div class="card">
        <h2 style="margin:0 0 8px;">KVK import uitvoeren</h2>
        <p class="muted" style="margin:0 0 12px;">Start direct een nieuwe KVK-import zonder terminal.</p>
        <form method="POST" action="{{ route('admin.imports.run-kvk') }}" class="toolbar">
            @csrf
            <label for="pages">Pages</label>
            <input id="pages" name="pages" type="number" min="0" max="250" value="0">
            <label for="max_pages">Max pages</label>
            <input id="max_pages" name="max_pages" type="number" min="1" max="500" value="250">
            <label for="per_page">Per page</label>
            <input id="per_page" name="per_page" type="number" min="1" max="100" value="100">
            <button class="button-secondary" type="submit">KVK import draaien</button>
        </form>
        @if($errors->any())
            <p class="muted" style="margin-top:10px;">{{ $errors->first() }}</p>
        @endif
    </div>

    <div class="card" style="overflow:auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Source</th>
                    <th>External ID</th>
                    <th>Status</th>
                    <th>Fout</th>
                    <th>Processed</th>
                    <th>Aangemaakt</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rawImports as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td><code>{{ $row->source }}</code></td>
                        <td>{{ $row->external_id ?: '-' }}</td>
                        <td><span class="pill {{ $row->status }}">{{ $row->status }}</span></td>
                        <td class="muted">{{ $row->error_message ?: '-' }}</td>
                        <td class="muted">{{ optional($row->processed_at)->format('Y-m-d H:i:s') ?: '-' }}</td>
                        <td class="muted">{{ $row->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted">Geen importregels gevonden.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="muted">
        {{ $rawImports->links() }}
    </div>
</div>
@include('partials.site-footer')
</body>
</html>
