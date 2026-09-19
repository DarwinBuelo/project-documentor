@php($themeManager = app(\App\Support\ThemeManager::class))
<style id="site-theme">
    html.dark {
    {!! $themeManager->cssVariablesForMode('dark') !!}
    }
    html.light {
    {!! $themeManager->cssVariablesForMode('light') !!}
    }
</style>
