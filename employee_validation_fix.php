<?php
// Fix for employee validation rules - corrected field names to match database schema

$validationRules = [
    'name' => 'required|string|max:255',
    'position' => 'required|string|max:255',
    'department' => 'required|string|max:255',
    'email' => 'required|email|unique:employees,email,' . $employee->id,
    'phone' => 'required|string|max:20',
    'hire_date' => 'required|date',
    'salary' => 'required|numeric|min:0',
    'working_hours' => 'required|numeric|min:1|max:24',
    'national_id' => 'required|string|max:20|unique:employees,national_id,' . $employee->id,
    'national_id_expiry' => 'nullable|date|after:today', // Corrected from national_id_expiry_date
    'address' => 'nullable|string',
    'status' => 'required|in:active,inactive,suspended,terminated',
    'role' => 'required|string|max:255',
    'sponsorship' => 'nullable|string|max:255',
    'category' => 'nullable|string|max:255',

    // Photo uploads
    'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    'national_id_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    'work_permit_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

    // Passport data
    'passport_number' => 'nullable|string|max:50',
    'passport_issue_date' => 'nullable|date',
    'passport_expiry_date' => 'nullable|date|after:passport_issue_date',

    // Work permit data
    'work_permit_number' => 'nullable|string|max:50',
    'work_permit_issue_date' => 'nullable|date',
    'work_permit_expiry_date' => 'nullable|date|after:work_permit_issue_date',

    // Driving license data - removed non-existent fields
    'driving_license_expiry_date' => 'nullable|date|after:today',
    'driving_license_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

    // Location assignment
    'location_id' => 'nullable|exists:locations,id',
    'location_assignment_date' => 'nullable|date',

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
    'medical_insurance_status' => 'nullable|in:مشمول,غير مشمول',
    'location_type' => 'nullable|in:داخل المملكة,خارج المملكة',

    // Rating
    'rating' => 'nullable|numeric|min:1|max:5',

    // Additional documents
    'additional_documents' => 'nullable|array',
    'additional_documents.*.name' => 'required_with:additional_documents|string|max:255',
    'additional_documents.*.file' => 'required_with:additional_documents|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
];

// Fields that don't exist in database but appear in form (need to be removed from form or added to database):
// - driving_license_issue_date (not in database schema)

// Corrected field names:
// - national_id_expiry_date -> national_id_expiry
?>
