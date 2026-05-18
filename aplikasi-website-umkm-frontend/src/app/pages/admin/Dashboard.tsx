import React from 'react';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';
import { TrendingUp, Users, DollarSign, Package } from 'lucide-react';
import { SALES_DATA, ORDERS } from '../../mockData';

export function AdminDashboard() {
  const stats = [
    { label: 'Total Sales', value: 'Rp 4.2M', icon: DollarSign, trend: '+12%' },
    { label: 'Orders', value: '145', icon: Package, trend: '+5%' },
    { label: 'Customers', value: '89', icon: Users, trend: '+18%' },
    { label: 'Avg. Order', value: 'Rp 28.5K', icon: TrendingUp, trend: '+2%' },
  ];

  return (
    <div className="p-6 md:p-8 space-y-8 max-w-7xl mx-auto">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Dashboard</h1>
          <p className="text-gray-500">Welcome back, Admin. Here's what's happening.</p>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {stats.map((stat, idx) => (
          <div key={idx} className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start justify-between">
            <div>
              <p className="text-sm font-medium text-gray-500">{stat.label}</p>
              <h3 className="text-2xl font-bold text-gray-900 mt-2">{stat.value}</h3>
              <p className="text-sm text-emerald-600 mt-1">{stat.trend} from last week</p>
            </div>
            <div className="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
              <stat.icon size={24} />
            </div>
          </div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
          <h2 className="text-lg font-bold text-gray-900 mb-6">Sales Overview</h2>
          <div className="h-80">
            <ResponsiveContainer width="100%" height="100%">
              <BarChart data={SALES_DATA}>
                <CartesianGrid key="grid" strokeDasharray="3 3" vertical={false} stroke="#E5E7EB" />
                <XAxis key="xaxis" dataKey="name" axisLine={false} tickLine={false} tick={{fill: '#6B7280'}} />
                <YAxis key="yaxis" axisLine={false} tickLine={false} tick={{fill: '#6B7280'}} />
                <Tooltip key="tooltip" cursor={{fill: '#F3F4F6'}} contentStyle={{borderRadius: '12px', border: 'none', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)'}} />
                <Bar key="bar" dataKey="sales" fill="#059669" radius={[4, 4, 0, 0]} />
              </BarChart>
            </ResponsiveContainer>
          </div>
        </div>

        <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
          <div className="flex justify-between items-center mb-6">
            <h2 className="text-lg font-bold text-gray-900">Recent Orders</h2>
            <button className="text-emerald-600 text-sm font-medium hover:text-emerald-700">View All</button>
          </div>
          <div className="space-y-6">
            {ORDERS.map((order) => (
              <div key={order.id} className="flex items-center justify-between pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                <div>
                  <p className="font-semibold text-gray-900">{order.customer}</p>
                  <p className="text-sm text-gray-500">{order.items[0]} {order.items.length > 1 && `+${order.items.length - 1} more`}</p>
                </div>
                <div className="text-right">
                  <p className="font-semibold text-gray-900">Rp {(order.total).toLocaleString()}</p>
                  <span className={`text-xs px-2 py-1 rounded-full font-medium ${
                    order.status === 'Ready' ? 'bg-emerald-100 text-emerald-700' :
                    order.status === 'Preparing' ? 'bg-amber-100 text-amber-700' :
                    'bg-gray-100 text-gray-700'
                  }`}>
                    {order.status}
                  </span>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
