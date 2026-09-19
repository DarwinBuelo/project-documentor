@php
    $project = $project ?? null;
@endphp

<div class="space-y-4">
    <div>
        <label for="name" class="form-label">Project name</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $project?->name) }}"
            required
            class="form-input @error('name') form-input-error @enderror"
            placeholder="My Documentation Project"
        >
    </div>

    <div>
        <label for="slug" class="form-label">URL slug</label>
        <input
            type="text"
            id="slug"
            name="slug"
            value="{{ old('slug', $project?->slug) }}"
            class="form-input @error('slug') form-input-error @enderror"
            placeholder="my-documentation-project"
        >
        <p class="mt-1 text-[11px] leading-5 text-muted">
            Used in the public URL. Leave blank to generate from the project name.
        </p>
    </div>

    <div>
        <label for="description" class="form-label">Description</label>
        <textarea
            id="description"
            name="description"
            rows="3"
            class="form-input @error('description') form-input-error @enderror"
            placeholder="A short summary shown on the projects list."
        >{{ old('description', $project?->description) }}</textarea>
    </div>

    <label class="flex items-start gap-2 rounded-lg border border-border bg-background px-3 py-2.5">
        <input
            type="checkbox"
            name="is_published"
            value="1"
            @checked(old('is_published', $project?->is_published ?? true))
            class="form-checkbox mt-0.5"
        >
        <span>
            <span class="block text-xs font-medium text-foreground">Published on the site</span>
            <span class="mt-0.5 block text-[11px] leading-5 text-muted">
                When unchecked, the project stays in the admin but is hidden from visitors.
            </span>
        </span>
    </label>
</div>
