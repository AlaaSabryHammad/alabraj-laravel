# مواصفات تطبيق Flutter - نظام إدارة شركة الأبراج للمقاولات

## نظرة عامة

تطبيق جوال شامل لإدارة شركة الأبراج للمقاولات باستخدام Flutter، يوفر جميع وظائف نظام Laravel الموجود مع واجهة مستخدم حديثة ومتجاوبة.

---

## 1. الهوية البصرية والتصميم

### 1.1 الألوان الأساسية

```dart
class AppColors {
  // Primary Colors
  static const Color primary = Color(0xFF1e3c72);
  static const Color primaryDark = Color(0xFF2a5298);
  static const Color primaryLight = Color(0xFF3b5998);

  // Secondary Colors
  static const Color secondary = Color(0xFF667eea);
  static const Color secondaryDark = Color(0xFF764ba2);

  // Accent Colors
  static const Color accent = Color(0xFF3b82f6);
  static const Color accentLight = Color(0xFF60a5fa);

  // Status Colors
  static const Color success = Color(0xFF10b981);
  static const Color warning = Color(0xFFf59e0b);
  static const Color error = Color(0xFFef4444);
  static const Color info = Color(0xFF3b82f6);

  // Neutral Colors
  static const Color white = Color(0xFFFFFFFF);
  static const Color black = Color(0xFF000000);
  static const Color gray50 = Color(0xFFf9fafb);
  static const Color gray100 = Color(0xFFf3f4f6);
  static const Color gray200 = Color(0xFFe5e7eb);
  static const Color gray300 = Color(0xFFd1d5db);
  static const Color gray400 = Color(0xFF9ca3af);
  static const Color gray500 = Color(0xFF6b7280);
  static const Color gray600 = Color(0xFF4b5563);
  static const Color gray700 = Color(0xFF374151);
  static const Color gray800 = Color(0xFF1f2937);
  static const Color gray900 = Color(0xFF111827);
}
```

### 1.2 التايبوجرافي (الخطوط)

```dart
class AppTypography {
  static const String fontFamily = 'Cairo'; // خط عربي احترافي

  // Text Styles
  static const TextStyle h1 = TextStyle(
    fontFamily: fontFamily,
    fontSize: 32,
    fontWeight: FontWeight.bold,
    color: AppColors.gray900,
  );

  static const TextStyle h2 = TextStyle(
    fontFamily: fontFamily,
    fontSize: 24,
    fontWeight: FontWeight.bold,
    color: AppColors.gray900,
  );

  static const TextStyle h3 = TextStyle(
    fontFamily: fontFamily,
    fontSize: 20,
    fontWeight: FontWeight.bold,
    color: AppColors.gray900,
  );

  static const TextStyle body1 = TextStyle(
    fontFamily: fontFamily,
    fontSize: 16,
    fontWeight: FontWeight.normal,
    color: AppColors.gray700,
  );

  static const TextStyle body2 = TextStyle(
    fontFamily: fontFamily,
    fontSize: 14,
    fontWeight: FontWeight.normal,
    color: AppColors.gray600,
  );

  static const TextStyle caption = TextStyle(
    fontFamily: fontFamily,
    fontSize: 12,
    fontWeight: FontWeight.normal,
    color: AppColors.gray500,
  );

  static const TextStyle button = TextStyle(
    fontFamily: fontFamily,
    fontSize: 16,
    fontWeight: FontWeight.w600,
    color: AppColors.white,
  );
}
```

### 1.3 المسافات والأبعاد

```dart
class AppSpacing {
  static const double xs = 4.0;
  static const double sm = 8.0;
  static const double md = 16.0;
  static const double lg = 24.0;
  static const double xl = 32.0;
  static const double xxl = 48.0;

  static const double cardRadius = 16.0;
  static const double buttonRadius = 12.0;
  static const double inputRadius = 12.0;
}
```

---

## 2. البنية المعمارية

### 2.1 نمط البنية
استخدام **Clean Architecture** مع **BLoC Pattern** لإدارة الحالة

```
lib/
├── core/
│   ├── constants/
│   ├── theme/
│   ├── utils/
│   ├── widgets/
│   └── network/
├── features/
│   ├── auth/
│   │   ├── data/
│   │   ├── domain/
│   │   └── presentation/
│   ├── dashboard/
│   ├── equipment/
│   ├── employees/
│   ├── fuel_management/
│   ├── documents/
│   ├── profile/
│   └── [other features]/
└── main.dart
```

### 2.2 الحزم المطلوبة (pubspec.yaml)

