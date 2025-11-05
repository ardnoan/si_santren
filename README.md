# 📖 SI SANTREN - Sistem Informasi Pesantren

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Sistem Informasi Manajemen Pesantren berbasis web menggunakan Laravel 10 dengan implementasi **SOLID Principles** dan **Clean Architecture**.

---

## 🎯 FITUR UTAMA

### ✅ Sudah Lengkap
1. **Manajemen Santri** - CRUD lengkap dengan foto dan validasi
2. **Manajemen Pembayaran** - SPP, Pendaftaran, Seragam, dll
3. **Kehadiran Santri** - Tracking harian dengan status
4. **Nilai Akademik** - Input dan monitoring nilai
5. **Dashboard Interaktif** - Statistik real-time dengan Chart.js
6. **Authentication** - Login/Logout dengan role-based access
7. **Database Migration** - Schema lengkap siap pakai
8. **Dark Mode Support** - Toggle tema light/dark

### 🚧 Fitur Tambahan (Opsional)
- Export PDF/Excel untuk laporan
- Notifikasi email/SMS
- Calendar view untuk jadwal
- Cetak kartu santri
- Bulk import data santri

---

## 📋 REQUIREMENTS

- PHP >= 8.1
- Composer
- MySQL >= 5.7 / MariaDB >= 10.3
- Node.js >= 16.x & NPM
- Web Server (Apache/Nginx)

---

## 🔧 INSTALASI

### 1. Clone Repository
```bash
git clone https://github.com/username/sisantren.git
cd sisantren
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sisantren_db
DB_USERNAME=root
DB_PASSWORD=
```

Buat database:
```bash
mysql -u root -p -e "CREATE DATABASE sisantren_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 5. Run Migrations & Seeders
```bash
# Run migrations
php artisan migrate

# Run seeder (data dummy)
php artisan db:seed
```

### 6. Storage Link
```bash
php artisan storage:link
```

### 7. Compile Assets (Opsional)
```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Run Application
```bash
php artisan serve
```

Buka browser: `http://localhost:8000`

---

## 👤 AKUN DEMO

| Role | Username | Password | Akses |
|------|----------|----------|-------|
| Admin | admin | admin123 | Full access |
| Ustadz | ustadz1 | ustadz123 | Terbatas |
| Santri | santri1-50 | santri123 | Dashboard santri |

---

## 📂 STRUKTUR PROYEK

```
sisantren/
├── app/
│   ├── Console/
│   ├── Contracts/          # Interfaces
│   │   └── RepositoryInterface.php
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── SantriController.php
│   │   │   │   ├── PembayaranController.php
│   │   │   │   ├── KehadiranController.php
│   │   │   │   ├── NilaiController.php
│   │   │   │   └── KelasController.php
│   │   │   └── DashboardController.php
│   │   ├── Middleware/
│   │   └── Requests/
│   │       ├── SantriRequest.php
│   │       ├── PembayaranRequest.php
│   │       └── KehadiranRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Santri.php
│   │   ├── Kelas.php
│   │   ├── Pembayaran.php
│   │   ├── Kehadiran.php
│   │   ├── Nilai.php
│   │   └── MataPelajaran.php
│   ├── Repositories/
│   │   ├── BaseRepository.php
│   │   ├── SantriRepository.php
│   │   └── PembayaranRepository.php
│   └── Services/
│       ├── SantriService.php
│       └── PembayaranService.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── santri/
│       │   ├── pembayaran/
│       │   ├── kehadiran/
│       │   ├── nilai/
│       │   └── kelas/
│       ├── auth/
│       │   └── login-custom.blade.php
│       └── layouts/
│           └── admin.blade.php
└── routes/
    └── web.php
```

---

## 🏗️ ARSITEKTUR & DESIGN PATTERNS

### 1. Repository Pattern
```php
// Separasi data access dari business logic
class SantriRepository extends BaseRepository {
    public function getAllAktifWithKelas() {
        return $this->model->with('kelas')->aktif()->paginate(15);
    }
}
```

### 2. Service Layer
```php
// Business logic terpisah dari controller
class SantriService {
    public function createSantri(array $data) {
        DB::beginTransaction();
        try {
            // Complex business logic here
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
```

### 3. Form Request Validation
```php
// Validation logic terpisah
class SantriRequest extends FormRequest {
    public function rules(): array {
        return [
            'nama_lengkap' => 'required|string|max:124',
            // ...
        ];
    }
}
```

---

## 💾 DATABASE SCHEMA

### Tables Overview
- `users` - User authentication & authorization
- `santris` - Data santri (soft deletes)
- `kelas` - Kelas & tingkatan
- `mata_pelajarans` - Mata pelajaran
- `kehadirans` - Rekam kehadiran
- `pembayarans` - Transaksi keuangan
- `nilais` - Nilai akademik
- `jadwal_pelajarans` - Jadwal pelajaran

