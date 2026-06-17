<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Talent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class TalentJobMatchingService
{
    public function score(Talent $talent, Job $job): int
    {
        return (int) array_sum($this->scoreBreakdown($talent, $job));
    }

    public function scoreBreakdown(Talent $talent, Job $job): array
    {
        $weights = config('matching.weights');

        $skillsScore = $this->skillsScore($talent, $job, $weights['skills']);
        $specialtyScore = ($talent->tech_specialty_id && $talent->tech_specialty_id === $job->tech_specialty_id)
            ? $weights['specialty'] : 0;

        $openScore = ($talent->is_open_to_work || $talent->activePublicHiringRequest) ? $weights['open_to_work'] : 0;

        $remoteScore = 0;
        if ($talent->is_remote && in_array($job->remote_type, ['full-remote', 'hybrid'], true)) {
            $remoteScore = $weights['remote'];
        }

        $rateScore = $this->rateScore($talent, $job, $weights['rate']);

        $syriaScore = ($job->is_syria_friendly && $talent->is_remote) ? $weights['syria_friendly'] : 0;

        return [
            'skills' => $skillsScore,
            'specialty' => $specialtyScore,
            'open_to_work' => $openScore,
            'remote' => $remoteScore,
            'rate' => $rateScore,
            'syria_friendly' => $syriaScore,
        ];
    }

    public function topTalentsForJob(Job $job, int $limit = 10): Collection
    {
        $cacheKey = 'match.job.'.$job->id.'.'.$limit;

        return Cache::remember($cacheKey, now()->addMinutes(config('matching.cache_ttl_minutes', 15)), function () use ($job, $limit) {
            $min = config('matching.min_display_score', 40);

            return Talent::query()
                ->active()
                ->with('techSpecialty')
                ->get()
                ->map(fn (Talent $talent) => [
                    'talent' => $talent,
                    'score' => $this->score($talent, $job),
                ])
                ->filter(fn (array $row) => $row['score'] >= $min)
                ->sortByDesc('score')
                ->take($limit)
                ->values();
        });
    }

    public function topJobsForTalent(Talent $talent, int $limit = 10): Collection
    {
        $min = config('matching.min_display_score', 40);

        return Job::query()
            ->active()
            ->with('techSpecialty')
            ->get()
            ->map(fn (Job $job) => [
                'job' => $job,
                'score' => $this->score($talent, $job),
            ])
            ->filter(fn (array $row) => $row['score'] >= $min)
            ->sortByDesc('score')
            ->take($limit)
            ->values();
    }

    private function skillsScore(Talent $talent, Job $job, int $max): int
    {
        $talentSkills = collect($talent->skills ?? [])->map(fn ($s) => mb_strtolower(trim($s)))->filter();
        $jobSkills = collect($job->skills ?? [])->map(fn ($s) => mb_strtolower(trim($s)))->filter();

        if ($talentSkills->isEmpty() || $jobSkills->isEmpty()) {
            return 0;
        }

        $overlap = $talentSkills->intersect($jobSkills)->count();
        $ratio = $overlap / max($jobSkills->count(), 1);

        return (int) round($ratio * $max);
    }

    private function rateScore(Talent $talent, Job $job, int $max): int
    {
        if (! $talent->rate_min && ! $talent->rate_max) {
            return 0;
        }

        if (! $job->salary_min && ! $job->salary_max) {
            return (int) round($max * 0.5);
        }

        $tMin = $talent->rate_min ?? 0;
        $tMax = $talent->rate_max ?? $tMin;
        $jMin = $job->salary_min ?? 0;
        $jMax = $job->salary_max ?? $jMin;

        if ($tMax < $jMin || $tMin > $jMax) {
            return 0;
        }

        return $max;
    }
}
