'use client';

interface StatsCardProps {
  title: string;
  value: string;
  change: string;
  changeType: 'increase' | 'decrease';
  icon: string;
  color: string;
  bgColor: string;
}

export default function StatsCard({
  title,
  value,
  change,
  changeType,
  icon,
  color,
  bgColor
}: StatsCardProps) {
  return (
    <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
      <div className="flex items-center justify-between">
        <div className="flex-1">
          <p className="text-gray-600 text-sm font-medium mb-1">{title}</p>
          <p className="text-3xl font-bold text-gray-900 mb-2">{value}</p>
          <div className="flex items-center">
            <span className={`text-sm font-medium flex items-center ${
              changeType === 'increase' ? 'text-emerald-600' : 'text-red-500'
            }`}>
              <i className={`${changeType === 'increase' ? 'ri-arrow-up-line' : 'ri-arrow-down-line'} ml-1`}></i>
              {change}
            </span>
            <span className="text-gray-500 text-sm mr-2">من الشهر السابق</span>
          </div>
        </div>
        <div className={`w-16 h-16 rounded-2xl bg-gradient-to-br ${bgColor} flex items-center justify-center`}>
          <i className={`${icon} text-2xl bg-gradient-to-r ${color} bg-clip-text text-transparent`}></i>
        </div>
      </div>
    </div>
  );
}