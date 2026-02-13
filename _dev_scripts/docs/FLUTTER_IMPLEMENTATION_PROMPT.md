# برومبت تطوير تطبيق Flutter - نظام إدارة شركة الأبراج للمقاولات

## المهمة الرئيسية
قم ببناء تطبيق Flutter كامل لنظام إدارة شركة الأبراج للمقاولات باستخدام المواصفات والـ API الموثق.

---

## 1. الملفات المرجعية

يرجى قراءة وفهم الملفات التالية قبل البدء:

1. **`FLUTTER_APP_SPECIFICATION.md`** - المواصفات الكاملة للتطبيق (الهوية البصرية، البنية، الوحدات)
2. **`API_DOCUMENTATION.md`** - توثيق كامل لجميع API endpoints

---

## 2. خطة التنفيذ المطلوبة

### المرحلة 1: الإعداد الأولي (Setup)

**الخطوات:**
1. إنشاء مشروع Flutter جديد:
   ```bash
   flutter create abraj_app --org com.abraj.contracting
   cd abraj_app
   ```

2. إضافة جميع الـ dependencies من ملف `FLUTTER_APP_SPECIFICATION.md` إلى `pubspec.yaml`

3. إنشاء البنية الأساسية للمجلدات:
   ```
   lib/
   ├── core/
   │   ├── constants/
   │   │   ├── colors.dart
   │   │   ├── typography.dart
   │   │   ├── spacing.dart
   │   │   └── api_constants.dart
   │   ├── theme/
   │   │   └── app_theme.dart
   │   ├── utils/
   │   │   ├── error_handler.dart
   │   │   ├── validators.dart
   │   │   └── helpers.dart
   │   ├── widgets/
   │   │   ├── custom_button.dart
   │   │   ├── custom_text_field.dart
   │   │   ├── statistic_card.dart
   │   │   ├── empty_state.dart
   │   │   └── loading_shimmer.dart
   │   └── network/
   │       ├── dio_client.dart
   │       ├── api_interceptor.dart
   │       └── api_response.dart
   ├── features/
   │   ├── auth/
   │   ├── dashboard/
   │   ├── equipment/
   │   ├── employees/
   │   ├── fuel_management/
   │   ├── documents/
   │   └── profile/
   └── main.dart
   ```

4. إعداد الملفات الأساسية:

**colors.dart:**
```dart
import 'package:flutter/material.dart';

class AppColors {
  static const Color primary = Color(0xFF1e3c72);
  static const Color primaryDark = Color(0xFF2a5298);
  // ... نسخ جميع الألوان من FLUTTER_APP_SPECIFICATION.md
}
```

**typography.dart:**
```dart
import 'package:flutter/material.dart';
import 'colors.dart';

class AppTypography {
  static const String fontFamily = 'Cairo';
  // ... نسخ جميع TextStyles من FLUTTER_APP_SPECIFICATION.md
}
```

**api_constants.dart:**
```dart
class ApiConstants {
  static const String baseUrl = 'http://your-domain.com/api';

  // Auth Endpoints
  static const String login = '/auth/login';
  static const String logout = '/auth/logout';
  static const String me = '/auth/me';
  static const String updateProfile = '/auth/profile';
  static const String changePassword = '/auth/change-password';

  // Dashboard Endpoints
  static const String dashboardStats = '/dashboard/statistics';
  static const String recentActivities = '/dashboard/recent-activities';
  static const String fuelChart = '/dashboard/charts/fuel-consumption';

  // Equipment Endpoints
  static const String equipment = '/equipment';
  static const String equipmentDetail = '/equipment/{id}';
  static const String assignDriver = '/equipment/{id}/assign-driver';
  static const String moveEquipment = '/equipment/{id}/move';

  // Employees Endpoints
  static const String employees = '/employees';
  static const String employeeDetail = '/employees/{id}';
  static const String employeeAttendance = '/employees/{id}/attendance';

  // Fuel Management Endpoints
  static const String fuelTrucks = '/fuel/trucks';
  static const String fuelTruckDetail = '/fuel/trucks/{id}';
  static const String fuelDistributions = '/fuel/distributions';
  static const String fuelConsumptions = '/fuel/consumptions';
  static const String approveDistribution = '/fuel/distributions/{id}/approve';
  static const String rejectDistribution = '/fuel/distributions/{id}/reject';
  static const String consumptionReport = '/fuel/consumption-report';
  static const String refillTruck = '/fuel/trucks/{id}/refill';

  // Documents Endpoints
  static const String documents = '/documents';
  static const String documentDetail = '/documents/{id}';
  static const String downloadDocument = '/documents/{id}/download';
  static const String documentsStats = '/documents/statistics';

  // Profile Endpoints
  static const String profileEquipment = '/profile/equipment';

  // Notifications Endpoints
  static const String notifications = '/notifications';
  static const String readNotification = '/notifications/{id}/read';
  static const String readAllNotifications = '/notifications/read-all';

  // Settings Endpoints
  static const String locations = '/settings/locations';
  static const String equipmentTypes = '/settings/equipment-types';
}
```

