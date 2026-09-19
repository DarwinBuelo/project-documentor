<?php

namespace App\Http\Requests\Admin;

use App\Support\ThemeManager;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ThemeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'color_palette' => ['required', 'string', Rule::in(array_keys(app(ThemeManager::class)->palettes()))],
            'appearance_mode' => ['required', 'string', Rule::in(['dark', 'light'])],
        ];
    }
}
