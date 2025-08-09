import 'package:flutter/material.dart';
import '../models/user.dart';
import '../services/api_service.dart';

class AuthProvider extends ChangeNotifier {
  final ApiService _apiService = ApiService();

  User? _user;
  bool _isLoading = false;
  bool _isAuthenticated = false;
  String? _error;

  User? get user => _user;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _isAuthenticated;
  String? get error => _error;

  AuthProvider() {
    _checkAuthStatus();
  }

  Future<void> _checkAuthStatus() async {
    try {
      final token = await _apiService.getToken();
      if (token != null) {
        await getCurrentUser();
      }
    } catch (e) {
      await logout();
    }
  }

  Future<bool> login(String email, String password) async {
    print('AuthProvider: Starting login');
    _setLoading(true);
    _clearError();

    try {
      print('AuthProvider: Calling API login');
      final response = await _apiService.login(email, password);
      print('AuthProvider: API response: $response');

      if (response['status'] == 'success') {
        _user = User.fromJson(response['user']);
        _isAuthenticated = true;
        print('AuthProvider: Login successful, user: ${_user?.name}');
        notifyListeners();
        return true;
      } else {
        print('AuthProvider: Login failed: ${response['message']}');
        _setError(response['message'] ?? 'فشل تسجيل الدخول');
        return false;
      }
    } catch (e) {
      print('AuthProvider: Login error: $e');
      _setError(e.toString());
      return false;
    } finally {
      _setLoading(false);
      print('AuthProvider: Login process completed');
    }
  }

  Future<void> getCurrentUser() async {
    try {
      final response = await _apiService.getUser();

      if (response['status'] == 'success') {
        _user = User.fromJson(response['data']);
        _isAuthenticated = true;
        notifyListeners();
      }
    } catch (e) {
      await logout();
    }
  }

  Future<void> logout() async {
    try {
      await _apiService.logout();
    } catch (e) {
      // Continue with logout even if API call fails
    }

    _user = null;
    _isAuthenticated = false;
    _clearError();
    notifyListeners();
  }

  void _setLoading(bool loading) {
    _isLoading = loading;
    notifyListeners();
  }

  void _setError(String error) {
    _error = error;
    notifyListeners();
  }

  void _clearError() {
    _error = null;
    notifyListeners();
  }
}
