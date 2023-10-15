@extends('shop::layouts.master')

@section('page_title')
    {{ trim($category->meta_title) != "" ? $category->meta_title : $category->name }}
@stop

@section('seo')
    <meta name="description" content="{{ trim($category->meta_description) != "" ? $category->meta_description : \Illuminate\Support\Str::limit(strip_tags($category->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $category->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.categories.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getCategoryJsonLd($category) !!}
        </script>
    @endif
@stop

@section('content-wrapper')

    @inject ('productRepository', 'Webkul\Product\Repositories\ProductRepository')

	<!-- ================ category section start ================= -->		  
    <section class="section-margin--small mb-5">
        <div class="container">
          @if (in_array($category->display_mode, [null, 'products_only', 'products_and_description']))
                @include ('shop::products.list.layered-navigation')
          @endif
          <div class="row justify-content-center">
            <div class="hero-image mb-35">
                @if (!is_null($category->image))
                    <img class="logo" src="{{ $category->image_url }}" alt="" />
                @endif
            </div>

            @if (in_array($category->display_mode, [null, 'description_only', 'products_and_description']))
                @if ($category->description)
                    <div class="category-description">
                        {!! $category->description !!}
                    </div>
                @endif
            @endif
          </div>
          <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-5">
              <div class="sidebar-categories">
                <div class="head">Browse Categories</div>
                <ul class="main-categories">
                  <li class="common-filter">
                    <form action="#">
                        <?php
                        $categories = [];
                            foreach (app('Webkul\Category\Repositories\CategoryRepository')->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id) as $category){
                                if ($category->slug)
                                    array_push($categories, $category);
                            }
                        ?>
                        <ul>
                            @foreach ($categories as $category)
                                <li class="filter-list">
                                    <a href="{{ route('shop.productOrCategory.index', $category->slug) }}">{{$category->name}}<span></span></a>
                                </li>
                            @endforeach
                        </ul>
                    </form>
                  </li>
                </ul>
              </div>
              <div class="sidebar-filter">
                <div class="top-filter-head">Product Filters</div>
                <div class="common-filter">
                  <div class="head">Brands</div>
                  <form action="#">
                    <ul>
                      <li class="filter-list"><input class="pixel-radio" type="radio" id="apple" name="brand"><label for="apple">Apple<span>(29)</span></label></li>
                      <li class="filter-list"><input class="pixel-radio" type="radio" id="asus" name="brand"><label for="asus">Asus<span>(29)</span></label></li>
                      <li class="filter-list"><input class="pixel-radio" type="radio" id="gionee" name="brand"><label for="gionee">Gionee<span>(19)</span></label></li>
                      <li class="filter-list"><input class="pixel-radio" type="radio" id="micromax" name="brand"><label for="micromax">Micromax<span>(19)</span></label></li>
                      <li class="filter-list"><input class="pixel-radio" type="radio" id="samsung" name="brand"><label for="samsung">Samsung<span>(19)</span></label></li>
                    </ul>
                  </form>
                </div>
                <div class="common-filter">
                  <div class="head">Color</div>
                  <form action="#">
                    <ul>
                      <li class="filter-list"><input class="pixel-radio" type="radio" id="black" name="color"><label for="black">Black<span>(29)</span></label></li>
                      <li class="filter-list"><input class="pixel-radio" type="radio" id="balckleather" name="color"><label for="balckleather">Black
                          Leather<span>(29)</span></label></li>
                      <li class="filter-list"><input class="pixel-radio" type="radio" id="blackred" name="color"><label for="blackred">Black
                          with red<span>(19)</span></label></li>
                      <li class="filter-list"><input class="pixel-radio" type="radio" id="gold" name="color"><label for="gold">Gold<span>(19)</span></label></li>
                      <li class="filter-list"><input class="pixel-radio" type="radio" id="spacegrey" name="color"><label for="spacegrey">Spacegrey<span>(19)</span></label></li>
                    </ul>
                  </form>
                </div>
                <div class="common-filter">
                  <div class="head">Price</div>
                  <div class="price-range-area">
                    <div id="price-range"></div>
                    <div class="value-wrapper d-flex">
                      <div class="price">Price:</div>
                      <span>$</span>
                      <div id="lower-value"></div>
                      <div class="to">to</div>
                      <span>$</span>
                      <div id="upper-value"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-7">
              <!-- Start Filter Bar -->
              <div class="filter-bar flex-wrap align-items-center">
                    @if (in_array($category->display_mode, [null, 'products_only', 'products_and_description']))
                        <?php $products = $productRepository->getAll($category->id); ?>
                        @include ('shop::products.list.toolbar')
                    @endif
              </div>
              <!-- End Filter Bar -->

              <!-- Start Best Seller -->
              @if(count($products)) 
                <section class="lattest-product-area pb-40 category-list">
                  <div class="row">
                      @foreach ($products as $productFlat)
                        @include ('shop::products.list.card', ['product' => $productFlat])
                      @endforeach
                  </div>
                  <div class="bottom-toolbar">
                    {{ $products->appends(request()->input())->links() }}
                  </div>
                </section>
              @else
                <div class="product-list empty align-items-center text-center">
                    <h2>{{ __('shop::app.products.whoops') }}!</h2>

                    <p>
                        {{ __('shop::app.products.empty') }}
                    </p>
                </div>
            @endif
              <!-- End Best Seller -->
            </div>
          </div>
        </div>
    </section>
  <!-- ================ category section end ================= -->	

    

    {{-- <div class="main">
        {!! view_render_event('bagisto.shop.products.index.before', ['category' => $category]) !!}

        <div class="category-container">

            @if (in_array($category->display_mode, [null, 'products_only', 'products_and_description']))
                @include ('shop::products.list.layered-navigation')
            @endif

            <div class="category-block" @if ($category->display_mode == 'description_only') style="width: 100%" @endif>
                <div class="hero-image mb-35">
                    @if (!is_null($category->image))
                        <img class="logo" src="{{ $category->image_url }}" alt="" />
                    @endif
                </div>

                @if (in_array($category->display_mode, [null, 'description_only', 'products_and_description']))
                    @if ($category->description)
                        <div class="category-description">
                            {!! $category->description !!}
                        </div>
                    @endif
                @endif-

                @if (in_array($category->display_mode, [null, 'products_only', 'products_and_description']))
                    <?php $products = $productRepository->getAll($category->id); ?>

                    @include ('shop::products.list.toolbar')

                    @if ($products->count())

                        @inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

                        @if ($toolbarHelper->getCurrentMode() == 'grid')
                            <div class="product-grid-3">
                                @foreach ($products as $productFlat)

                                    @include ('shop::products.list.card', ['product' => $productFlat])

                                @endforeach
                            </div>
                        @else
                            <div class="product-list">
                                @foreach ($products as $productFlat)

                                    @include ('shop::products.list.card', ['product' => $productFlat])

                                @endforeach
                            </div>
                        @endif

                        {!! view_render_event('bagisto.shop.products.index.pagination.before', ['category' => $category]) !!}

                        <div class="bottom-toolbar">
                            {{ $products->appends(request()->input())->links() }}
                        </div>

                        {!! view_render_event('bagisto.shop.products.index.pagination.after', ['category' => $category]) !!}

                    @else
                        <div class="product-list empty">
                            <h2>{{ __('shop::app.products.whoops') }} ss</h2>

                            <p>
                                {{ __('shop::app.products.empty') }}
                            </p>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {!! view_render_event('bagisto.shop.products.index.after', ['category' => $category]) !!}
    </div> --}}
