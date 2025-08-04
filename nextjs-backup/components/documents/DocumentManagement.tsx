'use client';

import { useState } from 'react';

export default function DocumentManagement() {
  const [activeCategory, setActiveCategory] = useState('all');
  const [showUpload, setShowUpload] = useState(false);

  const documents = [
    {
      id: 1,
      name: 'عقد مشروع برج الرياض',
      type: 'contracts',
      size: '2.4 MB',
      uploadDate: '2024-01-15',
      category: 'عقود',
      uploader: 'أحمد محمد',
      status: 'active'
    },
    {
      id: 2,
      name: 'فاتورة مواد البناء - يناير 2024',
      type: 'invoices',
      size: '1.8 MB',
      uploadDate: '2024-01-20',
      category: 'فواتير',
      uploader: 'فاطمة أحمد',
      status: 'active'
    },
    {
      id: 3,
      name: 'ترخيص البناء - مشروع الخبر',
      type: 'licenses',
      size: '3.2 MB',
      uploadDate: '2024-01-18',
      category: 'تراخيص',
      uploader: 'خالد عبدالله',
      status: 'active'
    },
    {
      id: 4,
      name: 'تقرير التقدم الشهري - يناير',
      type: 'reports',
      size: '4.1 MB',
      uploadDate: '2024-01-25',
      category: 'تقارير',
      uploader: 'نورا سالم',
      status: 'active'
    },
    {
      id: 5,
      name: 'شهادة السلامة المهنية',
      type: 'licenses',
      size: '1.2 MB',
      uploadDate: '2024-01-22',
      category: 'تراخيص',
      uploader: 'عبدالرحمن محمد',
      status: 'active'
    }
  ];

  const categories = [
    { id: 'all', name: 'جميع المستندات', icon: 'ri-folder-line', count: documents.length },
    { id: 'contracts', name: 'العقود', icon: 'ri-file-text-line', count: documents.filter(d => d.type === 'contracts').length },
    { id: 'invoices', name: 'الفواتير', icon: 'ri-bill-line', count: documents.filter(d => d.type === 'invoices').length },
    { id: 'licenses', name: 'التراخيص', icon: 'ri-award-line', count: documents.filter(d => d.type === 'licenses').length },
    { id: 'reports', name: 'التقارير', icon: 'ri-file-chart-line', count: documents.filter(d => d.type === 'reports').length }
  ];

  const getFileIcon = (type: string) => {
    switch (type) {
      case 'contracts':
        return 'ri-file-text-line';
      case 'invoices':
        return 'ri-bill-line';
      case 'licenses':
        return 'ri-award-line';
      case 'reports':
        return 'ri-file-chart-line';
      default:
        return 'ri-file-line';
    }
  };

  const getFileColor = (type: string) => {
    switch (type) {
      case 'contracts':
        return 'text-blue-600 bg-blue-50';
      case 'invoices':
        return 'text-emerald-600 bg-emerald-50';
      case 'licenses':
        return 'text-amber-600 bg-amber-50';
      case 'reports':
        return 'text-purple-600 bg-purple-50';
      default:
        return 'text-gray-600 bg-gray-50';
    }
  };

  const filteredDocuments = activeCategory === 'all' 
    ? documents 
    : documents.filter(doc => doc.type === activeCategory);

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold text-gray-900 mb-2">إدارة المستندات</h1>
            <p className="text-gray-600">تنظيم وإدارة جميع وثائق ومستندات الشركة</p>
          </div>
          <button
            onClick={() => setShowUpload(true)}
            className="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-xl font-medium hover:from-purple-700 hover:to-purple-800 transition-all duration-200 flex items-center whitespace-nowrap"
          >
            <i className="ri-upload-line ml-2"></i>
            رفع مستند جديد
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {/* Categories Sidebar */}
        <div className="lg:col-span-1">
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 className="text-lg font-bold text-gray-900 mb-4">فئات المستندات</h3>
            <div className="space-y-2">
              {categories.map((category) => (
                <button
                  key={category.id}
                  onClick={() => setActiveCategory(category.id)}
                  className={`w-full flex items-center justify-between p-3 rounded-xl transition-all duration-200 ${
                    activeCategory === category.id
                      ? 'bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-lg'
                      : 'text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  <div className="flex items-center">
                    <i className={`${category.icon} ml-3`}></i>
                    <span className="font-medium">{category.name}</span>
                  </div>
                  <span className={`text-xs font-bold px-2 py-1 rounded-full ${
                    activeCategory === category.id
                      ? 'bg-white/20 text-white'
                      : 'bg-gray-100 text-gray-600'
                  }`}>
                    {category.count}
                  </span>
                </button>
              ))}
            </div>
          </div>
        </div>

        {/* Documents List */}
        <div className="lg:col-span-3">
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div className="flex items-center justify-between mb-6">
              <h3 className="text-xl font-bold text-gray-900">
                {categories.find(c => c.id === activeCategory)?.name}
              </h3>
              <div className="flex items-center space-x-2 space-x-reverse">
                <button className="p-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                  <i className="ri-search-line text-gray-600"></i>
                </button>
                <button className="p-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                  <i className="ri-filter-line text-gray-600"></i>
                </button>
              </div>
            </div>

            <div className="space-y-4">
              {filteredDocuments.map((doc) => (
                <div key={doc.id} className="border border-gray-100 rounded-xl p-4 hover:shadow-md transition-all duration-200">
                  <div className="flex items-center">
                    <div className={`w-12 h-12 rounded-xl flex items-center justify-center ml-4 ${getFileColor(doc.type)}`}>
                      <i className={`${getFileIcon(doc.type)} text-xl`}></i>
                    </div>
                    
                    <div className="flex-1">
                      <h4 className="font-semibold text-gray-900 mb-1">{doc.name}</h4>
                      <div className="flex items-center space-x-4 space-x-reverse text-sm text-gray-600">
                        <span>{doc.size}</span>
                        <span>•</span>
                        <span>{doc.uploader}</span>
                        <span>•</span>
                        <span>{new Date(doc.uploadDate).toLocaleDateString('ar-SA')}</span>
                      </div>
                    </div>
                    
                    <div className="flex items-center space-x-2 space-x-reverse">
                      <button className="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors flex items-center justify-center">
                        <i className="ri-download-line text-sm"></i>
                      </button>
                      <button className="w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors flex items-center justify-center">
                        <i className="ri-share-line text-sm"></i>
                      </button>
                      <button className="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors flex items-center justify-center">
                        <i className="ri-delete-bin-line text-sm"></i>
                      </button>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            {filteredDocuments.length === 0 && (
              <div className="text-center py-12">
                <i className="ri-folder-open-line text-4xl text-gray-300 mb-4"></i>
                <p className="text-gray-500">لا توجد مستندات في هذه الفئة</p>
              </div>
            )}
          </div>
        </div>
      </div>

      {/* Upload Modal */}
      {showUpload && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-2xl shadow-2xl max-w-2xl w-full">
            <div className="p-6 border-b border-gray-100">
              <div className="flex items-center justify-between">
                <h2 className="text-2xl font-bold text-gray-900">رفع مستند جديد</h2>
                <button
                  onClick={() => setShowUpload(false)}
                  className="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors"
                >
                  <i className="ri-close-line text-gray-500"></i>
                </button>
              </div>
            </div>
            
            <div className="p-6">
              <div className="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-purple-300 transition-colors">
                <i className="ri-upload-cloud-line text-4xl text-gray-400 mb-4"></i>
                <p className="text-gray-600 mb-2">اسحب الملفات هنا أو اضغط للاختيار</p>
                <p className="text-sm text-gray-500">يدعم: PDF, DOC, DOCX, XLS, XLSX</p>
                <button className="mt-4 px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                  اختيار الملفات
                </button>
              </div>
              
              <div className="mt-6 space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">فئة المستند</label>
                  <select className="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-100 outline-none transition-all appearance-none pr-8">
                    <option value="">اختر الفئة</option>
                    <option value="contracts">عقود</option>
                    <option value="invoices">فواتير</option>
                    <option value="licenses">تراخيص</option>
                    <option value="reports">تقارير</option>
                  </select>
                </div>
                
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">وصف المستند</label>
                  <textarea
                    className="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-100 outline-none transition-all resize-none"
                    rows={3}
                    placeholder="أدخل وصف للمستند..."
                  ></textarea>
                </div>
              </div>
              
              <div className="flex justify-end space-x-4 space-x-reverse pt-6 border-t border-gray-100 mt-6">
                <button
                  onClick={() => setShowUpload(false)}
                  className="px-6 py-3 rounded-xl border border-gray-200 text-gray-700 font-medium hover:bg-gray-50 transition-colors whitespace-nowrap"
                >
                  إلغاء
                </button>
                <button className="px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium hover:from-purple-700 hover:to-purple-800 transition-all whitespace-nowrap">
                  رفع المستند
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}