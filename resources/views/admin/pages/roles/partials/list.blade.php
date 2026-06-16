<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 220px;">اسم الدور</th>
                    <th style="min-width: 130px;">الصلاحيات</th>
                    <th style="min-width: 130px;">المستخدمون</th>
                    <th style="min-width: 120px;">إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    @php $initial = mb_strtoupper(mb_substr($role->name, 0, 1)); @endphp
                    <tr>
                        <td class="text-muted fw-medium">{{ $roles->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}">{{ $initial }}</span>
                                <div>
                                    <a href="{{ route('admin.roles.edit', $role->id) }}"
                                       class="fw-bold row-title-link text-decoration-none d-block">
                                        {{ $role->name }}
                                    </a>
                                    <span class="text-muted fs-11">دور نظام</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-soft badge-soft-primary">
                                <i class="ri-lock-line me-1"></i>{{ number_format($role->permissions_count) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-soft badge-soft-info">
                                <i class="ri-user-line me-1"></i>{{ number_format($role->users_count) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <a class="action-btn action-btn--edit" title="تعديل"
                                   href="{{ route('admin.roles.edit', $role->id) }}">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <button type="button" class="action-btn action-btn--delete"
                                        data-bs-toggle="modal" data-bs-target="#delete{{ $role->id }}"
                                        title="حذف">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-shield-line"></i></div>
                                @if(request()->filled('query'))
                                    <h5 class="fw-bold mb-2">لا توجد نتائج</h5>
                                    <p class="text-muted mb-3">لم يتم العثور على أدوار مطابقة للبحث.</p>
                                    <button type="button" class="btn btn-light border btn-sm" data-ajax-reset>
                                        <i class="ri-refresh-line me-1"></i> إعادة تعيين
                                    </button>
                                @else
                                    <h5 class="fw-bold mb-2">لا توجد أدوار</h5>
                                    <p class="text-muted mb-3">لم يتم إنشاء أي أدوار بعد. ابدأ بإضافة دور جديد.</p>
                                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                                        <i class="ri-add-line me-1"></i> إضافة دور جديد
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($roles->hasPages())
        <div class="card-footer border-top bg-transparent py-3 ajax-pagination">
            {{ $roles->withQueryString()->links() }}
        </div>
    @endif
</div>
