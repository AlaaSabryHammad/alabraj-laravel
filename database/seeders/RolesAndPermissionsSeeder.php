<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الصلاحيات الأساسية
        $permissions = [
            // إدارة الموظفين
            ['name' => 'employees.view', 'display_name' => 'عرض الموظفين', 'category' => 'الموظفين', 'description' => 'عرض قائمة الموظفين'],
            ['name' => 'employees.create', 'display_name' => 'إنشاء موظف', 'category' => 'الموظفين', 'description' => 'إنشاء موظف جديد'],
            ['name' => 'employees.edit', 'display_name' => 'تعديل موظف', 'category' => 'الموظفين', 'description' => 'تعديل بيانات الموظفين'],
            ['name' => 'employees.delete', 'display_name' => 'حذف موظف', 'category' => 'الموظفين', 'description' => 'حذف الموظفين'],
            ['name' => 'employees.deactivate', 'display_name' => 'إلغاء تفعيل موظف', 'category' => 'الموظفين', 'description' => 'إلغاء تفعيل الموظفين'],

            // إدارة المعدات
            ['name' => 'equipment.view', 'display_name' => 'عرض المعدات', 'category' => 'المعدات', 'description' => 'عرض قائمة المعدات'],
            ['name' => 'equipment.create', 'display_name' => 'إنشاء معدة', 'category' => 'المعدات', 'description' => 'إنشاء معدة جديدة'],
            ['name' => 'equipment.edit', 'display_name' => 'تعديل معدة', 'category' => 'المعدات', 'description' => 'تعديل بيانات المعدات'],
            ['name' => 'equipment.delete', 'display_name' => 'حذف معدة', 'category' => 'المعدات', 'description' => 'حذف المعدات'],
            ['name' => 'equipment.maintenance', 'display_name' => 'صيانة المعدات', 'category' => 'المعدات', 'description' => 'إدارة صيانة المعدات'],

            // استهلاك المحروقات
            ['name' => 'fuel.view', 'display_name' => 'عرض المحروقات', 'category' => 'المحروقات', 'description' => 'عرض استهلاك المحروقات'],
            ['name' => 'fuel.add', 'display_name' => 'إضافة استهلاك', 'category' => 'المحروقات', 'description' => 'إضافة استهلاك محروقات'],
            ['name' => 'fuel.approve', 'display_name' => 'اعتماد الاستهلاك', 'category' => 'المحروقات', 'description' => 'اعتماد استهلاك المحروقات'],
            ['name' => 'fuel.manage', 'display_name' => 'إدارة المحروقات', 'category' => 'المحروقات', 'description' => 'إدارة نظام المحروقات'],
            ['name' => 'fuel.distribution', 'display_name' => 'توزيع المحروقات', 'category' => 'المحروقات', 'description' => 'إدارة توزيع المحروقات'],

            // إدارة المواقع
            ['name' => 'locations.view', 'display_name' => 'عرض المواقع', 'category' => 'المواقع', 'description' => 'عرض المواقع'],
            ['name' => 'locations.create', 'display_name' => 'إنشاء موقع', 'category' => 'المواقع', 'description' => 'إنشاء موقع جديد'],
            ['name' => 'locations.edit', 'display_name' => 'تعديل موقع', 'category' => 'المواقع', 'description' => 'تعديل المواقع'],
            ['name' => 'locations.delete', 'display_name' => 'حذف موقع', 'category' => 'المواقع', 'description' => 'حذف المواقع'],

            // إدارة المواد والمخازن
            ['name' => 'materials.view', 'display_name' => 'عرض المواد', 'category' => 'المواد والمخازن', 'description' => 'عرض المواد'],
            ['name' => 'materials.create', 'display_name' => 'إنشاء مادة', 'category' => 'المواد والمخازن', 'description' => 'إنشاء مادة جديدة'],
            ['name' => 'materials.edit', 'display_name' => 'تعديل مادة', 'category' => 'المواد والمخازن', 'description' => 'تعديل المواد'],
            ['name' => 'materials.delete', 'display_name' => 'حذف مادة', 'category' => 'المواد والمخازن', 'description' => 'حذف المواد'],
            ['name' => 'warehouses.manage', 'display_name' => 'إدارة المخازن', 'category' => 'المواد والمخازن', 'description' => 'إدارة المخازن'],

            // التقارير
            ['name' => 'reports.view', 'display_name' => 'عرض التقارير', 'category' => 'التقارير', 'description' => 'عرض التقارير'],
            ['name' => 'reports.export', 'display_name' => 'تصدير التقارير', 'category' => 'التقارير', 'description' => 'تصدير التقارير'],
            ['name' => 'reports.advanced', 'display_name' => 'التقارير المتقدمة', 'category' => 'التقارير', 'description' => 'التقارير المتقدمة'],

            // إدارة المستندات
            ['name' => 'documents.view', 'display_name' => 'عرض المستندات', 'category' => 'المستندات', 'description' => 'عرض جميع المستندات في النظام'],
            ['name' => 'documents.create', 'display_name' => 'إنشاء مستند', 'category' => 'المستندات', 'description' => 'إنشاء مستندات جديدة'],
            ['name' => 'documents.edit', 'display_name' => 'تعديل مستند', 'category' => 'المستندات', 'description' => 'تعديل المستندات الموجودة'],
            ['name' => 'documents.delete', 'display_name' => 'حذف مستند', 'category' => 'المستندات', 'description' => 'حذف المستندات'],
            ['name' => 'documents.download', 'display_name' => 'تحميل مستند', 'category' => 'المستندات', 'description' => 'تحميل المستندات'],
            ['name' => 'documents.upload', 'display_name' => 'رفع مستند', 'category' => 'المستندات', 'description' => 'رفع مستندات جديدة'],
            ['name' => 'documents.approve', 'display_name' => 'اعتماد مستند', 'category' => 'المستندات', 'description' => 'اعتماد المستندات المرفوعة'],
            ['name' => 'documents.archive', 'display_name' => 'أرشفة مستند', 'category' => 'المستندات', 'description' => 'أرشفة المستندات القديمة'],
            ['name' => 'documents.share', 'display_name' => 'مشاركة مستند', 'category' => 'المستندات', 'description' => 'مشاركة المستندات مع المستخدمين'],
            ['name' => 'documents.manage_categories', 'display_name' => 'إدارة فئات المستندات', 'category' => 'المستندات', 'description' => 'إدارة تصنيفات المستندات'],
            ['name' => 'documents.version_control', 'display_name' => 'إدارة إصدارات المستند', 'category' => 'المستندات', 'description' => 'إدارة إصدارات المستندات المختلفة'],
            ['name' => 'documents.audit', 'display_name' => 'مراجعة سجل المستندات', 'category' => 'المستندات', 'description' => 'مراجعة سجل العمليات على المستندات'],

            // إدارة المشاريع
            ['name' => 'projects.view', 'display_name' => 'عرض المشاريع', 'category' => 'المشاريع', 'description' => 'عرض قائمة المشاريع ومعلوماتها'],
            ['name' => 'projects.create', 'display_name' => 'إنشاء مشروع', 'category' => 'المشاريع', 'description' => 'إنشاء مشروع جديد'],
            ['name' => 'projects.edit', 'display_name' => 'تعديل مشروع', 'category' => 'المشاريع', 'description' => 'تعديل معلومات المشاريع الموجودة'],
            ['name' => 'projects.delete', 'display_name' => 'حذف مشروع', 'category' => 'المشاريع', 'description' => 'حذف المشاريع'],
            ['name' => 'projects.manage', 'display_name' => 'إدارة المشاريع', 'category' => 'المشاريع', 'description' => 'إدارة شاملة للمشاريع'],
            ['name' => 'projects.planning', 'display_name' => 'تخطيط المشاريع', 'category' => 'المشاريع', 'description' => 'وضع خطط وجداول زمنية للمشاريع'],
            ['name' => 'projects.timeline', 'display_name' => 'إدارة الجدول الزمني', 'category' => 'المشاريع', 'description' => 'إدارة الجداول الزمنية والمواعيد'],
            ['name' => 'projects.milestones', 'display_name' => 'إدارة المعالم', 'category' => 'المشاريع', 'description' => 'تحديد وإدارة معالم المشروع'],
            ['name' => 'projects.tasks', 'display_name' => 'إدارة المهام', 'category' => 'المشاريع', 'description' => 'إدارة مهام المشروع وتوزيعها'],
            ['name' => 'projects.resources', 'display_name' => 'إدارة موارد المشروع', 'category' => 'المشاريع', 'description' => 'إدارة الموارد المخصصة للمشروع'],
            ['name' => 'projects.assign_team', 'display_name' => 'تعيين فريق العمل', 'category' => 'المشاريع', 'description' => 'تعيين وإدارة فريق العمل للمشروع'],
            ['name' => 'projects.assign_equipment', 'display_name' => 'تخصيص المعدات', 'category' => 'المشاريع', 'description' => 'تخصيص المعدات للمشاريع'],
            ['name' => 'projects.materials', 'display_name' => 'إدارة مواد المشروع', 'category' => 'المشاريع', 'description' => 'إدارة المواد المطلوبة للمشروع'],
            ['name' => 'projects.track_progress', 'display_name' => 'متابعة تقدم المشروع', 'category' => 'المشاريع', 'description' => 'متابعة ومراقبة تقدم المشروع'],
            ['name' => 'projects.status_update', 'display_name' => 'تحديث حالة المشروع', 'category' => 'المشاريع', 'description' => 'تحديث حالة وتقدم المشروع'],
            ['name' => 'projects.quality_control', 'display_name' => 'مراقبة الجودة', 'category' => 'المشاريع', 'description' => 'مراقبة جودة تنفيذ المشروع'],
            ['name' => 'projects.inspection', 'display_name' => 'فحص المشروع', 'category' => 'المشاريع', 'description' => 'إجراء فحوصات دورية للمشروع'],
            ['name' => 'projects.budget', 'display_name' => 'إدارة ميزانية المشروع', 'category' => 'المشاريع', 'description' => 'إدارة الميزانية والتكاليف'],
            ['name' => 'projects.expenses', 'display_name' => 'إدارة مصروفات المشروع', 'category' => 'المشاريع', 'description' => 'تسجيل ومتابعة مصروفات المشروع'],
            ['name' => 'projects.approve_expenses', 'display_name' => 'اعتماد مصروفات المشروع', 'category' => 'المشاريع', 'description' => 'اعتماد المصروفات والطلبات المالية'],
            ['name' => 'projects.financial_reports', 'display_name' => 'التقارير المالية للمشروع', 'category' => 'المشاريع', 'description' => 'إنشاء تقارير مالية للمشروع'],
            ['name' => 'projects.reports', 'display_name' => 'تقارير المشاريع', 'category' => 'المشاريع', 'description' => 'إنشاء وعرض تقارير المشاريع'],
            ['name' => 'projects.documents', 'display_name' => 'وثائق المشروع', 'category' => 'المشاريع', 'description' => 'إدارة وثائق ومستندات المشروع'],
            ['name' => 'projects.archive', 'display_name' => 'أرشفة المشاريع', 'category' => 'المشاريع', 'description' => 'أرشفة المشاريع المكتملة'],
            ['name' => 'projects.approve', 'display_name' => 'اعتماد المشاريع', 'category' => 'المشاريع', 'description' => 'اعتماد وتأكيد المشاريع'],
            ['name' => 'projects.close', 'display_name' => 'إغلاق المشروع', 'category' => 'المشاريع', 'description' => 'إغلاق المشاريع المكتملة'],
            ['name' => 'projects.reopen', 'display_name' => 'إعادة فتح المشروع', 'category' => 'المشاريع', 'description' => 'إعادة فتح المشاريع المغلقة'],
            ['name' => 'projects.export', 'display_name' => 'تصدير بيانات المشروع', 'category' => 'المشاريع', 'description' => 'تصدير تقارير وبيانات المشروع'],
            ['name' => 'projects.communication', 'display_name' => 'إدارة التواصل', 'category' => 'المشاريع', 'description' => 'إدارة التواصل بين فريق المشروع'],
            ['name' => 'projects.notifications', 'display_name' => 'إشعارات المشروع', 'category' => 'المشاريع', 'description' => 'إدارة إشعارات وتنبيهات المشروع'],
            ['name' => 'projects.settings', 'display_name' => 'إعدادات المشاريع', 'category' => 'المشاريع', 'description' => 'إدارة إعدادات نظام المشاريع'],
            ['name' => 'projects.templates', 'display_name' => 'قوالب المشاريع', 'category' => 'المشاريع', 'description' => 'إدارة قوالب ونماذج المشاريع'],

            // الإعدادات
            ['name' => 'settings.view', 'display_name' => 'عرض الإعدادات', 'category' => 'الإعدادات', 'description' => 'عرض الإعدادات'],
            ['name' => 'settings.edit', 'display_name' => 'تعديل الإعدادات', 'category' => 'الإعدادات', 'description' => 'تعديل الإعدادات'],
            ['name' => 'settings.roles', 'display_name' => 'إدارة الأدوار', 'category' => 'الإعدادات', 'description' => 'إدارة الأدوار والصلاحيات'],

            // إدارة النظام
            ['name' => 'system.admin', 'display_name' => 'مدير النظام', 'category' => 'إدارة النظام', 'description' => 'صلاحيات المدير العام'],
            ['name' => 'system.backup', 'display_name' => 'النسخ الاحتياطي', 'category' => 'إدارة النظام', 'description' => 'نسخ احتياطي للنظام'],
            ['name' => 'system.logs', 'display_name' => 'سجلات النظام', 'category' => 'إدارة النظام', 'description' => 'عرض سجلات النظام'],
        ];

        // إنشاء الصلاحيات
        $permissionObjects = [];
        foreach ($permissions as $permission) {
            $permissionObj = Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
            $permissionObjects[] = $permissionObj;
        }

        // إنشاء الأدوار والربط مع الصلاحيات - محدثة حسب المطلوب
        $rolesData = [
            [
                'name' => 'general_manager',
                'display_name' => 'مدير عام',
                'category' => 'executive',
                'description' => 'صلاحيات كاملة على النظام',
                'permissions' => Permission::pluck('name')->toArray()
            ],
            [
                'name' => 'project_manager',
                'display_name' => 'مدير مشاريع',
                'category' => 'management',
                'description' => 'مدير متخصص في إدارة وتنفيذ المشاريع',
                'permissions' => [
                    'projects.view',
                    'projects.create',
                    'projects.edit',
                    'projects.delete',
                    'projects.manage',
                    'projects.planning',
                    'projects.timeline',
                    'projects.milestones',
                    'projects.tasks',
                    'projects.resources',
                    'projects.assign_team',
                    'projects.assign_equipment',
                    'projects.materials',
                    'projects.track_progress',
                    'projects.status_update',
                    'projects.quality_control',
                    'projects.inspection',
                    'projects.budget',
                    'projects.expenses',
                    'projects.approve_expenses',
                    'projects.financial_reports',
                    'projects.reports',
                    'projects.documents',
                    'projects.archive',
                    'projects.approve',
                    'projects.close',
                    'projects.reopen',
                    'projects.export',
                    'projects.communication',
                    'projects.notifications',
                    'projects.settings',
                    'projects.templates',
                    'employees.view',
                    'equipment.view',
                    'materials.view',
                    'documents.view',
                    'documents.create',
                    'documents.upload',
                    'documents.download',
                    'reports.view',
                    'reports.export'
                ]
            ],
            [
                'name' => 'engineer',
                'display_name' => 'مهندس',
                'category' => 'technical',
                'description' => 'تنفيذ المهام الهندسية والفنية',
                'permissions' => [
                    'projects.view',
                    'projects.create',
                    'projects.edit',
                    'projects.track_progress',
                    'equipment.view',
                    'materials.view',
                    'documents.view',
                    'documents.create',
                    'documents.upload',
                    'documents.download',
                    'reports.view'
                ]
            ],
            [
                'name' => 'financial_manager',
                'display_name' => 'مدير مالي',
                'category' => 'management',
                'description' => 'إدارة الشؤون المالية والمحاسبية',
                'permissions' => [
                    'fuel.view',
                    'fuel.approve',
                    'materials.view',
                    'documents.view',
                    'documents.download',
                    'documents.upload',
                    'projects.view',
                    'projects.budget',
                    'projects.expenses',
                    'projects.financial_reports',
                    'projects.reports',
                    'reports.view',
                    'reports.export',
                    'reports.advanced'
                ]
            ],
            [
                'name' => 'accountant',
                'display_name' => 'محاسب',
                'category' => 'administrative',
                'description' => 'المعاملات المالية والتقارير',
                'permissions' => [
                    'fuel.view',
                    'fuel.approve',
                    'materials.view',
                    'documents.view',
                    'documents.download',
                    'documents.upload',
                    'projects.view',
                    'projects.budget',
                    'projects.expenses',
                    'projects.financial_reports',
                    'projects.reports',
                    'reports.view',
                    'reports.export',
                    'reports.advanced'
                ]
            ],
            [
                'name' => 'manager',
                'display_name' => 'مدير',
                'category' => 'management',
                'description' => 'إدارة العمليات التشغيلية',
                'permissions' => [
                    'employees.view',
                    'equipment.view',
                    'equipment.edit',
                    'equipment.maintenance',
                    'fuel.view',
                    'fuel.add',
                    'fuel.approve',
                    'materials.view',
                    'materials.edit',
                    'documents.view',
                    'documents.create',
                    'documents.edit',
                    'documents.upload',
                    'documents.download',
                    'projects.view',
                    'projects.track_progress',
                    'projects.status_update',
                    'reports.view'
                ]
            ],
            [
                'name' => 'driver',
                'display_name' => 'سائق',
                'category' => 'operational',
                'description' => 'نقل المواد والمعدات',
                'permissions' => [
                    'fuel.view',
                    'fuel.add',
                    'materials.view',
                    'documents.view'
                ]
            ],
            [
                'name' => 'security',
                'display_name' => 'أمن',
                'category' => 'operational',
                'description' => 'أمن الموقع والمراقبة',
                'permissions' => [
                    'employees.view',
                    'equipment.view',
                    'materials.view',
                    'documents.view'
                ]
            ],
            [
                'name' => 'worker',
                'display_name' => 'عامل',
                'category' => 'operational',
                'description' => 'تنفيذ المهام العملية والإنتاجية',
                'permissions' => [
                    'equipment.view',
                    'fuel.view',
                    'fuel.add',
                    'materials.view'
                ]
            ],
            [
                'name' => 'warehouse_manager',
                'display_name' => 'أمين مستودع',
                'category' => 'operational',
                'description' => 'إدارة المواد والمخازن',
                'permissions' => [
                    'materials.view',
                    'materials.create',
                    'materials.edit',
                    'warehouses.manage',
                    'documents.view',
                    'documents.create',
                    'documents.upload',
                    'documents.download',
                    'reports.view'
                ]
            ],
            [
                'name' => 'workship_manager',
                'display_name' => 'أمين ورشة',
                'category' => 'technical',
                'description' => 'إدارة الورش والصيانة',
                'permissions' => [
                    'equipment.view',
                    'equipment.create',
                    'equipment.edit',
                    'equipment.maintenance',
                    'materials.view',
                    'materials.edit',
                    'documents.view',
                    'documents.create',
                    'documents.upload',
                    'reports.view'
                ]
            ],
            [
                'name' => 'site_manager',
                'display_name' => 'مشرف موقع',
                'category' => 'management',
                'description' => 'إشراف وإدارة موقع العمل',
                'permissions' => [
                    'employees.view',
                    'employees.edit',
                    'equipment.view',
                    'equipment.edit',
                    'equipment.maintenance',
                    'fuel.view',
                    'fuel.add',
                    'fuel.approve',
                    'materials.view',
                    'materials.edit',
                    'documents.view',
                    'documents.create',
                    'documents.edit',
                    'documents.upload',
                    'documents.download',
                    'projects.view',
                    'projects.track_progress',
                    'projects.status_update',
                    'reports.view'
                ]
            ],
            [
                'name' => 'fuel_manager',
                'display_name' => 'سائق تانك محروقات',
                'category' => 'operational',
                'description' => 'إدارة وتوزيع المحروقات',
                'permissions' => [
                    'fuel.view',
                    'fuel.add',
                    'fuel.approve',
                    'fuel.manage',
                    'fuel.distribution',
                    'equipment.view',
                    'documents.view',
                    'reports.view'
                ]
            ],
            [
                'name' => 'truck_driver',
                'display_name' => 'سائق شاحنة',
                'category' => 'operational',
                'description' => 'نقل المواد والمعدات الثقيلة',
                'permissions' => [
                    'fuel.view',
                    'fuel.add',
                    'materials.view',
                    'equipment.view',
                    'documents.view'
                ]
            ]
        ];

        // إنشاء الأدوار وربطها بالصلاحيات
        foreach ($rolesData as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );

            // حذف الصلاحيات السابقة للدور
            \DB::table('role_permissions')->where('role_id', $role->id)->delete();

            // ربط الصلاحيات بالدور
            foreach ($permissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    \DB::table('role_permissions')->insertOrIgnore([
                        'role_id' => $role->id,
                        'permission_id' => $permission->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
    }
}
