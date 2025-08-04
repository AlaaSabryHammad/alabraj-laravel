'use client';

export default function RecentActivity() {
  const activities = [
    {
      id: 1,
      type: 'employee',
      message: 'تم إضافة موظف جديد: أحمد محمد',
      time: 'منذ 10 دقائق',
      icon: 'ri-user-add-line',
      color: 'text-blue-500',
      bgColor: 'bg-blue-50'
    },
    {
      id: 2,
      type: 'project',
      message: 'تم تحديث حالة مشروع برج الرياض',
      time: 'منذ 25 دقيقة',
      icon: 'ri-building-line',
      color: 'text-emerald-500',
      bgColor: 'bg-emerald-50'
    },
    {
      id: 3,
      type: 'transport',
      message: 'انطلقت شاحنة رقم 15 إلى موقع المشروع',
      time: 'منذ 45 دقيقة',
      icon: 'ri-truck-line',
      color: 'text-orange-500',
      bgColor: 'bg-orange-50'
    },
    {
      id: 4,
      type: 'finance',
      message: 'تم إصدار فاتورة جديدة بقيمة 125,000 ر.س',
      time: 'منذ ساعة واحدة',
      icon: 'ri-money-dollar-circle-line',
      color: 'text-amber-500',
      bgColor: 'bg-amber-50'
    },
    {
      id: 5,
      type: 'equipment',
      message: 'تم بدء صيانة الحفارة CAT 320',
      time: 'منذ ساعتين',
      icon: 'ri-tools-line',
      color: 'text-purple-500',
      bgColor: 'bg-purple-50'
    }
  ];

  return (
    <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
      <h3 className="text-xl font-bold text-gray-900 mb-6">النشاطات الأخيرة</h3>
      <div className="space-y-4">
        {activities.map((activity, index) => (
          <div key={activity.id} className="flex items-center p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200">
            <div className={`w-10 h-10 rounded-full ${activity.bgColor} flex items-center justify-center`}>
              <i className={`${activity.icon} ${activity.color}`}></i>
            </div>
            <div className="mr-3 flex-1">
              <p className="text-sm font-medium text-gray-900 mb-1">{activity.message}</p>
              <p className="text-xs text-gray-500">{activity.time}</p>
            </div>
            <div className="flex items-center">
              <div className={`w-2 h-2 rounded-full ${activity.color.replace('text-', 'bg-')} opacity-60`}></div>
            </div>
          </div>
        ))}
      </div>
      <button className="w-full mt-4 py-2 text-blue-600 font-medium hover:bg-blue-50 rounded-lg transition-colors duration-200">
        عرض جميع النشاطات
      </button>
    </div>
  );
}