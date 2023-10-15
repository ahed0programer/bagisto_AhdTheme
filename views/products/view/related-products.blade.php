<?php
    $relatedProducts = $product->related_products()->get();
?>

@if ($relatedProducts->count())
    <div class="container">
        <div class="section-intro pb-60px">
            <p>Popular Item in the market</p>
            <h2> <span class="section-intro__style">{{ __('shop::app.products.related-product-title') }}</span></h2>
            <span class="border-bottom"></span>
        </div>
        <div class="row mt-30">
            @foreach ($relatedProducts as $related_product)
                @include ('shop::products.list.card', ['product' => $related_product])
            @endforeach
        </div>
    </div>      
@endif