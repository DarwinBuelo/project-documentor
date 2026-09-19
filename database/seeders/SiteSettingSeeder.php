<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Support\ThemeManager;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::setValue(ThemeManager::KEY_PALETTE, ThemeManager::DEFAULT_PALETTE);
        SiteSetting::setValue(ThemeManager::KEY_APPEARANCE, ThemeManager::DEFAULT_APPEARANCE);
    }
}
