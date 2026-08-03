@extends('layouts.app')

@section('title', 'Projects — '.config('app.name'))

@section('hero')
    <section class="border-b border-border bg-gradient-to-br from-hero-from via-hero-via to-hero-to">
        <div class="page-container py-8 lg:py-10">
            <p class="page-eyebrow">Documentation</p>
            <h1 class="mt-2 max-w-2xl text-xl font-semibold tracking-tight text-foreground lg:text-2xl">
                This is a test
            </h1>
            <p class="mt-3 max-w-xl text-sm leading-6 text-muted">
                ttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt
            </p>
        </div>
    </section>
@endsection

@section('content')
    <div class="mb-6 flex items-end justify-between gap-4">
        <div>
            <h2 class="text-base font-semibold tracking-tight text-foreground">All projects</h2>
            <p class="mt-0.5 text-xs text-muted">{{ $projects->count() }} {{ Str::plural('project', $projects->count()) }} available</p>
        </div>
    </div>

    @if ($projects->isEmpty())
        <div class="empty-state">
            <div class="mb-3 flex size-10 items-center justify-center rounded-lg bg-primary-light text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-foreground">No projects yet</h3>
            <p class="mt-1.5 max-w-md text-xs leading-5 text-muted">
                Seed the example project to preview the documentation experience.
            </p>
            <code class="mt-4 rounded-lg border border-border bg-background px-3 py-1.5 text-xs text-primary-dark">
                php artisan db:seed --class=ExampleProjectSeeder
            </code>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            @foreach ($projects as $project)
                <a href="{{ route('projects.show', $project) }}" class="group project-card">
                    <div class="mb-3 flex size-9 items-center justify-center rounded-lg bg-primary-light text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                        </svg>
                    </div>

                    <h3 class="text-sm font-semibold tracking-tight text-foreground transition group-hover:text-primary-dark">
                        {{ $project->name }}
                    </h3>

                    @if ($project->description)
                        <p class="mt-2 line-clamp-3 text-xs leading-5 text-muted">{{ $project->description }}</p>
                    @endif

                    <div class="mt-4 flex items-center justify-between border-t border-border-subtle pt-3">
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wider text-primary">
                            <span class="size-1 rounded-full bg-primary"></span>
                            {{ $project->pages_count }} {{ Str::plural('page', $project->pages_count) }}
                        </span>
                        <span class="text-xs font-medium text-primary opacity-0 transition group-hover:opacity-100">
                            Open docs &rarr;
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