@stop

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.responsive-layred-filter').css('display','none');
            $(".sort-icon, .filter-icon").on('click', function(e){
                var currentElement = $(e.currentTarget);
                if (currentElement.hasClass('sort-icon')) {
                    currentElement.removeClass('sort-icon');
                    currentElement.addClass('icon-menu-close-adj');
                    currentElement.next().removeClass();
                    currentElement.next().addClass('icon filter-icon');
                    $('.responsive-layred-filter').css('display','none');
                    $('.pager').css('display','flex');
                    $('.pager').css('justify-content','space-between');
                } else if (currentElement.hasClass('filter-icon')) {
                    currentElement.removeClass('filter-icon');
                    currentElement.addClass('icon-menu-close-adj');
                    currentElement.prev().removeClass();
                    currentElement.prev().addClass('icon sort-icon');
                    $('.pager').css('display','none');
                    $('.responsive-layred-filter').css('display','block');
                    $('.responsive-layred-filter').css('margin-top','10px');
                } else {
                    currentElement.removeClass('icon-menu-close-adj');
                    $('.responsive-layred-filter').css('display','none');
                    $('.pager').css('display','none');
                    if ($(this).index() == 0) {
                        currentElement.addClass('sort-icon');
                    } else {
                        currentElement.addClass('filter-icon');
                    }
                }
            });
        });
    </script>
@endpush