```yaml
dependencies:
  flutter:
    sdk: flutter

  # State Management
  flutter_bloc: ^8.1.3
  equatable: ^2.0.5

  # Network
  dio: ^5.3.3
  retrofit: ^4.0.3
  json_annotation: ^4.8.1

  # Local Storage
  shared_preferences: ^2.2.2
  hive: ^2.2.3
  hive_flutter: ^1.1.0

  # UI Components
  flutter_svg: ^2.0.9
  cached_network_image: ^3.3.0
  shimmer: ^3.0.0
  lottie: ^2.7.0

  # Forms & Validation
  flutter_form_builder: ^9.1.1
  form_builder_validators: ^9.1.0

  # Date & Time
  intl: ^0.18.1
  persian_datetime_picker: ^2.6.0

  # File Handling
  image_picker: ^1.0.4
  file_picker: ^6.0.0
  path_provider: ^2.1.1

  # PDF
  pdf: ^3.10.7
  printing: ^5.11.1

  # Charts
  fl_chart: ^0.64.0
  syncfusion_flutter_charts: ^23.1.44

  # Navigation
  go_router: ^12.1.1

  # QR Code
  qr_flutter: ^4.1.0
  mobile_scanner: ^3.5.2

  # Localization
  flutter_localizations:
    sdk: flutter

  # Utilities
  get_it: ^7.6.4
  injectable: ^2.3.2
  logger: ^2.0.2
  connectivity_plus: ^5.0.1
  permission_handler: ^11.0.1

dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^3.0.0
  build_runner: ^2.4.6
  json_serializable: ^6.7.1
  retrofit_generator: ^8.0.4
  hive_generator: ^2.0.1
  injectable_generator: ^2.4.1
```

---

## 3. الوحدات الرئيسية (Features)

### 3.1 المصادقة والتسجيل (Authentication)

#### الشاشات:
1. **شاشة تسجيل الدخول** (`login_screen.dart`)
   - حقول: البريد الإلكتروني، كلمة المرور
   - زر تسجيل الدخول
   - رابط نسيت كلمة المرور
   - تصميم: خلفية بـ gradient أزرق، شعار الشركة في الأعلى

2. **شاشة نسيت كلمة المرور** (`forgot_password_screen.dart`)
   - حقل البريد الإلكتروني
   - زر إرسال رابط إعادة التعيين

#### API Endpoints:
```dart
@RestApi(baseUrl: "http://your-api.com/api/")
abstract class AuthApi {
  @POST("/login")
  Future<AuthResponse> login(@Body() LoginRequest request);

  @POST("/logout")
  Future<void> logout();

  @POST("/forgot-password")
  Future<void> forgotPassword(@Body() ForgotPasswordRequest request);
}
```

---

### 3.2 لوحة التحكم (Dashboard)

#### شاشة لوحة التحكم الرئيسية (`dashboard_screen.dart`)

**المكونات:**
1. **بطاقات الإحصائيات** (4 بطاقات في صف)
   - إجمالي المعدات
   - الموظفين النشطين
   - المشاريع الجارية
   - المستندات

2. **الرسوم البيانية**
   - رسم بياني لاستهلاك المحروقات (خط)
   - رسم بياني للمعدات حسب الحالة (دائري)
   - رسم بياني للمصروفات الشهرية (عمودي)

3. **قائمة الأنشطة الأخيرة**
   - آخر 5 أنشطة في النظام
   - أيقونات ملونة حسب نوع النشاط

**التصميم:**
```dart
class DashboardScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.gray50,
      appBar: AppBar(
        title: Text('لوحة التحكم', style: AppTypography.h2.copyWith(color: Colors.white)),
        flexibleSpace: Container(
          decoration: BoxDecoration(
            gradient: LinearGradient(
              colors: [AppColors.primary, AppColors.primaryDark],
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
          ),
        ),
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          // Refresh data
        },
        child: SingleChildScrollView(
          padding: EdgeInsets.all(AppSpacing.md),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Statistics Cards
              _buildStatisticsCards(),
              SizedBox(height: AppSpacing.lg),

              // Charts Section
              _buildChartsSection(),
              SizedBox(height: AppSpacing.lg),

              // Recent Activities
              _buildRecentActivities(),
            ],
          ),
        ),
      ),
    );
  }
}
```

---

### 3.3 إدارة المعدات (Equipment Management)

#### الشاشات:

1. **قائمة المعدات** (`equipment_list_screen.dart`)
   - عرض جميع المعدات في بطاقات
   - فلترة حسب النوع والحالة
   - بحث بالاسم أو الرقم التسلسلي
   - زر إضافة معدة جديدة

