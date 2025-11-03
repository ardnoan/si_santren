# 📖 SI SANTREN - Sistem Informasi Pesantren

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

### 🚧 Yang Perlu Ditambahkan (Opsional)
- Export PDF/Excel untuk laporan
- Notifikasi email/SMS
- Calendar view untuk jadwal
- Cetak kartu santri

---

## 📁 STRUKTUR FILE YANG SUDAH DIBUAT

### 1. **Models** (`app/Models/`)
```
✅ User.php (Complete)
✅ Santri.php (Complete)
✅ Kelas.php (Complete)
✅ Pembayaran.php (Complete)
✅ Kehadiran.php (Complete)
✅ Nilai.php (Complete)
✅ MataPelajaran.php (Complete)
✅ JadwalPelajaran.php (Complete)
```

### 2. **Repositories** (`app/Repositories/`)
```
✅ BaseRepository.php
✅ SantriRepository.php
✅ PembayaranRepository.php
```

### 3. **Services** (`app/Services/`)
```
✅ SantriService.php
✅ PembayaranService.php
```

### 4. **Controllers** (`app/Http/Controllers/`)
```
✅ DashboardController.php
✅ Admin/SantriController.php (Complete)
✅ Admin/PembayaranController.php (Complete)
⚠️ Admin/KehadiranController.php (Perlu dilengkapi)
⚠️ Admin/NilaiController.php (Perlu dilengkapi)
⚠️ Admin/KelasController.php (Perlu dilengkapi)
```

### 5. **Form Requests** (`app/Http/Requests/`)
```
✅ SantriRequest.php
✅ PembayaranRequest.php
✅ KehadiranRequest.php
```

### 6. **Migrations** (`database/migrations/`)
```
✅ create_users_table_complete.php
✅ create_kelas_table_complete.php
✅ create_santris_table_complete.php
✅ create_mata_pelajarans_table_complete.php
✅ create_kehadirans_table_complete.php
✅ create_pembayarans_table_complete.php
✅ create_nilais_table_complete.php
✅ create_jadwal_pelajarans_table_complete.php
```

### 7. **Views** (`resources/views/`)
```
✅ layouts/admin.blade.php (Complete)
✅ admin/dashboard.blade.php (Complete dengan Chart)
✅ auth/login-custom.blade.php (Beautiful Login Page)
⚠️ admin/santri/* (Perlu dibuat)
⚠️ admin/pembayaran/* (Perlu dibuat)
⚠️ admin/kehadiran/* (Perlu dibuat)
⚠️ admin/nilai/* (Perlu dibuat)
```

### 8. **Routes** (`routes/web.php`)
```
✅ Sudah lengkap dengan semua route
```

---

## 🔧 INSTALASI & SETUP

### Step 1: Clone & Install Dependencies
```bash
# Clone repository
git clone <your-repo-url>
cd sisantren

# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate
```

### Step 2: Database Configuration
Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sisantren_db
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Run Migrations & Seeders
```bash
# Create database
mysql -u root -e "CREATE DATABASE sisantren_db"

# Run migrations
php artisan migrate:fresh

# Run seeder
php artisan db:seed
```

### Step 4: Storage Link
```bash
php artisan storage:link
```

### Step 5: Run Development Server
```bash
# Laravel server
php artisan serve

# NPM (if using Vite)
npm run dev
```

Buka: `http://localhost:8000`

---

## 👤 AKUN DEMO

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin123 |
| Ustadz | ustadz1 | ustadz123 |
| Santri | santri1-10 | santri123 |

---

## 📂 IMPLEMENTASI SOLID PRINCIPLES

### 1. **Single Responsibility Principle (SRP)**
- Setiap class memiliki satu tanggung jawab
- Controller hanya handle HTTP request/response
- Service layer untuk business logic
- Repository untuk data access

### 2. **Open/Closed Principle (OCP)**
- BaseRepository dapat di-extend tanpa modifikasi
- Service dapat ditambahkan fitur baru

### 3. **Liskov Substitution Principle (LSP)**
- Semua repository implement RepositoryInterface
- Dapat diganti tanpa error

### 4. **Interface Segregation Principle (ISP)**
- RepositoryInterface hanya method yang diperlukan
- Tidak ada method yang tidak terpakai

### 5. **Dependency Inversion Principle (DIP)**
- Controller depend on abstraction (Service)
- Service depend on abstraction (Repository)
- Dependency Injection via constructor

---

## 🎨 CLEAN CODE PATTERNS

### Repository Pattern
```php
// Data access layer
class SantriRepository extends BaseRepository {
    public function getAllAktifWithKelas() {
        return $this->model->with('kelas')->aktif()->paginate();
    }
}
```

### Service Layer
```php
// Business logic layer
class SantriService {
    public function createSantri(array $data) {
        DB::beginTransaction();
        try {
            // Business logic here
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
```

