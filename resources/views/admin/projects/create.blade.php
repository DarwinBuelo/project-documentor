@extends('layouts.admin')

@section('title', 'New Project — '.config('app.name'))

@section('content')
    <div class="mb-6">
        <p class="page-eyebrow">Projects</p>
        <h1 class="mt-2 text-lg font-semibold tracking-tight text-foreground">New project</h1>
        <p class="mt-1.5 max-w-xl text-xs leading-5 text-muted">
            Add a project that visitors can browse from the public documentation hub.
        </p>
    </div>

    <div class="mx-auto max-w-xl">
        <div class="surface-card p-5">
            @include('partials.flash')

            <form method="POST" action="{{ route('admin.projects.store') }}" class="space-y-5">
                @csrf
                @include('admin.projects._form')

                <div class="flex flex-wrap items-center gap-2 border-t border-border pt-4">
                    <button type="submit" class="btn btn-primary">
                        Create project
                    </button>
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
