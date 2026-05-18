import React, { useState } from 'react';
import { Clock, Star, MapPin, Search } from 'lucide-react';

const PAST_ORDERS = [
  { id: '#1015', date: 'May 4, 2026', items: ['1x Signature Iced Coffee', '1x Butter Croissant'], total: 53000, status: 'Completed', isRated: true },
  { id: '#1012', date: 'May 3, 2026', items: ['1x Matcha Latte'], total: 32000, status: 'Completed', isRated: true },
  { id: '#1008', date: 'May 1, 2026', items: ['1x Signature Iced Coffee', '1x Espresso'], total: 48000, status: 'Completed', isRated: false },
  { id: '#1005', date: 'Apr 30, 2026', items: ['2x Matcha Latte'], total: 64000, status: 'Completed', isRated: true },
];

export function CustomerHistory() {
  const [activeTab, setActiveTab] = useState<'completed' | 'cancelled'>('completed');
  const [ratingModal, setRatingModal] = useState<string | null>(null);
  const [hoveredStar, setHoveredStar] = useState(0);
  const [selectedRating, setSelectedRating] = useState(0);
  const [reviewText, setReviewText] = useState('');

  const handleRateSubmit = () => {
    // In a real app, send rating to backend
    setRatingModal(null);
    setHoveredStar(0);
    setSelectedRating(0);
    setReviewText('');
  };

  return (
    <div className="p-4 flex flex-col min-h-full pb-24">
      <div className="pt-8 pb-4 sticky top-0 bg-gray-50 z-10">
        <h1 className="text-2xl font-bold text-gray-900 px-2">Order History</h1>
        
        <div className="flex space-x-4 mt-6 px-2 border-b border-gray-200">
          <button
            onClick={() => setActiveTab('completed')}
            className={`pb-3 font-medium transition-colors relative ${activeTab === 'completed' ? 'text-emerald-700' : 'text-gray-500'}`}
          >
            Completed
            {activeTab === 'completed' && <div className="absolute bottom-0 left-0 right-0 h-0.5 bg-emerald-600 rounded-t-full"></div>}
          </button>
          <button
            onClick={() => setActiveTab('cancelled')}
            className={`pb-3 font-medium transition-colors relative ${activeTab === 'cancelled' ? 'text-emerald-700' : 'text-gray-500'}`}
          >
            Cancelled
            {activeTab === 'cancelled' && <div className="absolute bottom-0 left-0 right-0 h-0.5 bg-emerald-600 rounded-t-full"></div>}
          </button>
        </div>
      </div>

      <div className="mt-4 space-y-4">
        {activeTab === 'completed' ? (
          PAST_ORDERS.map((order) => (
            <div key={order.id} className="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
              <div className="flex justify-between items-start mb-3">
                <div>
                  <h3 className="font-bold text-gray-900">{order.id}</h3>
                  <p className="text-xs text-gray-500 mt-0.5">{order.date}</p>
                </div>
                <div className="text-right">
                  <p className="font-bold text-gray-900">Rp {(order.total).toLocaleString()}</p>
                  <span className="text-[10px] font-medium px-2 py-1 bg-gray-100 text-gray-600 rounded-full mt-1 inline-block">
                    {order.status}
                  </span>
                </div>
              </div>
              
              <div className="border-t border-b border-gray-50 py-3 my-3">
                <p className="text-sm text-gray-700 leading-relaxed">
                  {order.items.join(', ')}
                </p>
              </div>
              
              <div className="flex justify-end pt-1">
                {order.isRated ? (
                  <span className="text-xs font-medium text-amber-500 flex items-center gap-1">
                    <Star size={14} className="fill-amber-500" /> Rated
                  </span>
                ) : (
                  <button 
                    onClick={() => setRatingModal(order.id)}
                    className="px-4 py-2 bg-emerald-50 text-emerald-700 text-sm font-bold rounded-xl hover:bg-emerald-100 transition-colors"
                  >
                    Rate Order
                  </button>
                )}
              </div>
            </div>
          ))
        ) : (
          <div className="text-center py-12">
            <p className="text-gray-500">No cancelled orders.</p>
          </div>
        )}
      </div>

      {/* Rating Modal */}
      {ratingModal && (
        <div className="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4">
          <div className="fixed inset-0 bg-black/40" onClick={() => setRatingModal(null)} />
          <div className="bg-white rounded-3xl p-6 w-full max-w-sm relative z-10 animate-in slide-in-from-bottom-10 sm:slide-in-from-bottom-0 sm:zoom-in-95 duration-200">
            <h2 className="text-xl font-bold text-gray-900 text-center mb-1">Rate Your Experience</h2>
            <p className="text-sm text-gray-500 text-center mb-6">Order {ratingModal}</p>
            
            <div className="flex justify-center gap-2 mb-6">
              {[1, 2, 3, 4, 5].map((star) => (
                <button
                  key={star}
                  onMouseEnter={() => setHoveredStar(star)}
                  onMouseLeave={() => setHoveredStar(0)}
                  onClick={() => setSelectedRating(star)}
                  className="p-1 transition-transform hover:scale-110"
                >
                  <Star 
                    size={36} 
                    className={`${
                      (hoveredStar || selectedRating) >= star 
                        ? 'text-amber-400 fill-amber-400' 
                        : 'text-gray-200'
                    } transition-colors duration-200`} 
                  />
                </button>
              ))}
            </div>

            <div className="mb-6">
              <label className="block text-sm font-medium text-gray-700 mb-2">Leave a review (optional)</label>
              <textarea
                className="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-gray-50 resize-none h-24"
                placeholder="What did you like or dislike?"
                value={reviewText}
                onChange={(e) => setReviewText(e.target.value)}
              ></textarea>
            </div>

            <button 
              onClick={handleRateSubmit}
              disabled={selectedRating === 0}
              className={`w-full py-3 rounded-xl font-bold text-white transition-colors ${
                selectedRating === 0 ? 'bg-gray-300' : 'bg-emerald-600 hover:bg-emerald-700'
              }`}
            >
              Submit Review
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
