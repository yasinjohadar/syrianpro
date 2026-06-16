<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 220px;">التخصص</th>
                    <th style="min-width: 120px;">عدد الوظائف</th>
                    <th style="min-width: 80px;">الترتيب</th>
                    <th style="min-width: 100px;">الرئيسية</th>
                    <th style="min-width: 100px;">الحالة</th>
                    <th style="min-width: 150px;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($specialties as $specialty)
                    <tr>
                        <td class="text-muted fw-medium">{{ $specialties->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if ($specialty->image)
                                    <img src="{{ $specialty->iconUrl() }}" class="row-avatar row-avatar--img" alt="">
                                @else
                                    <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}" style="font-size:1.1rem;">
                                        {{ $specialty->icon ?: mb_strtoupper(mb_substr($specialty->name, 0, 1)) }}
                                    </span>
                                @endif
                                <div>
                                    <a href="{{ route('admin.tech-specialties.edit', $specialty) }}"
                                       class="fw-bold row-title-link text-decoration-none d-block">
                                        {{ $specialty->name }}
                                    </a>
                                    <span class="text-muted fs-11" dir="ltr">{{ $specialty->slug }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-soft badge-soft-info">{{ $specialty->jobs_count_label }}</span>
                        </td>
                        <td>
                            <span class="meta-text"><i class="ri-sort-asc"></i> {{ $specialty->order }}</span>
                        </td>
                        <td>
                            @if ($specialty->show_on_home)
                                <span class="badge-soft badge-soft-primary">نعم</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">لا</span>
                            @endif
                        </td>
                        <td>
                            @if ($specialty->is_active)
                                <span class="badge-soft badge-soft-success">نشط</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">غير نشط</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <a href="{{ route('admin.tech-specialties.edit', $specialty) }}"
                                   class="action-btn action-btn--edit" title="تعديل">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('admin.tech-specialties.toggle-active', $specialty) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="action-btn action-btn--success {{ $specialty->is_active ? 'is-active' : '' }}"
                                            title="{{ $specialty->is_active ? 'تعطيل' : 'تفعيل' }}">
                                        <i class="ri-{{ $specialty->is_active ? 'checkbox-circle-fill' : 'checkbox-circle-line' }}"></i>
                                    </button>
                                </form>
                                <button type="button"
                                        class="action-btn action-btn--delete"
                                        title="حذف"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteSpecialtyModal"
                                        data-specialty-id="{{ $specialty->id }}"
                                        data-specialty-name="{{ $specialty->name }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-code-box-line"></i></div>
                                <h5 class="fw-bold mb-2">لا توجد تخصصات</h5>
                                <p class="text-muted mb-3">لم يتم العثور على تخصصات مطابقة.</p>
                                <a href="{{ route('admin.tech-specialties.create') }}" class="btn btn-primary btn-sm">
                                    <i class="ri-add-line me-1"></i> إضافة تخصص جديد
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($specialties->hasPages())
        <div class="card-footer border-top bg-transparent py-3">
            {{ $specialties->links() }}
        </div>
    @endif
</div>
