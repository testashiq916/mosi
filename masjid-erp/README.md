# Masjid ERP — Member Module

This is `masjid.zip`, unzipped and assembled into an actual runnable Laravel
module, instead of 36 loosely-named `deepseek_*` export files.

## What was in the zip

The archive was a raw export from a DeepSeek chat: 36 files with names like
`deepseek_sql_20260728_f189e9.sql`, no folder structure, several exact
duplicates (re-downloads of the same answer), and one truncated duplicate.
Sorted out, it contained three different kinds of content:

1. **Real, working PHP** (4 files) — three Laravel controllers for a member
   portal (dashboard, donations, profile) and one routes snippet for seven
   *other* modules. This is the only actual application code in the zip.
2. **A real database schema** (27 `.sql` files, 18 unique after dedup) —
   `CREATE TABLE` statements for 45 tables covering accounting,
   membership, donations, mahalla (resident) management, marriage/NOC,
   utensil & building rental, staff, fixed assets, meetings, and
   notifications, plus six worked double-entry bookkeeping examples.
3. **Aspirational directory listings** (3 `.txt` files, ~2,200 lines) — `tree`
   -style diagrams of a hypothetical full "masjid-erp" SaaS (thousands of
   files: mobile app, multi-tenant SaaS core, Docker, CI/CD, docs site...).
   None of that was ever generated — these are plans, not code. They're kept
   under `docs/source/` for reference but nothing in this module tries to
   build out that whole tree.

The original files (deduplicated) are preserved under `database/schema/`
(SQL) and `docs/source/` (PHP + the three tree diagrams) for provenance.

## What this module is

`app/`, `database/`, and `routes/` here are a working slice of a Laravel 11
app: the real Member module (controllers, views, routes) wired up against
Eloquent models and migrations generated from the *entire* provided schema,
not just the Member tables — so the other 40+ tables (accounting, donations,
mahalla, marriage, rentals, staff, assets, meetings, notifications) are
present as migrations + models too, ready for their own controllers to be
built on top the same way.

It is **not** a full bootable Laravel installation — there's no
`public/index.php`, `bootstrap/app.php`, or `config/*.php`. Drop this into a
fresh `laravel new masjid-erp` (or `composer create-project laravel/laravel`)
and merge in `app/`, `database/`, `routes/`, `resources/views/`, and
`composer.json`'s `require` block.

```
masjid-erp/
├── app/
│   ├── Http/Controllers/
│   │   ├── Controller.php                  # base controller (missing from the dump)
│   │   └── Member/                         # the 3 real controllers, bug-fixed (see below)
│   ├── Mail/DonationConfirmation.php       # stub — referenced by the donation controller, not in the dump
│   ├── Models/                             # 56 Eloquent models, one per table
│   └── Services/Accounting/
│       └── AccountingService.php           # createDonationEntries() — referenced but not provided;
│                                            # implemented from the worked receipt/donation example
├── database/
│   ├── migrations/                         # 56 migrations, dependency-ordered, raw SQL from the dump
│   ├── schema/                             # the original CREATE TABLE files, deduplicated
│   └── seeders/ChartOfAccountsSeeder.php   # the default chart-of-accounts INSERTs from the dump
├── docs/source/                            # original PHP + architecture .txt files, untouched
├── resources/views/member/                 # minimal Blade views for every view the controllers return
└── routes/
    ├── web.php, member.php                 # wired up and working
    └── api.php                             # the "additional modules" routes file, as found — see below
```

## How the migrations work

Rather than hand-translating 45+ `CREATE TABLE` statements (with MySQL
`ENUM`, `JSON`, composite foreign keys, etc.) into Laravel's `Schema::create`
builder — a process with plenty of room to introduce subtle type mismatches —
each migration runs the **original SQL verbatim** via `DB::unprepared()`.
This keeps the schema byte-for-byte what was actually generated, while still
giving you ordinary Laravel migrations you can run with `php artisan
migrate`. Migrations are numbered `0001`–`0056` in dependency order (a table
never migrates before something it has a foreign key to).

## Gaps that had to be filled in

The dump's tables reference several tables via `FOREIGN KEY` that were
**never defined anywhere in the zip**: `users`, `masjids`, `committees`,
`donors`, `donation_categories`, `students`, `waqf_properties`,
`waqf_tenants`. Without them the schema doesn't migrate at all. Minimal,
clearly-marked stub tables were added for these (see
`database/schema/00_inferred_core_tables.sql` and the migration doc-comments
that say "table not present in the source dump"). Two more —
`madrassa_classes` and `events`/`event_registrations` — were added for the
same reason: `MemberDashboardController` calls
`$student->currentClass->fee_amount` and queries `EventRegistration`, but no
schema for either existed in the dump.

The three real controllers also had two actual bugs, fixed here:

- None of them imported `App\Http\Controllers\Controller`. Since their
  namespace is `App\Http\Controllers\Member`, `extends Controller` would
  have resolved to the nonexistent `App\Http\Controllers\Member\Controller`
  and fatally errored on every request.
- `MemberDonationController` never imported `App\Models\Member`, despite
  calling `Member::where(...)` in five different methods.

`MemberDonationController::sendDonationConfirmation()` also references
`\App\Mail\DonationConfirmation` and the `barryvdh/laravel-dompdf` `\PDF`
facade — neither ships in the dump. A minimal `Mailable` was added at
`app/Mail/DonationConfirmation.php`; the PDF facade just needs
`composer require barryvdh/laravel-dompdf` in a real install.

## Known gaps inherited from the source material (not fixed, just flagged)

- **`donor_id` is overloaded.** `donations.donor_id` and `receipts.donor_id`
  have a `FOREIGN KEY ... REFERENCES donors(id)` in the schema, but
  `MemberDonationController::store()` writes `$member->id` into that column.
  `members` and `donors` are different tables, so as written this will throw
  an FK violation the first time a member's id doesn't happen to also exist
  as a donor id. Not changed here since it's the actual logic that was
  generated — needs a real decision (sync a `Donor` row per `Member`, or
  drop the FK) before this ships.
- `routes/api.php` is kept exactly as found. It routes to
  `MahallaController`, `MarriageController`, `UtensilController`,
  `MeetingController`, `AssetController`, `StaffController`, and
  `NotificationController` — none of which exist in the dump, only their
  database schema does. The route list registers fine; hitting any of these
  endpoints will throw a class-not-found error until those controllers are
  written using the models already generated for those tables.
- Views are minimal functional Blade templates (forms/tables wired to the
  right routes and model fields), not styled UI — the dump contained no
  view files at all.

## Setup (once dropped into a real Laravel install)

```bash
composer require laravel/sanctum barryvdh/laravel-dompdf
cp .env.example .env   # then fill in DB credentials
php artisan key:generate
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\ChartOfAccountsSeeder
```
