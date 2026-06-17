# توصيات الأدمن للتقنيين (Curated Recommendations) — Design Spec

**Date:** 2026-06-17  
**Status:** Approved for implementation  
**Priority:** Phase 2A — first recommendation layer (manual, high quality)

## Goal

Allow admins to explicitly recommend talents to increase visibility and hiring opportunities. Distinct from `is_featured` (static flag): recommendations have a **reason**, **scope**, **expiry**, and trigger **notifications**.

## Problem

`is_featured` on [`Talent`](app/Models/Talent.php) is binary and permanent. Admins cannot:

- Recommend a talent for a specific context (homepage vs a job category)
- Explain why they are recommended
- Time-limit a campaign
- Notify the talent they were highlighted

## Data Model

### Table: `talent_recommendations`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint PK | |
| `talent_id` | FK → talents | required |
| `recommended_by` | FK → users | admin user |
| `reason` | string(500) | public short text, e.g. "خبير Laravel بخبرة 5 سنوات" |
| `scope` | enum | `homepage`, `talents_page`, `specialty`, `job` |
| `scope_id` | bigint nullable | `tech_specialty_id` or `job_listing_id` when scope needs it |
| `priority` | unsigned tinyint | default 0; higher = shown first |
| `starts_at` | timestamp nullable | default now |
| `expires_at` | timestamp nullable | null = no expiry |
| `is_active` | boolean | default true |
| `created_at`, `updated_at` | timestamps | |

**Indexes:** `(scope, scope_id, is_active)`, `(talent_id, is_active)`

### Model: `App\Models\TalentRecommendation`

Scopes: `active()`, `forScope($scope, $scopeId = null)`, `ordered()`.

Active = `is_active` AND (`starts_at` null or past) AND (`expires_at` null or future).

## Admin UI

### Entry points

1. **Talents list** ([`admin/pages/talents`](resources/views/admin/pages/talents)) — action «أوصِ به»
2. **Talent show** — form: reason, scope, specialty/job picker, expiry, priority
3. **New page:** `/admin/talent-recommendations` — CRUD index with filters

### Permissions

Reuse existing talent admin permissions or add `talent-recommendation-manage` via Spatie.

### Validation

- `reason` required, max 500 chars
- `scope` required; `scope_id` required when scope is `specialty` or `job`
- Cannot recommend inactive talent
- Max 20 active `homepage` recommendations (configurable)

## Public / Frontend Display

### Homepage ([`frontend/pages/index`](resources/views/frontend/pages/index.blade.php))

New section **«موصى به من تك سوريا»** below or merged with featured talents:

- Query: `TalentRecommendation::active()->forScope('homepage')->with('talent')->ordered()->limit(6)`
- Card shows talent + admin `reason` badge

### Talents page

Optional filter chip «موصى به» — talents with any active recommendation.

### Job detail page

When `scope = job` and `scope_id` matches current job: sidebar «مرشح موصى به» block.

## Company panel (read-only v1)

No company actions. Companies see recommended badge on talent cards in pool (optional).

## Notifications

New class: `TalentRecommendedNotification`

- Sent to talent user when recommendation created (active)
- Payload: reason, scope label, link to public profile
- Channel: database only (consistent with marketplace notifications)

## Service: `TalentRecommendationService`

- `create(User $admin, Talent $talent, array $data): TalentRecommendation`
- `deactivate(TalentRecommendation $rec): void`
- `activeForHomepage(): Collection`
- Enforces max active per scope

## Relationship to `is_featured`

Keep both:

- `is_featured` — long-term editorial pick (seeder/demo)
- `talent_recommendations` — campaign-style, reasoned, expiring

Admin UI copy clarifies the difference.

## Out of Scope

- Recommending jobs to talents (separate feature / job alerts)
- Company-created recommendations (see company-invites spec)
- AI-generated reasons

## Testing

- Admin can create homepage recommendation
- Expired recommendation not shown on homepage
- Talent receives notification on create
- Max homepage limit enforced

## Files (implementation)

- Migration, model, service
- `Admin\TalentRecommendationController`
- Views: admin CRUD, homepage partial, optional talent-card badge
- `TalentRecommendedNotification`
- Routes in [`routes/admin.php`](routes/admin.php)
