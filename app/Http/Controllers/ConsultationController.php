<?php

namespace App\Http\Controllers;

use App\Models\ConsultationRequest;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * Store a consultation form submission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'consultation_type' => 'required|in:quick,deep,code_review,learning_path,other',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'preferred_time' => 'nullable|in:morning,afternoon,evening,flexible',
            'topic' => 'required|string|max:5000',
            'notes' => 'nullable|string|max:2000',
        ]);

        ConsultationRequest::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'message' => 'تم إرسال طلبك بنجاح. سنتواصل معك قريباً.']);
        }

        return redirect()->route('consultation')
            ->with('success', 'تم إرسال طلبك بنجاح. سنتواصل معك قريباً.');
    }
}
