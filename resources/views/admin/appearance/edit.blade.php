@extends('layouts.admin')

@section('title', 'Appearance — '.config('app.name'))

@section('content')
    <div class="mb-6">
        <p class="page-eyebrow">Settings</p>
        <h1 class="mt-2 text-lg font-semibold tracking-tight text-foreground">Appearance</h1>
        <p class="mt-1.5 max-w-2xl text-xs leading-5 text-muted">
            Choose the default color palette and light/dark mode for the public site. Visitors can still toggle mode in the header.
        </p>
    </div>

    @include('partials.flash')

    <form method="POST" action="{{ route('admin.appearance.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="surface-card p-5">
            <h2 class="text-sm font-semibold text-foreground">Default appearance mode</h2>
            <p class="mt-1 text-xs leading-5 text-muted">Used when a visitor has not chosen a preference yet.</p>

            <div class="mt-4 flex flex-wrap gap-3">
                <label class="appearance-option @if($selectedAppearance === 'dark') appearance-option-active @endif">
                    <input type="radio" name="appearance_mode" value="dark" @checked(old('appearance_mode', $selectedAppearance) === 'dark') class="sr-only">
                    <span class="appearance-option-icon">🌙</span>
                    <span class="appearance-option-label">Dark</span>
                </label>

                <label class="appearance-option @if($selectedAppearance === 'light') appearance-option-active @endif">
                    <input type="radio" name="appearance_mode" value="light" @checked(old('appearance_mode', $selectedAppearance) === 'light') class="sr-only">
                    <span class="appearance-option-icon">☀️</span>
                    <span class="appearance-option-label">Light</span>
                </label>
            </div>
        </div>

        <div class="surface-card p-5">
            <h2 class="text-sm font-semibold text-foreground">Color palette</h2>
            <p class="mt-1 text-xs leading-5 text-muted">Pick one of 20 popular editor-inspired themes.</p>

            <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                @foreach ($palettes as $slug => $palette)
                    @php($preview = $palette['preview'])
                    <label @class(['theme-card', 'theme-card-active' => old('color_palette', $selectedPalette) === $slug])>
                        <input
                            type="radio"
                            name="color_palette"
                            value="{{ $slug }}"
                            @checked(old('color_palette', $selectedPalette) === $slug)
                            class="sr-only"
                        >

                        <div class="theme-card-preview" style="background: {{ $preview['background'] }};">
                            <span class="theme-card-bar" style="background: {{ $preview['surface'] }};"></span>
                            <div class="theme-card-swatches">
                                <span style="background: {{ $preview['primary'] }};"></span>
                                <span style="background: {{ $preview['accent'] }};"></span>
                                <span style="background: {{ $preview['surface'] }}; border: 1px solid {{ $preview['primary'] }};"></span>
                            </div>
                        </div>

                        <div class="theme-card-body">
                            <span class="theme-card-title">{{ $palette['name'] }}</span>
                            <span class="theme-card-description">{{ $palette['description'] }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button type="submit" class="btn btn-primary">
                Save appearance
            </button>
            <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
    <script>
        document.querySelectorAll('.appearance-option input, .theme-card input').forEach((input) => {
            input.addEventListener('change', () => {
                document.querySelectorAll('.appearance-option').forEach((el) => el.classList.remove('appearance-option-active'));
                document.querySelectorAll('.theme-card').forEach((el) => el.classList.remove('theme-card-active'));

                const parent = input.closest('.appearance-option') ?? input.closest('.theme-card');
                parent?.classList.add(parent.classList.contains('appearance-option') ? 'appearance-option-active' : 'theme-card-active');
            });
        });
    </script>
@endsection
