<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Rekap Penjualan
        |--------------------------------------------------------------------------
        */

        // Harian
        $dailySales = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_price');

        // Mingguan
        $weeklySales = Order::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->where('status', 'completed')
            ->sum('total_price');

        // Bulanan
        $monthlySales = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('total_price');

        /*
        |--------------------------------------------------------------------------
        | Produk Terlaris
        |--------------------------------------------------------------------------
        */

        $bestProducts = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Produk Kurang Diminati
        |--------------------------------------------------------------------------
        */

        $leastProducts = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_sold')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Produk Tidak Pernah Dibeli
        |--------------------------------------------------------------------------
        */

        $unsoldProducts = Product::doesntHave('orderItems')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'dailySales',
            'weeklySales',
            'monthlySales',
            'bestProducts',
            'leastProducts',
            'unsoldProducts'
        ));
    }
}