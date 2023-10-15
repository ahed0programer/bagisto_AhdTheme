@extends('shop::layouts.master')

@section('page_title')
    {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
@stop

@section('seo')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {{ app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) }}
        </script>
    @endif

    <?php $productBaseImage = product_image()->getProductBaseImage($product); ?>

    <meta name="twitter:card" content="summary_large_image" />

    <meta name="twitter:title" content="{{ $product->name }}" />

    <meta name="twitter:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta name="twitter:image:alt" content="" />

    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />

    <meta property="og:title" content="{{ $product->name }}" />

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta property="og:url" content="{{ route('shop.productOrCategory.index', $product->url_key) }}" />
@stop

@section('content-wrapper')

    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <section class="product-detail">

        <div class="layouter">
            <product-view>
                <div class="form-container">
                    @include ('shop::products.view.gallery')
                </div>
            </product-view>
        </div>
    </section>

    <!--================Single Product Area =================-->
	<div class="product_image_area">
		<div class="container">
			<div class="row s_product_inner">
				<div class="col-lg-6">
					<div class="owl-carousel owl-theme s_Product_carousel">
                        @foreach (product_image()->getGalleryImages($product) as $Image)
                            <div class="single-prd-item">
                                <img class="img-fluid" src="{{ $Image['large_image_url'] }}" alt=""> 
                            </div>
                        @endforeach
					</div>

                    {{-- <!-- Carousel -->
                    <div id="gllery_imgs" class="carousel slide" data-bs-ride="carousel">

                        <!-- Indicators/dots -->
                        <div class="carousel-indicators">
                            @foreach (product_image()->getGalleryImages($product) as $key=>$Image)
                                <button type="button" data-bs-target="#gllery_imgs" data-bs-slide-to="{{$key}}" class="active"></button>      
                            @endforeach
                        </div>
                
                        <!-- The slideshow/carousel -->
                        <div class="carousel-inner">
                            @php
                                $i=0;
                            @endphp
                            @foreach (product_image()->getGalleryImages($product) as $Image)
                                <div class="carousel-item {{$i==0 ? 'active':''}}">
                                    <img class="d-block w-100" src="{{ $Image['large_image_url'] }}" alt=""> 
                                </div>
                            @endforeach
                        </div>
                    
                        <!-- Left and right controls/icons -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#gllery_imgs" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#gllery_imgs" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        </button>
                    </div> --}}
				</div>
				<div class="col-lg-5 offset-lg-1">
					<div class="s_product_text">
                        @if (
                            Webkul\Tax\Helpers\Tax::isTaxInclusive()
                            && $product->getTypeInstance()->getTaxCategory()
                        )
                            <div>
                                {{ __('shop::app.products.tax-inclusive') }}
                            </div>
                        @endif

						<h3>{{ $product->name }}</h3>

						<h2>@include ('shop::products.price', ['product' => $product])</h2>
                        @include ('shop::products.review', ['product' => $product])

						<ul class="list">
							<li><a class="active" href="#"><span>Category</span> : Household</a></li>
                            @if ($product->brand) <li><a class="active" href="#"><span>Brand : </span> : {{$product->brand}}</a></li> @endif
							<li><a href="#"><span>Availibility</span> : @include ('shop::products.view.stock', ['product' => $product])</li>
						</ul>
                        
                        {{-- product description --}}
                        <div>
                            {!! $product->short_description !!}
                        </div>
						
						<div class="product_count"> 
                            <form action="{{ route('shop.cart.add', $product->id) }}" method="POST"> 
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                @if ($product->getTypeInstance()->showQuantityBox())
                                    <label for="qty">Quantity:</label>
                                    <div style="display: inline-block;">
                                        <input type="text" name="quantity" id="sst" maxlength="12" min="1" value="1" title="Quantity:" class="input-text qty">
                                        
                                        <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                                class="increase items-count qty_changer" type="button"><i class="ti-angle-left"></i>
                                        </button>

                                        <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 1 ) result.value--;return false;"
                                                class="reduced items-count qty_changer" type="button"><i class="ti-angle-right"></i>
                                        </button>
                                    </div>
                                    @include ('shop::products.view.product-add')
                                @else
                                    <input type="hidden" name="quantity" value="1">
                                    @include ('shop::products.view.product-add')
                                @endif
                            </form>
						</div>

						<div class="card_area d-flex align-items-center">
							<a class="icon_btn" href="#"><i class="lnr lnr lnr-diamond"></i></a>
							
                            @inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')
                            @auth('customer')
                                @if ((bool) core()->getConfigData('general.content.shop.wishlist_option'))
                                    <form id="wishlist-{{ $product->id }}" action="{{ route('shop.customer.wishlist.add', $product->id) }}" method="POST">
                                        @csrf
                                    </form>
                                    <a class="icon_btn"
                                        @if ($wishListHelper->getWishlistProduct($product))
                                            style="background-color: red ; color : #fff;"
                                        @else
                                            style="background-color: #e8f0f2;"
                                        @endif
                                        href="javascript:void(0);"
                                        onclick="document.getElementById('wishlist-{{ $product->id }}').submit();"
                                    >
                                        <i class="lnr lnr ti-heart"></i>
                                    </a>
                                @endif
                            @endauth
						</div>

                        @if (count($product->getTypeInstance()->getCustomerGroupPricingOffers()))
                            <div class="discount-offers">
                                @foreach ($product->getTypeInstance()->getCustomerGroupPricingOffers() as $offer)
                                    <p> {{ $offer }} </p>
                                @endforeach
                            </div>
                        @endif
					</div>
				</div>
                <div class="col">
                    <div class="details">
                        @include ('shop::products.view.configurable-options')

                        @include ('shop::products.view.downloadable')

                        @include ('shop::products.view.grouped-products')

                        @include ('shop::products.view.bundle-options')
                    </div>
                </div>
			</div>
		</div>
	</div>
	<!--================End Single Product Area =================-->

    <!--================Product Description Area =================-->
	<section class="product_description_area">
		<div class="container">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                        {{ __('shop::app.products.description') }}
                    </a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile"aria-selected="false">
                        {{ __('shop::app.products.specification') }}
                    </a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" id="review-tab" data-bs-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false">
                        {{ __('shop::app.products.reviews-title') }}
                    </a>
				</li>
			</ul>

			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
					{!! $product->description !!}
				</div>
				<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
					<div class="table-responsive">
						<table class="table">
							<tbody>
								<tr>
									<td>
										<h5>Width</h5>
									</td>
									<td>
										<h5>{{$product->width}}mm</h5>
									</td>
								</tr>
								<tr>
									<td>
										<h5>Height</h5>
									</td>
									<td>
										<h5>{{$product->height}}mm</h5>
									</td>
								</tr>
								<tr>
									<td>
										<h5>Depth</h5>
									</td>
									<td>
										<h5>85mm</h5>
									</td>
								</tr>
								<tr>
									<td>
										<h5>Weight</h5>
									</td>
									<td>
										<h5>{{$product->weight}}gm</h5>
									</td>
								</tr>
								<tr>
									<td>
										<h5>Quality checking</h5>
									</td>
									<td>
										<h5>yes</h5>
									</td>
								</tr>
								<tr>
									<td>
										<h5>Freshness Duration</h5>
									</td>
									<td>
										<h5>03days</h5>
									</td>
								</tr>
								<tr>
									<td>
										<h5>When packeting</h5>
									</td>
									<td>
										<h5>Without touch of hand</h5>
									</td>
								</tr>
								<tr>
									<td>
										<h5>Each Box contains</h5>
									</td>
									<td>
										<h5>60pcs</h5>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
                    @include ('shop::products.view.attributes')
				</div>
				<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
					<div class="row">
						<div class="col-lg-6">
							<div class="comment_list">
								<div class="review_item">
									<div class="media">
										<div class="d-flex">
											<img src="img/product/review-1.png" alt="">
										</div>
										<div class="media-body">
											<h4>Blake Ruiz</h4>
											<h5>12th Feb, 2018 at 05:56 pm</h5>
											<a class="reply_btn" href="#">Reply</a>
										</div>
									</div>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
										dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
										commodo</p>
								</div>
								<div class="review_item reply">
									<div class="media">
										<div class="d-flex">
											<img src="img/product/review-2.png" alt="">
										</div>
										<div class="media-body">
											<h4>Blake Ruiz</h4>
											<h5>12th Feb, 2018 at 05:56 pm</h5>
											<a class="reply_btn" href="#">Reply</a>
										</div>
									</div>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
										dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
										commodo</p>
								</div>
								<div class="review_item">
									<div class="media">
										<div class="d-flex">
											<img src="img/product/review-3.png" alt="">
										</div>
										<div class="media-body">
											<h4>Blake Ruiz</h4>
											<h5>12th Feb, 2018 at 05:56 pm</h5>
											<a class="reply_btn" href="#">Reply</a>
										</div>
									</div>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
										dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
										commodo</p>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="review_box">
								<h4>Post a comment</h4>
								<form class="row contact_form" action="contact_process.php" method="post" id="contactForm" novalidate="novalidate">
									<div class="col-md-12">
										<div class="form-group">
											<input type="text" class="form-control" id="name" name="name" placeholder="Your Full name">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<input type="text" class="form-control" id="number" name="number" placeholder="Phone Number">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<textarea class="form-control" name="message" id="message" rows="1" placeholder="Message"></textarea>
										</div>
									</div>
									<div class="col-md-12 text-right">
										<button type="submit" value="submit" class="btn primary-btn">Submit Now</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade show active" id="review" role="tabpanel" aria-labelledby="review-tab">
					@include ('shop::products.view.reviews')
				</div>
			</div>
		</div>
	</section>
	<!--================End Product Description Area =================-->




	<!--================ Start related Product area =================-->  
	<section class="related-product-area section-margin--small mt-0">
        @include ('shop::products.view.related-products')
	</section>

    <section class="related-product-area section-margin--small mt-0">
         @include ('shop::products.view.up-sells')
        {{-- {{$product}} --}}
	</section>

	<!--================ end related Product area =================--> 

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}
@endsection


