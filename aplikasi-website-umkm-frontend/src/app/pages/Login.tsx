import React from 'react';
import { Link } from 'react-router';
import { Coffee, ShieldCheck, Users, UserCircle } from 'lucide-react';

export function Login() {
  return (
    <div className="min-h-screen bg-emerald-900 flex flex-col justify-center items-center p-4">
      <div className="max-w-md w-full bg-white rounded-3xl shadow-2xl p-8 space-y-8">
        <div className="text-center space-y-2">
          <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 text-emerald-800 mb-4">
            <Coffee size={32} />
          </div>
          <h1 className="text-3xl font-bold text-gray-900 tracking-tight">FNBMaster</h1>
          <p className="text-gray-500">Select your portal to continue</p>
        </div>

        <div className="space-y-4 pt-4">
          <Link
            to="/admin"
            className="group flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-emerald-600 hover:bg-emerald-50 transition-all"
          >
            <div className="bg-emerald-100 p-3 rounded-xl text-emerald-700 group-hover:bg-emerald-200 transition-colors">
              <ShieldCheck size={24} />
            </div>
            <div className="ml-4">
              <h2 className="text-lg font-semibold text-gray-900">Owner / Admin</h2>
              <p className="text-sm text-gray-500">Manage business & reports</p>
            </div>
          </Link>

          <Link
            to="/staff"
            className="group flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-emerald-600 hover:bg-emerald-50 transition-all"
          >
            <div className="bg-emerald-100 p-3 rounded-xl text-emerald-700 group-hover:bg-emerald-200 transition-colors">
              <Users size={24} />
            </div>
            <div className="ml-4">
              <h2 className="text-lg font-semibold text-gray-900">Staff</h2>
              <p className="text-sm text-gray-500">Manage orders & stock</p>
            </div>
          </Link>

          <Link
            to="/customer"
            className="group flex items-center p-4 border-2 border-gray-100 rounded-2xl hover:border-emerald-600 hover:bg-emerald-50 transition-all"
          >
            <div className="bg-emerald-100 p-3 rounded-xl text-emerald-700 group-hover:bg-emerald-200 transition-colors">
              <UserCircle size={24} />
            </div>
            <div className="ml-4">
              <h2 className="text-lg font-semibold text-gray-900">Customer</h2>
              <p className="text-sm text-gray-500">Order & track status</p>
            </div>
          </Link>
        </div>
      </div>
      
      <div className="mt-8 text-emerald-200/50 text-sm">
        &copy; 2026 FNBMaster System
      </div>
    </div>
  );
}
