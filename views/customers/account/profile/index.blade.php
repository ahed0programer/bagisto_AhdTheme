@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.profile.index.title') }}
@endsection

@section('account-content')
    <div class="col-xl-9 col-lg-8 col-md-7">
        <div class="filter-bar d-flex justify-content-between  ">
            {{-- <span class="back-icon"><a href="{{ route('shop.customer.profile.index') }}"><i class="icon icon-menu-back"></i></a></span> --}}
            <span class="mr-auto ">{{ __('shop::app.customer.account.profile.index.title') }}</span>

            <span class="account-action">
                <a style="color: inherit" href="{{ route('shop.customer.profile.edit') }}">{{ __('shop::app.customer.account.profile.index.edit') }}</a>
            </span>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.profile.view.before', ['customer' => $customer]) !!}

        <div class="row">
            <div class="account-table-content col-xl-4 col-lg-8 col-md-7" >
                <img style="width: 100%" src="{{bagisto_asset("images/ahd_icons/account.profile.png")}}" alt="">
            </div>
            <div class="account-table-content col-xl-7 col-lg-8 col-md-7" >
                <table style="color: #5E5E5E; width:100%">
                    <thead>
                        <th>
                            <td style="width:70%"></td>
                            <td></td>
                        </th>
                    </thead>
                    <tbody>
                        {!! view_render_event('bagisto.shop.customers.account.profile.view.table.before', ['customer' => $customer]) !!}

                        <tr>
                            <td class="account-table-first-td">{{ __('shop::app.customer.account.profile.fname') }} :</td>
                            <td class="account-table-second-td">{{ $customer->first_name }}</td>
                        </tr>

                        {!! view_render_event('bagisto.shop.customers.account.profile.view.table.first_name.after', ['customer' => $customer]) !!}

                        <tr>
                            <td class="account-table-first-td">{{ __('shop::app.customer.account.profile.lname') }} :</td>
                            <td class="account-table-second-td">{{ $customer->last_name }}</td>
                        </tr>

                        {!! view_render_event('bagisto.shop.customers.account.profile.view.table.last_name.after', ['customer' => $customer]) !!}

                        <tr>
                            <td class="account-table-first-td">{{ __('shop::app.customer.account.profile.gender') }} :</td>
                            <td class="account-table-second-td">{{$customer->gender? __($customer->gender):"unavaliable" }}</td>
                        </tr>

                        {!! view_render_event('bagisto.shop.customers.account.profile.view.table.gender.after', ['customer' => $customer]) !!}

                        <tr>
                            <td class="account-table-first-td">{{ __('shop::app.customer.account.profile.dob') }} :</td>
                            <td class="account-table-second-td">{{ $customer->date_of_birth? $customer->date_of_birth : "unavaliable" }}</td>
                        </tr>

                        {!! view_render_event('bagisto.shop.customers.account.profile.view.table.date_of_birth.after', ['customer' => $customer]) !!}

                        <tr>
                            <td class="account-table-first-td">{{ __('shop::app.customer.account.profile.email') }} :</td>
                            <td class="account-table-second-td"><a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a></td>
                        </tr>

                        {!! view_render_event('bagisto.shop.customers.account.profile.view.table.after', ['customer' => $customer]) !!}
                    </tbody>
                </table>

                <hr>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#myModal">
                        {{ __('shop::app.customer.account.address.index.delete') }}
                    </button>
                </div>
                
                
                <form method="POST" action="{{ route('shop.customer.profile.destroy') }}" @submit.prevent="onSubmit">
                    @csrf
                    <modal class="modal"  id="deleteProfile" :is-open="modalIds.deleteProfile">
                        <h3 slot="header">{{ __('shop::app.customer.account.address.index.enter-password') }}</h3>

                        <div slot="body">
                            <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                                <label for="password" class="required">{{ __('admin::app.users.users.password') }}</label>
                                <input type="password" v-validate="'required|min:6|max:18'" class="control" id="password" name="password" data-vv-as="&quot;{{ __('admin::app.users.users.password') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                            </div>

                            <div class="page-action">
                                <button type="submit"  class="btn btn-lg btn-primary mt-10">
                                {{ __('shop::app.customer.account.address.index.delete') }}
                                </button>
                            </div>
                        </div>
                    </modal>
                </form>
            </div>
        </div>

        <!-- The Modal -->
        <form method="POST" action="{{ route('shop.customer.profile.destroy') }}" @submit.prevent="onSubmit">
            @csrf
            <div class="modal" id="myModal" style="top:30%">
                <div class="modal-dialog">
                    <div class="modal-content">
                
                        <!-- Modal Header -->
                        <div class="modal-header">
                        <h4 class="modal-title">{{ __('shop::app.customer.account.address.index.enter-password') }}</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                                <input 
                                    placeholder="{{ __('admin::app.users.users.password') }}"
                                    type="password" min="6" max="18" required 
                                    class="form-control" 
                                    name="password" 
                                >
                                <span class="control-error">@if($errors->has("password")) {{ $errors->first('password') }} @endif</span>
                            </div>
                        </div>
                
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <div class="page-action">
                                <button type="submit"  class="btn btn-md btn-warning mt-10">
                                    {{ __('shop::app.customer.account.address.index.delete') }}
                                </button>
                            </div>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div> 
        </form>   

        {!! view_render_event('bagisto.shop.customers.account.profile.view.after', ['customer' => $customer]) !!}
    </div>
@endsection
