# 📚 API & CRUD Documentation

## 🎯 Overview

Dokumentasi lengkap untuk semua CRUD operations yang tersedia di aplikasi Intimate Bali Wedding.

---

## 🔗 Base URL

```
Development: http://localhost:8000
Production: https://your-domain.com
```

---

## 📦 PACKAGES

### List All Packages
```
GET /admin/packages
```

**Response:**
```php
// Returns paginated list of packages with services count
```

### Show Package
```
GET /admin/packages/{id}
```

### Create Package
```
GET /admin/packages/create (form)
POST /admin/packages (submit)
```

**Parameters:**
- `name` (required, string, max:255)
- `description` (optional, text)

**Example:**
```php
[
    'name' => 'Beach Wedding',
    'description' => 'Beautiful beach wedding package'
]
```

### Update Package
```
GET /admin/packages/{id}/edit (form)
PUT /admin/packages/{id} (submit)
```

**Parameters:** Same as Create

### Delete Package
```
DELETE /admin/packages/{id}
```

**Note:** Cannot delete if package has services

---

## 🎨 SERVICES

### List All Services
```
GET /admin/services
```

**Response:**
```php
// Returns paginated services with package relationship
```

### Show Service
```
GET /admin/services/{id}
```

### Create Service
```
GET /admin/services/create (form)
POST /admin/services (submit)
```

**Parameters:**
- `name` (required, string, max:255)
- `package_id` (required, uuid, exists in packages)
- `foto` (optional, image, max:2MB, types: jpeg,png,jpg,webp)
- `description` (optional, text)

**Example:**
```php
[
    'name' => 'Beachfront Ceremony',
    'package_id' => '9c8a1234-5678-90ab-cdef-1234567890ab',
    'foto' => UploadedFile,
    'description' => 'Beautiful beachfront ceremony setup'
]
```

### Update Service
```
GET /admin/services/{id}/edit (form)
PUT /admin/services/{id} (submit)
```

**Parameters:** Same as Create (foto is optional on update)

### Delete Service
```
DELETE /admin/services/{id}
```

**Note:** Will also delete the uploaded image file

---

## 🖼️ GALLERY

### List All Galleries
```
GET /admin/galleries
```

**Response:**
```php
// Returns paginated galleries ordered by 'order' field
```

### Show Gallery Item
```
GET /admin/galleries/{id}
```

### Create Gallery Item
```
GET /admin/galleries/create (form)
POST /admin/galleries (submit)
```

**Parameters:**
- `title` (required, string, max:255)
- `image` (required, image, max:2MB, types: jpeg,png,jpg,webp)
- `description` (optional, text)
- `category` (optional, string, max:255)
- `order` (optional, integer)

**Example:**
```php
[
    'title' => 'Beach Ceremony',
    'image' => UploadedFile,
    'description' => 'Beautiful beach ceremony',
    'category' => 'Beach Wedding',
    'order' => 1
]
```

### Update Gallery Item
```
GET /admin/galleries/{id}/edit (form)
PUT /admin/galleries/{id} (submit)
```

**Parameters:** Same as Create (image is optional on update)

### Delete Gallery Item
```
DELETE /admin/galleries/{id}
```

**Note:** Will also delete the uploaded image file

---

## 📝 BLOGS

### List All Blogs
```
GET /admin/blogs
```

**Response:**
```php
// Returns paginated blogs ordered by published_at
```

### Show Blog
```
GET /admin/blogs/{id} (admin)
GET /blog/{slug} (public)
```

### Create Blog Post
```
GET /admin/blogs/create (form)
POST /admin/blogs (submit)
```

**Parameters:**
- `title` (required, string, max:255)
- `image` (required, image, max:2MB, types: jpeg,png,jpg,webp)
- `excerpt` (optional, text)
- `content` (required, text)
- `author` (optional, string, max:255)
- `is_published` (optional, boolean)
- `published_at` (optional, date)

**Example:**
```php
[
    'title' => 'Best Time for Bali Wedding',
    'image' => UploadedFile,
    'excerpt' => 'Discover the perfect time...',
    'content' => 'Full article content here...',
    'author' => 'Wedding Planner Team',
    'is_published' => true,
    'published_at' => '2024-02-01'
]
```

**Note:** Slug is auto-generated from title

### Update Blog Post
```
GET /admin/blogs/{id}/edit (form)
PUT /admin/blogs/{id} (submit)
```

**Parameters:** Same as Create (image is optional on update)

### Delete Blog Post
```
DELETE /admin/blogs/{id}
```

**Note:** Will also delete the uploaded image file

---

## 📧 ENQUIRIES

### List All Enquiries
```
GET /admin/enquiries
```

**Response:**
```php
// Returns paginated enquiries ordered by latest
```

### Show Enquiry
```
GET /admin/enquiries/{id}
```

### Create Enquiry (Public Form)
```
POST /enquiry
```

**Parameters:**
- `name` (required, string, max:255)
- `email` (required, email, max:255)
- `phone` (optional, string, max:255)
- `wedding_date` (optional, string, max:255)
- `wedding_type` (optional, string, max:255)
- `guest_count` (optional, integer)
- `message` (required, text)

**Example:**
```php
[
    'name' => 'John & Jane Doe',
    'email' => 'john@example.com',
    'phone' => '+62 812 3456 7890',
    'wedding_date' => '2024-12-25',
    'wedding_type' => 'Beach Wedding',
    'guest_count' => 50,
    'message' => 'We would like to inquire about...'
]
```

**Response (Success):**
```json
{
    "success": true,
    "message": "Thank you for your enquiry. We will contact you soon!"
}
```

