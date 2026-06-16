@extends('admin.layouts.master')

@section('page-title')
طلبات الاستشارة
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h4 class="mb-0">طلبات الاستشارة</h4>
                <p class="mb-0 text-muted">طلبات حجز موعد استشارة من صفحة حجز موعد</p>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card custom-card mb-4">
            <div class="card-header">
                <div class="card-title">فلترة وبحث</div>
            </div>
            <div class="card-body">
                <form method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">بحث</label>
                            <input type="text" name="search" class="form-control" placeholder="الاسم، البريد، أو موضوع الاستشارة..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">نوع الاستشارة</label>
                            <select name="consultation_type" class="form-select">
                                <option value="">الكل</option>
                                <option value="quick" {{ request('consultation_type') === 'quick' ? 'selected' : '' }}>استشارة سريعة</option>
                                <option value="deep" {{ request('consultation_type') === 'deep' ? 'selected' : '' }}>استشارة معمقة</option>
                                <option value="code_review" {{ request('consultation_type') === 'code_review' ? 'selected' : '' }}>مراجعة مشروع / كود</option>
                                <option value="learning_path" {{ request('consultation_type') === 'learning_path' ? 'selected' : '' }}>تخطيط مسار تعلم</option>
                                <option value="other" {{ request('consultation_type') === 'other' ? 'selected' : '' }}>أخرى</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">الحالة</label>
                            <select name="read" class="form-select">
                                <option value="">الكل</option>
                                <option value="unread" {{ request('read') === 'unread' ? 'selected' : '' }}>غير مقروءة</option>
                                <option value="read" {{ request('read') === 'read' ? 'selected' : '' }}>مقروءة</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search me-1"></i> بحث
                            </button>
                            <a href="{{ route('admin.consultation-requests.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">قائمة الطلبات ({{ $requests->total() }})</div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th>التاريخ</th>
                                <th>الاسم</th>
                                <th>البريد</th>
                                <th>نوع الاستشارة</th>
                                <th>مقروءة</th>
                                <th width="120">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                            <tr class="{{ !$req->is_read ? 'table-warning' : '' }}">
                                <td>{{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}</td>
                                <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>
                                <td><strong>{{ $req->name }}</strong></td>
                                <td>{{ $req->email }}</td>
                                <td><span class="badge bg-primary-transparent">{{ \App\Models\ConsultationRequest::consultationTypeLabel($req->consultation_type) }}</span></td>
                                <td>
                                    @if($req->is_read)
                                        <span class="badge bg-success-transparent text-success">مقروءة</span>
                                    @else
                                        <span class="badge bg-warning-transparent text-warning">جديدة</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.consultation-requests.show', $req) }}" class="btn btn-sm btn-primary-light" title="عرض">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.consultation-requests.destroy', $req) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger-light" title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-calendar-check display-4 text-muted"></i>
                                    <p class="text-muted mt-3">لا توجد طلبات استشارة حالياً</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($requests->hasPages())
            <div class="card-footer">
                {{ $requests->links() }}
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
