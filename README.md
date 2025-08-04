# Alabraj Construction Management System

A comprehensive Laravel-based construction management system designed for construction companies to manage projects, equipment, employees, and operations efficiently.

## 🏗️ Features

### Core Modules
- **Projects Management**: Complete project lifecycle management with items, costs, and documents
- **Equipment Management**: Track construction equipment, maintenance, and assignments  
- **Employee Management**: HR system with attendance, payroll, and performance tracking
- **Finance Management**: Financial transactions and budget tracking
- **Transport Management**: Vehicle and logistics coordination
- **Document Management**: Centralized file and document storage

### Advanced Features
- **Project Items System**: Detailed cost breakdown with tax calculations
- **Arabic Number Conversion**: Convert amounts to Arabic text (تفقيط)
- **Print-Friendly Layouts**: Professional reports and documents
- **Image Management**: Upload and manage project/equipment photos
- **Role-Based Access**: Manager and employee permission levels
- **Real-time Calculations**: Automatic cost and tax computations

## 🚀 Technology Stack

- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL
- **File Storage**: Laravel Storage System
- **Icons**: Remix Icons
- **Styling**: Responsive design with Arabic RTL support

## 📋 Installation

1. Clone the repository:
```bash
git clone https://github.com/YOUR_USERNAME/alabraj-laravel.git
cd alabraj-laravel
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Environment setup:
```bash
cp .env.example .env
php artisan key:generate
```

4. Database configuration:
```bash
# Configure your database in .env file
php artisan migrate
php artisan db:seed
```

5. Storage setup:
```bash
php artisan storage:link
```

6. Build assets:
```bash
npm run build
```

## 🗄️ Database Structure

### Main Tables
- `projects` - Project information and management
- `project_items` - Detailed project cost items
- `equipment` - Construction equipment tracking
- `employees` - Staff and worker management
- `transports` - Vehicle and logistics
- `finances` - Financial transactions
- `documents` - File and document storage

## 🔧 Configuration

### Default Credentials
- **Admin**: Check `TEST_ADMIN_CREDENTIALS.md` for login details
- **Database**: Configure in `.env` file
- **Storage**: Public disk for file uploads

### Key Settings
- Tax rate configuration (default: 15%)
- Arabic number conversion
- File upload limits
- Print layout customization

## 📊 Key Features

### Project Management
- Comprehensive project creation with multiple data sections
- Project items with quantity, unit price, and tax calculations
- File and image attachments
- Delivery request tracking
- Extract generation for reports

### Employee System
- Complete employee profiles with personal information
- Attendance tracking and reporting
- Payroll management with bonuses and deductions
- Performance ratings and evaluations
- Document management for employee files

### Equipment Tracking
- Equipment registration with technical specifications
- Driver assignment and history tracking
- Location and movement monitoring
- Maintenance scheduling and records
- Equipment type categorization

### Financial Management
- Transaction recording and categorization
- Budget tracking and reporting
- Tax calculations and compliance
- Cost center management

## 🖨️ Printing & Reports

- Professional print layouts for all modules
- Arabic text support in generated documents
- PDF-ready styling for official documents
- Customizable report templates

## 🌐 Localization

- Arabic RTL interface support
- Arabic number conversion (تفقيط)
- Bilingual form labels and content
- Cultural date and number formatting

## 🔐 Security Features

- Role-based access control
- Password change enforcement
- Session management
- File upload validation
- CSRF protection

## 🛠️ Development

### Project Structure
```
app/
├── Http/Controllers/    # Application controllers
├── Models/             # Eloquent models
└── Http/Middleware/    # Custom middleware

resources/views/        # Blade templates
├── projects/          # Project management views
├── equipment/         # Equipment management views
├── employees/         # Employee management views
└── layouts/          # Layout templates

