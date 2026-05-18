import React from 'react';
import { Outlet, Link, useLocation } from 'react-router';
import { Home, ShoppingBag, Truck, Clock } from 'lucide-react';

export function CustomerLayout() {
  const location = useLocation();

  const navItems = [
    { name: 'Menu', path: '/customer', icon: Home },
    { name: 'Cart', path: '/customer/cart', icon: ShoppingBag },
    { name: 'Track', path: '/customer/track', icon: Truck },
    { name: 'History', path: '/customer/history', icon: Clock },
  ];

  return (
    <div className="min-h-screen bg-gray-50 flex justify-center">
      <div className="w-full max-w-md bg-white min-h-screen shadow-xl relative pb-20 flex flex-col">
        {/* Content */}
        <div className="flex-1 overflow-y-auto">
          <Outlet />
        </div>

        {/* Bottom Nav */}
        <nav className="fixed bottom-0 w-full max-w-md bg-white border-t border-gray-100 flex justify-around py-2 z-20 pb-safe">
          {navItems.map((item) => {
            const isActive = location.pathname === item.path;
            return (
              <Link
                key={item.name}
                to={item.path}
                className={`flex flex-col items-center p-2 rounded-2xl w-16 transition-all ${
                  isActive ? 'text-emerald-700 bg-emerald-50' : 'text-gray-400'
                }`}
              >
                <item.icon size={22} className="mb-1" />
                <span className="text-[10px] font-medium">{item.name}</span>
              </Link>
            );
          })}
        </nav>
      </div>
    </div>
  );
}
