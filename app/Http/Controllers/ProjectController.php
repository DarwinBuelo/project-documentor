<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::query()
            ->withCount('pages')
            ->orderBy('name')
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project): View
    {
        $project->load('pages');

        $page = $project->pages->first();

        return view('projects.show', compact('project', 'page'));
    }
}
