@php($themeConfig = app(\App\Support\ThemeManager::class)->clientConfig())
<script>
    window.__SITE_THEME__ = @json($themeConfig);
    (function () {
        const site = window.__SITE_THEME__;
        const mode = localStorage.getItem('theme') ?? site.mode;
        document.documentElement.classList.remove('dark', 'light');
        document.documentElement.classList.add(mode === 'light' ? 'light' : 'dark');
        document.documentElement.dataset.palette = site.palette;
    })();
</script>
@include('partials.theme-styles')
