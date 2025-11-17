<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بطاقة الموظف - {{ $employee->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Tajawal', Arial, sans-serif; background: white; color: #333; line-height: 1.5; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; }
        .header { background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 50%, #7c3aed 100%); color: white; padding: 25px; border-radius: 8px 8px 0 0; text-align: center; }
        .header h1 { font-size: 22px; margin-bottom: 8px; font-weight: 700; }
        .header p { font-size: 14px; opacity: 0.9; margin-bottom: 12px; }
        .header-badge { display: inline-block; background: rgba(255,255,255,0.2); padding: 10px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .section { background: white; border: 1px solid #ddd; padding: 18px; border-bottom: none; }
        .section:last-of-type { border-radius: 0 0 8px 8px; }
        .section-title { display: flex; align-items: center; font-size: 17px; font-weight: 700; margin-bottom: 16px; color: #1e40af; border-bottom: 2px solid #1e40af; padding-bottom: 12px; }
        .section-title-icon { width: 26px; height: 26px; background: #1e40af; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; margin-left: 10px; font-size: 14px; }
        .profile-section { display: flex; gap: 20px; margin-bottom: 0; }
        .photo-box { width: 130px; height: 130px; border: 3px solid #ddd; border-radius: 8px; overflow: hidden; background: #f5f5f5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; }
        .employee-id { position: absolute; bottom: -1px; left: 0; right: 0; background: #1e40af; color: white; text-align: center; padding: 5px; font-weight: bold; font-size: 12px; }
        .profile-details { flex: 1; }
        .employee-name { font-size: 20px; font-weight: 700; color: #000; margin-bottom: 5px; }
        .employee-position { font-size: 16px; color: #1e40af; font-weight: 600; margin-bottom: 8px; }
        .status-badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 12px; }
        .status-active { background-color: #dcfce7; color: #166534; }
        .status-inactive { background-color: #fee2e2; color: #991b1b; }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .info-box { background: #f9f9f9; padding: 10px; border-radius: 6px; border-left: 3px solid #1e40af; }
        .info-label { font-size: 12px; color: #666; font-weight: 600; margin-bottom: 4px; text-transform: uppercase; }
        .info-value { font-size: 13px; font-weight: 600; color: #000; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
        .doc-section { display: flex; gap: 15px; margin-bottom: 16px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .doc-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .doc-image { width: 100px; height: 100px; border: 2px solid #ddd; border-radius: 6px; overflow: hidden; background: #f5f5f5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 36px; }
        .doc-image img { width: 100%; height: 100%; object-fit: cover; }
        .doc-info { flex: 1; }
        .doc-title { font-size: 14px; font-weight: 700; color: #000; margin-bottom: 8px; }
        .doc-status { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; margin-bottom: 8px; }
        .doc-status-yes { background: #dcfce7; color: #166534; }
        .doc-status-no { background: #fee2e2; color: #991b1b; }
        .doc-details { font-size: 12px; color: #666; line-height: 1.6; }
        .footer-section { background: white; border: 1px solid #ddd; padding: 18px; border-radius: 0 0 8px 8px; margin-top: 0; }
        .signatures { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 2px solid #ddd; }
        .signature-box { text-align: center; }
        .signature-label { font-size: 13px; font-weight: 600; color: #000; margin-bottom: 12px; }
        .signature-line { height: 40px; border-bottom: 2px solid #000; margin-bottom: 8px; }
        .company-info { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 15px; }
        .company-detail-title { font-size: 12px; color: #666; font-weight: 600; margin-bottom: 4px; }
        .company-detail { font-size: 13px; color: #000; font-weight: 600; }
        .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 120px; color: rgba(30, 64, 175, 0.03); z-index: -1; font-weight: 900; }
        @media print { body { padding: 0; } .container { max-width: 100%; } .section, .header, .footer-section { page-break-inside: avoid; } }
    </style>
</head>
<body>
    <div class="watermark">شركة الأبراج</div>
    <div class="container">
        <div class="header">
            <h1>شركة الأبراج للمقاولات المحدودة</h1>
            <p>Al-Abraj Contracting Company Limited</p>
            <div class="header-badge">بطاقة تعريف الموظف - Employee ID Card</div>
        </div>
        <!-- Profile Section -->
        <div class="section">
            <div class="profile-section">
                <div class="photo-box">
                    @if($employee->photo)
                        <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->name }}">
                    @else
                        <div style="font-size: 40px; color: #ccc;">👤</div>
                    @endif
                    <div class="employee-id">#{{ str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div class="profile-details">
                    <div class="employee-name">{{ $employee->name }}</div>
                    <div class="employee-position">{{ $employee->position ?? 'موظف' }}</div>
                    <div class="status-badge {{ $employee->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        {{ $employee->status === 'active' ? '✓ نشط' : '✗ غير نشط' }}
                    </div>
                    <div class="info-grid">
                        <div class="info-box">
                            <div class="info-label">القسم</div>
                            <div class="info-value">{{ $employee->department ?? 'غير محدد' }}</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">الموقع</div>
                            <div class="info-value">{{ $employee->location ? $employee->location->name : 'غير محدد' }}</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">الصلاحية</div>
                            <div class="info-value">{{ $employee->role ?? 'غير محدد' }}</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">التوظيف</div>
                            <div class="info-value">{{ $employee->hire_date ? $employee->hire_date->format('Y/m/d') : 'غير محدد' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="section">
            <div class="section-title">
                <div class="section-title-icon">ℹ</div>
                المعلومات الشخصية
            </div>
            <div class="grid-4">
                <div class="info-box">
                    <div class="info-label">رقم الهوية</div>
                    <div class="info-value">{{ $employee->national_id ?? 'غير محدد' }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">الهاتف</div>
                    <div class="info-value">{{ $employee->phone ?? 'غير محدد' }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">البريد</div>
                    <div class="info-value">{{ $employee->email ?? 'غير محدد' }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">الجنسية</div>
                    <div class="info-value">{{ $employee->nationality ?? 'غير محدد' }}</div>
                </div>
                @if($employee->hire_date)
                <div class="info-box">
                    <div class="info-label">مدة الخدمة</div>
                    <div class="info-value">{{ round($employee->hire_date->diffInYears()) }} س</div>
                </div>
                @endif
                <div class="info-box">
                    <div class="info-label">الفئة</div>
                    <div class="info-value">{{ $employee->category ?? 'غير محدد' }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">الراتب الأساسي</div>
                    <div class="info-value">{{ $employee->salary ? number_format((float)$employee->salary) . ' ر.س' : 'غير محدد' }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">التأمين الطبي</div>
                    <div class="info-value">{{ $employee->medical_insurance_status ?? 'غير محدد' }}</div>
                </div>
            </div>
        </div>

        <!-- Financial Information -->
        @if($employee->bank_name || $employee->iban)
        <div class="section">
            <div class="section-title">
                <div class="section-title-icon">💰</div>
                المعلومات المالية
            </div>
            <div class="grid-2">
                @if($employee->bank_name)
                <div class="info-box">
                    <div class="info-label">البنك</div>
                    <div class="info-value">{{ $employee->bank_name }}</div>
                </div>
                @endif
                @if($employee->iban)
                <div class="info-box">
                    <div class="info-label">الآيبان</div>
                    <div class="info-value" style="font-family: monospace; font-size: 11px;">SA{{ $employee->iban }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Documents Section -->
        <div class="section">
            <div class="section-title">
                <div class="section-title-icon">📄</div>
                الوثائق والمستندات
            </div>
            
            <!-- National ID -->
            <div class="doc-section">
                <div class="doc-image">
                    @if($employee->national_id_photo)
                        <img src="{{ asset('storage/' . $employee->national_id_photo) }}" alt="الهوية">
                    @else
                        <div>🆔</div>
                    @endif
                </div>
                <div class="doc-info">
                    <div class="doc-title">الهوية الوطنية</div>
                    <div class="doc-status {{ $employee->national_id_photo ? 'doc-status-yes' : 'doc-status-no' }}">
                        {{ $employee->national_id_photo ? 'متوفرة ✓' : 'غير متوفرة ✗' }}
                    </div>
                    <div class="doc-details">
                        @if($employee->national_id)
                        <strong>الرقم:</strong> {{ $employee->national_id }}<br>
                        @endif
                        @if($employee->national_id_expiry_date)
                        <strong>تنتهي في:</strong> {{ $employee->national_id_expiry_date->format('Y/m/d') }}<br>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Passport -->
            <div class="doc-section">
                <div class="doc-image">
                    @if($employee->passport_photo)
                        <img src="{{ asset('storage/' . $employee->passport_photo) }}" alt="جواز">
                    @else
                        <div>📕</div>
                    @endif
                </div>
                <div class="doc-info">
                    <div class="doc-title">جواز السفر</div>
                    <div class="doc-status {{ $employee->passport_photo ? 'doc-status-yes' : 'doc-status-no' }}">
                        {{ $employee->passport_photo ? 'متوفر ✓' : 'غير متوفر ✗' }}
                    </div>
                    <div class="doc-details">
                        @if($employee->passport_number)
                        <strong>الرقم:</strong> {{ $employee->passport_number }}<br>
                        @endif
                        @if($employee->passport_expiry_date)
                        <strong>ينتهي في:</strong> {{ $employee->passport_expiry_date->format('Y/m/d') }}<br>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Work Permit -->
            <div class="doc-section">
                <div class="doc-image">
                    @if($employee->work_permit_photo)
                        <img src="{{ asset('storage/' . $employee->work_permit_photo) }}" alt="التشغيل">
                    @else
                        <div>💼</div>
                    @endif
                </div>
                <div class="doc-info">
                    <div class="doc-title">بطاقة التشغيل</div>
                    <div class="doc-status {{ $employee->work_permit_photo ? 'doc-status-yes' : 'doc-status-no' }}">
                        {{ $employee->work_permit_photo ? 'متوفرة ✓' : 'غير متوفرة ✗' }}
                    </div>
                    <div class="doc-details">
                        @if($employee->work_permit_number)
                        <strong>الرقم:</strong> {{ $employee->work_permit_number }}<br>
                        @endif
                        @if($employee->work_permit_expiry_date)
                        <strong>تنتهي في:</strong> {{ $employee->work_permit_expiry_date->format('Y/m/d') }}<br>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div class="footer-section">
            <div class="signatures">
                <div class="signature-box">
                    <div class="signature-label">توقيع المسؤول</div>
                    <div class="signature-line"></div>
                    <div style="font-size: 11px; color: #666; margin-top: 5px;">التاريخ: ___________</div>
                </div>
                <div class="signature-box">
                    <div style="font-size: 24px; font-weight: bold; color: #1e40af; margin: 15px 0;">ختم</div>
                    <div style="font-size: 11px; color: #666; margin-top: 20px;">الشركة</div>
                </div>
                <div class="signature-box">
                    <div class="signature-label">توقيع الموظف</div>
                    <div class="signature-line"></div>
                    <div style="font-size: 11px; color: #666; margin-top: 5px;">التاريخ: ___________</div>
                </div>
            </div>

            <div class="company-info">
                <div>
                    <div class="company-detail-title">الموقع</div>
                    <div class="company-detail">🇸🇦 المملكة العربية السعودية</div>
                </div>
                <div>
                    <div class="company-detail-title">الهاتف</div>
                    <div class="company-detail">+966 XX XXX XXXX</div>
                </div>
                <div>
                    <div class="company-detail-title">البريد الإلكتروني</div>
                    <div class="company-detail">info@abraj.com</div>
                </div>
            </div>

            <div style="text-align: center; font-size: 11px; color: #666; margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                تم إنشاء هذه الوثيقة في: {{ now()->format('Y/m/d H:i') }}
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            console.log('Page loaded');
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>