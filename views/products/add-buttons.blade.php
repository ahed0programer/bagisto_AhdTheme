@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

<li>
    <form action="{{ route('shop.cart.add', $product->id) }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="quantity" value="1">
        <button {{ $product->isSaleable() ? '' : 'disabled' }} style="color: white;">
            {!! ($product->type == 'booking') ?  __('shop::app.products.book-now') :'<i class="ti-shopping-cart"></i>' !!}
        </button>
    </form>
</li>

@if ((bool) core()->getConfigData('general.content.shop.wishlist_option'))
    @include('shop::products.wishlist')
@endif

@if ((bool) core()->getConfigData('general.content.shop.compare_option'))
    @include('shop::products.compare', [
        'productId' => $product->id
    ])
@endif