@push('scripts')

    <script type="text/x-template" id="product-view-template">
        <form method="POST" id="product-form" action="{{ route('shop.cart.add', $product->id) }}" @click="onSubmit($event)">

            <input type="hidden" name="is_buy_now" v-model="is_buy_now">

            <slot></slot>
        </form>
    </script>

    <script type="text/x-template" id="quantity-changer-template">
        <div class="quantity control-group" :class="[errors.has(controlName) ? 'has-error' : '']">
            <label class="required">{{ __('shop::app.products.quantity') }}</label>

            <span class="quantity-container">
                <button type="button" class="decrease" @click="decreaseQty()">-</button>

                <input
                    ref="quantityChanger"
                    :name="controlName"
                    :model="qty"
                    class="control"
                    v-validate="validations"
                    data-vv-as="&quot;{{ __('shop::app.products.quantity') }}&quot;"
                    @keyup="setQty($event)">

                <button type="button" class="increase" @click="increaseQty()">+</button>
            </span>

            <span class="control-error" v-if="errors.has(controlName)">@{{ errors.first(controlName) }}</span>
        </div>
    </script>

    <script>

        Vue.component('product-view', {

            template: '#product-view-template',

            inject: ['$validator'],

            data: function() {
                return {
                    is_buy_now: 0,
                }
            },

            methods: {
                onSubmit: function(e) {
                    if (e.target.getAttribute('type') != 'submit')
                        return;

                    e.preventDefault();

                    var this_this = this;

                    this.$validator.validateAll().then(function (result) {
                        if (result) {
                            this_this.is_buy_now = e.target.classList.contains('buynow') ? 1 : 0;

                            setTimeout(function() {
                                document.getElementById('product-form').submit();
                            }, 0);
                        }
                    });
                }
            }
        });

        Vue.component('quantity-changer', {
            template: '#quantity-changer-template',

            inject: ['$validator'],

            props: {
                controlName: {
                    type: String,
                    default: 'quantity'
                },

                quantity: {
                    type: [Number, String],
                    default: 1
                },

                minQuantity: {
                    type: [Number, String],
                    default: 1
                },

                validations: {
                    type: String,
                    default: 'required|numeric|min_value:1'
                }
            },

            data: function() {
                return {
                    qty: this.quantity
                }
            },

            mounted: function() {
                this.$refs.quantityChanger.value = this.qty > this.minQuantity
                    ? this.qty
                    : this.minQuantity;
            },

            watch: {
                qty: function (val) {
                    this.$refs.quantityChanger.value = ! isNaN(parseFloat(val)) ? val : 0;

                    this.qty = ! isNaN(parseFloat(val)) ? this.qty : 0;

                    this.$emit('onQtyUpdated', this.qty);

                    this.$validator.validate();
                }
            },

            methods: {
                setQty: function({ target }) {
                    this.qty = parseInt(target.value);
                },

                decreaseQty: function() {
                    if (this.qty > this.minQuantity)
                        this.qty = parseInt(this.qty) - 1;
                },

                increaseQty: function() {
                    this.qty = parseInt(this.qty) + 1;
                }
            }
        });

        window.onload = function() {
            var thumbList = document.getElementsByClassName('thumb-list')[0];
            var thumbFrame = document.getElementsByClassName('thumb-frame');
            var productHeroImage = document.getElementsByClassName('product-hero-image')[0];

            if (thumbList && productHeroImage) {

                for(let i=0; i < thumbFrame.length ; i++) {
                    thumbFrame[i].style.height = (productHeroImage.offsetHeight/4) + "px";
                    thumbFrame[i].style.width = (productHeroImage.offsetHeight/4)+ "px";
                }

                if (screen.width > 720) {
                    thumbList.style.width = (productHeroImage.offsetHeight/4) + "px";
                    thumbList.style.minWidth = (productHeroImage.offsetHeight/4) + "px";
                    thumbList.style.height = productHeroImage.offsetHeight + "px";
                }
            }

            window.onresize = function() {
                if (thumbList && productHeroImage) {

                    for(let i=0; i < thumbFrame.length; i++) {
                        thumbFrame[i].style.height = (productHeroImage.offsetHeight/4) + "px";
                        thumbFrame[i].style.width = (productHeroImage.offsetHeight/4)+ "px";
                    }

                    if (screen.width > 720) {
                        thumbList.style.width = (productHeroImage.offsetHeight/4) + "px";
                        thumbList.style.minWidth = (productHeroImage.offsetHeight/4) + "px";
                        thumbList.style.height = productHeroImage.offsetHeight + "px";
                    }
                }
            }
        };
    </script>
@endpush
