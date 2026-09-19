<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DocumentationPage extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'slug',
        'content',
        'sort_order',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function renderedContent(): string
    {
        if ($this->content === null || $this->content === '') {
            return '';
        }

        $cacheKey = 'documentation_page_html_'.$this->id.'_'.$this->updated_at?->getTimestamp();

        return Cache::remember($cacheKey, now()->addDays(7), fn (): string => Str::markdown($this->content));
    }
}
