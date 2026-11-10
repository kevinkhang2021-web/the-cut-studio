<div class="card product">
    <a href="{{ route('catalog.show', $product) }}" class="thumb">
        @if($product->image_url)<img src="{{ $product->image_url }}" alt="{{ $product->name }}">
        @else
            {{ ['console' => '🎮', 'jeu' => '💿', 'accessoire' => '🎧'][$product->category] ?? '🎮' }}
        @endif
    </a>
    <div class="body">
        <span class="tag">{{ $product->platform ?: $product->category_label }}</span>
        <a class="name" href="{{ route('catalog.show', $product) }}">{{ $product->name }}</a>
        <div class="price">@fc($product->price_fc)</div>
        <div style="margin-top:auto">
            @if($product->stock > 0)
                <form method="POST" action="{{ route('cart.add', $product) }}">@csrf
                    <button class="btn sm block" type="submit">Ajouter au panier</button>
                </form>
            @else
                <span class="badge bad">Rupture de stock</span>
            @endif
        </div>
    </div>
</div>
