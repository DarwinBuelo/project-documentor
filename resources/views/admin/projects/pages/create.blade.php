@extends('layouts.admin')

@section('title', 'New Page — '.$project->name.' — '.config('app.name'))

@section('content')
    <nav class="mb-4 flex flex-wrap items-center gap-1.5 text-xs text-muted">
        <a href="{{ route('admin.projects.index') }}" class="font-medium transition hover:text-primary-dark">Projects</a>
        <span class="text-muted-foreground">/</span>
        <a href="{{ route('admin.projects.pages.index', $project) }}" class="font-medium transition hover:text-primary-dark">{{ $project->name }}</a>
        <span class="text-muted-foreground">/</span>
        <span class="text-primary-dark">New page</span>
    </nav>

    <div class="mb-6">
        <p class="page-eyebrow">Documentation</p>
        <h1 class="mt-2 text-lg font-semibold tracking-tight text-foreground">New page</h1>
        <p class="mt-1.5 max-w-xl text-xs leading-5 text-muted">
            Add a documentation page to <span class="font-medium text-foreground">{{ $project->name }}</span>.
        </p>
    </div>

    <div class="mx-auto max-w-3xl">
        <div class="surface-card p-5">
            @include('partials.flash')

            <form method="POST" action="{{ route('admin.projects.pages.store', $project) }}" class="space-y-5">
                @csrf
                @include('admin.projects.pages._form', ['nextSortOrder' => $nextSortOrder])

                <div class="flex flex-wrap items-center gap-2 border-t border-border pt-4">
                    <button type="submit" class="btn btn-primary">
                        Create page
                    </button>
                    <a href="{{ route('admin.projects.pages.index', $project) }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
