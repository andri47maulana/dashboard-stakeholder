# Dashboard Stakeholder PTPN 1

Sistem manajemen dashboard stakeholder untuk PT Perkebunan Nusantara (PTPN) 1 yang dibangun dengan Laravel 12.

## Fitur

- 📊 Dashboard stakeholder dengan visualisasi data
- 👥 Manajemen user dan akses berbasis role
- 🏢 Data master kebun dan wilayah
- 📋 Sistem perizinan dan sertifikasi
- 🤝 Manajemen stakeholder governance dan non-governance
- 🔐 Sistem autentikasi dan otorisasi

## Persyaratan Sistem

- **PHP**: ^8.2 atau lebih tinggi
- **MySQL**: 5.7+ atau MariaDB 10.2+
- **Composer**: 2.0+
- **Node.js**: 16+ (opsional, untuk asset compilation)
- **Web Server**: Apache/Nginx

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/andri47maulana/dashboard-stakeholder.git
cd dashboard-stakeholder
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies (opsional)
npm install
```

### 3. Environment Setup

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dashboard_stakeholder
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Database Migration

```bash
# Buat database
mysql -u root -p -e "CREATE DATABASE dashboard_stakeholder;"

# Jalankan migrasi
php artisan migrate

# Jalankan seeder (opsional)
php artisan db:seed
```

### 6. File Permissions

```bash
# Linux/Mac
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Windows (PowerShell as Administrator)
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
```

### 7. Asset Compilation (Opsional)

```bash
# Development
npm run dev

# Production
npm run build
```

## Menjalankan Aplikasi

### Development Server

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

### Production Server

1. **Apache**: Arahkan document root ke folder `public`
2. **Nginx**: Konfigurasi virtual host ke folder `public`

## Konfigurasi Tambahan

### Cache Configuration

```bash
# Clear all caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Storage Link

```bash
php artisan storage:link
```

## Default Login

Setelah instalasi dan seeding:

- **Username**: admin@ptpn1.com
- **Password**: password

> ⚠️ **Penting**: Ubah password default setelah login pertama kali!

## Struktur Project

```
dashboard-stakeholder/
├── app/                    # Application logic
├── bootstrap/              # Framework bootstrap files
├── config/                 # Configuration files
├── database/              # Migrations, seeders, factories
├── public/                # Web server document root
├── resources/             # Views, CSS, JS, language files
├── routes/                # Route definitions
├── storage/               # Generated files, logs, cache
├── tests/                 # Automated tests
└── vendor/                # Composer dependencies
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/dashboard` | Dashboard utama |
| GET | `/dash/stakeholder` | Data stakeholder |
| GET | `/masterdata/kebun` | Master data kebun |
| POST | `/login/func_login` | Proses login |
| GET | `/func_logout` | Logout |

## Troubleshooting

### Error "Class not found"

```bash
composer dump-autoload
```

### Permission Denied

```bash
# Linux/Mac
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Windows
# Jalankan CMD sebagai Administrator
takeown /f storage /r /d y
takeown /f bootstrap\cache /r /d y
```

### 404 Asset Files

Pastikan asset files tersedia di folder `public/`:

```bash
# Copy assets jika diperlukan
cp -r public_html/public/* public/
```

### Database Connection Error

1. Pastikan MySQL service berjalan
2. Cek kredensial database di file `.env`
3. Pastikan database sudah dibuat

## Development

### Code Style

Project ini menggunakan PSR-12 coding standard:

```bash
composer require --dev psr/coding-standard
```

### Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=ExampleTest
```

## Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Security

Jika menemukan vulnerability keamanan, silakan kirim email ke: security@ptpn1.com

## Changelog

### v2.0.0 (2025-09-22)
- ✅ Upgrade ke Laravel 12
- ✅ Kompatibilitas PHP 8.4
- ✅ Perbaikan asset loading
- ✅ Update CORS middleware

### v1.0.0
- 🎉 Rilis perdana
- ✨ Dashboard stakeholder
- 👥 User management
- 📊 Master data management

## License

Project ini dilindungi hak cipta PT Perkebunan Nusantara 1.
