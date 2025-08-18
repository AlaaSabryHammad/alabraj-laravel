<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class DocumentsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إضافة صلاحيات المستندات
        $documentsPermissions = [
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
        ];

        foreach ($documentsPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // إضافة صلاحيات المستندات للمدير العام
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $currentPermissions = $superAdmin->permissions ?? [];
            $newPermissions = collect($documentsPermissions)->pluck('name')->toArray();
            $allPermissions = array_unique(array_merge($currentPermissions, $newPermissions));
            $superAdmin->update(['permissions' => $allPermissions]);
        }

        // إضافة صلاحيات محددة لمدير الموقع
        $siteManager = Role::where('name', 'site_manager')->first();
        if ($siteManager) {
            $currentPermissions = $siteManager->permissions ?? [];
            $siteManagerDocPermissions = [
                'documents.view',
                'documents.create',
                'documents.edit',
                'documents.upload',
                'documents.download',
                'documents.approve',
                'documents.share'
            ];
            $allPermissions = array_unique(array_merge($currentPermissions, $siteManagerDocPermissions));
            $siteManager->update(['permissions' => $allPermissions]);
        }

        // إضافة صلاحيات محددة للمحاسب
        $accountant = Role::where('name', 'accountant')->first();
        if ($accountant) {
            $currentPermissions = $accountant->permissions ?? [];
            $accountantDocPermissions = ['documents.view', 'documents.download', 'documents.upload'];
            $allPermissions = array_unique(array_merge($currentPermissions, $accountantDocPermissions));
            $accountant->update(['permissions' => $allPermissions]);
        }

        // إضافة صلاحيات محددة لمسؤول المخازن
        $warehouseManager = Role::where('name', 'warehouse_manager')->first();
        if ($warehouseManager) {
            $currentPermissions = $warehouseManager->permissions ?? [];
            $warehouseDocPermissions = [
                'documents.view',
                'documents.create',
                'documents.upload',
                'documents.download'
            ];
            $allPermissions = array_unique(array_merge($currentPermissions, $warehouseDocPermissions));
            $warehouseManager->update(['permissions' => $allPermissions]);
        }

        // إضافة صلاحيات محددة للمراقب
        $observer = Role::where('name', 'observer')->first();
        if ($observer) {
            $currentPermissions = $observer->permissions ?? [];
            $observerDocPermissions = ['documents.view', 'documents.download'];
            $allPermissions = array_unique(array_merge($currentPermissions, $observerDocPermissions));
            $observer->update(['permissions' => $allPermissions]);
        }

        echo "تم إضافة " . count($documentsPermissions) . " صلاحية للمستندات بنجاح!\n";
        echo "تم تحديث الأدوار الموجودة مع صلاحيات المستندات المناسبة.\n";
    }
}
