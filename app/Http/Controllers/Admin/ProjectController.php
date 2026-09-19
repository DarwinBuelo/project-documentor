<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::query()
            ->withCount('pages')
            ->orderBy('name')
            ->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('admin.projects.create');
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        Project::query()->create($this->projectAttributes($request));

        return redirect()
            ->route('admin.projects.index')
            ->with('status', 'Project created successfully.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($this->projectAttributes($request));

        return redirect()
            ->route('admin.projects.index')
            ->with('status', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('status', 'Project deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function projectAttributes(ProjectRequest $request): array
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['slug'] ?? $data['name']);

        return $data;
    }
}
