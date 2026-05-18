export const PRODUCTS = [
  { id: 1, name: 'Signature Iced Coffee', price: 28000, category: 'Coffee', image: 'https://images.unsplash.com/photo-1684439670717-b1147a7e7534?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxpY2VkJTIwY29mZmVlJTIwZHJpbmt8ZW58MXx8fHwxNzc4MDM4NDYwfDA&ixlib=rb-4.1.0&q=80&w=1080', inStock: true, sales: 124 },
  { id: 2, name: 'Matcha Latte', price: 32000, category: 'Non-Coffee', image: 'https://images.unsplash.com/photo-1617892165107-76fb45f50f7c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtYXRjaGElMjBsYXR0ZSUyMGRyaW5rfGVufDF8fHx8MTc3ODA4NzMzM3ww&ixlib=rb-4.1.0&q=80&w=1080', inStock: true, sales: 89 },
  { id: 3, name: 'Butter Croissant', price: 25000, category: 'Pastry', image: 'https://images.unsplash.com/photo-1712723246766-3eaea22e52ff?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjcm9pc3NhbnQlMjBwYXN0cnl8ZW58MXx8fHwxNzc3OTk4NTAxfDA&ixlib=rb-4.1.0&q=80&w=1080', inStock: false, sales: 45 },
];

export const RAW_MATERIALS = [
  { id: 'RM1', name: 'Coffee Beans (Arabica)', stock: '12 kg', status: 'Good' },
  { id: 'RM2', name: 'Fresh Milk', stock: '5 L', status: 'Low' },
  { id: 'RM3', name: 'Matcha Powder', stock: '2 kg', status: 'Good' },
  { id: 'RM4', name: 'Sugar Syrup', stock: '1 L', status: 'Critical' },
];

export const EMPLOYEES = [
  { id: 'E1', name: 'Budi Santoso', role: 'Head Barista', shift: 'Morning', image: 'https://images.unsplash.com/photo-1626113337617-7e9ea91253d7?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxiYXJpc3RhJTIwcG9ydHJhaXR8ZW58MXx8fHwxNzc4MDg3MzMzfDA&ixlib=rb-4.1.0&q=80&w=1080' },
  { id: 'E2', name: 'Siti Aminah', role: 'Cashier', shift: 'Afternoon', image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=1080' },
];

export const ORDERS = [
  { id: '#1024', customer: 'John D.', items: ['1x Signature Iced Coffee', '1x Butter Croissant'], total: 53000, status: 'Preparing', time: '10:45 AM' },
  { id: '#1025', customer: 'Alice M.', items: ['2x Matcha Latte'], total: 64000, status: 'Pending', time: '10:50 AM' },
  { id: '#1023', customer: 'Bob K.', items: ['1x Espresso'], total: 20000, status: 'Ready', time: '10:40 AM' },
];

export const REVIEWS = [
  { id: 'R1', customer: 'Sarah W.', orderId: '#1015', rating: 5, date: 'May 4, 2026', comment: 'The signature iced coffee is consistently amazing! Perfect balance of sweetness.', items: ['Signature Iced Coffee', 'Butter Croissant'] },
  { id: 'R2', customer: 'Mike T.', orderId: '#1012', rating: 4, date: 'May 3, 2026', comment: 'Matcha latte was good, but the pastry was a bit cold.', items: ['Matcha Latte'] },
  { id: 'R3', customer: 'Elena R.', orderId: '#1008', rating: 5, date: 'May 1, 2026', comment: 'Fast preparation and super friendly staff at pickup.', items: ['Signature Iced Coffee', 'Espresso'] },
  { id: 'R4', customer: 'David K.', orderId: '#1005', rating: 3, date: 'Apr 30, 2026', comment: 'Order took a bit longer than expected to be ready.', items: ['2x Matcha Latte'] },
];

export const SALES_DATA = [
  { name: 'Mon', sales: 4000 },
  { name: 'Tue', sales: 3000 },
  { name: 'Wed', sales: 2000 },
  { name: 'Thu', sales: 2780 },
  { name: 'Fri', sales: 1890 },
  { name: 'Sat', sales: 2390 },
  { name: 'Sun', sales: 3490 },
];
