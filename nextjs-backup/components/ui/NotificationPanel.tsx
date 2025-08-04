'use client';

export default function NotificationPanel() {
  const notifications = [
    {
      id: 1,
      title: 'تنبيه صيانة',
      message: 'موعد صيانة الخلاطة رقم 7 اليوم',
      type: 'warning',
      time: 'منذ 15 دقيقة',
      icon: 'ri-alert-line',
      color: 'text-amber-600',
      bgColor: 'bg-amber-50',
      borderColor: 'border-amber-200'
    },
    {
      id: 2,
      title: 'مشروع جديد',
      message: 'تم الموافقة على مشروع فيلا الخبر',
      type: 'success',
      time: 'منذ ساعة واحدة',
      icon: 'ri-check-line',
      color: 'text-emerald-600',
      bgColor: 'bg-emerald-50',
      borderColor: 'border-emerald-200'
    },
    {
      id: 3,
      title: 'انتهاء عقد',
      message: 'عقد المورد أحمد علي ينتهي خلال أسبوع',
      type: 'error',
      time: 'منذ 3 ساعات',
      icon: 'ri-error-warning-line',
      color: 'text-red-600',
      bgColor: 'bg-red-50',
      borderColor: 'border-red-200'
    }
  ];

  return (
    <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
      <div className="flex items-center justify-between mb-6">
        <h3 className="text-xl font-bold text-gray-900">الإشعارات والتنبيهات</h3>
        <span className="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
          {notifications.length}
        </span>
      </div>
      <div className="space-y-4">
        {notifications.map((notification) => (
          <div key={notification.id} className={`p-4 rounded-xl border ${notification.borderColor} ${notification.bgColor} transition-all duration-200 hover:shadow-sm`}>
            <div className="flex items-start">
              <div className={`w-8 h-8 rounded-full bg-white flex items-center justify-center ${notification.color} flex-shrink-0`}>
                <i className={`${notification.icon} text-sm`}></i>
              </div>
              <div className="mr-3 flex-1">
                <h4 className="text-sm font-semibold text-gray-900 mb-1">{notification.title}</h4>
                <p className="text-xs text-gray-600 mb-2">{notification.message}</p>
                <p className="text-xs text-gray-500">{notification.time}</p>
              </div>
              <button className="text-gray-400 hover:text-gray-600 transition-colors">
                <i className="ri-close-line text-sm"></i>
              </button>
            </div>
          </div>
        ))}
      </div>
      <button className="w-full mt-4 py-2 text-blue-600 font-medium hover:bg-blue-50 rounded-lg transition-colors duration-200">
        عرض جميع الإشعارات
      </button>
    </div>
  );
}