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
        // =========================
        // REKAP PENJUALAN
        // =========================

        $dailySales = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_price');

        $weeklySales = Order::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->where('status', 'completed')
            ->sum('total_price');

        $monthlySales = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('total_price');

        // =========================
        // GRAFIK 7 HARI
        // =========================

        $dailyChart = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('status', 'completed')
            ->whereBetween('created_at', [now()->subDays(6), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // =========================
        // PRODUK
        // =========================

        $bestProducts = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $leastProducts = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_sold')
            ->take(5)
            ->get();

        $unsoldProducts = Product::doesntHave('orderItems')
            ->take(5)
            ->get();

        // =========================
        // RETURN VIEW (WAJIB ADA SEMUA VARIABLE)
        // =========================

        return view('owner.dashboard', compact(
            'dailySales',
            'weeklySales',
            'monthlySales',
            'bestProducts',
            'leastProducts',
            'unsoldProducts',
            'dailyChart'
        ));
    }

    public function salesReport()
    {
        $dailySales = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_price');

        $weeklySales = Order::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->where('status', 'completed')
            ->sum('total_price');

        $monthlySales = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('total_price');

        return view('owner.sales-report', compact(
            'dailySales',
            'weeklySales',
            'monthlySales'
        ));
    }


    public function bestProducts()
    {
        $bestProducts = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();

        return view('owner.best-products', compact(
            'bestProducts'
        ));
    }
}