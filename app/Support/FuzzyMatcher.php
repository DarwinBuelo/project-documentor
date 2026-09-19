<?php

namespace App\Support;

class FuzzyMatcher
{
    public function score(string $needle, string $haystack): int
    {
        $needle = mb_strtolower(trim($needle));
        $haystack = mb_strtolower(trim($haystack));

        if ($needle === '' || $haystack === '') {
            return 0;
        }

        if ($haystack === $needle) {
            return 100;
        }

        if (str_starts_with($haystack, $needle)) {
            return 95;
        }

        if (str_contains($haystack, $needle)) {
            return 85;
        }

        similar_text($needle, $haystack, $phraseSimilarity);

        $best = (int) $phraseSimilarity;

        foreach ($this->tokens($needle) as $token) {
            $best = max($best, $this->bestTokenScore($token, $haystack));
        }

        return min(100, $best);
    }

    public function matches(string $needle, string $haystack, int $threshold = 55): bool
    {
        return $this->score($needle, $haystack) >= $threshold;
    }

    /**
     * @return list<string>
     */
    private function tokens(string $value): array
    {
        $parts = preg_split('/[\s\-_]+/', $value) ?: [];

        return array_values(array_filter($parts, fn (string $part) => $part !== ''));
    }

    private function bestTokenScore(string $token, string $haystack): int
    {
        $best = 0;

        if (str_contains($haystack, $token)) {
            $best = max($best, 80);
        }

        similar_text($token, $haystack, $fullSimilarity);
        $best = max($best, (int) $fullSimilarity);

        foreach ($this->tokens($haystack) as $word) {
            if ($word === $token) {
                $best = max($best, 100);
                continue;
            }

            if (str_starts_with($word, $token) || str_contains($word, $token)) {
                $best = max($best, 85);
            }

            similar_text($token, $word, $wordSimilarity);
            $best = max($best, (int) $wordSimilarity);

            $maxDistance = max(1, (int) floor(mb_strlen($token) / 3));

            if ($this->withinLevenshteinDistance($token, $word, $maxDistance)) {
                $best = max($best, 72);
            }
        }

        return $best;
    }

    private function withinLevenshteinDistance(string $left, string $right, int $maxDistance): bool
    {
        if ($maxDistance < 1) {
            return $left === $right;
        }

        $lengthDifference = abs(mb_strlen($left) - mb_strlen($right));

        if ($lengthDifference > $maxDistance) {
            return false;
        }

        return levenshtein($left, $right) <= $maxDistance;
    }
}
