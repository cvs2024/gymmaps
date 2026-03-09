<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RawImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ImportMonitorController extends Controller
{
    public function index(Request $request)
    {
        $status = (string) $request->query('status', 'all');
        $allowed = ['all', 'pending', 'processed', 'failed', 'skipped'];
        if (!in_array($status, $allowed, true)) {
            $status = 'all';
        }

        $rawImports = RawImport::query()
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->orderByDesc('id')
            ->paginate(50)
            ->withQueryString();

        return view('admin.imports.index', [
            'status' => $status,
            'rawImports' => $rawImports,
        ]);
    }

    public function runKvkImport(Request $request)
    {
        $validated = $request->validate([
            'pages' => ['nullable', 'integer', 'min:0', 'max:250'],
            'max_pages' => ['nullable', 'integer', 'min:1', 'max:500'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $pages = (int) ($validated['pages'] ?? 0);
        $maxPages = (int) ($validated['max_pages'] ?? 250);
        $perPage = (int) ($validated['per_page'] ?? 100);

        $exitCode = Artisan::call('gymmap:import-kvk-sports', [
            '--pages' => $pages,
            '--max-pages' => $maxPages,
            '--per-page' => $perPage,
        ]);

        $output = trim(Artisan::output());
        $statusLine = $exitCode === 0
            ? 'KVK import succesvol gestart/afgerond.'
            : 'KVK import gaf een foutmelding.';

        return redirect()
            ->route('admin.imports.index')
            ->with('import_status', $statusLine)
            ->with('import_output', $output);
    }
}
