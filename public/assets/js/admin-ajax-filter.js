/**
 * Admin Ajax Filter — Central module for table search/filter without page reload.
 *
 * Usage: add data-admin-ajax-filter on a GET form with:
 *   data-target="#tableContainer"        (required)
 *   data-modals-target="#modalsHost"     (optional)
 *   data-count-target="#filteredCount"   (optional)
 *   data-reset-url="/admin/..."          (optional)
 *   data-debounce="450"                  (optional, ms)
 *
 * Fields:
 *   data-ajax-search  — debounced live search
 *   data-ajax-auto    — fetch on change (selects)
 *   data-ajax-chip-label — custom chip label
 *
 * Controller JSON response:
 *   { success: true, html: "...", filtered: 10, modals?: "..." }
 */
(function (window) {
    'use strict';

    var popstateBound = false;
    var config = { onRefresh: null };

    function formatNumber(value) {
        try {
            return new Intl.NumberFormat('ar-EG').format(Number(value) || 0);
        } catch (e) {
            return String(value);
        }
    }

    function buildFilterUrl(form, extraParams) {
        var url = new URL(form.getAttribute('action') || window.location.href, window.location.origin);
        var params = new URLSearchParams();

        new FormData(form).forEach(function (value, key) {
            if (String(value).trim() !== '') {
                params.set(key, value);
            }
        });

        if (extraParams) {
            Object.keys(extraParams).forEach(function (key) {
                if (extraParams[key] === null || extraParams[key] === '') {
                    params.delete(key);
                } else {
                    params.set(key, extraParams[key]);
                }
            });
        }

        url.search = params.toString();
        return url.toString();
    }

    function setFilterLoading(form, isLoading) {
        var target = document.querySelector(form.getAttribute('data-target') || '');
        var btn = form.querySelector('[type="submit"]');
        var statusEl = form.querySelector('.ajax-filter-status');

        if (target) {
            target.classList.toggle('is-loading', isLoading);
            target.setAttribute('aria-busy', isLoading ? 'true' : 'false');
        }

        if (btn) {
            btn.disabled = isLoading;
            if (isLoading) {
                if (!btn.dataset.defaultHtml) btn.dataset.defaultHtml = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري البحث...';
            } else if (btn.dataset.defaultHtml) {
                btn.innerHTML = btn.dataset.defaultHtml;
            }
        }

        if (statusEl && isLoading) {
            statusEl.textContent = 'جاري تحديث النتائج...';
        }
    }

    function getFieldChipLabel(form, field) {
        var chipLabel = field.getAttribute('data-ajax-chip-label');
        if (chipLabel) return chipLabel;

        if (field.id) {
            var labelEl = form.querySelector('label[for="' + field.id + '"]');
            if (labelEl) return labelEl.textContent.trim();
        }

        var wrap = field.closest('[class*="col-"]');
        if (wrap) {
            var colLabel = wrap.querySelector('.form-label');
            if (colLabel) return colLabel.textContent.trim();
        }

        return field.name;
    }

    function getFieldDisplayValue(field) {
        if (field.tagName === 'SELECT') {
            var opt = field.options[field.selectedIndex];
            return opt ? opt.text.trim() : field.value;
        }
        return field.value.trim();
    }

    function updateFilterChips(form) {
        var statusEl = form.querySelector('.ajax-filter-status');
        if (!statusEl) return;

        var chips = [];

        form.querySelectorAll('input[name], select[name], textarea[name]').forEach(function (field) {
            if (field.type === 'submit' || field.type === 'hidden') return;
            if (!field.name || field.name === '_token') return;

            var value = field.value;
            if (value === null || String(value).trim() === '') return;

            chips.push(getFieldChipLabel(form, field) + ': ' + getFieldDisplayValue(field));
        });

        if (!chips.length) {
            statusEl.innerHTML = '';
            return;
        }

        statusEl.innerHTML = '<span class="ajax-filter-chips">' + chips.map(function (chip) {
            return '<span class="ajax-filter-chip">' + chip + '</span>';
        }).join('') + '</span>';
    }

    function runRefreshHook(target, form) {
        if (typeof config.onRefresh === 'function') {
            config.onRefresh(target, form);
            return;
        }
        if (typeof window.adminUiRefresh === 'function') {
            window.adminUiRefresh(target, {
                toggleUrl: form.getAttribute('data-toggle-url'),
                toggleModal: form.getAttribute('data-toggle-modal')
            });
        }
    }

    function fetchAjaxFilter(form, url, pushState) {
        var target = document.querySelector(form.getAttribute('data-target') || '');
        if (!target) return Promise.resolve();

        var modalsTarget = form.getAttribute('data-modals-target');
        var countTarget = form.getAttribute('data-count-target');

        setFilterLoading(form, true);

        return fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function (response) {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(function (data) {
            if (!data.success) throw new Error(data.message || 'Error');

            target.innerHTML = data.html;

            if (modalsTarget && data.modals !== undefined) {
                var modalsEl = document.querySelector(modalsTarget);
                if (modalsEl) modalsEl.innerHTML = data.modals;
            }

            if (countTarget && data.filtered !== undefined) {
                var countEl = document.querySelector(countTarget);
                if (countEl) countEl.textContent = formatNumber(data.filtered);
            }

            if (pushState !== false) {
                window.history.pushState({ adminAjaxFilter: form.id || true }, '', url);
            }

            updateFilterChips(form);
            runRefreshHook(target, form);
        })
        .catch(function () {
            if (typeof window.adminUiToast === 'function') {
                window.adminUiToast('تعذّر تحميل النتائج. حاول مرة أخرى.', 'error');
            }
        })
        .finally(function () {
            setFilterLoading(form, false);
        });
    }

    function resetAjaxFilter(form) {
        form.reset();
        var url = form.getAttribute('data-reset-url') || form.getAttribute('action') || window.location.pathname;
        fetchAjaxFilter(form, url, true);
    }

    function bindAjaxFilterDelegates(form) {
        if (form.dataset.delegatesBound) return;
        form.dataset.delegatesBound = '1';

        var targetSelector = form.getAttribute('data-target') || '';
        var target = document.querySelector(targetSelector);
        if (!target) return;

        target.addEventListener('click', function (event) {
            var pageLink = event.target.closest('.ajax-pagination a, .pagination a');
            if (pageLink && pageLink.href) {
                event.preventDefault();
                fetchAjaxFilter(form, pageLink.href, true);
                target.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                return;
            }

            if (event.target.closest('[data-ajax-reset]')) {
                event.preventDefault();
                resetAjaxFilter(form);
            }
        });

        form.querySelectorAll('[data-ajax-reset]').forEach(function (btn) {
            btn.addEventListener('click', function (event) {
                event.preventDefault();
                resetAjaxFilter(form);
            });
        });
    }

    function initForm(form) {
        if (form.dataset.ajaxFilterInit) return;
        form.dataset.ajaxFilterInit = '1';

        var debounceTimer;
        var debounceMs = parseInt(form.getAttribute('data-debounce') || '450', 10);

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            fetchAjaxFilter(form, buildFilterUrl(form), true);
        });

        form.querySelectorAll('[data-ajax-auto]').forEach(function (field) {
            field.addEventListener('change', function () {
                fetchAjaxFilter(form, buildFilterUrl(form), true);
            });
        });

        var searchInput = form.querySelector('[data-ajax-search]');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () {
                    fetchAjaxFilter(form, buildFilterUrl(form), true);
                }, debounceMs);
            });

            searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    clearTimeout(debounceTimer);
                    fetchAjaxFilter(form, buildFilterUrl(form), true);
                }
            });
        }

        bindAjaxFilterDelegates(form);
        updateFilterChips(form);

        var target = document.querySelector(form.getAttribute('data-target') || '');
        if (target) {
            runRefreshHook(target, form);
        }
    }

    function bindPopstate() {
        if (popstateBound) return;
        popstateBound = true;

        window.addEventListener('popstate', function () {
            document.querySelectorAll('[data-admin-ajax-filter]').forEach(function (form) {
                var actionPath = new URL(form.getAttribute('action') || window.location.href, window.location.origin).pathname;
                if (window.location.pathname === actionPath) {
                    fetchAjaxFilter(form, window.location.href, false);
                }
            });
        });
    }

    window.AdminAjaxFilter = {
        init: function (options) {
            config = Object.assign({ onRefresh: null }, options || {});
            document.querySelectorAll('[data-admin-ajax-filter]').forEach(initForm);
            bindPopstate();
        },
        fetch: fetchAjaxFilter,
        reset: resetAjaxFilter,
        buildUrl: buildFilterUrl,
        refreshChips: updateFilterChips
    };

    document.addEventListener('DOMContentLoaded', function () {
        window.AdminAjaxFilter.init();
    });
})(window);
