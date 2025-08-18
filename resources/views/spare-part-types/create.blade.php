@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">إضافة نوع قطعة غيار جديد</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('spare-part-types.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">اسم نوع القطعة <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category">الفئة <span class="text-danger">*</span></label>
                                        <select class="form-control @error('category') is-invalid @enderror" id="category"
                                            name="category" required>
                                            <option value="">اختر الفئة</option>
                                            <option value="engine" {{ old('category') == 'engine' ? 'selected' : '' }}>محرك
                                            </option>
                                            <option value="transmission"
                                                {{ old('category') == 'transmission' ? 'selected' : '' }}>ناقل الحركة
                                            </option>
                                            <option value="brakes" {{ old('category') == 'brakes' ? 'selected' : '' }}>
                                                المكابح</option>
                                            <option value="electrical"
                                                {{ old('category') == 'electrical' ? 'selected' : '' }}>كهربائي</option>
                                            <option value="hydraulic"
                                                {{ old('category') == 'hydraulic' ? 'selected' : '' }}>هيدروليك</option>
                                            <option value="cooling" {{ old('category') == 'cooling' ? 'selected' : '' }}>
                                                تبريد</option>
                                            <option value="filters" {{ old('category') == 'filters' ? 'selected' : '' }}>
                                                فلاتر</option>
                                            <option value="tires" {{ old('category') == 'tires' ? 'selected' : '' }}>
                                                إطارات</option>
                                            <option value="body" {{ old('category') == 'body' ? 'selected' : '' }}>هيكل
                                            </option>
                                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>أخرى
                                            </option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">الوصف</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group text-right">
                                <a href="{{ route('spare-part-types.index') }}" class="btn btn-secondary">إلغاء</a>
                                <button type="submit" class="btn btn-primary">حفظ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
