<?php

namespace Tests\Feature;

use App\Models\DocumentationPage;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DocumentationPagePerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_documentation_page_avoids_redundant_queries(): void
    {
        $project = Project::query()->create([
            'name' => 'Project Documentor',
            'slug' => 'project-documentor',
            'description' => 'Example project',
            'is_published' => true,
        ]);

        foreach (range(1, 5) as $index) {
            DocumentationPage::query()->create([
                'project_id' => $project->id,
                'title' => "Page {$index}",
                'slug' => "page-{$index}",
                'sort_order' => $index,
                'content' => str_repeat("## Section {$index}\n\nContent.\n\n", 20),
            ]);
        }

        DB::enableQueryLog();

        $response = $this->get(route('pages.show', [$project, 'page-3']));

        $response->assertOk();
        $response->assertSee('Page 3');

        $queries = collect(DB::getQueryLog())->pluck('query');

        $this->assertSame(
            1,
            $queries->filter(fn (string $query): bool => str_contains($query, 'from "site_settings"'))->count(),
            'Site settings should be loaded in a single query.',
        );

        $pageContentQueries = $queries->filter(
            fn (string $query): bool => str_contains($query, 'from "documentation_pages"')
                && str_contains($query, 'content'),
        );

        $this->assertLessThanOrEqual(
            1,
            $pageContentQueries->count(),
            'Only the active page content should be loaded.',
        );
    }
}
