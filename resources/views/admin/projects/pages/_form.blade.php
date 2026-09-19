@php
    $page = $page ?? null;
    $nextSortOrder = $nextSortOrder ?? ($page?->sort_order ?? 1);
@endphp

<div class="space-y-4">
    <div>
        <label for="title" class="form-label">Page title</label>
        <input
            type="text"
            id="title"
            name="title"
            value="{{ old('title', $page?->title) }}"
            required
            class="form-input @error('title') form-input-error @enderror"
            placeholder="Getting Started"
        >
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="slug" class="form-label">URL slug</label>
            <input
                type="text"
                id="slug"
                name="slug"
                value="{{ old('slug', $page?->slug) }}"
                class="form-input @error('slug') form-input-error @enderror"
                placeholder="getting-started"
            >
            <p class="mt-1 text-[11px] leading-5 text-muted">
                Leave blank to generate from the title.
            </p>
        </div>

        <div>
            <label for="sort_order" class="form-label">Order</label>
            <input
                type="number"
                id="sort_order"
                name="sort_order"
                min="1"
                value="{{ old('sort_order', $page?->sort_order ?? $nextSortOrder) }}"
                class="form-input @error('sort_order') form-input-error @enderror"
            >
            <p class="mt-1 text-[11px] leading-5 text-muted">
                Controls sidebar order.
            </p>
        </div>
    </div>

    <div>
        <label for="content" class="form-label">Content (Markdown)</label>
        <textarea
            id="content"
            name="content"
            rows="18"
            required
            class="form-input font-mono text-xs leading-5 @error('content') form-input-error @enderror"
            placeholder="## Overview&#10;&#10;Write your documentation here..."
        >{{ old('content', $page?->content) }}</textarea>
        <p class="mt-1 text-[11px] leading-5 text-muted">
            Supports headings, lists, links, inline code, and fenced code blocks.
        </p>
    </div>
</div>