database/
├── migrations/        # Database migrations
└── seeders/          # Data seeders
```

### Contributing
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## 📝 License

This project is proprietary software developed for Alabraj Construction Company.

## 📞 Support

For technical support or feature requests, please contact the development team.

---

**Alabraj Construction Management System** - Streamlining construction operations with modern technology.
- **قاعدة البيانات**: جداول الموظفين والمعدات والمشاريع والمستندات والنقليات

### 🔄 قيد التطوير:
- إدارة المعدات
- إدارة المستندات  
- حركة النقليات
- المالية والفواتير
- إدارة المشاريع
- متابعة الحضور والانصراف

## التقنيات المستخدمة

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates + Tailwind CSS
- **قاعدة البيانات**: SQLite (افتراضي)
- **الرموز**: Remix Icons
- **المخططات**: Chart.js
- **الخطوط**: Google Fonts (Tajawal)

## التثبيت والتشغيل

### المتطلبات:
- PHP 8.2 أو أحدث
- Composer
- Node.js (اختياري)

### خطوات التشغيل:

1. **تثبيت التبعيات**:
```bash
composer install
```

2. **إعداد ملف البيئة**:
```bash
cp .env.example .env
php artisan key:generate
```

3. **تشغيل قاعدة البيانات**:
```bash
php artisan migrate
php artisan db:seed --class=EmployeeSeeder
```

4. **تشغيل الخادم**:
```bash
php artisan serve
```

5. **فتح التطبيق**: http://127.0.0.1:8000

## هيكل المشروع

```
/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php
│   │   ├── EmployeeController.php
│   │   ├── EquipmentController.php
│   │   ├── DocumentController.php
│   │   ├── TransportController.php
│   │   ├── FinanceController.php
│   │   └── ProjectController.php
│   └── Models/
│       ├── Employee.php
│       ├── Equipment.php
│       ├── Project.php
│       ├── Transport.php
│       └── Document.php
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   └── sidebar.blade.php
│   ├── dashboard/
│   │   └── index.blade.php
│   └── employees/
│       └── index.blade.php
└── routes/
    └── web.php
```

## مقارنة مع النسخة السابقة (Next.js)

| الميزة | Next.js | Laravel |
|--------|---------|---------|
| النوع | SPA | Full-Stack |
| البيانات | Mock Data | قاعدة بيانات حقيقية |
| التوجيه | Client-side | Server-side |
| الحالة | React State | Session/Database |
| الأمان | Client-side | Server-side |

## البيانات التجريبية

تم إنشاء 5 موظفين تجريبيين:
- أحمد محمد الأحمد (مدير المشاريع)
- فاطمة علي السالم (محاسبة رئيسية)
- خالد عبدالله الخالد (مهندس مدني)
- نورا سعد الدوسري (مديرة الموارد البشرية)
- محمد سالم القحطاني (فني معدات)

## الصفحات المتاحة

- `/` - لوحة التحكم الرئيسية
- `/employees` - إدارة الموظفين
- `/employees/create` - إضافة موظف جديد
- `/employees/attendance` - متابعة الحضور

## الميزات المحولة بالكامل

### 1. لوحة التحكم
- إحصائيات في الوقت الفعلي
- مخططات مالية تفاعلية
- النشاطات الأخيرة
- التوقيت والتاريخ

### 2. إدارة الموظفين
- قائمة الموظفين مع الترقيم
- إضافة موظف جديد
- تعديل بيانات الموظف
- حذف الموظف
- البحث والفلترة

## خطط التطوير المستقبلية

1. **إكمال باقي الصفحات**: المعدات، المستندات، النقليات، المالية، المشاريع
2. **نظام المصادقة**: تسجيل دخول وصلاحيات
3. **التقارير**: تصدير PDF وExcel  
4. **الإشعارات**: نظام تنبيهات فوري
5. **API**: إنشاء REST API
6. **تطبيق الجوال**: Flutter أو React Native

## المطور

تم التحويل من Next.js إلى Laravel بواسطة GitHub Copilot

---

*هذا النظام تم تطويره خصيصاً لشركة الأبراج للمقاولات العامة*
