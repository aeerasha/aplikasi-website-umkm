import React from 'react';
import { PRODUCTS } from '../../mockData';
import { Minus, Plus, Trash2, QrCode } from 'lucide-react';
import { Link } from 'react-router';

export function CustomerCart() {
  const cartItems = [
    { ...PRODUCTS[0], quantity: 1 },
    { ...PRODUCTS[2], quantity: 2 },
  ];

  const subtotal = cartItems.reduce((acc, item) => acc + (item.price * item.quantity), 0);
  const tax = subtotal * 0.11;
  const total = subtotal + tax;

  return (
    <div className="p-4 flex flex-col min-h-full">
      <div className="pt-8 pb-4 text-center sticky top-0 bg-gray-50 z-10 border-b border-gray-100 mb-4">
        <h1 className="text-xl font-bold text-gray-900">Your Cart</h1>
      </div>

      <div className="flex-1 space-y-4">
        {cartItems.map(item => (
          <div key={item.id} className="bg-white rounded-2xl p-3 shadow-sm border border-gray-100 flex gap-4">
            <img src={item.image} alt={item.name} className="w-20 h-20 rounded-xl object-cover" />
            <div className="flex-1 flex flex-col">
              <div className="flex justify-between items-start">
                <h3 className="font-semibold text-gray-900 text-sm">{item.name}</h3>
                <button className="text-gray-400 hover:text-red-500">
                  <Trash2 size={16} />
                </button>
              </div>
              <p className="text-emerald-700 font-bold text-sm mt-1">Rp {item.price.toLocaleString()}</p>
              
              <div className="mt-auto flex items-center gap-3">
                <button className="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                  <Minus size={14} />
                </button>
                <span className="font-medium text-sm w-4 text-center">{item.quantity}</span>
                <button className="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center">
                  <Plus size={14} />
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>

      <div className="mt-6 bg-white rounded-3xl p-5 shadow-sm border border-gray-100 space-y-3 mb-6">
        <h3 className="font-bold text-gray-900 mb-2">Payment Details</h3>
        <div className="flex justify-between text-sm text-gray-500">
          <span>Subtotal</span>
          <span>Rp {subtotal.toLocaleString()}</span>
        </div>
        <div className="flex justify-between text-sm text-gray-500">
          <span>Tax (11%)</span>
          <span>Rp {tax.toLocaleString()}</span>
        </div>
        <div className="pt-3 border-t border-gray-100 flex justify-between font-bold text-lg text-gray-900">
          <span>Total</span>
          <span>Rp {total.toLocaleString()}</span>
        </div>

        <div className="pt-4 space-y-2">
          <p className="text-xs font-semibold text-gray-500 uppercase tracking-wide">Payment Method</p>
          <div className="flex items-center gap-3 p-3 border border-emerald-200 bg-emerald-50 rounded-xl">
            <QrCode className="text-emerald-700" size={24} />
            <div className="flex-1">
              <div className="font-semibold text-emerald-900 text-sm">QRIS</div>
              <div className="text-emerald-700 text-xs">Pay with any E-Wallet</div>
            </div>
            <div className="w-4 h-4 rounded-full border-4 border-emerald-600 bg-white"></div>
          </div>
        </div>

        <Link to="/customer/track" className="mt-4 w-full bg-emerald-700 text-white py-4 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-emerald-800 transition-colors shadow-lg shadow-emerald-700/30">
          Pay Now
        </Link>
      </div>
    </div>
  );
}
