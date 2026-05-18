import React from 'react';
import { Outlet, Link, useLocation } from 'react-router';
import { ClipboardList, PackageSearch, LogOut } from 'lucide-react';

export function StaffLayout() {
  const location = useLocation();

  const navItems = [
    { name: 'Orders', path: '/staff', icon: ClipboardList },
    { name: 'Stock', path: '/staff/stock', icon: PackageSearch },
  ];

  return (
    <div className="min-h-screen bg-gray-50 flex justify-center">
      <div className="w-full max-w-md bg-white min-h-screen shadow-xl relative pb-20 flex flex-col">
        {/* Header */}
        <header className="bg-emerald-800 text-white p-4 flex justify-between items-center shadow-md z-10 sticky top-0">
          <div className="font-semibold text-lg">Staff Portal</div>
          <Link to="/" className="text-emerald-100 hover:text-white">
            <LogOut size={20} />
          </Link>
        </header>

        {/* Content */}
        <div className="flex-1 overflow-y-auto">
          <Outlet />
        </div>

        {/* Bottom Nav */}
        <nav className="fixed bottom-0 w-full max-w-md bg-white border-t border-gray-100 flex justify-around py-3 z-20">
          {navItems.map((item) => {
            const isActive = location.pathname === item.path;
            return (
              <Link
                key={item.name}
                to={item.path}
                className={`flex flex-col items-center p-2 rounded-xl transition-all ${
                  isActive ? 'text-emerald-700 font-semibold' : 'text-gray-400'
                }`}
              >
                <item.icon size={24} className={isActive ? 'mb-1' : 'mb-1'} />
                <span className="text-[10px] uppercase tracking-wide">{item.name}</span>
              </Link>
            );
          })}
        </nav>
      </div>
    </div>
  );
}
