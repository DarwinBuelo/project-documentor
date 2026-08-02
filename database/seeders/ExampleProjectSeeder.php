<?php

namespace Database\Seeders;

use App\Models\DocumentationPage;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ExampleProjectSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::query()->updateOrCreate(
            ['slug' => 'project-documentor'],
            [
                'name' => 'Project Documentor',
                'description' => 'A simple Laravel app for hosting project documentation pages in one place.',
            ],
        );

        $pages = [
            [
                'title' => 'Getting Started',
                'slug' => 'getting-started',
                'sort_order' => 1,
                'content' => <<<'MD'
## Welcome

Project Documentor helps teams publish lightweight documentation for their projects without setting up a separate wiki.

Each **project** contains ordered documentation pages. Pages support Markdown so you can write guides, runbooks, and reference material quickly.

### What you can do

- List all projects from the home page
- Browse documentation pages in a sidebar
- Seed example content to get started fast

### Quick links

- View all projects at `/`
- Open this example at `/projects/project-documentor`
MD,
            ],
            [
                'title' => 'Installation',
                'slug' => 'installation',
                'sort_order' => 2,
                'content' => <<<'MD'
## Requirements

- PHP 8.3+
- Composer
- Node.js 20+ (for frontend assets)
- MySQL 8 (or SQLite for local development)

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

Visit `http://localhost:8000` to see the projects list.

## Docker setup

```bash
docker compose up -d --build
docker compose exec app php artisan migrate --seed --force
```

The app will be available at `http://localhost:8081` (or your configured `APP_PORT`).
MD,
            ],
            [
                'title' => 'Writing Documentation',
                'slug' => 'writing-documentation',
                'sort_order' => 3,
                'content' => <<<'MD'
## Content format

Documentation pages store Markdown in the `content` column. Laravel renders them with `Str::markdown()`.

### Supported Markdown

- Headings (`##`, `###`)
- **Bold** and *italic* text
- Ordered and unordered lists
- Inline `code` and fenced code blocks
- Links

### Example page structure

1. **Overview** — what the feature does
2. **Setup** — how to install or configure it
3. **Usage** — common workflows
4. **Troubleshooting** — known issues and fixes

Keep pages focused. Shorter pages are easier to navigate than one long document.
MD,
            ],
            [
                'title' => 'Deployment',
                'slug' => 'deployment',
                'sort_order' => 4,
                'content' => <<<'MD'
## Production checklist

Before deploying, make sure you have:

- `APP_KEY` set
- `APP_ENV=production` and `APP_DEBUG=false`
- Database connection configured
- Migrations run: `php artisan migrate --force`
- Frontend assets built: `npm run build`

## Docker / Railway

This project ships with a production Dockerfile using nginx, PHP-FPM, and Supervisor.

Health checks respond at `/up` without bootstrapping Laravel.

### Environment variables

| Variable | Purpose |
| --- | --- |
| `APP_KEY` | Encryption key (required) |
| `DB_*` | Database connection |
| `APP_URL` | Public URL for links |

After deploy, seed the example project if you want demo content:

```bash
php artisan db:seed --class=ExampleProjectSeeder --force
```
MD,
            ],
        ];

        foreach ($pages as $page) {
            DocumentationPage::query()->updateOrCreate(
                [
                    'project_id' => $project->id,
                    'slug' => $page['slug'],
                ],
                [
                    'title' => $page['title'],
                    'content' => $page['content'],
                    'sort_order' => $page['sort_order'],
                ],
            );
        }
    }
}
