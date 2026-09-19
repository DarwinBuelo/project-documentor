<?php

namespace App\Support;

class ThemePalette
{
    /**
     * @param  array<string, mixed>  $colors
     * @return array<string, string>
     */
    public static function expand(array $colors): array
    {
        $isDark = (bool) ($colors['is_dark'] ?? true);
        $background = $colors['background'];
        $surface = $colors['surface'] ?? $background;
        $foreground = $colors['foreground'];
        $muted = $colors['muted'];
        $primary = $colors['primary'];
        $accent = $colors['accent'] ?? $primary;
        $danger = $colors['danger'] ?? '#ee6666';

        $primaryRgb = self::toRgb($primary);
        $dangerRgb = self::toRgb($danger);

        return [
            'background' => $background,
            'surface' => $surface,
            'header' => self::withAlpha($surface, 0.92),
            'foreground' => $foreground,
            'muted' => $muted,
            'muted_foreground' => $colors['muted_foreground'] ?? $muted,
            'border' => $colors['border'] ?? ($isDark ? self::mix($background, '#ffffff', 0.18) : self::mix($background, '#000000', 0.12)),
            'border_subtle' => $colors['border_subtle'] ?? ($isDark ? self::mix($surface, '#ffffff', 0.08) : self::mix($background, '#000000', 0.06)),
            'primary' => $primary,
            'primary_dark' => $colors['primary_dark'] ?? self::adjustBrightness($primary, $isDark ? 12 : -8),
            'primary_muted' => $colors['primary_muted'] ?? $accent,
            'primary_light' => $colors['primary_light'] ?? "rgb({$primaryRgb} / ".($isDark ? '0.16' : '0.12').')',
            'accent' => $accent,
            'danger' => $danger,
            'danger_dark' => $colors['danger_dark'] ?? self::adjustBrightness($danger, 10),
            'danger_light' => $colors['danger_light'] ?? "rgb({$dangerRgb} / ".($isDark ? '0.14' : '0.1').')',
            'hero_from' => $colors['hero_from'] ?? "rgb({$primaryRgb} / ".($isDark ? '0.14' : '0.1').')',
            'hero_via' => $colors['hero_via'] ?? $background,
            'hero_to' => $colors['hero_to'] ?? $surface,
            'code_bg' => $colors['code_bg'] ?? "rgb({$primaryRgb} / ".($isDark ? '0.12' : '0.1').')',
            'code_text' => $colors['code_text'] ?? ($colors['primary_dark'] ?? self::adjustBrightness($primary, $isDark ? 15 : -5)),
            'pre_bg' => $colors['pre_bg'] ?? ($isDark ? self::adjustBrightness($background, -8) : '#282a36'),
            'pre_text' => $colors['pre_text'] ?? ($isDark ? $foreground : '#f6f6f4'),
            'shadow' => $colors['shadow'] ?? ($isDark ? 'rgb(0 0 0 / 0.55)' : 'rgb(31 31 31 / 0.08)'),
            'brand_text' => $colors['brand_text'] ?? ($isDark ? $background : '#ffffff'),
            'card_glow' => $colors['card_glow'] ?? "rgb({$primaryRgb} / ".($isDark ? '0.35' : '0.25').')',
        ];
    }

    public static function withAlpha(string $hex, float $alpha): string
    {
        $rgb = self::toRgb($hex);

        return "rgb({$rgb} / {$alpha})";
    }

    public static function toRgb(string $color): string
    {
        $color = ltrim(trim($color), '#');

        if (strlen($color) === 3) {
            $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
        }

        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));

        return "{$r} {$g} {$b}";
    }

    public static function adjustBrightness(string $hex, int $percent): string
    {
        $color = ltrim(trim($hex), '#');

        if (strlen($color) === 3) {
            $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
        }

        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));

        $adjust = static function (int $channel) use ($percent): int {
            $value = $channel + (int) round(255 * ($percent / 100));

            return max(0, min(255, $value));
        };

        return sprintf('#%02x%02x%02x', $adjust($r), $adjust($g), $adjust($b));
    }

    public static function mix(string $hex, string $mixWith, float $weight): string
    {
        $base = array_map('hexdec', str_split(ltrim($hex, '#'), 2));
        $mix = array_map('hexdec', str_split(ltrim($mixWith, '#'), 2));

        $channels = [];
        foreach ($base as $index => $channel) {
            $channels[] = (int) round($channel * (1 - $weight) + $mix[$index] * $weight);
        }

        return sprintf('#%02x%02x%02x', $channels[0], $channels[1], $channels[2]);
    }
}
