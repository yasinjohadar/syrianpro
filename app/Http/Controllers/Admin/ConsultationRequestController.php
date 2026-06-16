<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultationRequest;
use Illuminate\Http\Request;

class ConsultationRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ConsultationRequest::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('topic', 'like', "%{$search}%");
            });
        }

        if ($request->filled('consultation_type')) {
            $query->where('consultation_type', $request->consultation_type);
        }

        if ($request->filled('read')) {
            if ($request->read === 'read') {
                $query->where('is_read', true);
            } elseif ($request->read === 'unread') {
                $query->where('is_read', false);
            }
        }

        $requests = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.consultation-requests.index', compact('requests'));
    }

    public function show(ConsultationRequest $consultationRequest)
    {
        $consultationRequest->markAsRead();

        return view('admin.consultation-requests.show', compact('consultationRequest'));
    }

    public function destroy(ConsultationRequest $consultationRequest)
    {
        $consultationRequest->delete();

        return redirect()->route('admin.consultation-requests.index')
            ->with('success', 'تم حذف طلب الاستشارة بنجاح.');
    }
}
