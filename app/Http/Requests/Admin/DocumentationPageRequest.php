<?php

namespace App\Http\Requests\Admin;

use App\Models\DocumentationPage;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentationPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Project $project */
        $project = $this->route('project');

        /** @var DocumentationPage|null $page */
        $page = $this->route('page');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('documentation_pages', 'slug')
                    ->where('project_id', $project->id)
                    ->ignore($page?->id),
            ],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'content' => ['required', 'string'],
        ];
    }
}
