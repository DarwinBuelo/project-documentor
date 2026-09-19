<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ThemeRequest;
use App\Support\ThemeManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ThemeController extends Controller
{
    public function edit(ThemeManager $themes): View
    {
        return view('admin.appearance.edit', [
            'palettes' => $themes->palettes(),
            'selectedPalette' => $themes->paletteSlug(),
            'selectedAppearance' => $themes->appearanceMode(),
        ]);
    }

    public function update(ThemeRequest $request, ThemeManager $themes): RedirectResponse
    {
        $themes->update(
            $request->string('color_palette')->toString(),
            $request->string('appearance_mode')->toString(),
        );

        return redirect()
            ->route('admin.appearance.edit')
            ->with('status', 'Site appearance updated successfully.');
    }
}
