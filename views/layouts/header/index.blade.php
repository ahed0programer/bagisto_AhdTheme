<?php
    $term = request()->input('term');
    $image_search = request()->input('image-search');

    if (! is_null($term)) {
        $serachQuery = 'term='.request()->input('term');
    }
?>

<!--================ Start Header Menu Area =================-->
<header class="header_area">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand logo_h" href="{{ route('shop.home.index') }}">
                    @if ($logo = core()->getCurrentChannel()->logo_url)
                    <img class="logo" src="{{ $logo }}" alt="" />
                    @endif
                </a>

                @php
                    $showCompare = (bool) core()->getConfigData('general.content.shop.compare_option');

                    $showWishlist = (bool) core()->getConfigData('general.content.shop.wishlist_option');
                @endphp

                <ul class="nav-shop" style="padding-bottom: 0%">
                    {{-- <li class="nav-item"><button><i class="ti-search"></i></button></li> --}}
                    <li class="nav-item">
                        @include('shop::checkout.cart.mini-cart')
                    </li>
                    {{-- <li class="nav-item"><a class="button button-header" href="#">Buy Now</a></li> --}}
                </ul> 

                <div class="form-container ">
                    <div class="control-group">
                        <select class="control locale-switcher" id="locale-switcher" onchange="window.location.href = this.value" @if (count(core()->getCurrentChannel()->locales) == 1) disabled="disabled" @endif>

                            @foreach (core()->getCurrentChannel()->locales()->orderBy('name')->get() as $locale)
                                @if (isset($serachQuery))
                                    <option value="?{{ $serachQuery }}&locale={{ $locale->code }}" {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>{{ $locale->name }}</option>
                                @else
                                    <option value="?locale={{ $locale->code }}" {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>{{ $locale->code }}</option>
                                @endif
                            @endforeach

                        </select>
                    </div>
                </div>

                <div class="form-container">
                    <div class="control-group">
                        <select class="control locale-switcher" id="currency-switcher" onchange="window.location.href = this.value">
    
                            @foreach (core()->getCurrentChannel()->currencies as $currency)
                                @if (isset($serachQuery))
                                    <option value="?{{ $serachQuery }}&currency={{ $currency->code }}" {{ $currency->code == core()->getCurrentCurrencyCode() ? 'selected' : '' }}>{{ $currency->code }}</option>
                                @else
                                    <option value="?currency={{ $currency->code }}" {{ $currency->code == core()->getCurrentCurrencyCode() ? 'selected' : '' }}>{{ $currency->code }}</option>
                                @endif
                            @endforeach
    
                        </select>
                    </div>
                </div>
                

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="collapse navbar-collapse offset justify-content-between" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto mr-auto">
                        <li class="nav-item active"><a class="nav-link" href="index.html">Home</a></li>
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                aria-expanded="false">Shop</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a class="nav-link" href="category.html">Shop Category</a></li>
                                <li class="nav-item"><a class="nav-link" href="single-product.html">Product Details</a></li>
                                <li class="nav-item"><a class="nav-link" href="checkout.html">Product Checkout</a></li>
                                <li class="nav-item"><a class="nav-link" href="confirmation.html">Confirmation</a></li>
                                <li class="nav-item"><a class="nav-link" href="cart.html">Shopping Cart</a></li>
                            </ul>
                        </li>
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                aria-expanded="false">Blog</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a class="nav-link" href="blog.html">Blog</a></li>
                                <li class="nav-item"><a class="nav-link" href="single-blog.html">Blog Details</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                    </ul>

                    <ul class="search-container">
                        <li class="search-group">
                            <form role="search" action="{{ route('shop.search.index') }}" method="GET" >
                                <div>
                                <label for="search-bar" style="position: absolute; z-index: -1;">Search</label>
                                <input
                                    required
                                    name="term"
                                    type="search"
                                    value="{{ ! $image_search ? $term : '' }}"
                                    class="search-field"
                                    id="search-bar"
                                    placeholder="{{ __('shop::app.header.search-text') }}"
                                >
                                <div class="search-icon-wrapper" style="display: inline;
                                border: blue solid;
                                padding: 3px;">
                                    <button class="" style="background: none; border: none;" aria-label="Search">
                                        <i class="ti-search"></i>
                                    </button>
                                </div>
                                </div>

                                <image-search-component></image-search-component>
        
                                
                            </form>
                        </li>
                    </ul>

                    @guest('customer')
                        <ul class="nav navbar-nav menu_nav ml-auto mr-auto">
                            <li>
                                {{-- <div>
                                    <label style="color: #9e9e9e; font-weight: 700; text-transform: uppercase; font-size: 15px;">
                                        {{ __('shop::app.header.title') }}
                                    </label>
                                </div>

                                <div style="margin-top: 5px;">
                                    <span style="font-size: 12px;">{{ __('shop::app.header.dropdown-text') }}</span>
                                </div> --}}

                                <div class="button-group">
                                    <a class="btn btn-primary btn-md" href="{{ route('shop.customer.session.index') }}" style="color: #ffffff">
                                        {{ __('shop::app.header.sign-in') }}
                                    </a>

                                    <a class="btn btn-primary btn-md" href="{{ route('shop.customer.register.index') }}" style="float: right; color: #ffffff">
                                        {{ __('shop::app.header.sign-up') }}
                                    </a>
                                </div>
                            </li>

                            <hr>

                            {{-- @if ($showWishlist)
                                <li>
                                    <a class="btn btn-success btn-md" href="{{ route('shop.customer.wishlist.index') }}">
                                        {{ __('shop::app.header.wishlist') }}
                                    </a>
                                </li>
                            @endif --}}

                            {{-- @if ($showCompare)
                                <li>
                                    <a href="{{ route('velocity.product.compare') }}">
                                        {{ __('shop::app.customer.compare.text') }}
                                    </a>
                                </li>
                            @endif --}}
                        </ul>
                    @endguest

                    @auth('customer')

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            {{ auth()->guard('customer')->user()->first_name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="accountDropdown">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('shop.customer.profile.index') }}">{{ __('shop::app.header.profile') }}</a>
                            </li>
                    
                            @if ($showWishlist)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('shop.customer.wishlist.index') }}">
                                        {{ __('shop::app.header.wishlist') }}
                                    </a>
                                </li>
                            @endif
                    
                            @if ($showCompare)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('velocity.customer.product.compare') }}">
                                        {{ __('shop::app.customer.compare.text') }}
                                    </a>
                                    <span class="badge badge-primary">4</span> <!-- You can replace 4 with the actual count -->
                                </li>
                            @endif
                    
                            <li class="nav-item">
                                <form id="customerLogout" action="{{ route('shop.customer.session.destroy') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <a class="nav-link" href="{{ route('shop.customer.session.destroy') }}"
                                   onclick="event.preventDefault(); document.getElementById('customerLogout').submit();">
                                    {{ __('shop::app.header.logout') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    
                    @endauth

                </div>
            </div>
        </nav>
    </div>
</header>
{{-- ---------------------------------------------------- --}}

{{-- <header class="header_area">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                
                @php
                    $showCompare = (bool) core()->getConfigData('general.content.shop.compare_option');

                    $showWishlist = (bool) core()->getConfigData('general.content.shop.wishlist_option');
                @endphp

                

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto mr-auto">
                        <li class="nav-item active"><a class="nav-link" href="index.html">Home</a></li>
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                aria-expanded="false">Shop</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a class="nav-link" href="category.html">Shop Category</a></li>
                                <li class="nav-item"><a class="nav-link" href="single-product.html">Product Details</a></li>
                                <li class="nav-item"><a class="nav-link" href="checkout.html">Product Checkout</a></li>
                                <li class="nav-item"><a class="nav-link" href="confirmation.html">Confirmation</a></li>
                                <li class="nav-item"><a class="nav-link" href="cart.html">Shopping Cart</a></li>
                            </ul>
                        </li>
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                aria-expanded="false">Blog</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a class="nav-link" href="blog.html">Blog</a></li>
                                <li class="nav-item"><a class="nav-link" href="single-blog.html">Blog Details</a></li>
                            </ul>
                        </li>
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                aria-expanded="false">Pages</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a class="nav-link" href="login.html">Login</a></li>
                                <li class="nav-item"><a class="nav-link" href="register.html">Register</a></li>
                                <li class="nav-item"><a class="nav-link" href="tracking-order.html">Tracking</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
<!--================ End Header Menu Area =================--> --}}

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/mobilenet" defer></script>

    <script type="text/x-template" id="image-search-component-template">
        <div v-if="image_search_status">
            <label class="image-search-container" :for="'image-search-container-' + _uid">
                <i class="icon camera-icon"></i>

                <input type="file" :id="'image-search-container-' + _uid" ref="image_search_input" v-on:change="uploadImage()"/>

                <img :id="'uploaded-image-url-' +  + _uid" :src="uploaded_image_url" alt="" width="20" height="20" style="display:none" />
            </label>
        </div>
    </script>

    <script>

        Vue.component('image-search-component', {

            template: '#image-search-component-template',

            data: function() {
                return {
                    uploaded_image_url: '',
                    image_search_status: "{{core()->getConfigData('general.content.shop.image_search') == '1' ? 'true' : 'false'}}" == 'true'
                }
            },

            methods: {
                uploadImage: function() {
                    var imageInput = this.$refs.image_search_input;

                    if (imageInput.files && imageInput.files[0]) {
                        if (imageInput.files[0].type.includes('image/')) {
                            var self = this;

                            if (imageInput.files[0].size <= 2000000) {
                                self.$root.showLoader();

                                var formData = new FormData();

                                formData.append('image', imageInput.files[0]);

                                axios.post("{{ route('shop.image.search.upload') }}", formData, {headers: {'Content-Type': 'multipart/form-data'}})
                                    .then(function(response) {
                                        self.uploaded_image_url = response.data;

                                        var net;

                                        async function app() {
                                            var analysedResult = [];

                                            var queryString = '';

                                            net = await mobilenet.load();

                                            const imgElement = document.getElementById('uploaded-image-url-' +  + self._uid);

                                            try {
                                                const result = await net.classify(imgElement);

                                                result.forEach(function(value) {
                                                    queryString = value.className.split(',');

                                                    if (queryString.length > 1) {
                                                        analysedResult = analysedResult.concat(queryString)
                                                    } else {
                                                        analysedResult.push(queryString[0])
                                                    }
                                                });
                                            } catch (error) {
                                                self.$root.hideLoader();

                                                window.flashMessages = [
                                                    {
                                                        'type': 'alert-error',
                                                        'message': "{{ __('shop::app.common.error') }}"
                                                    }
                                                ];

                                                self.$root.addFlashMessages();
                                            };

                                            localStorage.searched_image_url = self.uploaded_image_url;

                                            queryString = localStorage.searched_terms = analysedResult.join('_');

                                            self.$root.hideLoader();

                                            window.location.href = "{{ route('shop.search.index') }}" + '?term=' + queryString + '&image-search=1';
                                        }

                                        app();
                                    })
                                    .catch(function(error) {
                                        self.$root.hideLoader();

                                        window.flashMessages = [
                                            {
                                                'type': 'alert-error',
                                                'message': "{{ __('shop::app.common.error') }}"
                                            }
                                        ];

                                        self.$root.addFlashMessages();
                                    });
                            } else {

                                imageInput.value = '';

                                        window.flashMessages = [
                                            {
                                                'type': 'alert-error',
                                                'message': "{{ __('shop::app.common.image-upload-limit') }}"
                                            }
                                        ];

                                self.$root.addFlashMessages();

                            }
                        } else {
                            imageInput.value = '';

                            alert('Only images (.jpeg, .jpg, .png, ..) are allowed.');
                        }
                    }
                }
            }
        });

    </script>

    <script>
        $(document).ready(function() {

            $('body').delegate('#search, .icon-menu-close, .icon.icon-menu', 'click', function(e) {
                toggleDropdown(e);
            });

            @auth('customer')
                @php
                    $compareCount = app('Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository')
                        ->count([
                            'customer_id' => auth()->guard('customer')->user()->id,
                        ]);
                @endphp

                let comparedItems = JSON.parse(localStorage.getItem('compared_product'));
                $('#compare-items-count').html({{ $compareCount }});
            @endauth

            @guest('customer')
                let comparedItems = JSON.parse(localStorage.getItem('compared_product'));
                $('#compare-items-count').html(comparedItems ? comparedItems.length : 0);
            @endguest

            function toggleDropdown(e) {
                var currentElement = $(e.currentTarget);

                if (currentElement.hasClass('icon-search')) {
                    currentElement.removeClass('icon-search');
                    currentElement.addClass('icon-menu-close');
                    $('#hammenu').removeClass('icon-menu-close');
                    $('#hammenu').addClass('icon-menu');
                    $("#search-responsive").css("display", "block");
                    $("#header-bottom").css("display", "none");
                } else if (currentElement.hasClass('icon-menu')) {
                    currentElement.removeClass('icon-menu');
                    currentElement.addClass('icon-menu-close');
                    $('#search').removeClass('icon-menu-close');
                    $('#search').addClass('icon-search');
                    $("#search-responsive").css("display", "none");
                    $("#header-bottom").css("display", "block");
                } else {
                    currentElement.removeClass('icon-menu-close');
                    $("#search-responsive").css("display", "none");
                    $("#header-bottom").css("display", "none");
                    if (currentElement.attr("id") == 'search') {
                        currentElement.addClass('icon-search');
                    } else {
                        currentElement.addClass('icon-menu');
                    }
                }
            }
        });
    </script>
@endpush