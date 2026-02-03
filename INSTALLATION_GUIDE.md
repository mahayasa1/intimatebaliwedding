# 📖 Panduan Instalasi Lengkap - Step by Step

## 🎯 Prerequisites
- PHP >= 8.1
- Composer
- MySQL/PostgreSQL/SQLite
- Node.js & NPM (untuk Vite)
- Laravel 11.x sudah terinstall

---

## 📦 Step 1: Copy Files

### 1.1. Copy Models
```bash
# Dari folder yang telah di-extract
cp app/Models/Service.php /path/to/your-laravel-project/app/Models/
cp app/Models/Package.php /path/to/your-laravel-project/app/Models/
cp app/Models/Gallery.php /path/to/your-laravel-project/app/Models/
cp app/Models/Blog.php /path/to/your-laravel-project/app/Models/
cp app/Models/Enquiry.php /path/to/your-laravel-project/app/Models/
```

### 1.2. Copy Controllers
```bash
cp app/Http/Controllers/ServiceController.php /path/to/your-laravel-project/app/Http/Controllers/
cp app/Http/Controllers/PackageController.php /path/to/your-laravel-project/app/Http/Controllers/
cp app/Http/Controllers/GalleryController.php /path/to/your-laravel-project/app/Http/Controllers/
cp app/Http/Controllers/BlogController.php /path/to/your-laravel-project/app/Http/Controllers/
cp app/Http/Controllers/EnquiryController.php /path/to/your-laravel-project/app/Http/Controllers/
```

### 1.3. Copy Migrations
```bash
cp database/migrations/2026_02_02_081729_create_galleries_table.php /path/to/your-laravel-project/database/migrations/
cp database/migrations/2026_02_02_081736_create_blogs_table.php /path/to/your-laravel-project/database/migrations/
cp database/migrations/2026_02_02_081757_create_enquiries_table.php /path/to/your-laravel-project/database/migrations/
```

### 1.4. Copy Routes
```bash
# BACKUP dulu routes/web.php yang lama!
cp routes/web.php /path/to/your-laravel-project/routes/web.php
```

### 1.5. Copy Views
```bash
# Copy main views
cp resources/views/about.blade.php /path/to/your-laravel-project/resources/views/
cp resources/views/contact.blade.php /path/to/your-laravel-project/resources/views/
cp resources/views/welcome.blade.php /path/to/your-laravel-project/resources/views/

# Create directories for module views
mkdir -p /path/to/your-laravel-project/resources/views/services
mkdir -p /path/to/your-laravel-project/resources/views/packages
mkdir -p /path/to/your-laravel-project/resources/views/galleries
mkdir -p /path/to/your-laravel-project/resources/views/blogs

# Copy module views
cp resources/views/services/index.blade.php /path/to/your-laravel-project/resources/views/services/
cp resources/views/packages/index.blade.php /path/to/your-laravel-project/resources/views/packages/
cp resources/views/galleries/index.blade.php /path/to/your-laravel-project/resources/views/galleries/
cp resources/views/blogs/index.blade.php /path/to/your-laravel-project/resources/views/blogs/
```

---

## ⚙️ Step 2: Configuration

### 2.1. Update .env File
```env
APP_NAME="Intimate Bali Wedding"
APP_URL=http://localhost

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=intimate_bali_wedding
DB_USERNAME=root
DB_PASSWORD=

# File System
FILESYSTEM_DISK=public
```

### 2.2. Create Database
```bash
# Buat database baru
mysql -u root -p
CREATE DATABASE intimate_bali_wedding;
exit;
```

---

## 🗄️ Step 3: Run Migrations

```bash
cd /path/to/your-laravel-project

# Run migrations
php artisan migrate

# Jika ada error, coba:
php artisan migrate:fresh
```

Expected output:
```
Migration table created successfully.
Migrating: 2026_02_02_081709_create_services_table
Migrated:  2026_02_02_081709_create_services_table
Migrating: 2026_02_02_081717_create_packages_table
Migrated:  2026_02_02_081717_create_packages_table
Migrating: 2026_02_02_081729_create_galleries_table
Migrated:  2026_02_02_081729_create_galleries_table
Migrating: 2026_02_02_081736_create_blogs_table
Migrated:  2026_02_02_081736_create_blogs_table
Migrating: 2026_02_02_081757_create_enquiries_table
Migrated:  2026_02_02_081757_create_enquiries_table
```

---

## 📁 Step 4: Storage Setup

### 4.1. Create Storage Link
```bash
php artisan storage:link
```

### 4.2. Set Permissions
```bash
# Linux/Mac
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Windows: Pastikan folder bisa di-write
```

### 4.3. Create Upload Directories
```bash
mkdir -p storage/app/public/services
mkdir -p storage/app/public/galleries
mkdir -p storage/app/public/blogs
```

---

## 🎨 Step 5: Assets & Frontend

### 5.1. Install NPM Dependencies (jika belum)
```bash
npm install
```

### 5.2. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

---

## 🧪 Step 6: Testing

### 6.1. Clear All Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 6.2. Check Routes
```bash
php artisan route:list
```

