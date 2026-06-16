# Admin Dashboard Home — Design Spec

**Date:** 2026-06-12  
**Status:** Approved for implementation

## Goal

Replace the placeholder admin dashboard with an attractive home page: personalized welcome, four gradient stat cards with real data, and a quick-shortcuts grid — consistent with the existing `admin-ui` design system (RTL, dark mode).

## Stats Cards (real data)

| Card | Variant | Primary | Hint |
|------|---------|---------|------|
| إجمالي المستخدمين | purple | `User::count()` | online users (sessions, last 5 min) |
| مقالات المدونة | green | `BlogPost::published()->count()` | total posts |
| رسائل التواصل | cyan | `ContactMessage::count()` | unread count |
| مشتركو النشرة | orange | `NewsletterSubscriber::active()->count()` | subscribed today |

Consultation requests appear in shortcuts with unread badge, not in stat cards.

## Welcome Banner

- Text: `مرحباً {name}، أهلاً بعودتك!`
- Subtitle: `أنت مسجل الدخول كـ {role}` (first role or "مدير")

## Quick Shortcuts

12 shortcuts from sidebar, defined in `config/admin-dashboard.php`. Filtered by optional Spatie permission. Badges for unread contact messages and consultation requests.

## Architecture

- `DashboardController@index` — aggregates stats and resolves shortcuts
- Partials: `dashboard-welcome`, `shortcut-card`, reuse `stat-card-gradient`
- CSS additions in `admin-ui.css`: `.dashboard-welcome`, `.shortcut-grid`, `.shortcut-card`

## Out of Scope

Charts, activity feed, LMS placeholder stats, query caching.

## Verification

- `/admin` shows welcome, 4 stats, shortcuts grid
- Dark mode readable
- Permission-gated shortcuts hidden when unauthorized
- Links match sidebar routes
