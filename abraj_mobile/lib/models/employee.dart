class Employee {
  final int id;
  final String name;
  final String email;
  final String phone;
  final int departmentId;
  final String position;
  final DateTime hireDate;
  final double? salary;
  final String employeeId;
  final String nationalId;
  final String? passportNumber;
  final String? iqamaNumber;
  final String status;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  Employee({
    required this.id,
    required this.name,
    required this.email,
    required this.phone,
    required this.departmentId,
    required this.position,
    required this.hireDate,
    this.salary,
    required this.employeeId,
    required this.nationalId,
    this.passportNumber,
    this.iqamaNumber,
    required this.status,
    this.createdAt,
    this.updatedAt,
  });

  factory Employee.fromJson(Map<String, dynamic> json) {
    return Employee(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      phone: json['phone'],
      departmentId: json['department_id'],
      position: json['position'],
      hireDate: DateTime.parse(json['hire_date']),
      salary: json['salary'] != null
          ? double.tryParse(json['salary'].toString())
          : null,
      employeeId: json['employee_id'],
      nationalId: json['national_id'],
      passportNumber: json['passport_number'],
      iqamaNumber: json['iqama_number'],
      status: json['status'],
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
      'email': email,
      'phone': phone,
      'department_id': departmentId,
      'position': position,
      'hire_date': hireDate.toIso8601String().split('T')[0],
      'salary': salary,
      'employee_id': employeeId,
      'national_id': nationalId,
      'passport_number': passportNumber,
      'iqama_number': iqamaNumber,
      'status': status,
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }

  String get statusDisplayName {
    switch (status) {
      case 'active':
        return 'نشط';
      case 'inactive':
        return 'غير نشط';
      case 'terminated':
        return 'منتهي الخدمة';
      default:
        return status;
    }
  }
}
