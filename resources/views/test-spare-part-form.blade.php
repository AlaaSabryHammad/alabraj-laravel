@extends('layouts.app')

@section('title', 'اختبار إضافة قطعة غيار')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">اختبار إضافة قطعة غيار - مستودع رقم 41</h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('warehouses.store-spare-part', 41) }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="code">كود قطعة الغيار *</label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                            id="code" name="code" value="{{ old('code', 'TEST-' . time()) }}"
                                            required>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name">اسم قطعة الغيار *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name"
                                            value="{{ old('name', 'اختبار - ' . date('Y-m-d H:i:s')) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="unit_price">سعر الوحدة *</label>
                                        <input type="number" class="form-control @error('unit_price') is-invalid @enderror"
                                            id="unit_price" name="unit_price" value="{{ old('unit_price', '100.00') }}"
                                            step="0.01" min="0" required>
                                        @error('unit_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="unit_type">نوع الوحدة *</label>
                                        <select class="form-control @error('unit_type') is-invalid @enderror" id="unit_type"
                                            name="unit_type" required>
                                            <option value="قطعة" {{ old('unit_type') == 'قطعة' ? 'selected' : '' }}>قطعة
                                            </option>
                                            <option value="متر" {{ old('unit_type') == 'متر' ? 'selected' : '' }}>متر
                                            </option>
                                            <option value="كيلو" {{ old('unit_type') == 'كيلو' ? 'selected' : '' }}>كيلو
                                            </option>
                                        </select>
                                        @error('unit_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="minimum_stock">الحد الأدنى للمخزون *</label>
                                        <input type="number"
                                            class="form-control @error('minimum_stock') is-invalid @enderror"
                                            id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', '5') }}"
                                            min="0" required>
                                        @error('minimum_stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="initial_quantity">الكمية الأولية *</label>
                                        <input type="number"
                                            class="form-control @error('initial_quantity') is-invalid @enderror"
                                            id="initial_quantity" name="initial_quantity"
                                            value="{{ old('initial_quantity', '10') }}" min="0" required>
                                        @error('initial_quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="description">الوصف</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description', 'اختبار إضافة قطعة غيار جديدة') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">حفظ قطعة الغيار</button>
                                <a href="{{ route('warehouses.show', 41) }}" class="btn btn-secondary">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