---

### المرحلة 2: إعداد الشبكة (Network Layer)

**dio_client.dart:**
```dart
import 'package:dio/dio.dart';
import 'api_interceptor.dart';
import '../constants/api_constants.dart';

class DioClient {
  final Dio _dio;

  DioClient(this._dio) {
    _dio
      ..options.baseUrl = ApiConstants.baseUrl
      ..options.connectTimeout = const Duration(seconds: 30)
      ..options.receiveTimeout = const Duration(seconds: 30)
      ..options.headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      }
      ..interceptors.add(AuthInterceptor())
      ..interceptors.add(LogInterceptor(
        request: true,
        requestBody: true,
        responseBody: true,
        error: true,
        requestHeader: true,
      ));
  }

  Dio get dio => _dio;

  // GET request
  Future<Response> get(
    String path, {
    Map<String, dynamic>? queryParameters,
    Options? options,
  }) async {
    return await _dio.get(
      path,
      queryParameters: queryParameters,
      options: options,
    );
  }

  // POST request
  Future<Response> post(
    String path, {
    dynamic data,
    Map<String, dynamic>? queryParameters,
    Options? options,
  }) async {
    return await _dio.post(
      path,
      data: data,
      queryParameters: queryParameters,
      options: options,
    );
  }

  // PUT request
  Future<Response> put(
    String path, {
    dynamic data,
    Map<String, dynamic>? queryParameters,
    Options? options,
  }) async {
    return await _dio.put(
      path,
      data: data,
      queryParameters: queryParameters,
      options: options,
    );
  }

  // DELETE request
  Future<Response> delete(
    String path, {
    dynamic data,
    Map<String, dynamic>? queryParameters,
    Options? options,
  }) async {
    return await _dio.delete(
      path,
      data: data,
      queryParameters: queryParameters,
      options: options,
    );
  }

  // Upload file
  Future<Response> uploadFile(
    String path,
    FormData formData, {
    ProgressCallback? onSendProgress,
  }) async {
    return await _dio.post(
      path,
      data: formData,
      onSendProgress: onSendProgress,
    );
  }
}
```

**api_interceptor.dart:**
```dart
import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class AuthInterceptor extends Interceptor {
  final _storage = const FlutterSecureStorage();

  @override
  void onRequest(
    RequestOptions options,
    RequestInterceptorHandler handler,
  ) async {
    final token = await _storage.read(key: 'auth_token');

    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }

    super.onRequest(options, handler);
  }

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) {
    if (err.response?.statusCode == 401) {
      // Token expired, logout user
      _handleUnauthorized();
    }

    super.onError(err, handler);
  }

  Future<void> _handleUnauthorized() async {
    await _storage.delete(key: 'auth_token');
    // Navigate to login screen
  }
}
```

---

### المرحلة 3: تطبيق وحدة المصادقة (Authentication Feature)

**المطلوب:**

1. **إنشاء Models:**
   - `user_model.dart`
   - `login_request.dart`
   - `auth_response.dart`

2. **إنشاء Repository:**
   ```dart
   abstract class AuthRepository {
     Future<AuthResponse> login(String email, String password);
     Future<void> logout();
     Future<User> getCurrentUser();
     Future<void> updateProfile(UpdateProfileRequest request);
     Future<void> changePassword(ChangePasswordRequest request);
   }

   class AuthRepositoryImpl implements AuthRepository {
     final DioClient _dioClient;

     // Implement all methods using API endpoints from API_DOCUMENTATION.md
   }
   ```