Pastikan routes berikut ada:
- GET / → welcome
- GET /about → about
- GET /services → ServiceController@index
- GET /packages → PackageController@index
- GET /gallery → GalleryController@index
- GET /blog → BlogController@index
- GET /contact → contact
- POST /enquiry → EnquiryController@store

### 6.3. Start Development Server
```bash
php artisan serve
```

### 6.4. Open Browser
```
http://localhost:8000
```

Test halaman berikut:
- ✅ Homepage (/)
- ✅ About (/about)
- ✅ Services (/services)
- ✅ Packages (/packages)
- ✅ Gallery (/gallery)
- ✅ Blog (/blog)
- ✅ Contact (/contact)

---

## 📊 Step 7: Seeding Data (Optional)

### 7.1. Create Seeders
```bash
php artisan make:seeder PackageSeeder
php artisan make:seeder ServiceSeeder
php artisan make:seeder GallerySeeder
php artisan make:seeder BlogSeeder
```

### 7.2. Example Package Seeder

Edit `database/seeders/PackageSeeder.php`:
```php
<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Beach Wedding',
                'description' => 'Celebrate your love with the ocean as your backdrop. Perfect for couples who dream of saying "I do" with sand between their toes and the sound of waves in the background.',
            ],
            [
                'name' => 'Garden Wedding',
                'description' => 'Exchange vows surrounded by lush tropical gardens and natural beauty. Ideal for couples seeking an intimate ceremony in nature.',
            ],
            [
                'name' => 'Chapel Wedding',
                'description' => 'Traditional elegance in our beautiful chapel. Perfect for couples who want a classic, elegant wedding ceremony.',
            ],
            [
                'name' => 'Villa Wedding',
                'description' => 'Intimate celebration in a private luxury villa. Exclusive and personalized for your special day.',
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
```

### 7.3. Run Seeders
```bash
php artisan db:seed --class=PackageSeeder
```

---

## 🔐 Step 8: Admin Authentication (Optional)

### 8.1. Install Laravel Breeze
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
php artisan migrate
```

### 8.2. Update Admin Routes

Edit `routes/web.php`, wrap admin routes dengan middleware:
```php
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('services', ServiceController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('galleries', GalleryController::class);
    Route::resource('blogs', BlogController::class);
    Route::resource('enquiries', EnquiryController::class);
});
```

### 8.3. Create Admin User
```bash
php artisan tinker
```

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin',
    'username' => 'admin',
    'email' => 'admin@intimatebaliwedding.com',
    'password' => Hash::make('password123'),
    'role' => 'admin'
]);
```

---

## 🎯 Step 9: Testing CRUD Operations

### 9.1. Test Package CRUD
```
Visit: http://localhost:8000/admin/packages
Try: Create, Edit, Delete packages
```

### 9.2. Test Service CRUD
```
Visit: http://localhost:8000/admin/services
Try: Create with image upload
```

### 9.3. Test Gallery Upload
```
Visit: http://localhost:8000/admin/galleries
Try: Upload images, add categories
```

### 9.4. Test Blog Posts
```
Visit: http://localhost:8000/admin/blogs
Try: Create blog post with image
```

### 9.5. Test Enquiry Form
```
Visit: http://localhost:8000/contact
Fill form and submit
Check: http://localhost:8000/admin/enquiries
```

---

## 🐛 Common Issues & Solutions

### Issue 1: Migration Failed - Package Table Not Found
```bash
# Solution: Run migrations in order
php artisan migrate:fresh
```

### Issue 2: Images Not Displaying
```bash
# Solution: Create storage link
php artisan storage:link
chmod -R 775 storage
```

### Issue 3: Route Not Found
```bash
# Solution: Clear route cache
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### Issue 4: 500 Error on Upload
```bash
# Solution: Check permissions
chmod -R 775 storage/app/public
```

### Issue 5: Class Not Found
```bash
# Solution: Dump autoload
composer dump-autoload
```

---

## ✅ Checklist Setelah Install

- [ ] All migrations berhasil
- [ ] Storage link dibuat
- [ ] Permissions sudah benar
- [ ] Homepage bisa diakses
- [ ] About page bisa diakses
- [ ] Services page bisa diakses
- [ ] Packages page bisa diakses
- [ ] Gallery page bisa diakses
- [ ] Blog page bisa diakses
- [ ] Contact form bisa submit
- [ ] Admin routes bisa diakses
- [ ] Upload image berfungsi
- [ ] CRUD operations berfungsi

---

## 🎉 Selesai!

Aplikasi Anda sudah siap digunakan. Untuk production deployment, jangan lupa:
1. Set `APP_DEBUG=false` di `.env`
2. Set `APP_ENV=production`
3. Generate production key: `php artisan key:generate`
4. Optimize: `php artisan optimize`
5. Build assets: `npm run build`

---

## 📞 Need Help?

Jika ada masalah atau pertanyaan:
1. Check error logs di `storage/logs/laravel.log`
2. Enable debug mode: `APP_DEBUG=true`
3. Check Laravel documentation: https://laravel.com/docs