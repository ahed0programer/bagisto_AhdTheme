{!! view_render_event('bagisto.shop.products.view.product-add.before', ['product' => $product]) !!}

@include ('shop::products.add-to-cart', ['product' => $product])

@if (core()->getConfigData('catalog.products.storefront.buy_now_button_display'))
    @include ('shop::products.buy-now')                                      
@endif

{!! view_render_event('bagisto.shop.products.view.product-add.after', ['product' => $product]) !!}