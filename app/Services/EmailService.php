<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\NewEmployeeNotification;

class EmailService
{
    /**
     * إرسال بريد إلكتروني للمدير العام عند إضافة موظف جديد
     *
     * @param \App\Models\Employee $employee
     * @return void
     */
    public static function sendNewEmployeeNotification($employee)
    {
        // للاختبار: إرسال إلى عنوان بريد ثابت (يمكن تعديله لاحقًا)
        $testEmail = 'admin@alabraaj.com.sa';

        try {
            \Log::info("محاولة إرسال بريد إلكتروني إلى: {$testEmail}");
            Mail::to($testEmail)->send(new NewEmployeeNotification($employee));
            \Log::info("تم إرسال البريد الإلكتروني بنجاح إلى: {$testEmail}");
            return true;
        } catch (\Exception $e) {
            \Log::error("فشل إرسال البريد الإلكتروني: " . $e->getMessage());
            return false;
        }

        /* هذا الكود سيتم تفعيله لاحقًا بعد التأكد من عمل النظام
        // البحث عن المستخدمين الذين لديهم صلاحية المدير العام
        $generalManagers = User::whereHas('roles', function ($query) {
            $query->where('name', 'general-manager');
        })->get();

        if ($generalManagers->isEmpty()) {
            // إذا لم يكن هناك مديرين عامين، نجرب البحث عن المستخدمين بدور "admin"
            $generalManagers = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();
        }

        if ($generalManagers->isNotEmpty()) {
            foreach ($generalManagers as $manager) {
                if ($manager->email) {
                    try {
                        Mail::to($manager->email)->send(new NewEmployeeNotification($employee));
                    } catch (\Exception $e) {
                        \Log::error('فشل إرسال البريد الإلكتروني للمدير العام: ' . $e->getMessage());
                    }
                }
            }
        } else {
            \Log::warning('لا يوجد مدير عام لإرسال إشعار إضافة الموظف الجديد إليه');
        }
        */
    }
}
