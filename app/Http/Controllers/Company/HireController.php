<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Hire;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HireController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.user.active', 'role:company']);
    }

    public function index(Request $request): View
    {
        $company = $request->user()->company;

        $hires = Hire::query()
            ->when($company, fn ($q) => $q->where('company_id', $company->id), fn ($q) => $q->whereRaw('1 = 0'))
            ->with(['talent', 'job'])
            ->latest('hired_at')
            ->paginate(15);

        $stats = [
            'total' => $company ? $company->hires()->count() : 0,
            'this_month' => $company
                ? $company->hires()->where('hired_at', '>=', now()->startOfMonth())->count()
                : 0,
        ];

        return view('company.pages.hires.index', [
            'company' => $company,
            'hires' => $hires,
            'stats' => $stats,
        ]);
    }
}
