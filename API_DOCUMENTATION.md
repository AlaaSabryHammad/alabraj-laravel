# توثيق API - نظام إدارة شركة الأبراج للمقاولات

## معلومات عامة

**Base URL**: `http://your-domain.com/api`
**Authentication**: Bearer Token (JWT)
**Content-Type**: `application/json`
**Accept**: `application/json`

---

## 1. المصادقة (Authentication)

### 1.1 تسجيل الدخول
```http
POST /api/auth/login
```

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "تم تسجيل الدخول بنجاح",
  "data": {
    "user": {
      "id": 1,
      "name": "أحمد محمد",
      "email": "user@example.com",
      "role": "admin",
      "employee": {
        "id": 1,
        "position": "مدير",
        "department": "الإدارة",
        "photo": "http://domain.com/storage/photos/user.jpg"
      }
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "Bearer",
    "expires_in": 3600
  }
}
```

### 1.2 تسجيل الخروج
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "تم تسجيل الخروج بنجاح"
}
```

### 1.3 معلومات المستخدم الحالي
```http
GET /api/auth/me
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "أحمد محمد",
    "email": "user@example.com",
    "phone": "0501234567",
    "role": "admin",
    "employee": {
      "id": 1,
      "position": "مدير",
      "department": "الإدارة",
      "salary": 15000,
      "hire_date": "2020-01-15",
      "photo": "http://domain.com/storage/photos/user.jpg"
    }
  }
}
```

### 1.4 تحديث الملف الشخصي
```http
PUT /api/auth/profile
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "name": "أحمد محمد علي",
  "email": "newemail@example.com",
  "phone": "0501234567",
  "avatar": "base64_encoded_image" // Optional
}
```

### 1.5 تغيير كلمة المرور
```http
POST /api/auth/change-password
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "current_password": "oldpassword",
  "new_password": "newpassword",
  "new_password_confirmation": "newpassword"
}
```

---

## 2. لوحة التحكم (Dashboard)

### 2.1 إحصائيات لوحة التحكم
```http
GET /api/dashboard/statistics
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "equipment": {
      "total": 45,
      "in_use": 32,
      "available": 10,
      "maintenance": 3
    },
    "employees": {
      "total": 120,
      "active": 115,
      "on_leave": 5
    },
    "fuel": {
      "total_trucks": 2,
      "total_capacity": 45000,
      "current_quantity": 43008,
      "total_consumed": 1992
    },
    "documents": {
      "total": 156,
      "expiring_soon": 5,
      "expired": 2
    }
  }
}
```

### 2.2 الأنشطة الأخيرة
```http
GET /api/dashboard/recent-activities?limit=10
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "type": "equipment_created",
      "title": "إضافة معدة جديدة",
      "description": "تم إضافة معدة حفار جديد",
      "user": "أحمد محمد",
      "created_at": "2025-12-02 15:30:00",
      "icon": "tools",
      "color": "blue"
    },
    {
      "id": 2,
      "type": "fuel_consumption",
      "title": "تسجيل استهلاك محروقات",
      "description": "استهلاك 500 لتر ديزل",
      "user": "محمد علي",
      "created_at": "2025-12-02 14:20:00",
      "icon": "gas-station",
      "color": "orange"
    }
  ]
}
```

### 2.3 الرسوم البيانية - استهلاك المحروقات
```http
GET /api/dashboard/charts/fuel-consumption?period=monthly
Authorization: Bearer {token}
```

**Query Parameters:**
- `period`: daily, weekly, monthly, yearly

**Response (200):**
```json
{
  "success": true,
  "data": {
    "labels": ["يناير", "فبراير", "مارس", "أبريل", "مايو"],
    "datasets": [
      {
        "label": "ديزل",
        "data": [1200, 1500, 1800, 1600, 1400],
        "color": "#3b82f6"
      },
      {
        "label": "بنزين",
        "data": [300, 350, 400, 380, 320],
        "color": "#10b981"
      }
    ]
  }
}
```

---

## 3. إدارة المعدات (Equipment)

### 3.1 قائمة المعدات
```http
GET /api/equipment?page=1&per_page=20&status=in_use&type=حفار&search=keyword
Authorization: Bearer {token}
```

