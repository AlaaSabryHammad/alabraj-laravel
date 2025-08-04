<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Correspondence;
use App\Models\CorrespondenceReply;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user for correspondence submission
        $submitterUser = User::firstOrCreate([
            'email' => 'submitter@test.com'
        ], [
            'name' => 'مقدم المراسلة',
            'password' => Hash::make('password'),
        ]);

        // Create a test user for employee tasks
        $employeeUser = User::firstOrCreate([
            'email' => 'employee@test.com'
        ], [
            'name' => 'الموظف المكلف',
            'password' => Hash::make('password'),
        ]);

        // Create employee record for the employee user
        $employee = Employee::firstOrCreate([
            'user_id' => $employeeUser->id
        ], [
            'name' => 'الموظف المكلف',
            'department' => 'قسم التجارب',
            'position' => 'موظف',
            'phone' => '0501234567',
            'email' => 'employee@test.com',
        ]);

        // Create test correspondences assigned to the employee
        $correspondences = [
            [
                'subject' => 'طلب معلومات حول المشروع الجديد',
                'notes' => 'يرجى تزويدنا بالمعلومات المطلوبة حول المشروع الجديد والتفاصيل الخاصة به.',
                'type' => 'incoming',
                'priority' => 'high',
                'status' => 'pending',
            ],
            [
                'subject' => 'مراجعة التقرير الشهري',
                'notes' => 'نحتاج لمراجعة التقرير الشهري وتقديم الملاحظات اللازمة.',
                'type' => 'incoming',
                'priority' => 'medium',
                'status' => 'in_progress',
            ],
            [
                'subject' => 'استفسار حول الإجراءات الجديدة',
                'notes' => 'يرجى توضيح الإجراءات الجديدة المتبعة في القسم.',
                'type' => 'incoming',
                'priority' => 'low',
                'status' => 'pending',
            ],
        ];

        foreach ($correspondences as $corrData) {
            $correspondence = Correspondence::create([
                'subject' => $corrData['subject'],
                'notes' => $corrData['notes'],
                'type' => $corrData['type'],
                'priority' => $corrData['priority'],
                'status' => $corrData['status'],
                'created_by' => $submitterUser->id,
                'assigned_to' => $employee->id,
                'correspondence_date' => now()->toDateString(),
                'reference_number' => 'REF-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            ]);

            // Add a reply to one correspondence
            if ($correspondence->subject === 'مراجعة التقرير الشهري') {
                CorrespondenceReply::create([
                    'correspondence_id' => $correspondence->id,
                    'user_id' => $employeeUser->id,
                    'reply_content' => 'تم مراجعة التقرير وإليكم الملاحظات الأولية.',
                    'status' => 'sent',
                ]);

                // Update correspondence status
                $correspondence->update([
                    'status' => 'replied',
                    'replied_at' => now(),
                ]);
            }
        }

        $this->command->info('تم إنشاء البيانات التجريبية بنجاح!');
        $this->command->info('المستخدمين:');
        $this->command->info('- مقدم المراسلة: submitter@test.com / password');
        $this->command->info('- الموظف المكلف: employee@test.com / password');
    }
}
