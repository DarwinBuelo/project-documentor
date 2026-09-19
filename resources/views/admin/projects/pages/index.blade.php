@extends('layouts.admin')

@section('title', $project->name.' — Documentation — '.config('app.name'))

@section('content')
    <nav class="mb-4 flex flex-wrap items-center gap-1.5 text-xs text-muted">
        <a href="{{ route('admin.projects.index') }}" class="font-medium transition hover:text-primary-dark">Projects</a>
        <span class="text-muted-foreground">/</span>
        <span class="font-medium text-foreground">{{ $project->name }}</span>
        <span class="text-muted-foreground">/</span>
        <span class="text-primary-dark">Documentation</span>
    </nav>

    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="page-eyebrow">Documentation</p>
            <h1 class="mt-2 text-lg font-semibold tracking-tight text-foreground">{{ $project->name }}</h1>
            <p class="mt-1.5 max-w-xl text-xs leading-5 text-muted">
                Manage the pages shown in this project's public documentation sidebar.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-1.5">
            <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-secondary">
                Project settings
            </a>
            <a href="{{ route('admin.projects.pages.create', $project) }}" class="btn btn-primary">
                New page
            </a>
        </div>
    </div>

    @include('partials.flash')

    @if ($pages->isEmpty())
        <div class="empty-state">
            <h3 class="text-sm font-semibold text-foreground">No documentation pages yet</h3>
            <p class="mt-1.5 max-w-md text-xs leading-5 text-muted">
                Add your first page to start building this project's documentation.
            </p>
            <a href="{{ route('admin.projects.pages.create', $project) }}" class="btn btn-primary mt-4">
                Create page
            </a>
        </div>
    @else
        <div class="surface-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                            <tr>
                                <td class="text-muted">{{ $page->sort_order }}</td>
                                <td>
                                    <div class="text-sm font-medium text-foreground">{{ $page->title }}</div>
                                </td>
                                <td>
                                    <code class="rounded border border-border bg-background px-1.5 py-0.5 text-[11px] text-primary-dark">
                                        {{ $page->slug }}
                                    </code>
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if ($project->is_published)
                                            <a href="{{ route('pages.show', [$project, $page]) }}" class="btn btn-secondary btn-sm">
                                                View
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.projects.pages.edit', [$project, $page]) }}" class="btn btn-secondary btn-sm">
                                            Edit
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route('admin.projects.pages.destroy', [$project, $page]) }}"
                                            onsubmit="return confirm('Delete this documentation page?')"
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
