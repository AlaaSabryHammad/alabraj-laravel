@extends('layouts.app')

@section('title', 'التقرير الشهري لقطع الغيار')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('reports.spare-parts.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="ri-arrow-right-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <i class="ri-calendar-2-line text-green-600"></i>
                        التقرير الشهري لقطع الغيار
                    </h1>
                    <p class="text-gray-600">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('ar')->format('F Y') }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-printer-line"></i>
                    طباعة
                </button>
                <button onclick="exportToPDF()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-file-pdf-line"></i>
                    تصدير PDF
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <form method="GET" action="{{ route('reports.spare-parts.monthly') }}" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الشهر والسنة</label>
                    <input type="month" name="month" value="{{ $month }}" 
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">المستودع</label>
                    <select name="warehouse_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">جميع المستودعات</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ $warehouseId == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-search-line"></i>
                    عرض
                </button>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">قطع مُستلمة</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_received']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ri-arrow-down-line text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">قطع مُصدرة</p>
                        <p class="text-2xl font-bold text-red-600">{{ number_format($stats['total_exported']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="ri-arrow-up-line text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">قيمة الاستلام</p>
                        <p class="text-xl font-bold text-green-600">{{ number_format($stats['total_received_value'], 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ri-money-dollar-circle-line text-xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">قيمة التصدير</p>
                        <p class="text-xl font-bold text-red-600">{{ number_format($stats['total_exported_value'], 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="ri-money-dollar-circle-line text-xl text-red-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">أنواع القطع</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['unique_parts'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ri-tools-line text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">أيام النشاط</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['days_with_activity'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="ri-calendar-check-line text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Parts -->
        @if($topParts->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="ri-trophy-line text-yellow-600"></i>
                أكثر القطع استخداماً
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($topParts as $index => $part)
                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold text-yellow-600">{{ $index + 1 }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $part['spare_part']->name }}</p>
                                <p class="text-sm text-gray-600">{{ $part['spare_part']->code }}</p>
                            </div>
                        </div>
                        <div class="text-left">
                            <p class="text-lg font-bold text-red-600">{{ number_format($part['total_exported']) }}</p>
                            <p class="text-sm text-gray-600">{{ number_format($part['total_value'], 2) }} ر.س</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Daily Summary Chart -->
        @if($dailyStats->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="ri-line-chart-line text-blue-600"></i>
                الحركة اليومية للشهر
            </h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-right">التاريخ</th>
                            <th class="px-4 py-2 text-center">استلام</th>
                            <th class="px-4 py-2 text-center">تصدير</th>
                            <th class="px-4 py-2 text-center">صافي الحركة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyStats as $dayStat)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 font-medium">
                                {{ \Carbon\Carbon::parse($dayStat['date'])->format('d/m/Y') }}
                                <br>
                                <small class="text-gray-500">{{ \Carbon\Carbon::parse($dayStat['date'])->locale('ar')->dayName }}</small>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <span class="text-green-600 font-medium">{{ number_format($dayStat['received']) }}</span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <span class="text-red-600 font-medium">{{ number_format($dayStat['exported']) }}</span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                @php $net = $dayStat['received'] - $dayStat['exported']; @endphp
                                <span class="font-medium {{ $net >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $net >= 0 ? '+' : '' }}{{ number_format($net) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="ri-history-line text-gray-600"></i>
                    آخر المعاملات ({{ $transactions->take(20)->count() }} من أصل {{ $transactions->count() }})
                </h2>
            </div>

            @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">النوع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">القطعة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الكمية</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">القيمة</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transactions->take(20) as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $transaction->transaction_type == 'استلام' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $transaction->transaction_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $transaction->sparePart->name }}</div>
                                <div class="text-gray-500">{{ $transaction->sparePart->code }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ number_format($transaction->quantity) }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                {{ number_format($transaction->total_amount, 2) }} ر.س
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-calendar-2-line text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد معاملات</h3>
                <p class="text-gray-500">لا توجد معاملات لقطع الغيار في هذا الشهر</p>
            </div>
            @endif
        </div>
    </div>
@endsection
