<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $validRoles = Role::where('is_active', true)->pluck('name')->toArray();

        return [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|string|max:20',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'working_hours' => 'required|numeric|min:1|max:24',
            'national_id' => [
                'required',
                'string',
                'max:20',
                'unique:employees',
                function ($attribute, $value, $fail) {
                    $userEmail = $value . '@alabraaj.com.sa';
                    if (User::where('email', $userEmail)->exists()) {
                        $fail('يوجد مستخدم مسجل بالفعل بهذا الرقم الوطني.');
                    }
                },
            ],
            'national_id_expiry' => 'nullable|date|after:today',
            'address' => 'nullable|string',
            'role' => 'required|in:' . implode(',', $validRoles),
            'sponsorship_status' => 'required|in:شركة الأبراج للمقاولات المحدودة,فرع1 شركة الأبراج للمقاولات المحدودة,فرع2 شركة الأبراج للمقاولات المحدودة,مؤسسة فريق التعمير للمقاولات,فرع مؤسسة فريق التعمير للنقل,مؤسسة الزفاف الذهبي,مؤسسة عنوان الكادي,عمالة منزلية,عمالة كفالة خارجية تحت التجربة,أخرى',
            'category' => 'required|in:A+,A,B,C,D,E',

            // Photo uploads
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'national_id_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'work_permit_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',

            // Passport data
            'passport_number' => 'nullable|string|max:50',
            'passport_issue_date' => 'nullable|date',
            'passport_expiry_date' => 'nullable|date|after:passport_issue_date',

            // Work permit data
            'work_permit_number' => 'nullable|string|max:50',
            'work_permit_issue_date' => 'nullable|date',
            'work_permit_expiry_date' => 'nullable|date|after:work_permit_issue_date',

            // Driving license data
            'driving_license_issue_date' => 'nullable|date',
            'driving_license_expiry' => 'nullable|date|after:driving_license_issue_date',
            'driving_license_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',

            // Location assignment
            'location_id' => 'nullable|exists:locations,id',

            // Bank account information
            'bank_name' => 'nullable|string|max:255',
            'iban' => [
                'nullable',
                'string',
                'regex:/^[0-9]{22}$/',
            ],

            // Personal information
            'birth_date' => 'nullable|date|before:today',
            'nationality' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',

            // Rating
            'rating' => 'nullable|numeric|min:1|max:5',

            // Additional documents
            'additional_documents' => 'nullable|array',
            'additional_documents.*.name' => 'required_with:additional_documents|string|max:255',
            'additional_documents.*.file' => 'required_with:additional_documents|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'role.required' => 'الدور الوظيفي مطلوب.',
            'role.in' => 'الدور الوظيفي المختار غير صحيح. يرجى اختيار دور صحيح من القائمة.',
        ];
    }
}
