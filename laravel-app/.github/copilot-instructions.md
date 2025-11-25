# 🧑‍💻 Copilot Instructions for MHQ Decision Support System

## Project Architecture
- **Laravel 12.x** backend, Bootstrap 5 frontend
- Core modules: Participants (`Peserta`), Judges (`Juri`), Criteria (`Kriteria`), Assessments (`Penilaian`)
- Algorithms: **SMART** (multi-criteria weighted scoring) and **Borda Count** (group decision aggregation)
- Key directories:
  - `app/Http/Controllers/`: Business logic, CRUD, and algorithm orchestration
  - `app/Models/`: Eloquent models for all main entities
  - `app/Services/`: Algorithm implementations (`SMARTService.php`, `BordaService.php`)
  - `resources/views/`: Blade templates for UI
  - `routes/web.php`: Main HTTP routes
  - `database/migrations/`, `database/seeders/`: Schema and initial data

## Data Flow & Patterns
- **Assessment workflow:** Judges score participants on 5 criteria; scores are normalized and weighted (SMART), then aggregated (Borda)
- **Combined results:** Final ranking = 50% SMART + 50% Borda
- **CRUD pattern:** Standard Laravel resource controllers for all entities
- **Validation:** Real-time validation in forms, backend validation in controllers
- **No authentication yet:** All routes are open; see `PROJECT_TODO.md` for planned security features

## Developer Workflows
- **Install:** `composer install`, copy `.env.example` to `.env`, `php artisan key:generate`
- **Database:** Default is SQLite (`DB_CONNECTION=sqlite`), can switch to MySQL in `.env`
- **Migrate & Seed:** `php artisan migrate:fresh --seed`
- **Run server:** `php artisan serve`
- **Clear caches:** `php artisan cache:clear`, `php artisan view:clear`, `php artisan route:clear`
- **Testing:** Use `phpunit` for unit/feature tests in `tests/`

## Conventions & Integration
- **Algorithm services:** All decision logic in `app/Services/`
- **Blade views:** UI logic in `resources/views/`, grouped by module
- **AJAX:** Used for real-time validation and dynamic forms
- **Database access:** Use Eloquent models; see `app/Models/`
- **Routes:** All main routes in `routes/web.php`
- **Project tasks:** See `PROJECT_TODO.md` for current priorities and missing features

## Examples
- **SMART calculation:** See `SMARTService.php` for normalization and weighted scoring
- **Borda aggregation:** See `BordaService.php` for ranking logic
- **Assessment CRUD:** See `PenilaianController.php` for input, validation, and result calculation

## Key Files
- `app/Services/SMARTService.php`, `app/Services/BordaService.php`
- `app/Http/Controllers/PenilaianController.php`
- `app/Models/Peserta.php`, `Juri.php`, `Kriteria.php`, `Penilaian.php`
- `resources/views/penilaian/`, `hasil/`, `dashboard/`
- `routes/web.php`
- `PROJECT_TODO.md`, `README.md`

## Guidance
- Follow existing CRUD and service patterns for new features
- Reference `PROJECT_TODO.md` for current development priorities
- Document any new conventions in this file for future agents

---
*Update this file as the codebase evolves. For questions, see `README.md` and `PROJECT_TODO.md`.*
