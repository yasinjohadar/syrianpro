<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 260px;">الوظيفة</th>
                    <th style="min-width: 120px;">الراتب</th>
                    <th style="min-width: 100px;">نوع العمل</th>
                    <th style="min-width: 90px;">المتقدمون</th>
                    <th style="min-width: 100px;">الحالة</th>
                    <th style="min-width: 160px;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jobs as $job)
                    <tr>
                        <td class="text-muted fw-medium">{{ $jobs->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}" style="font-size:1.1rem;">
                                    @if($job->logo_image)
                                        <img src="{{ $job->logoUrl() }}" alt="" class="rounded" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        {{ $job->logo ?: '💼' }}
                                    @endif
                                </span>
                                <div>
                                    <a href="{{ route('company.jobs.edit', $job) }}"
                                       class="fw-bold row-title-link text-decoration-none d-block">
                                        {{ $job->title }}
                                    </a>
                                    <span class="text-muted fs-11">{{ $job->location }} · {{ $job->relative_date }}</span>
                                    @if($job->is_new)
                                        <span class="badge-soft badge-soft-warning ms-1">جديد</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-soft badge-soft-info">{{ $job->salary_display }} {{ $job->currency }}</span>
                        </td>
                        <td>
                            @php
                                $remoteLabel = match($job->remote_type) {
                                    'full-remote' => 'عن بُعد',
                                    'hybrid' => 'هجين',
                                    'onsite' => 'حضوري',
                                    default => $job->remote_type,
                                };
                            @endphp
                            <span class="badge-soft badge-soft-primary">{{ $remoteLabel }}</span>
                        </td>
                        <td>
                            @if ($job->applications_count > 0)
                                <a href="{{ route('company.applications.index', ['job_id' => $job->id]) }}"
                                   class="badge-soft badge-soft-info text-decoration-none">
                                    {{ $job->applications_count }}
                                </a>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td>
                            @if ($job->is_active)
                                <span class="badge-soft badge-soft-success">نشط</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">متوقف</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btn-group">
                                @if($job->slug)
                                    <a href="{{ route('jobs.show', $job) }}" target="_blank"
                                       class="action-btn action-btn--view" title="عرض عام">
                                        <i class="ri-external-link-line"></i>
                                    </a>
                                @endif
                                <a href="{{ route('company.jobs.edit', $job) }}"
                                   class="action-btn action-btn--edit" title="تعديل">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('company.jobs.toggle-active', $job) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="action-btn action-btn--success {{ $job->is_active ? 'is-active' : '' }}"
                                            title="{{ $job->is_active ? 'إيقاف' : 'تفعيل' }}">
                                        <i class="ri-{{ $job->is_active ? 'pause-circle-fill' : 'play-circle-line' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('company.jobs.destroy', $job) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('حذف هذه الوظيفة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn--delete" title="حذف">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-briefcase-line"></i></div>
                                <h5 class="fw-bold mb-2">لا توجد وظائف</h5>
                                <p class="text-muted mb-3">ابدأ بنشر أول فرصة عمل عن بُعد لجذب المواهب السورية.</p>
                                <a href="{{ route('company.jobs.create') }}" class="btn btn-primary btn-sm">
                                    <i class="ri-add-line me-1"></i> أضف وظيفة جديدة
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
