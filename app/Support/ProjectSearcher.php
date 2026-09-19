<?php

namespace App\Support;

use App\Models\Project;
use Illuminate\Support\Collection;

class ProjectSearcher
{
    public function __construct(private FuzzyMatcher $matcher) {}

    /**
     * @param  Collection<int, Project>  $projects
     * @return Collection<int, Project>
     */
    public function search(Collection $projects, string $query, int $threshold = 55): Collection
    {
        $query = trim($query);

        if ($query === '') {
            return $projects;
        }

        return $projects
            ->map(function (Project $project) use ($query, $threshold) {
                $score = max(
                    $this->matcher->score($query, $project->name) * 1.0,
                    $this->matcher->score($query, $project->slug) * 0.9,
                    $this->matcher->score($query, (string) $project->description) * 0.75,
                );

                return ['project' => $project, 'score' => $score];
            })
            ->filter(fn (array $result) => $result['score'] >= $threshold)
            ->sortByDesc('score')
            ->pluck('project')
            ->values();
    }
}
