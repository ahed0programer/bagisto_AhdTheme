@if (count($categories))
    <!--================ Hero Carousel start =================-->
    <section class="section-margin mt-0">
        <div class="owl-carousel owl-theme hero-carousel">
            @foreach ($categories as  $key => $category)
                <div class="hero-carousel__slide">
                    <img src="{{bagisto_asset("storage/".$category->image)}}" alt="" class="img-fluid">
                    <a href="{{ route('shop.productOrCategory.index', $category->slug) }}" class="hero-carousel__slideOverlay">
                        <h3>{{ $category->name }}</h3>
                        <p>Accessories Item</p>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
    <!--================ Hero Carousel end =================-->
@endif