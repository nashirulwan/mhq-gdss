# 🏆 Sistem Penilaian MHQ - Group Decision Support System

A comprehensive **Group Decision Support System (GDSS)** for evaluating Musabaqah Hifdzil Qur'an (MHQ) competitions using **SMART** and **Borda Count** methods.

## 🎯 Project Overview

This system provides objective scoring and ranking for MHQ competitions through multi-criteria evaluation and group decision aggregation, eliminating subjective bias in judgment.

### 📊 Features Implemented

- ✅ **SMART Algorithm** - Weighted scoring and normalization
- ✅ **Borda Count Method** - Group decision aggregation
- ✅ **Multi-criteria Assessment** - 5 evaluation criteria
- ✅ **Multi-juri Support** - Multiple independent judges
- ✅ **Complete CRUD** - Participants, Judges, Assessments
- ✅ **Responsive UI** - Bootstrap 5 mobile-friendly interface
- ✅ **Real-time Validation** - Input validation and AJAX interactions
- ✅ **User Authentication** - Secure login/logout with session management

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- SQLite (default) or MySQL
- Web server (Apache/Nginx)

### Installation

1. **Clone Repository**
```bash
git clone https://github.com/your-username/mhq-dss.git
cd mhq-dss/laravel-app
```

2. **Install Dependencies**
```bash
composer install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Setup**
```bash
# Run migrations and seeders
php artisan migrate:fresh --seed
```

5. **Build Frontend Assets**
```bash
npm install
npm run build
```

6. **Start Server**
```bash
php artisan serve
```

7. **Access Application**
```
http://localhost:8000
```

8. **Login with Demo Accounts**
- **Admin**: `admin@tahfidz.com` / `password123`
- **Juri**: `juri@tahfidz.com` / `password123`
- **Peserta**: `peserta@tahfidz.com` / `password123`

## 📋 Database Structure

### Core Tables
- **`pesertas`** - Participant information
- **`juris`** - Judge/evaluator data
- **`kriterias`** - Assessment criteria (5 criteria)
- **`penilaians`** - Assessment records and scores

### Assessment Criteria
1. **Tajwid** - Quranic recitation rules
2. **Kelancaran** - Fluency in recitation
3. **Fasohah** - Clarity and eloquence
4. **Adab** - Etiquette and demeanor
5. **Tartil** - Proper recitation technique

## 🔧 Configuration

### Database Options

**Option 1: SQLite (Default)**
```env
DB_CONNECTION=sqlite
# DB_DATABASE=null
```

**Option 2: MySQL/PHPMyAdmin**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mhqdss
DB_USERNAME=root
DB_PASSWORD=
```

### Database Access Tools
- **SQLite**: Use DB Browser for SQLite
- **MySQL**: Access via PHPMyAdmin at `http://localhost/phpmyadmin`

## 📱 Module Overview

### 1. Dashboard (`/`)
- System statistics and progress tracking
- Recent activity monitoring
- Quick navigation to all modules

### 2. Peserta Management (`/peserta`)
- **Index**: List all participants with statistics
- **Create**: Add new participants
- **Edit/Show**: Update and view participant details
- Auto-generated participant numbers

### 3. Penilaian System (`/penilaian`)
- **Create**: Advanced multi-criteria input form
- **Index**: Assessment listing with filtering
- **Edit/Show**: Update and view assessments
- Real-time validation and calculation

### 4. Hasil Analysis (`/hasil`)
- SMART and Borda calculation interface
- Results visualization dashboard
- Ranking and decision support

## 🎯 Algorithms

### SMART Method
1. **Normalization** - Convert scores to comparable scale
2. **Weighted Scoring** - Apply criteria weights
3. **Ranking** - Calculate final scores

### Borda Count Method
1. **Preference Ranking** - Each judge ranks participants
2. **Point Assignment** - Points based on rankings
3. **Aggregation** - Sum points across all judges

### Combined Results
- 50% SMART + 50% Borda for balanced decision making

## 🛠️ Development

### Key Directories
- `app/Http/Controllers/` - Business logic
- `app/Models/` - Database models
- `app/Services/` - Algorithm implementations
- `resources/views/` - Blade templates
- `database/migrations/` - Database schema
- `routes/web.php` - Application routes

### Available Commands
```bash
# Clear caches
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Database operations
php artisan migrate:fresh --seed
php artisan tinker
```

## 🔒 Security & Access

⚠️ **Current Status**: Open access (no authentication)

### Recommended Security
- User authentication system
- Role-based access control
- Input validation and sanitization
- CSRF protection

## 📊 System Requirements

- **PHP**: 8.2+
- **Composer**: 2.0+
- **Database**: SQLite 3+ or MySQL 5.7+
- **Memory**: 512MB+ RAM
- **Storage**: 100MB+ disk space

## 🔄 Current Status

### ✅ Working Routes (HTTP 200)
- `/` - Dashboard
- `/peserta/*` - Participant management
- `/penilaian/*` - Assessment system
- `/hasil` - Results analysis

### ❌ Missing Features
- User authentication
- Role-specific dashboards
- Advanced security features
- Export functionality
- Email notifications

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📝 Documentation

- **Project TODO**: See `PROJECT_TODO.md` for development tasks
- **Laravel Docs**: https://laravel.com/docs
- **Bootstrap Docs**: https://getbootstrap.com/docs

## 📋 Changelog

For detailed information about changes, updates, and new features, please see the [CHANGELOG.md](CHANGELOG.md).

### Recent Updates (v1.0.1)
- ✅ **User Authentication System** - Secure login/logout functionality
- ✅ **Session Management** - User state preservation and security
- ✅ **Route Protection** - Authentication-based access control
- ✅ **Demo User Accounts** - Pre-configured accounts for testing
- ✅ **Enhanced UI** - User profiles and logout functionality
- ✅ **Database Integration** - MySQL with demo data seeding

## 📞 Support

For technical support:
- Review the `PROJECT_TODO.md` file for development guidance
- Check Laravel documentation for framework-specific questions
- Use GitHub Issues for bug reports and feature requests

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Built with ❤️ using Laravel 12.x + Bootstrap 5 + SMART + Borda Algorithms**