2. **تفاصيل المعدة** (`equipment_detail_screen.dart`)
   - معلومات المعدة الكاملة
   - السائق المعين
   - الموقع الحالي
   - سجل الصيانة
   - سجل استهلاك المحروقات
   - أزرار: تعديل، حذف، تعيين سائق، تغيير الموقع

3. **إضافة/تعديل معدة** (`equipment_form_screen.dart`)
   - نموذج شامل لبيانات المعدة
   - رفع صور المعدة
   - اختيار النوع والفئة
   - تحديد الموقع على الخريطة (اختياري)

**مثال كود البطاقة:**
```dart
class EquipmentCard extends StatelessWidget {
  final Equipment equipment;

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(AppSpacing.cardRadius),
      ),
      child: InkWell(
        onTap: () => Navigator.push(
          context,
          MaterialPageRoute(
            builder: (_) => EquipmentDetailScreen(equipment: equipment),
          ),
        ),
        borderRadius: BorderRadius.circular(AppSpacing.cardRadius),
        child: Padding(
          padding: EdgeInsets.all(AppSpacing.md),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Equipment Header
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(equipment.name, style: AppTypography.h3),
                        SizedBox(height: 4),
                        Text(equipment.type, style: AppTypography.body2),
                      ],
                    ),
                  ),
                  _buildStatusBadge(equipment.status),
                ],
              ),
              Divider(height: 24),

              // Equipment Info
              _buildInfoRow(Icons.qr_code, 'الرمز', equipment.code),
              SizedBox(height: 8),
              _buildInfoRow(Icons.location_on, 'الموقع', equipment.location?.name ?? 'غير محدد'),
              SizedBox(height: 8),
              _buildInfoRow(Icons.person, 'السائق', equipment.driver?.name ?? 'غير معين'),
            ],
          ),
        ),
      ),
    );
  }
}
```

---

### 3.4 إدارة الموظفين (Employees Management)

#### الشاشات:

1. **قائمة الموظفين** (`employees_list_screen.dart`)
   - عرض الموظفين مع صورهم
   - فلترة حسب القسم والوظيفة
   - بحث بالاسم أو رقم الهوية

2. **تفاصيل الموظف** (`employee_detail_screen.dart`)
   - المعلومات الشخصية
   - المعلومات الوظيفية
   - الصور والوثائق
   - سجل الحضور
   - رصيد الإجازات

3. **إضافة/تعديل موظف** (`employee_form_screen.dart`)
   - نموذج كامل للموظف
   - رفع الصور (شخصية، هوية، جواز، رخصة)

**نموذج البيانات:**
```dart
@HiveType(typeId: 1)
class Employee {
  @HiveField(0)
  final int id;

  @HiveField(1)
  final String name;

  @HiveField(2)
  final String? email;

  @HiveField(3)
  final String? phone;

  @HiveField(4)
  final String? nationalId;

  @HiveField(5)
  final String? position;

  @HiveField(6)
  final String? department;

  @HiveField(7)
  final double? salary;

  @HiveField(8)
  final DateTime? hireDate;

  @HiveField(9)
  final String? photoUrl;

  @HiveField(10)
  final String? nationalIdPhotoUrl;

  @HiveField(11)
  final String? passportPhotoUrl;

  Employee({
    required this.id,
    required this.name,
    this.email,
    this.phone,
    this.nationalId,
    this.position,
    this.department,
    this.salary,
    this.hireDate,
    this.photoUrl,
    this.nationalIdPhotoUrl,
    this.passportPhotoUrl,
  });
}
```

---

### 3.5 إدارة المحروقات (Fuel Management)

#### الشاشات:

1. **لوحة إدارة المحروقات** (`fuel_management_screen.dart`)
   - بطاقات الإحصائيات (إجمالي السيارات، السعة، الكمية الحالية، المستهلك)
   - بطاقات سيارات المحروقات
   - زر إضافة توزيع
   - زر تسجيل استهلاك

2. **تفاصيل سيارة المحروقات** (`fuel_truck_detail_screen.dart`)
   - معلومات السيارة
   - سجل التوزيعات
   - سجل الاستهلاك
   - رسم بياني للاستهلاك

3. **إضافة توزيع** (`add_distribution_screen.dart`)
   - اختيار سيارة المحروقات
   - اختيار المعدة المستلمة
   - إدخال الكمية
   - التاريخ والملاحظات

4. **تسجيل استهلاك** (`add_consumption_screen.dart`)
   - اختيار المعدة
   - اختيار سيارة المحروقات (تلقائي من السياق)
   - نوع الوقود
   - الكمية
   - التاريخ والملاحظات

