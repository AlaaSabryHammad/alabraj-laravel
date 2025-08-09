import 'package:flutter/material.dart';
import '../models/project.dart';
import '../services/api_service.dart';

class ProjectProvider extends ChangeNotifier {
  final ApiService _apiService = ApiService();

  List<Project> _projects = [];
  Project? _selectedProject;
  bool _isLoading = false;
  bool _hasMore = true;
  int _currentPage = 1;
  String? _error;

  List<Project> get projects => _projects;
  Project? get selectedProject => _selectedProject;
  bool get isLoading => _isLoading;
  bool get hasMore => _hasMore;
  String? get error => _error;

  Future<void> loadProjects({bool refresh = false}) async {
    if (_isLoading) return;

    if (refresh) {
      _currentPage = 1;
      _hasMore = true;
      _projects.clear();
    }

    _setLoading(true);
    _clearError();

    try {
      final response = await _apiService.getProjects(page: _currentPage);

      if (response['status'] == 'success') {
        final List<dynamic> projectsData = response['data']['data'];
        final List<Project> newProjects =
            projectsData.map((json) => Project.fromJson(json)).toList();

        if (refresh) {
          _projects = newProjects;
        } else {
          _projects.addAll(newProjects);
        }

        _currentPage++;
        _hasMore = projectsData.length == 15; // Assuming 15 is per_page

        notifyListeners();
      } else {
        _setError(response['message'] ?? 'فشل في تحميل المشاريع');
      }
    } catch (e) {
      _setError(e.toString());
    } finally {
      _setLoading(false);
    }
  }

  Future<void> loadProject(int id) async {
    _setLoading(true);
    _clearError();

    try {
      final response = await _apiService.getProject(id);

      if (response['status'] == 'success') {
        _selectedProject = Project.fromJson(response['data']);
        notifyListeners();
      } else {
        _setError(response['message'] ?? 'فشل في تحميل المشروع');
      }
    } catch (e) {
      _setError(e.toString());
    } finally {
      _setLoading(false);
    }
  }

  Future<bool> createProject(Map<String, dynamic> projectData) async {
    _setLoading(true);
    _clearError();

    try {
      final response = await _apiService.createProject(projectData);

      if (response['status'] == 'success') {
        final newProject = Project.fromJson(response['data']);
        _projects.insert(0, newProject);
        notifyListeners();
        return true;
      } else {
        _setError(response['message'] ?? 'فشل في إنشاء المشروع');
        return false;
      }
    } catch (e) {
      _setError(e.toString());
      return false;
    } finally {
      _setLoading(false);
    }
  }

  Future<bool> updateProject(int id, Map<String, dynamic> projectData) async {
    _setLoading(true);
    _clearError();

    try {
      final response = await _apiService.updateProject(id, projectData);

      if (response['status'] == 'success') {
        final updatedProject = Project.fromJson(response['data']);
        final index = _projects.indexWhere((p) => p.id == id);

        if (index != -1) {
          _projects[index] = updatedProject;
        }

        if (_selectedProject?.id == id) {
          _selectedProject = updatedProject;
        }

        notifyListeners();
        return true;
      } else {
        _setError(response['message'] ?? 'فشل في تحديث المشروع');
        return false;
      }
    } catch (e) {
      _setError(e.toString());
      return false;
    } finally {
      _setLoading(false);
    }
  }

  Future<bool> deleteProject(int id) async {
    _setLoading(true);
    _clearError();

    try {
      final response = await _apiService.deleteProject(id);

      if (response['status'] == 'success') {
        _projects.removeWhere((p) => p.id == id);

        if (_selectedProject?.id == id) {
          _selectedProject = null;
        }

        notifyListeners();
        return true;
      } else {
        _setError(response['message'] ?? 'فشل في حذف المشروع');
        return false;
      }
    } catch (e) {
      _setError(e.toString());
      return false;
    } finally {
      _setLoading(false);
    }
  }

  void clearSelectedProject() {
    _selectedProject = null;
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