3. **إنشاء BLoC:**
   ```dart
   // Events
   abstract class AuthEvent extends Equatable {}

   class LoginRequested extends AuthEvent {
     final String email;
     final String password;
   }

   class LogoutRequested extends AuthEvent {}

   // States
   abstract class AuthState extends Equatable {}

   class AuthInitial extends AuthState {}
   class AuthLoading extends AuthState {}
   class AuthSuccess extends AuthState {
     final User user;
     final String token;
   }
   class AuthFailure extends AuthState {
     final String error;
   }

   // BLoC
   class AuthBloc extends Bloc<AuthEvent, AuthState> {
     final AuthRepository _authRepository;

     AuthBloc({required AuthRepository authRepository})
         : _authRepository = authRepository,
           super(AuthInitial());
   }
   ```

4. **إنشاء Screens:**
   - `login_screen.dart` - استخدم التصميم من FLUTTER_APP_SPECIFICATION.md
   - خلفية gradient بألوان primary
   - شعار الشركة في الأعلى
   - حقول البريد الإلكتروني وكلمة المرور
   - زر تسجيل الدخول مع loading indicator

**مثال Login Screen:**
```dart
class LoginScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            colors: [AppColors.primary, AppColors.primaryDark],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
        ),
        child: SafeArea(
          child: BlocConsumer<AuthBloc, AuthState>(
            listener: (context, state) {
              if (state is AuthSuccess) {
                Navigator.pushReplacementNamed(context, '/dashboard');
              } else if (state is AuthFailure) {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text(state.error)),
                );
              }
            },
            builder: (context, state) {
              return SingleChildScrollView(
                padding: EdgeInsets.all(AppSpacing.lg),
                child: Column(
                  children: [
                    SizedBox(height: 60),
                    // Logo
                    Icon(
                      Icons.account_balance,
                      size: 100,
                      color: Colors.white,
                    ),
                    SizedBox(height: AppSpacing.md),
                    Text(
                      'شركة الأبراج للمقاولات',
                      style: AppTypography.h1.copyWith(color: Colors.white),
                    ),
                    SizedBox(height: AppSpacing.xxl),

                    // Login Form
                    Container(
                      padding: EdgeInsets.all(AppSpacing.lg),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(AppSpacing.cardRadius),
                      ),
                      child: Column(
                        children: [
                          CustomTextField(
                            label: 'البريد الإلكتروني',
                            hint: 'user@example.com',
                            prefixIcon: Icons.email,
                            keyboardType: TextInputType.emailAddress,
                          ),
                          SizedBox(height: AppSpacing.md),
                          CustomTextField(
                            label: 'كلمة المرور',
                            hint: '••••••••',
                            prefixIcon: Icons.lock,
                            obscureText: true,
                          ),
                          SizedBox(height: AppSpacing.lg),
                          PrimaryButton(
                            text: 'تسجيل الدخول',
                            onPressed: () {
                              // Dispatch LoginRequested event
                            },
                            isLoading: state is AuthLoading,
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              );
            },
          ),
        ),
      ),
    );
  }
}
```

---

### المرحلة 4: تطبيق لوحة التحكم (Dashboard Feature)

**المطلوب:**

1. **Models:**
   - `dashboard_statistics.dart`
   - `recent_activity.dart`
   - `chart_data.dart`

2. **Repository:**
   - استخدم endpoints: `/dashboard/statistics`, `/dashboard/recent-activities`, `/dashboard/charts/fuel-consumption`

3. **BLoC:**
   - `DashboardBloc` مع events و states

4. **Screens:**
   - `dashboard_screen.dart`
   - بطاقات الإحصائيات (4 بطاقات)
   - رسوم بيانية (استخدم fl_chart)
   - قائمة الأنشطة الأخيرة

**مثال Statistic Card:**
```dart
StatisticCard(
  title: 'إجمالي المعدات',
  value: '${statistics.equipment.total}',
  icon: Icons.construction,
  color: AppColors.primary,
  backgroundColor: AppColors.primary.withOpacity(0.1),
)
```

---

### المرحلة 5: تطبيق إدارة المعدات (Equipment Feature)

**المطلوب:**

1. **Models:**
   - `equipment.dart` (مع freezed)
   - `equipment_list_response.dart`
   - `location.dart`
   - `driver.dart`

2. **Repository:**
   - `GET /equipment` - قائمة المعدات مع فلترة
   - `GET /equipment/{id}` - تفاصيل معدة
   - `POST /equipment` - إضافة معدة (مع رفع صور)
   - `PUT /equipment/{id}` - تحديث معدة
   - `DELETE /equipment/{id}` - حذف معدة
   - `POST /equipment/{id}/assign-driver` - تعيين سائق
   - `POST /equipment/{id}/move` - نقل معدة