5. **تقرير الاستهلاك** (`consumption_report_screen.dart`)
   - فلترة حسب التاريخ
   - جدول تفصيلي بالاستهلاك
   - إحصائيات حسب نوع الوقود
   - زر طباعة/تصدير PDF

**مثال Widget لبطاقة سيارة المحروقات:**
```dart
class FuelTruckCard extends StatelessWidget {
  final FuelTruck truck;

  @override
  Widget build(BuildContext context) {
    final percentage = (truck.currentQuantity / truck.capacity) * 100;

    return Card(
      elevation: 3,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(AppSpacing.cardRadius),
      ),
      child: InkWell(
        onTap: () => _showTruckDetails(context),
        borderRadius: BorderRadius.circular(AppSpacing.cardRadius),
        child: Container(
          padding: EdgeInsets.all(AppSpacing.md),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(AppSpacing.cardRadius),
            gradient: LinearGradient(
              colors: [Colors.blue.shade50, Colors.blue.shade100],
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Header
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(truck.equipmentName, style: AppTypography.h3),
                  Container(
                    padding: EdgeInsets.symmetric(
                      horizontal: 12,
                      vertical: 6,
                    ),
                    decoration: BoxDecoration(
                      color: AppColors.primary,
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      truck.fuelTypeText,
                      style: AppTypography.caption.copyWith(
                        color: Colors.white,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ],
              ),
              SizedBox(height: AppSpacing.md),

              // Info
              _buildInfoRow(Icons.storage, 'السعة', '${truck.capacity.toInt()} لتر'),
              SizedBox(height: 8),
              _buildInfoRow(Icons.local_gas_station, 'الكمية الحالية', '${truck.currentQuantity.toInt()} لتر'),
              SizedBox(height: 8),
              _buildInfoRow(Icons.trending_down, 'المتبقي', '${truck.remainingQuantity.toInt()} لتر'),
              SizedBox(height: AppSpacing.md),

              // Progress Bar
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('نسبة الامتلاء', style: AppTypography.caption),
                  SizedBox(height: 4),
                  LinearProgressIndicator(
                    value: percentage / 100,
                    backgroundColor: AppColors.gray200,
                    valueColor: AlwaysStoppedAnimation<Color>(
                      percentage > 50 ? AppColors.success :
                      percentage > 20 ? AppColors.warning :
                      AppColors.error,
                    ),
                    minHeight: 8,
                  ),
                  SizedBox(height: 4),
                  Text('${percentage.toStringAsFixed(1)}%', style: AppTypography.caption),
                ],
              ),
              SizedBox(height: AppSpacing.md),

              // Buttons
              Row(
                children: [
                  Expanded(
                    child: ElevatedButton.icon(
                      onPressed: () => _addDistribution(context),
                      icon: Icon(Icons.add, size: 18),
                      label: Text('إضافة كمية'),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.success,
                        foregroundColor: Colors.white,
                      ),
                    ),
                  ),
                  SizedBox(width: 8),
                  Expanded(
                    child: ElevatedButton.icon(
                      onPressed: () => _addConsumption(context),
                      icon: Icon(Icons.local_gas_station, size: 18),
                      label: Text('استهلاك'),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.warning,
                        foregroundColor: Colors.white,
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}
```

---

### 3.6 إدارة المستندات (Documents Management)

#### الشاشات:

1. **قائمة المستندات** (`documents_list_screen.dart`)
   - بطاقات الإحصائيات
   - عرض المستندات في شبكة
   - فلترة حسب النوع
   - بحث بالعنوان

2. **تفاصيل المستند** (`document_detail_screen.dart`)
   - معلومات المستند
   - عرض الملف (PDF/صورة)
   - تاريخ الانتهاء والحالة
   - أزرار: تحميل، مشاركة، تعديل، حذف

3. **إضافة مستند** (`add_document_screen.dart`)
   - العنوان والنوع
   - الوصف والوسوم
   - تاريخ الانتهاء
   - رفع الملف

---

### 3.7 الملف الشخصي (Profile)

#### شاشة الملف الشخصي (`profile_screen.dart`)

**الأقسام:**
1. رأس الصفحة مع الصورة الشخصية
2. المعلومات الشخصية
3. المعلومات الوظيفية
4. الصور والوثائق
5. المعدات المسجلة تحت اسم المستخدم
6. تغيير كلمة المرور
7. الإحصائيات والنشاط

