@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

{!! view_render_event('bagisto.shop.products.list.toolbar.before') !!}

@push('css')
    <style>
        .filter_box{
            border-radius: 0px;
            border: 1px solid #ccc;
            padding-right: 50px;
            height: 38px;
            color: #999999;
            background-color: #fff
        }
    </style>
@endpush

<div>
    <span>
        {{ __('shop::app.products.pager-info', ['showing' => $products->firstItem() . '-' . $products->lastItem(), 'total' => $products->total()]) }}
    </span>
</div>
<div class="d-flex">
    <div class="view-mode">
        @if ($toolbarHelper->isModeActive('grid'))
            <span class="grid-view">
                <i class="icon grid-view-icon"></i>
            </span>
        @else
            <a href="{{ $toolbarHelper->getModeUrl('grid') }}" class="grid-view" aria-label="Grid">
                <i class="icon grid-view-icon"></i>
            </a>
        @endif
    
        @if ($toolbarHelper->isModeActive('list'))
            <span class="list-view">
                <i class="icon list-view-icon"></i>
            </span>
        @else
            <a href="{{ $toolbarHelper->getModeUrl('list') }}" class="list-view" aria-label="list">
                <i class="icon list-view-icon"></i>
            </a>
        @endif
    </div>
    
    <div class="sorting">
        <label for="sort-by-toolbar">{{ __('shop::app.products.sort-by') }}</label>
    
        <select class="filter_box" onchange="window.location.href = this.value" id="sort-by-toolbar">
    
            @foreach ($toolbarHelper->getAvailableOrders() as $key => $order)
    
                <option value="{{ $toolbarHelper->getOrderUrl($key) }}" {{ $toolbarHelper->isOrderCurrent($key) ? 'selected' : '' }}>
                    {{ __('shop::app.products.' . $order) }}
                </option>
    
            @endforeach
    
        </select>
    </div>
    
    <div class="sorting mr-auto">
        <label for="show-toolbar">{{ __('shop::app.products.show') }}</label>
    
        <select class="filter_box" onchange="window.location.href = this.value" id="show-toolbar">
    
            @foreach ($toolbarHelper->getAvailableLimits() as $limit)
    
                <option value="{{ $toolbarHelper->getLimitUrl($limit) }}" {{ $toolbarHelper->isLimitCurrent($limit) ? 'selected' : '' }}>
                    {{ $limit }} 
                </option>
    
            @endforeach
    
        </select>
    </div>

    {{-- search box --}}
    <div>
        <div class="input-group filter-bar-search">
            <input type="text" placeholder="Search">
            <div class="input-group-append">
            <button type="button"><i class="ti-search"></i></button>
            </div>
        </div>
    </div> 
</div>

{{-- <span class="sort-filter">
    <i class="icon sort-icon" id="sort" ></i>
    <i class="icon filter-icon" id="filter"></i>
</span> --}}


    

{!! view_render_event('bagisto.shop.products.list.toolbar.after') !!}


<div class="responsive-layred-filter mb-20">
    <layered-navigation></layered-navigation>
</div>