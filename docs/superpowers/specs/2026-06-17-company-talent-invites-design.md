# دعوات الشركة وقوائم المرشحين (Company Talent Invites) — Design Spec

**Date:** 2026-06-17  
**Status:** Approved for implementation  
**Priority:** Phase 2B — proactive company actions on talent pool

## Goal

Let companies take initiative beyond responding to pitches: invite a talent to apply for a job, maintain a private shortlist, and leave internal fit notes. Increases placement rate by connecting [`Company\TalentController`](app/Http/Controllers/Company/TalentController.php) to the job application flow.

## Problem

Companies can only react to:

- Job applications on their listings
- Public hiring requests (respond interested/declined)
- Pitches directed at them

They cannot proactively say: "We want **you** for **this role**" with a tracked status.

## Data Model

### Table: `company_talent_actions`

Single table for invite, shortlist, and note types (simpler than three tables).

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint PK | |
| `company_id` | FK → companies | required |
| `talent_id` | FK → talents | required |
| `job_listing_id` | FK nullable | required for `invite` type |
| `user_id` | FK → users | company user who created |
| `type` | enum | `invite`, `shortlist`, `note` |
| `message` | text nullable | invite message or note body |
| `fit_rating` | tinyint nullable | 1–5 for notes/shortlist |
| `status` | enum | see below |
| `viewed_at` | timestamp nullable | talent opened notification/link |
| `responded_at` | timestamp nullable | |
| `created_at`, `updated_at` | timestamps | |

**Status values by type:**

| Type | Statuses |
|------|----------|
| `invite` | `pending`, `viewed`, `applied`, `declined`, `expired` |
| `shortlist` | `active`, `removed` |
| `note` | `active` (private, no status workflow) |

**Unique constraints:**

- `invite`: one pending invite per `(company_id, talent_id, job_listing_id)`
- `shortlist`: one active row per `(company_id, talent_id)`

### Model: `App\Models\CompanyTalentAction`

## Service: `CompanyTalentActionService`

### `invite(Company, User, Talent, Job, ?string $message)`

- Validates company owns job via `job.company_id`
- Rate limit: max 10 invites per company per 7 days (config `marketplace.invite_weekly_limit`)
- Creates row `type=invite`, `status=pending`
- Notifies talent: `JobInviteNotification` with apply URL

### `addToShortlist(Company, User, Talent, ?int $fitRating)`

- Upsert shortlist row
- No talent notification in v1 (private list)

### `addNote(Company, User, Talent, string $message, ?int $fitRating)`

- Company-private; visible only in company talent show page

### `markInviteApplied(CompanyTalentAction $action)`

- Called when talent applies to linked job (hook in `Frontend\JobApplicationController`)
- Auto-update invite status to `applied`

## Company UI

### Talent show ([`company/pages/talents/show`](resources/views/company/pages/talents/show.blade.php))

Action bar:

- **دعوة للتقديم** — modal: pick active job + optional message
- **إضافة للقائمة المختصرة** — toggle star
- **ملاحظة داخلية** — small form (not visible to talent)

### New page: `/company/shortlist`

Table of shortlisted talents with filters, link to profile, remove action.

### Job show / applications

Badge on applicant if they arrived via invite.

## Talent UI

### Notifications

`JobInviteNotification`: "{company} تدعوك للتقديم على {job title}" → link to job page with `?invite={action_id}`

### Applications

When applying from invite link, pre-fill context; on success mark invite `applied`.

Optional decline button on notification deep-link: `POST /talent/invites/{action}/decline`

## Admin (read-only v1)

Admin index of invites for moderation/spam reports — no create.

## Anti-spam

- Weekly invite limit per company
- Cannot invite same talent to same job twice while pending
- Inactive jobs cannot be used for invites

## Integration with Hire Records

When invite → application → `accepted`, hire record source remains `application` with optional `metadata` or note linking `company_talent_action_id` (future column on `hires` — optional v2).

## Out of Scope

- Bulk invite (CSV)
- In-app messaging thread
- Shortlist shared between company team members with roles (all company users share one list)

## Testing

- Company invites talent → notification sent
- Rate limit blocks 11th invite in week
- Applying to job marks invite as applied
- Shortlist toggle idempotent
- Notes not visible to talent API

## Files (implementation)

- Migration, model, service
- `Company\TalentActionController` or extend `TalentController`
- `Talent\InviteController` for decline
- `JobInviteNotification`
- Views: modals on talent show, shortlist index
- Routes in [`routes/company.php`](routes/company.php), [`routes/talent.php`](routes/talent.php)