```dart
class ProfileScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: CustomScrollView(
        slivers: [
          // App Bar with Gradient
          SliverAppBar(
            expandedHeight: 200,
            pinned: true,
            flexibleSpace: FlexibleSpaceBar(
              background: Container(
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [AppColors.primary, AppColors.primaryDark],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                ),
                child: SafeArea(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      CircleAvatar(
                        radius: 50,
                        backgroundColor: Colors.white,
                        backgroundImage: user.photoUrl != null
                          ? NetworkImage(user.photoUrl!)
                          : null,
                        child: user.photoUrl == null
                          ? Icon(Icons.person, size: 50, color: AppColors.primary)
                          : null,
                      ),
                      SizedBox(height: 12),
                      Text(
                        user.name,
                        style: AppTypography.h2.copyWith(color: Colors.white),
                      ),
                      Text(
                        user.email,
                        style: AppTypography.body2.copyWith(color: Colors.white70),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),

          // Content
          SliverToBoxAdapter(
            child: Padding(
              padding: EdgeInsets.all(AppSpacing.md),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Personal Info Card
                  _buildPersonalInfoCard(),
                  SizedBox(height: AppSpacing.md),

                  // Employment Info Card
                  _buildEmploymentInfoCard(),
                  SizedBox(height: AppSpacing.md),

                  // Photos & Documents
                  _buildPhotosSection(),
                  SizedBox(height: AppSpacing.md),

                  // Registered Equipment
                  _buildEquipmentSection(),
                  SizedBox(height: AppSpacing.md),

                  // Change Password
                  _buildChangePasswordCard(),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
```

---

## 4. مكونات مشتركة (Shared Widgets)

### 4.1 بطاقة إحصائية (Statistic Card)

```dart
class StatisticCard extends StatelessWidget {
  final String title;
  final String value;
  final IconData icon;
  final Color color;
  final Color backgroundColor;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.all(AppSpacing.md),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [backgroundColor.withOpacity(0.1), backgroundColor.withOpacity(0.2)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(AppSpacing.cardRadius),
        border: Border.all(color: color.withOpacity(0.2)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(title, style: AppTypography.body2.copyWith(color: color)),
              Container(
                padding: EdgeInsets.all(8),
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [color, color.withOpacity(0.7)],
                  ),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(icon, color: Colors.white, size: 24),
              ),
            ],
          ),
          SizedBox(height: 12),
          Text(value, style: AppTypography.h2.copyWith(color: AppColors.gray900)),
        ],
      ),
    );
  }
}
```

### 4.2 زر مخصص (Custom Button)

```dart
class PrimaryButton extends StatelessWidget {
  final String text;
  final VoidCallback onPressed;
  final bool isLoading;
  final IconData? icon;

  @override
  Widget build(BuildContext context) {
    return ElevatedButton(
      onPressed: isLoading ? null : onPressed,
      style: ElevatedButton.styleFrom(
        padding: EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(AppSpacing.buttonRadius),
        ),
        elevation: 2,
      ).copyWith(
        backgroundColor: MaterialStateProperty.resolveWith<Color>(
          (Set<MaterialState> states) {
            if (states.contains(MaterialState.disabled)) {
              return AppColors.gray300;
            }
            return AppColors.primary;
          },
        ),
      ),
      child: isLoading
        ? SizedBox(
            height: 20,
            width: 20,
            child: CircularProgressIndicator(
              strokeWidth: 2,
              valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
            ),
          )
        : Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              if (icon != null) ...[
                Icon(icon, size: 20),
                SizedBox(width: 8),
              ],
              Text(text, style: AppTypography.button),
            ],
          ),
    );
  }
}
```

### 4.3 حقل إدخال مخصص (Custom Input Field)

```dart
class CustomTextField extends StatelessWidget {
  final String label;
  final String? hint;
  final TextEditingController? controller;
  final bool obscureText;
  final IconData? prefixIcon;
  final IconData? suffixIcon;
  final VoidCallback? onSuffixTap;
  final String? Function(String?)? validator;
  final TextInputType? keyboardType;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: AppTypography.body2.copyWith(fontWeight: FontWeight.w600)),
        SizedBox(height: 8),
        TextFormField(
          controller: controller,
          obscureText: obscureText,
          keyboardType: keyboardType,
          validator: validator,
          style: AppTypography.body1,
          textDirection: TextDirection.rtl,
          decoration: InputDecoration(
            hintText: hint,
            prefixIcon: prefixIcon != null ? Icon(prefixIcon, color: AppColors.gray400) : null,
            suffixIcon: suffixIcon != null
              ? IconButton(
                  icon: Icon(suffixIcon, color: AppColors.gray400),
                  onPressed: onSuffixTap,
                )
              : null,
            filled: true,
            fillColor: AppColors.gray50,
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSpacing.inputRadius),
              borderSide: BorderSide(color: AppColors.gray200),
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSpacing.inputRadius),
              borderSide: BorderSide(color: AppColors.gray200),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSpacing.inputRadius),
              borderSide: BorderSide(color: AppColors.primary, width: 2),
            ),
            errorBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSpacing.inputRadius),
              borderSide: BorderSide(color: AppColors.error),
            ),
          ),
        ),
      ],
    );
  }
}
```

