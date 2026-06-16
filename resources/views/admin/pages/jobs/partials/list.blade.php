<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 260px;">الوظيفة</th>
                    <th style="min-width: 140px;">الشركة</th>
                    <th style="min-width: 120px;">الراتب</th>
                    <th style="min-width: 100px;">Remote</th>
                    <th style="min-width: 80px;">مميزة</th>
                    <th style="min-width: 100px;">الحالة</th>
                    <th style="min-width: 150px;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jobs as $job)
                    <tr>
                        <td class="text-muted fw-medium">{{ $jobs->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}" style="font-size:1.1rem;">
                                    {{ $job->logo ?: '💼' }}
                                </span>
                                <div>
                                    <a href="{{ route('admin.jobs.edit', $job) }}"
                                       class="fw-bold row-title-link text-decoration-none d-block">
                                        {{ $job->title }}
                                    </a>
                                    <span class="text-muted fs-11">{{ $job->location }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $job->company_name }}</td>
                        <td>
                            <span class="badge-soft badge-soft-info">{{ $job->salary_display }} {{ $job->currency }}</span>
                        </td>
                        <td>
                            <span class="badge-soft badge-soft-primary">{{ $job->remote_type === 'full-remote' ? 'عن بُعد' : $job->remote_type }}</span>
                        </td>
                        <td>
                            @if ($job->is_featured)
                                <span class="badge-soft badge-soft-warning">نعم</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">لا</span>
                            @endif
                        </td>
                        <td>
                            @if ($job->is_active)
                                <span class="badge-soft badge-soft-success">نشط</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">غير نشط</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <a href="{{ route('jobs.show', $job) }}" target="_blank"
                                   class="action-btn action-btn--view" title="عرض">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('admin.jobs.edit', $job) }}"
                                   class="action-btn action-btn--edit" title="تعديل">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('admin.jobs.toggle-active', $job) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="action-btn action-btn--success {{ $job->is_active ? 'is-active' : '' }}"
                                            title="{{ $job->is_active ? 'تعطيل' : 'تفعيل' }}">
                                        <i class="ri-{{ $job->is_active ? 'checkbox-circle-fill' : 'checkbox-circle-line' }}"></i>
                                    </button>
                                </form>
                                <button type="button"
                                        class="action-btn action-btn--delete"
                                        title="حذف"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteJobModal"
                                        data-job-id="{{ $job->id }}"
                                        data-job-name="{{ $job->title }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-briefcase-line"></i></div>
                                <h5 class="fw-bold mb-2">لا توجد وظائف</h5>
                                <p class="text-muted mb-3">لم يتم العثور على وظائف مطابقة.</p>
                                <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary btn-sm">
                                    <i class="ri-add-line me-1"></i> إضافة وظيفة جديدة
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($jobs->hasPages())
        <div class="card-footer border-top bg-transparent py-3">
            {{ $jobs->links() }}
        </div>
    @endif
</div>
