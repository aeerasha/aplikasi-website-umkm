import React, { useState } from 'react';
import { PRODUCTS } from '../../mockData';
import { Search, Plus } from 'lucide-react';
import { Link } from 'react-router';

export function CustomerCatalog() {
  const [activeCategory, setActiveCategory] = useState('All');
  
  const categories = ['All', 'Coffee', 'Non-Coffee', 'Pastry'];
  
  const filteredProducts = activeCategory === 'All' 
    ? PRODUCTS 
    : PRODUCTS.filter(p => p.category === activeCategory);

  return (
    <div className="flex flex-col h-full bg-gray-50">
      <div className="bg-emerald-800 pt-12 pb-6 px-4 rounded-b-[2rem] shadow-md z-10 sticky top-0">
        <h1 className="text-2xl font-bold text-white mb-4">Good morning, John!</h1>
        <div className="relative">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size={20} />
          <input 
            type="text" 
            placeholder="What are you craving?" 
            className="w-full bg-white rounded-2xl py-3 pl-12 pr-4 text-sm outline-none shadow-sm"
          />
        </div>
      </div>

      <div className="px-4 py-6">
        <div className="flex gap-2 overflow-x-auto pb-2 scrollbar-hide -mx-4 px-4">
          {categories.map(cat => (
            <button
              key={cat}
              onClick={() => setActiveCategory(cat)}
              className={`px-5 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors ${
                activeCategory === cat 
                  ? 'bg-emerald-700 text-white shadow-md' 
                  : 'bg-white text-gray-600 border border-gray-200'
              }`}
            >
              {cat}
            </button>
          ))}
        </div>

        <div className="mt-6 grid grid-cols-2 gap-4">
          {filteredProducts.map(product => (
            <div key={product.id} className="bg-white rounded-2xl p-3 shadow-sm border border-gray-100 flex flex-col">
              <div className="relative aspect-square rounded-xl overflow-hidden mb-3">
                <img src={product.image} alt={product.name} className="w-full h-full object-cover" />
                {!product.inStock && (
                  <div className="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center">
                    <span className="bg-gray-900 text-white text-xs font-bold px-2 py-1 rounded-full">Sold Out</span>
                  </div>
                )}
              </div>
              <div className="flex-1 flex flex-col">
                <h3 className="font-semibold text-gray-900 text-sm leading-tight mb-1">{product.name}</h3>
                <p className="text-emerald-700 font-bold text-sm mt-auto mb-2">Rp {product.price.toLocaleString()}</p>
                <button 
                  disabled={!product.inStock}
                  className={`w-full py-2 rounded-xl text-sm font-medium flex items-center justify-center gap-1 transition-colors ${
                    product.inStock 
                      ? 'bg-gray-900 text-white hover:bg-gray-800' 
                      : 'bg-gray-100 text-gray-400'
                  }`}
                >
                  <Plus size={16} /> Add
                </button>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
