# acmi_db

Aplikasi web berbasis **Laravel** untuk manajemen database ACMI (ASOSIASI CEO MASTERMIND INDONESIA). Dibangun dengan stack modern menggunakan Tailwind CSS, Vite, dan TypeScript.


## 🚀 Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | PHP · Laravel |
| Frontend | Blade · JavaScript · TypeScript |
| Styling | Tailwind CSS |
| Build Tool | Vite |
| Database | MySQL / PostgreSQL (via Laravel Eloquent) |
| Testing | PHPUnit |


## 📋 Prasyarat

Pastikan environment kamu sudah memiliki:

- PHP **>= 8.1**
- Composer **>= 2.x**
- Node.js **>= 18.x** & npm
- MySQL / PostgreSQL
- (Opsional) Git

## ⚙️ Instalasi

### 1. Clone repository

```bash
git clone https://github.com/nnaelmuna/acmi_db.git
cd acmi_db
```

### 2. Install dependensi PHP

```bash
composer install
```

### 3. Install dependensi JavaScript

```bash
npm install
```

### 4. Salin dan konfigurasi file environment

```bash
cp .env.example .env
```

Edit file `.env` sesuai konfigurasi lokal kamu:

```env
APP_NAME=acmi_db
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=acmi_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Jalankan migrasi database

```bash
php artisan migrate
```

Atau jika ingin sekaligus menjalankan seeder:

```bash
php artisan migrate --seed
```

### 7. Build aset frontend

Untuk development (dengan hot-reload):

```bash
npm run dev
```

Untuk production:

```bash
npm run build
```

### 8. Jalankan server lokal

```bash
php artisan serve
```

Aplikasi dapat diakses di `http://127.0.0.1:8000`


## 🗂️ Struktur Proyek

```
acmi_db/
├── app/
│   ├── Http/
│   │   ├── Controllers/    # Controller aplikasi
│   │   └── Middleware/     # Middleware
│   ├── Models/             # Eloquent models
│   └── Providers/          # Service providers
├── config/                 # Konfigurasi aplikasi
├── database/
│   ├── migrations/         # Migrasi database
│   ├── seeders/            # Data seeder
│   └── factories/          # Model factories
├── public/                 # Asset publik & entry point
├── resources/
│   ├── views/              # Blade templates
│   ├── js/                 # JavaScript / TypeScript
│   └── css/                # Stylesheet (Tailwind)
├── routes/
│   ├── web.php             # Route web
│   └── api.php             # Route API
├── storage/                # File cache, log, upload
└── tests/                  # Unit & feature tests
```

## 🧪 Menjalankan Tests

```bash
php artisan test
```

Atau menggunakan PHPUnit langsung:

```bash
./vendor/bin/phpunit
```

## 🛠️ Perintah Artisan Berguna

```bash
# Bersihkan cache aplikasi
php artisan optimize:clear

# Lihat semua route
php artisan route:list

# Buat controller baru
php artisan make:controller NamaController

# Buat model beserta migrasi
php artisan make:model NamaModel -m
```

## 🤝 Kontribusi

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/nama-fitur`)
3. Commit perubahan (`git commit -m 'feat: tambah fitur X'`)
4. Push ke branch (`git push origin feature/nama-fitur`)
5. Buat Pull Request

