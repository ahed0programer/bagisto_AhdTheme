@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.address.index.page-title') }}
@endsection

@section('account-content')
    <div class="account-layout col-xl-9 col-lg-9">
        <div class="account-head">
            <span class="back-icon">
                <a href="{{ route('shop.customer.profile.index') }}">
                    <i class="icon icon-menu-back"></i>
                </a>
            </span>

            <span class="account-heading">{{ __('shop::app.customer.account.address.index.title') }}</span>

            @if (! $addresses->isEmpty())
                <span class="account-action">
                    <a href="{{ route('shop.customer.addresses.create') }}">{{ __('shop::app.customer.account.address.index.add') }}</a>
                </span>
            @else
                <span></span>
            @endif

            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.address.list.before', ['addresses' => $addresses]) !!}

        <div class="account-table-content">
            @if ($addresses->isEmpty())
                <div>{{ __('shop::app.customer.account.address.index.empty') }}</div>
                <br/>
                <a href="{{ route('shop.customer.addresses.create') }}">{{ __('shop::app.customer.account.address.index.add') }}</a>
            @else
                <div class="address-holder">
                    @foreach ($addresses as $address)
                        <div class="address-card">
                            <div class="details" style="width: 100%;">
                                <span class="bold">{{ auth()->guard('customer')->user()->name }}</span>
 
                                <ul class="address-card-list">
                                    <li class="expaned_li">
                                        {{ $address->company_name }}
                                    </li>

                                    <li class="expaned_li">
                                        {{ $address->first_name }}
                                    </li>

                                    <li class="expaned_li">
                                        {{ $address->last_name }}
                                    </li>

                                    <li class="expaned_li">
                                        {{ $address->address1 }}
                                    </li>

                                    <li class="expaned_li">
                                        {{ $address->city }}
                                    </li>

                                    <li class="expaned_li">
                                        {{ $address->state }}
                                    </li>

                                    <li class="expaned_li">
                                        {{ core()->country_name($address->country) }} {{ $address->postcode }}
                                    </li>

                                    <li class="expaned_li">
                                        {{ __('shop::app.customer.account.address.index.contact') }}
                                        : {{ $address->phone }}
                                    </li>
                                </ul>
                                <div class="control-links" style="padding: 5px">
                                    <span>
                                        <a class="btn btn-md btn-primary" href="{{ route('shop.customer.addresses.edit', $address->id) }}">
                                            {{ __('shop::app.customer.account.address.index.edit') }}
                                        </a>
                                    </span>
        
                                    <span>
                                        <a class="btn btn-md btn-warning" href="javascript:void(0);" onclick="deleteAddress('{{ __('shop::app.customer.account.address.index.confirm-delete') }}', '{{ $address->id }}')">
                                            {{ __('shop::app.customer.account.address.index.delete') }}
                                        </a>
        
                                        <form id="deleteAddressForm{{ $address->id }}" action="{{ route('shop.customer.addresses.delete', $address->id) }}" method="post">
                                            @method('delete')
                                            @csrf
                                        </form>
                                    </span>
                                </div>
                            </div>
                        </div>   
                    @endforeach
                </div>
            @endif
        </div>

        {!! view_render_event('bagisto.shop.customers.account.address.list.after', ['addresses' => $addresses]) !!}
    </div>
@endsection

@push('css')
<style>
    .checkout-step-heading{
        font-size: 24px;
    }

    .address-card{
        display: flex;
        background-color: #eee;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 2px;
        padding: 10px;
        margin: 5px;
    }

    .control-links{
        margin-top: 20px;
        border-top: 1px solid;
    }

    @media  (min-width: 991px){
        .expaned_li{
            display: inline-block;
            width:45%;
        }
    }
</style>
@endpush

@push('scripts')
    <script>
        function deleteAddress(message, addressId) {
            if (! confirm(message)) {
                return;
            }

            $(`#deleteAddressForm${addressId}`).submit();
        }
    </script>
@endpush
