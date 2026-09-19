<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function pages(): HasMany
    {
        return $this->hasMany(DocumentationPage::class)->orderBy('sort_order');
    }

    public function loadNavigationPages(): void
    {
        $this->setRelation(
            'pages',
            $this->pages()
                ->select('id', 'project_id', 'title', 'slug', 'sort_order')
                ->get(),
        );
    }
}
