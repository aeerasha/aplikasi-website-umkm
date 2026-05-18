import React, { useState } from 'react';
import { REVIEWS } from '../../mockData';
import { Star, StarHalf, Search, Filter } from 'lucide-react';

export function AdminReviews() {
  const [searchTerm, setSearchTerm] = useState('');

  const filteredReviews = REVIEWS.filter(r => 
    r.customer.toLowerCase().includes(searchTerm.toLowerCase()) || 
    r.orderId.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const averageRating = (REVIEWS.reduce((acc, curr) => acc + curr.rating, 0) / REVIEWS.length).toFixed(1);

  const renderStars = (rating: number) => {
    return Array.from({ length: 5 }).map((_, idx) => (
      <Star 
        key={idx} 
        size={16} 
        className={idx < Math.floor(rating) ? "text-amber-400 fill-amber-400" : "text-gray-300"} 
      />
    ));
  };

  return (
    <div className="p-6 md:p-8 max-w-7xl mx-auto space-y-8">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Customer Reviews</h1>
          <p className="text-gray-500">Monitor and respond to customer feedback.</p>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6">
          <div className="flex-1">
            <p className="text-sm font-medium text-gray-500">Average Rating</p>
            <div className="flex items-end gap-2 mt-2">
              <h3 className="text-4xl font-bold text-gray-900">{averageRating}</h3>
              <p className="text-sm text-gray-500 mb-1">/ 5.0</p>
            </div>
            <div className="flex mt-2">
              {renderStars(Number(averageRating))}
            </div>
          </div>
          <div className="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center text-amber-500 shrink-0">
            <Star size={32} className="fill-amber-500" />
          </div>
        </div>
        <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6">
          <div className="flex-1">
            <p className="text-sm font-medium text-gray-500">Total Reviews</p>
            <h3 className="text-4xl font-bold text-gray-900 mt-2">{REVIEWS.length}</h3>
            <p className="text-sm text-emerald-600 mt-1">+12% this month</p>
          </div>
        </div>
      </div>

      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div className="p-4 border-b border-gray-100 flex flex-col md:flex-row gap-4">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={20} />
            <input
              type="text"
              placeholder="Search by customer name or order ID..."
              className="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
          </div>
          <button className="flex items-center justify-center space-x-2 px-4 py-2 border border-gray-200 rounded-xl hover:bg-gray-50 text-gray-700 font-medium whitespace-nowrap">
            <Filter size={20} />
            <span>Filter</span>
          </button>
        </div>
        
        <div className="divide-y divide-gray-100">
          {filteredReviews.map((review) => (
            <div key={review.id} className="p-6 hover:bg-gray-50 transition-colors">
              <div className="flex justify-between items-start mb-4">
                <div>
                  <div className="flex items-center gap-3">
                    <h4 className="font-bold text-gray-900">{review.customer}</h4>
                    <span className="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">{review.orderId}</span>
                  </div>
                  <p className="text-sm text-gray-500 mt-1">{review.date}</p>
                </div>
                <div className="flex">
                  {renderStars(review.rating)}
                </div>
              </div>
              <p className="text-gray-700">{review.comment}</p>
              <div className="mt-4 flex flex-wrap gap-2">
                {review.items.map((item, idx) => (
                  <span key={idx} className="text-xs px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full font-medium">
                    {item}
                  </span>
                ))}
              </div>
            </div>
          ))}
          {filteredReviews.length === 0 && (
            <div className="p-8 text-center text-gray-500">
              No reviews found matching your search.
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
