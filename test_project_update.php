<?php
// اختبار تحديث المشروع من خلال محاكاة طلب HTTP

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\ProjectController;
use App\Models\Project;

// تحديد بيانات التحديث
$projectId = 11;
$updateData = [
    'name' => 'مشروع محدث من السكريپت - ' . date('H:i:s'),
    'description' => 'وصف محدث',
    'start_date' => '2025-01-01',
    'end_date' => '2025-12-31',
    'budget' => 150000,
    'location' => 'جدة',
    'project_manager_id' => 3,
    'status' => 'active',
    'progress' => 75
];

echo "=== اختبار تحديث المشروع رقم {$projectId} ===" . PHP_EOL;

// جلب المشروع قبل التحديث
$project = Project::find($projectId);
if (!$project) {
    echo "❌ المشروع رقم {$projectId} غير موجود" . PHP_EOL;
    exit;
}

echo "📋 البيانات قبل التحديث:" . PHP_EOL;
echo "   الاسم: {$project->name}" . PHP_EOL;
echo "   مدير المشروع: " . ($project->projectManager->name ?? 'غير محدد') . PHP_EOL;
echo "   الحالة: {$project->status}" . PHP_EOL;
echo "   نسبة الإنجاز: {$project->progress}%" . PHP_EOL;
echo PHP_EOL;

// إجراء التحديث مباشرة
try {
    $project->update($updateData);
    echo "✅ تم التحديث بنجاح!" . PHP_EOL;

    // جلب البيانات المحدثة
    $updatedProject = $project->fresh();
    echo "📋 البيانات بعد التحديث:" . PHP_EOL;
    echo "   الاسم: {$updatedProject->name}" . PHP_EOL;
    echo "   مدير المشروع: " . ($updatedProject->projectManager->name ?? 'غير محدد') . PHP_EOL;
    echo "   الحالة: {$updatedProject->status}" . PHP_EOL;
    echo "   نسبة الإنجاز: {$updatedProject->progress}%" . PHP_EOL;
    echo "   آخر تحديث: " . $updatedProject->updated_at->format('Y-m-d H:i:s') . PHP_EOL;

} catch (Exception $e) {
    echo "❌ خطأ في التحديث: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "🔗 رابط التحقق من النتيجة: http://127.0.0.1:8000/projects/{$projectId}" . PHP_EOL;
?>
