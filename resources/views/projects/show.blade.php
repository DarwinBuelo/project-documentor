@extends('layouts.app')

@section('title', ($page?->title ?? $project->name).' — '.config('app.name'))

@section('content')
    <nav class="mb-5 flex flex-wrap items-center gap-1.5 text-xs text-muted">
        <a href="{{ route('projects.index') }}" class="font-medium transition hover:text-primary-dark">Projects</a>
        <span class="text-muted-foreground">/</span>
        <span class="font-medium text-foreground">{{ $project->name }}</span>
        @if ($page)
            <span class="text-muted-foreground">/</span>
            <span class="text-primary-dark">{{ $page->title }}</span>
        @endif
    </nav>

    @if ($project->pages->isEmpty())
        <div class="mb-6 max-w-3xl">
            <p class="page-eyebrow">Project documentation</p>
            <h1 class="mt-2 text-lg font-semibold tracking-tight text-foreground">{{ $project->name }}</h1>
            @if ($project->description)
                <p class="mt-2 text-sm leading-6 text-muted">{{ $project->description }}</p>
            @endif
        </div>

        <div class="empty-state">
            <h3 class="text-sm font-semibold text-foreground">No pages yet</h3>
            <p class="mt-1.5 max-w-md text-xs text-muted">This project does not have any documentation pages.</p>
        </div>
    @else
        <div class="grid min-h-[calc(100vh-12rem)] gap-5 xl:grid-cols-[240px_minmax(0,1fr)] 2xl:grid-cols-[260px_minmax(0,1fr)]">
            <aside class="xl:sticky xl:top-[4.5rem] xl:self-start">
                <div class="surface-card p-4">
                    <div class="mb-4 border-b border-border-subtle pb-4">
                        <p class="page-eyebrow">Project</p>
                        <h1 class="mt-1.5 text-sm font-semibold tracking-tight text-foreground">{{ $project->name }}</h1>
                        @if ($project->description)
                            <p class="mt-1.5 text-xs leading-5 text-muted">{{ $project->description }}</p>
                        @endif
                    </div>

                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="text-[11px] font-semibold uppercase tracking-[0.14em] text-muted">Contents</h2>
                        <span class="rounded-full bg-primary-light px-2 py-0.5 text-[10px] font-semibold text-primary">
                            {{ $project->pages->count() }}
                        </span>
                    </div>

                    <nav class="space-y-0.5">
                        @foreach ($project->pages as $docPage)
                            <a
                                href="{{ route('pages.show', [$project, $docPage]) }}"
                                @class([
                                    'sidebar-link',
                                    'sidebar-link-active' => $page && $page->id === $docPage->id,
                                ])
                            >
                                <span class="sidebar-step">{{ $docPage->sort_order }}</span>
                                <span>{{ $docPage->title }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            <article class="surface-card flex min-h-full flex-col overflow-hidden">
                @if ($page)
                    <div class="border-b border-border bg-gradient-to-r from-hero-from via-surface to-background px-5 py-5 lg:px-8 lg:py-6">
                        <p class="page-eyebrow">Documentation page</p>
                        <h2 class="mt-1.5 text-lg font-semibold tracking-tight text-foreground">{{ $page->title }}</h2>
                    </div>

                    <div class="flex-1 px-5 py-5 lg:px-8 lg:py-6">
                        <div class="doc-content max-w-none xl:max-w-3xl">
                            {!! Str::markdown($page->content) !!}
                        </div>
                    </div>
                @endif
            </article>
        </div>
    @endif
@endsection
