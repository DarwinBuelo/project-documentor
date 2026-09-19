<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_fuzzy_search_matches_projects_with_typos(): void
    {
        Project::query()->create([
            'name' => 'Project Documentor',
            'slug' => 'project-documentor',
            'description' => 'A simple Laravel app for hosting project documentation pages.',
            'is_published' => true,
        ]);

        $response = $this->get('/?search=documntor');

        $response->assertOk();
        $response->assertSee('Project Documentor');
    }

    public function test_fuzzy_search_matches_partial_words(): void
    {
        Project::query()->create([
            'name' => 'Project Documentor',
            'slug' => 'project-documentor',
            'description' => 'Documentation hub',
            'is_published' => true,
        ]);

        $response = $this->get('/?search=doc hub');

        $response->assertOk();
        $response->assertSee('Project Documentor');
    }
}
