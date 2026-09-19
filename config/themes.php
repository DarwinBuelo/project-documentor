<?php

use App\Support\ThemePalette;

$theme = static function (string $name, string $description, array $dark, array $light): array {
    return [
        'name' => $name,
        'description' => $description,
        'dark' => ThemePalette::expand([...$dark, 'is_dark' => true]),
        'light' => ThemePalette::expand([...$light, 'is_dark' => false]),
        'preview' => [
            'background' => $dark['background'],
            'surface' => $dark['surface'] ?? $dark['background'],
            'primary' => $dark['primary'],
            'accent' => $dark['accent'] ?? $dark['primary'],
        ],
    ];
};

return [
    'dracula-soft' => $theme(
        'Dracula Soft',
        'Muted purple accents on charcoal',
        ['background' => '#282a36', 'surface' => '#21222c', 'foreground' => '#f6f6f4', 'muted' => '#7b7f8b', 'primary' => '#bf9eee', 'accent' => '#97e1f1', 'danger' => '#ee6666', 'border' => '#44475a'],
        ['background' => '#fffbeb', 'surface' => '#ffffff', 'foreground' => '#1f1f1f', 'muted' => '#6c664b', 'primary' => '#644ac9', 'accent' => '#036a96', 'danger' => '#cb3a2a'],
    ),
    'dracula' => $theme(
        'Dracula',
        'Classic Dracula purple and pink',
        ['background' => '#282a36', 'surface' => '#21222c', 'foreground' => '#f8f8f2', 'muted' => '#6272a4', 'primary' => '#bd93f9', 'accent' => '#8be9fd', 'danger' => '#ff5555', 'border' => '#44475a'],
        ['background' => '#fafafa', 'surface' => '#ffffff', 'foreground' => '#282a36', 'muted' => '#6272a4', 'primary' => '#7c3aed', 'accent' => '#0891b2', 'danger' => '#dc2626'],
    ),
    'nord' => $theme(
        'Nord',
        'Arctic blues and frost tones',
        ['background' => '#2e3440', 'surface' => '#3b4252', 'foreground' => '#eceff4', 'muted' => '#d8dee9', 'primary' => '#88c0d0', 'accent' => '#8fbcbb', 'danger' => '#bf616a', 'border' => '#4c566a'],
        ['background' => '#eceff4', 'surface' => '#ffffff', 'foreground' => '#2e3440', 'muted' => '#4c566a', 'primary' => '#5e81ac', 'accent' => '#8fbcbb', 'danger' => '#bf616a'],
    ),
    'tokyo-night' => $theme(
        'Tokyo Night',
        'Deep navy with neon blue',
        ['background' => '#1a1b26', 'surface' => '#16161e', 'foreground' => '#c0caf5', 'muted' => '#565f89', 'primary' => '#7aa2f7', 'accent' => '#7dcfff', 'danger' => '#f7768e', 'border' => '#292e42'],
        ['background' => '#e1e2e7', 'surface' => '#ffffff', 'foreground' => '#343b58', 'muted' => '#565f89', 'primary' => '#2e7de9', 'accent' => '#007acc', 'danger' => '#f7768e'],
    ),
    'catppuccin-mocha' => $theme(
        'Catppuccin Mocha',
        'Pastel lavender on mocha',
        ['background' => '#1e1e2e', 'surface' => '#181825', 'foreground' => '#cdd6f4', 'muted' => '#a6adc8', 'primary' => '#cba6f7', 'accent' => '#89dceb', 'danger' => '#f38ba8', 'border' => '#313244'],
        ['background' => '#eff1f5', 'surface' => '#ffffff', 'foreground' => '#4c4f69', 'muted' => '#6c6f85', 'primary' => '#8839ef', 'accent' => '#04a5e5', 'danger' => '#d20f39'],
    ),
    'catppuccin-latte' => $theme(
        'Catppuccin Latte',
        'Soft pastels on warm white',
        ['background' => '#303446', 'surface' => '#292c3c', 'foreground' => '#b5bfe2', 'muted' => '#838ba7', 'primary' => '#ca9ee6', 'accent' => '#99d1db', 'danger' => '#e78284', 'border' => '#414559'],
        ['background' => '#eff1f5', 'surface' => '#ffffff', 'foreground' => '#4c4f69', 'muted' => '#7c7f93', 'primary' => '#8839ef', 'accent' => '#1e66f5', 'danger' => '#d20f39'],
    ),
    'one-dark' => $theme(
        'One Dark',
        'Atom-inspired balanced dark',
        ['background' => '#282c34', 'surface' => '#21252b', 'foreground' => '#abb2bf', 'muted' => '#5c6370', 'primary' => '#c678dd', 'accent' => '#56b6c2', 'danger' => '#e06c75', 'border' => '#3e4451'],
        ['background' => '#fafafa', 'surface' => '#ffffff', 'foreground' => '#383a42', 'muted' => '#696c77', 'primary' => '#a626a4', 'accent' => '#0184bc', 'danger' => '#e45649'],
    ),
    'gruvbox-dark' => $theme(
        'Gruvbox Dark',
        'Retro warm earthy dark',
        ['background' => '#282828', 'surface' => '#1d2021', 'foreground' => '#ebdbb2', 'muted' => '#a89984', 'primary' => '#d79921', 'accent' => '#83a598', 'danger' => '#fb4934', 'border' => '#3c3836'],
        ['background' => '#fbf1c7', 'surface' => '#ffffff', 'foreground' => '#3c3836', 'muted' => '#7c6f64', 'primary' => '#b57614', 'accent' => '#076678', 'danger' => '#cc241d'],
    ),
    'gruvbox-light' => $theme(
        'Gruvbox Light',
        'Warm paper tones',
        ['background' => '#3c3836', 'surface' => '#32302f', 'foreground' => '#ebdbb2', 'muted' => '#a89984', 'primary' => '#fabd2f', 'accent' => '#83a598', 'danger' => '#fb4934', 'border' => '#504945'],
        ['background' => '#fbf1c7', 'surface' => '#ffffff', 'foreground' => '#3c3836', 'muted' => '#7c6f64', 'primary' => '#b57614', 'accent' => '#427b58', 'danger' => '#9d0006'],
    ),
    'solarized-dark' => $theme(
        'Solarized Dark',
        'Precision contrast dark',
        ['background' => '#002b36', 'surface' => '#073642', 'foreground' => '#839496', 'muted' => '#586e75', 'primary' => '#268bd2', 'accent' => '#2aa198', 'danger' => '#dc322f', 'border' => '#094352'],
        ['background' => '#fdf6e3', 'surface' => '#ffffff', 'foreground' => '#657b83', 'muted' => '#93a1a1', 'primary' => '#268bd2', 'accent' => '#2aa198', 'danger' => '#dc322f'],
    ),
    'solarized-light' => $theme(
        'Solarized Light',
        'Precision contrast light',
        ['background' => '#073642', 'surface' => '#002b36', 'foreground' => '#93a1a1', 'muted' => '#657b83', 'primary' => '#268bd2', 'accent' => '#2aa198', 'danger' => '#dc322f', 'border' => '#094352'],
        ['background' => '#fdf6e3', 'surface' => '#ffffff', 'foreground' => '#586e75', 'muted' => '#839496', 'primary' => '#268bd2', 'accent' => '#2aa198', 'danger' => '#dc322f'],
    ),
    'monokai' => $theme(
        'Monokai',
        'Classic code editor neon',
        ['background' => '#272822', 'surface' => '#1e1f1c', 'foreground' => '#f8f8f2', 'muted' => '#75715e', 'primary' => '#ae81ff', 'accent' => '#66d9ef', 'danger' => '#f92672', 'border' => '#3e3d32'],
        ['background' => '#fafafa', 'surface' => '#ffffff', 'foreground' => '#272822', 'muted' => '#75715e', 'primary' => '#8f5fd4', 'accent' => '#0891b2', 'danger' => '#e11d48'],
    ),
    'github-dark' => $theme(
        'GitHub Dark',
        'GitHub dimmed dark UI',
        ['background' => '#0d1117', 'surface' => '#161b22', 'foreground' => '#c9d1d9', 'muted' => '#8b949e', 'primary' => '#58a6ff', 'accent' => '#79c0ff', 'danger' => '#f85149', 'border' => '#30363d'],
        ['background' => '#ffffff', 'surface' => '#f6f8fa', 'foreground' => '#24292f', 'muted' => '#656d76', 'primary' => '#0969da', 'accent' => '#0550ae', 'danger' => '#cf222e'],
    ),
    'github-light' => $theme(
        'GitHub Light',
        'Clean GitHub light UI',
        ['background' => '#24292f', 'surface' => '#1f2328', 'foreground' => '#f0f6fc', 'muted' => '#8b949e', 'primary' => '#4493f8', 'accent' => '#79c0ff', 'danger' => '#ff7b72', 'border' => '#30363d'],
        ['background' => '#ffffff', 'surface' => '#f6f8fa', 'foreground' => '#24292f', 'muted' => '#656d76', 'primary' => '#0969da', 'accent' => '#0550ae', 'danger' => '#cf222e'],
    ),
    'ayu-mirage' => $theme(
        'Ayu Mirage',
        'Muted teal and orange',
        ['background' => '#1f2430', 'surface' => '#171b24', 'foreground' => '#cccac2', 'muted' => '#707a8c', 'primary' => '#ffcc66', 'accent' => '#95e6cb', 'danger' => '#f28779', 'border' => '#2d3340'],
        ['background' => '#fafafa', 'surface' => '#ffffff', 'foreground' => '#575f66', 'muted' => '#828c99', 'primary' => '#ff9940', 'accent' => '#4cbf99', 'danger' => '#f07171'],
    ),
    'rose-pine' => $theme(
        'Rosé Pine',
        'Muted rose and pine',
        ['background' => '#191724', 'surface' => '#1f1d2e', 'foreground' => '#e0def4', 'muted' => '#908caa', 'primary' => '#c4a7e7', 'accent' => '#9ccfd8', 'danger' => '#eb6f92', 'border' => '#26233a'],
        ['background' => '#faf4ed', 'surface' => '#ffffff', 'foreground' => '#575279', 'muted' => '#9893a5', 'primary' => '#907aa9', 'accent' => '#56949f', 'danger' => '#b4637a'],
    ),
    'rose-pine-dawn' => $theme(
        'Rosé Pine Dawn',
        'Soft dawn pastels',
        ['background' => '#232136', 'surface' => '#2a273f', 'foreground' => '#e0def4', 'muted' => '#908caa', 'primary' => '#ea9a97', 'accent' => '#9ccfd8', 'danger' => '#eb6f92', 'border' => '#393552'],
        ['background' => '#faf4ed', 'surface' => '#ffffff', 'foreground' => '#575279', 'muted' => '#9893a5', 'primary' => '#d7827e', 'accent' => '#56949f', 'danger' => '#b4637a'],
    ),
    'night-owl' => $theme(
        'Night Owl',
        'Deep blue editor theme',
        ['background' => '#011627', 'surface' => '#0b2942', 'foreground' => '#d6deeb', 'muted' => '#637777', 'primary' => '#82aaff', 'accent' => '#7fdbca', 'danger' => '#ef5350', 'border' => '#1d3b53'],
        ['background' => '#fbfbfb', 'surface' => '#ffffff', 'foreground' => '#403f53', 'muted' => '#90a7b2', 'primary' => '#4876d6', 'accent' => '#08916a', 'danger' => '#de3d3b'],
    ),
    'palenight' => $theme(
        'Material Palenight',
        'Material purple night',
        ['background' => '#292d3e', 'surface' => '#232635', 'foreground' => '#a6accd', 'muted' => '#676e95', 'primary' => '#c792ea', 'accent' => '#89ddff', 'danger' => '#f07178', 'border' => '#32374d'],
        ['background' => '#fafafa', 'surface' => '#ffffff', 'foreground' => '#444444', 'muted' => '#777777', 'primary' => '#7c4dff', 'accent' => '#0097a7', 'danger' => '#e53935'],
    ),
    'cobalt2' => $theme(
        'Cobalt2',
        'Wes Bos deep cobalt blue',
        ['background' => '#193549', 'surface' => '#122738', 'foreground' => '#ffffff', 'muted' => '#adb7c2', 'primary' => '#ffc600', 'accent' => '#3ad900', 'danger' => '#ff628c', 'border' => '#234567'],
        ['background' => '#f5f8fc', 'surface' => '#ffffff', 'foreground' => '#193549', 'muted' => '#5c738a', 'primary' => '#0088ff', 'accent' => '#3ad900', 'danger' => '#ff628c'],
    ),
];
