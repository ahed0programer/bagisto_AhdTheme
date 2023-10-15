@extends('shop::layouts.master')

@section('content-wrapper')
    <section class="section-margin--small mb-5">
        <div class="container">
            <div>
                @if (request()->route()->getName() !== 'shop.customer.profile.index')
                    @if (Breadcrumbs::exists())
                        {{ Breadcrumbs::render() }}
                    @endif
                @endif
            </div>
            <div class="row ">
                @include('shop::customers.account.partials.sidemenu')

                @yield('account-content')
            </div> 
        </div>
    </section>
@endsection
