import React from 'react';
import { EMPLOYEES } from '../../mockData';
import { Mail, Phone } from 'lucide-react';

export function AdminEmployees() {
  return (
    <div className="p-6 md:p-8 max-w-7xl mx-auto space-y-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Employee Management</h1>
          <p className="text-gray-500">Manage your team and shifts</p>
        </div>
        <button className="bg-emerald-700 hover:bg-emerald-800 text-white px-5 py-2.5 rounded-xl font-medium transition-colors">
          Add Employee
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {EMPLOYEES.map(emp => (
          <div key={emp.id} className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center">
            <img src={emp.image} alt={emp.name} className="w-24 h-24 rounded-full object-cover mb-4 ring-4 ring-emerald-50" />
            <h3 className="text-xl font-bold text-gray-900">{emp.name}</h3>
            <p className="text-emerald-600 font-medium text-sm mb-4">{emp.role}</p>
            
            <div className="w-full space-y-3 mb-6">
              <div className="flex justify-between items-center py-2 border-b border-gray-50">
                <span className="text-gray-500 text-sm">Shift</span>
                <span className="text-gray-900 font-medium text-sm">{emp.shift}</span>
              </div>
              <div className="flex justify-between items-center py-2 border-b border-gray-50">
                <span className="text-gray-500 text-sm">Employee ID</span>
                <span className="text-gray-900 font-mono text-sm">{emp.id}</span>
              </div>
            </div>

            <div className="flex gap-2 w-full">
              <button className="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-700 py-2 rounded-xl flex justify-center items-center gap-2 transition-colors">
                <Mail size={16} />
                <span className="text-sm font-medium">Message</span>
              </button>
              <button className="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-700 py-2 rounded-xl flex justify-center items-center gap-2 transition-colors">
                <Phone size={16} />
                <span className="text-sm font-medium">Call</span>
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
