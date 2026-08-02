<?php

namespace App\Http\Controllers;

use App\Models\DocumentationPage;
use App\Models\Project;
use Illuminate\View\View;

class DocumentationPageController extends Controller
{
    public function show(Project $project, DocumentationPage $page): View
    {
        abort_unless($page->project_id === $project->id, 404);

        $project->load('pages');

        return view('projects.show', compact('project', 'page'));
    }
}
