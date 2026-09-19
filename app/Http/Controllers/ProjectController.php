<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\ProjectSearcher;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request, ProjectSearcher $projectSearcher): View
    {
        $search = $request->string('search')->trim()->toString();

        $projects = Project::query()
            ->where('is_published', true)
            ->withCount('pages')
            ->orderBy('name')
            ->get();

        $projects = $projectSearcher->search($projects, $search);

        return view('projects.index', compact('projects', 'search'));
    }

    public function show(Project $project): View
    {
        abort_unless($project->is_published, 404);

        $page = $project->pages()->first();
        $project->loadNavigationPages();

        return view('projects.show', compact('project', 'page'));
    }
}
