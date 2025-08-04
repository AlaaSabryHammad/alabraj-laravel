'use client';

import { useState } from 'react';

export default function AttendanceTracker() {
  const [selectedDate, setSelectedDate] = useState(new Date().toISOString().split('T')[0]);
  
  const attendanceData = [
    {
      id: 1,
      name: 'أحمد محمد علي',
      position: 'مهندس مدني أول',
      checkIn: '07:30',
      checkOut: '16:45',
      status: 'present',
      workingHours: '9:15',
      avatar: 'https://readdy.ai/api/search-image?query=Professional%20Arab%20male%20construction%20engineer%20wearing%20safety%20helmet%20and%20reflective%20vest%20at%20construction%20site%2C%20clean%20white%20background%2C%20professional%20portrait%20photography%20style%2C%20high%20resolution&width=60&height=60&seq=att1&orientation=squarish'
    },
    {
      id: 2,
      name: 'فاطمة أحمد السعيد',
      position: 'محاسبة رئيسية',
      checkIn: '08:00',
      checkOut: '17:00',
      status: 'present',
      workingHours: '9:00',
      avatar: 'https://readdy.ai/api/search-image?query=Professional%20Arab%20female%20accountant%20wearing%20business%20attire%20in%20modern%20office%20setting%2C%20clean%20white%20background%2C%20professional%20portrait%20photography%20style%2C%20high%20resolution&width=60&height=60&seq=att2&orientation=squarish'
    },
    {
      id: 3,
      name: 'خالد عبدالله الزهراني',
      position: 'مشرف موقع',
      checkIn: '07:00',
      checkOut: '15:30',
      status: 'present',
      workingHours: '8:30',
      avatar: 'https://readdy.ai/api/search-image?query=Professional%20Arab%20male%20site%20supervisor%20wearing%20safety%20helmet%20and%20construction%20uniform%20at%20building%20site%2C%20clean%20white%20background%2C%20professional%20portrait%20photography%20style%2C%20high%20resolution&width=60&height=60&seq=att3&orientation=squarish'
    },
    {
      id: 4,
      name: 'نورا سالم القحطاني',
      position: 'مديرة المشاريع',
      checkIn: '--',
      checkOut: '--',
      status: 'absent',
      workingHours: '--',
      avatar: 'https://readdy.ai/api/search-image?query=Professional%20Arab%20female%20project%20manager%20in%20business%20suit%20holding%20blueprints%20in%20modern%20office%20environment%2C%20clean%20white%20background%2C%20professional%20portrait%20photography%20style%2C%20high%20resolution&width=60&height=60&seq=att4&orientation=squarish'
    },
    {
      id: 5,
      name: 'عبدالرحمن محمد الدوسري',
      position: 'فني معدات',
      checkIn: '08:15',
      checkOut: '--',
      status: 'late',
      workingHours: 'جاري العمل',
      avatar: 'https://readdy.ai/api/search-image?query=Professional%20Arab%20male%20equipment%20technician%20wearing%20work%20uniform%20and%20safety%20gear%20with%20construction%20machinery%20background%2C%20clean%20white%20background%2C%20professional%20portrait%20photography%20style%2C%20high%20resolution&width=60&height=60&seq=att5&orientation=squarish'
    },
    {
      id: 6,
      name: 'سارة علي المالكي',
      position: 'منسقة المستندات',
      checkIn: '07:45',
      checkOut: '16:30',
      status: 'present',
      workingHours: '8:45',
      avatar: 'https://readdy.ai/api/search-image?query=Professional%20Arab%20female%20document%20coordinator%20in%20office%20attire%20organizing%20files%20in%20modern%20office%20space%2C%20clean%20white%20background%2C%20professional%20portrait%20photography%20style%2C%20high%20resolution&width=60&height=60&seq=att6&orientation=squarish'
    }
  ];

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'present':
        return 'bg-emerald-100 text-emerald-800';
      case 'absent':
        return 'bg-red-100 text-red-800';
      case 'late':
        return 'bg-amber-100 text-amber-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'present':
        return 'حاضر';
      case 'absent':
        return 'غائب';
      case 'late':
        return 'متأخر';
      default:
        return 'غير محدد';
    }
  };

  const getStatusIcon = (status: string) => {
    switch (status) {
      case 'present':
        return 'ri-check-line';
      case 'absent':
        return 'ri-close-line';
      case 'late':
        return 'ri-time-line';
      default:
        return 'ri-question-line';
    }
  };

  const presentCount = attendanceData.filter(emp => emp.status === 'present').length;
  const absentCount = attendanceData.filter(emp => emp.status === 'absent').length;
  const lateCount = attendanceData.filter(emp => emp.status === 'late').length;

  return (
    <div className="space-y-6">
      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">إجمالي الموظفين</p>
              <p className="text-3xl font-bold text-gray-900">{attendanceData.length}</p>
            </div>
            <div className="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
              <i className="ri-group-line text-xl text-blue-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">الحاضرون</p>
              <p className="text-3xl font-bold text-emerald-600">{presentCount}</p>
            </div>
            <div className="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
              <i className="ri-check-line text-xl text-emerald-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">الغائبون</p>
              <p className="text-3xl font-bold text-red-600">{absentCount}</p>
            </div>
            <div className="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
              <i className="ri-close-line text-xl text-red-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">المتأخرون</p>
              <p className="text-3xl font-bold text-amber-600">{lateCount}</p>
            </div>
            <div className="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
              <i className="ri-time-line text-xl text-amber-600"></i>
            </div>
          </div>
        </div>
      </div>

      {/* Attendance Table */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div className="flex items-center justify-between mb-6">
          <h3 className="text-xl font-bold text-gray-900">سجل الحضور والانصراف</h3>
          <div className="flex items-center space-x-4 space-x-reverse">
            <input
              type="date"
              value={selectedDate}
              onChange={(e) => setSelectedDate(e.target.value)}
              className="px-4 py-2 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all text-sm"
            />
            <button className="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-sm whitespace-nowrap">
              تصدير التقرير
            </button>
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="border-b border-gray-100">
                <th className="text-right py-4 px-4 text-sm font-medium text-gray-700">الموظف</th>
                <th className="text-right py-4 px-4 text-sm font-medium text-gray-700">وقت الوصول</th>
                <th className="text-right py-4 px-4 text-sm font-medium text-gray-700">وقت المغادرة</th>
                <th className="text-right py-4 px-4 text-sm font-medium text-gray-700">ساعات العمل</th>
                <th className="text-right py-4 px-4 text-sm font-medium text-gray-700">الحالة</th>
                <th className="text-right py-4 px-4 text-sm font-medium text-gray-700">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              {attendanceData.map((employee) => (
                <tr key={employee.id} className="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                  <td className="py-4 px-4">
                    <div className="flex items-center">
                      <img
                        src={employee.avatar}
                        alt={employee.name}
                        className="w-10 h-10 rounded-full object-cover object-top ml-3"
                      />
                      <div>
                        <p className="font-medium text-gray-900 text-sm">{employee.name}</p>
                        <p className="text-gray-500 text-xs">{employee.position}</p>
                      </div>
                    </div>
                  </td>
                  <td className="py-4 px-4">
                    <span className="text-sm text-gray-900 font-mono">{employee.checkIn}</span>
                  </td>
                  <td className="py-4 px-4">
                    <span className="text-sm text-gray-900 font-mono">{employee.checkOut}</span>
                  </td>
                  <td className="py-4 px-4">
                    <span className="text-sm text-gray-900 font-mono">{employee.workingHours}</span>
                  </td>
                  <td className="py-4 px-4">
                    <span className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${getStatusColor(employee.status)}`}>
                      <i className={`${getStatusIcon(employee.status)} ml-1`}></i>
                      {getStatusText(employee.status)}
                    </span>
                  </td>
                  <td className="py-4 px-4">
                    <div className="flex space-x-2 space-x-reverse">
                      <button className="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors flex items-center justify-center">
                        <i className="ri-edit-line text-sm"></i>
                      </button>
                      <button className="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors flex items-center justify-center">
                        <i className="ri-time-line text-sm"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}