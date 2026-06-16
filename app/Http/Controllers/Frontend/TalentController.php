<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Talent;
use Illuminate\Http\Request;

class TalentController extends Controller
{
    public function index(Request $request)
    {
        $query = Talent::query()->active()->ordered();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhereJsonContains('skills', $search);
            });
        }

        $talents = $query->get();
        $talentsJson = $talents->map(fn (Talent $talent) => $talent->toFrontendArray())->values();

        return view('frontend.pages.talents', [
            'activePage' => 'talents',
            'talents' => $talents,
            'talentsJson' => $talentsJson,
            'searchQuery' => $request->input('q', ''),
        ]);
    }

    public function show(Talent $talent)
    {
        abort_unless($talent->is_active, 404);

        return view('frontend.pages.talent-profile', [
            'activePage' => 'talents',
            'talent' => $talent,
        ]);
    }
}