### Entity Relationship
```
User (1) ─── (1) Santri
Santri (N) ─── (1) Kelas
Santri (1) ─── (N) Kehadiran
Santri (1) ─── (N) Pembayaran
Santri (1) ─── (N) Nilai
```

---

## 🎨 UI/UX FEATURES

### Bootstrap 5.3 Implementation
- Responsive design (mobile-first)
- Bootstrap Icons
- Card-based layout
- Alert notifications
- Modal dialogs

### Dark Mode Support
```javascript
// Toggle dark mode
localStorage.setItem('theme', 'dark');
// atau
localStorage.setItem('theme', 'light');
```

### Charts & Visualization
- Chart.js untuk grafik
- Real-time statistics
- Interactive dashboard

---

## 🔐 SECURITY FEATURES

1. **CSRF Protection** - Token validation
2. **SQL Injection Prevention** - Eloquent ORM
3. **XSS Protection** - Blade escaping
4. **Password Hashing** - bcrypt
5. **Authentication** - Laravel Auth
6. **Authorization** - Role-based access

---

## 🧪 TESTING (Coming Soon)

```bash
# Run tests
php artisan test

# With coverage
php artisan test --coverage
```

---

## 📝 API ENDPOINTS (Internal)

### Santri API
```
GET    /api/santri/search?q={keyword}
GET    /api/santri/{id}/detail
```

### Admin Routes
```
GET    /admin/dashboard
GET    /admin/santri
POST   /admin/santri
GET    /admin/santri/{id}
PUT    /admin/santri/{id}
DELETE /admin/santri/{id}

GET    /admin/pembayaran
POST   /admin/pembayaran
GET    /admin/pembayaran/laporan

GET    /admin/kehadiran
POST   /admin/kehadiran
POST   /admin/kehadiran/bulk-create

GET    /admin/nilai
POST   /admin/nilai
GET    /admin/nilai/santri/{id}
```

---

## 🚀 DEPLOYMENT

### Production Setup
```bash
# Optimize autoload
composer install --optimize-autoloader --no-dev

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Link storage
php artisan storage:link
```

### Environment Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_DATABASE=sisantren_prod
DB_USERNAME=sisantren_user
DB_PASSWORD=strong_password
```

### Web Server Config

#### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/sisantren/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 🐛 TROUBLESHOOTING

### Error: Class not found
```bash
composer dump-autoload
```

### Error: 500 Internal Server Error
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Error: Permission denied (storage)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Error: Migration already exists
```bash
php artisan migrate:fresh --seed
```

---

## 📚 RESOURCES & DOCUMENTATION

- [Laravel Documentation](https://laravel.com/docs/10.x)
- [Bootstrap Documentation](https://getbootstrap.com/docs/5.3)
- [Chart.js Documentation](https://www.chartjs.org/docs)
- [PHP Best Practices](https://phptherightway.com)

---

## 🤝 CONTRIBUTING

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

### Coding Standards
- PSR-12 untuk PHP
- Eloquent naming conventions
- Meaningful commit messages
- Comment untuk logic kompleks

---

## 📄 LICENSE

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 AUTHOR & SUPPORT

**Developer**: Your Name / Team Name  
**Email**: your.email@example.com  
**GitHub**: [@yourusername](https://github.com/yourusername)

### Support
- 📧 Email: support@sisantren.com
- 💬 Issues: [GitHub Issues](https://github.com/username/sisantren/issues)
- 📖 Wiki: [Documentation](https://github.com/username/sisantren/wiki)

---

## 🎓 CREDITS

Aplikasi ini dibuat sebagai pembelajaran implementasi:
- **SOLID Principles** dalam PHP/Laravel
- **Repository Pattern** untuk data access
- **Service Layer** untuk business logic
- **Clean Architecture** untuk maintainability

---

## 📊 CHANGELOG

### Version 1.0.0 (Current)
- ✅ Complete CRUD for Santri, Pembayaran, Kehadiran, Nilai
- ✅ Dashboard with charts
- ✅ Authentication & Authorization
- ✅ Responsive UI with Bootstrap 5
- ✅ Dark mode support
- ✅ Database seeder with 50 dummy data
- 🔄 API endpoints for future mobile app

### Upcoming Features
- 📱 Mobile app (Flutter/React Native)
- 📧 Email notifications
- 📄 PDF report generation
- 📊 Advanced analytics
- 🔔 Push notifications

---

## ⭐ STAR THIS REPO

If you find this project helpful, please consider giving it a ⭐ star on GitHub!

---

**Happy Coding! 🚀**

Made with ❤️ using Laravel & Bootstrap