import React from 'react';
import { RAW_MATERIALS } from '../../mockData';
import { AlertCircle, CheckCircle2 } from 'lucide-react';

export function AdminInventory() {
  return (
    <div className="p-6 md:p-8 max-w-7xl mx-auto space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Inventory Management</h1>
        <p className="text-gray-500">Monitor raw materials and stock levels</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-emerald-500">
          <p className="text-sm text-gray-500 font-medium">Total Items</p>
          <p className="text-3xl font-bold text-gray-900 mt-2">{RAW_MATERIALS.length}</p>
        </div>
        <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-amber-500">
          <p className="text-sm text-gray-500 font-medium">Low Stock Alerts</p>
          <p className="text-3xl font-bold text-gray-900 mt-2">{RAW_MATERIALS.filter(r => r.status === 'Low').length}</p>
        </div>
        <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-red-500">
          <p className="text-sm text-gray-500 font-medium">Critical Stock</p>
          <p className="text-3xl font-bold text-gray-900 mt-2">{RAW_MATERIALS.filter(r => r.status === 'Critical').length}</p>
        </div>
      </div>

      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="bg-gray-50 border-b border-gray-100">
              <th className="px-6 py-4 text-sm font-semibold text-gray-600">Material ID</th>
              <th className="px-6 py-4 text-sm font-semibold text-gray-600">Name</th>
              <th className="px-6 py-4 text-sm font-semibold text-gray-600">Current Stock</th>
              <th className="px-6 py-4 text-sm font-semibold text-gray-600">Status</th>
              <th className="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Action</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-100">
            {RAW_MATERIALS.map(material => (
              <tr key={material.id} className="hover:bg-gray-50/50 transition-colors">
                <td className="px-6 py-4 text-gray-500 font-mono text-sm">{material.id}</td>
                <td className="px-6 py-4 font-medium text-gray-900">{material.name}</td>
                <td className="px-6 py-4 text-gray-900">{material.stock}</td>
                <td className="px-6 py-4">
                  <div className="flex items-center gap-1.5">
                    {material.status === 'Good' && <CheckCircle2 size={16} className="text-emerald-500" />}
                    {material.status === 'Low' && <AlertCircle size={16} className="text-amber-500" />}
                    {material.status === 'Critical' && <AlertCircle size={16} className="text-red-500" />}
                    <span className={`text-sm font-medium ${
                      material.status === 'Good' ? 'text-emerald-700' :
                      material.status === 'Low' ? 'text-amber-700' : 'text-red-700'
                    }`}>
                      {material.status}
                    </span>
                  </div>
                </td>
                <td className="px-6 py-4 text-right">
                  <button className="text-emerald-600 hover:text-emerald-800 text-sm font-medium">Update Stock</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
