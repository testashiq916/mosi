# Masjid ERP

This is `masjid.zip`, unzipped and assembled into an actual runnable Laravel
app, instead of 36 loosely-named `deepseek_*` export files.

## What was in the zip

The archive was a raw export from a DeepSeek chat: 36 files with names like
`deepseek_sql_20260728_f189e9.sql`, no folder structure, several exact
duplicates (re-downloads of the same answer), and one truncated duplicate.
Sorted out, it contained three different kinds of content:

1. **Real, working PHP** (4 files) — three Laravel controllers for a member
   portal (dashboard, donations, profile) and one routes snippet naming
   seven *other* modules. This was the only actual application code in the
   zip — everything else in those seven modules (Mahalla, Marriage, Utensil
   rental, Meetings, Assets, Staff, Notifications) was schema only, no
   controllers, until this module filled them in (see below).
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
app covering **every table in the source dump**, with two layers of
controllers on top:

- **Member portal** (`app/Http/Controllers/Member/`, `routes/member.php`) —
  the three controllers that were actually in the zip: dashboard, donations
  (with double-entry accounting posted on every donation), profile. Scoped
  by the logged-in `Member` record.
- **Admin API** (`app/Http/Controllers/API/V1/`, `routes/api.php`) — seven
  controllers implementing the routes the dump only sketched: Mahalla
  residents, Marriage/NOC, Utensil rental, Meetings, Fixed assets, Staff
  (incl. payroll + attendance), Notifications. None of these existed as code
  in the zip — only the route list and the database schema did. Scoped by
  company/masjid via `AdminApiController`, auth via Sanctum.

Every one of the schema's 56 tables (45 from the dump + 11 inferred, see
below) has a migration and an Eloquent model, so the admin controllers above
were built directly against real relationships rather than raw queries.

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
│   │   ├── Member/                         # the 3 real controllers, bug-fixed (see below)
│   │   └── API/V1/                         # 7 admin controllers, new — see "Admin API modules"
│   ├── Mail/DonationConfirmation.php       # stub — referenced by the donation controller, not in the dump
│   ├── Models/                             # 56 Eloquent models, one per table, with relationships
│   └── Services/Accounting/
│       └── AccountingService.php           # createDonationEntries() — referenced but not provided;
│                                            # implemented from the worked receipt/donation example
├── database/
│   ├── migrations/                         # 56 migrations, dependency-ordered, raw SQL from the dump
│   ├── schema/                             # the original CREATE TABLE files, deduplicated
│   └── seeders/ChartOfAccountsSeeder.php   # the default chart-of-accounts INSERTs from the dump
├── docs/source/                            # original PHP + architecture .txt files, untouched
├── resources/views/member/                 # minimal Blade views for every view the Member controllers return
└── routes/
    ├── web.php, member.php                 # Member portal — wired up and working
    └── api.php                             # Admin API — the dump's route list, now wired to real controllers
```

## Admin API modules

All seven live under `app/Http/Controllers/API/V1/<Domain>/` and share
`AdminApiController` for company/masjid scoping (`currentCompanyId()`,
`currentMasjidId()`, `paginate()`). Every route in `routes/api.php` resolves
to a real method — verified mechanically, not just by inspection.

| Controller | Tables | Notes |
|---|---|---|
| `MahallaController` | `mahalla_residents`, `resident_dependents` | CRUD + `addDependent`; soft-deactivates on destroy |
| `MarriageController` | `marriage_registrations`, `marriage_noc_requests` | register → approve workflow; separate NOC request/lookup |
| `UtensilController` | `utensils`, `utensil_rentals`, `rental_items` | `rent()`/`return()` keep `available_quantity`/`damaged_quantity` in sync inside a DB transaction; flat per-day late fee (not specified in the schema, so a placeholder) |
| `MeetingController` | `meetings`, `meeting_actions` | CRUD + action items with their own complete/assign lifecycle |
| `AssetController` | `fixed_assets`, `asset_maintenance` | CRUD + `scheduleMaintenance()` flips the asset to `in_maintenance` |
| `StaffController` | `staff`, `staff_payroll`, `staff_attendance` | `processPayroll()` computes earnings/deductions/net from the staff record + request input; `markAttendance()` upserts on the `(staff_id, attendance_date)` unique key and computes `working_hours` from check-in/out |
| `NotificationController` | `notification_types`, `notification_queues` | `send()` only queues (`status = pending`) — no real email/SMS/push dispatcher, matching the dump's own `sendSMS()` no-op in `MemberDonationController` |

## How the migrations work

Rather than hand-translating 45+ `CREATE TABLE` statements (with MySQL
`ENUM`, `JSON`, composite foreign keys, etc.) into Laravel's `Schema::create`
builder — a process with plenty of room to introduce subtle type mismatches —
each migration runs the **original SQL verbatim** via `DB::unprepared()`.
This keeps the schema byte-for-byte what was actually generated, while still
giving you ordinary Laravel migrations you can run with `php artisan
migrate`. Migrations are numbered `0001`–`0056` in dependency order (a table
never migrates before something it has a foreign key to) — verified with a
script that walks every migration's `REFERENCES` clauses.

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

`users` also picked up a `masjid_id` column (not in the original dump) so
admin/staff accounts can be scoped to one masjid the same way every other
table in the schema is — used by `AdminApiController::currentMasjidId()`.

The three real Member controllers also had two actual bugs, fixed here:

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
- The utensil rental late fee (`UtensilController::return()`) is a flat
  amount per day late — the schema has a `late_fee` column but never
  specified how it should be calculated, so this is a placeholder pending a
  real business rule.
- Views are minimal functional Blade templates (forms/tables wired to the
  right routes and model fields), not styled UI — the dump contained no
  view files at all. The Admin API has no views at all (it's JSON-only, as
  the original route list implies with `auth:sanctum`).
- Still schema-and-model-only, with no controllers yet: building/Waqf
  rental (`rental_properties`, `rental_agreements`, `waqf_properties`,
  `waqf_tenants` — the dump never sketched routes for this one), financial
  reporting (`financial_statements`, `audit_logs`), and admin-side
  accounting (voucher/daybook CRUD and reporting beyond the donation
  auto-posting `AccountingService` already does).

## Setup (once dropped into a real Laravel install)

```bash
composer require laravel/sanctum barryvdh/laravel-dompdf
cp .env.example .env   # then fill in DB credentials
php artisan key:generate
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\ChartOfAccountsSeeder
```