### 4.4 حالة فارغة (Empty State)

```dart
class EmptyState extends StatelessWidget {
  final String title;
  final String message;
  final IconData icon;
  final String? actionText;
  final VoidCallback? onAction;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: EdgeInsets.all(AppSpacing.xl),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 100, color: AppColors.gray300),
            SizedBox(height: AppSpacing.lg),
            Text(title, style: AppTypography.h2, textAlign: TextAlign.center),
            SizedBox(height: AppSpacing.sm),
            Text(message, style: AppTypography.body2, textAlign: TextAlign.center),
            if (actionText != null && onAction != null) ...[
              SizedBox(height: AppSpacing.lg),
              PrimaryButton(
                text: actionText!,
                onPressed: onAction!,
                icon: Icons.add,
              ),
            ],
          ],
        ),
      ),
    );
  }
}
```

---

## 5. إدارة الحالة (State Management)

### 5.1 مثال BLoC للمصادقة

```dart
// Events
abstract class AuthEvent extends Equatable {
  @override
  List<Object?> get props => [];
}

class LoginRequested extends AuthEvent {
  final String email;
  final String password;

  LoginRequested({required this.email, required this.password});

  @override
  List<Object?> get props => [email, password];
}

class LogoutRequested extends AuthEvent {}

// States
abstract class AuthState extends Equatable {
  @override
  List<Object?> get props => [];
}

class AuthInitial extends AuthState {}

class AuthLoading extends AuthState {}

class AuthSuccess extends AuthState {
  final User user;
  final String token;

  AuthSuccess({required this.user, required this.token});

  @override
  List<Object?> get props => [user, token];
}

class AuthFailure extends AuthState {
  final String error;

  AuthFailure({required this.error});

  @override
  List<Object?> get props => [error];
}

// BLoC
class AuthBloc extends Bloc<AuthEvent, AuthState> {
  final AuthRepository authRepository;

  AuthBloc({required this.authRepository}) : super(AuthInitial()) {
    on<LoginRequested>(_onLoginRequested);
    on<LogoutRequested>(_onLogoutRequested);
  }

  Future<void> _onLoginRequested(
    LoginRequested event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());

    try {
      final response = await authRepository.login(
        email: event.email,
        password: event.password,
      );

      emit(AuthSuccess(user: response.user, token: response.token));
    } catch (e) {
      emit(AuthFailure(error: e.toString()));
    }
  }

  Future<void> _onLogoutRequested(
    LogoutRequested event,
    Emitter<AuthState> emit,
  ) async {
    await authRepository.logout();
    emit(AuthInitial());
  }
}
```

---

## 6. الشبكة والـ API (Networking)

### 6.1 إعدادات Dio

```dart
class DioClient {
  final Dio _dio;

  DioClient(this._dio) {
    _dio
      ..options.baseUrl = 'http://your-api.com/api/'
      ..options.connectTimeout = Duration(seconds: 30)
      ..options.receiveTimeout = Duration(seconds: 30)
      ..interceptors.add(AuthInterceptor())
      ..interceptors.add(LogInterceptor(
        request: true,
        requestBody: true,
        responseBody: true,
        error: true,
      ));
  }

  Dio get dio => _dio;
}

class AuthInterceptor extends Interceptor {
  @override
  void onRequest(
    RequestOptions options,
    RequestInterceptorHandler handler,
  ) async {
    final token = await _getToken();
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }
    options.headers['Accept'] = 'application/json';
    options.headers['Content-Type'] = 'application/json';

    super.onRequest(options, handler);
  }

  Future<String?> _getToken() async {
    // Get token from secure storage
    return null;
  }
}
```

---

## 7. التخزين المحلي (Local Storage)

### 7.1 إعدادات Hive

```dart
class HiveManager {
  static Future<void> init() async {
    await Hive.initFlutter();

    // Register Adapters
    Hive.registerAdapter(UserAdapter());
    Hive.registerAdapter(EquipmentAdapter());
    Hive.registerAdapter(EmployeeAdapter());

    // Open Boxes
    await Hive.openBox<User>('users');
    await Hive.openBox<Equipment>('equipment');
    await Hive.openBox<Employee>('employees');
    await Hive.openBox('settings');
  }

  static Box<T> getBox<T>(String name) {
    return Hive.box<T>(name);
  }
}
```

