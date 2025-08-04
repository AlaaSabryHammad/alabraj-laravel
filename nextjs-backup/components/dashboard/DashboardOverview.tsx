'use client';

import { useState, useEffect } from 'react';
import StatsCard from '../ui/StatsCard';
import Chart from '../ui/Chart';
import RecentActivity from '../ui/RecentActivity';
import NotificationPanel from '../ui/NotificationPanel';

export default function DashboardOverview() {
  const [currentTime, setCurrentTime] = useState(new Date());

  useEffect(() => {
    const timer = setInterval(() => setCurrentTime(new Date()), 1000);
    return () => clearInterval(timer);
  }, []);

  const statsData = [
    {
      title: 'إجمالي الموظفين',
      value: '156',
      change: '+12%',
      changeType: 'increase',
      icon: 'ri-group-fill',
      color: 'from-blue-500 to-blue-600',
      bgColor: 'from-blue-50 to-blue-100'
    },
    {
      title: 'المعدات المتاحة',
      value: '89',
      change: '+5%',
      changeType: 'increase',
      icon: 'ri-tools-fill',
      color: 'from-emerald-500 to-emerald-600',
      bgColor: 'from-emerald-50 to-emerald-100'
    },
    {
      title: 'المشاريع النشطة',
      value: '23',
      change: '+8%',
      changeType: 'increase',
      icon: 'ri-building-fill',
      color: 'from-orange-500 to-orange-600',
      bgColor: 'from-orange-50 to-orange-100'
    },
    {
      title: 'إيرادات الشهر',
      value: '2.4M ر.س',
      change: '+15%',
      changeType: 'increase',
      icon: 'ri-money-dollar-circle-fill',
      color: 'from-amber-500 to-amber-600',
      bgColor: 'from-amber-50 to-amber-100'
    },
    {
      title: 'رحلات اليوم',
      value: '47',
      change: '+3%',
      changeType: 'increase',
      icon: 'ri-truck-fill',
      color: 'from-teal-500 to-teal-600',
      bgColor: 'from-teal-50 to-teal-100'
    },
    {
      title: 'المستندات الجديدة',
      value: '128',
      change: '+22%',
      changeType: 'increase',
      icon: 'ri-folder-fill',
      color: 'from-purple-500 to-purple-600',
      bgColor: 'from-purple-50 to-purple-100'
    }
  ];

  const chartData = [
    { name: 'يناير', إيرادات: 2400000, مصروفات: 1800000 },
    { name: 'فبراير', إيرادات: 2600000, مصروفات: 1900000 },
    { name: 'مارس', إيرادات: 2800000, مصروفات: 2100000 },
    { name: 'أبريل', إيرادات: 3200000, مصروفات: 2300000 },
    { name: 'مايو', إيرادات: 2900000, مصروفات: 2000000 },
    { name: 'يونيو', إيرادات: 3400000, مصروفات: 2200000 }
  ];

  return (
    <div className="space-y-6 animate-fadeIn">
      {/* Header */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold text-gray-900 mb-2">مرحباً بك في لوحة التحكم</h1>
            <p className="text-gray-600">نظرة شاملة على أداء شركة الأبراج للمقاولات</p>
          </div>
          <div className="text-left" suppressHydrationWarning={true}>
            <div className="text-2xl font-bold text-blue-600">
              {currentTime.toLocaleTimeString('ar-SA')}
            </div>
            <div className="text-gray-500">
              {currentTime.toLocaleDateString('ar-SA', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
              })}
            </div>
          </div>
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {statsData.map((stat, index) => (
          <StatsCard key={index} {...stat} />
        ))}
      </div>

      {/* Charts and Activity */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2">
          <Chart data={chartData} title="الإيرادات والمصروفات الشهرية" />
        </div>
        <div className="space-y-6">
          <RecentActivity />
          <NotificationPanel />
        </div>
      </div>
    </div>
  );
}