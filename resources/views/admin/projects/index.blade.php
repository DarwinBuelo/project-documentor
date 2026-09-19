@extends('layouts.admin')

@section('title', 'Manage Projects — '.config('app.name'))

@section('content')
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="page-eyebrow">Projects</p>
            <h1 class="mt-2 text-lg font-semibold tracking-tight text-foreground">Manage projects</h1>
            <p class="mt-1.5 max-w-xl text-xs leading-5 text-muted">
                Create, edit, and publish documentation projects for the public site.
            </p>
        </div>

        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
            New project
        </a>
    </div>

    @include('partials.flash')

    @if ($projects->isEmpty())
        <div class="empty-state">
            <h3 class="text-sm font-semibold text-foreground">No projects yet</h3>
            <p class="mt-1.5 max-w-md text-xs leading-5 text-muted">
                Create your first project to start publishing documentation.
            </p>
            <a href="{{ route('admin.projects.create') }}" class="btn btn-primary mt-4">
                Create project
            </a>
        </div>
    @else
        <div class="surface-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Slug</th>
                            <th>Pages</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td>
                                    <div class="text-sm font-medium text-foreground">{{ $project->name }}</div>
                                    @if ($project->description)
                                        <div class="mt-0.5 max-w-md text-xs leading-5 text-muted line-clamp-2">
                                            {{ $project->description }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <code class="rounded border border-border bg-background px-1.5 py-0.5 text-[11px] text-primary-dark">
                                        {{ $project->slug }}
                                    </code>
                                </td>
                                <td class="text-muted">{{ $project->pages_count }}</td>
                                <td>
                                    @if ($project->is_published)
                                        <span class="status-badge status-badge-published">Published</span>
                                    @else
                                        <span class="status-badge status-badge-draft">Hidden</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.projects.pages.index', $project) }}" class="btn btn-secondary btn-sm">
                                            Docs
                                        </a>
                                        @if ($project->is_published)
                                            <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary btn-sm">
                                                View
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-secondary btn-sm">
                                            Edit
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route('admin.projects.destroy', $project) }}"
                                            onsubmit="return confirm('Delete this project and all of its documentation pages?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
