<!-- Name -->
<div class="mb-6">
    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم المورد *</label>
    <input type="text" id="name" name="name" value="{{ old('name', $supplier->name ?? '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="اسم المورد">
    @error('name')
        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>

<!-- Company Name -->
<div class="mb-6">
    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الشركة</label>
    <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $supplier->company_name ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="اسم الشركة">
    @error('company_name')
        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>

<!-- Contact Information -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
        <input type="email" id="email" name="email" value="{{ old('email', $supplier->email ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="البريد الإلكتروني">
        @error('email')
            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Phone -->
    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="رقم الهاتف">
        @error('phone')
            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Phone 2 and Contact Person -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Phone 2 -->
    <div>
        <label for="phone_2" class="block text-sm font-medium text-gray-700 mb-2">رقم هاتف بديل</label>
        <input type="text" id="phone_2" name="phone_2" value="{{ old('phone_2', $supplier->phone_2 ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="رقم هاتف بديل">
        @error('phone_2')
            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Contact Person -->
    <div>
        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">شخص المراجعة</label>
        <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person', $supplier->contact_person ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="شخص المراجعة">
        @error('contact_person')
            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Contact Person Phone -->
<div class="mb-6">
    <label for="contact_person_phone" class="block text-sm font-medium text-gray-700 mb-2">رقم هاتف شخص المراجعة</label>
    <input type="text" id="contact_person_phone" name="contact_person_phone" value="{{ old('contact_person_phone', $supplier->contact_person_phone ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="رقم هاتف شخص المراجعة">
    @error('contact_person_phone')
        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>

<!-- Address Information -->
<div class="mb-6">
    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
    <textarea id="address" name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="العنوان">{{ old('address', $supplier->address ?? '') }}</textarea>
    @error('address')
        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>

<!-- City and Country -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- City -->
    <div>
        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">المدينة</label>
        <input type="text" id="city" name="city" value="{{ old('city', $supplier->city ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="المدينة">
        @error('city')
            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Country -->
    <div>
        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">الدولة</label>
        <input type="text" id="country" name="country" value="{{ old('country', $supplier->country ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="الدولة">
        @error('country')
            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Tax and CR Numbers -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Tax Number -->
    <div>
        <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الضريبة</label>
        <input type="text" id="tax_number" name="tax_number" value="{{ old('tax_number', $supplier->tax_number ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="رقم الضريبة">
        @error('tax_number')
            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- CR Number -->
    <div>
        <label for="cr_number" class="block text-sm font-medium text-gray-700 mb-2">رقم السجل التجاري</label>
        <input type="text" id="cr_number" name="cr_number" value="{{ old('cr_number', $supplier->cr_number ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="رقم السجل التجاري">
        @error('cr_number')
            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Credit Limit and Payment Terms -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Credit Limit -->
    <div>
        <label for="credit_limit" class="block text-sm font-medium text-gray-700 mb-2">حد الائتمان</label>
        <input type="number" id="credit_limit" name="credit_limit" value="{{ old('credit_limit', $supplier->credit_limit ?? 0) }}" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="حد الائتمان">
        @error('credit_limit')
            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Payment Terms -->
    <div>
        <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">شروط الدفع</label>
        <input type="text" id="payment_terms" name="payment_terms" value="{{ old('payment_terms', $supplier->payment_terms ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="شروط الدفع">
        @error('payment_terms')
            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Notes -->
<div class="mb-6">
    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
    <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="ملاحظات">{{ old('notes', $supplier->notes ?? '') }}</textarea>
    @error('notes')
        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>

<!-- Status -->
<div class="mb-6">
    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة *</label>
    <select id="status" name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="نشط" {{ old('status', $supplier->status ?? '') === 'نشط' ? 'selected' : '' }}>نشط</option>
        <option value="غير نشط" {{ old('status', $supplier->status ?? '') === 'غير نشط' ? 'selected' : '' }}>غير نشط</option>
    </select>
    @error('status')
        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>
