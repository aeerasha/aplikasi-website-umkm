import React from 'react';
import { ORDERS } from '../../mockData';
import { Check, Clock } from 'lucide-react';

export function StaffOrders() {
  return (
    <div className="p-4 space-y-4">
      <div className="flex justify-between items-end mb-2">
        <div>
          <h2 className="text-xl font-bold text-gray-900">Active Orders</h2>
          <p className="text-sm text-gray-500">Real-time order feed</p>
        </div>
        <div className="bg-emerald-100 text-emerald-800 text-xs font-bold px-2 py-1 rounded-full animate-pulse">
          Live
        </div>
      </div>

      <div className="space-y-4">
        {ORDERS.map(order => (
          <div key={order.id} className="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div className="flex justify-between items-start mb-3">
              <div>
                <span className="text-emerald-700 font-bold">{order.id}</span>
                <h3 className="font-semibold text-gray-900 mt-0.5">{order.customer}</h3>
              </div>
              <div className="flex items-center text-gray-500 text-sm">
                <Clock size={14} className="mr-1" />
                {order.time}
              </div>
            </div>

            <div className="bg-gray-50 rounded-xl p-3 mb-4 space-y-1">
              {order.items.map((item, idx) => (
                <div key={idx} className="text-gray-700 text-sm flex justify-between">
                  <span>{item}</span>
                </div>
              ))}
            </div>

            <div className="flex gap-2">
              {order.status === 'Pending' ? (
                <button className="flex-1 bg-amber-500 text-white py-2.5 rounded-xl font-medium text-sm flex justify-center items-center gap-2">
                  Start Preparing
                </button>
              ) : order.status === 'Preparing' ? (
                <button className="flex-1 bg-emerald-600 text-white py-2.5 rounded-xl font-medium text-sm flex justify-center items-center gap-2">
                  <Check size={16} /> Mark Ready
                </button>
              ) : (
                <div className="flex-1 bg-gray-100 text-gray-500 py-2.5 rounded-xl font-medium text-sm flex justify-center items-center gap-2">
                  Completed
                </div>
              )}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
