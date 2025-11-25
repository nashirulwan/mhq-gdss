# Changelog

All notable changes to the Sistem Pendukung Keputusan Tahfidz (SPK Tahfidz) will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2025-11-25

### Added
- **User Authentication System**
  - Session-based login/logout functionality
  - Custom authentication middleware
  - Protected route system
  - Demo user accounts for testing

### Security
- **CSRF Protection** on all authentication forms
- **Session Management** with secure token regeneration
- **Input Validation** on login credentials
- **Route Protection** for authenticated areas

### Authentication Features
- **Login Page** (`/login`) with responsive design
- **Session Storage** for user authentication state
- **Custom AuthController** for login/logout logic
- **CheckUserSession Middleware** for route protection
- **RedirectIfAuthenticated Middleware** for guest users

### User Interface
- **Auth Layout** with beautiful gradient design
- **Indonesian Language** interface
- **Demo Credentials Display** for easy testing
- **Bootstrap Integration** for responsive design
- **Vite Asset Pipeline** for optimized frontend assets

### Database
- **MySQL Database Integration**
- **Demo Data Seeding**:
  - 5 Peserta (participants) with complete profiles
  - 3 Juri (judges) with expertise areas
  - 4 Kriteria (evaluation criteria) for SPK calculations
- **Migration System** for database schema management

### Enhanced Navigation
- **User Dropdown Menu** in navigation bar
- **User Information Display** (name, email, role)
- **Logout Buttons** in both navigation bar and sidebar
- **Role-based Visual Indicators**

### Demo Accounts
- **Admin**: `admin@tahfidz.com` / `password123`
- **Juri**: `juri@tahfidz.com` / `password123`
- **Peserta**: `peserta@tahfidz.com` / `password123`

### Technical
- **File-based Sessions** (no database dependency for auth)
- **Custom Middleware Aliases** in bootstrap/app.php
- **Route Groups** for guest and authenticated users
- **Asset Building** with npm/Vite

### Configuration
- **Environment Setup** for MySQL database
- **Session Configuration** for secure authentication
- **Asset Compilation** for production-ready frontend

## [1.0.0] - 2024-11-24

### Added
- **Core SPK Functionality**
  - Peserta (participant) management system
  - Juri (judge) management system
  - Kriteria (evaluation criteria) management
  - Penilaian (evaluation) system
  - SMART Method calculation engine
  - Borda Method calculation engine
  - Dashboard with statistics
  - Results analysis and ranking

### Features
- **Multi-criteria Decision Support**
  - Weighted evaluation criteria
  - Judge scoring system
  - Participant ranking algorithms
  - Comprehensive reporting

### Database Schema
- **pesertas table** - Participant information
- **juris table** - Judge profiles
- **kriterias table** - Evaluation criteria
- **penilaians table** - Evaluation scores
- **users table** - User authentication

### User Interface
- **Responsive Design** with Bootstrap 5
- **Data Tables** with DataTables integration
- **Form Validation** and user feedback
- **Dashboard Widgets** for statistics

### Algorithms
- **SMART (Simple Multi-Attribute Rating Technique)**
- **Borda Count Method**
- **Combined Ranking System**
- **Weight-based Scoring**

---

## Version History

### Development Notes

#### Authentication System Architecture
- **Session-based**: Uses Laravel sessions instead of database for user state
- **Demo Mode**: Pre-configured user accounts for testing without registration
- **Custom Middleware**: Route protection without Laravel's built-in Auth system
- **Security Focus**: CSRF protection, session regeneration, secure logout

#### Database Integration
- **MySQL**: Production-ready database configuration
- **Migrations**: Complete database schema with versioning
- **Seeders**: Demo data for immediate system testing
- **Relationships**: Proper Eloquent model relationships

#### Frontend Assets
- **Vite**: Modern asset build pipeline
- **Bootstrap 5**: Responsive UI framework
- **Custom CSS**: Tailored styling for SPK application
- **Icons**: Bootstrap Icons for visual enhancement

#### Route Protection
- **Guest Routes**: Login page accessible only to non-authenticated users
- **Authenticated Routes**: All main features require login
- **Redirect Logic**: Proper redirects based on authentication status

---

## Future Plans

### Version 1.1.0 (Planned)
- **User Registration System**
- **Email Verification**
- **Password Reset Functionality**
- **Role-based Access Control**
- **Audit Trail System**

### Version 1.2.0 (Planned)
- **Mobile Application API**
- **Real-time Notifications**
- **Advanced Reporting**
- **Data Export Features**
- **Multi-language Support**

---

## Migration Guide

### From 1.0.0 to 1.0.1
1. Run database migrations: `php artisan migrate`
2. Seed demo data: `php artisan db:seed --class=PesertaSeeder`
3. Build frontend assets: `npm run build`
4. Update environment variables for database connection

### Breaking Changes
- Authentication system now required for main application access
- Database migration required for new authentication features
- New middleware configuration in bootstrap/app.php

---

## Security Considerations

### Authentication Security
- CSRF tokens enabled on all forms
- Session regeneration on login/logout
- Secure session configuration
- Input validation and sanitization

### Database Security
- Prepared statements for all queries
- Migration system for schema management
- Proper user access controls

### Application Security
- Route protection implemented
- File upload restrictions
- Input validation throughout application

---

For full documentation and usage instructions, see the project README.