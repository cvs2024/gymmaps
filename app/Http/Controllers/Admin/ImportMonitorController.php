<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RawImport;
use Illuminate\Http\Request;

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
}
