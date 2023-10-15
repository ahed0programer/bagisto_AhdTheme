
@php
    $cart = cart()->getCart();
@endphp


@if ($cart)
    @php
        $items = $cart->items;
    @endphp

    <div class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#demo">
        <a class="cart-link" href="{{ route('shop.checkout.cart.index') }}">
            <button>
                <img class="cart_img" src="{{bagisto_asset("images/ahd_icons/shopping-cart.png")}}" alt="">
                <span class="nav-shop__circle">{{count($items)}} </span>
            </button>
        </a>
    </div>

    <div id="demo" class="dropdown-list collapse" style="position :fixed; width: 300px;">
        <div class="dropdown-container" style="background-color:  #e8e8e8; padding: 10px;">
            <div class="dropdown-header">
                <p class="heading">
                    {{ __('shop::app.checkout.cart.cart-subtotal') }} -

                    {!! view_render_event('bagisto.shop.checkout.cart-mini.subtotal.before', ['cart' => $cart]) !!}

                    @if (Webkul\Tax\Helpers\Tax::isTaxInclusive())
                        <b>{{ core()->currency($cart->base_grand_total) }}</b>
                    @else
                        <b>{{ core()->currency($cart->base_sub_total) }}</b>
                    @endif

                    {!! view_render_event('bagisto.shop.checkout.cart-mini.subtotal.after', ['cart' => $cart]) !!}
                </p>
            </div>

            <div class="dropdown-content" style="overflow-y: auto;
                                                overflow-x:hidden;
                                                height: 300px;">
                @foreach ($items as $item)
                    <div class="item row" style="margin-bottom: 15px ">
                        <div class="item-image col" >
                            @php
                                $images = $item->product->getTypeInstance()->getBaseImage($item);
                            @endphp

                            <a href="{{ route('shop.productOrCategory.index', $item->product->url_key) }}" title="{{ $item->name }}">
                                <img src="{{ $images['small_image_url'] }}"  alt=""/>
                            </a>
                        </div>

                        <div class="item-details col">
                            {!! view_render_event('bagisto.shop.checkout.cart-mini.item.name.before', ['item' => $item]) !!}

                            <div class="item-name">
                                <a href="{{ route('shop.productOrCategory.index', $item->product->url_key) }}" title="{{ $item->name }}">
                                    {{ $item->name }}
                                </a>
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.cart-mini.item.name.after', ['item' => $item]) !!}

                            {!! view_render_event('bagisto.shop.checkout.cart-mini.item.options.before', ['item' => $item]) !!}

                            @if (isset($item->additional['attributes']))
                                <div class="item-options">
                                    @foreach ($item->additional['attributes'] as $attribute)
                                        <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                    @endforeach
                                </div>
                            @endif

                            {!! view_render_event('bagisto.shop.checkout.cart-mini.item.options.after', ['item' => $item]) !!}

                            {!! view_render_event('bagisto.shop.checkout.cart-mini.item.price.before', ['item' => $item]) !!}

                            <div class="item-price">
                                @if (Webkul\Tax\Helpers\Tax::isTaxInclusive())
                                    <b>{{ core()->currency($item->base_total + $item->tax_amount) }}</b>
                                @else
                                    <b>{{ core()->currency($item->base_total) }}</b>
                                @endif
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.cart-mini.item.price.after', ['item' => $item]) !!}

                            {!! view_render_event('bagisto.shop.checkout.cart-mini.item.quantity.before', ['item' => $item]) !!}

                            <div class="item-qty">Quantity : {{ $item->quantity }}</div>

                            {!! view_render_event('bagisto.shop.checkout.cart-mini.item.quantity.after', ['item' => $item]) !!}

                            <div class="item-remove">
                                <a href="{{ route('shop.checkout.cart.remove', $item->id) }}" onclick="removeLink('{{ __('shop::app.checkout.cart.cart-remove-action') }}')">{{ __('shop::app.checkout.cart.remove-link') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="dropdown-footer" style="background-color: #c8c8c8;
            text-align: center;">
                <a href="{{ route('shop.checkout.cart.index') }}">{{ __('shop::app.minicart.view-cart') }}</a>

                @php
                    $minimumOrderAmount = (float) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;
                @endphp

                <a class="primary-btn ml-2" href="{{ route('shop.checkout.onepage.index') }}">{{ __('shop::app.checkout.cart.proceed-to-checkout') }}</a>

                <proceed-to-checkout
                    href="{{ route('shop.checkout.onepage.index') }}"
                    add-class="btn btn-primary btn-lg"
                    text="{{ __('shop::app.minicart.checkout') }}"
                    is-minimum-order-completed="{{ $cart->checkMinimumOrder() }}"
                    minimum-order-message="{{ __('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]) }}"
                    style="color: white;">
                </proceed-to-checkout>
            </div>
        </div>
    </div>
@else
    <div class="dropdown-toggle">
        <a class="cart-link" href="{{ route('shop.checkout.cart.index') }}">
            <button>
                <img class="cart_img" src="{{bagisto_asset("images/ahd_icons/shopping-cart.png")}}" alt="">
                <span class="nav-shop__circle">{{ __('shop::app.minicart.zero') }} </span>
            </button>
        </a>
        <i class="icon arrow-down-icon"></i>
    </div>
@endif