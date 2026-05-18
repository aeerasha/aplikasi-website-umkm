<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    // =========================
    // LIST ANTRIAN PEGAWAI
    // =========================
    public function index()
    {
        $orders = Order::with(['items.product', 'user'])
            ->orderBy('created_at', 'asc') // ANTRIAN
            ->get();

        return view('pegawai.orders.index', compact('orders'));
    }

    // =========================
    // UPDATE STATUS ORDER
    // =========================
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status order berhasil diperbarui');
    }
}