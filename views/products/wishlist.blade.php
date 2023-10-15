@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

@auth('customer')
    {!! view_render_event('bagisto.shop.products.wishlist.before') !!}

    <li>
        <form id="wishlist-{{ $product->id }}" action="{{ route('shop.customer.wishlist.add', $product->id) }}" method="POST">
            @csrf
        </form>

        <button style=" {{$wishListHelper->getWishlistProduct($product)? 'background-color: #f00':'background-color: #384aeb'}}">
            <a
                id="wishlist-changer"
                href="javascript:void(0);"
                onclick="document.getElementById('wishlist-{{ $product->id }}').submit();"
            >
            @if ($wishListHelper->getWishlistProduct($product))
                <i 
                style=" font-size: 15px;
                        color: #fff;
                        vertical-align: middle;" 
                class="ti-heart">
                </i>
            @else
            <i 
                style=" font-size: 15px;
                        color: #fff;
                        vertical-align: middle;" 
                class="ti-heart">
            </i>
            @endif
            </a>
        </button>
    </li>    

    {!! view_render_event('bagisto.shop.products.wishlist.after') !!}
@endauth
