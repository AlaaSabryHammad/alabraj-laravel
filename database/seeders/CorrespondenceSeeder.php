<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Correspondence;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;

class CorrespondenceSeeder extends Seeder
{
    public function run()
    {
        // Get some test data
        $user = User::first();
        $employees = Employee::take(3)->get();
        $projects = Project::take(2)->get();

        if (!$user || $employees->count() === 0) {
            echo "تحتاج إلى إنشاء مستخدمين وموظفين أولاً\n";
            return;
        }

        // Sample correspondences
        $correspondences = [
            [
                'type' => 'incoming',
                'subject' => 'طلب عرض أسعار لمشروع البرج التجاري',
                'from_entity' => 'شركة التطوير العقاري المحدودة',
                'correspondence_date' => now()->subDays(5),
                'priority' => 'high',
                'notes' => 'طلب عاجل لتقديم عرض سعر مفصل لمشروع البرج التجاري الجديد',
                'assigned_to' => $employees->first()->id,
                'project_id' => $projects->first()->id ?? null,
            ],
            [
                'type' => 'outgoing',
                'subject' => 'تسليم عرض أسعار مشروع الفيلا السكنية',
                'to_entity' => 'مكتب الهندسة المعمارية',
                'correspondence_date' => now()->subDays(3),
                'priority' => 'medium',
                'notes' => 'تم إرفاق عرض السعر المفصل مع المخططات الأولية',
                'project_id' => $projects->count() > 1 ? $projects[1]->id : null,
            ],
            [
                'type' => 'incoming',
                'subject' => 'استفسار حول التراخيص المطلوبة',
                'from_entity' => 'أمانة المنطقة الشرقية',
                'correspondence_date' => now()->subDays(2),
                'priority' => 'urgent',
                'notes' => 'استفسار عاجل حول التراخيص المطلوبة لبدء أعمال الحفر',
                'assigned_to' => $employees->count() > 1 ? $employees[1]->id : $employees->first()->id,
            ],
            [
                'type' => 'outgoing',
                'subject' => 'رد على استفسار التراخيص',
                'to_entity' => 'أمانة المنطقة الشرقية',
                'correspondence_date' => now()->subDay(),
                'priority' => 'urgent',
                'notes' => 'رد مفصل على استفسار التراخيص مع إرفاق الوثائق المطلوبة',
            ],
            [
                'type' => 'incoming',
                'subject' => 'دعوة لحضور اجتماع تنسيقي',
                'from_entity' => 'مجلس الغرف التجارية',
                'correspondence_date' => now(),
                'priority' => 'low',
                'notes' => 'دعوة لحضور اجتماع تنسيقي بين شركات المقاولات',
                'assigned_to' => $employees->count() > 2 ? $employees[2]->id : $employees->first()->id,
            ],
        ];

        foreach ($correspondences as $correspondence) {
            // Generate reference number
            $correspondence['reference_number'] = Correspondence::generateReferenceNumber($correspondence['type']);
            $correspondence['created_by'] = $user->id;

            Correspondence::create($correspondence);
        }

        echo "تم إنشاء " . count($correspondences) . " مراسلات تجريبية بنجاح!\n";
    }
}
