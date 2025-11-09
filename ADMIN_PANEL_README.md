# Cambridge College Admin Panel

## 🎉 Admin Panel Documentation

### 📍 Access Information

**Admin Login URL:**
- Development: `http://localhost:8000/admin/login`
- Production: `https://yourdomain.com/admin/login`

**Default Credentials:**
- Email: `admin@admin.com`
- Password: `password`

---

## 🚀 Features

### ✅ Complete CRUD Operations for:

1. **Courses Management**
   - Create, Read, Update, Delete courses
   - Rich text editor (TinyMCE) for descriptions
   - Image upload for course thumbnails
   - Category and Level assignment
   - Featured courses option
   - Active/Inactive status

2. **Course Categories**
   - Manage course categories
   - Icon support (Font Awesome)
   - Auto-generated slugs
   - Course count per category

3. **Course Levels**
   - Manage education levels
   - Sortable order
   - Auto-generated slugs

4. **Pages**
   - Create dynamic pages
   - Full HTML editor (TinyMCE)
   - SEO fields (Meta Title, Meta Description)
   - Published/Draft status

5. **Success Stories**
   - Student testimonials
   - Rich text editor for stories
   - Image upload for student photos
   - Country field

6. **Contact Messages**
   - View all contact form submissions
   - Mark as read/unread
   - Filter by status
   - Reply via email

7. **Banners**
   - Homepage banner management
   - Image upload
   - Sortable order
   - Active/Inactive status
   - Link support

---

## 📊 Dashboard Features

- **Statistics Cards:**
  - Total Courses
  - Categories Count
  - Unread Messages
  - Success Stories

- **Recent Activity:**
  - Latest 5 courses
  - Recent contact messages

- **Notifications:**
  - Unread messages counter in navbar
  - Real-time updates

---

## 🛠️ Technical Stack

- **Backend:** Laravel 11
- **Frontend:** AdminLTE 3 (Bootstrap 4)
- **Rich Text Editor:** TinyMCE 6
- **Icons:** Font Awesome 6
- **Authentication:** Custom Admin Guard
- **File Storage:** Laravel Storage (public disk)

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       ├── Auth/
│   │       │   └── LoginController.php
│   │       ├── DashboardController.php
│   │       ├── CourseController.php
│   │       ├── CourseCategoryController.php
│   │       ├── CourseLevelController.php
│   │       ├── PageController.php
│   │       ├── SuccessStoryController.php
│   │       ├── ContactController.php
│   │       └── BannerController.php
│   └── Middleware/
│       ├── AdminMiddleware.php
│       └── RedirectIfAdmin.php
├── Models/
│   ├── Admin.php
│   ├── Course.php
│   ├── CourseCategory.php
│   ├── CourseLevel.php
│   ├── Page.php
│   ├── SuccessStory.php
│   ├── Contact.php
│   └── Banner.php

resources/
└── views/
    └── admin/
        ├── auth/
        │   └── login.blade.php
        ├── layouts/
        │   ├── app.blade.php
        │   ├── navbar.blade.php
        │   └── sidebar.blade.php
        ├── dashboard.blade.php
        ├── courses/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   └── edit.blade.php
        ├── categories/
        ├── levels/
        ├── pages/
        ├── stories/
        ├── contacts/
        └── banners/

routes/
└── admin.php
```

---

## 🔧 Setup Instructions

### 1. Database Migration
```bash
php artisan migrate:fresh --seed
```

### 2. Storage Link
```bash
php artisan storage:link
```

### 3. Run Server
```bash
php artisan serve
```

### 4. Access Admin Panel
Navigate to: `http://localhost:8000/admin/login`

---

## 📝 Usage Guide

### Creating a Course

1. Navigate to **Courses → Add New Course**
2. Fill in the required fields:
   - Title (required)
   - Slug (auto-generated or custom)
   - Short Description
   - Full Description (use rich text editor)
   - Select Category
   - Select Level
   - Duration
   - Mode (Online/Offline/Hybrid)
   - Fee
   - Upload Image
   - Set Status (Active/Inactive)
   - Mark as Featured (optional)
3. Click **Create Course**

### Managing Pages

1. Navigate to **Pages → Add New Page**
2. Use TinyMCE editor for full HTML content
3. Add SEO meta tags
4. Set publish status
5. Click **Create Page**

### Viewing Contact Messages

1. Navigate to **Messages**
2. Filter by All/Unread/Read
3. Click **View** to read message details
4. Message automatically marks as read when viewed
5. Reply via email or delete message

### Managing Banners

1. Navigate to **Banners → Add New Banner**
2. Upload banner image (recommended: 1920x600px)
3. Add title and subtitle
4. Set link URL (optional)
5. Set display order
6. Set active status
7. Click **Create Banner**

---

## 🔐 Security Features

- Separate authentication guard for admins
- Protected routes with middleware
- CSRF protection on all forms
- Password hashing
- Session management
- Secure file uploads

---

## 📱 Responsive Design

- Fully responsive AdminLTE layout
- Mobile-friendly navigation
- Touch-optimized controls
- Collapsible sidebar

---

## 🎨 Customization

### Changing Admin Logo
Edit: `resources/views/admin/layouts/sidebar.blade.php`
```php
<img src="YOUR_LOGO_URL" alt="Logo">
```

### Adding New Admin User
```bash
php artisan tinker
```
```php
App\Models\Admin::create([
    'name' => 'Admin Name',
    'email' => 'admin@example.com',
    'password' => bcrypt('password')
]);
```

### Changing Default Password
Edit: `database/seeders/AdminSeeder.php`

---

## 🐛 Troubleshooting

### Images not showing?
```bash
php artisan storage:link
```

### Permission denied on storage?
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Routes not working?
```bash
php artisan route:clear
php artisan cache:clear
php artisan config:clear
```

---

## 📞 Support

For issues or questions, please contact the development team.

---

## 📄 License

This admin panel is part of the Cambridge College project.

---

**Version:** 1.0.0  
**Last Updated:** November 2025  
**Developed with ❤️ using Laravel & AdminLTE**