**Query Parameters:**
- `page`: رقم الصفحة (default: 1)
- `per_page`: عدد العناصر (default: 20)
- `status`: available, in_use, maintenance, out_of_order
- `type`: نوع المعدة
- `search`: البحث بالاسم أو الرمز

**Response (200):**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "ف ف ف 9632",
        "type": "حفار",
        "code": "EQ-001",
        "serial_number": "6532542",
        "status": "in_use",
        "location": {
          "id": 1,
          "name": "الموقع الرئيسي"
        },
        "driver": {
          "id": 5,
          "name": "السائق الثاني",
          "phone": "0501234567"
        },
        "purchase_date": "2020-05-15",
        "purchase_price": 250000,
        "images": [
          "http://domain.com/storage/equipment/eq1-1.jpg",
          "http://domain.com/storage/equipment/eq1-2.jpg"
        ],
        "fuel_truck": null,
        "internal_truck": null
      }
    ],
    "total": 45,
    "per_page": 20,
    "last_page": 3
  }
}
```

### 3.2 تفاصيل معدة
```http
GET /api/equipment/{id}
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "ف ف ف 9632",
    "type": "حفار",
    "code": "EQ-001",
    "serial_number": "6532542",
    "status": "in_use",
    "manufacturer": "كاتربيلر",
    "model": "320D",
    "purchase_date": "2020-05-15",
    "purchase_price": 250000,
    "warranty_expiry": "2023-05-15",
    "last_maintenance": "2025-11-20",
    "description": "حفار ثقيل للحفريات الكبيرة",
    "location": {
      "id": 1,
      "name": "الموقع الرئيسي",
      "address": "الرياض"
    },
    "driver": {
      "id": 5,
      "name": "السائق الثاني",
      "phone": "0501234567",
      "photo": "http://domain.com/storage/employees/driver.jpg"
    },
    "images": ["url1", "url2"],
    "fuel_consumptions": [
      {
        "id": 1,
        "fuel_type": "ديزل",
        "quantity": 500,
        "consumption_date": "2025-12-01",
        "approval_status": "approved"
      }
    ],
    "maintenances": [
      {
        "id": 1,
        "type": "صيانة دورية",
        "cost": 5000,
        "date": "2025-11-20",
        "notes": "تغيير زيت وفلاتر"
      }
    ]
  }
}
```

### 3.3 إضافة معدة جديدة
```http
POST /api/equipment
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (FormData):**
```
name: "معدة جديدة"
type: "حفار"
serial_number: "123456"
code: "EQ-999"
status: "available"
location_id: 1
driver_id: 5 (optional)
manufacturer: "كاتربيلر"
model: "320D"
purchase_date: "2025-12-02"
purchase_price: 250000
description: "وصف المعدة"
images[]: file1.jpg
images[]: file2.jpg
```

**Response (201):**
```json
{
  "success": true,
  "message": "تم إضافة المعدة بنجاح",
  "data": {
    "id": 46,
    "name": "معدة جديدة",
    "type": "حفار",
    ...
  }
}
```

### 3.4 تحديث معدة
```http
PUT /api/equipment/{id}
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "name": "اسم محدث",
  "status": "maintenance",
  "location_id": 2,
  "driver_id": 3
}
```

### 3.5 حذف معدة
```http
DELETE /api/equipment/{id}
Authorization: Bearer {token}
```

### 3.6 تعيين سائق لمعدة
```http
POST /api/equipment/{id}/assign-driver
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "driver_id": 5,
  "assigned_at": "2025-12-02 10:00:00"
}
```

### 3.7 نقل معدة لموقع آخر
```http
POST /api/equipment/{id}/move
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "location_id": 3,
  "moved_at": "2025-12-02 10:00:00",
  "notes": "نقل للموقع الجنوبي"
}
```

---

## 4. إدارة الموظفين (Employees)

### 4.1 قائمة الموظفين
```http
GET /api/employees?page=1&department=الإدارة&position=مدير&search=أحمد
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "أحمد محمد",
        "email": "ahmed@example.com",
        "phone": "0501234567",
        "national_id": "1234567890",
        "position": "مدير",
        "department": "الإدارة",
        "salary": 15000,
        "hire_date": "2020-01-15",
        "photo": "http://domain.com/storage/employees/photo.jpg",
        "status": "active"
      }
    ],
    "total": 120,
    "per_page": 20,
    "last_page": 6
  }
}
```

