import React from 'react';
import { CheckCircle2, Clock, Coffee, MapPin } from 'lucide-react';

export function CustomerTrackOrder() {
  const steps = [
    { label: 'Order Placed', time: '10:45 AM', completed: true, icon: Clock },
    { label: 'Preparing', time: '10:48 AM', completed: true, icon: Coffee },
    { label: 'Ready for Pickup', time: 'Est. 10:55 AM', completed: false, icon: CheckCircle2 },
  ];

  return (
    <div className="p-4 flex flex-col min-h-full">
      <div className="pt-8 pb-4 text-center sticky top-0 bg-gray-50 z-10">
        <h1 className="text-xl font-bold text-gray-900">Order Status</h1>
        <p className="text-gray-500 text-sm mt-1">Order #1024</p>
      </div>

      <div className="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mt-4 mb-6">
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center w-20 h-20 rounded-full bg-amber-100 text-amber-600 mb-4 animate-bounce">
            <Coffee size={40} />
          </div>
          <h2 className="text-2xl font-bold text-gray-900">Preparing...</h2>
          <p className="text-gray-500 mt-2 text-sm">Your delicious order is being crafted by our barista.</p>
        </div>

        <div className="relative">
          <div className="absolute left-6 top-6 bottom-6 w-0.5 bg-gray-100"></div>
          
          <div className="space-y-6 relative">
            {steps.map((step, idx) => (
              <div key={idx} className="flex gap-4 items-start">
                <div className={`w-12 h-12 rounded-full flex items-center justify-center shrink-0 z-10 relative ${
                  step.completed ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-400'
                }`}>
                  <step.icon size={20} />
                  {step.completed && idx < steps.length - 1 && (
                    <div className="absolute top-12 bottom-[-24px] left-1/2 w-0.5 -translate-x-1/2 bg-emerald-500"></div>
                  )}
                </div>
                <div className="pt-2">
                  <h4 className={`font-semibold ${step.completed ? 'text-gray-900' : 'text-gray-400'}`}>{step.label}</h4>
                  <p className="text-xs text-gray-500 mt-0.5">{step.time}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      <div className="bg-emerald-900 rounded-3xl p-5 text-white flex gap-4 items-center mb-6">
        <div className="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center shrink-0 backdrop-blur-sm">
          <MapPin size={24} />
        </div>
        <div>
          <h4 className="font-bold text-sm">Pickup Point</h4>
          <p className="text-emerald-200 text-xs mt-1">FNBMaster Central Store, Counter 2</p>
        </div>
      </div>
    </div>
  );
}
