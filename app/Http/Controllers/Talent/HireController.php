<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\Hire;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HireController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.user.active', 'role:talent']);
    }

    public function index(Request $request): View
    {
        $talent = $request->user()->talent;

        $stats = [
            'total' => 0,
            'this_month' => 0,
            'companies' => 0,
            'jobs' => 0,
        ];

        $hires = Hire::query()
            ->when($talent, fn ($q) => $q->where('talent_id', $talent->id), fn ($q) => $q->whereRaw('1 = 0'))
            ->with(['company', 'job'])
            ->latest('hired_at')
            ->paginate(15);

        if ($talent) {
            $base = Hire::query()->where('talent_id', $talent->id);
            $stats['total'] = (clone $base)->count();
            $stats['this_month'] = (clone $base)
                ->whereMonth('hired_at', now()->month)
                ->whereYear('hired_at', now()->year)
                ->count();
            $stats['companies'] = (clone $base)->whereNotNull('company_id')->distinct('company_id')->count('company_id');
            $stats['jobs'] = (clone $base)->whereNotNull('job_listing_id')->distinct('job_listing_id')->count('job_listing_id');
        }

        return view('talents.pages.hires.index', [
            'talent' => $talent,
            'hires' => $hires,
            'stats' => $stats,
        ]);
    }
}