---

## 8. الترجمة والتوطين (Localization)

### 8.1 ملفات الترجمة

```dart
// lib/l10n/app_ar.arb
{
  "appName": "شركة الأبراج للمقاولات",
  "dashboard": "لوحة التحكم",
  "equipment": "المعدات",
  "employees": "الموظفين",
  "fuelManagement": "إدارة المحروقات",
  "documents": "المستندات",
  "profile": "الملف الشخصي",
  "login": "تسجيل الدخول",
  "logout": "تسجيل الخروج",
  "email": "البريد الإلكتروني",
  "password": "كلمة المرور",
  "submit": "إرسال",
  "cancel": "إلغاء",
  "save": "حفظ",
  "delete": "حذف",
  "edit": "تعديل",
  "add": "إضافة",
  "search": "بحث",
  "filter": "تصفية",
  "total": "الإجمالي",
  "status": "الحالة",
  "active": "نشط",
  "inactive": "غير نشط",
  "loading": "جاري التحميل..."
}
```

---

## 9. الإشعارات (Notifications)

### 9.1 Firebase Cloud Messaging

```dart
class NotificationService {
  final FirebaseMessaging _fcm = FirebaseMessaging.instance;

  Future<void> initialize() async {
    // Request permission
    NotificationSettings settings = await _fcm.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );

    if (settings.authorizationStatus == AuthorizationStatus.authorized) {
      print('User granted permission');

      // Get FCM token
      String? token = await _fcm.getToken();
      print('FCM Token: $token');

      // Send token to server

      // Handle foreground messages
      FirebaseMessaging.onMessage.listen((RemoteMessage message) {
        _showNotification(message);
      });

      // Handle background messages
      FirebaseMessaging.onMessageOpenedApp.listen((RemoteMessage message) {
        _handleNotificationClick(message);
      });
    }
  }

  void _showNotification(RemoteMessage message) {
    // Show local notification
  }

  void _handleNotificationClick(RemoteMessage message) {
    // Navigate to appropriate screen
  }
}
```

---

## 10. الأمان (Security)

### 10.1 تخزين آمن للـ Token

```dart
class SecureStorageService {
  final FlutterSecureStorage _storage = FlutterSecureStorage();

  Future<void> saveToken(String token) async {
    await _storage.write(key: 'auth_token', value: token);
  }

  Future<String?> getToken() async {
    return await _storage.read(key: 'auth_token');
  }

  Future<void> deleteToken() async {
    await _storage.delete(key: 'auth_token');
  }
}
```

---

## 11. الميزات الإضافية

### 11.1 الوضع المظلم (Dark Mode)

```dart
class AppTheme {
  static ThemeData lightTheme = ThemeData(
    useMaterial3: true,
    colorScheme: ColorScheme.fromSeed(
      seedColor: AppColors.primary,
      brightness: Brightness.light,
    ),
    fontFamily: AppTypography.fontFamily,
    appBarTheme: AppBarTheme(
      backgroundColor: AppColors.primary,
      foregroundColor: Colors.white,
      elevation: 0,
    ),
  );

  static ThemeData darkTheme = ThemeData(
    useMaterial3: true,
    colorScheme: ColorScheme.fromSeed(
      seedColor: AppColors.primary,
      brightness: Brightness.dark,
    ),
    fontFamily: AppTypography.fontFamily,
  );
}
```

### 11.2 QR Code Scanner لمسح رموز المعدات

```dart
class QRScannerScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('مسح رمز QR')),
      body: MobileScanner(
        onDetect: (capture) {
          final List<Barcode> barcodes = capture.barcodes;
          for (final barcode in barcodes) {
            final String? code = barcode.rawValue;
            if (code != null) {
              // Handle scanned code
              Navigator.pop(context, code);
            }
          }
        },
      ),
    );
  }
}
```

### 11.3 تصدير PDF

```dart
class PDFService {
  Future<void> generateConsumptionReport(
    List<FuelConsumption> consumptions,
  ) async {
    final pdf = pw.Document();

    pdf.addPage(
      pw.Page(
        textDirection: pw.TextDirection.rtl,
        build: (context) => pw.Column(
          crossAxisAlignment: pw.CrossAxisAlignment.start,
          children: [
            // Header
            pw.Text(
              'تقرير استهلاك المحروقات',
              style: pw.TextStyle(fontSize: 24, fontWeight: pw.FontWeight.bold),
            ),
            pw.SizedBox(height: 20),

            // Table
            pw.TableHelper.fromTextArray(
              headers: ['التاريخ', 'المعدة', 'النوع', 'الكمية'],
              data: consumptions.map((c) => [
                c.date,
                c.equipmentName,
                c.fuelType,
                c.quantity,
              ]).toList(),
            ),
          ],
        ),
      ),
    );

    // Save or share PDF
    await Printing.layoutPdf(
      onLayout: (format) async => pdf.save(),
    );
  }
}
```

