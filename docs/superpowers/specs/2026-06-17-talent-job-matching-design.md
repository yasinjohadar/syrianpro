# المطابقة التلقائية Talent–Job Matching — Design Spec

**Date:** 2026-06-17  
**Status:** Approved for implementation  
**Priority:** Phase 2C — after admin recommendations and company invites; requires complete profiles

## Goal

Score how well a talent matches a job using existing structured data (no ML in v1). Surface "best candidates for this job" to companies and "jobs for you" to talents to increase applications and hires.

## Problem

Companies post jobs and manually search the talent pool. Talents browse all jobs. Neither side gets ranked suggestions based on skills, specialty, remote preference, or availability signals (`is_open_to_work`, active hiring request).

## Service: `TalentJobMatchingService`

### Public API

```php
public function score(Talent $talent, Job $job): int; // 0–100

public function scoreBreakdown(Talent $talent, Job $job): array; // factors for UI tooltips

public function topTalentsForJob(Job $job, int $limit = 10): Collection;

public function topJobsForTalent(Talent $talent, int $limit = 10): Collection;
```

### Scoring factors (v1)

| Factor | Max points | Logic |
|--------|------------|-------|
| Skills overlap | 40 | `% of job.skills found in talent.skills` × 40 |
| Specialty match | 20 | 20 if `tech_specialty_id` equal |
| Open to work | 15 | 15 if `is_open_to_work` or active public hiring request |
| Remote fit | 10 | 10 if job `remote_type` compatible with `talent.is_remote` |
| Rate/salary overlap | 10 | 10 if ranges overlap; 5 if partial; 0 if no data |
| Syria-friendly bonus | 5 | 5 if both job and talent/profile syria-friendly flags |

**Minimum threshold:** only show matches with `score >= 40` in UI lists.

**Normalization:** skills compared case-insensitive, trimmed; support Arabic/English duplicates via lowercase.

### Performance

- No persistent `match_scores` table in v1 (compute on demand)
- Cache `topTalentsForJob` for 15 minutes per job ID when job published
- Eager-load talents with skills for batch scoring on job edit page

### v2 (out of scope)

- Nightly materialized table `talent_job_match_scores`
- ML re-ranking with AI module

## Company UI

### Job create/edit success

After publish: sidebar or modal **«أفضل المرشحين لهذه الوظيفة»** — top 10 with score %, link to profile, quick invite (requires company-invites spec).

### Job applications index

Column or badge: match % next to each applicant (helps prioritize review).

### Dashboard widget

«وظائفك تحتاج مرشحين» — jobs with &lt; 3 applications and top 3 suggested talents.

## Talent UI

### Dashboard card: **«وظائف تناسبك»**

Top 5 active jobs with score badge and apply CTA.

### `/talent/applications` or hiring-request page

Related jobs section based on active hiring request headline/skills.

## Admin UI

### Report: `/admin/matching-insights`

- Jobs with zero applications (last 30 days)
- Talents `open_to_work` with no company responses (last 30 days)
- Average match score of accepted applications (analytics hook for hire records)

Read-only; no admin override of scores in v1.

## Notifications (Phase 1.3 tie-in)

When new job published:

- For each talent with `score >= 60` and job alerts enabled (or always if `is_open_to_work`): queue `JobMatchNotification`

When new talent sets `open_to_work`:

- Notify companies with active jobs in same specialty where `score >= 60` — max 5 companies per talent event (avoid spam)

Uses existing [`NotificationController`](app/Http/Controllers/NotificationController.php) infrastructure.

## Profile completeness gate

Do not show match UI to talents below 60% profile completion (future `ProfileCompletenessService`). For v1, require at least: `title`, `skills` (≥3), `bio` (≥50 chars).

## Dependencies

- [`Job`](app/Models/Job.php) with `company_id`, `skills`, `tech_specialty_id`
- [`Talent`](app/Models/Talent.php) with `skills`, `is_open_to_work`
- Optional: company invites for "invite top match" CTA

## Out of Scope

- Semantic/NLP skill matching
- Learning from accept/reject history
- Matching companies to talents without a specific job

## Testing

Unit tests per factor:

- Full skills match → high score
- No skills → low score
- `topTalentsForJob` respects threshold and limit
- Case-insensitive skill "Laravel" vs "laravel"

Feature: company sees suggestions after creating job (seeded talents).

## Files (implementation)

- `app/Services/TalentJobMatchingService.php`
- `app/Services/ProfileCompletenessService.php` (minimal)
- `JobMatchNotification`
- View partials: company job suggestions, talent dashboard jobs
- Optional: `config/matching.php` for weights and thresholds
- Hook in `Company\JobController::store` to dispatch match notifications (queued job)

## Config defaults (`config/matching.php`)

```php
return [
    'min_display_score' => 40,
    'min_notify_score' => 60,
    'cache_ttl_minutes' => 15,
    'weights' => [
        'skills' => 40,
        'specialty' => 20,
        'open_to_work' => 15,
        'remote' => 10,
        'rate' => 10,
        'syria_friendly' => 5,
    ],
];
```
