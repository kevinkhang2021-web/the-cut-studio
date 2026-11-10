<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user', 'items')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate(['status' => 'required|in:prepared,delivered,cancelled']);
        DB::transaction(function () use ($order, $data) {
            // Une commande annulée remet les articles en stock
            if ($data['status'] === 'cancelled' && $order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product) $item->product->increment('stock', $item->quantity);
                }
            }
            $order->update(['status' => $data['status']]);
        });
        return back()->with('success', 'Commande #' . $order->id . ' mise à jour.');
    }

    public function cash(Order $order, PaymentService $service)
    {
        if ($order->paid) return back()->with('error', 'Déjà payée.');
        $service->pay($order, 'cash');
        return back()->with('success', 'Paiement en espèces enregistré.');
    }
}
