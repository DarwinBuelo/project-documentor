<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\ThemeManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_site_appearance(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('admin.appearance.update'), [
            'color_palette' => 'nord',
            'appearance_mode' => 'light',
        ]);

        $response->assertRedirect(route('admin.appearance.edit'));
        $this->assertSame('nord', app(ThemeManager::class)->paletteSlug());
        $this->assertSame('light', app(ThemeManager::class)->appearanceMode());
    }

    public function test_homepage_includes_selected_palette_styles(): void
    {
        app(ThemeManager::class)->update('tokyo-night', 'dark');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('--app-background: #1a1b26;', false);
    }
}
