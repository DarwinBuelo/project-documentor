@extends('layouts.app')

@section('title', 'Admin Login — '.config('app.name'))

@section('content')
    <div class="mx-auto max-w-sm">
        <div class="mb-6 text-center">
            <p class="page-eyebrow">Administration</p>
            <h1 class="mt-2 text-lg font-semibold tracking-tight text-foreground">Sign in</h1>
            <p class="mt-2 text-xs leading-5 text-muted">
                Manage projects and control what appears on the public site.
            </p>
        </div>

        <div class="surface-card p-5">
            @include('partials.flash')

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="form-label">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="form-input @error('email') form-input-error @enderror"
                    >
                </div>

                <div>
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="form-input @error('password') form-input-error @enderror"
                    >
                </div>

                <label class="flex items-center gap-2 text-xs text-muted">
                    <input type="checkbox" name="remember" value="1" class="form-checkbox">
                    <span>Remember me</span>
                </label>

                <button type="submit" class="btn btn-primary w-full">
                    Sign in
                </button>
            </form>
        </div>
    </div>
@endsection
