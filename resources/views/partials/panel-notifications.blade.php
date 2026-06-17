@php
    $notificationsIndexUrl = $notificationsIndexUrl ?? route('company.notifications.index');
    $notificationsReadAllUrl = $notificationsReadAllUrl ?? route('company.notifications.read-all');
@endphp

<div class="header-element notifications-dropdown main-header-notification" id="panel-notifications"
     data-index-url="{{ $notificationsIndexUrl }}"
     data-read-all-url="{{ $notificationsReadAllUrl }}"
     data-csrf="{{ csrf_token() }}">
    <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"/></svg>
        <span class="badge bg-danger rounded-pill header-notification-badge d-none" id="notification-unread-badge">0</span>
    </a>
    <div class="main-header-dropdown dropdown-menu dropdown-menu-end main-header-message" data-popper-placement="none">
        <div class="menu-header-content bg-primary text-fixed-white">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fs-15 fw-semibold text-fixed-white">الإشعارات</h6>
                <button type="button" class="btn btn-sm btn-warning py-0 px-2 fs-11" id="notification-mark-all-read">تحديد الكل كمقروء</button>
            </div>
            <p class="dropdown-title-text subtext mb-0 text-fixed-white op-6 pb-0 fs-12" id="notification-subtitle">جاري التحميل...</p>
        </div>
        <div><hr class="dropdown-divider"></div>
        <ul class="list-unstyled mb-0" id="notification-list" style="max-height:320px;overflow-y:auto;">
            <li class="dropdown-item text-center py-3">
                <p class="text-muted mb-0">جاري التحميل...</p>
            </li>
        </ul>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.getElementById('panel-notifications');
    if (!root) return;

    const indexUrl = root.dataset.indexUrl;
    const readAllUrl = root.dataset.readAllUrl;
    const csrf = root.dataset.csrf;
    const badge = document.getElementById('notification-unread-badge');
    const subtitle = document.getElementById('notification-subtitle');
    const list = document.getElementById('notification-list');
    const markAllBtn = document.getElementById('notification-mark-all-read');

    function renderNotifications(data) {
        const count = data.unread_count || 0;
        if (count > 0) {
            badge.textContent = count > 9 ? '9+' : count;
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('d-none');
        }
        subtitle.textContent = count > 0
            ? 'لديك ' + count + ' إشعار' + (count === 1 ? '' : (count === 2 ? 'ان' : 'ات')) + ' جديد' + (count === 1 ? '' : 'ة')
            : 'لا توجد إشعارات جديدة';

        if (!data.notifications || data.notifications.length === 0) {
            list.innerHTML = '<li class="dropdown-item text-center py-3"><p class="text-muted mb-0">لا توجد إشعارات</p></li>';
            return;
        }

        list.innerHTML = data.notifications.map(function (n) {
            const unread = !n.read_at ? ' bg-light' : '';
            return '<li class="dropdown-item' + unread + '">' +
                '<a href="' + n.action_url + '" class="d-block text-decoration-none text-dark notification-item-link" data-id="' + n.id + '">' +
                '<div class="fw-semibold fs-13 mb-1">' + n.title + '</div>' +
                '<div class="text-muted fs-12 mb-1">' + n.body + '</div>' +
                '<div class="text-muted fs-11">' + n.created_at + '</div>' +
                '</a></li>';
        }).join('');
    }

    function loadNotifications() {
        fetch(indexUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(renderNotifications)
            .catch(function () {
                subtitle.textContent = 'تعذر تحميل الإشعارات';
            });
    }

    list.addEventListener('click', function (e) {
        const link = e.target.closest('.notification-item-link');
        if (!link) return;
        const id = link.dataset.id;
        fetch(indexUrl.replace(/\/notifications$/, '/notifications/' + id + '/read'), {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf,
            },
        }).catch(function () {});
    });

    if (markAllBtn) {
        markAllBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            fetch(readAllUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf,
                },
            }).then(loadNotifications);
        });
    }

    root.querySelector('[data-bs-toggle="dropdown"]').addEventListener('show.bs.dropdown', loadNotifications);
    loadNotifications();
});
</script>
