<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class ProjectsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إضافة صلاحيات المشاريع
        $projectsPermissions = [
            // إدارة المشاريع الأساسية
            ['name' => 'projects.view', 'display_name' => 'عرض المشاريع', 'category' => 'المشاريع', 'description' => 'عرض جميع المشاريع في النظام'],
            ['name' => 'projects.create', 'display_name' => 'إنشاء مشروع', 'category' => 'المشاريع', 'description' => 'إنشاء مشاريع جديدة'],
            ['name' => 'projects.edit', 'display_name' => 'تعديل مشروع', 'category' => 'المشاريع', 'description' => 'تعديل بيانات المشاريع الموجودة'],
            ['name' => 'projects.delete', 'display_name' => 'حذف مشروع', 'category' => 'المشاريع', 'description' => 'حذف المشاريع'],

            // إدارة حالة المشروع
            ['name' => 'projects.status', 'display_name' => 'تغيير حالة المشروع', 'category' => 'المشاريع', 'description' => 'تغيير حالة المشروع (قيد التنفيذ، مكتمل، متوقف)'],
            ['name' => 'projects.approve', 'display_name' => 'اعتماد مشروع', 'category' => 'المشاريع', 'description' => 'اعتماد المشاريع الجديدة أو التعديلات'],
            ['name' => 'projects.archive', 'display_name' => 'أرشفة مشروع', 'category' => 'المشاريع', 'description' => 'أرشفة المشاريع المكتملة أو الملغية'],

            // إدارة فريق المشروع
            ['name' => 'projects.manage_team', 'display_name' => 'إدارة فريق المشروع', 'category' => 'المشاريع', 'description' => 'إضافة وإزالة أعضاء فريق المشروع'],
            ['name' => 'projects.assign_roles', 'display_name' => 'تعيين أدوار المشروع', 'category' => 'المشاريع', 'description' => 'تعيين أدوار ومسؤوليات أعضاء الفريق'],

            // إدارة مهام المشروع
            ['name' => 'projects.manage_tasks', 'display_name' => 'إدارة مهام المشروع', 'category' => 'المشاريع', 'description' => 'إنشاء وتعديل وحذف مهام المشروع'],
            ['name' => 'projects.assign_tasks', 'display_name' => 'تعيين المهام', 'category' => 'المشاريع', 'description' => 'تعيين المهام للموظفين'],
            ['name' => 'projects.track_progress', 'display_name' => 'تتبع تقدم المشروع', 'category' => 'المشاريع', 'description' => 'متابعة وتتبع تقدم المشروع والمهام'],

            // إدارة ميزانية المشروع
            ['name' => 'projects.manage_budget', 'display_name' => 'إدارة ميزانية المشروع', 'category' => 'المشاريع', 'description' => 'إدارة الميزانية والتكاليف'],
            ['name' => 'projects.view_budget', 'display_name' => 'عرض ميزانية المشروع', 'category' => 'المشاريع', 'description' => 'عرض تفاصيل الميزانية والإنفاق'],
            ['name' => 'projects.approve_expenses', 'display_name' => 'اعتماد مصروفات المشروع', 'category' => 'المشاريع', 'description' => 'اعتماد المصروفات والفواتير'],

            // إدارة موارد المشروع
            ['name' => 'projects.manage_resources', 'display_name' => 'إدارة موارد المشروع', 'category' => 'المشاريع', 'description' => 'إدارة المعدات والمواد المخصصة للمشروع'],
            ['name' => 'projects.allocate_equipment', 'display_name' => 'تخصيص المعدات', 'category' => 'المشاريع', 'description' => 'تخصيص المعدات للمشاريع'],
            ['name' => 'projects.allocate_materials', 'display_name' => 'تخصيص المواد', 'category' => 'المشاريع', 'description' => 'تخصيص المواد والمستلزمات للمشاريع'],

            // التقارير والتحليلات
            ['name' => 'projects.reports', 'display_name' => 'تقارير المشاريع', 'category' => 'المشاريع', 'description' => 'إنشاء وعرض تقارير المشاريع'],
            ['name' => 'projects.analytics', 'display_name' => 'تحليلات المشاريع', 'category' => 'المشاريع', 'description' => 'عرض تحليلات أداء المشاريع'],
            ['name' => 'projects.timeline', 'display_name' => 'الجدول الزمني للمشروع', 'category' => 'المشاريع', 'description' => 'إدارة ومراجعة الجدول الزمني'],

            // التواصل والتعاون
            ['name' => 'projects.communicate', 'display_name' => 'التواصل في المشروع', 'category' => 'المشاريع', 'description' => 'التواصل مع فريق المشروع وإرسال التحديثات'],
            ['name' => 'projects.meetings', 'display_name' => 'إدارة اجتماعات المشروع', 'category' => 'المشاريع', 'description' => 'جدولة وإدارة اجتماعات المشروع'],

            // إدارة المخاطر والجودة
            ['name' => 'projects.risk_management', 'display_name' => 'إدارة مخاطر المشروع', 'category' => 'المشاريع', 'description' => 'تحديد وإدارة مخاطر المشروع'],
            ['name' => 'projects.quality_control', 'display_name' => 'مراقبة جودة المشروع', 'category' => 'المشاريع', 'description' => 'مراقبة وضمان جودة تنفيذ المشروع'],
        ];

        foreach ($projectsPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // إضافة صلاحيات المشاريع للمدير العام
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $currentPermissions = $superAdmin->permissions ?? [];
            $newPermissions = collect($projectsPermissions)->pluck('name')->toArray();
            $allPermissions = array_unique(array_merge($currentPermissions, $newPermissions));
            $superAdmin->update(['permissions' => $allPermissions]);
        }

        // إضافة صلاحيات شاملة لمدير الموقع
        $siteManager = Role::where('name', 'site_manager')->first();
        if ($siteManager) {
            $currentPermissions = $siteManager->permissions ?? [];
            $siteManagerProjectPermissions = [
                'projects.view',
                'projects.create',
                'projects.edit',
                'projects.status',
                'projects.manage_team',
                'projects.assign_roles',
                'projects.manage_tasks',
                'projects.assign_tasks',
                'projects.track_progress',
                'projects.view_budget',
                'projects.manage_resources',
                'projects.allocate_equipment',
                'projects.allocate_materials',
                'projects.reports',
                'projects.timeline',
                'projects.communicate',
                'projects.meetings',
                'projects.risk_management',
                'projects.quality_control'
            ];
            $allPermissions = array_unique(array_merge($currentPermissions, $siteManagerProjectPermissions));
            $siteManager->update(['permissions' => $allPermissions]);
        }

        // إنشاء دور جديد: مدير مشاريع
        $projectManager = Role::firstOrCreate([
            'name' => 'project_manager',
            'display_name' => 'مدير مشاريع',
            'category' => 'الإدارة',
            'description' => 'مسؤول عن إدارة المشاريع من البداية للنهاية',
            'is_active' => true
        ]);

        $projectManagerPermissions = [
            // صلاحيات المشاريع
            'projects.view',
            'projects.create',
            'projects.edit',
            'projects.status',
            'projects.approve',
            'projects.manage_team',
            'projects.assign_roles',
            'projects.manage_tasks',
            'projects.assign_tasks',
            'projects.track_progress',
            'projects.manage_budget',
            'projects.view_budget',
            'projects.approve_expenses',
            'projects.manage_resources',
            'projects.allocate_equipment',
            'projects.allocate_materials',
            'projects.reports',
            'projects.analytics',
            'projects.timeline',
            'projects.communicate',
            'projects.meetings',
            'projects.risk_management',
            'projects.quality_control',

            // صلاحيات أخرى مفيدة
            'employees.view',
            'equipment.view',
            'materials.view',
            'documents.view',
            'documents.create',
            'documents.upload',
            'documents.download',
            'reports.view',
            'reports.export'
        ];

        $projectManager->update(['permissions' => $projectManagerPermissions]);

        // إضافة صلاحيات محددة للمحاسب
        $accountant = Role::where('name', 'accountant')->first();
        if ($accountant) {
            $currentPermissions = $accountant->permissions ?? [];
            $accountantProjectPermissions = [
                'projects.view',
                'projects.view_budget',
                'projects.approve_expenses',
                'projects.reports'
            ];
            $allPermissions = array_unique(array_merge($currentPermissions, $accountantProjectPermissions));
            $accountant->update(['permissions' => $allPermissions]);
        }

        // إضافة صلاحيات محددة للمراقب
        $observer = Role::where('name', 'observer')->first();
        if ($observer) {
            $currentPermissions = $observer->permissions ?? [];
            $observerProjectPermissions = [
                'projects.view',
                'projects.track_progress',
                'projects.reports',
                'projects.timeline'
            ];
            $allPermissions = array_unique(array_merge($currentPermissions, $observerProjectPermissions));
            $observer->update(['permissions' => $allPermissions]);
        }

        echo "تم إضافة " . count($projectsPermissions) . " صلاحية للمشاريع بنجاح!\n";
        echo "تم إنشاء دور 'مدير مشاريع' جديد.\n";
        echo "تم تحديث الأدوار الموجودة مع صلاحيات المشاريع المناسبة.\n";
    }
}
