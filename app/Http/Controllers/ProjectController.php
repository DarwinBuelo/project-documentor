<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $projects = Project::query()
            ->where('is_published', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->withCount('pages')
            ->orderBy('name')
            ->get();

        return view('projects.index', compact('projects', 'search'));
    }

    public function show(Project $project): View
    {
        abort_unless($project->is_published, 404);

        $project->load('pages');

        $page = $project->pages->first();

        return view('projects.show', compact('project', 'page'));
    }
}
