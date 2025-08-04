'use client';

import { useState } from 'react';

export default function ProjectManagement() {
  const [activeTab, setActiveTab] = useState('active');

  const projects = [
    {
      id: 1,
      name: 'برج الرياض التجاري',
      client: 'شركة الخليج للتطوير',
      status: 'in-progress',
      progress: 75,
      startDate: '2023-06-01',
      endDate: '2024-08-30',
      budget: 15000000,
      spent: 11250000,
      location: 'الرياض، المملكة العربية السعودية',
      manager: 'أحمد محمد علي',
      team: 45,
      image: 'https://readdy.ai/api/search-image?query=Modern%20commercial%20tower%20building%20construction%20site%20in%20Riyadh%20Saudi%20Arabia%20with%20cranes%20and%20construction%20workers%2C%20professional%20architecture%20photography%2C%20clean%20background&width=400&height=200&seq=proj1&orientation=landscape'
    },
    {
      id: 2,
      name: 'فيلا الخبر السكنية',
      client: 'مؤسسة البناء الحديث',
      status: 'in-progress',
      progress: 60,
      startDate: '2023-09-15',
      endDate: '2024-06-30',
      budget: 8500000,
      spent: 5100000,
      location: 'الخبر، المنطقة الشرقية',
      manager: 'فاطمة أحمد السعيد',
      team: 25,
      image: 'https://readdy.ai/api/search-image?query=Luxury%20residential%20villa%20construction%20project%20in%20Al%20Khobar%20Saudi%20Arabia%20with%20modern%20architecture%20design%2C%20construction%20site%20with%20clean%20professional%20background&width=400&height=200&seq=proj2&orientation=landscape'
    },
    {
      id: 3,
      name: 'مجمع جدة التجاري',
      client: 'شركة التعمير الشامل',
      status: 'completed',
      progress: 100,
      startDate: '2022-12-01',
      endDate: '2024-01-15',
      budget: 25000000,
      spent: 24500000,
      location: 'جدة، منطقة مكة المكرمة',
      manager: 'خالد عبدالله الزهراني',
      team: 80,
      image: 'https://readdy.ai/api/search-image?query=Completed%20modern%20commercial%20complex%20building%20in%20Jeddah%20Saudi%20Arabia%20with%20glass%20facade%20and%20contemporary%20architecture%2C%20professional%20real%20estate%20photography&width=400&height=200&seq=proj3&orientation=landscape'
    },
    {
      id: 4,
      name: 'برج الدمام السكني',
      client: 'مجموعة الإنشاء المتقدم',
      status: 'on-hold',
      progress: 30,
      startDate: '2023-11-01',
      endDate: '2025-03-30',
      budget: 18000000,
      spent: 5400000,
      location: 'الدمام، المنطقة الشرقية',
      manager: 'نورا سالم القحطاني',
      team: 35,
      image: 'https://readdy.ai/api/search-image?query=Residential%20tower%20construction%20project%20in%20Dammam%20Saudi%20Arabia%20with%20modern%20design%2C%20construction%20site%20with%20cranes%20and%20equipment%2C%20professional%20architecture%20photography&width=400&height=200&seq=proj4&orientation=landscape'
    }
  ];

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'in-progress':
        return 'bg-blue-100 text-blue-800';
      case 'completed':
        return 'bg-emerald-100 text-emerald-800';
      case 'on-hold':
        return 'bg-amber-100 text-amber-800';
      case 'cancelled':
        return 'bg-red-100 text-red-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'in-progress':
        return 'قيد التنفيذ';
      case 'completed':
        return 'مكتمل';
      case 'on-hold':
        return 'متوقف';
      case 'cancelled':
        return 'ملغي';
      default:
        return 'غير محدد';
    }
  };

  const getProgressColor = (progress: number) => {
    if (progress >= 80) return 'bg-emerald-500';
    if (progress >= 50) return 'bg-blue-500';
    if (progress >= 25) return 'bg-amber-500';
    return 'bg-red-500';
  };

  const formatCurrency = (amount: number) => {
    return `${(amount / 1000000).toFixed(1)}M ر.س`;
  };

  const filteredProjects = projects.filter(project => {
    if (activeTab === 'active') return project.status === 'in-progress';
    if (activeTab === 'completed') return project.status === 'completed';
    if (activeTab === 'on-hold') return project.status === 'on-hold';
    return true;
  });

  const activeProjects = projects.filter(p => p.status === 'in-progress').length;
  const completedProjects = projects.filter(p => p.status === 'completed').length;
  const totalBudget = projects.reduce((sum, p) => sum + p.budget, 0);
  const totalSpent = projects.reduce((sum, p) => sum + p.spent, 0);

  const tabs = [
    { id: 'active', label: 'المشاريع النشطة', icon: 'ri-play-line', count: activeProjects },
    { id: 'completed', label: 'المشاريع المكتملة', icon: 'ri-check-line', count: completedProjects },
    { id: 'on-hold', label: 'المشاريع المتوقفة', icon: 'ri-pause-line', count: projects.filter(p => p.status === 'on-hold').length },
    { id: 'all', label: 'جميع المشاريع', icon: 'ri-building-line', count: projects.length }
  ];

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold text-gray-900 mb-2">إدارة المشاريع</h1>
            <p className="text-gray-600">متابعة وإدارة جميع مشاريع الشركة والجداول الزمنية</p>
          </div>
          <button className="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-3 rounded-xl font-medium hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 flex items-center whitespace-nowrap">
            <i className="ri-add-line ml-2"></i>
            إضافة مشروع جديد
          </button>
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">إجمالي المشاريع</p>
              <p className="text-3xl font-bold text-gray-900">{projects.length}</p>
            </div>
            <div className="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
              <i className="ri-building-line text-xl text-blue-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">المشاريع النشطة</p>
              <p className="text-3xl font-bold text-blue-600">{activeProjects}</p>
            </div>
            <div className="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
              <i className="ri-play-line text-xl text-blue-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">المشاريع المكتملة</p>
              <p className="text-3xl font-bold text-emerald-600">{completedProjects}</p>
            </div>
            <div className="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
              <i className="ri-check-line text-xl text-emerald-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">إجمالي الميزانية</p>
              <p className="text-3xl font-bold text-indigo-600">{formatCurrency(totalBudget)}</p>
              <p className="text-xs text-gray-500">منفق: {formatCurrency(totalSpent)}</p>
            </div>
            <div className="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
              <i className="ri-money-dollar-circle-line text-xl text-indigo-600"></i>
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
                  ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg'
                  : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50'
              }`}
            >
              <i className={`${tab.icon} ml-2`}></i>
              {tab.label}
              <span className={`mr-2 text-xs font-bold px-2 py-1 rounded-full ${
                activeTab === tab.id
                  ? 'bg-white/20 text-white'
                  : 'bg-gray-100 text-gray-600'
              }`}>
                {tab.count}
              </span>
            </button>
          ))}
        </div>
      </div>

      {/* Projects Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {filteredProjects.map((project) => (
          <div key={project.id} className="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div className="relative">
              <img
                src={project.image}
                alt={project.name}
                className="w-full h-48 object-cover object-top"
              />
              <div className={`absolute top-4 right-4 px-3 py-1 rounded-full text-sm font-medium ${getStatusColor(project.status)}`}>
                {getStatusText(project.status)}
              </div>
              <div className="absolute bottom-4 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm">
                {project.progress}% مكتمل
              </div>
            </div>
            
            <div className="p-6">
              <div className="flex items-start justify-between mb-4">
                <div>
                  <h3 className="text-xl font-bold text-gray-900 mb-2">{project.name}</h3>
                  <p className="text-gray-600 text-sm mb-1">{project.client}</p>
                  <p className="text-gray-500 text-xs flex items-center">
                    <i className="ri-map-pin-line ml-1 w-3 h-3 flex items-center justify-center"></i>
                    {project.location}
                  </p>
                </div>
                <div className="text-left">
                  <p className="text-2xl font-bold text-indigo-600">{formatCurrency(project.budget)}</p>
                  <p className="text-xs text-gray-500">الميزانية</p>
                </div>
              </div>

              {/* Progress Bar */}
              <div className="mb-4">
                <div className="flex justify-between items-center mb-2">
                  <span className="text-sm font-medium text-gray-700">التقدم</span>
                  <span className="text-sm font-bold text-gray-900">{project.progress}%</span>
                </div>
                <div className="w-full bg-gray-200 rounded-full h-3">
                  <div
                    className={`h-3 rounded-full transition-all duration-300 ${getProgressColor(project.progress)}`}
                    style={{ width: `${project.progress}%` }}
                  ></div>
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4 mb-4">
                <div>
                  <p className="text-xs text-gray-500 mb-1">تاريخ البداية</p>
                  <p className="text-sm font-medium text-gray-900">{new Date(project.startDate).toLocaleDateString('ar-SA')}</p>
                </div>
                <div>
                  <p className="text-xs text-gray-500 mb-1">تاريخ الانتهاء</p>
                  <p className="text-sm font-medium text-gray-900">{new Date(project.endDate).toLocaleDateString('ar-SA')}</p>
                </div>
              </div>

              <div className="flex items-center justify-between pt-4 border-t border-gray-100">
                <div className="flex items-center text-sm text-gray-600">
                  <i className="ri-user-line ml-2 w-4 h-4 flex items-center justify-center"></i>
                  <span>{project.manager}</span>
                  <span className="mx-2">•</span>
                  <span>{project.team} عضو</span>
                </div>
                <div className="flex space-x-2 space-x-reverse">
                  <button className="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors flex items-center justify-center">
                    <i className="ri-eye-line text-sm"></i>
                  </button>
                  <button className="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors flex items-center justify-center">
                    <i className="ri-edit-line text-sm"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>

      {filteredProjects.length === 0 && (
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-12">
          <div className="text-center">
            <i className="ri-building-line text-4xl text-gray-300 mb-4"></i>
            <p className="text-gray-500">لا توجد مشاريع في هذه الفئة</p>
          </div>
        </div>
      )}
    </div>
  );
}