### 4.2 تفاصيل موظف
```http
GET /api/employees/{id}
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "أحمد محمد",
    "email": "ahmed@example.com",
    "phone": "0501234567",
    "national_id": "1234567890",
    "position": "مدير",
    "department": "الإدارة",
    "salary": 15000,
    "hire_date": "2020-01-15",
    "birth_date": "1985-05-10",
    "nationality": "سعودي",
    "marital_status": "متزوج",
    "working_hours": 8,
    "photo": "http://domain.com/storage/employees/photo.jpg",
    "national_id_photo": "http://domain.com/storage/employees/id.jpg",
    "passport_photo": "http://domain.com/storage/employees/passport.jpg",
    "driving_license_photo": "http://domain.com/storage/employees/license.jpg",
    "work_permit_photo": "http://domain.com/storage/employees/permit.jpg",
    "status": "active",
    "balance": {
      "total_salary": 180000,
      "total_paid": 150000,
      "balance": 30000
    },
    "attendance_summary": {
      "total_days": 250,
      "present_days": 240,
      "absent_days": 10
    }
  }
}
```

### 4.3 إضافة موظف جديد
```http
POST /api/employees
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (FormData):**
```
name: "موظف جديد"
email: "employee@example.com"
phone: "0501234567"
national_id: "1234567890"
position: "مهندس"
department: "الهندسة"
salary: 12000
hire_date: "2025-12-02"
photo: file.jpg (optional)
national_id_photo: file.jpg (optional)
passport_photo: file.jpg (optional)
```

### 4.4 تحديث بيانات موظف
```http
PUT /api/employees/{id}
Authorization: Bearer {token}
```

### 4.5 حذف موظف
```http
DELETE /api/employees/{id}
Authorization: Bearer {token}
```

### 4.6 سجل حضور الموظف
```http
GET /api/employees/{id}/attendance?from=2025-12-01&to=2025-12-31
Authorization: Bearer {token}
```

---

## 5. إدارة المحروقات (Fuel Management)

### 5.1 قائمة سيارات المحروقات
```http
GET /api/fuel/trucks
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 5,
      "equipment_id": 5,
      "name": "خ خ خ 4444",
      "type": "سيارة محروقات",
      "fuel_type": "diesel",
      "fuel_type_text": "ديزل",
      "capacity": 20000,
      "current_quantity": 19008,
      "remaining_quantity": 18908,
      "percentage": 95.04,
      "location": {
        "id": 1,
        "name": "الموقع الرئيسي"
      },
      "driver": {
        "id": 3,
        "name": "سائق المحروقات الاول",
        "phone": "0501234567"
      }
    }
  ]
}
```

### 5.2 تفاصيل سيارة محروقات
```http
GET /api/fuel/trucks/{id}
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "truck": {
      "id": 5,
      "name": "خ خ خ 4444",
      "fuel_type": "diesel",
      "fuel_type_text": "ديزل",
      "capacity": 20000,
      "current_quantity": 19008,
      "remaining_quantity": 18908,
      "percentage": 95.04
    },
    "distributions": [
      {
        "id": 1,
        "target_equipment": {
          "id": 3,
          "name": "ح ح ح 665"
        },
        "quantity": 100,
        "distribution_date": "2025-12-01",
        "approval_status": "approved",
        "distributed_by": "أحمد محمد",
        "notes": "توزيع عادي"
      }
    ],
    "consumptions": [
      {
        "id": 73,
        "equipment": {
          "id": 3,
          "name": "ح ح ح 665"
        },
        "quantity": 500,
        "fuel_type": "ديزل",
        "consumption_date": "2025-12-02",
        "approval_status": "approved",
        "recorded_by": "محمد علي"
      }
    ]
  }
}
```

### 5.3 إضافة توزيع محروقات
```http
POST /api/fuel/distributions
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "fuel_truck_id": 1,
  "target_equipment_id": 3,
  "quantity": 100,
  "distribution_date": "2025-12-02",
  "notes": "توزيع عادي"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "تم إضافة التوزيع بنجاح",
  "data": {
    "id": 10,
    "fuel_truck_id": 1,
    "target_equipment_id": 3,
    "quantity": 100,
    "distribution_date": "2025-12-02",
    "approval_status": "pending"
  }
}
```

### 5.4 تسجيل استهلاك محروقات
```http
POST /api/fuel/consumptions
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "equipment_id": 3,
  "fuel_truck_id": 5,
  "fuel_type": "diesel",
  "quantity": 50,
  "consumption_date": "2025-12-02",
  "notes": "استهلاك يومي"
}
```

### 5.5 الموافقة على توزيع/استهلاك
```http
POST /api/fuel/distributions/{id}/approve
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "تمت الموافقة على التوزيع"
}
```

### 5.6 رفض توزيع/استهلاك
```http
POST /api/fuel/distributions/{id}/reject
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "reason": "كمية غير صحيحة"
}
```

### 5.7 تقرير استهلاك المحروقات
```http
GET /api/fuel/consumption-report?start_date=2025-12-01&end_date=2025-12-31
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "summary": {
      "total_consumption": 10500,
      "by_fuel_type": {
        "ديزل": 8500,
        "بنزين": 2000
      },
      "total_records": 45,
      "approved_records": 40,
      "pending_records": 3,
      "rejected_records": 2
    },
    "consumptions": [
      {
        "id": 1,
        "equipment_name": "ف ف ف 9632",
        "fuel_truck_name": "خ خ خ 4444",
        "fuel_type": "ديزل",
        "quantity": 500,
        "consumption_date": "2025-12-02",
        "approval_status": "approved",
        "recorded_by": "أحمد محمد"
      }
    ]
  }
}
```

### 5.8 إضافة كمية محروقات لسيارة
```http
POST /api/fuel/trucks/{id}/refill
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "quantity": 5000,
  "refill_date": "2025-12-02",
  "supplier": "محطة الوقود المركزية",
  "cost": 7500,
  "notes": "تعبئة كاملة"
}
```

---

## 6. إدارة المستندات (Documents)

### 6.1 قائمة المستندات
```http
GET /api/documents?page=1&type=عقود&status=active&search=keyword
Authorization: Bearer {token}
```

**Query Parameters:**
- `type`: عقود, فواتير, تراخيص, شهادات
- `status`: active, expiring, expired

**Response (200):**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "السجل التجاري",
        "type": "شهادة",
        "description": "السجل التجاري للشركة",
        "file_path": "documents/1234567_file.pdf",
        "file_url": "http://domain.com/storage/documents/1234567_file.pdf",
        "file_size": 2048576,
        "tags": ["رسمي", "مهم"],
        "expiry_date": "2026-01-01",
        "status": "active",
        "days_until_expiry": 30,
        "uploaded_by": "أحمد محمد",
        "created_at": "2025-12-01 10:00:00"
      }
    ],
    "total": 156,
    "per_page": 20,
    "last_page": 8
  }
}
```

