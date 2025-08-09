import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:pretty_dio_logger/pretty_dio_logger.dart';

class ApiService {
  static const String _baseUrl = 'https://59529cf6f824.ngrok-free.app/api';
  // static const String _baseUrl = 'http://127.0.0.1:8000/api';
  static const FlutterSecureStorage _storage = FlutterSecureStorage();

  late Dio _dio;

  static final ApiService _instance = ApiService._internal();
  factory ApiService() => _instance;

  ApiService._internal() {
    _dio = Dio(BaseOptions(
      baseUrl: _baseUrl,
      connectTimeout: const Duration(milliseconds: 30000),
      receiveTimeout: const Duration(milliseconds: 30000),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    ));

    _dio.interceptors.add(PrettyDioLogger(
      requestHeader: true,
      requestBody: true,
      responseBody: true,
      responseHeader: false,
      error: true,
      compact: true,
      maxWidth: 90,
    ));

    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        final token = await getToken();
        if (token != null) {
          options.headers['Authorization'] = 'Bearer $token';
        }
        handler.next(options);
      },
      onError: (error, handler) async {
        if (error.response?.statusCode == 401) {
          await clearToken();
          // Redirect to login or show login dialog
        }
        handler.next(error);
      },
    ));
  }

  // Token management
  Future<String?> getToken() async {
    return await _storage.read(key: 'auth_token');
  }

  Future<void> setToken(String token) async {
    await _storage.write(key: 'auth_token', value: token);
  }

  Future<void> clearToken() async {
    await _storage.delete(key: 'auth_token');
  }

  // Authentication
  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await _dio.post('/login', data: {
        'email': email,
        'password': password,
      });

      if (response.data['status'] == 'success' &&
          response.data['token'] != null) {
        await setToken(response.data['token']);
      }

      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> logout() async {
    try {
      final response = await _dio.post('/logout');
      await clearToken();
      return response.data;
    } on DioException catch (e) {
      await clearToken();
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> getUser() async {
    try {
      final response = await _dio.get('/user');
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  // Dashboard
  Future<Map<String, dynamic>> getDashboardStats() async {
    try {
      final response = await _dio.get('/dashboard/stats');
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> getRecentActivities() async {
    try {
      final response = await _dio.get('/dashboard/recent-activities');
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  // Projects
  Future<Map<String, dynamic>> getProjects(
      {int page = 1, int perPage = 15}) async {
    try {
      final response = await _dio.get('/projects', queryParameters: {
        'page': page,
        'per_page': perPage,
      });
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> getProject(int id) async {
    try {
      final response = await _dio.get('/projects/$id');
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> createProject(Map<String, dynamic> data) async {
    try {
      final response = await _dio.post('/projects', data: data);
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> updateProject(
      int id, Map<String, dynamic> data) async {
    try {
      final response = await _dio.put('/projects/$id', data: data);
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> deleteProject(int id) async {
    try {
      final response = await _dio.delete('/projects/$id');
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  // Employees
  Future<Map<String, dynamic>> getEmployees(
      {int page = 1, int perPage = 15}) async {
    try {
      final response = await _dio.get('/employees', queryParameters: {
        'page': page,
        'per_page': perPage,
      });
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> getEmployee(int id) async {
    try {
      final response = await _dio.get('/employees/$id');
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> createEmployee(Map<String, dynamic> data) async {
    try {
      final response = await _dio.post('/employees', data: data);
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> updateEmployee(
      int id, Map<String, dynamic> data) async {
    try {
      final response = await _dio.put('/employees/$id', data: data);
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> deleteEmployee(int id) async {
    try {
      final response = await _dio.delete('/employees/$id');
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> getEmployeeAttendance(int id,
      {String? startDate, String? endDate}) async {
    try {
      final queryParams = <String, dynamic>{};
      if (startDate != null) queryParams['start_date'] = startDate;
      if (endDate != null) queryParams['end_date'] = endDate;

      final response = await _dio.get('/employees/$id/attendance',
          queryParameters: queryParams);
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> recordAttendance(
      int employeeId, Map<String, dynamic> data) async {
    try {
      final response =
          await _dio.post('/employees/$employeeId/attendance', data: data);
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  // Equipment
  Future<Map<String, dynamic>> getEquipment(
      {int page = 1, int perPage = 15}) async {
    try {
      final response = await _dio.get('/equipment', queryParameters: {
        'page': page,
        'per_page': perPage,
      });
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> getEquipmentItem(int id) async {
    try {
      final response = await _dio.get('/equipment/$id');
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> createEquipment(
      Map<String, dynamic> data) async {
    try {
      final response = await _dio.post('/equipment', data: data);
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> updateEquipment(
      int id, Map<String, dynamic> data) async {
    try {
      final response = await _dio.put('/equipment/$id', data: data);
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Map<String, dynamic>> deleteEquipment(int id) async {
    try {
      final response = await _dio.delete('/equipment/$id');
      return response.data;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  String _handleError(DioException error) {
    if (error.response != null) {
      final data = error.response!.data;
      if (data is Map<String, dynamic> && data.containsKey('message')) {
        return data['message'];
      }
      return 'خطأ في الخادم: ${error.response!.statusCode}';
    }

    switch (error.type) {
      case DioExceptionType.connectionTimeout:
      case DioExceptionType.receiveTimeout:
      case DioExceptionType.sendTimeout:
        return 'انتهت مهلة الاتصال';
      case DioExceptionType.connectionError:
        return 'خطأ في الاتصال بالإنترنت';
      default:
        return 'حدث خطأ غير متوقع';
    }
  }
}
