<div class="col-xl-3 col-lg-3 col-sm-4">
    <div class="card">
        @php
            $image = $item->product->getTypeInstance()->getBaseImage($item);
        @endphp

        <a href="{{ route('shop.productOrCategory.index', $item->product->url_key) }}" title="{{ $item->product->name }}">
            <img class="card-img-top" src="{{ $image['medium_image_url'] }}" alt="" />
        </a>

        <div class="card-body">
            <div class="card-text">
                {{ $item->product->name }}

                @if (isset($item->additional['attributes']))
                    <div class="item-options">
                        @foreach ($item->additional['attributes'] as $attribute)
                            <b>{{ $attribute['attribute_name'] }} : </b> {{ $attribute['option_label'] }}
                            </br>
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($visibility ?? false)
                <div class="mb-2">
                    <span class="fs16">
                        {{ __('shop::app.customer.account.wishlist.visibility') }} :

                        <span class="badge badge-sm {{ $item->shared ? 'badge-success' : 'badge-danger' }}">
                            {{ $item->shared ? __('shop::app.customer.account.wishlist.public') : __('shop::app.customer.account.wishlist.private') }}
                        </span>
                    </span>
                </div>
            @endif

            <span class="stars" style="display: inline">
                @for ($i = 1; $i <= $reviewHelper->getAverageRating($item->product); $i++)
                    <span class="icon star-icon"></span>
                @endfor
            </span>
        </div>

        <div class="card-footer">
            <form id="wishlist-{{ $item->id }}" action="{{ route('shop.customer.wishlist.remove', $item->id) }}" method="POST">
                @method('DELETE')
    
                @csrf
            </form>
    
            @auth('customer')
                <a
                    class="mb-50"
                    href="javascript:void(0);"
                    onclick="document.getElementById('wishlist-{{ $item->id }}').submit();">
                    <span class="icon trash-icon"></span>
                </a>
            @endauth
    
            <a href="{{ route('shop.customer.wishlist.move', $item->id) }}" class="btn btn-primary btn-md">
                {{ __('shop::app.customer.account.wishlist.move-to-cart') }}
            </a>
        </div>
    </div>

   
</div>

<div class="horizontal-rule mb-10 mt-10"></div>