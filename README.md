# 🚀 Web Pegawai - Sistem Manajemen Aktivitas Pegawai

Aplikasi berbasis **Laravel 11 + Filament v4.1 + Vite + FullCalendar + Spatie Permission** untuk mengelola:

* 👥 Data pegawai dan struktur organisasi
* 📅 Aktivitas pegawai dengan sistem persetujuan
* 📋 Peminjaman aset dan inventaris
* 📄 Manajemen dokumen digital
* 📊 Kalender aktivitas interaktif

---

## 📌 Features

* ✅ **Multi-role Authentication** (Admin, Manager, Employee)
* ✅ **CRUD Management**: Pegawai, Aktivitas, Aset, Peminjaman, Dokumen
* ✅ **Approval Workflow**: Sistem pengajuan dan persetujuan aktivitas
* ✅ **Interactive Calendar**: FullCalendar.js dengan color-coded status
* ✅ **Role-based Permissions**: Spatie Laravel Permission
* ✅ **Document Management**: Upload dan download dokumen
* ✅ **Responsive Design**: Mobile-friendly interface

---

## ⚡ Tech Stack

* **Backend**: Laravel 11.31
* **Admin Panel**: Filament v4.1.0
* **Frontend Build**: Vite 7.1.7
* **Database**: SQLite (development) / MySQL (production)
* **Authentication**: Laravel Sanctum + Spatie Permission
* **Calendar**: FullCalendar.js v6
* **Styling**: Tailwind CSS v4

---

## 📂 Project Structure

```
app/
  Models/                    # Eloquent Models
    Activity.php            # Aktivitas pegawai
    Employee.php            # Data pegawai
    Asset.php              # Inventaris aset
    Loan.php               # Peminjaman
    Document.php           # Dokumen
    User.php               # User authentication
  Filament/
    Resources/             # Filament CRUD Resources
    Pages/                 # Custom Filament Pages
      EmployeeActivityCalendar.php
resources/
  views/
    filament/pages/        # Filament custom views
  js/
    calendar.js            # FullCalendar implementation
  css/
    app.css               # Tailwind styles
database/
  migrations/             # Database schema
  seeders/               # Sample data
```

---

## 🔧 Requirements

* **PHP 8.3+** with extensions:
  * ext-intl, ext-zip, ext-bcmath
  * ext-curl, ext-mbstring, ext-xml
* **Composer 2.x**
* **Node.js 18+** with **NPM 9+**
* **SQLite** (development) or **MySQL 8+** (production)

---

## 🛠️ Installation

**1. Clone repository:**

```bash
git clone https://github.com/Rofiq02bae/web-pegawai.git
cd web-pegawai/web-pegawai
```

**2. Install PHP dependencies:**

```bash
composer install --ignore-platform-req=ext-zip
```

**3. Install Node.js dependencies:**

```bash
npm install
```

**4. Setup environment:**

```bash
cp .env.example .env
php artisan key:generate
```

**5. Setup database & permissions:**

```bash
php artisan migrate
php artisan db:seed --class=PermissionSeeder
```

**6. Build frontend assets:**

```bash
npm run build
# or for development
npm run dev
```

**7. Start development server:**

```bash
php artisan serve
```

---

## 👥 Default Test Users

* **🔑 Admin**
  * Email: `admin@test.com`
  * Password: `password`
  * Access: Full system access

* **👔 Manager**  
  * Email: `manager@test.com`
  * Password: `password`
  * Access: Approve activities, view reports

* **👤 Employee**
  * Email: `employee@test.com`
  * Password: `password`
  * Access: Create activities, view calendar

**Login URL:** `http://localhost:8000/admin`

---

## 🎯 Key Features Detail

### 📅 **Employee Activity Calendar**
- Interactive FullCalendar.js integration
- Color-coded activity status (Pending/Approved/Rejected)
- Click events for activity details
- Monthly/weekly view navigation

### 🔐 **Role-based Access Control**
- **Admin**: Full system management
- **Manager**: Activity approval, employee oversight
- **Employee**: Activity submission, calendar view

### 📋 **Activity Management**
- Create, edit, delete activities
- Approval workflow with status tracking
- Document attachment support
- Activity history and logs

### 🏢 **Asset & Loan Management**
- Asset inventory tracking
- Loan request and approval system
- Asset availability monitoring

---

## 🚦 Development Workflow

1. **Setup**: `composer install --ignore-platform-req=ext-zip && npm install`
2. **Database**: `php artisan migrate && php artisan db:seed --class=PermissionSeeder`
3. **Build**: `npm run build` atau `npm run dev`
4. **Serve**: `php artisan serve`
5. **Access**: `http://localhost:8000/admin`

---

## 📊 Roadmap & Future Features

### ✅ **Completed**
- [x] Filament 4.1 admin panel setup
- [x] Spatie Permission integration
- [x] FullCalendar implementation
- [x] Activity CRUD with approval workflow
- [x] Employee management system
- [x] Role-based authentication

### 🚧 **In Progress**
- [ ] PDF report generation (DomPDF)
- [ ] Excel export functionality
- [ ] Advanced dashboard statistics

### 📋 **Planned**
- [ ] Email notifications for approvals
- [ ] Advanced reporting & analytics
- [ ] Multi-tenant support
- [ ] Integration with external HR systems

---

## 🛠️ Troubleshooting

### **Calendar tidak muncul?**
```bash
# Clear cache dan rebuild assets
php artisan cache:clear
php artisan config:clear
npm run build
```

### **Permission errors?**
```bash
# Install PHP extensions
sudo apt install php8.3-zip php8.3-intl php8.3-bcmath
# Atau install dengan ignore platform requirements
composer install --ignore-platform-req=ext-zip
```

### **Database migration errors?**
```bash
# Reset database
php artisan migrate:fresh
php artisan db:seed --class=PermissionSeeder
```

### **Filament login issues?**
- Default admin: `admin@test.com` / `password`
- Check if user has proper roles assigned
- Verify Filament panel URL: `/admin`

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. **Fork** the repository
2. **Create** feature branch: `git checkout -b feat/amazing-feature`
3. **Commit** changes: `git commit -m "feat: add amazing feature"`
4. **Push** to branch: `git push origin feat/amazing-feature`
5. **Open** a Pull Request

### **Commit Message Convention:**
- `feat:` new features
- `fix:` bug fixes
- `docs:` documentation updates
- `style:` formatting, missing semicolons
- `refactor:` code restructuring
- `test:` adding tests
- `chore:` maintenance tasks

---

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/Rofiq02bae/web-pegawai/issues)
- **Discussions**: [GitHub Discussions](https://github.com/Rofiq02bae/web-pegawai/discussions)
- **Email**: [Your Email Here]

---

## 📜 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Filament](https://filamentphp.com) - Beautiful admin panels
- [FullCalendar](https://fullcalendar.io) - JavaScript calendar
- [Spatie Permission](https://spatie.be/docs/laravel-permission) - Role management
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework

---

**⭐ If this project helps you, please give it a star!**