---

## 12. الاختبارات (Testing)

### 12.1 Unit Tests

```dart
void main() {
  group('AuthBloc', () {
    late AuthBloc authBloc;
    late MockAuthRepository mockAuthRepository;

    setUp(() {
      mockAuthRepository = MockAuthRepository();
      authBloc = AuthBloc(authRepository: mockAuthRepository);
    });

    tearDown(() {
      authBloc.close();
    });

    test('initial state is AuthInitial', () {
      expect(authBloc.state, AuthInitial());
    });

    blocTest<AuthBloc, AuthState>(
      'emits [AuthLoading, AuthSuccess] when login succeeds',
      build: () {
        when(mockAuthRepository.login(
          email: 'test@test.com',
          password: 'password',
        )).thenAnswer((_) async => AuthResponse(
          user: User(id: 1, name: 'Test User'),
          token: 'test_token',
        ));

        return authBloc;
      },
      act: (bloc) => bloc.add(LoginRequested(
        email: 'test@test.com',
        password: 'password',
      )),
      expect: () => [
        AuthLoading(),
        isA<AuthSuccess>(),
      ],
    );
  });
}
```

---

## 13. نصائح وأفضل الممارسات

1. **استخدام Freezed للنماذج**
   ```dart
   @freezed
   class User with _$User {
     const factory User({
       required int id,
       required String name,
       String? email,
       String? phone,
     }) = _User;

     factory User.fromJson(Map<String, dynamic> json) => _$UserFromJson(json);
   }
   ```

2. **معالجة الأخطاء بشكل موحد**
   ```dart
   class ErrorHandler {
     static String getErrorMessage(dynamic error) {
       if (error is DioException) {
         switch (error.type) {
           case DioExceptionType.connectionTimeout:
             return 'انتهت مهلة الاتصال';
           case DioExceptionType.receiveTimeout:
             return 'انتهت مهلة استقبال البيانات';
           default:
             return 'حدث خطأ في الاتصال';
         }
       }
       return 'حدث خطأ غير متوقع';
     }
   }
   ```

3. **استخدام GetIt للـ Dependency Injection**
   ```dart
   final getIt = GetIt.instance;

   void setupDependencies() {
     // Network
     getIt.registerLazySingleton(() => Dio());
     getIt.registerLazySingleton(() => DioClient(getIt()));

     // Repositories
     getIt.registerLazySingleton<AuthRepository>(
       () => AuthRepositoryImpl(getIt()),
     );

     // BLoCs
     getIt.registerFactory(() => AuthBloc(authRepository: getIt()));
   }
   ```

4. **استخدام Shimmer للتحميل**
   ```dart
   class LoadingShimmer extends StatelessWidget {
     @override
     Widget build(BuildContext context) {
       return Shimmer.fromColors(
         baseColor: Colors.grey[300]!,
         highlightColor: Colors.grey[100]!,
         child: ListView.builder(
           itemCount: 5,
           itemBuilder: (context, index) => Card(
             child: Container(height: 100),
           ),
         ),
       );
     }
   }
   ```

---

## 14. الخلاصة

هذا المستند يوفر مواصفات شاملة لبناء تطبيق Flutter احترافي يحاكي نظام Laravel الخاص بشركة الأبراج للمقاولات. التطبيق يشمل:

✅ تصميم حديث ومتجاوب مع هوية بصرية موحدة
✅ جميع الوحدات الرئيسية (لوحة التحكم، المعدات، الموظفين، المحروقات، المستندات، الملف الشخصي)
✅ بنية معمارية نظيفة مع BLoC Pattern
✅ إدارة الحالة المتقدمة
✅ اتصال بـ API مع معالجة الأخطاء
✅ تخزين محلي آمن
✅ دعم الترجمة والتوطين
✅ إشعارات Push
✅ ميزات إضافية (QR Scanner, PDF Export, Dark Mode)
✅ اختبارات شاملة

**الخطوات التالية:**
1. إنشاء مشروع Flutter جديد
2. إضافة جميع الحزم المطلوبة
3. إعداد البنية الأساسية
4. تطبيق الوحدات واحدة تلو الأخرى
5. الاختبار والتحسين
6. النشر على المتاجر

---

**تم إنشاء هذا المستند بواسطة Claude Code**
**شركة الأبراج للمقاولات - © 2025**
