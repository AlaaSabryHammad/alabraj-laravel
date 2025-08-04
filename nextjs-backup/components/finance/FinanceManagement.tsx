'use client';

import { useState } from 'react';
import { AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, PieChart, Pie, Cell } from 'recharts';

export default function FinanceManagement() {
  const [activeTab, setActiveTab] = useState('overview');

  const monthlyData = [
    { month: 'يناير', revenue: 2400000, expenses: 1800000, profit: 600000 },
    { month: 'فبراير', revenue: 2600000, expenses: 1900000, profit: 700000 },
    { month: 'مارس', revenue: 2800000, expenses: 2100000, profit: 700000 },
    { month: 'أبريل', revenue: 3200000, expenses: 2300000, profit: 900000 },
    { month: 'مايو', revenue: 2900000, expenses: 2000000, profit: 900000 },
    { month: 'يونيو', revenue: 3400000, expenses: 2200000, profit: 1200000 }
  ];

  const expenseBreakdown = [
    { name: 'الرواتب', value: 1200000, color: '#3B82F6' },
    { name: 'المواد', value: 800000, color: '#10B981' },
    { name: 'المعدات', value: 600000, color: '#F59E0B' },
    { name: 'النقل', value: 400000, color: '#EF4444' },
    { name: 'أخرى', value: 300000, color: '#8B5CF6' }
  ];

  const recentInvoices = [
    {
      id: 'INV-2024-001',
      client: 'شركة الخليج للتطوير',
      amount: 450000,
      status: 'paid',
      date: '2024-01-25',
      project: 'برج الرياض التجاري'
    },
    {
      id: 'INV-2024-002',
      client: 'مؤسسة البناء الحديث',
      amount: 320000,
      status: 'pending',
      date: '2024-01-22',
      project: 'فيلا الخبر السكنية'
    },
    {
      id: 'INV-2024-003',
      client: 'شركة التعمير الشامل',
      amount: 780000,
      status: 'overdue',
      date: '2024-01-18',
      project: 'مجمع جدة التجاري'
    },
    {
      id: 'INV-2024-004',
      client: 'مجموعة الإنشاء المتقدم',
      amount: 650000,
      status: 'paid',
      date: '2024-01-20',
      project: 'برج الدمام السكني'
    }
  ];

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'paid':
        return 'bg-emerald-100 text-emerald-800';
      case 'pending':
        return 'bg-amber-100 text-amber-800';
      case 'overdue':
        return 'bg-red-100 text-red-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'paid':
        return 'مدفوع';
      case 'pending':
        return 'في الانتظار';
      case 'overdue':
        return 'متأخر';
      default:
        return 'غير محدد';
    }
  };

  const formatCurrency = (amount: number) => {
    return `${(amount / 1000).toFixed(0)}K ر.س`;
  };

  const totalRevenue = monthlyData.reduce((sum, item) => sum + item.revenue, 0);
  const totalExpenses = monthlyData.reduce((sum, item) => sum + item.expenses, 0);
  const totalProfit = totalRevenue - totalExpenses;
  const paidInvoices = recentInvoices.filter(inv => inv.status === 'paid').reduce((sum, inv) => sum + inv.amount, 0);

  const tabs = [
    { id: 'overview', label: 'نظرة عامة', icon: 'ri-dashboard-line' },
    { id: 'invoices', label: 'الفواتير', icon: 'ri-bill-line' },
    { id: 'reports', label: 'التقارير المالية', icon: 'ri-file-chart-line' }
  ];

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold text-gray-900 mb-2">المالية والفواتير</h1>
            <p className="text-gray-600">إدارة شاملة للإيرادات والمصروفات والفواتير</p>
          </div>
          <button className="bg-gradient-to-r from-amber-600 to-amber-700 text-white px-6 py-3 rounded-xl font-medium hover:from-amber-700 hover:to-amber-800 transition-all duration-200 flex items-center whitespace-nowrap">
            <i className="ri-add-line ml-2"></i>
            إصدار فاتورة جديدة
          </button>
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">إجمالي الإيرادات</p>
              <p className="text-3xl font-bold text-emerald-600">{formatCurrency(totalRevenue)}</p>
              <p className="text-xs text-emerald-600 font-medium">+15% من الشهر السابق</p>
            </div>
            <div className="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
              <i className="ri-arrow-up-line text-xl text-emerald-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">إجمالي المصروفات</p>
              <p className="text-3xl font-bold text-red-600">{formatCurrency(totalExpenses)}</p>
              <p className="text-xs text-red-600 font-medium">+8% من الشهر السابق</p>
            </div>
            <div className="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
              <i className="ri-arrow-down-line text-xl text-red-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">صافي الربح</p>
              <p className="text-3xl font-bold text-blue-600">{formatCurrency(totalProfit)}</p>
              <p className="text-xs text-blue-600 font-medium">+22% من الشهر السابق</p>
            </div>
            <div className="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
              <i className="ri-money-dollar-circle-line text-xl text-blue-600"></i>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-gray-600 text-sm font-medium mb-1">الفواتير المدفوعة</p>
              <p className="text-3xl font-bold text-amber-600">{formatCurrency(paidInvoices)}</p>
              <p className="text-xs text-amber-600 font-medium">{recentInvoices.filter(inv => inv.status === 'paid').length} فاتورة</p>
            </div>
            <div className="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
              <i className="ri-bill-line text-xl text-amber-600"></i>
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
                  ? 'bg-gradient-to-r from-amber-600 to-amber-700 text-white shadow-lg'
                  : 'text-gray-600 hover:text-amber-600 hover:bg-gray-50'
              }`}
            >
              <i className={`${tab.icon} ml-2`}></i>
              {tab.label}
            </button>
          ))}
        </div>
      </div>

      {/* Overview Tab */}
      {activeTab === 'overview' && (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Revenue Chart */}
          <div className="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 className="text-xl font-bold text-gray-900 mb-6">الإيرادات والمصروفات الشهرية</h3>
            <div className="h-80">
              <ResponsiveContainer width="100%" height="100%">
                <AreaChart data={monthlyData}>
                  <defs>
                    <linearGradient id="colorRevenue" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="#10B981" stopOpacity={0.3}/>
                      <stop offset="95%" stopColor="#10B981" stopOpacity={0}/>
                    </linearGradient>
                    <linearGradient id="colorExpenses" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="#EF4444" stopOpacity={0.3}/>
                      <stop offset="95%" stopColor="#EF4444" stopOpacity={0}/>
                    </linearGradient>
                  </defs>
                  <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
                  <XAxis dataKey="month" tick={{ fontSize: 12, fill: '#6B7280' }} />
                  <YAxis tick={{ fontSize: 12, fill: '#6B7280' }} tickFormatter={formatCurrency} />
                  <Tooltip 
                    contentStyle={{
                      backgroundColor: 'white',
                      border: 'none',
                      borderRadius: '12px',
                      boxShadow: '0 10px 25px -5px rgba(0, 0, 0, 0.1)',
                      direction: 'rtl'
                    }}
                    formatter={(value: number, name: string) => [formatCurrency(value), name]}
                  />
                  <Area type="monotone" dataKey="revenue" stroke="#10B981" strokeWidth={3} fillOpacity={1} fill="url(#colorRevenue)" name="الإيرادات" />
                  <Area type="monotone" dataKey="expenses" stroke="#EF4444" strokeWidth={3} fillOpacity={1} fill="url(#colorExpenses)" name="المصروفات" />
                </AreaChart>
              </ResponsiveContainer>
            </div>
          </div>

          {/* Expense Breakdown */}
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 className="text-xl font-bold text-gray-900 mb-6">تفصيل المصروفات</h3>
            <div className="h-64 mb-6">
              <ResponsiveContainer width="100%" height="100%">
                <PieChart>
                  <Pie
                    data={expenseBreakdown}
                    cx="50%"
                    cy="50%"
                    innerRadius={60}
                    outerRadius={100}
                    dataKey="value"
                  >
                    {expenseBreakdown.map((entry, index) => (
                      <Cell key={`cell-${index}`} fill={entry.color} />
                    ))}
                  </Pie>
                  <Tooltip formatter={(value: number) => formatCurrency(value)} />
                </PieChart>
              </ResponsiveContainer>
            </div>
            <div className="space-y-3">
              {expenseBreakdown.map((item, index) => (
                <div key={index} className="flex items-center justify-between">
                  <div className="flex items-center">
                    <div className={`w-3 h-3 rounded-full ml-2`} style={{ backgroundColor: item.color }}></div>
                    <span className="text-sm text-gray-700">{item.name}</span>
                  </div>
                  <span className="text-sm font-medium text-gray-900">{formatCurrency(item.value)}</span>
                </div>
              ))}
            </div>
          </div>
        </div>
      )}

      {/* Invoices Tab */}
      {activeTab === 'invoices' && (
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div className="flex items-center justify-between mb-6">
            <h3 className="text-xl font-bold text-gray-900">الفواتير الحديثة</h3>
            <div className="flex space-x-2 space-x-reverse">
              <button className="px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors text-sm">
                تصدير
              </button>
              <button className="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm">
                فلترة
              </button>
            </div>
          </div>

          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr className="border-b border-gray-100">
                  <th className="text-right py-4 px-4 text-sm font-medium text-gray-700">رقم الفاتورة</th>
                  <th className="text-right py-4 px-4 text-sm font-medium text-gray-700">العميل</th>
                  <th className="text-right py-4 px-4 text-sm font-medium text-gray-700">المشروع</th>
                  <th className="text-right py-4 px-4 text-sm font-medium text-gray-700">المبلغ</th>
                  <th className="text-right py-4 px-4 text-sm font-medium text-gray-700">التاريخ</th>
                  <th className="text-right py-4 px-4 text-sm font-medium text-gray-700">الحالة</th>
                  <th className="text-right py-4 px-4 text-sm font-medium text-gray-700">الإجراءات</th>
                </tr>
              </thead>
              <tbody>
                {recentInvoices.map((invoice) => (
                  <tr key={invoice.id} className="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <td className="py-4 px-4">
                      <span className="font-mono text-sm font-medium text-gray-900">{invoice.id}</span>
                    </td>
                    <td className="py-4 px-4">
                      <span className="text-sm text-gray-900">{invoice.client}</span>
                    </td>
                    <td className="py-4 px-4">
                      <span className="text-sm text-gray-700">{invoice.project}</span>
                    </td>
                    <td className="py-4 px-4">
                      <span className="text-sm font-medium text-gray-900">{invoice.amount.toLocaleString('ar-SA')} ر.س</span>
                    </td>
                    <td className="py-4 px-4">
                      <span className="text-sm text-gray-700">{new Date(invoice.date).toLocaleDateString('ar-SA')}</span>
                    </td>
                    <td className="py-4 px-4">
                      <span className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${getStatusColor(invoice.status)}`}>
                        {getStatusText(invoice.status)}
                      </span>
                    </td>
                    <td className="py-4 px-4">
                      <div className="flex space-x-2 space-x-reverse">
                        <button className="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors flex items-center justify-center">
                          <i className="ri-eye-line text-sm"></i>
                        </button>
                        <button className="w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors flex items-center justify-center">
                          <i className="ri-download-line text-sm"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* Reports Tab */}
      {activeTab === 'reports' && (
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <h3 className="text-xl font-bold text-gray-900 mb-6">التقارير المالية</h3>
          <div className="text-center py-12">
            <i className="ri-file-chart-line text-4xl text-gray-300 mb-4"></i>
            <p className="text-gray-500">سيتم عرض التقارير المالية المفصلة هنا</p>
          </div>
        </div>
      )}
    </div>
  );
}