import React, { useState } from 'react';
import { Outlet, Link, useLocation } from 'react-router';
import { Menu, X, LayoutDashboard, Package, Users, Settings, LogOut, FileText, Star } from 'lucide-react';

export function AdminLayout() {
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const location = useLocation();

  const navItems = [
    { name: 'Dashboard', path: '/admin', icon: LayoutDashboard },
    { name: 'Products', path: '/admin/products', icon: Package },
    { name: 'Inventory', path: '/admin/inventory', icon: FileText },
    { name: 'Employees', path: '/admin/employees', icon: Users },
    { name: 'Reviews', path: '/admin/reviews', icon: Star },
  ];

  const SidebarContent = () => (
    <div className="flex flex-col h-full bg-emerald-900 text-white w-64">
      <div className="p-6 font-bold text-2xl tracking-tighter">FNBMaster</div>
      <nav className="flex-1 px-4 space-y-2">
        {navItems.map((item) => {
          const isActive = location.pathname === item.path;
          return (
            <Link
              key={item.name}
              to={item.path}
              className={`flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors ${
                isActive ? 'bg-emerald-800 text-white' : 'text-emerald-100 hover:bg-emerald-800/50'
              }`}
            >
              <item.icon size={20} />
              <span className="font-medium">{item.name}</span>
            </Link>
          );
        })}
      </nav>
      <div className="p-4 border-t border-emerald-800">
        <Link to="/" className="flex items-center space-x-3 px-4 py-3 text-emerald-200 hover:text-white transition-colors">
          <LogOut size={20} />
          <span>Sign Out</span>
        </Link>
      </div>
    </div>
  );

  return (
    <div className="min-h-screen bg-gray-50 flex">
      {/* Desktop Sidebar */}
      <div className="hidden md:block fixed inset-y-0 left-0 w-64">
        <SidebarContent />
      </div>

      {/* Mobile Header & Overlay */}
      <div className="md:hidden fixed top-0 left-0 right-0 h-16 bg-emerald-900 text-white flex items-center justify-between px-4 z-50">
        <div className="font-bold text-xl">FNBMaster</div>
        <button onClick={() => setIsMobileMenuOpen(true)} className="p-2">
          <Menu size={24} />
        </button>
      </div>

      {/* Mobile Sidebar */}
      {isMobileMenuOpen && (
        <div className="md:hidden fixed inset-0 z-50 flex">
          <div className="fixed inset-0 bg-black/50" onClick={() => setIsMobileMenuOpen(false)} />
          <div className="relative w-64 max-w-sm flex-1">
            <button
              onClick={() => setIsMobileMenuOpen(false)}
              className="absolute top-4 right-4 text-white"
            >
              <X size={24} />
            </button>
            <SidebarContent />
          </div>
        </div>
      )}

      {/* Main Content */}
      <div className="flex-1 md:ml-64 pt-16 md:pt-0">
        <Outlet />
      </div>
    </div>
  );
}
