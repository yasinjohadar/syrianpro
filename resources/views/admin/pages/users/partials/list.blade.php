<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 200px;">المستخدم</th>
                    <th style="min-width: 220px;">البريد</th>
                    <th style="min-width: 140px;">الهاتف</th>
                    <th style="min-width: 130px;">آخر دخول</th>
                    <th style="min-width: 110px;">الأدوار</th>
                    <th style="min-width: 100px;">الاتصال</th>
                    <th style="min-width: 110px;">التفعيل</th>
                    <th style="min-width: 80px;">إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    @php
                        $userSessions = $sessions->get($user->id);
                        $lastSession = $userSessions ? $userSessions->first() : null;
                        $initials = mb_strtoupper(mb_substr($user->name, 0, 1));
                        $isOnline = in_array($user->id, $onlineUserIds, true);
                        $roleLabel = $user->getRoleNames()->first();
                    @endphp
                    <tr>
                        <td class="text-muted fw-medium">{{ $users->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" class="row-avatar row-avatar--img" alt="">
                                @else
                                    <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}">{{ $initials }}</span>
                                @endif
                                <div>
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                       class="fw-bold row-title-link text-decoration-none d-block">
                                        {{ $user->name }}
                                    </a>
                                    @if($roleLabel)
                                        <span class="text-muted fs-11">{{ $roleLabel }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($user->email)
                                <span class="email-copy-wrap">
                                    <span dir="ltr" class="text-primary">{{ $user->email }}</span>
                                    <button type="button" class="copy-btn" data-copy="{{ $user->email }}" title="نسخ">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if ($user->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}"
                                   target="_blank" rel="noopener"
                                   class="text-success text-decoration-none d-inline-flex align-items-center gap-1">
                                    <i class="ri-whatsapp-line"></i>
                                    <span dir="ltr">{{ $user->phone }}</span>
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if ($lastSession)
                                <span class="meta-text" title="{{ \Carbon\Carbon::createFromTimestamp($lastSession->last_activity)->locale('ar')->translatedFormat('j F Y، H:i') }}">
                                    <i class="ri-time-line"></i>
                                    {{ \Carbon\Carbon::createFromTimestamp($lastSession->last_activity)->locale('ar')->diffForHumans() }}
                                </span>
                            @else
                                <span class="text-muted fs-12">—</span>
                            @endif
                        </td>
                        <td>
                            @forelse ($user->getRoleNames() as $role)
                                <span class="badge-soft badge-soft-primary me-1">{{ $role }}</span>
                            @empty
                                <span class="text-muted fs-12">—</span>
                            @endforelse
                        </td>
                        <td>
                            @if($isOnline)
                                <span class="connection-status connection-status--online">
                                    <span class="connection-status__dot"></span> متصل
                                </span>
                            @else
                                <span class="connection-status connection-status--offline">
                                    <span class="connection-status__dot"></span> غير متصل
                                </span>
                            @endif
                        </td>
                        <td>
                            <label class="activation-pill {{ $user->is_active ? 'activation-pill--active' : 'activation-pill--inactive' }} mb-0">
                                <input type="checkbox" class="toggle-status"
                                       data-user-id="{{ $user->id }}"
                                       data-user-name="{{ $user->name }}"
                                       data-user-email="{{ $user->email }}"
                                       data-user-avatar="{{ $user->photo ? asset('storage/' . $user->photo) : '' }}"
                                       data-user-initial="{{ $initials }}"
                                       {{ $user->is_active ? 'checked' : '' }}>
                                <i class="ri-shut-down-line"></i>
                                <span class="toggle-label">{{ $user->is_active ? 'نشط' : 'غير نشط' }}</span>
                            </label>
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <a class="action-btn action-btn--edit" title="تعديل"
                                   href="{{ route('admin.users.edit', $user->id) }}">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                    <button type="button"
                                            class="action-btn action-btn--delete"
                                            title="حذف"
                                            data-bs-toggle="modal"
                                            data-bs-target="#delete{{ $user->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-user-search-line"></i></div>
                                <h5 class="fw-bold mb-2">لا توجد نتائج</h5>
                                <p class="text-muted mb-3">لم يتم العثور على مستخدمين مطابقين.</p>
                                <button type="button" class="btn btn-light border btn-sm" data-ajax-reset>
                                    <i class="ri-refresh-line me-1"></i> إعادة تعيين
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="card-footer border-top bg-transparent py-3 ajax-pagination">
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</div>
