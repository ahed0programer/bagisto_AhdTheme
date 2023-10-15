@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.cart.title') }}
@stop

@push('css')
    <style>
        .table_action{
            display: block;
        }
    </style>
@endpush

@section('content-wrapper')

  <!--================Cart Area =================-->
  <section class="cart_area">
    @if ($cart)
        <div class="container">
            <div class="cart_inner">
                <form
                    method="POST"
                    action="{{  route('shop.cart.remove.all.items') }}">
                    @csrf
                    <button
                        type="submit"
                        onclick="return confirm('{{ __('shop::app.checkout.cart.confirm-action') }}')"
                        class="btn btn-warning">
                        {{ __('shop::app.checkout.cart.remove-all-items') }}
                    </button>
                </form>
                <div class="table-responsive">        
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <form id="update_cart" action="{{ route('shop.checkout.cart.update') }}" method="POST" @submit.prevent="onSubmit">
                            @csrf
                            <tbody>
                                @foreach ($cart->items as $key => $item)
                                    @php
                                        $productBaseImage = $item->product->getTypeInstance()->getBaseImage($item);

                                        if (is_null($item->product->url_key)) {
                                            if (! is_null($item->product->parent)) {
                                                $url_key = $item->product->parent->url_key;
                                            }
                                        } else {
                                            $url_key = $item->product->url_key;
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="media">
                                                <div class="d-flex">
                                                    <a href="{{ route('shop.productOrCategory.index', $url_key) }}"><img src="{{ $productBaseImage['medium_image_url'] }}" alt="cannot load image"></a>
                                                </div>
                                                <div class="media-body">
                                                    <p><a href="{{ route('shop.productOrCategory.index', $url_key) }}">
                                                        {{ $item->product->name }}
                                                    </a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>{{ core()->currency($item->base_price) }}</h5>
                                        </td>
                                        <td>
                                            @if ($item->product->getTypeInstance()->showQuantityBox() === true)
                                                <div class="product_count">
                                                    <input type="text" name="qty[]" id="qty{{$item->id}}" maxlength="12" max="" value="{{$item->quantity}}" title="Quantity:"
                                                        class="input-text qty">
                                                    <button onclick="increase_quantity({{ $item->id}} ); return false;"
                                                        class="increase items-count" type="button">
                                                        <span class="material-symbols-outlined" style="color :black;">
                                                            add
                                                        </span>
                                                    </button>
                                                    <button onclick="decrease_quantity({{ $item->id }}); return false;"
                                                        class="reduced items-count" type="button">
                                                        <span class="material-symbols-outlined" style="translate: 0px 3px; color :black;">
                                                            remove
                                                        </span>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <h5 id="subtotal_product{{$item->id}}">{{ core()->currency($item->base_price*$item->quantity) }}</h5>
                                            <input id="pro_price{{$item->id}}" type="hidden" value="{{$item->base_price}}">
                                        </td>
                                        <td>
                                            <span class="table_action">
                                                <a href="{{ route('shop.checkout.cart.remove', $item->id) }}" onclick="removeLink('{{ __('shop::app.checkout.cart.cart-remove-action') }}')">
                                                    {{ __('shop::app.checkout.cart.remove-link') }}
                                                </a>
                                            </span>

                                            @auth('customer')
                                                @if ((bool) core()->getConfigData('general.content.shop.wishlist_option'))
                                                    <span class="table_action towishlist">
                                                            @if (
                                                                $item->parent_id != 'null'
                                                                || $item->parent_id != null
                                                            )
                                                            <a
                                                                href="javascript:void(0);"
                                                                onclick="moveToWishlist('{{ __('shop::app.checkout.cart.cart-remove-action') }}', '{{ route('shop.move_to_wishlist', $item->id) }}')">
                                                                    {{ __('shop::app.checkout.cart.move-to-wishlist') }}
                                                                </a>
                                                        @else
                                                            <a
                                                                href="javascript:void(0);"
                                                                onclick="moveToWishlist('{{ __('shop::app.checkout.cart.cart-remove-action') }}', '{{ route('shop.move_to_wishlist', $item->child->id) }}')">
                                                                    {{ __('shop::app.checkout.cart.move-to-wishlist') }}
                                                                </a>
                                                        @endif
                                                        </span>
                                                @endif
                                            @endauth
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <tr class="bottom_button">
                                    <td>
                                        {{-- <a class="button" href="#">Update Cart</a> --}}
                                        @if ($cart->hasProductsWithQuantityBox())
                                            <button type="submit" class="btn btn-primary" id="update_cart_button">
                                                {{ __('shop::app.checkout.cart.update-cart') }}
                                            </button>
                                        @endif
                                    </td>
                                    <td></form>

                                    </td>
                                    <td>

                                    </td>
                                    <td></td>
                                    <td>
                                        @if(!$cart->coupon_code)
                                            <form class="coupon-form" id="coupon_form">
                                                @csrf
                                                <div class="cupon_text d-flex align-items-center">
                                                    <input id="coupon_code_id" type="text" required name="code" placeholder="{{  __('shop::app.checkout.onepage.enter-coupon-code') }}">
                                                    <span id="code_error" style="color: red"></span>
                                                    <button id='applyCoupon' class="primary-btn" >{{ __('shop::app.checkout.onepage.apply-coupon') }}</button>
                                                    {{-- <a class="button" href="#">Have a Coupon?</a> --}}
                                                </div>
                                            </form>
                                        @else
                                            <form  method="post" action="{{ route('shop.checkout.coupon.remove.coupon') }}">
                                                @csrf
                                                @method('delete')
                                                <div class="cupon_text d-flex align-items-center">
                                                    <button id='applyCoupon' class="primary-btn" >{{  __('shop::app.checkout.total.remove-coupon') }}</button>
                                                    <span id="code_error" style="color: red"></span>
                                                    {{-- <a href="{{ route('admin.urls.delete') }}" 
                                                        class="btn btn-sm pd-x-15 btn-danger btn-uppercase" 
                                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                                        <i class="wd-10 mg-r-5" data-feather="trash"></i>
                                                        remove coupon 
                                                    </a> --}}
                                                </div>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @include('shop::checkout.total.summary', ['cart' => $cart])
                                    </td>
                                    <td>
                                        <h5>coupon code : {{$cart->coupon_code}}</h5>
                                    </td>
                                    <td></td>
                                    <td>
                                        <h5>Subtotal</h5>
                                    </td>
                                    <td>
                                        <h5>$2160.00</h5>
                                    </td>
                                    
                                </tr>
                                <tr class="shipping_area">
                                    <td class="d-none d-md-block">

                                    </td>
                                    <td>

                                    </td>
                                    <td></td>
                                    <td>
                                        <h5>Shipping</h5>
                                    </td>
                                    <td>
                                        <div class="shipping_box">
                                            <ul class="list">
                                                <li><a href="#">Flat Rate: $5.00</a></li>
                                                <li><a href="#">Free Shipping</a></li>
                                                <li><a href="#">Flat Rate: $10.00</a></li>
                                                <li class="active"><a href="#">Local Delivery: $2.00</a></li>
                                            </ul>
                                            <h6>Calculate Shipping <i class="fa fa-caret-down" aria-hidden="true"></i></h6>
                                            <select class="shipping_select">
                                                <option value="1">Bangladesh</option>
                                                <option value="2">India</option>
                                                <option value="4">Pakistan</option>
                                            </select>
                                            <select class="shipping_select">
                                                <option value="1">Select a State</option>
                                                <option value="2">Select a State</option>
                                                <option value="4">Select a State</option>
                                            </select>
                                            <input type="text" placeholder="Postcode/Zipcode">
                                            <a class="gray_btn" href="#">Update Details</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="out_button_area">
                                    <td class="d-none-l">

                                    </td>
                                    <td class="">

                                    </td>
                                    <td></td>
                                    <td>

                                    </td>
                                    <td>
                                        <div class="checkout_btn_inner d-flex align-items-center">
                                            <a href="{{ route('shop.home.index') }}" class="gray_btn">{{  __('shop::app.checkout.cart.continue-shopping') }}</a>
                                            <a class="primary-btn ml-2" href="{{ route('shop.checkout.onepage.index') }}">{{ __('shop::app.checkout.cart.proceed-to-checkout') }}</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        
                    </table>
                   
                </div>
            </div>
        </div>
        @else

        <div class="title">
            {{ __('shop::app.checkout.cart.title') }}
        </div>

        <div class="cart-content">
            <p>
                {{ __('shop::app.checkout.cart.empty') }}
            </p>

            <p style="display: inline-block;">
                <a style="display: inline-block;" href="{{ route('shop.home.index') }}" class="btn btn-lg btn-primary">{{ __('shop::app.checkout.cart.continue-shopping') }}</a>
            </p>
        </div>

        @endif

</section>  
<!--================End Cart Area =================-->

    <section class="cart">
        @if ($cart)
            <div class="title">
                {{ __('shop::app.checkout.cart.title') }}
            </div>

            <div class="cart-content">
                <div class="left-side">
                    <div style="display: flex;justify-content: flex-end;margin-bottom: 20px;">
                        <form
                            method="POST"
                            action="{{  route('shop.cart.remove.all.items') }}">
                            @csrf
                            <button
                                type="submit"
                                onclick="return confirm('{{ __('shop::app.checkout.cart.confirm-action') }}')"
                                class="btn btn-lg btn-primary">

                                {{ __('shop::app.checkout.cart.remove-all-items') }}
                            </button>
                        </form>
                    </div>
                    <form action="{{ route('shop.checkout.cart.update') }}" method="POST" @submit.prevent="onSubmit">

                        <div class="cart-item-list" style="margin-top: 0">
                            @csrf
                            @foreach ($cart->items as $key => $item)
                                @php
                                    $productBaseImage = $item->product->getTypeInstance()->getBaseImage($item);

                                    if (is_null($item->product->url_key)) {
                                        if (! is_null($item->product->parent)) {
                                            $url_key = $item->product->parent->url_key;
                                        }
                                    } else {
                                        $url_key = $item->product->url_key;
                                    }
                                @endphp

                                <div class="item mt-5">
                                    <div class="item-image" style="margin-right: 15px;">
                                        <a href="{{ route('shop.productOrCategory.index', $url_key) }}"><img src="{{ $productBaseImage['medium_image_url'] }}" alt="" /></a>
                                    </div>

                                    <div class="item-details">

                                        {!! view_render_event('bagisto.shop.checkout.cart.item.name.before', ['item' => $item]) !!}

                                        <div class="item-title">
                                            <a href="{{ route('shop.productOrCategory.index', $url_key) }}">
                                                {{ $item->product->name }}
                                            </a>
                                        </div>

                                        {!! view_render_event('bagisto.shop.checkout.cart.item.name.after', ['item' => $item]) !!}


                                        {!! view_render_event('bagisto.shop.checkout.cart.item.price.before', ['item' => $item]) !!}

                                        <div class="price">
                                            {{ core()->currency($item->base_price) }}
                                        </div>

                                        {!! view_render_event('bagisto.shop.checkout.cart.item.price.after', ['item' => $item]) !!}


                                        {!! view_render_event('bagisto.shop.checkout.cart.item.options.before', ['item' => $item]) !!}

                                        @if (isset($item->additional['attributes']))
                                            <div class="item-options">

                                                @foreach ($item->additional['attributes'] as $attribute)
                                                    <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                @endforeach

                                            </div>
                                        @endif

                                        {!! view_render_event('bagisto.shop.checkout.cart.item.options.after', ['item' => $item]) !!}


                                        {!! view_render_event('bagisto.shop.checkout.cart.item.quantity.before', ['item' => $item]) !!}

                                        <div class="misc">
                                            @if ($item->product->getTypeInstance()->showQuantityBox() === true)
                                                <quantity-changer
                                                    :control-name="'qty[{{$item->id}}]'"
                                                    quantity="{{$item->quantity}}">
                                                </quantity-changer>
                                            @endif

                                            <span class="remove">
                                                <a href="{{ route('shop.checkout.cart.remove', $item->id) }}" onclick="removeLink('{{ __('shop::app.checkout.cart.cart-remove-action') }}')">{{ __('shop::app.checkout.cart.remove-link') }}</a></span>

                                            @auth('customer')
                                                @if ((bool) core()->getConfigData('general.content.shop.wishlist_option'))
                                                    <span class="towishlist">
                                                            @if (
                                                                $item->parent_id != 'null'
                                                                || $item->parent_id != null
                                                            )
                                                            <a
                                                                href="javascript:void(0);"
                                                                onclick="moveToWishlist('{{ __('shop::app.checkout.cart.cart-remove-action') }}', '{{ route('shop.move_to_wishlist', $item->id) }}')">
                                                                    {{ __('shop::app.checkout.cart.move-to-wishlist') }}
                                                                </a>
                                                        @else
                                                            <a
                                                                href="javascript:void(0);"
                                                                onclick="moveToWishlist('{{ __('shop::app.checkout.cart.cart-remove-action') }}', '{{ route('shop.move_to_wishlist', $item->child->id) }}')">
                                                                    {{ __('shop::app.checkout.cart.move-to-wishlist') }}
                                                                </a>
                                                        @endif
                                                        </span>
                                                @endif
                                            @endauth
                                        </div>

                                        {!! view_render_event('bagisto.shop.checkout.cart.item.quantity.after', ['item' => $item]) !!}

                                        @if (! cart()->isItemHaveQuantity($item))
                                            <div class="error-message mt-15">
                                                * {{ __('shop::app.checkout.cart.quantity-error') }}
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.cart.controls.after', ['cart' => $cart]) !!}

                        <div class="misc-controls">
                            <a href="{{ route('shop.home.index') }}" class="link">{{ __('shop::app.checkout.cart.continue-shopping') }}</a>

                            <div style="display:flex;">
                                @if ($cart->hasProductsWithQuantityBox())
                                    <button type="submit" class="btn btn-lg btn-primary" id="update_cart_button">
                                        {{ __('shop::app.checkout.cart.update-cart') }}
                                    </button>
                                @endif

                                @if (! cart()->hasError())
                                    @php
                                        $minimumOrderAmount = (float) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;
                                    @endphp

                                    <proceed-to-checkout
                                        href="{{ route('shop.checkout.onepage.index') }}"
                                        add-class="btn btn-lg btn-primary"
                                        text="{{ __('shop::app.checkout.cart.proceed-to-checkout') }}"
                                        is-minimum-order-completed="{{ $cart->checkMinimumOrder() }}"
                                        minimum-order-message="{{ __('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]) }}">
                                    </proceed-to-checkout>
                                @endif
                            </div>
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.cart.controls.after', ['cart' => $cart]) !!}
                    </form>
                </div>

                <div class="right-side">
                    {!! view_render_event('bagisto.shop.checkout.cart.summary.after', ['cart' => $cart]) !!}

                    @include('shop::checkout.total.summary', ['cart' => $cart])

                    <coupon-component>
                        
                    </coupon-component>

                    {!! view_render_event('bagisto.shop.checkout.cart.summary.after', ['cart' => $cart]) !!}
                </div>
            </div>

            @include ('shop::products.view.cross-sells')

        @else

            <div class="title">
                {{ __('shop::app.checkout.cart.title') }}
            </div>

            <div class="cart-content">
                <p>
                    {{ __('shop::app.checkout.cart.empty') }}
                </p>

                <p style="display: inline-block;">
                    <a style="display: inline-block;" href="{{ route('shop.home.index') }}" class="btn btn-lg btn-primary">{{ __('shop::app.checkout.cart.continue-shopping') }}</a>
                </p>
            </div>

        @endif
    </section>

@endsection

@push('scripts')
    @include('shop::checkout.cart.coupon')
 
    {{-- quantity controller script  --}}
    <script>
        const qty ={};

        // Assuming you have a form with id "myForm" and an array called "dataArray"
        const form = document.getElementById('update_cart');
        
        form.addEventListener('submit', (event) => {
            event.preventDefault(); // Prevent default form submission
            
            const formData = new FormData(form); // Get form data
            
            // Append array data to form data
            formData.append('qty',JSON.stringify(qty));
            
            // Get reference to the submit button
            const submitButton = document.getElementById('update_cart_button');
            
            // Update button text to "Loading" on form submission
            submitButton.innerText = 'Loading...';
            
            // Send AJAX request
            fetch("{{ route('shop.checkout.cart.update') }}", {
            method: 'POST',
            body: formData,
            })
            
            .then(response => response.json())
            .then(data => {
                // Handle the response from the server
            
                // Revert button text after receiving response
                submitButton.innerText = "{{ __('shop::app.checkout.cart.update-cart') }}";
                window.location.href = window.location.href;
            })
            .catch(error => {
                // Handle any errors
                alert(error);
            
                // Revert button text on error
                submitButton.innerText = 'error';
            });
        });

        // decrease the quantity of an item
        function decrease_quantity(id){
            var result = document.getElementById('qty'+id);
            var subtotal_product = document.getElementById('subtotal_product'+id);
            var price = document.getElementById('pro_price'+id);
            var sst = result.value;
            if( !isNaN( sst ) && sst > 1 ) {
                result.value--;
            }

            qty[""+id]= result.value;
            
            // var r = price.value*result.value
            // subtotal_product.innerText = '{{core()->currency(floatval("'+r+'"))}}';
        }

        // increase the quantity of an item
        function increase_quantity(id){

            var result = document.getElementById('qty'+id);
            var subtotal_product = document.getElementById('subtotal_product'+id);
            var price = document.getElementById('pro_price'+id);

            var sst = result.value;
            if( !isNaN( sst )) {
                result.value++;
            }

            qty[""+id]= result.value;

            // var r = price.value*result.value
            // subtotal_product.innerText = '{{core()->currency()}}';
            // console.log(r);
        }

    </script>

    {{-- coupon script appling --}}
    <script>
        const couponForm = document.getElementById("coupon_form");

        couponForm.addEventListener('submit',(event)=>{
            event.preventDefault();
            var code = document.getElementById("coupon_code_id");
            var codError = document.getElementById("code_error");
            const applyButton = document.getElementById('applyCoupon');

            applyButton.innerText = "appling...";//__('shop.checkout.cart.coupon.appling')
            applyButton.disabled = true;

            formData = new FormData(couponForm);

            fetch("{{ route('shop.checkout.cart.coupon.apply') }}",{
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data=>{
                applyButton.disabled = false;
                if(data.success){
                    alert(data.message);
                    applyButton.innerText = "apply"
                    window.flashMessages = [{'type': 'alert-error', 'message': data.message}];
                }else{
                    codError.innerText = data.message;
                    window.flashMessages = [{'type': 'alert-error', 'message': data.message}];
                }

            }).catch(error=>{
                alert(error);
                codError.innerText = "{{ __('shop::app.checkout.total.invalid-coupon') }}"
            })
        })
    </script>
@endpush 


