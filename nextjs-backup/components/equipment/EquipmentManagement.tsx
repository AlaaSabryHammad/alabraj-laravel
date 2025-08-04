'use client';

import { useState } from 'react';

export default function EquipmentManagement() {
  const [activeTab, setActiveTab] = useState('list');
  const [showAddForm, setShowAddForm] = useState(false);

  const equipment = [
    {
      id: 1,
      name: 'حفارة CAT 320',
      type: 'حفارة',
      status: 'available',
      location: 'مستودع الرياض',
      lastMaintenance: '2024-01-15',
      nextMaintenance: '2024-04-15',
      operator: 'محمد أحمد',
      image: 'https://readdy.ai/api/search-image?query=Modern%20yellow%20CAT%20excavator%20heavy%20construction%20machinery%20in%20construction%20site%20with%20clean%20professional%20background%2C%20industrial%20equipment%20photography%20style%2C%20high%20resolution&width=300&height=200&seq=eq1&orientation=landscape'
    },
    {
      id: 2,
      name: 'خلاطة خرسانة LIEBHERR',
      type: 'خلاطة',
      status: 'maintenance',
      location: 'ورشة الصيانة',
      lastMaintenance: '2024-01-10',
      nextMaintenance: '2024-02-10',
      operator: 'علي سالم',
      image: 'https://readdy.ai/api/search-image?query=Modern%20concrete%20mixer%20truck%20LIEBHERR%20brand%20at%20construction%20site%20with%20clean%20professional%20background%2C%20industrial%20equipment%20photography%20style%2C%20high%20resolution&width=300&height=200&seq=eq2&orientation=landscape'
    },
    {
      id: 3,
      name: 'رافعة شوكية TOYOTA',
      type: 'رافعة شوكية',
      status: 'in-use',
      location: 'مشروع برج الخبر',
      lastMaintenance: '2024-01-08',
      nextMaintenance: '2024-03-08',
      operator: 'خالد محمد',
      image: 'https://readdy.ai/api/search-image?query=Modern%20TOYOTA%20forklift%20industrial%20vehicle%20in%20warehouse%20setting%20with%20clean%20professional%20background%2C%20industrial%20equipment%20photography%20style%2C%20high%20resolution&width=300&height=200&seq=eq3&orientation=landscape'
    },
    {
      id: 4,
      name: 'شاحنة نقل MAN',
      type: 'شاحنة',
      status: 'available',
      location: 'مستودع جدة',
      lastMaintenance: '2024-01-20',
      nextMaintenance: '2024-05-20',
      operator: 'عبدالله أحمد',
      image: 'https://readdy.ai/api/search-image?query=Modern%20MAN%20construction%20truck%20heavy%20duty%20vehicle%20at%20construction%20site%20with%20clean%20professional%20background%2C%20industrial%20equipment%20photography%20style%2C%20high%20resolution&width=300&height=200&seq=eq4&orientation=landscape'
    }
  ];

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'available':
        return 'bg-emerald-100 text-emerald-800';
      case 'in-use':
        return 'bg-blue-100 text-blue-800';
      case 'maintenance':
        return 'bg-amber-100 text-amber-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'available':
        return 'متاح';
      case 'in-use':
        return 'قيد الاستخدام';
      case 'maintenance':
        return 'تحت الصيانة';
      default:
        return 'غير محدد';
    }
  };

  const availableCount = equipment.filter(eq => eq.status === 'available').length;
  const inUseCount = equipment.filter(eq => eq.status === 'in-use').length;
  const maintenanceCount = equipment.filter(eq => eq.status === 'maintenance').length;

  const tabs = [
    { id: 'list', label: 'قائمة المعدات', icon: 'ri-list-check' },
    { id: 'maintenance', label: 'سجل الصيانة', icon: 'ri-tools-line' }
  ];

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold text-gray-900 mb-2">إدارة المعدات</h1>
            <p className="text-gray-600">متابعة المعدات والآليات وحالتها وصيانتها</p>
          </div>
          <button
            onClick={() => setShowAddForm(true)}
            className="bg-gradient-to-r from-orange-600 to-orange-700 text-white px-6 py-3 rounded-xl font-medium hover:from-orange-700 hover:to-orange-800 transition-all duration-200 flex items-center whitespace-nowrap"
          >
            <i className="ri-add-line ml-2"></i>
            إضافة معدة جديدة
          </button>
        </div>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">إجمالي المعدات</p>
              <p className="text-3xl font-bold text-gray-900">{equipment.length}</p>
            </div>
            <div className="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
              <i className="ri-tools-line text-xl text-blue-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">المتاحة</p>
              <p className="text-3xl font-bold text-emerald-600">{availableCount}</p>
            </div>
            <div className="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
              <i className="ri-check-line text-xl text-emerald-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">قيد الاستخدام</p>
              <p className="text-3xl font-bold text-blue-600">{inUseCount}</p>
            </div>
            <div className="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
              <i className="ri-play-line text-xl text-blue-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">تحت الصيانة</p>
              <p className="text-3xl font-bold text-amber-600">{maintenanceCount}</p>
            </div>
            <div className="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
              <i className="ri-tools-line text-xl text-amber-600"></i>
            </div>
          </div>
        </div>
      </div>

      {/* Tabs */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-2">
        <div className="flex space-x-1 space-x-reverse">
          {tabs.map((tab) => (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              className={`flex-1 flex items-center justify-center px-4 py-3 rounded-xl font-medium transition-all duration-200 ${
                activeTab === tab.id
                  ? 'bg-gradient-to-r from-orange-600 to-orange-700 text-white shadow-lg'
                  : 'text-gray-600 hover:text-orange-600 hover:bg-gray-50'
              }`}
            >
              <i className={`${tab.icon} ml-2`}></i>
              {tab.label}
            </button>
          ))}
        </div>
      </div>

      {/* Equipment Grid */}
      {activeTab === 'list' && (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {equipment.map((item) => (
            <div key={item.id} className="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
              <div className="relative">
                <img
                  src={item.image}
                  alt={item.name}
                  className="w-full h-48 object-cover object-top"
                />
                <div className={`absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-medium ${getStatusColor(item.status)}`}>
                  {getStatusText(item.status)}
                </div>
              </div>
              
              <div className="p-6">
                <h3 className="text-xl font-bold text-gray-900 mb-2">{item.name}</h3>
                <p className="text-gray-600 mb-4">{item.type}</p>
                
                <div className="space-y-2 mb-4">
                  <div className="flex items-center text-sm text-gray-600">
                    <i className="ri-map-pin-line ml-2 w-4 h-4 flex items-center justify-center"></i>
                    {item.location}
                  </div>
                  <div className="flex items-center text-sm text-gray-600">
                    <i className="ri-user-line ml-2 w-4 h-4 flex items-center justify-center"></i>
                    {item.operator}
                  </div>
                  <div className="flex items-center text-sm text-gray-600">
                    <i className="ri-calendar-line ml-2 w-4 h-4 flex items-center justify-center"></i>
                    آخر صيانة: {new Date(item.lastMaintenance).toLocaleDateString('ar-SA')}
                  </div>
                </div>
                
                <div className="flex justify-between items-center">
                  <div className="text-xs text-gray-500">
                    الصيانة القادمة: {new Date(item.nextMaintenance).toLocaleDateString('ar-SA')}
                  </div>
                  <div className="flex space-x-2 space-x-reverse">
                    <button className="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors flex items-center justify-center">
                      <i className="ri-edit-line text-sm"></i>
                    </button>
                    <button className="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors flex items-center justify-center">
                      <i className="ri-tools-line text-sm"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Maintenance Log */}
      {activeTab === 'maintenance' && (
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <h3 className="text-xl font-bold text-gray-900 mb-6">سجل الصيانة</h3>
          <div className="space-y-4">
            {equipment.map((item) => (
              <div key={item.id} className="border border-gray-100 rounded-xl p-4 hover:bg-gray-50 transition-colors">
                <div className="flex items-center justify-between">
                  <div className="flex items-center">
                    <div className="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center ml-4">
                      <i className="ri-tools-line text-orange-600"></i>
                    </div>
                    <div>
                      <h4 className="font-medium text-gray-900">{item.name}</h4>
                      <p className="text-sm text-gray-600">آخر صيانة: {new Date(item.lastMaintenance).toLocaleDateString('ar-SA')}</p>
                    </div>
                  </div>
                  <div className="text-left">
                    <p className="text-sm text-gray-900">الصيانة القادمة</p>
                    <p className="text-sm text-amber-600 font-medium">{new Date(item.nextMaintenance).toLocaleDateString('ar-SA')}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}