@php
    $roleModel = $role ?? null;
    $groups = \App\Support\PermissionRegistry::groupsForForm();
    $totalPerms = collect($groups)->sum(fn ($g) => count($g['permissions'] ?? []));
@endphp

<div class="permissions-form mb-0">
    <div class="permissions-panel">
        <div class="perm-toolbar">
            <div>
                <label class="form-label fw-bold mb-1 fs-15">
                    <i class="ri-lock-unlock-line me-1 text-primary"></i> الصلاحيات
                </label>
                <div class="text-muted fs-12">حدّد الصلاحيات الممنوحة لهذا الدور</div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="perm-counter" id="perm-selected-count">0 / {{ $totalPerms }}</span>
                <button type="button" class="btn btn-sm btn-outline-primary perm-action-btn" id="perm-select-all">
                    <i class="ri-checkbox-multiple-line me-1"></i> تحديد الكل
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary perm-action-btn" id="perm-deselect-all">
                    <i class="ri-close-circle-line me-1"></i> إلغاء الكل
                </button>
            </div>
        </div>

        <div class="mb-3 perm-search-wrap">
            <i class="ri-search-line"></i>
            <input type="search" class="form-control" id="perm-search"
                   placeholder="بحث في الصلاحيات..." autocomplete="off">
        </div>

        <div class="accordion permission-accordion" id="permissionsAccordion">
            @foreach ($groups as $groupKey => $group)
                @php
                    $groupId = 'perm-group-' . $groupKey;
                    $permCount = count($group['permissions'] ?? []);
                @endphp
                <div class="accordion-item permission-group" data-group="{{ $groupKey }}">
                    <h2 class="accordion-header">
                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#{{ $groupId }}"
                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="{{ $groupId }}">
                            <i class="ri-folder-shield-line me-2 opacity-75"></i>
                            <span class="fw-semibold">{{ $group['label'] }}</span>
                            <span class="badge bg-primary-transparent text-primary ms-2">{{ $permCount }}</span>
                        </button>
                    </h2>
                    <div id="{{ $groupId }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                        data-bs-parent="#permissionsAccordion">
                        <div class="accordion-body pt-2">
                            <div class="form-check group-toggle-row mb-0">
                                <input class="form-check-input perm-group-toggle" type="checkbox"
                                    id="toggle-{{ $groupKey }}" data-group="{{ $groupKey }}">
                                <label class="form-check-label fw-semibold" for="toggle-{{ $groupKey }}">
                                    تحديد كل صلاحيات «{{ $group['label'] }}»
                                </label>
                            </div>
                            <div class="row g-2">
                                @foreach ($group['permissions'] as $permName => $permLabel)
                                    @php
                                        $inputId = 'perm_' . md5($permName);
                                        $checked = $roleModel
                                            ? $roleModel->hasPermissionTo($permName)
                                            : old('permissions.' . $permName);
                                    @endphp
                                    <div class="col-md-6 col-lg-4 perm-item" data-search="{{ $permName }} {{ $permLabel }}">
                                        <div class="form-check permission-check-card p-2">
                                            <input class="form-check-input perm-checkbox" type="checkbox"
                                                name="permissions[{{ $permName }}]" value="{{ $permName }}"
                                                id="{{ $inputId }}" data-group="{{ $groupKey }}"
                                                @checked($checked)>
                                            <label class="form-check-label w-100" for="{{ $inputId }}">
                                                <span class="d-block fw-medium fs-13">{{ $permLabel }}</span>
                                                <small>{{ $permName }}</small>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const root = document.querySelector('.permissions-form');
                if (!root) return;

                const checkboxes = () => root.querySelectorAll('.perm-checkbox');
                const visibleCheckboxes = () => Array.from(checkboxes()).filter(cb => {
                    const item = cb.closest('.perm-item');
                    return item && item.style.display !== 'none';
                });

                function updateSelectedCount() {
                    const counter = root.querySelector('#perm-selected-count');
                    if (!counter) return;
                    const total = checkboxes().length;
                    const selected = Array.from(checkboxes()).filter(cb => cb.checked).length;
                    counter.textContent = selected + ' / ' + total;
                }

                root.querySelector('#perm-select-all')?.addEventListener('click', () => {
                    visibleCheckboxes().forEach(cb => { cb.checked = true; });
                    syncGroupToggles();
                    updateSelectedCount();
                });

                root.querySelector('#perm-deselect-all')?.addEventListener('click', () => {
                    visibleCheckboxes().forEach(cb => { cb.checked = false; });
                    syncGroupToggles();
                    updateSelectedCount();
                });

                root.querySelectorAll('.perm-group-toggle').forEach(toggle => {
                    toggle.addEventListener('change', function () {
                        const group = this.dataset.group;
                        const checked = this.checked;
                        root.querySelectorAll('.perm-checkbox[data-group="' + group + '"]').forEach(cb => {
                            const item = cb.closest('.perm-item');
                            if (!item || item.style.display === 'none') return;
                            cb.checked = checked;
                        });
                        updateSelectedCount();
                    });
                });

                root.querySelectorAll('.perm-checkbox').forEach(cb => {
                    cb.addEventListener('change', function () {
                        syncGroupToggles();
                        updateSelectedCount();
                    });
                });

                root.querySelectorAll('.permission-check-card').forEach(card => {
                    card.addEventListener('click', function (e) {
                        if (e.target.classList.contains('form-check-input')) return;
                        const cb = this.querySelector('.perm-checkbox');
                        if (cb) {
                            cb.checked = !cb.checked;
                            cb.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    });
                });

                function syncGroupToggles() {
                    root.querySelectorAll('.perm-group-toggle').forEach(toggle => {
                        const group = toggle.dataset.group;
                        const groupBoxes = Array.from(
                            root.querySelectorAll('.perm-checkbox[data-group="' + group + '"]')
                        ).filter(cb => {
                            const item = cb.closest('.perm-item');
                            return item && item.style.display !== 'none';
                        });
                        if (!groupBoxes.length) return;
                        toggle.checked = groupBoxes.every(cb => cb.checked);
                        toggle.indeterminate = !toggle.checked && groupBoxes.some(cb => cb.checked);
                    });
                }

                const search = root.querySelector('#perm-search');
                search?.addEventListener('input', function () {
                    const q = this.value.trim().toLowerCase();
                    root.querySelectorAll('.perm-item').forEach(item => {
                        const hay = (item.dataset.search || '').toLowerCase();
                        item.style.display = !q || hay.includes(q) ? '' : 'none';
                    });
                    root.querySelectorAll('.permission-group').forEach(group => {
                        const hasVisible = Array.from(group.querySelectorAll('.perm-item'))
                            .some(item => item.style.display !== 'none');
                        group.style.display = hasVisible || !q ? '' : 'none';
                    });
                    syncGroupToggles();
                    updateSelectedCount();
                });

                syncGroupToggles();
                updateSelectedCount();
            });
        </script>
    @endpush
@endonce
