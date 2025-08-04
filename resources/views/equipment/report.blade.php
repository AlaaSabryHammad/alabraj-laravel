<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير المعدة: {{ $equipment->name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', Arial, sans-serif;
            direction: rtl;
            line-height: 1.7;
            color: #2d3748;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #6366f1 100%);
            color: white;
            padding: 40px 50px;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="25" cy="75" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="75" cy="25" r="1" fill="%23ffffff" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            animation: grain 20s linear infinite;
        }

        @keyframes grain {
            0%, 100% { transform: translate(0, 0) }
            10% { transform: translate(-5%, -10%) }
            20% { transform: translate(-15%, 5%) }
            30% { transform: translate(7%, -25%) }
            40% { transform: translate(-5%, 25%) }
            50% { transform: translate(-15%, 10%) }
            60% { transform: translate(15%, 0%) }
            70% { transform: translate(0%, 15%) }
            80% { transform: translate(3%, 35%) }
            90% { transform: translate(-10%, 10%) }
        }

        .header-content {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 30px;
        }

        .logo-container {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 10px;
            overflow: hidden;
        }

        .logo-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 10px;
        }

        .logo-placeholder {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 800;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header-text {
            text-align: center;
        }

        .company-name {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 8px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            color: #ffffff;
        }

        .company-subtitle {
            font-size: 16px;
            color: #f1f5f9;
            font-weight: 500;
            margin-bottom: 15px;
        }

        .report-title {
            font-size: 28px;
            font-weight: 700;
            color: #fbbf24;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .print-info {
            text-align: center;
            font-size: 14px;
            color: #f1f5f9;
        }

        .print-button {
            background: linear-gradient(45deg, #10b981, #34d399);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            font-family: inherit;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
            position: relative;
            z-index: 3;
        }

        .print-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.6);
        }

        .content {
            padding: 50px;
        }

        .section {
            margin-bottom: 40px;
            background: #f8fafc;
            border-radius: 16px;
            padding: 30px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .section:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid #dbeafe;
            position: relative;
        }

        .section-title::before {
            content: '';
            position: absolute;
            bottom: -3px;
            right: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(45deg, #3b82f6, #6366f1);
            border-radius: 2px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-label::before {
            content: '●';
            color: #3b82f6;
            font-size: 12px;
        }

        .info-value {
            color: #374151;
            font-size: 14px;
            line-height: 1.6;
            font-weight: 500;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-available {
            background: linear-gradient(45deg, #dcfce7, #bbf7d0);
            color: #166534;
            border: 1px solid #86efac;
        }
        .status-in_use {
            background: linear-gradient(45deg, #dbeafe, #bfdbfe);
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        .status-maintenance {
            background: linear-gradient(45deg, #fef3c7, #fed7aa);
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        .status-out_of_service {
            background: linear-gradient(45deg, #fee2e2, #fecaca);
            color: #991b1b;
            border: 1px solid #f87171;
        }

        .equipment-images {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .image-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .image-item:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .equipment-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 2px solid #e5e7eb;
        }

        .image-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(45deg, #f3f4f6, #e5e7eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 48px;
            border-bottom: 2px solid #e5e7eb;
        }

        .image-caption {
            padding: 10px 15px;
            font-size: 12px;
            color: #374151;
            text-align: center;
            font-weight: 500;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .table th, .table td {
            padding: 16px 20px;
            text-align: right;
            border-bottom: 1px solid #f1f5f9;
            color: #1f2937;
        }

        .table th {
            background: linear-gradient(45deg, #1e293b, #334155);
            color: white;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table th:first-child {
            border-top-right-radius: 12px;
        }

        .table th:last-child {
            border-top-left-radius: 12px;
        }

        .table tr:nth-child(even) {
            background: #f8fafc;
        }

        .table tr:hover {
            background: #e2e8f0;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        .current-driver {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 2px solid #3b82f6;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .current-driver::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1), transparent);
        }

        .current-driver-title {
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 15px;
            font-size: 18px;
            position: relative;
            z-index: 1;
        }

        .driver-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            position: relative;
            z-index: 1;
        }

        .driver-detail-item {
            background: rgba(255, 255, 255, 0.9);
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #1f2937;
        }

        .no-data {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            padding: 40px 20px;
            background: #f9fafb;
            border-radius: 12px;
            border: 2px dashed #d1d5db;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .footer {
            background: linear-gradient(135deg, #1f2937, #374151);
            color: white;
            padding: 30px 50px;
            text-align: center;
            font-size: 14px;
            line-height: 1.6;
        }

        .footer-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                font-size: 11px !important;
                line-height: 1.4 !important;
            }
            .report-container {
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            .print-button {
                display: none !important;
            }
            .section {
                page-break-inside: avoid;
                margin-bottom: 20px !important;
                box-shadow: none !important;
                padding: 15px !important;
            }
            .header::before {
                display: none !important;
            }
            .header {
                padding: 20px 30px !important;
            }
            .content {
                padding: 20px !important;
            }
            .company-name {
                font-size: 24px !important;
            }
            .company-subtitle {
                font-size: 12px !important;
            }
            .report-title {
                font-size: 18px !important;
            }
            .section-title {
                font-size: 16px !important;
                margin-bottom: 12px !important;
                padding-bottom: 8px !important;
            }
            .info-grid {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 10px !important;
            }
            .info-item {
                padding: 10px !important;
                margin-bottom: 8px !important;
            }
            .info-label {
                font-size: 11px !important;
                margin-bottom: 4px !important;
            }
            .info-value {
                font-size: 10px !important;
            }
            .table {
                font-size: 9px !important;
                margin-top: 10px !important;
            }
            .table th {
                padding: 8px 10px !important;
                font-size: 8px !important;
            }
            .table td {
                padding: 6px 8px !important;
                font-size: 9px !important;
            }
            .current-driver {
                padding: 12px !important;
                margin-bottom: 12px !important;
            }
            .current-driver-title {
                font-size: 14px !important;
                margin-bottom: 8px !important;
            }
            .driver-details {
                gap: 8px !important;
            }
            .driver-detail-item {
                padding: 6px 8px !important;
                font-size: 9px !important;
            }
            .highlight-box {
                padding: 12px !important;
                margin: 10px 0 !important;
            }
            .highlight-title {
                font-size: 14px !important;
                margin-bottom: 4px !important;
            }
            .highlight-value {
                font-size: 16px !important;
            }
            .equipment-images {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 15px !important;
            }
            .image-item {
                height: 120px !important;
            }
            .equipment-image,
            .image-placeholder {
                height: 100px !important;
            }
            .image-caption {
                padding: 4px 6px !important;
                font-size: 8px !important;
            }
            .no-data {
                padding: 20px 12px !important;
                font-size: 10px !important;
            }
            .status-badge {
                padding: 4px 8px !important;
                font-size: 8px !important;
            }
            .footer {
                padding: 15px 30px !important;
                font-size: 10px !important;
            }
            .footer-title {
                font-size: 12px !important;
                margin-bottom: 6px !important;
            }
            .logo-container {
                width: 80px !important;
                height: 80px !important;
                padding: 8px !important;
            }
            .header-content {
                gap: 15px !important;
            }
            .print-info {
                font-size: 10px !important;
            }
        }

        .highlight-box {
            background: linear-gradient(45deg, #fef3c7, #fde68a);
            border: 2px solid #f59e0b;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .highlight-title {
            font-size: 18px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 8px;
        }

        .highlight-value {
            font-size: 24px;
            font-weight: 800;
            color: #78350f;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="header">
            <div class="header-content">
                <div class="logo-container">
                    <img src="{{ asset('assets/logo.png') }}"
                         alt="شعار شركة الأبراج"
                         style="width: 100%; height: 100%; object-fit: contain; border-radius: 15px;"
                         onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'logo-placeholder\'>أبراج</div>'">
                </div>

                <div class="header-text">
                    <div class="company-name">شركة الأبراج للمقاولات المحدودة</div>
                    <div class="company-subtitle">إدارة المعدات والآليات الثقيلة</div>
                    <div class="report-title">تقرير تفصيلي للمعدة</div>
                </div>

                <div class="print-info">
                    <button class="print-button" onclick="window.print()">
                        🖨️ طباعة التقرير
                    </button>
                    <div style="margin-top: 15px; font-size: 12px;">
                        <div>تاريخ الطباعة</div>
                        <div>{{ now()->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

        <div class="content">
            <!-- Equipment Name Highlight -->
            <div class="highlight-box">
                <div class="highlight-title">{{ $equipment->name }}</div>
                <div class="highlight-value">رقم المعدة: #{{ $equipment->id }}</div>
            </div>

            <!-- Equipment Images Section -->
            @if($equipment->images && count($equipment->images) > 0)
            <div class="section">
                <div class="section-title">📸 صور المعدة</div>
                <div class="equipment-images">
                    @foreach($equipment->images as $index => $image)
                    <div class="image-item">
                        <img src="{{ asset('storage/' . $image) }}"
                             alt="صورة المعدة {{ $index + 1 }}"
                             class="equipment-image"
                             onerror="this.parentElement.innerHTML='<div class=\'image-placeholder\'><i class=\'📷\'></i></div>'">
                        <div class="image-caption">صورة {{ $index + 1 }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="section">
                <div class="section-title">📸 صور المعدة</div>
                <div class="no-data">
                    <div style="font-size: 48px; margin-bottom: 15px;">📷</div>
                    <div>لا توجد صور متاحة لهذه المعدة</div>
                </div>
            </div>
            @endif

            <!-- Basic Information -->
            <div class="section">
                <div class="section-title">ℹ️ المعلومات الأساسية للمعدة</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">رقم اللوحة</div>
                        <div class="info-value">{{ $equipment->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">النوع</div>
                        <div class="info-value">{{ $equipment->type }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">الطراز</div>
                        <div class="info-value">{{ $equipment->model ?? 'غير محدد' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">الرقم التسلسلي</div>
                        <div class="info-value">{{ $equipment->serial_number ?? 'غير محدد' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">الحالة الحالية</div>
                        <div class="info-value">
                            <span class="status-badge status-{{ $equipment->status }}">
                                @if($equipment->status === 'available') ✅ متاحة
                                @elseif($equipment->status === 'in_use') 🔧 قيد الاستخدام
                                @elseif($equipment->status === 'maintenance') ⚠️ تحت الصيانة
                                @else ❌ خارج الخدمة
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="info-item full-width">
                        <div class="info-label">الوصف</div>
                        <div class="info-value">{{ $equipment->description ?? 'لا يوجد وصف متاح' }}</div>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="section">
                <div class="section-title">💰 المعلومات المالية والتجارية</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">سعر الشراء</div>
                        <div class="info-value">{{ number_format($equipment->purchase_price, 2) }} ريال سعودي</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">تاريخ الشراء</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($equipment->purchase_date)->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">تاريخ انتهاء الضمان</div>
                        <div class="info-value">
                            @if($equipment->warranty_expiry)
                                {{ \Carbon\Carbon::parse($equipment->warranty_expiry)->format('d/m/Y') }}
                                @php
                                    $isExpired = \Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($equipment->warranty_expiry));
                                @endphp
                                @if($isExpired)
                                    <span class="status-badge status-out_of_service">❌ منتهي الصلاحية</span>
                                @else
                                    <span class="status-badge status-available">✅ ساري المفعول</span>
                                @endif
                            @else
                                غير محدد
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">المورد</div>
                        <div class="info-value">{{ $equipment->supplier ?? 'غير محدد' }}</div>
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="section">
                <div class="section-title">📍 معلومات الموقع والتشغيل</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">الموقع الحالي</div>
                        <div class="info-value">{{ $equipment->locationDetail ? $equipment->locationDetail->name : 'غير محدد' }}</div>
                    </div>
                </div>
            </div>

            <!-- Current Driver -->
            @if($equipment->driver)
            <div class="section">
                <div class="section-title">👨‍💼 السائق المكلف حالياً</div>
                <div class="current-driver">
                    <div class="current-driver-title">🚗 {{ $equipment->driver->name }}</div>
                    <div class="driver-details">
                        <div class="driver-detail-item">
                            <strong>المنصب:</strong> {{ $equipment->driver->position ?? 'سائق' }}
                        </div>
                        <div class="driver-detail-item">
                            <strong>الهاتف:</strong> {{ $equipment->driver->phone ?? 'غير محدد' }}
                        </div>
                        <div class="driver-detail-item">
                            <strong>البريد الإلكتروني:</strong> {{ $equipment->driver->email ?? 'غير محدد' }}
                        </div>
                        <div class="driver-detail-item">
                            <strong>القسم:</strong> {{ $equipment->driver->department ?? 'غير محدد' }}
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="section">
                <div class="section-title">👨‍💼 السائق المكلف</div>
                <div class="no-data">
                    <div style="font-size: 48px; margin-bottom: 15px;">🚫</div>
                    <div>لا يوجد سائق مكلف حالياً</div>
                </div>
            </div>
            @endif

            <!-- Files and Documents -->
            @if($equipment->files && count($equipment->files) > 0)
            <div class="section">
                <div class="section-title">📁 الملفات والوثائق المرفقة</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>📄 اسم الملف</th>
                            <th>📋 نوع الملف</th>
                            <th>📅 تاريخ الرفع</th>
                            <th>⏰ تاريخ الانتهاء</th>
                            <th>✅ حالة الصلاحية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($equipment->files as $file)
                        <tr>
                            <td>{{ $file->original_name }}</td>
                            <td>{{ $file->file_type }}</td>
                            <td>{{ $file->created_at->format('d/m/Y') }}</td>
                            <td>{{ $file->expiry_date ? \Carbon\Carbon::parse($file->expiry_date)->format('d/m/Y') : 'غير محدد' }}</td>
                            <td>
                                @if($file->expiry_date && \Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($file->expiry_date)))
                                    <span class="status-badge status-out_of_service">❌ منتهي الصلاحية</span>
                                @else
                                    <span class="status-badge status-available">✅ ساري المفعول</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="section">
                <div class="section-title">📁 الملفات والوثائق</div>
                <div class="no-data">
                    <div style="font-size: 48px; margin-bottom: 15px;">📂</div>
                    <div>لا توجد ملفات مرفقة</div>
                </div>
            </div>
            @endif

            <!-- Driver History -->
            @if($equipment->driverHistory && count($equipment->driverHistory) > 0)
            <div class="section">
                <div class="section-title">👥 تاريخ السائقين</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>👨‍💼 اسم السائق</th>
                            <th>📅 تاريخ التكليف</th>
                            <th>📅 تاريخ الانتهاء</th>
                            <th>📊 الحالة</th>
                            <th>📝 ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($equipment->driverHistory as $history)
                        <tr>
                            <td>{{ $history->driver ? $history->driver->name : 'سائق محذوف' }}</td>
                            <td>{{ $history->assigned_at ? \Carbon\Carbon::parse($history->assigned_at)->format('d/m/Y H:i') : 'غير محدد' }}</td>
                            <td>{{ $history->released_at ? \Carbon\Carbon::parse($history->released_at)->format('d/m/Y H:i') : 'مستمر' }}</td>
                            <td>
                                <span class="status-badge status-{{ $history->status === 'active' ? 'in_use' : 'available' }}">
                                    {{ $history->status === 'active' ? '🟢 نشط' : '🔴 منتهي' }}
                                </span>
                            </td>
                            <td>{{ $history->notes ?? 'لا توجد ملاحظات' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Movement History -->
            @if($equipment->movementHistory && count($equipment->movementHistory) > 0)
            <div class="section">
                <div class="section-title">🚚 تاريخ تحركات المعدة</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>📍 من الموقع</th>
                            <th>📍 إلى الموقع</th>
                            <th>📅 تاريخ النقل</th>
                            <th>🔄 نوع الحركة</th>
                            <th>📝 السبب</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($equipment->movementHistory as $movement)
                        <tr>
                            <td>{{ $movement->fromLocation ? $movement->fromLocation->name : ($movement->from_location_text ?? 'غير محدد') }}</td>
                            <td>{{ $movement->toLocation ? $movement->toLocation->name : ($movement->to_location_text ?? 'غير محدد') }}</td>
                            <td>{{ $movement->moved_at ? \Carbon\Carbon::parse($movement->moved_at)->format('d/m/Y H:i') : 'غير محدد' }}</td>
                            <td>
                                @if($movement->movement_type === 'location_change') 🔄 تغيير موقع
                                @elseif($movement->movement_type === 'initial_assignment') 🆕 تعيين أولي
                                @elseif($movement->movement_type === 'maintenance') 🔧 صيانة
                                @elseif($movement->movement_type === 'disposal') 🗑️ إتلاف
                                @else {{ $movement->movement_type }}
                                @endif
                            </td>
                            <td>{{ $movement->reason ?? 'غير محدد' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- System Information -->
            <div class="section">
                <div class="section-title">🖥️ معلومات النظام</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">تاريخ إدخال المعدة للنظام</div>
                        <div class="info-value">{{ $equipment->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">آخر تحديث للبيانات</div>
                        <div class="info-value">{{ $equipment->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">رقم المعدة في النظام</div>
                        <div class="info-value">#{{ $equipment->id }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">تاريخ إنشاء التقرير</div>
                        <div class="info-value">{{ now()->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-title">🏢 شركة الأبراج للمقاولات المحدودة</div>
            <div>هذا التقرير تم إنشاؤه آلياً من نظام إدارة المعدات والآليات الثقيلة</div>
            <div>📞 للاستفسارات، يرجى التواصل مع قسم تقنية المعلومات</div>
            <div style="margin-top: 15px; opacity: 0.8;">
                📧 info@abraj.com | 📱 +966 XX XXX XXXX | 🌐 www.abraj.com
            </div>
        </div>
    </div>
</body>
</html>
