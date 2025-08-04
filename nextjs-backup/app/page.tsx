'use client';

import { useState } from 'react';
import Sidebar from '../components/Sidebar';
import DashboardOverview from '../components/dashboard/DashboardOverview';
import EmployeeManagement from '../components/employees/EmployeeManagement';
import EquipmentManagement from '../components/equipment/EquipmentManagement';
import DocumentManagement from '../components/documents/DocumentManagement';
import TransportManagement from '../components/transport/TransportManagement';
import FinanceManagement from '../components/finance/FinanceManagement';
import ProjectManagement from '../components/projects/ProjectManagement';

export default function Dashboard() {
  const [activeSection, setActiveSection] = useState('dashboard');

  const renderActiveSection = () => {
    switch (activeSection) {
      case 'dashboard':
        return <DashboardOverview />;
      case 'employees':
        return <EmployeeManagement />;
      case 'equipment':
        return <EquipmentManagement />;
      case 'documents':
        return <DocumentManagement />;
      case 'transport':
        return <TransportManagement />;
      case 'finance':
        return <FinanceManagement />;
      case 'projects':
        return <ProjectManagement />;
      default:
        return <DashboardOverview />;
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 rtl flex" dir="rtl">
      <Sidebar activeSection={activeSection} setActiveSection={setActiveSection} />
      <div className="flex-1 mr-64 p-6">
        <div className="max-w-7xl mx-auto">
          {renderActiveSection()}
        </div>
      </div>
    </div>
  );
}