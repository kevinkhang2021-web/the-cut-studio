<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function lines(): array
    {
        $cart = session('cart', []);
        $products = Product::active()->whereIn('id', array_keys($cart))->get()->keyBy('id');
        $lines = [];
        foreach ($cart as $id => $qty) {
            if ($products->has($id)) {
                $p = $products[$id];
                $lines[] = ['product' => $p, 'qty' => $qty, 'total' => $p->price_fc * $qty];
            }
        }
        return $lines;
    }

    public function show()
    {
        $lines = $this->lines();
        return view('cart.show', ['lines' => $lines, 'total' => collect($lines)->sum('total')]);
    }

    public function add(Request $request, Product $product)
    {
        abort_unless($product->is_active, 404);
        $cart = session('cart', []);
        $qty = ($cart[$product->id] ?? 0) + max(1, (int) $request->input('qty', 1));
        if ($qty > $product->stock) {
            return back()->with('error', 'Stock insuffisant pour « ' . $product->name . ' » (disponible : ' . $product->stock . ').');
        }
        $cart[$product->id] = $qty;
        session(['cart' => $cart]);
        return back()->with('success', '« ' . $product->name . ' » ajouté au panier.');
    }

    public function update(Request $request, Product $product)
    {
        $cart = session('cart', []);
        $qty = (int) $request->input('qty', 1);
        if ($qty <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = min($qty, max(1, $product->stock));
        }
        session(['cart' => $cart]);
        return redirect()->route('cart');
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);
        return redirect()->route('cart');
    }

    public function checkout()
    {
        $lines = $this->lines();
        if (!$lines) return redirect()->route('cart')->with('error', 'Votre panier est vide.');
        return view('cart.checkout', ['lines' => $lines, 'total' => collect($lines)->sum('total')]);
    }

    public function place(Request $request)
    {
        $data = $request->validate([
            'delivery_method' => 'required|in:pickup,delivery',
            'phone'           => 'required|regex:/^\+?[0-9 ]{9,16}$/',
            'address'         => 'required_if:delivery_method,delivery|nullable|string|max:255',
        ]);
        $lines = $this->lines();
        if (!$lines) return redirect()->route('cart');

        $order = DB::transaction(function () use ($lines, $data, $request) {
            // Verrouille les produits pour éviter de vendre plus que le stock
            foreach ($lines as $l) {
                $p = Product::lockForUpdate()->find($l['product']->id);
                if ($p->stock < $l['qty']) {
                    abort(422, 'Stock insuffisant pour « ' . $p->name . ' ».');
                }
            }
            $order = Order::create([
                'user_id'         => $request->user()->id,
                'delivery_method' => $data['delivery_method'],
                'address'         => $data['delivery_method'] === 'delivery' ? $data['address'] : null,
                'phone'           => preg_replace('/\s+/', '', $data['phone']),
                'total_fc'        => collect($lines)->sum('total'),
            ]);
            foreach ($lines as $l) {
                $order->items()->create([
                    'product_id'    => $l['product']->id,
                    'name'          => $l['product']->name,
                    'unit_price_fc' => $l['product']->price_fc,
                    'quantity'      => $l['qty'],
                ]);
                $l['product']->decrement('stock', $l['qty']);
            }
            return $order;
        });

        session()->forget('cart');
        return redirect()->route('payments.show', ['type' => 'order', 'id' => $order->id])
            ->with('success', 'Commande enregistrée. Il ne reste qu\'à payer.');
    }
}
