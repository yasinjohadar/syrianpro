<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 240px;">الموهبة</th>
                    <th style="min-width: 120px;">المدينة</th>
                    <th style="min-width: 120px;">المعدل</th>
                    <th style="min-width: 100px;">موثّق</th>
                    <th style="min-width: 100px;">مميزة</th>
                    <th style="min-width: 100px;">الحالة</th>
                    <th style="min-width: 150px;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($talents as $talent)
                    <tr>
                        <td class="text-muted fw-medium">{{ $talents->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}" style="font-size:1.1rem;">
                                    {{ $talent->avatar_initial }}
                                </span>
                                <div>
                                    <a href="{{ route('admin.talents.edit', $talent) }}"
                                       class="fw-bold row-title-link text-decoration-none d-block">
                                        {{ $talent->name }}
                                    </a>
                                    <span class="text-muted fs-11">{{ $talent->title }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $talent->city }}</td>
                        <td><span class="badge-soft badge-soft-info">{{ $talent->rate_display }}</span></td>
                        <td>
                            @if ($talent->is_verified)
                                <span class="badge-soft badge-soft-success">نعم</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">لا</span>
                            @endif
                        </td>
                        <td>
                            @if ($talent->is_featured)
                                <span class="badge-soft badge-soft-warning">نعم</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">لا</span>
                            @endif
                        </td>
                        <td>
                            @if ($talent->is_active)
                                <span class="badge-soft badge-soft-success">نشط</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">غير نشط</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <a href="{{ route('talents.show', $talent) }}" target="_blank"
                                   class="action-btn action-btn--view" title="عرض">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('admin.talents.edit', $talent) }}"
                                   class="action-btn action-btn--edit" title="تعديل">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('admin.talents.toggle-active', $talent) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="action-btn action-btn--success {{ $talent->is_active ? 'is-active' : '' }}"
                                            title="{{ $talent->is_active ? 'تعطيل' : 'تفعيل' }}">
                                        <i class="ri-{{ $talent->is_active ? 'checkbox-circle-fill' : 'checkbox-circle-line' }}"></i>
                                    </button>
                                </form>
                                <button type="button"
                                        class="action-btn action-btn--delete"
                                        title="حذف"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteTalentModal"
                                        data-talent-id="{{ $talent->id }}"
                                        data-talent-name="{{ $talent->name }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-user-star-line"></i></div>
                                <h5 class="fw-bold mb-2">لا توجد مواهب</h5>
                                <a href="{{ route('admin.talents.create') }}" class="btn btn-primary btn-sm">
                                    <i class="ri-add-line me-1"></i> إضافة موهبة جديدة
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($talents->hasPages())
        <div class="card-footer border-top bg-transparent py-3">
            {{ $talents->links() }}
        </div>
    @endif
</div>
