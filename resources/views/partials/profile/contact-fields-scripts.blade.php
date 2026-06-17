@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function reindexList(listEl) {
        var prefix = listEl.dataset.prefix;
        listEl.querySelectorAll('.dynamic-list-row').forEach(function (row, index) {
            row.querySelectorAll('[name]').forEach(function (input) {
                var field = input.getAttribute('data-name') || input.name.match(/\[([^\]]+)\]$/)[1];
                input.name = prefix + '[' + index + '][' + field + ']';
            });
        });
    }

    function updateSocialIcon(row) {
        var select = row.querySelector('.social-platform-select');
        var iconEl = row.querySelector('.social-platform-icon i');
        if (!select || !iconEl || !window.socialPlatformIcons) return;
        var meta = window.socialPlatformIcons[select.value] || window.socialPlatformIcons.other;
        iconEl.className = meta.icon + ' ' + (meta.color || '') + ' fs-18';
    }

    function toggleSocialLabel(row) {
        var select = row.querySelector('.social-platform-select');
        var labelCol = row.querySelector('.social-custom-label');
        var urlCol = row.querySelector('.social-url-col');
        if (!select || !labelCol || !urlCol) return;
        var isOther = select.value === 'other';
        labelCol.classList.toggle('d-none', !isOther);
        urlCol.classList.remove('col-md-4', 'col-md-7');
        urlCol.classList.add(isOther ? 'col-md-4' : 'col-md-7');
        updateSocialIcon(row);
    }

    function bindSocialSelect(row) {
        var select = row.querySelector('.social-platform-select');
        if (!select || select.dataset.bound) return;
        select.dataset.bound = '1';
        select.addEventListener('change', function () { toggleSocialLabel(row); });
        toggleSocialLabel(row);
    }

    function bindRemoveButtons(listEl) {
        listEl.querySelectorAll('.dynamic-list-remove').forEach(function (btn) {
            if (btn.dataset.bound) return;
            btn.dataset.bound = '1';
            btn.addEventListener('click', function () {
                var rows = listEl.querySelectorAll('.dynamic-list-row');
                if (rows.length <= 1) {
                    rows[0].querySelectorAll('input, select').forEach(function (el) {
                        if (el.tagName === 'SELECT') {
                            el.selectedIndex = 0;
                        } else {
                            el.value = '';
                        }
                    });
                    toggleSocialLabel(rows[0]);
                    return;
                }
                btn.closest('.dynamic-list-row').remove();
                reindexList(listEl);
            });
        });
    }

    function addRow(listId) {
        var listEl = document.getElementById(listId + '-list');
        var template = document.getElementById(listId + '-template');
        if (!listEl || !template) return;
        var index = listEl.querySelectorAll('.dynamic-list-row').length;
        var row = template.content.firstElementChild.cloneNode(true);
        var prefix = listEl.dataset.prefix;
        row.querySelectorAll('[data-name]').forEach(function (input) {
            var field = input.getAttribute('data-name');
            input.name = prefix + '[' + index + '][' + field + ']';
        });
        listEl.appendChild(row);
        bindRemoveButtons(listEl);
        bindSocialSelect(row);
    }

    document.querySelectorAll('.dynamic-list').forEach(function (listEl) {
        bindRemoveButtons(listEl);
        listEl.querySelectorAll('.dynamic-list-row').forEach(bindSocialSelect);
    });

    document.querySelectorAll('.dynamic-list-add').forEach(function (btn) {
        btn.addEventListener('click', function () {
            addRow(btn.dataset.list);
        });
    });
});
</script>
@endpush
