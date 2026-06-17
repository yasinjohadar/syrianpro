# سجل التوظيف الموحّد (Hire Records) — Design Spec

**Date:** 2026-06-17  
**Status:** Approved for implementation  
**Priority:** Phase 1 — build before recommendations and analytics

## Goal

Create a single `hires` table that records every successful placement on the platform, regardless of whether it came from a job application, a public hiring request, or a company pitch. This unifies the marketplace closure loop and enables dashboards, admin reports, and future reviews.

## Problem

Today, "hired" outcomes live in three disconnected places:

| Source | Signal today | Gap |
|--------|--------------|-----|
| Job application | `job_applications.status = accepted` | No link to company FK history |
| Public hiring request | `talent_hiring_requests.status = hired` (talent confirms) | No company on record |
| Pitch | `talent_hiring_requests.status = hired` (company confirms) | No job reference |

Admin cannot answer: "How many talents were hired this month?" or "Which companies hired through pitches vs applications?"

## Data Model

### Table: `hires`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint PK | |
| `talent_id` | FK → talents | required |
| `company_id` | FK → companies | nullable (null for public-request hires without a company) |
| `job_listing_id` | FK → job_listings | nullable |
| `source` | enum | `application`, `public_request`, `pitch` |
| `source_id` | bigint | ID of `job_applications` or `talent_hiring_requests` |
| `hired_at` | timestamp | defaults to `now()` |
| `notes` | text nullable | optional admin/company note |
| `created_at`, `updated_at` | timestamps | |

**Indexes:** `(talent_id)`, `(company_id)`, `(source, source_id)` unique to prevent duplicate hire records from the same event.

### Model: `App\Models\Hire`

Relationships: `talent()`, `company()`, `job()`, polymorphic-style accessors for source record.

Constants:

```php
public const SOURCE_APPLICATION = 'application';
public const SOURCE_PUBLIC_REQUEST = 'public_request';
public const SOURCE_PITCH = 'pitch';
```

## Service: `HireRecordService`

Single entry point — `recordHire(array $payload): Hire`

Called from:

1. **`Company\JobApplicationController::update`** — when status changes to `accepted`
2. **`Admin\JobApplicationController::updateStatus`** — same
3. **`TalentHiringRequestService::markAsHiredByCompany`** — source `pitch`, `company_id` from request
4. **`TalentHiringRequestService::markAsHiredByTalent`** — source `public_request`, `company_id` null

**Rules:**

- Idempotent: if a hire already exists for `(source, source_id)`, return existing record (no duplicate)
- Set `job_listing_id` from application when source is `application`
- Set `company_id` from job's `company_id` when available
- `hired_at` = event timestamp

## UI

### Talent panel (`/talent`)

- New card on dashboard: **«سجل التوظيف»** — count + latest hire
- New page or section: list of hires (company name, role/job title, date, source label)

### Company panel (`/company`)

- Dashboard card: **«من وظّفنا»**
- Page listing hires with talent name, source, date, link to talent profile

### Admin (`/admin`)

- Index under job applications or new menu item **«سجل التوظيف»**
- Filters: company, talent, source, date range
- Export CSV (optional v2)

## Notifications

No new notification type in v1. Existing `TalentHiredNotification` and `JobApplicationStatusChangedNotification` remain sufficient.

## Migration / Backfill

Optional one-time command `hires:backfill`:

- All `job_applications` with `status = accepted` → hire records
- All `talent_hiring_requests` with `status = hired` → hire records

Run once after deploy; safe to re-run (idempotent).

## Out of Scope

- Post-hire reviews/ratings
- Salary or contract details on hire record
- Undo/revert hire (admin can delete record manually in v1)

## Testing

- Feature: accepting application creates one hire row
- Feature: mark pitch hired creates hire with `company_id`
- Feature: mark public request hired creates hire with null `company_id`
- Unit: duplicate call does not create second row

## Files (implementation)

- `database/migrations/..._create_hires_table.php`
- `app/Models/Hire.php`
- `app/Services/HireRecordService.php`
- Hooks in `JobApplicationController` (company + admin), `TalentHiringRequestService`
- Views: talent dashboard partial, company dashboard partial, admin index