3. **BLoC:**
   - `EquipmentListBloc`
   - `EquipmentDetailBloc`
   - `EquipmentFormBloc`

4. **Screens:**
   - `equipment_list_screen.dart` - عرض شبكة البطاقات مع بحث وفلترة
   - `equipment_detail_screen.dart` - تفاصيل كاملة مع Tabs (معلومات، صيانة، محروقات)
   - `equipment_form_screen.dart` - نموذج إضافة/تعديل مع رفع صور

**مثال Equipment Card:**
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
        child: Padding(
          padding: EdgeInsets.all(AppSpacing.md),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Expanded(
                    child: Text(equipment.name, style: AppTypography.h3),
                  ),
                  _StatusBadge(status: equipment.status),
                ],
              ),
              Divider(),
              _InfoRow(icon: Icons.qr_code, label: 'الرمز', value: equipment.code),
              _InfoRow(icon: Icons.location_on, label: 'الموقع',
                      value: equipment.location?.name ?? 'غير محدد'),
            ],
          ),
        ),
      ),
    );
  }
}
```

---

### المرحلة 6: تطبيق إدارة المحروقات (Fuel Management Feature)

**المطلوب:**

1. **Models:**
   - `fuel_truck.dart`
   - `fuel_distribution.dart`
   - `fuel_consumption.dart`
   - `consumption_report.dart`

2. **Repository:**
   - جميع endpoints من `/fuel/*` في API Documentation

3. **BLoC:**
   - `FuelTrucksBloc`
   - `FuelDistributionBloc`
   - `FuelConsumptionBloc`

4. **Screens:**
   - `fuel_management_screen.dart` - بطاقات سيارات المحروقات
   - `fuel_truck_detail_screen.dart` - تفاصيل مع سجلات
   - `add_distribution_screen.dart` - نموذج إضافة توزيع
   - `add_consumption_screen.dart` - نموذج تسجيل استهلاك
   - `consumption_report_screen.dart` - تقرير مع فلترة وتصدير PDF

**مثال Fuel Truck Card (من FLUTTER_APP_SPECIFICATION.md):**
```dart
class FuelTruckCard extends StatelessWidget {
  final FuelTruck truck;

  @override
  Widget build(BuildContext context) {
    final percentage = (truck.currentQuantity / truck.capacity) * 100;

    return Card(
      child: Container(
        padding: EdgeInsets.all(AppSpacing.md),
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(AppSpacing.cardRadius),
          gradient: LinearGradient(
            colors: [Colors.blue.shade50, Colors.blue.shade100],
          ),
        ),
        child: Column(
          children: [
            // Header, Info, Progress Bar, Buttons
            // انسخ الكود الكامل من FLUTTER_APP_SPECIFICATION.md
          ],
        ),
      ),
    );
  }
}
```

---

### المرحلة 7: تطبيق الوحدات المتبقية

**تطبيق بنفس الأسلوب:**

1. **Employees Feature** - استخدم `/employees/*` endpoints
2. **Documents Feature** - استخدم `/documents/*` endpoints
3. **Profile Feature** - استخدم `/profile/*` و `/auth/*` endpoints

---

### المرحلة 8: الميزات الإضافية

**المطلوب:**

1. **Notifications:**
   - استخدم Firebase Cloud Messaging
   - عرض الإشعارات في notification tray
   - badge counter على الأيقونة

2. **QR Scanner:**
   - استخدم mobile_scanner package
   - مسح رموز QR للمعدات

3. **PDF Export:**
   - استخدم pdf و printing packages
   - تصدير تقارير الاستهلاك

4. **Dark Mode:**
   - تطبيق الثيم الداكن من FLUTTER_APP_SPECIFICATION.md

5. **Offline Support:**
   - حفظ البيانات محلياً بـ Hive
   - Sync عند اتصال الإنترنت

---

## 3. متطلبات التصميم

**يجب الالتزام بـ:**

1. ✅ جميع الألوان من `AppColors`
2. ✅ جميع الخطوط من `AppTypography`
3. ✅ المسافات من `AppSpacing`
4. ✅ استخدام خط Cairo العربي
5. ✅ دعم RTL (Right-to-Left)
6. ✅ تصميم متجاوب (Responsive)
7. ✅ تأثيرات Hover و animations

---

## 4. معالجة الأخطاء

**يجب معالجة جميع الأخطاء:**

```dart
class ErrorHandler {
  static String getErrorMessage(dynamic error) {
    if (error is DioException) {
      switch (error.type) {
        case DioExceptionType.connectionTimeout:
          return 'انتهت مهلة الاتصال';
        case DioExceptionType.receiveTimeout:
          return 'انتهت مهلة استقبال البيانات';
        case DioExceptionType.badResponse:
          if (error.response?.statusCode == 401) {
            return 'غير مصرح بالوصول';
          } else if (error.response?.statusCode == 422) {
            final errors = error.response?.data['errors'];
            if (errors != null && errors is Map) {
              return errors.values.first[0];
            }
            return 'خطأ في البيانات المدخلة';
          }
          return error.response?.data['message'] ?? 'حدث خطأ في الخادم';
        default:
          return 'حدث خطأ في الاتصال';
      }
    }
    return 'حدث خطأ غير متوقع';
  }

  static void showError(BuildContext context, String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message, style: AppTypography.body1.copyWith(color: Colors.white)),
        backgroundColor: AppColors.error,
        behavior: SnackBarBehavior.floating,
      ),
    );
  }
}
```

---

## 5. الاختبارات (Testing)

**مطلوب:**

1. **Unit Tests** لجميع الـ Repositories
2. **Widget Tests** للـ screens الرئيسية
3. **Integration Tests** للـ user flows

**مثال Unit Test:**
```dart
void main() {
  group('AuthRepository', () {
    late AuthRepository authRepository;
    late MockDioClient mockDioClient;

    setUp(() {
      mockDioClient = MockDioClient();
      authRepository = AuthRepositoryImpl(mockDioClient);
    });

    test('login returns AuthResponse on success', () async {
      when(mockDioClient.post(
        ApiConstants.login,
        data: anyNamed('data'),
      )).thenAnswer((_) async => Response(
        data: {
          'success': true,
          'data': {
            'user': {'id': 1, 'name': 'Test'},
            'token': 'test_token',
          },
        },
        statusCode: 200,
        requestOptions: RequestOptions(path: ''),
      ));

      final result = await authRepository.login('test@test.com', 'password');

      expect(result.token, 'test_token');
      expect(result.user.id, 1);
    });
  });
}
```

---

## 6. التوثيق المطلوب

**يجب توثيق:**

1. جميع الـ Models بـ JSON serialization
2. جميع الـ Repositories بـ comments
3. جميع الـ BLoCs بـ comments
4. README.md شامل للمشروع

---

## 7. معايير الجودة

**يجب أن يكون الكود:**

- ✅ نظيف ومنظم
- ✅ يتبع Clean Architecture
- ✅ يستخدم BLoC pattern
- ✅ مع error handling شامل
- ✅ مع loading states
- ✅ مع validation للـ forms
- ✅ responsive لجميع الأحجام
- ✅ يدعم RTL
- ✅ مع animations سلسة
- ✅ production-ready

---

## 8. نقاط مهمة

1. **استخدم API_DOCUMENTATION.md** لجميع الـ endpoints
2. **استخدم FLUTTER_APP_SPECIFICATION.md** للتصميم والألوان
3. **لا تخترع endpoints جديدة** - استخدم الموثق فقط
4. **الألوان والخطوط** يجب أن تكون من الملفات المحددة
5. **جميع النصوص بالعربية**
6. **دعم RTL إلزامي**
7. **معالجة الأخطاء إلزامية**
8. **Loading states إلزامية**

---

## 9. التسليم

**يجب تسليم:**

1. ✅ كود المشروع كامل
2. ✅ ملف README.md
3. ✅ الاختبارات
4. ✅ ملف APK للتجربة
5. ✅ Screenshots من التطبيق

---

## 10. الأولويات

**ترتيب التطوير:**

1. **أولوية قصوى:**
   - Authentication
   - Dashboard
   - Equipment Management

2. **أولوية عالية:**
   - Fuel Management
   - Employees

3. **أولوية متوسطة:**
   - Documents
   - Profile

4. **ميزات إضافية:**
   - QR Scanner
   - PDF Export
   - Dark Mode
   - Offline Support

---

**ابدأ العمل الآن وتأكد من قراءة جميع الملفات المرجعية قبل البدء!**

**© 2025 شركة الأبراج للمقاولات**
