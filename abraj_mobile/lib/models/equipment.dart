class Equipment {
  final int id;
  final String name;
  final String equipmentId;
  final String category;
  final String? model;
  final String? manufacturer;
  final String? serialNumber;
  final DateTime? purchaseDate;
  final double? purchasePrice;
  final DateTime? warrantyExpiry;
  final String status;
  final String? location;
  final int? projectId;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  Equipment({
    required this.id,
    required this.name,
    required this.equipmentId,
    required this.category,
    this.model,
    this.manufacturer,
    this.serialNumber,
    this.purchaseDate,
    this.purchasePrice,
    this.warrantyExpiry,
    required this.status,
    this.location,
    this.projectId,
    this.createdAt,
    this.updatedAt,
  });

  factory Equipment.fromJson(Map<String, dynamic> json) {
    return Equipment(
      id: json['id'],
      name: json['name'],
      equipmentId: json['equipment_id'],
      category: json['category'],
      model: json['model'],
      manufacturer: json['manufacturer'],
      serialNumber: json['serial_number'],
      purchaseDate: json['purchase_date'] != null
          ? DateTime.parse(json['purchase_date'])
          : null,
      purchasePrice: json['purchase_price'] != null
          ? double.tryParse(json['purchase_price'].toString())
          : null,
      warrantyExpiry: json['warranty_expiry'] != null
          ? DateTime.parse(json['warranty_expiry'])
          : null,
      status: json['status'],
      location: json['location'],
      projectId: json['project_id'],
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
      'equipment_id': equipmentId,
      'category': category,
      'model': model,
      'manufacturer': manufacturer,
      'serial_number': serialNumber,
      'purchase_date': purchaseDate?.toIso8601String().split('T')[0],
      'purchase_price': purchasePrice,
      'warranty_expiry': warrantyExpiry?.toIso8601String().split('T')[0],
      'status': status,
      'location': location,
      'project_id': projectId,
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }

  String get statusDisplayName {
    switch (status) {
      case 'active':
        return 'نشط';
      case 'maintenance':
        return 'صيانة';
      case 'retired':
        return 'معطل';
      case 'damaged':
        return 'تالف';
      default:
        return status;
    }
  }

  bool get isWarrantyExpired {
    if (warrantyExpiry == null) return false;
    return DateTime.now().isAfter(warrantyExpiry!);
  }
}
