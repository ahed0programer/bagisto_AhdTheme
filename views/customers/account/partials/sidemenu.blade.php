
<div class="col-xl-3 col-lg-4 col-md-5" style="margin-bottom: 20px;">
    @foreach ($menu->items as $menuItem)
        <div class="sidebar-categories ">
            <div class="head">{{ trans($menuItem['name']) }}</div>
            <ul class="main-categories" >
                <li class="common-filter">
                    <ul>
                        @if (! (bool) core()->getConfigData('general.content.shop.wishlist_option'))
                        @php
                            unset($menuItem['children']['compare']);
                        @endphp
                        @endif

                        @if (! (bool) core()->getConfigData('general.content.shop.compare_option'))
                            @php
                                unset($menuItem['children']['wishlist']);
                            @endphp
                        @endif

                        @foreach ($menuItem['children'] as $subMenuItem)
                            <li class="menu-item {{ $menu->getActive($subMenuItem) }}">
                                    <img style="max-width: 30px" src="{{bagisto_asset("images/ahd_icons/".$subMenuItem['key'].".png")}}" alt="">
                                
                                <a href="{{ $subMenuItem['url'] }}">
                                    {{ trans($subMenuItem['name']) }}
                                </a>
                                <i class="icon angle-right-icon"></i>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>
    @endforeach
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".icon.icon-arrow-down.right").on('click', function(e){
                var currentElement = $(e.currentTarget);
                if (currentElement.hasClass('icon-arrow-down')) {
                    $(this).parents('.menu-block').find('.menubar').show();
                    currentElement.removeClass('icon-arrow-down');
                    currentElement.addClass('icon-arrow-up');
                } else {
                    $(this).parents('.menu-block').find('.menubar').hide();
                    currentElement.removeClass('icon-arrow-up');
                    currentElement.addClass('icon-arrow-down');
                }
            });
        });
    </script>
@endpush