'use client';

import { useState } from 'react';

export default function TransportManagement() {
  const [activeTab, setActiveTab] = useState('daily');

  const dailyTrips = [
    {
      id: 1,
      vehicleNumber: 'ش-ر-س 1547',
      driver: 'محمد أحمد علي',
      route: 'الرياض - الخبر',
      startTime: '06:00',
      endTime: '14:30',
      status: 'completed',
      cargo: 'مواد بناء - أسمنت',
      quantity: '25 طن',
      distance: '395 كم'
    },
    {
      id: 2,
      vehicleNumber: 'ش-ن-ق 2891',
      driver: 'خالد سالم',
      route: 'جدة - مكة',
      startTime: '07:30',
      endTime: '--',
      status: 'in-progress',
      cargo: 'معدات البناء',
      quantity: '15 طن',
      distance: '85 كم'
    },
    {
      id: 3,
      vehicleNumber: 'ش-ع-م 3456',
      driver: 'أحمد محمود',
      route: 'الدمام - الجبيل',
      startTime: '08:00',
      endTime: '12:15',
      status: 'completed',
      cargo: 'رمل وحصى',
      quantity: '30 طن',
      distance: '65 كم'
    },
    {
      id: 4,
      vehicleNumber: 'ش-ب-ل 4789',
      driver: 'عبدالله الزهراني',
      route: 'الرياض - القصيم',
      startTime: '05:30',
      endTime: '--',
      status: 'in-progress',
      cargo: 'حديد البناء',
      quantity: '20 طن',
      distance: '330 كم'
    }
  ];

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'completed':
        return 'bg-emerald-100 text-emerald-800';
      case 'in-progress':
        return 'bg-blue-100 text-blue-800';
      case 'delayed':
        return 'bg-amber-100 text-amber-800';
      case 'cancelled':
        return 'bg-red-100 text-red-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'completed':
        return 'مكتملة';
      case 'in-progress':
        return 'جارية';
      case 'delayed':
        return 'متأخرة';
      case 'cancelled':
        return 'ملغية';
      default:
        return 'غير محدد';
    }
  };

  const completedTrips = dailyTrips.filter(trip => trip.status === 'completed').length;
  const inProgressTrips = dailyTrips.filter(trip => trip.status === 'in-progress').length;
  const totalDistance = dailyTrips.reduce((sum, trip) => sum + parseInt(trip.distance), 0);

  const tabs = [
    { id: 'daily', label: 'الرحلات اليومية', icon: 'ri-truck-line' },
    { id: 'history', label: 'السجل التاريخي', icon: 'ri-history-line' },
    { id: 'reports', label: 'التقارير', icon: 'ri-file-chart-line' }
  ];

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold text-gray-900 mb-2">حركة النقليات والشاحنات</h1>
            <p className="text-gray-600">متابعة الرحلات اليومية وإدارة النقليات</p>
          </div>
          <button className="bg-gradient-to-r from-teal-600 to-teal-700 text-white px-6 py-3 rounded-xl font-medium hover:from-teal-700 hover:to-teal-800 transition-all duration-200 flex items-center whitespace-nowrap">
            <i className="ri-add-line ml-2"></i>
            إضافة رحلة جديدة
          </button>
        </div>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">إجمالي الرحلات</p>
              <p className="text-3xl font-bold text-gray-900">{dailyTrips.length}</p>
            </div>
            <div className="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
              <i className="ri-truck-line text-xl text-blue-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">الرحلات المكتملة</p>
              <p className="text-3xl font-bold text-emerald-600">{completedTrips}</p>
            </div>
            <div className="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
              <i className="ri-check-line text-xl text-emerald-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">الرحلات الجارية</p>
              <p className="text-3xl font-bold text-blue-600">{inProgressTrips}</p>
            </div>
            <div className="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
              <i className="ri-time-line text-xl text-blue-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">إجمالي المسافة</p>
              <p className="text-3xl font-bold text-teal-600">{totalDistance}</p>
              <p className="text-xs text-gray-500">كيلومتر</p>
            </div>
            <div className="w-12 h-12 bg-teal-50 rounded-xl flex items-center justify-center">
              <i className="ri-road-map-line text-xl text-teal-600"></i>
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
                  ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg'
                  : 'text-gray-600 hover:text-teal-600 hover:bg-gray-50'
              }`}
            >
              <i className={`${tab.icon} ml-2`}></i>
              {tab.label}
            </button>
          ))}
        </div>
      </div>

      {/* Daily Trips */}
      {activeTab === 'daily' && (
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <h3 className="text-xl font-bold text-gray-900 mb-6">الرحلات اليومية - {new Date().toLocaleDateString('ar-SA')}</h3>
          
          <div className="space-y-4">
            {dailyTrips.map((trip) => (
              <div key={trip.id} className="border border-gray-100 rounded-xl p-6 hover:shadow-md transition-all duration-200">
                <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
                  <div className="lg:col-span-1">
                    <div className="flex items-center mb-4">
                      <div className="w-12 h-12 bg-teal-50 rounded-xl flex items-center justify-center ml-4">
                        <i className="ri-truck-line text-xl text-teal-600"></i>
                      </div>
                      <div>
                        <h4 className="font-bold text-gray-900">{trip.vehicleNumber}</h4>
                        <p className="text-sm text-gray-600">{trip.driver}</p>
                      </div>
                    </div>
                    <div className={`inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${getStatusColor(trip.status)}`}>
                      {getStatusText(trip.status)}
                    </div>
                  </div>
                  
                  <div className="lg:col-span-2">
                    <div className="grid grid-cols-2 gap-4 mb-4">
                      <div>
                        <p className="text-xs text-gray-500 mb-1">المسار</p>
                        <p className="font-medium text-gray-900">{trip.route}</p>
                      </div>
                      <div>
                        <p className="text-xs text-gray-500 mb-1">البضائع</p>
                        <p className="font-medium text-gray-900">{trip.cargo}</p>
                      </div>
                    </div>
                    
                    <div className="grid grid-cols-3 gap-4">
                      <div>
                        <p className="text-xs text-gray-500 mb-1">الكمية</p>
                        <p className="text-sm font-medium text-gray-900">{trip.quantity}</p>
                      </div>
                      <div>
                        <p className="text-xs text-gray-500 mb-1">المسافة</p>
                        <p className="text-sm font-medium text-gray-900">{trip.distance}</p>
                      </div>
                      <div>
                        <p className="text-xs text-gray-500 mb-1">وقت الرحلة</p>
                        <p className="text-sm font-medium text-gray-900">{trip.startTime} - {trip.endTime}</p>
                      </div>
                    </div>
                  </div>
                  
                  <div className="lg:col-span-1 flex flex-col items-end justify-between">
                    <div className="text-left mb-4">
                      <p className="text-xs text-gray-500 mb-1">تتبع الشاحنة</p>
                      <button className="text-teal-600 hover:text-teal-700 font-medium text-sm">
                        عرض الموقع الحالي
                      </button>
                    </div>
                    
                    <div className="flex space-x-2 space-x-reverse">
                      <button className="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors flex items-center justify-center">
                        <i className="ri-edit-line text-sm"></i>
                      </button>
                      <button className="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100 transition-colors flex items-center justify-center">
                        <i className="ri-map-pin-line text-sm"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Historical Records */}
      {activeTab === 'history' && (
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <h3 className="text-xl font-bold text-gray-900 mb-6">السجل التاريخي</h3>
          <div className="text-center py-12">
            <i className="ri-history-line text-4xl text-gray-300 mb-4"></i>
            <p className="text-gray-500">سيتم عرض السجل التاريخي للرحلات هنا</p>
          </div>
        </div>
      )}

      {/* Reports */}
      {activeTab === 'reports' && (
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <h3 className="text-xl font-bold text-gray-900 mb-6">تقارير النقليات</h3>
          <div className="text-center py-12">
            <i className="ri-file-chart-line text-4xl text-gray-300 mb-4"></i>
            <p className="text-gray-500">سيتم عرض التقارير التحليلية هنا</p>
          </div>
        </div>
      )}
    </div>
  );
}