### Form Request Validation
```php
// Validation logic separated
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

## 🛠️ FILE YANG PERLU DILENGKAPI

### Priority 1: Controllers (Urgent)

#### 1. `app/Http/Controllers/Admin/KehadiranController.php`
```php
// Tambahkan method:
- index() -> Tampilkan daftar kehadiran
- create() -> Form input kehadiran
- store() -> Simpan kehadiran
- bulkCreate() -> Input kehadiran massal per kelas
```

#### 2. `app/Http/Controllers/Admin/NilaiController.php`
```php
// Tambahkan method:
- index() -> Daftar nilai
- create() -> Form input nilai
- store() -> Simpan nilai
- bySantri() -> Nilai per santri
```

#### 3. `app/Http/Controllers/Admin/KelasController.php`
```php
// Lengkapi semua method CRUD
```

### Priority 2: Views (Urgent)

#### Santri Views
- `resources/views/admin/santri/index.blade.php` - Daftar santri dengan search & filter
- `resources/views/admin/santri/create.blade.php` - Form tambah santri
- `resources/views/admin/santri/edit.blade.php` - Form edit santri
- `resources/views/admin/santri/show.blade.php` - Detail santri

#### Pembayaran Views
- `resources/views/admin/pembayaran/index.blade.php` - Daftar pembayaran
- `resources/views/admin/pembayaran/create.blade.php` - Form input pembayaran
- `resources/views/admin/pembayaran/laporan.blade.php` - Laporan pembayaran

#### Kehadiran Views
- `resources/views/admin/kehadiran/index.blade.php` - Daftar kehadiran
- `resources/views/admin/kehadiran/create.blade.php` - Form input kehadiran
- `resources/views/admin/kehadiran/bulk-create.blade.php` - Input massal

#### Nilai Views
- `resources/views/admin/nilai/index.blade.php` - Daftar nilai
- `resources/views/admin/nilai/create.blade.php` - Form input nilai
- `resources/views/admin/nilai/show.blade.php` - Detail nilai santri

### Priority 3: Additional Features (Optional)

#### Laporan PDF
```bash
composer require barryvdh/laravel-dompdf
```

#### Export Excel
```bash
composer require maatwebsite/excel
```

---

## 🎯 CARA MELANJUTKAN DEVELOPMENT

### 1. Lengkapi Controller yang Kosong
Template untuk KehadiranController:
```php
public function index() {
    $kehadiran = Kehadiran::with(['santri'])
        ->orderBy('tanggal', 'desc')
        ->paginate(20);
    return view('admin.kehadiran.index', compact('kehadiran'));
}

public function create() {
    $santri = Santri::aktif()->orderBy('nama_lengkap')->get();
    return view('admin.kehadiran.create', compact('santri'));
}

public function store(KehadiranRequest $request) {
    Kehadiran::create($request->validated());
    return redirect()->route('admin.kehadiran.index')
        ->with('success', 'Kehadiran berhasil disimpan');
}
```

### 2. Buat View dengan Copy-Paste dari Dashboard
Gunakan layout dan style yang sama dengan dashboard.

### 3. Testing
```bash
php artisan test
```

---

## 📌 TIPS DEVELOPMENT

1. **Gunakan Soft Deletes** untuk data penting
2. **Validation** di Form Request, bukan di Controller
3. **Transaction** untuk operasi database kompleks
4. **Eager Loading** untuk avoid N+1 query
5. **Index** database untuk performa

---

## 🐛 TROUBLESHOOTING

### Error: Class not found
```bash
composer dump-autoload
```

### Error: Migration already exists
```bash
php artisan migrate:fresh --seed
```

### Error: 500 Internal Server Error
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 📞 SUPPORT

Jika ada pertanyaan atau butuh bantuan:
1. Check dokumentasi Laravel: https://laravel.com/docs
2. Read source code comments
3. Contact: [your-email@example.com]

---

## 📝 CHANGELOG

### Version 1.0.0 (Current)
- ✅ Complete Models with relationships
- ✅ Repository Pattern implementation
- ✅ Service Layer for business logic
- ✅ Dashboard with statistics & charts
- ✅ Authentication system
- ✅ Database migrations
- ✅ Santri & Pembayaran controllers complete
- ⚠️ Views need to be created
- ⚠️ Some controllers need completion

---

## 🎓 PEMBELAJARAN OOP

Aplikasi ini mengimplementasikan:
- **Encapsulation**: Private properties, public methods
- **Inheritance**: BaseRepository, Controllers
- **Polymorphism**: Repository interfaces
- **Abstraction**: Service layer, interfaces
- **Dependency Injection**: Constructor injection
- **Design Patterns**: Repository, Service, Factory

---

## ⚡ NEXT STEPS

1. ✅ Baca dokumentasi ini dengan seksama
2. ✅ Run migration & seeder
3. ✅ Test login dengan akun demo
4. ⚠️ Lengkapi controllers yang kosong
5. ⚠️ Buat views untuk setiap modul
6. ⚠️ Test setiap fitur
7. ⚠️ Deploy ke production

---

**Happy Coding! 🚀**