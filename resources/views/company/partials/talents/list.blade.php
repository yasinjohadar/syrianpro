<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 260px;">الموهبة</th>
                    <th style="min-width: 120px;">المدينة</th>
                    <th style="min-width: 130px;">التخصص</th>
                    <th style="min-width: 120px;">المعدل</th>
                    <th style="min-width: 100px;">التوفر</th>
                    <th style="min-width: 120px;">المهارات</th>
                    <th style="min-width: 100px;">الحالة</th>
                    <th style="min-width: 120px;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($talents as $talent)
                    <tr>
                        <td class="text-muted fw-medium">{{ $talents->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}" style="font-size:1.1rem; overflow:hidden;">
                                    @if($talent->avatar_image)
                                        <img src="{{ $talent->avatarUrl() }}" alt="" class="rounded" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        {{ $talent->avatar_initial }}
                                    @endif
                                </span>
                                <div>
                                    <a href="{{ route('company.talents.show', $talent) }}"
                                       class="fw-bold row-title-link text-decoration-none d-block">
                                        {{ $talent->name }}
                                        @if($talent->is_verified)
                                            <i class="ri-verified-badge-fill text-primary fs-12"></i>
                                        @endif
                                    </a>
                                    <span class="text-muted fs-11">{{ $talent->title }}</span>
                                    @if($talent->is_featured)
                                        <span class="badge-soft badge-soft-warning ms-1">مميز</span>
                                    @endif
                                    @if($talent->is_open_to_work)
                                        <span class="badge-soft badge-soft-success ms-1">يبحث عن عمل</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $talent->city }}</td>
                        <td>
                            @if($talent->techSpecialty)
                                <span class="badge-soft badge-soft-primary">{{ $talent->techSpecialty->name }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><span class="badge-soft badge-soft-info">{{ $talent->rate_display }}</span></td>
                        <td>
                            <span class="text-muted fs-12">{{ $talent->availability ?: '—' }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @forelse(array_slice($talent->skills ?? [], 0, 3) as $skill)
                                    <span class="badge-soft badge-soft-secondary">{{ $skill }}</span>
                                @empty
                                    <span class="text-muted">—</span>
                                @endforelse
                                @if(count($talent->skills ?? []) > 3)
                                    <span class="badge-soft badge-soft-secondary">+{{ count($talent->skills) - 3 }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($talent->is_remote)
                                <span class="badge-soft badge-soft-success">Remote</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">محلي</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <a href="{{ route('company.talents.show', $talent) }}"
                                   class="action-btn action-btn--view" title="عرض الملف">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('talents.show', $talent) }}" target="_blank"
                                   class="action-btn action-btn--edit" title="الصفحة العامة">
                                    <i class="ri-external-link-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-user-star-line"></i></div>
                                <h5 class="fw-bold mb-2">لا توجد مواهب</h5>
                                <p class="text-muted mb-0">جرّب تغيير معايير البحث أو الفلاتر.</p>
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
