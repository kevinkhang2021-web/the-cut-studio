<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private function rules(): array
    {
        return [
            'name'        => 'required|string|max:150',
            'category'    => 'required|in:console,jeu,accessoire',
            'platform'    => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'price_usd'   => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|max:2048',
        ];
    }

    private function save(Request $request, Product $product)
    {
        $data = $request->validate($this->rules());
        $data['is_active'] = $request->boolean('is_active');
        unset($data['image']);
        if ($request->hasFile('image')) {
            $name = uniqid('p_') . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads'), $name);
            $data['image'] = 'uploads/' . $name;
        }
        $product->fill($data)->save();
    }

    public function index(Request $request)
    {
        $products = Product::when($request->q, fn ($q, $s) => $q->where('name', 'like', "%$s%"))
            ->when($request->category, fn ($q, $c) => $q->where('category', $c))
            ->orderBy('category')->orderBy('name')->paginate(20)->withQueryString();
        return view('admin.products.index', compact('products'));
    }

    public function create() { return view('admin.products.form', ['product' => new Product(['is_active' => true, 'category' => 'jeu', 'platform' => 'PS4'])]); }

    public function store(Request $request)
    {
        $this->save($request, new Product());
        return redirect()->route('admin.products.index')->with('success', 'Article ajouté.');
    }

    public function edit(Product $product) { return view('admin.products.form', compact('product')); }

    public function update(Request $request, Product $product)
    {
        $this->save($request, $product);
        return redirect()->route('admin.products.index')->with('success', 'Article modifié.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Article supprimé.');
    }
}
