# Intimate Bali Wedding - Laravel Application

Aplikasi wedding planning yang lengkap dengan fitur CRUD untuk Services, Packages, Gallery, Blog, dan Enquiry Management.

## 📋 Struktur File yang Dibuat

### 1. **Models** (app/Models/)
- `Service.php` - Model untuk layanan wedding
- `Package.php` - Model untuk paket wedding
- `Gallery.php` - Model untuk galeri foto
- `Blog.php` - Model untuk artikel blog
- `Enquiry.php` - Model untuk form enquiry

### 2. **Controllers** (app/Http/Controllers/)
- `ServiceController.php` - CRUD untuk services
- `PackageController.php` - CRUD untuk packages
- `GalleryController.php` - CRUD untuk gallery
- `BlogController.php` - CRUD untuk blog
- `EnquiryController.php` - CRUD untuk enquiries

### 3. **Migrations** (database/migrations/)
- `2026_02_02_081729_create_galleries_table.php`
- `2026_02_02_081736_create_blogs_table.php`
- `2026_02_02_081757_create_enquiries_table.php`

### 4. **Routes** (routes/)
- `web.php` - Semua routes untuk frontend dan admin

### 5. **Views** (resources/views/)
- `about.blade.php` - Halaman About Us
- `contact.blade.php` - Halaman Contact dengan form enquiry
- `welcome.blade.php` - Homepage (updated)
- `services/index.blade.php` - Daftar services
- `packages/index.blade.php` - Daftar packages
- `galleries/index.blade.php` - Gallery dengan filter
- `blogs/index.blade.php` - Daftar blog posts

## 🚀 Cara Install & Setup

### 1. Copy Files ke Project Laravel Anda

```bash
# Copy Models
cp app/Models/* your-project-path/app/Models/

# Copy Controllers
cp app/Http/Controllers/* your-project-path/app/Http/Controllers/

# Copy Migrations
cp database/migrations/* your-project-path/database/migrations/

# Copy Routes
cp routes/web.php your-project-path/routes/

# Copy Views
cp -r resources/views/* your-project-path/resources/views/
```

### 2. Jalankan Migrations

```bash
php artisan migrate
```

### 3. Create Storage Link (untuk upload images)

```bash
php artisan storage:link
```

### 4. Set Permissions untuk Storage

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## 📱 Routes yang Tersedia

### Frontend Routes:
- `/` - Homepage
- `/about` - About Us
- `/services` - Daftar Services
- `/packages` - Daftar Packages
- `/gallery` - Gallery dengan filter
- `/blog` - Blog listing
- `/blog/{slug}` - Blog detail
- `/contact` - Contact form
- `POST /enquiry` - Submit enquiry form

### Admin Routes (prefix: /admin):
- `/admin/services` - CRUD Services
- `/admin/packages` - CRUD Packages
- `/admin/galleries` - CRUD Gallery
- `/admin/blogs` - CRUD Blogs
- `/admin/enquiries` - Manage Enquiries

## 🎨 Fitur-Fitur

### 1. **Services Management**
- Tambah, edit, hapus services
- Upload foto service
- Kategorisasi berdasarkan package
- Tampilan card responsive

### 2. **Packages Management**
- Manage wedding packages
- Deskripsi dan detail package
- Relationship dengan services

### 3. **Gallery Management**
- Upload dan manage foto gallery
- Filter berdasarkan kategori
- Lightbox untuk view foto
- Testimonial integration

### 4. **Blog Management**
- CRUD artikel blog
- Slug auto-generate
- Image upload
- Published/draft status
- Rich content support

### 5. **Enquiry Management**
- Form enquiry lengkap
- Status tracking (new, contacted, in_progress, completed, cancelled)
- Email dan phone validation
- AJAX submission support

## 📊 Database Schema

### Services Table:
```sql
- id (uuid, primary)
- name (string)
- package_id (uuid, foreign key)
- foto (string, nullable)
- description (text, nullable)
- timestamps
- soft deletes
```

### Packages Table:
```sql
- id (uuid, primary)
- name (string)
- description (text, nullable)
- timestamps
```

### Galleries Table:
```sql
- id (uuid, primary)
- title (string)
- image (string)
- description (text, nullable)
- category (string, nullable)
- order (integer, default 0)
- timestamps
- soft deletes
```

### Blogs Table:
```sql
- id (uuid, primary)
- title (string)
- slug (string, unique)
- image (string)
- excerpt (text, nullable)
- content (longtext)
- author (string, nullable)
- is_published (boolean, default false)
- published_at (timestamp, nullable)
- timestamps
- soft deletes
```

### Enquiries Table:
```sql
- id (uuid, primary)
- name (string)
- email (string)
- phone (string, nullable)
- wedding_date (string, nullable)
- wedding_type (string, nullable)
- guest_count (integer, nullable)
- message (text)
- status (enum: new, contacted, in_progress, completed, cancelled)
- timestamps
- soft deletes
```

## 🎯 Seeding Data (Optional)

Anda bisa membuat seeder untuk testing:

```bash
php artisan make:seeder PackageSeeder
php artisan make:seeder ServiceSeeder
php artisan make:seeder GallerySeeder
php artisan make:seeder BlogSeeder
```

Contoh PackageSeeder:
```php
Package::create([
    'name' => 'Beach Wedding',
    'description' => 'Beautiful beach wedding package',
]);
```

## 🔐 Authentication (Opsional)

Untuk menambah authentication ke admin routes:

1. Install Laravel Breeze/Jetstream:
```bash
composer require laravel/breeze --dev
php artisan breeze:install
```

2. Update routes/web.php:
```php
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Admin routes here
});
```

## 📝 Customization

### Mengubah Warna Theme:
Edit di layouts/app.blade.php dan file view lainnya:
```css
/* Primary Gold Color */
#D4AF37

/* Hover Color */
#B8941F
```

### Mengubah Font:
```css
/* Heading Font */
font-family: 'Playfair Display', serif;

/* Body Font */
font-family: 'Inter', sans-serif;
```

## 🖼️ Upload Images

Images akan disimpan di:
- Services: `storage/app/public/services/`
- Galleries: `storage/app/public/galleries/`
- Blogs: `storage/app/public/blogs/`

Pastikan sudah menjalankan `php artisan storage:link`

## 📧 Form Validation

Semua form sudah include validation:
- Required fields
- Email format
- File upload size limits (2MB)
- Image mime types (jpeg, png, jpg, webp)

## 🎨 Responsive Design

Semua halaman sudah responsive untuk:
- Desktop (1200px+)
- Tablet (768px - 1199px)
- Mobile (< 768px)

## 🐛 Troubleshooting

### Images tidak muncul:
```bash
php artisan storage:link
chmod -R 775 storage
```

### Migration error:
```bash
php artisan migrate:fresh
```

### Route tidak ditemukan:
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

## 📞 Support

Untuk pertanyaan atau bantuan, silakan buat issue di repository atau contact developer.

## 📄 License

This project is open-sourced software licensed under the MIT license.

---

**Happy Coding! 🎉**