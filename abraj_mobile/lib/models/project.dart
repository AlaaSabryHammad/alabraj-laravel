import 'package:flutter/material.dart';

class Project {
  final int id;
  final String name;
  final String? description;
  final DateTime startDate;
  final DateTime? endDate;
  final String status;
  final double? budget;
  final int? locationId;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  Project({
    required this.id,
    required this.name,
    this.description,
    required this.startDate,
    this.endDate,
    required this.status,
    this.budget,
    this.locationId,
    this.createdAt,
    this.updatedAt,
  });

  factory Project.fromJson(Map<String, dynamic> json) {
    return Project(
      id: json['id'],
      name: json['name'],
      description: json['description'],
      startDate: DateTime.parse(json['start_date']),
      endDate:
          json['end_date'] != null ? DateTime.parse(json['end_date']) : null,
      status: json['status'],
      budget: json['budget'] != null
          ? double.tryParse(json['budget'].toString())
          : null,
      locationId: json['location_id'],
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'start_date': startDate.toIso8601String().split('T')[0],
      'end_date': endDate?.toIso8601String().split('T')[0],
      'status': status,
      'budget': budget,
      'location_id': locationId,
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }

  String get statusDisplayName {
    switch (status) {
      case 'planning':
        return 'التخطيط';
      case 'active':
        return 'نشط';
      case 'completed':
        return 'مكتمل';
      case 'suspended':
        return 'معلق';
      default:
        return status;
    }
  }

  Color get statusColor {
    switch (status) {
      case 'planning':
        return Colors.orange;
      case 'active':
        return Colors.green;
      case 'completed':
        return Colors.blue;
      case 'suspended':
        return Colors.red;
      default:
        return Colors.grey;
    }
  }
}
