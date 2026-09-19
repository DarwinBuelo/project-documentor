<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DocumentationPageRequest;
use App\Models\DocumentationPage;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DocumentationPageController extends Controller
{
    public function index(Project $project): View
    {
        $pages = $project->pages()->orderBy('sort_order')->get();

        return view('admin.projects.pages.index', compact('project', 'pages'));
    }

    public function create(Project $project): View
    {
        $nextSortOrder = ($project->pages()->max('sort_order') ?? 0) + 1;

        return view('admin.projects.pages.create', compact('project', 'nextSortOrder'));
    }

    public function store(DocumentationPageRequest $request, Project $project): RedirectResponse
    {
        $project->pages()->create($this->pageAttributes($request, $project));

        return redirect()
            ->route('admin.projects.pages.index', $project)
            ->with('status', 'Documentation page created successfully.');
    }

    public function edit(Project $project, DocumentationPage $page): View
    {
        $this->ensurePageBelongsToProject($project, $page);

        return view('admin.projects.pages.edit', compact('project', 'page'));
    }

    public function update(
        DocumentationPageRequest $request,
        Project $project,
        DocumentationPage $page,
    ): RedirectResponse {
        $this->ensurePageBelongsToProject($project, $page);

        $page->update($this->pageAttributes($request, $project, $page));

        return redirect()
            ->route('admin.projects.pages.index', $project)
            ->with('status', 'Documentation page updated successfully.');
    }

    public function destroy(Project $project, DocumentationPage $page): RedirectResponse
    {
        $this->ensurePageBelongsToProject($project, $page);

        $page->delete();

        return redirect()
            ->route('admin.projects.pages.index', $project)
            ->with('status', 'Documentation page deleted successfully.');
    }

    private function ensurePageBelongsToProject(Project $project, DocumentationPage $page): void
    {
        abort_unless($page->project_id === $project->id, 404);
    }

    /**
     * @return array<string, mixed>
     */
    private function pageAttributes(
        DocumentationPageRequest $request,
        Project $project,
        ?DocumentationPage $page = null,
    ): array {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['slug'] ?? $data['title']);
        $data['sort_order'] = $data['sort_order']
            ?? ($page?->sort_order ?? (($project->pages()->max('sort_order') ?? 0) + 1));

        return $data;
    }
}
