<form data-vv-scope="address-form">

    {{-- start choose one of the already saved billing addresses --}}
    <div class="form-container" id="user_billing_address" >
        <div class="form-header mb-30">
            <span class="checkout-step-heading">{{ __('shop::app.checkout.onepage.billing-address') }}</span>

            <button type="button" class="btn btn-md btn-primary" style="float: right;" onclick="new_BillingAddress(true);">
                {{ __('shop::app.checkout.onepage.new-address') }}
            </button>
        </div>

        @auth('customer')
            <div class="address-holder">
                @php
                    $addresses = auth('customer')->user()->addresses;
                @endphp
                
                @foreach ($addresses as $address)
                    <div class="address-card " v-for='(addresses, index) in this.allAddress'>
                        <div class="address-radio-check">
                            <label class="radio-container" >
                                <input type="radio" v-validate="'required'" id="billing[address_id]" name="billing[address_id]" onclick="selectBillingAdress('{{$address->id}}')">
                                <span class="checkmark"></span>
                            </label>
                        </div>

                        <div style="float: left; width: 95%;"">
                            <ul class="address-card-list">
                                <li class="mb-10">
                                    <b v-text="`${allAddress.first_name} ${allAddress.last_name}`">{{$address->first_name}}  {{$address->last_name}}</b>
                                </li>

                                <li class="expaned_li">{{$address->company_name}} </li>

                                <li class="expaned_li" >{{ $address->address1}} </li>

                                <li class="expaned_li" >{{ $address->city}}</li>

                                <li class="expaned_li" > {{$address->state}}</li>

                                <li class="">
                                    <span v-text="addresses.country" v-if="addresses.country">{{$address->country}}</span>
                                    <span v-text="addresses.postcode" v-if="addresses.postcode">{{$address->postcode}}</span>
                                </li>

                                <li>
                                    <b>{{ __('shop::app.customer.account.address.index.contact') }}</b> :
                                    <span v-text="addresses.phone">{{$address->phone}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach

                <div id="message">
                </div>

                <div class="control-group" :class="[errors.has('address-form.billing[address_id]') ? 'has-error' : '']">
                    <span
                        class="control-error"
                        id="billing.address_id"
                        v-text="errors.first('address-form.billing[address_id]')"
                        v-if="errors.has('address-form.billing[address_id]')">
                    </span>
                </div>
            </div>
        @endauth
       
        
    </div>
    {{-- end choose one of the already saved billing addresses --}}


    {{-- start new billing addres form  --}}
    <div class="form-container" id="new_billing_address"  v-if="this.new_billing_address">
        <div class="form-header">
            <span class="checkout-step-heading">{{ __('shop::app.checkout.onepage.billing-address') }}</span>

            @auth('customer')
                @if(count(auth('customer')->user()->addresses))
                    <button type="button"  class="btn btn-md btn-primary" style="float: right;" onclick="new_BillingAddress(false)">
                        {{ __('shop::app.checkout.onepage.back') }}
                    </button>
                @endif
            @endauth
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[email]') ? 'has-error' : '']">
            <label class="form-label" for="billing[email]" class="required">
                {{ __('shop::app.checkout.onepage.email') }}
            </label>

            <input
                class="form-control"
                id="billing[email]"
                type="email"
                value="@auth("customer"){{ auth('customer')->user()->email }}@endauth"
                name="billing[email]"
                v-model="address.billing.email"
                v-validate="'required|email'"
                onblur="isCustomerExist()"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.email') }}&quot;"
                @blur="isCustomerExist"
            /> 

            <span
                class="control-error"
                id="billing.email"
                v-text="errors.first('address-form.billing[email]')"
                v-if="errors.has('address-form.billing[email]')">
            </span>
        </div>

        @if (! auth()->guard('customer')->check())
            @include('shop::checkout.onepage.customer-checkout')
        @endif

        <div class="control-group" :class="[errors.has('address-form.billing[company_name]') ? 'has-error' : '']">
            <label class="form-label" for="billing[company_name]">
                {{ __('shop::app.checkout.onepage.company-name') }}
            </label>

            <input
                class="form-control"
                id="billing[company_name]"
                type="text"
                name="billing[company_name]"
                v-model="address.billing.company_name"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.company-name') }}&quot;"/>

            <span
                class="control-error"
                id="billing.company_name"
                v-text="errors.first('address-form.billing[company_name]')"
                v-if="errors.has('address-form.billing[company_name]')">
            </span>
        </div>

        <div class="row">
            <div class="control-group col" :class="[errors.has('address-form.billing[first_name]') ? 'has-error' : '']">
                <label class="form-label" for="billing[first_name]" class="required">
                    {{ __('shop::app.checkout.onepage.first-name') }}
                </label>
    
                <input
                    class="form-control"
                    id="billing[first_name]"
                    type="text"
                    value="@auth("customer"){{ auth('customer')->user()->first_name }}@endauth"
                    name="billing[first_name]"
                    v-model="address.billing.first_name"
                    v-validate="'required'"
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.first-name') }}&quot;"/>
                <span
                    class="control-error"
                    id="billing.first_name"
                    v-text="errors.first('address-form.billing[first_name]')"
                    v-if="errors.has('address-form.billing[first_name]')">
                </span>
            </div>
    
            <div class="control-group col" :class="[errors.has('address-form.billing[last_name]') ? 'has-error' : '']">
                <label class="form-label" for="billing[last_name]" class="required">
                    {{ __('shop::app.checkout.onepage.last-name') }}
                </label>
    
                <input
                    class="form-control"
                    id="billing[last_name]"
                    type="text"
                    value="@auth("customer"){{ auth('customer')->user()->last_name }}@endauth"
                    name="billing[last_name]"
                    v-model="address.billing.last_name"
                    v-validate="'required'"
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.last-name') }}&quot;"
                />
    
                <span
                    class="control-error"
                    id="billing.last_name"
                    v-text="errors.first('address-form.billing[last_name]')"
                    v-if="errors.has('address-form.billing[last_name]')">
                </span>
            </div>
        </div>
        

        <div class="control-group" :class="[errors.has('address-form.billing[address1][]') ? 'has-error' : '']">
            <label for="billing_address_0" class="required">
                {{ __('shop::app.checkout.onepage.address1') }}
            </label>

            <input
                class="form-control"
                id="billing_address_0"
                type="text"
                name="billing[address1][]"
                v-model="address.billing.address1[0]"
                v-validate="'required'"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.address1') }}&quot;"/>

            <span
                class="control-error"
                v-text="errors.first('address-form.billing[address1][]')"
                v-if="errors.has('address-form.billing[address1][]')">
            </span>
        </div>

        @if (
            core()->getConfigData('customer.address.information.street_lines')
            && core()->getConfigData('customer.address.information.street_lines') > 1
            )
            <div class="control-group" style="margin-top: -25px;">
                @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                    <input
                        class="form-control"
                        id="billing_address_{{ $i }}"
                        type="text"
                        name="billing[address1][{{ $i }}]"
                        v-model="address.billing.address1[{{$i}}]">
                @endfor
            </div>
        @endif

        <div class="row">
            <div class="control-group col" :class="[errors.has('address-form.billing[city]') ? 'has-error' : '']">
                <label for="billing[city]" class="required">
                    {{ __('shop::app.checkout.onepage.city') }}
                </label>
    
                <input
                    class="form-control"
                    id="billing[city]"
                    type="text"
                    name="billing[city]"
                    v-model="address.billing.city"
                    v-validate="'required'"
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.city') }}&quot;"/>
    
                <span
                    class="control-error"
                    id="billing.city"
                    v-text="errors.first('address-form.billing[city]')"
                    v-if="errors.has('address-form.billing[city]')">
                </span>
            </div>
    
            <div class="control-group col" :class="[errors.has('address-form.billing[country]') ? 'has-error' : '']">
                <label class="form-label" for="billing[country]" class="{{ core()->isCountryRequired() ? 'required' : '' }}">
                    {{ __('shop::app.checkout.onepage.country') }}
                </label>
    
                <select
                    class="form-select"
                    id="billing[country]"
                    type="text"
                    name="billing[country]"
                    v-validate="'{{ core()->isCountryRequired() ? 'required' : '' }}'"
                    v-model="address.billing.country"
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.country') }}&quot;">
                    <option value="none">jjjjj</option>
                    @foreach (core()->countries() as $country)
                        <option value="{{ $country->code }}">{{ $country->name }}</option>
                    @endforeach
                </select>
    
                <span
                    class="control-error"
                    id="billing.country"
                    v-text="errors.first('address-form.billing[country]')"
                    v-if="errors.has('address-form.billing[country]')">
                </span>
            </div>
        </div>
        

        <div class="control-group" :class="[errors.has('address-form.billing[state]') ? 'has-error' : '']">
            <label for="billing[state]" class="{{ core()->isStateRequired() ? 'required' : '' }}">
                {{ __('shop::app.checkout.onepage.state') }}
            </label>

            <div class="row">
                <div class="col">
                   
                    <input
                        class="form-control"
                        id="billing[state]"
                        name="billing[state]"
                        type="text"
                        v-model="address.billing.state"
                        v-validate="'{{ core()->isStateRequired() ? 'required' : '' }}'"
                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;"
                        v-if="! haveStates('billing')"/
                    >
                </div>
                <div class="col">
                    <select
                    class="form-select"
                    id="billing[state]"
                    name="billing[state]"
                    v-model="address.billing.state"
                    v-validate=""
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;"
                    v-if="haveStates('billing')">
                    <option value="">{{ __('shop::app.checkout.onepage.select-state') }}</option>
    
                    <option v-for='(state, index) in countryStates[address.billing.country]' :value="state.code" v-text="state.default_name"></option>
                    </select>
                </div>
            </div>
            <span
                class="control-error"
                id="billing.state"
                v-text="errors.first('address-form.billing[state]')"
                v-if="errors.has('address-form.billing[state]')">
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[postcode]') ? 'has-error' : '']">
            <label for="billing[postcode]" class="{{ core()->isPostCodeRequired() ? 'required' : '' }}">
                {{ __('shop::app.checkout.onepage.postcode') }}
            </label>

            <input
                class="form-control"
                id="billing[postcode]"
                type="text"
                name="billing[postcode]"
                v-model="address.billing.postcode"
                v-validate="'{{ core()->isPostCodeRequired() ? 'required' : '' }}'"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.postcode') }}&quot;"/>

            <span
                class="control-error"
                id="billing.postcode"
                v-text="errors.first('address-form.billing[postcode]')"
                v-if="errors.has('address-form.billing[postcode]')">
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[phone]') ? 'has-error' : '']">
            <label for="billing[phone]" class="required">
                {{ __('shop::app.checkout.onepage.phone') }}
            </label>

            <input
                class="form-control"
                id="billing[phone]"
                type="text"
                name="billing[phone]"
                v-validate="'required|numeric'"
                v-model="address.billing.phone"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.phone') }}&quot;"/>

            <span
                class="control-error"
                id="billing.phone"
                style="color: red;"
                v-text="errors.first('address-form.billing[phone]')"
                v-if="errors.has('address-form.billing[phone]')"></span>
        </div>

        @auth('customer')
            <div class="control-group">
                <span class="checkbox">
                    <input
                        id="billing[save_as_address]"
                        type="checkbox"
                        name="billing[save_as_address]"
                        v-model="address.billing.save_as_address"/>

                    <label class="checkbox-view" for="billing[save_as_address]"></label>

                    {{ __('shop::app.checkout.onepage.save_as_address') }}
                </span>
            </div>
        @endauth
    </div>
    {{-- end new billing address form  --}}

    @if ($cart->haveStockableItems())
        <div class="control-group">
            <span class="checkbox">
                
                <input
                    id="billing_use_for_shipping"
                    type="checkbox"
                    name="billing[use_for_shipping]"
                    v-model="address.billing.use_for_shipping"
                    checked
                />
                <label class="checkbox-view" for="billing[use_for_shipping]">{{ __('shop::app.checkout.onepage.use_for_shipping') }}</label>
            </span>
        </div>
    @endif
  
    <hr>   
    
    {{-- -------------- SHIPPING SECTION FORM ---------- --}}
    {{-- --------------------- \|/ --------------------- --}}

    @if ($cart->haveStockableItems())
        <div  id="shipping_form">
            <div class="form-container" id="user_shipping_address" v-if="! address.billing.use_for_shipping && ! this.new_shipping_address" >
                <div class="form-header mb-30">
                    <span class="checkout-step-heading">{{ __('shop::app.checkout.onepage.shipping-address') }}</span>
        
                    <button type="button" class="btn btn-md btn-primary" style="float: right;" onclick="new_ShippingAddress(true);">
                        {{ __('shop::app.checkout.onepage.new-address') }}
                    </button>
                </div>
        
                @auth('customer')
                    <div class="address-holder">
                        @foreach ($addresses as $address)
                            <div class="address-card " v-for='(addresses, index) in this.allAddress'>
                                <div class="address-radio-check">
                                    <label class="radio-container" >
                                        <input type="radio" v-validate="'required'" name="shipping[address_id]" onclick="selectShippingAdress('{{$address->id}}')">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
            
                                <div style="float: left; width: 95%;"">
                                    <ul class="address-card-list">
                                        <li class="mb-10">
                                            <b v-text="`${allAddress.first_name} ${allAddress.last_name}`">{{$address->first_name}}  {{$address->last_name}}</b>
                                        </li>
            
                                        <li class="expaned_li">{{$address->company_name}} </li>
            
                                        <li class="expaned_li" >{{ $address->address1}} </li>
            
                                        <li class="expaned_li" >{{ $address->city}}</li>
            
                                        <li class="expaned_li" > {{$address->state}}</li>
            
                                        <li class="">
                                            <span v-text="addresses.country" v-if="addresses.country">{{$address->country}}</span>
                                            <span v-text="addresses.postcode" v-if="addresses.postcode">{{$address->postcode}}</span>
                                        </li>
            
                                        <li>
                                            <b>{{ __('shop::app.customer.account.address.index.contact') }}</b> :
                                            <span v-text="addresses.phone">{{$address->phone}}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endforeach

                        <div class="control-group" :class="[errors.has('address-form.shipping[address_id]') ? 'has-error' : '']">
                            <span
                                class="control-error"
                                id="shipping.address_id"
                                v-text="errors.first('address-form.shipping[address_id]')"
                                v-if="errors.has('address-form.shipping[address_id]')">
                            </span>
                        </div>
                    </div>
                @endauth
            </div>

            {{--  new shipping form  --}}
            <div class="form-container" id="new_shipping_address" v-if="! address.billing.use_for_shipping && this.new_shipping_address">
                <div class="form-header">
                    <span class="checkout-step-heading">{{ __('shop::app.checkout.onepage.shipping-address') }}</span>
        
                    @auth('customer')
                        @if(count(auth('customer')->user()->addresses))
                            <button type="button"  class="btn btn-md btn-primary" style="float: right;" onclick="new_ShippingAddress(false)">
                                {{ __('shop::app.checkout.onepage.back') }}
                            </button>
                        @endif
                    @endauth
                </div>

                <div class="control-group" :class="[errors.has('address-form.shipping[first_name]') ? 'has-error' : '']">
                    <label for="shipping[first_name]" class="required form-label">
                        {{ __('shop::app.checkout.onepage.first-name') }}
                    </label>

                    <input
                        class="form-control"
                        id="shipping[first_name]"
                        type="text"
                        name="shipping[first_name]"
                        v-model="address.shipping.first_name"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.first-name') }}&quot;"
                    />

                    <span
                        class="control-error"
                        id="shipping.first_name"
                        v-text="errors.first('address-form.shipping[first_name]')"
                        v-if="errors.has('address-form.shipping[first_name]')">
                    </span>
                </div>

                <div class="control-group" :class="[errors.has('address-form.shipping[last_name]') ? 'has-error' : '']">
                    <label for="shipping[last_name]" class="required form-label">
                        {{ __('shop::app.checkout.onepage.last-name') }}
                    </label>

                    <input
                        class="form-control"
                        id="shipping[last_name]"
                        type="text"
                        name="shipping[last_name]"
                        v-model="address.shipping.last_name"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.last-name') }}&quot;"/>

                    <span
                        class="control-error"
                        id="shipping.last_name"
                        v-text="errors.first('address-form.shipping[last_name]')"
                        v-if="errors.has('address-form.shipping[last_name]')">
                    </span>
                </div>

                <div class="control-group" :class="[errors.has('address-form.shipping[email]') ? 'has-error' : '']">
                    <label for="shipping[email]" class="required form-label" >
                        {{ __('shop::app.checkout.onepage.email') }}
                    </label>

                    <input
                        class="form-control"
                        id="shipping[email]"
                        type="text"
                        name="shipping[email]"
                        v-validate="'required|email'"
                        v-model="address.shipping.email"
                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.email') }}&quot;"/>

                    <span
                        class="control-error"
                        id="shipping.email"
                        v-text="errors.first('address-form.shipping[email]')"
                        v-if="errors.has('address-form.shipping[email]')">
                    </span>
                </div>

                <div class="control-group" :class="[errors.has('address-form.shipping[address1][]') ? 'has-error' : '']">
                    <label for="shipping_address_0" class="required form-label">
                        {{ __('shop::app.checkout.onepage.address1') }}
                    </label>

                    <input
                        class="form-control"
                        id="shipping_address_0"
                        type="text"
                        name="shipping[address1][]"
                        v-model="address.shipping.address1[0]"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.address1') }}&quot;"/>

                    <span
                        class="control-error"
                        id="shipping.address1"
                        v-text="errors.first('address-form.shipping[address1][]')"
                        v-if="errors.has('address-form.shipping[address1][]')">
                    </span>
                </div>

                @if (
                    core()->getConfigData('customer.address.information.street_lines')
                    && core()->getConfigData('customer.address.information.street_lines') > 1
                    )
                    <div class="control-group" style="margin-top: -25px;">
                        @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                            <input
                                class="form-control"
                                id="shipping_address_{{ $i }}"
                                type="text"
                                name="shipping[address1][{{ $i }}]"
                                v-model="address.shipping.address1[{{$i}}]">
                        @endfor
                    </div>
                @endif

                <div class="control-group" :class="[errors.has('address-form.shipping[city]') ? 'has-error' : '']">
                    <label for="shipping[city]" class="required form-label">
                        {{ __('shop::app.checkout.onepage.city') }}
                    </label>

                    <input
                        class="form-control"
                        id="shipping[city]"
                        type="text"
                        name="shipping[city]"
                        v-model="address.shipping.city"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.city') }}&quot;"/>

                    <span
                        class="control-error"
                        id="shipping.city"
                        v-text="errors.first('address-form.shipping[city]')"
                        v-if="errors.has('address-form.shipping[city]')">
                    </span>
                </div>

                <div class="control-group" :class="[errors.has('address-form.shipping[country]') ? 'has-error' : '']">
                    <label for="shipping[country]" class="{{ core()->isCountryRequired() ? 'required' : '' }} form-label">
                        {{ __('shop::app.checkout.onepage.country') }}
                    </label>

                    <select
                        class="form-select"
                        id="shipping[country]"
                        type="text"
                        name="shipping[country]"
                        v-model="address.shipping.country"
                        v-validate="'{{ core()->isCountryRequired() ? 'required' : '' }}'"
                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.country') }}&quot;">
                        <option value=""></option>

                        @foreach (core()->countries() as $country)
                            <option value="{{ $country->code }}">{{ $country->name }}</option>
                        @endforeach
                    </select>

                    <span
                        class="control-error"
                        id="shipping.country"
                        v-text="errors.first('address-form.shipping[country]')"
                        v-if="errors.has('address-form.shipping[country]')">
                    </span>
                </div>

                <div class="control-group" :class="[errors.has('address-form.shipping[state]') ? 'has-error' : '']">
                    <label for="shipping[state]" class="{{ core()->isStateRequired() ? 'required' : '' }} form-label">
                        {{ __('shop::app.checkout.onepage.state') }}
                    </label>

                    <input
                        class="form-control"
                        id="shipping[state]"
                        type="text"
                        name="shipping[state]"
                        v-model="address.shipping.state"
                        v-validate="'{{ core()->isStateRequired() ? 'required' : '' }}'"
                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;"
                        v-if="! haveStates('shipping')"/>

                    <select
                        class="form-select" id="shipping[state]"
                        name="shipping[state]"
                        v-model="address.shipping.state"
                        v-validate=""
                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;"
                        v-if="haveStates('shipping')">
                        <option value="">{{ __('shop::app.checkout.onepage.select-state') }}</option>

                        <option v-for='(state, index) in countryStates[address.shipping.country]' :value="state.code">
                            @{{ state.default_name }}
                        </option>
                    </select>

                    <span
                        class="control-error"
                        id="shipping.state"
                        v-text="errors.first('address-form.shipping[state]')"
                        v-if="errors.has('address-form.shipping[state]')">
                    </span>
                </div>

                <div class="control-group" :class="[errors.has('address-form.shipping[postcode]') ? 'has-error' : '']">
                    <label for="shipping[postcode]" class="{{ core()->isPostCodeRequired() ? 'required' : '' }} form-label">
                        {{ __('shop::app.checkout.onepage.postcode') }}
                    </label>

                    <input
                        class="form-control"
                        id="shipping[postcode]"
                        type="text"
                        name="shipping[postcode]"
                        v-model="address.shipping.postcode"
                        v-validate="'{{ core()->isPostCodeRequired() ? 'required' : '' }}'"
                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.postcode') }}&quot;"/>

                    <span
                        class="control-error"
                        id="shipping.postcode"
                        v-text="errors.first('address-form.shipping[postcode]')"
                        v-if="errors.has('address-form.shipping[postcode]')">
                    </span>
                </div>

                <div class="control-group" :class="[errors.has('address-form.shipping[phone]') ? 'has-error' : '']">
                    <label for="shipping[phone]" class="required form-label">
                        {{ __('shop::app.checkout.onepage.phone') }}
                    </label>

                    <input
                        class="form-control"
                        id="shipping[phone]"
                        type="text"
                        name="shipping[phone]"
                        v-model="address.shipping.phone"
                        v-validate="'required|numeric'"
                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.phone') }}&quot;"
                    />

                    <span
                        class="control-error"
                        id="shipping.phone"
                        v-text="errors.first('address-form.shipping[phone]')"
                        v-if="errors.has('address-form.shipping[phone]')">
                    </span>
                </div>

                @auth('customer')
                    <div class="control-group">
                        <span class="checkbox">
                            <input
                                id="shipping[save_as_address]"
                                type="checkbox"
                                name="shipping[save_as_address]"
                                v-model="address.shipping.save_as_address"/>

                            <label class="checkbox-view" for="shipping[save_as_address]"></label>

                            {{ __('shop::app.checkout.onepage.save_as_address') }}
                        </span>
                    </div>
                @endauth
            </div>
            <hr>
        </div>
    @endif
</form>

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

        .address-holder{
            border-left: 6px solid #4d1ced;
            border-radius: 4px;
        }

        .address-radio-check{
            float: left;
            width: 4%;
            margin: auto auto;
        }

        @media  (min-width: 991px){

            .expaned_li{
                display: inline-block;
                width:45%;
            }
        }
    </style>
@endpush
