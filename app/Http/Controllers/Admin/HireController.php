<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Hire;
use App\Models\Talent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $query = Hire::query()->with(['talent', 'company', 'job']);

        if ($request->filled('source')) {
            $query->where('source', $request->input('source'));
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->integer('company_id'));
        }

        if ($request->filled('talent_id')) {
            $query->where('talent_id', $request->integer('talent_id'));
        }

        if ($request->filled('from')) {
            $query->whereDate('hired_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('hired_at', '<=', $request->input('to'));
        }

        $hires = $query->latest('hired_at')->paginate(20)->withQueryString();

        $stats = [
            'total' => Hire::count(),
            'this_month' => Hire::where('hired_at', '>=', now()->startOfMonth())->count(),
            'applications' => Hire::where('source', Hire::SOURCE_APPLICATION)->count(),
            'pitches' => Hire::where('source', Hire::SOURCE_PITCH)->count(),
        ];

        return view('admin.pages.hires.index', [
            'hires' => $hires,
            'stats' => $stats,
            'sources' => Hire::sourceLabels(),
            'companies' => Company::query()->orderBy('name')->get(['id', 'name']),
            'talents' => Talent::query()->active()->orderBy('name')->limit(100)->get(['id', 'name']),
        ]);
    }
}
