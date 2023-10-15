{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

    <button type="submit" {{ $product->isSaleable() ? '' : 'disabled' }} class="button primary-btn">
        {!! ($product->type == 'booking') ?  __('shop::app.products.book-now') :__('shop::app.products.add-to-cart')!!}
    </button>

{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}
