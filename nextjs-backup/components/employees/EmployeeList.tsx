'use client';

import { useState } from 'react';

export default function EmployeeList() {
  const [searchTerm, setSearchTerm] = useState('');
  const [filterStatus, setFilterStatus] = useState('all');

  const employees = [
    {
      id: 1,
      name: 'أحمد محمد علي',
      position: 'مهندس مدني أول',
      phone: '+966501234567',
      status: 'active',
      joinDate: '2022-01-15',
      avatar: 'https://readdy.ai/api/search-image?query=Professional%20Arab%20male%20construction%20engineer%20wearing%20safety%20helmet%20and%20reflective%20vest%20at%20construction%20site%2C%20clean%20white%20background%2C%20professional%20portrait%20photography%20style%2C%20high%20resolution&width=100&height=100&seq=emp1&orientation=squarish'
    },
    {
      id: 2,
      name: 'فاطمة أحمد السعيد',
      position: 'محاسبة رئيسية',
      phone: '+966502345678',
      status: 'active',
      joinDate: '2021-08-22',
      avatar: 'https://readdy.ai/api/search-image?query=Professional%20Arab%20female%20accountant%20wearing%20business%20attire%20in%20modern%20office%20setting%2C%20clean%20white%20background%2C%20professional%20portrait%20photography%20style%2C%20high%20resolution&width=100&height=100&seq=emp2&orientation=squarish'
    },
    {
      id: 3,
      name: 'خالد عبدالله الزهراني',
      position: 'مشرف موقع',
      phone: '+966503456789',
      status: 'vacation',
      joinDate: '2020-03-10',
      avatar: 'https://readdy.ai/api/search-image?query=Professional%20Arab%20male%20site%20supervisor%20wearing%20safety%20helmet%20and%20construction%20uniform%20at%20building%20site%2C%20clean%20white%20background%2C%20professional%20portrait%20photography%20style%2C%20high%20resolution&width=100&height=100&seq=emp3&orientation=squarish'
    },
    {
      id: 4,
      name: 'نورا سالم القحطاني',
      position: 'مديرة المشاريع',
      phone: '+966504567890',
      status: 'active',
      joinDate: '2021-11-05',
      avatar: 'https://readdy.ai/api/search-image?query=Professional%20Arab%20female%20project%20manager%20in%20business%20suit%20holding%20blueprints%20in%20modern%20office%20environment%2C%20clean%20white%20background%2C%20professional%20portrait%20photography%20style%2C%20high%20resolution&width=100&height=100&seq=emp4&orientation=squarish'
    },
    {
      id: 5,
      name: 'عبدالرحمن محمد الدوسري',
      position: 'فني معدات',
      phone: '+966505678901',
      status: 'active',
      joinDate: '2022-06-18',
      avatar: 'https://readdy.ai/api/search-image?query=Professional%20Arab%20male%20equipment%20technician%20wearing%20work%20uniform%20and%20safety%20gear%20with%20construction%20machinery%20background%2C%20clean%20white%20background%2C%20professional%20portrait%20photography%20style%2C%20high%20resolution&width=100&height=100&seq=emp5&orientation=squarish'
    },
    {
      id: 6,
      name: 'سارة علي المالكي',
      position: 'منسقة المستندات',
      phone: '+966506789012',
      status: 'inactive',
      joinDate: '2023-02-14',
      avatar: 'https://readdy.ai/api/search-image?query=Professional%20Arab%20female%20document%20coordinator%20in%20office%20attire%20organizing%20files%20in%20modern%20office%20space%2C%20clean%20white%20background%2C%20professional%20portrait%20photography%20style%2C%20high%20resolution&width=100&height=100&seq=emp6&orientation=squarish'
    }
  ];

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'active':
        return 'bg-emerald-100 text-emerald-800';
      case 'vacation':
        return 'bg-amber-100 text-amber-800';
      case 'inactive':
        return 'bg-red-100 text-red-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'active':
        return 'نشط';
      case 'vacation':
        return 'في إجازة';
      case 'inactive':
        return 'غير نشط';
      default:
        return 'غير محدد';
    }
  };

  const filteredEmployees = employees.filter(employee => {
    const matchesSearch = employee.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         employee.position.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesFilter = filterStatus === 'all' || employee.status === filterStatus;
    return matchesSearch && matchesFilter;
  });

  return (
    <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
      {/* Search and Filter */}
      <div className="flex flex-col md:flex-row gap-4 mb-6">
        <div className="flex-1 relative">
          <i className="ri-search-line absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          <input
            type="text"
            placeholder="البحث في الموظفين..."
            className="w-full pr-10 pl-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all text-sm"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
        </div>
        <div className="relative">
          <select
            value={filterStatus}
            onChange={(e) => setFilterStatus(e.target.value)}
            className="appearance-none bg-white border border-gray-200 rounded-xl px-4 py-3 pr-8 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all text-sm"
          >
            <option value="all">جميع الحالات</option>
            <option value="active">نشط</option>
            <option value="vacation">في إجازة</option>
            <option value="inactive">غير نشط</option>
          </select>
          <i className="ri-arrow-down-s-line absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
        </div>
      </div>

      {/* Employee Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {filteredEmployees.map((employee) => (
          <div key={employee.id} className="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div className="flex items-center mb-4">
              <div className="relative">
                <img
                  src={employee.avatar}
                  alt={employee.name}
                  className="w-16 h-16 rounded-full object-cover object-top border-4 border-white shadow-md"
                />
                <div className={`absolute -bottom-1 -left-1 w-6 h-6 rounded-full border-2 border-white ${
                  employee.status === 'active' ? 'bg-emerald-500' : 
                  employee.status === 'vacation' ? 'bg-amber-500' : 'bg-red-500'
                }`}></div>
              </div>
              <div className="mr-4 flex-1">
                <h3 className="font-bold text-gray-900 text-lg mb-1">{employee.name}</h3>
                <p className="text-gray-600 text-sm">{employee.position}</p>
              </div>
            </div>

            <div className="space-y-3 mb-4">
              <div className="flex items-center text-gray-600">
                <i className="ri-phone-line ml-3 w-4 h-4 flex items-center justify-center"></i>
                <span className="text-sm">{employee.phone}</span>
              </div>
              <div className="flex items-center text-gray-600">
                <i className="ri-calendar-line ml-3 w-4 h-4 flex items-center justify-center"></i>
                <span className="text-sm">انضم في {new Date(employee.joinDate).toLocaleDateString('ar-SA')}</span>
              </div>
            </div>

            <div className="flex items-center justify-between">
              <span className={`px-3 py-1 rounded-full text-xs font-medium ${getStatusColor(employee.status)}`}>
                {getStatusText(employee.status)}
              </span>
              <div className="flex space-x-2 space-x-reverse">
                <button className="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors w-8 h-8 flex items-center justify-center">
                  <i className="ri-edit-line text-sm"></i>
                </button>
                <button className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors w-8 h-8 flex items-center justify-center">
                  <i className="ri-delete-bin-line text-sm"></i>
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>

      {filteredEmployees.length === 0 && (
        <div className="text-center py-12">
          <i className="ri-user-search-line text-4xl text-gray-300 mb-4"></i>
          <p className="text-gray-500">لا توجد نتائج مطابقة لبحثك</p>
        </div>
      )}
    </div>
  );
}