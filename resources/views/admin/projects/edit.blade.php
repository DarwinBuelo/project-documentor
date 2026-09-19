@extends('layouts.admin')

@section('title', 'Edit '.$project->name.' — '.config('app.name'))

@section('content')
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="page-eyebrow">Projects</p>
            <h1 class="mt-2 text-lg font-semibold tracking-tight text-foreground">Edit project</h1>
            <p class="mt-1.5 max-w-xl text-xs leading-5 text-muted">
                Update details for <span class="font-medium text-foreground">{{ $project->name }}</span>.
            </p>
        </div>

        <a href="{{ route('admin.projects.pages.index', $project) }}" class="btn btn-secondary">
            Manage documentation
        </a>
    </div>

    <div class="mx-auto max-w-xl">
        <div class="surface-card p-5">
            @include('partials.flash')

            <form method="POST" action="{{ route('admin.projects.update', $project) }}" class="space-y-5">
                @csrf
                @method('PUT')
                @include('admin.projects._form', ['project' => $project])

                <div class="flex flex-wrap items-center gap-2 border-t border-border pt-4">
                    <button type="submit" class="btn btn-primary">
                        Save changes
                    </button>
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
