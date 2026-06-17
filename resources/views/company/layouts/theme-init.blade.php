{{-- Apply saved theme before paint to prevent light-mode flash (FOUC) --}}
<script>
(function () {
    try {
        var html = document.documentElement;
        var ls = localStorage;
        var isDark = ls.getItem('valexdarktheme') || (ls.getItem('bodyBgRGB') && ls.getItem('bodylightRGB'));

        if (isDark) {
            html.setAttribute('data-theme-mode', 'dark');
            html.setAttribute('data-menu-styles', ls.getItem('valexMenu') || 'dark');
            html.setAttribute('data-header-styles', ls.getItem('valexHeader') || 'dark');
        }

        if (ls.getItem('primaryRGB')) {
            html.style.setProperty('--primary-rgb', ls.getItem('primaryRGB'));
        }

        if (ls.getItem('bodyBgRGB') && ls.getItem('bodylightRGB')) {
            html.style.setProperty('--body-bg-rgb', ls.getItem('bodyBgRGB'));
            html.style.setProperty('--body-bg-rgb2', ls.getItem('bodylightRGB'));
            html.style.setProperty('--light-rgb', ls.getItem('bodylightRGB'));
            html.style.setProperty('--form-control-bg', 'rgb(' + ls.getItem('bodylightRGB') + ')');
            html.style.setProperty('--input-border', 'rgba(255,255,255,0.1)');
            html.setAttribute('data-theme-mode', 'dark');
            html.setAttribute('data-menu-styles', ls.getItem('valexMenu') || 'dark');
            html.setAttribute('data-header-styles', ls.getItem('valexHeader') || 'dark');
        }
    } catch (e) {}
})();
</script>
<style>
    html[data-theme-mode="dark"] {
        color-scheme: dark;
        background-color: rgb(25, 32, 47);
    }
    html[data-theme-mode="dark"] body {
        background-color: rgb(36, 43, 57);
    }
    html[data-theme-mode="dark"] #loader {
        background-color: rgb(25, 32, 47);
    }
</style>
