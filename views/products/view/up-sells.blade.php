{!! view_render_event('bagisto.shop.products.view.up-sells.after', ['product' => $product]) !!}

<?php
    $productUpSells = $product->up_sells()->get();
?>

@if ($productUpSells->count())
    <div class="container">
        <div class="section-intro pb-60px">
            <p>Popular Item in the market</p>
            <h2><span class="section-intro__style"> {{ __('shop::app.products.up-sell-title') }} </span></h2>
            <span class="border-bottom"></span>
        </div>
        <div class="row mt-30">
            @foreach ($productUpSells as $up_sell_product)
                @include ('shop::products.list.card', ['product' => $up_sell_product])
            @endforeach
        </div>
    </div>
@endif

{!! view_render_event('bagisto.shop.products.view.up-sells.after', ['product' => $product]) !!}