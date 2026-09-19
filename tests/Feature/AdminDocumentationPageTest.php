<?php

namespace Tests\Feature;

use App\Models\DocumentationPage;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDocumentationPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_edit_documentation_page(): void
    {
        $user = User::factory()->create();

        $project = Project::query()->create([
            'name' => 'Project Documentor',
            'slug' => 'project-documentor',
            'description' => 'Example project',
            'is_published' => true,
        ]);

        $page = DocumentationPage::query()->create([
            'project_id' => $project->id,
            'title' => 'Getting Started',
            'slug' => 'getting-started',
            'sort_order' => 1,
            'content' => '## Welcome',
        ]);

        $response = $this->actingAs($user)->get(
            route('admin.projects.pages.edit', [$project, $page]),
        );

        $response->assertOk();
        $response->assertSee('Getting Started');
        $response->assertSee('## Welcome');
    }
}
