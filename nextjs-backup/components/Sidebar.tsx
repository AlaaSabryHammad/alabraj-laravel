'use client';

import { useState } from 'react';

interface SidebarProps {
  activeSection: string;
  setActiveSection: (section: string) => void;
}

const menuItems = [
  {
    id: 'dashboard',
    title: 'لوحة التحكم الرئيسية',
    icon: 'ri-dashboard-line',
    color: 'from-blue-600 to-blue-700'
  },
  {
    id: 'employees',
    title: 'إدارة الموظفين',
    icon: 'ri-group-line',
    color: 'from-emerald-600 to-emerald-700'
  },
  {
    id: 'equipment',
    title: 'إدارة المعدات',
    icon: 'ri-tools-line',
    color: 'from-orange-600 to-orange-700'
  },
  {
    id: 'documents',
    title: 'إدارة المستندات',
    icon: 'ri-folder-line',
    color: 'from-purple-600 to-purple-700'
  },
  {
    id: 'transport',
    title: 'حركة النقليات',
    icon: 'ri-truck-line',
    color: 'from-teal-600 to-teal-700'
  },
  {
    id: 'finance',
    title: 'المالية والفواتير',
    icon: 'ri-money-dollar-circle-line',
    color: 'from-yellow-600 to-amber-700'
  },
  {
    id: 'projects',
    title: 'إدارة المشاريع',
    icon: 'ri-building-line',
    color: 'from-indigo-600 to-indigo-700'
  }
];

export default function Sidebar({ activeSection, setActiveSection }: SidebarProps) {
  const [isCollapsed, setIsCollapsed] = useState(false);

  return (
    <div className={`fixed right-0 top-0 h-full bg-white shadow-2xl transition-all duration-300 ease-in-out z-40 ${isCollapsed ? 'w-16' : 'w-64'}`}>
      {/* Header */}
      <div className="p-6 border-b border-gray-100">
        <div className="flex items-center justify-between">
          {!isCollapsed && (
            <div className="flex flex-col">
              <h1 className="text-xl font-bold bg-gradient-to-r from-blue-600 to-amber-600 bg-clip-text text-transparent font-['Pacifico']">
                شركة الأبراج
              </h1>
              <p className="text-sm text-gray-500 mt-1">للمقاولات المحدودة</p>
            </div>
          )}
          <button
            onClick={() => setIsCollapsed(!isCollapsed)}
            className="p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200"
          >
            <i className={`ri-menu-${isCollapsed ? 'unfold' : 'fold'}-line text-lg text-gray-600`}></i>
          </button>
        </div>
      </div>

      {/* Navigation Menu */}
      <nav className="p-4">
        <ul className="space-y-2">
          {menuItems.map((item) => (
            <li key={item.id}>
              <button
                onClick={() => setActiveSection(item.id)}
                className={`w-full flex items-center p-3 rounded-xl transition-all duration-300 ease-in-out group relative overflow-hidden ${
                  activeSection === item.id
                    ? `bg-gradient-to-r ${item.color} text-white shadow-lg transform scale-105`
                    : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600'
                }`}
              >
                <div className={`w-6 h-6 flex items-center justify-center ${isCollapsed ? 'mx-auto' : 'ml-3'}`}>
                  <i className={`${item.icon} text-lg`}></i>
                </div>
                {!isCollapsed && (
                  <span className="mr-3 font-medium whitespace-nowrap">{item.title}</span>
                )}
                {activeSection === item.id && (
                  <div className="absolute left-0 top-0 w-1 h-full bg-white rounded-r opacity-80"></div>
                )}
              </button>
            </li>
          ))}
        </ul>
      </nav>

      {/* Footer */}
      {!isCollapsed && (
        <div className="absolute bottom-4 right-4 left-4">
          <div className="bg-gradient-to-r from-blue-50 to-amber-50 p-4 rounded-xl border border-blue-100">
            <div className="flex items-center">
              <div className="w-10 h-10 bg-gradient-to-r from-blue-500 to-amber-500 rounded-full flex items-center justify-center">
                <i className="ri-user-line text-white"></i>
              </div>
              <div className="mr-3">
                <p className="text-sm font-medium text-gray-800">مدير النظام</p>
                <p className="text-xs text-gray-500">متصل الآن</p>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}