import React from 'react';
import { RAW_MATERIALS } from '../../mockData';
import { AlertTriangle } from 'lucide-react';

export function StaffStock() {
  return (
    <div className="p-4 space-y-4">
      <div className="mb-2">
        <h2 className="text-xl font-bold text-gray-900">Quick Stock Check</h2>
        <p className="text-sm text-gray-500">Current material availability</p>
      </div>

      <div className="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex gap-3 items-start">
        <AlertTriangle className="text-amber-600 shrink-0" size={20} />
        <div>
          <h4 className="text-amber-800 font-semibold text-sm">Low Stock Alert</h4>
          <p className="text-amber-700 text-xs mt-1">Sugar Syrup is critically low. Please inform the manager.</p>
        </div>
      </div>

      <div className="space-y-3">
        {RAW_MATERIALS.map(material => (
          <div key={material.id} className="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex justify-between items-center">
            <div>
              <h3 className="font-semibold text-gray-900">{material.name}</h3>
              <p className="text-sm text-gray-500 mt-0.5">ID: {material.id}</p>
            </div>
            <div className="text-right">
              <div className="font-bold text-lg text-gray-900">{material.stock}</div>
              <span className={`text-[10px] uppercase tracking-wider font-bold px-2 py-0.5 rounded-full ${
                material.status === 'Good' ? 'bg-emerald-100 text-emerald-700' :
                material.status === 'Low' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700'
              }`}>
                {material.status}
              </span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
