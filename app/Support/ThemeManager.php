<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Arr;

class ThemeManager
{
    public const KEY_PALETTE = 'color_palette';

    public const KEY_APPEARANCE = 'appearance_mode';

    public const DEFAULT_PALETTE = 'dracula-soft';

    public const DEFAULT_APPEARANCE = 'dark';

    /**
     * @return array<string, array<string, mixed>>
     */
    public function palettes(): array
    {
        return config('themes', []);
    }

    public function paletteSlug(): string
    {
        $slug = SiteSetting::getValue(self::KEY_PALETTE, self::DEFAULT_PALETTE);

        return array_key_exists($slug, $this->palettes())
            ? $slug
            : self::DEFAULT_PALETTE;
    }

    public function appearanceMode(): string
    {
        $mode = SiteSetting::getValue(self::KEY_APPEARANCE, self::DEFAULT_APPEARANCE);

        return in_array($mode, ['dark', 'light'], true)
            ? $mode
            : self::DEFAULT_APPEARANCE;
    }

    /**
     * @return array<string, mixed>
     */
    public function activePalette(): array
    {
        return $this->palettes()[$this->paletteSlug()];
    }

    /**
     * @return array<string, string>
     */
    public function colorsForMode(string $mode): array
    {
        $palette = $this->activePalette();

        return $palette[$mode] ?? $palette['dark'];
    }

    public function cssVariablesForMode(string $mode): string
    {
        $variables = [];

        foreach ($this->colorsForMode($mode) as $key => $value) {
            $variables[] = '--app-'.str_replace('_', '-', $key).': '.$value.';';
        }

        return implode("\n    ", $variables);
    }

    public function update(string $paletteSlug, string $appearanceMode): void
    {
        if (! array_key_exists($paletteSlug, $this->palettes())) {
            abort(422, 'Invalid color palette.');
        }

        if (! in_array($appearanceMode, ['dark', 'light'], true)) {
            abort(422, 'Invalid appearance mode.');
        }

        SiteSetting::setValue(self::KEY_PALETTE, $paletteSlug);
        SiteSetting::setValue(self::KEY_APPEARANCE, $appearanceMode);
    }

    /**
     * @return array{palette: string, mode: string, name: string}
     */
    public function clientConfig(): array
    {
        return [
            'palette' => $this->paletteSlug(),
            'mode' => $this->appearanceMode(),
            'name' => Arr::get($this->activePalette(), 'name', 'Theme'),
        ];
    }
}