### 6.2 تفاصيل مستند
```http
GET /api/documents/{id}
Authorization: Bearer {token}
```

### 6.3 رفع مستند جديد
```http
POST /api/documents
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (FormData):**
```
title: "عقد جديد"
type: "عقود"
description: "وصف المستند"
tags: "مهم,عاجل" (comma separated)
expiry_date: "2026-12-31" (optional)
file: file.pdf
```

**Response (201):**
```json
{
  "success": true,
  "message": "تم رفع المستند بنجاح",
  "data": {
    "id": 157,
    "title": "عقد جديد",
    "type": "عقود",
    "file_url": "http://domain.com/storage/documents/file.pdf"
  }
}
```

### 6.4 تحديث مستند
```http
PUT /api/documents/{id}
Authorization: Bearer {token}
```

### 6.5 حذف مستند
```http
DELETE /api/documents/{id}
Authorization: Bearer {token}
```

### 6.6 تحميل ملف المستند
```http
GET /api/documents/{id}/download
Authorization: Bearer {token}
```

**Response**: File download

### 6.7 إحصائيات المستندات
```http
GET /api/documents/statistics
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "total": 156,
    "by_type": {
      "عقود": 45,
      "فواتير": 60,
      "تراخيص": 25,
      "شهادات": 26
    },
    "expiring_soon": 5,
    "expired": 2,
    "this_month": 12
  }
}
```

---

## 7. الملف الشخصي (Profile)

### 7.1 المعدات المسجلة للمستخدم
```http
GET /api/profile/equipment
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "ف ف ف 9632",
      "type": "حفار",
      "status": "in_use",
      "code": "EQ-001",
      "serial_number": "6532542",
      "location": {
        "id": 1,
        "name": "الموقع الرئيسي"
      },
      "driver": {
        "id": 5,
        "name": "السائق الثاني"
      },
      "fuel_truck": null,
      "internal_truck": null
    }
  ]
}
```

---

## 8. الإشعارات (Notifications)

### 8.1 قائمة الإشعارات
```http
GET /api/notifications?page=1&unread=true
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "توزيع محروقات جديد",
        "body": "تم إضافة توزيع محروقات بحاجة للموافقة",
        "type": "fuel_distribution",
        "data": {
          "distribution_id": 10,
          "quantity": 100
        },
        "read_at": null,
        "created_at": "2025-12-02 15:30:00"
      }
    ],
    "unread_count": 5,
    "total": 50
  }
}
```

### 8.2 تحديد إشعار كمقروء
```http
POST /api/notifications/{id}/read
Authorization: Bearer {token}
```

### 8.3 تحديد جميع الإشعارات كمقروءة
```http
POST /api/notifications/read-all
Authorization: Bearer {token}
```

### 8.4 حذف إشعار
```http
DELETE /api/notifications/{id}
Authorization: Bearer {token}
```

---

## 9. الإعدادات (Settings)

### 9.1 المواقع
```http
GET /api/settings/locations
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "الموقع الرئيسي",
      "address": "الرياض",
      "latitude": 24.7136,
      "longitude": 46.6753
    }
  ]
}
```

### 9.2 أنواع المعدات
```http
GET /api/settings/equipment-types
Authorization: Bearer {token}
```

---

## 10. معالجة الأخطاء

### أكواد الأخطاء الشائعة:

**401 Unauthorized**
```json
{
  "success": false,
  "message": "غير مصرح بالوصول",
  "error": "Unauthenticated"
}
```

**403 Forbidden**
```json
{
  "success": false,
  "message": "ليس لديك صلاحية للقيام بهذا الإجراء"
}
```

**404 Not Found**
```json
{
  "success": false,
  "message": "العنصر المطلوب غير موجود"
}
```

**422 Validation Error**
```json
{
  "success": false,
  "message": "خطأ في البيانات المدخلة",
  "errors": {
    "email": ["البريد الإلكتروني مطلوب"],
    "password": ["كلمة المرور يجب أن تكون 8 أحرف على الأقل"]
  }
}
```

**500 Server Error**
```json
{
  "success": false,
  "message": "حدث خطأ في الخادم",
  "error": "Internal Server Error"
}
```

---

## 11. Pagination

جميع endpoints التي تدعم الـ pagination تُرجع البنية التالية:

```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [...],
    "first_page_url": "http://domain.com/api/endpoint?page=1",
    "from": 1,
    "last_page": 5,
    "last_page_url": "http://domain.com/api/endpoint?page=5",
    "next_page_url": "http://domain.com/api/endpoint?page=2",
    "path": "http://domain.com/api/endpoint",
    "per_page": 20,
    "prev_page_url": null,
    "to": 20,
    "total": 100
  }
}
```

---

## 12. معدلات الاستخدام (Rate Limiting)

- **المصادقة**: 5 طلبات في الدقيقة
- **API العامة**: 60 طلب في الدقيقة
- **رفع الملفات**: 10 طلبات في الساعة

**Response عند تجاوز الحد:**
```json
{
  "success": false,
  "message": "تجاوزت الحد المسموح من الطلبات",
  "retry_after": 45
}
```

---

## 13. ملاحظات مهمة

1. **التاريخ والوقت**: جميع التواريخ بصيغة `Y-m-d H:i:s` (مثال: 2025-12-02 15:30:00)
2. **الترميز**: جميع النصوص بترميز UTF-8
3. **الصور**: الحد الأقصى للصورة 5MB
4. **الملفات**: الحد الأقصى للملف 10MB
5. **Token Expiry**: مدة صلاحية الـ token 1 ساعة (3600 ثانية)
6. **Refresh Token**: يجب طلب token جديد قبل انتهاء الصلاحية

---

**© 2025 شركة الأبراج للمقاولات - API Documentation**