### Update Enquiry Status
```
GET /admin/enquiries/{id}/edit (form)
PUT /admin/enquiries/{id} (submit)
```

**Parameters:**
- All create parameters plus:
- `status` (required, enum: new, contacted, in_progress, completed, cancelled)

### Delete Enquiry
```
DELETE /admin/enquiries/{id}
```

---

## 🎨 Frontend Routes

### Public Pages
```
GET /                    # Homepage
GET /about              # About Us page
GET /services           # Services listing
GET /packages           # Packages listing
GET /gallery            # Gallery with filters
GET /blog               # Blog listing
GET /blog/{slug}        # Blog detail
GET /contact            # Contact form
```

---

## 📤 File Upload Specifications

### Image Upload Limits:
- **Max Size:** 2048 KB (2 MB)
- **Allowed Types:** jpeg, png, jpg, webp
- **Storage Path:** `storage/app/public/{module}/`

### Upload Destinations:
```
Services: storage/app/public/services/
Galleries: storage/app/public/galleries/
Blogs: storage/app/public/blogs/
```

### Accessing Uploaded Files:
```php
// In views
{{ asset('storage/services/' . $filename) }}

// In controllers
Storage::disk('public')->delete($filepath);
```

---

## 🔒 Validation Rules

### Common Rules:
```php
// String Fields
'field' => 'required|string|max:255'

// Text Fields
'field' => 'nullable|string'

// Email
'email' => 'required|email|max:255'

// Image Upload
'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'

// UUID Foreign Key
'package_id' => 'required|exists:packages,id'

// Enum
'status' => 'required|in:new,contacted,in_progress,completed,cancelled'

// Boolean
'is_published' => 'boolean'

// Integer
'guest_count' => 'nullable|integer'

// Date
'published_at' => 'nullable|date'
```

---

## 💾 Database Relationships

### Package Model:
```php
public function services()
{
    return $this->hasMany(Service::class);
}
```

### Service Model:
```php
public function package()
{
    return $this->belongsTo(Package::class);
}
```

---

## 🧪 Testing CRUD Operations

### Using Browser:

1. **Create:**
   - Visit `/admin/packages/create`
   - Fill form
   - Submit
   - Check redirect to `/admin/packages`

2. **Read:**
   - Visit `/admin/packages`
   - Click on a package
   - See details at `/admin/packages/{id}`

3. **Update:**
   - Visit `/admin/packages`
   - Click Edit button
   - Modify data
   - Submit
   - Check redirect with success message

4. **Delete:**
   - Visit `/admin/packages`
   - Click Delete button
   - Confirm deletion
   - Check redirect with success message

### Using Postman/Insomnia:

```bash
# Create Package
POST http://localhost:8000/admin/packages
Content-Type: application/x-www-form-urlencoded

name=Beach Wedding&description=Beautiful beach wedding

# Update Package
PUT http://localhost:8000/admin/packages/{id}
Content-Type: application/x-www-form-urlencoded

name=Updated Beach Wedding&description=Updated description

# Delete Package
DELETE http://localhost:8000/admin/packages/{id}
```

---

## 🔍 Query Examples

### Get Packages with Services Count:
```php
$packages = Package::withCount('services')->get();
```

### Get Services with Package:
```php
$services = Service::with('package')->get();
```

### Get Published Blogs:
```php
$blogs = Blog::where('is_published', true)
             ->orderBy('published_at', 'desc')
             ->get();
```

### Get Galleries by Category:
```php
$galleries = Gallery::where('category', 'Beach Wedding')
                    ->orderBy('order')
                    ->get();
```

### Get New Enquiries:
```php
$enquiries = Enquiry::where('status', 'new')
                    ->latest()
                    ->get();
```

---

## 📊 Response Formats

### Success Response:
```php
return redirect()->route('packages.index')
    ->with('success', 'Package created successfully.');
```

### Error Response:
```php
return redirect()->back()
    ->with('error', 'Cannot delete package with existing services.')
    ->withInput();
```

### Validation Errors:
```php
// Automatically handled by Laravel
// Returns to previous page with errors
// Access in blade: @error('fieldname')
```

---

## 🎯 Best Practices

1. **Always validate input:**
   ```php
   $validated = $request->validate([...]);
   ```

2. **Use mass assignment protection:**
   ```php
   protected $fillable = ['name', 'description'];
   ```

3. **Handle file uploads safely:**
   ```php
   if ($request->hasFile('image')) {
       $path = $request->file('image')->store('galleries', 'public');
   }
   ```

4. **Delete old files on update:**
   ```php
   if ($model->image) {
       Storage::disk('public')->delete($model->image);
   }
   ```

5. **Use soft deletes:**
   ```php
   use SoftDeletes;
   ```

6. **Eager load relationships:**
   ```php
   $services = Service::with('package')->get();
   ```

---

## 🚀 Performance Tips

1. **Pagination:**
   ```php
   $items = Model::paginate(12);
   ```

2. **Eager Loading:**
   ```php
   $packages = Package::with('services')->get();
   ```

3. **Query Optimization:**
   ```php
   // Instead of
   foreach($services as $service) {
       echo $service->package->name;
   }
   
   // Use
   $services = Service::with('package')->get();
   ```

4. **Image Optimization:**
   - Resize images before upload
   - Use WebP format
   - Implement lazy loading

---

## 📞 Support

For issues or questions about the API:
- Check Laravel logs: `storage/logs/laravel.log`
- Enable debug mode: `APP_DEBUG=true`
- Check database queries with Laravel Debugbar

---

**Happy Coding! 🎉**