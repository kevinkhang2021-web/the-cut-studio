<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Studio;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active();
        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('q')) $query->where('name', 'like', '%' . $request->q . '%');

        // Filtre par gamme de prix (calculée à partir du prix en FC)
        if ($request->filled('gamme')) {
            $rate = Studio::rate();
            match ($request->gamme) {
                'petits' => $query->whereRaw('price_usd * ? <= 92000', [$rate]),
                'moyens' => $query->whereRaw('price_usd * ? > 92000 AND price_usd * ? <= 138000', [$rate, $rate]),
                'eleves' => $query->whereRaw('price_usd * ? > 138000', [$rate]),
                default  => null,
            };
        }

        $sort = $request->get('sort', 'price_asc');
        $query->orderBy($sort === 'price_desc' ? 'price_usd' : ($sort === 'name' ? 'name' : 'price_usd'),
                        $sort === 'price_desc' ? 'desc' : 'asc');

        return view('catalog.index', ['products' => $query->paginate(12)->withQueryString()]);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);
        return view('catalog.show', ['product' => $product]);
    }
}
