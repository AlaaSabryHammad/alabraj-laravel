<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class AdvancedPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // صلاحيات الموظفين المتقدمة
        $employeePermissions = [
            ['name' => 'employees.import', 'display_name' => 'استيراد الموظفين', 'category' => 'الموظفين', 'description' => 'استيراد بيانات الموظفين من ملفات Excel'],
            ['name' => 'employees.export', 'display_name' => 'تصدير الموظفين', 'category' => 'الموظفين', 'description' => 'تصدير بيانات الموظفين إلى ملفات Excel'],
            ['name' => 'employees.backup', 'display_name' => 'نسخ احتياطي للموظفين', 'category' => 'الموظفين', 'description' => 'إنشاء نسخ احتياطية لبيانات الموظفين'],
            ['name' => 'employees.restore', 'display_name' => 'استعادة بيانات الموظفين', 'category' => 'الموظفين', 'description' => 'استعادة بيانات الموظفين من النسخ الاحتياطية'],
            ['name' => 'employees.bulk_actions', 'display_name' => 'العمليات المجمعة للموظفين', 'category' => 'الموظفين', 'description' => 'تنفيذ عمليات على عدة موظفين معاً'],
            ['name' => 'employees.attendance', 'display_name' => 'إدارة حضور الموظفين', 'category' => 'الموظفين', 'description' => 'تسجيل ومراقبة حضور وانصراف الموظفين'],
            ['name' => 'employees.payroll', 'display_name' => 'إدارة كشف المرتبات', 'category' => 'الموظفين', 'description' => 'إعداد وإدارة كشوف مرتبات الموظفين'],
            ['name' => 'employees.performance', 'display_name' => 'تقييم أداء الموظفين', 'category' => 'الموظفين', 'description' => 'تقييم ومراجعة أداء الموظفين'],
            ['name' => 'employees.training', 'display_name' => 'إدارة تدريب الموظفين', 'category' => 'الموظفين', 'description' => 'تنظيم وإدارة برامج تدريب الموظفين'],
            ['name' => 'employees.contracts', 'display_name' => 'إدارة عقود الموظفين', 'category' => 'الموظفين', 'description' => 'إدارة وتجديد عقود العمل'],
            ['name' => 'employees.documents', 'display_name' => 'وثائق الموظفين', 'category' => 'الموظفين', 'description' => 'إدارة الوثائق الشخصية للموظفين'],
            ['name' => 'employees.notifications', 'display_name' => 'إشعارات الموظفين', 'category' => 'الموظفين', 'description' => 'إرسال إشعارات وتنبيهات للموظفين'],
        ];

        // صلاحيات المعدات المتقدمة
        $equipmentPermissions = [
            ['name' => 'equipment.import', 'display_name' => 'استيراد المعدات', 'category' => 'المعدات', 'description' => 'استيراد بيانات المعدات من ملفات خارجية'],
            ['name' => 'equipment.export', 'display_name' => 'تصدير المعدات', 'category' => 'المعدات', 'description' => 'تصدير بيانات المعدات إلى ملفات Excel'],
            ['name' => 'equipment.qr_codes', 'display_name' => 'إدارة أكواد QR للمعدات', 'category' => 'المعدات', 'description' => 'إنشاء وطباعة أكواد QR للمعدات'],
            ['name' => 'equipment.barcode', 'display_name' => 'إدارة الباركود للمعدات', 'category' => 'المعدات', 'description' => 'إنشاء وطباعة باركود للمعدات'],
            ['name' => 'equipment.schedule', 'display_name' => 'جدولة المعدات', 'category' => 'المعدات', 'description' => 'جدولة استخدام المعدات في المشاريع'],
            ['name' => 'equipment.tracking', 'display_name' => 'تتبع المعدات', 'category' => 'المعدات', 'description' => 'تتبع موقع وحالة المعدات'],
            ['name' => 'equipment.history', 'display_name' => 'تاريخ المعدات', 'category' => 'المعدات', 'description' => 'عرض تاريخ استخدام وصيانة المعدات'],
            ['name' => 'equipment.warranty', 'display_name' => 'إدارة ضمان المعدات', 'category' => 'المعدات', 'description' => 'إدارة فترات الضمان والصيانة'],
            ['name' => 'equipment.inspection', 'display_name' => 'فحص المعدات', 'category' => 'المعدات', 'description' => 'إجراء فحوصات دورية للمعدات'],
            ['name' => 'equipment.calibration', 'display_name' => 'معايرة المعدات', 'category' => 'المعدات', 'description' => 'معايرة وضبط المعدات الحساسة'],
            ['name' => 'equipment.rental', 'display_name' => 'تأجير المعدات', 'category' => 'المعدات', 'description' => 'إدارة تأجير المعدات للغير'],
            ['name' => 'equipment.depreciation', 'display_name' => 'استهلاك المعدات', 'category' => 'المعدات', 'description' => 'حساب وإدارة استهلاك قيمة المعدات'],
            ['name' => 'equipment.alerts', 'display_name' => 'تنبيهات المعدات', 'category' => 'المعدات', 'description' => 'إعداد تنبيهات الصيانة والفحص'],
        ];

        // صلاحيات الإعدادات المتقدمة
        $settingsPermissions = [
            ['name' => 'settings.system', 'display_name' => 'إعدادات النظام', 'category' => 'الإعدادات', 'description' => 'تكوين الإعدادات العامة للنظام'],
            ['name' => 'settings.security', 'display_name' => 'إعدادات الأمان', 'category' => 'الإعدادات', 'description' => 'إدارة إعدادات الأمان وكلمات المرور'],
            ['name' => 'settings.database', 'display_name' => 'إعدادات قاعدة البيانات', 'category' => 'الإعدادات', 'description' => 'إدارة إعدادات الاتصال بقاعدة البيانات'],
            ['name' => 'settings.email', 'display_name' => 'إعدادات البريد الإلكتروني', 'category' => 'الإعدادات', 'description' => 'تكوين إعدادات إرسال البريد الإلكتروني'],
            ['name' => 'settings.sms', 'display_name' => 'إعدادات الرسائل النصية', 'category' => 'الإعدادات', 'description' => 'تكوين إعدادات إرسال الرسائل النصية'],
            ['name' => 'settings.notifications', 'display_name' => 'إعدادات الإشعارات', 'category' => 'الإعدادات', 'description' => 'إدارة أنواع وأوقات الإشعارات'],
            ['name' => 'settings.themes', 'display_name' => 'إعدادات المظهر', 'category' => 'الإعدادات', 'description' => 'تخصيص مظهر وألوان النظام'],
            ['name' => 'settings.language', 'display_name' => 'إعدادات اللغة', 'category' => 'الإعدادات', 'description' => 'إدارة اللغات المدعومة في النظام'],
            ['name' => 'settings.timezone', 'display_name' => 'إعدادات المنطقة الزمنية', 'category' => 'الإعدادات', 'description' => 'تحديد المنطقة الزمنية والتاريخ'],
            ['name' => 'settings.currency', 'display_name' => 'إعدادات العملة', 'category' => 'الإعدادات', 'description' => 'تحديد العملة المحلية وأسعار الصرف'],
            ['name' => 'settings.integrations', 'display_name' => 'التكاملات الخارجية', 'category' => 'الإعدادات', 'description' => 'إدارة التكاملات مع الأنظمة الخارجية'],
            ['name' => 'settings.api', 'display_name' => 'إعدادات API', 'category' => 'الإعدادات', 'description' => 'إدارة مفاتيح وإعدادات واجهة البرمجة'],
            ['name' => 'settings.maintenance', 'display_name' => 'وضع الصيانة', 'category' => 'الإعدادات', 'description' => 'تفعيل وإلغاء وضع صيانة النظام'],
            ['name' => 'settings.performance', 'display_name' => 'إعدادات الأداء', 'category' => 'الإعدادات', 'description' => 'تحسين وإدارة أداء النظام'],
            ['name' => 'settings.cache', 'display_name' => 'إدارة التخزين المؤقت', 'category' => 'الإعدادات', 'description' => 'إدارة وحذف ملفات التخزين المؤقت'],
            ['name' => 'settings.logs', 'display_name' => 'إدارة سجلات النظام', 'category' => 'الإعدادات', 'description' => 'عرض وإدارة سجلات النظام والأخطاء'],
        ];

        // دمج جميع الصلاحيات
        $allPermissions = array_merge($employeePermissions, $equipmentPermissions, $settingsPermissions);

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // تحديث صلاحيات المدير العام
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $currentPermissions = $superAdmin->permissions ?? [];
            $newPermissions = collect($allPermissions)->pluck('name')->toArray();
            $allPermissions = array_unique(array_merge($currentPermissions, $newPermissions));
            $superAdmin->update(['permissions' => $allPermissions]);
        }

        // تحديث صلاحيات مدير الموقع
        $siteManager = Role::where('name', 'site_manager')->first();
        if ($siteManager) {
            $currentPermissions = $siteManager->permissions ?? [];
            $siteManagerAdvancedPermissions = [
                // موظفين
                'employees.import',
                'employees.export',
                'employees.bulk_actions',
                'employees.attendance',
                'employees.performance',
                'employees.training',
                'employees.contracts',
                'employees.documents',
                'employees.notifications',
                // معدات
                'equipment.export',
                'equipment.qr_codes',
                'equipment.barcode',
                'equipment.schedule',
                'equipment.tracking',
                'equipment.history',
                'equipment.inspection',
                'equipment.alerts',
                // إعدادات محدودة
                'settings.notifications',
                'settings.themes'
            ];
            $allPermissions = array_unique(array_merge($currentPermissions, $siteManagerAdvancedPermissions));
            $siteManager->update(['permissions' => $allPermissions]);
        }

        // إنشاء دور جديد: مدير تقني
        $techManager = Role::firstOrCreate([
            'name' => 'tech_manager',
            'display_name' => 'مدير تقني',
            'category' => 'التقنية',
            'description' => 'مسؤول عن الإعدادات التقنية وصيانة النظام',
            'is_active' => true
        ]);

        $techManagerPermissions = [
            // جميع صلاحيات الإعدادات
            'settings.view',
            'settings.edit',
            'settings.roles',
            'settings.system',
            'settings.security',
            'settings.database',
            'settings.email',
            'settings.sms',
            'settings.notifications',
            'settings.themes',
            'settings.language',
            'settings.timezone',
            'settings.currency',
            'settings.integrations',
            'settings.api',
            'settings.maintenance',
            'settings.performance',
            'settings.cache',
            'settings.logs',

            // صلاحيات معدات تقنية
            'equipment.view',
            'equipment.edit',
            'equipment.qr_codes',
            'equipment.barcode',
            'equipment.tracking',
            'equipment.calibration',
            'equipment.alerts',
            'equipment.import',
            'equipment.export',

            // صلاحيات موظفين محدودة
            'employees.view',
            'employees.import',
            'employees.export',

            // صلاحيات أخرى
            'system.admin',
            'system.backup',
            'system.logs',
            'reports.view',
            'reports.export'
        ];

        $techManager->update(['permissions' => $techManagerPermissions]);

        // إنشاء دور جديد: مدير موارد بشرية
        $hrManager = Role::firstOrCreate([
            'name' => 'hr_manager',
            'display_name' => 'مدير موارد بشرية',
            'category' => 'الموارد البشرية',
            'description' => 'مسؤول عن إدارة الموارد البشرية والموظفين',
            'is_active' => true
        ]);

        $hrManagerPermissions = [
            // جميع صلاحيات الموظفين
            'employees.view',
            'employees.create',
            'employees.edit',
            'employees.delete',
            'employees.deactivate',
            'employees.import',
            'employees.export',
            'employees.backup',
            'employees.restore',
            'employees.bulk_actions',
            'employees.attendance',
            'employees.payroll',
            'employees.performance',
            'employees.training',
            'employees.contracts',
            'employees.documents',
            'employees.notifications',

            // صلاحيات أخرى مفيدة
            'documents.view',
            'documents.create',
            'documents.upload',
            'documents.download',
            'reports.view',
            'reports.export',
            'reports.advanced',
            'settings.notifications',
            'settings.email'
        ];

        $hrManager->update(['permissions' => $hrManagerPermissions]);

        // تحديث صلاحيات المحاسب
        $accountant = Role::where('name', 'accountant')->first();
        if ($accountant) {
            $currentPermissions = $accountant->permissions ?? [];
            $accountantAdvancedPermissions = [
                'employees.payroll',
                'employees.export',
                'equipment.depreciation'
            ];
            $allPermissions = array_unique(array_merge($currentPermissions, $accountantAdvancedPermissions));
            $accountant->update(['permissions' => $allPermissions]);
        }

        echo "تم إضافة " . count($allPermissions) . " صلاحية متقدمة بنجاح!\n";
        echo "- " . count($employeePermissions) . " صلاحية للموظفين\n";
        echo "- " . count($equipmentPermissions) . " صلاحية للمعدات\n";
        echo "- " . count($settingsPermissions) . " صلاحية للإعدادات\n";
        echo "تم إنشاء دورين جديدين: 'مدير تقني' و 'مدير موارد بشرية'\n";
        echo "تم تحديث الأدوار الموجودة مع الصلاحيات المناسبة.\n";
    }
}
