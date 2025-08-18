@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">أنواع قطع الغيار</h5>
                        <a href="{{ route('spare-part-types.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة نوع جديد
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($sparePartTypes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>الفئة</th>
                                            <th>الوصف</th>
                                            <th>الحالة</th>
                                            <th>عدد القطع</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sparePartTypes as $type)
                                            <tr>
                                                <td>{{ $type->name }}</td>
                                                <td>{{ $type->category_label }}</td>
                                                <td>{{ $type->description ?: 'لا يوجد' }}</td>
                                                <td>
                                                    @if ($type->is_active)
                                                        <span class="badge badge-success">نشط</span>
                                                    @else
                                                        <span class="badge badge-danger">غير نشط</span>
                                                    @endif
                                                </td>
                                                <td>{{ $type->spareParts->count() }}</td>
                                                <td>
                                                    <a href="{{ route('spare-part-types.edit', $type) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if ($type->spareParts->count() == 0)
                                                        <form method="POST"
                                                            action="{{ route('spare-part-types.destroy', $type) }}"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $sparePartTypes->links() }}
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted">لا توجد أنواع قطع غيار</p>
                                <a href="{{ route('spare-part-types.create') }}" class="btn btn-primary">إضافة النوع
                                    الأول</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
