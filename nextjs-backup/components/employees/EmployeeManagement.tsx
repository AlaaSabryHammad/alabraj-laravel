'use client';

import { useState } from 'react';
import EmployeeList from './EmployeeList';
import EmployeeForm from './EmployeeForm';
import AttendanceTracker from './AttendanceTracker';

export default function EmployeeManagement() {
  const [activeTab, setActiveTab] = useState('list');
  const [showAddForm, setShowAddForm] = useState(false);

  const tabs = [
    { id: 'list', label: 'قائمة الموظفين', icon: 'ri-list-check' },
    { id: 'attendance', label: 'متابعة الحضور', icon: 'ri-time-line' }
  ];

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold text-gray-900 mb-2">إدارة الموظفين</h1>
            <p className="text-gray-600">إدارة شاملة لبيانات الموظفين والحضور والانصراف</p>
          </div>
          <button
            onClick={() => setShowAddForm(true)}
            className="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center whitespace-nowrap"
          >
            <i className="ri-user-add-line ml-2"></i>
            إضافة موظف جديد
          </button>
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
                  ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg'
                  : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50'
              }`}
            >
              <i className={`${tab.icon} ml-2`}></i>
              {tab.label}
            </button>
          ))}
        </div>
      </div>

      {/* Content */}
      <div className="min-h-96">
        {activeTab === 'list' && <EmployeeList />}
        {activeTab === 'attendance' && <AttendanceTracker />}
      </div>

      {/* Add Employee Modal */}
      {showAddForm && (
        <EmployeeForm onClose={() => setShowAddForm(false)} />
      )}
    </div>
  );
}