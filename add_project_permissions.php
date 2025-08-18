<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Permission;
use App\Models\Role;

try {
    echo "🚀 إضافة صلاحيات المشاريع\n\n";

    // صلاحيات المشاريع الشاملة
    $projectPermissions = [
        // إدارة المشاريع الأساسية
        ['name' => 'projects.view', 'display_name' => 'عرض المشاريع', 'category' => 'المشاريع', 'description' => 'عرض قائمة المشاريع ومعلوماتها'],
        ['name' => 'projects.create', 'display_name' => 'إنشاء مشروع', 'category' => 'المشاريع', 'description' => 'إنشاء مشروع جديد'],
        ['name' => 'projects.edit', 'display_name' => 'تعديل مشروع', 'category' => 'المشاريع', 'description' => 'تعديل معلومات المشاريع الموجودة'],
        ['name' => 'projects.delete', 'display_name' => 'حذف مشروع', 'category' => 'المشاريع', 'description' => 'حذف المشاريع'],
        ['name' => 'projects.manage', 'display_name' => 'إدارة المشاريع', 'category' => 'المشاريع', 'description' => 'إدارة شاملة للمشاريع'],

        // تخطيط وجدولة المشاريع
        ['name' => 'projects.planning', 'display_name' => 'تخطيط المشاريع', 'category' => 'المشاريع', 'description' => 'وضع خطط وجداول زمنية للمشاريع'],
        ['name' => 'projects.timeline', 'display_name' => 'إدارة الجدول الزمني', 'category' => 'المشاريع', 'description' => 'إدارة الجداول الزمنية والمواعيد'],
        ['name' => 'projects.milestones', 'display_name' => 'إدارة المعالم', 'category' => 'المشاريع', 'description' => 'تحديد وإدارة معالم المشروع'],
        ['name' => 'projects.tasks', 'display_name' => 'إدارة المهام', 'category' => 'المشاريع', 'description' => 'إدارة مهام المشروع وتوزيعها'],

        // موارد المشروع
        ['name' => 'projects.resources', 'display_name' => 'إدارة موارد المشروع', 'category' => 'المشاريع', 'description' => 'إدارة الموارد المخصصة للمشروع'],
        ['name' => 'projects.assign_team', 'display_name' => 'تعيين فريق العمل', 'category' => 'المشاريع', 'description' => 'تعيين وإدارة فريق العمل للمشروع'],
        ['name' => 'projects.assign_equipment', 'display_name' => 'تخصيص المعدات', 'category' => 'المشاريع', 'description' => 'تخصيص المعدات للمشاريع'],
        ['name' => 'projects.materials', 'display_name' => 'إدارة مواد المشروع', 'category' => 'المشاريع', 'description' => 'إدارة المواد المطلوبة للمشروع'],

        // متابعة ومراقبة المشاريع
        ['name' => 'projects.track_progress', 'display_name' => 'متابعة تقدم المشروع', 'category' => 'المشاريع', 'description' => 'متابعة ومراقبة تقدم المشروع'],
        ['name' => 'projects.status_update', 'display_name' => 'تحديث حالة المشروع', 'category' => 'المشاريع', 'description' => 'تحديث حالة وتقدم المشروع'],
        ['name' => 'projects.quality_control', 'display_name' => 'مراقبة الجودة', 'category' => 'المشاريع', 'description' => 'مراقبة جودة تنفيذ المشروع'],
        ['name' => 'projects.inspection', 'display_name' => 'فحص المشروع', 'category' => 'المشاريع', 'description' => 'إجراء فحوصات دورية للمشروع'],

        // إدارة مالية للمشاريع
        ['name' => 'projects.budget', 'display_name' => 'إدارة ميزانية المشروع', 'category' => 'المشاريع', 'description' => 'إدارة الميزانية والتكاليف'],
        ['name' => 'projects.expenses', 'display_name' => 'إدارة مصروفات المشروع', 'category' => 'المشاريع', 'description' => 'تسجيل ومتابعة مصروفات المشروع'],
        ['name' => 'projects.approve_expenses', 'display_name' => 'اعتماد مصروفات المشروع', 'category' => 'المشاريع', 'description' => 'اعتماد المصروفات والطلبات المالية'],
        ['name' => 'projects.financial_reports', 'display_name' => 'التقارير المالية للمشروع', 'category' => 'المشاريع', 'description' => 'إنشاء تقارير مالية للمشروع'],

        // تقارير ووثائق المشاريع
        ['name' => 'projects.reports', 'display_name' => 'تقارير المشاريع', 'category' => 'المشاريع', 'description' => 'إنشاء وعرض تقارير المشاريع'],
        ['name' => 'projects.documents', 'display_name' => 'وثائق المشروع', 'category' => 'المشاريع', 'description' => 'إدارة وثائق ومستندات المشروع'],
        ['name' => 'projects.archive', 'display_name' => 'أرشفة المشاريع', 'category' => 'المشاريع', 'description' => 'أرشفة المشاريع المكتملة'],

        // إدارة متقدمة للمشاريع
        ['name' => 'projects.approve', 'display_name' => 'اعتماد المشاريع', 'category' => 'المشاريع', 'description' => 'اعتماد وتأكيد المشاريع'],
        ['name' => 'projects.close', 'display_name' => 'إغلاق المشروع', 'category' => 'المشاريع', 'description' => 'إغلاق المشاريع المكتملة'],
        ['name' => 'projects.reopen', 'display_name' => 'إعادة فتح المشروع', 'category' => 'المشاريع', 'description' => 'إعادة فتح المشاريع المغلقة'],
        ['name' => 'projects.export', 'display_name' => 'تصدير بيانات المشروع', 'category' => 'المشاريع', 'description' => 'تصدير تقارير وبيانات المشروع'],

        // التعاون والتواصل
        ['name' => 'projects.communication', 'display_name' => 'إدارة التواصل', 'category' => 'المشاريع', 'description' => 'إدارة التواصل بين فريق المشروع'],
        ['name' => 'projects.notifications', 'display_name' => 'إشعارات المشروع', 'category' => 'المشاريع', 'description' => 'إدارة إشعارات وتنبيهات المشروع'],

        // إعدادات المشاريع
        ['name' => 'projects.settings', 'display_name' => 'إعدادات المشاريع', 'category' => 'المشاريع', 'description' => 'إدارة إعدادات نظام المشاريع'],
        ['name' => 'projects.templates', 'display_name' => 'قوالب المشاريع', 'category' => 'المشاريع', 'description' => 'إدارة قوالب ونماذج المشاريع'],
    ];

    // إضافة الصلاحيات إلى قاعدة البيانات
    $addedCount = 0;
    foreach ($projectPermissions as $permission) {
        $existing = Permission::where('name', $permission['name'])->first();
        if (!$existing) {
            Permission::create($permission + ['is_active' => true]);
            $addedCount++;
            echo "✅ تم إضافة: {$permission['display_name']}\n";
        } else {
            echo "⚠️ موجودة مسبقاً: {$permission['display_name']}\n";
        }
    }

    echo "\n📊 تم إضافة {$addedCount} صلاحية جديدة للمشاريع\n\n";

    // إضافة دور مدير المشاريع إذا لم يكن موجوداً
    $projectManagerRole = Role::where('name', 'project_manager')->first();
    if (!$projectManagerRole) {
        $projectManagerRole = Role::create([
            'name' => 'project_manager',
            'display_name' => 'مدير مشاريع',
            'description' => 'مدير متخصص في إدارة وتنفيذ المشاريع',
            'is_active' => true
        ]);

        // إضافة جميع صلاحيات المشاريع لمدير المشاريع
        $projectPermissionIds = Permission::where('category', 'المشاريع')->pluck('id');
        foreach ($projectPermissionIds as $permissionId) {
            \DB::table('role_permissions')->insert([
                'role_id' => $projectManagerRole->id,
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        echo "✅ تم إنشاء دور 'مدير مشاريع' مع جميع صلاحيات المشاريع\n";
    }

    // إضافة صلاحيات محددة للأدوار الموجودة
    echo "\n🔄 تحديث الأدوار الموجودة بصلاحيات المشاريع المناسبة...\n";

    // المدير العام - جميع الصلاحيات
    $superAdmin = Role::where('name', 'super_admin')->first();
    if ($superAdmin) {
        $allProjectPermissions = Permission::where('category', 'المشاريع')->pluck('id');
        foreach ($allProjectPermissions as $permissionId) {
            $exists = \DB::table('role_permissions')
                ->where('role_id', $superAdmin->id)
                ->where('permission_id', $permissionId)
                ->exists();

            if (!$exists) {
                \DB::table('role_permissions')->insert([
                    'role_id' => $superAdmin->id,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        echo "✅ تم تحديث صلاحيات المدير العام\n";
    }

    // مدير الموقع - صلاحيات أساسية
    $siteManager = Role::where('name', 'site_manager')->first();
    if ($siteManager) {
        $basicProjectPermissions = [
            'projects.view',
            'projects.create',
            'projects.edit',
            'projects.track_progress',
            'projects.assign_team',
            'projects.assign_equipment',
            'projects.materials',
            'projects.status_update',
            'projects.reports'
        ];

        foreach ($basicProjectPermissions as $permName) {
            $permission = Permission::where('name', $permName)->first();
            if ($permission) {
                $exists = \DB::table('role_permissions')
                    ->where('role_id', $siteManager->id)
                    ->where('permission_id', $permission->id)
                    ->exists();

                if (!$exists) {
                    \DB::table('role_permissions')->insert([
                        'role_id' => $siteManager->id,
                        'permission_id' => $permission->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
        echo "✅ تم تحديث صلاحيات مدير الموقع\n";
    }

    echo "\n🎉 تم الانتهاء من إضافة وتكوين صلاحيات المشاريع بنجاح!\n";
    echo "العدد الإجمالي للصلاحيات الآن: " . Permission::count() . "\n";
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}
