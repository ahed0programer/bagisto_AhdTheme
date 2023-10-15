@extends('shop::layouts.master')
@section('page_title')
    {{ __('shop::app.customer.signup-form.page-title') }}
@endsection
@section('content-wrapper')

 <!--================Login Box Area =================-->
 <section class="login_box_area section-margin">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="login_box_img">
                    <div class="hover">
                        <h4>{{ __('shop::app.customer.signup-text.account_exists') }}</h4>
                        <p>There are advances being made in science and technology everyday, and a good example of this is the</p>
                        <a class="button button-account" href="{{ route('shop.customer.session.index') }}">{{ __('shop::app.customer.signup-text.title') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="login_form_inner register_form_inner">
                    <h3>{{ __('shop::app.customer.signup-form.title') }}</h3>
                    <form class="row login_form" id="register_form" method="post" action="{{ route('shop.customer.register.create') }}">
                        {{ csrf_field() }}
                        <div class="col-md-12 form-group">
                            <input 
                            type="text" class="form-control"
                            required
                            name="first_name" 
                            value="{{ old('first_name') }}" 
                            placeholder="{{ __('shop::app.customer.signup-form.firstname') }}"
                            >
                            <span class="control-error" v-if="errors.has('first_name')">@if($errors->has('first_name')) {{$errors->first('first_name')}} @endif</span>
                        </div>
                        <div class="col-md-12 form-group">
                            <input 
                                required
                                type="text" class="form-control"
                                name="last_name" 
                                value="{{ old('last_name') }}" 
                                placeholder="{{ __('shop::app.customer.signup-form.lastname') }}"
                            >
                            <span class="control-error">@if($errors->has('last_name')) {{$errors->first('last_name')}} @endif</span>
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="email" class="form-control" id="email" 
                                name="email"
                                required
                                value="{{ old('email') }}" 
                                placeholder="{{ __('shop::app.customer.signup-form.email') }}" 
                                onfocus="this.placeholder = ''" 
                                onblur="this.placeholder = {{ __('shop::app.customer.signup-form.email') }}"
                            >
                            <span class="control-error">@if($errors->has('email')) {{$errors->first('email')}} @endif</span>
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="text" class="form-control" id="password" 
                                required
                                name="password" 
                                placeholder="{{ __('shop::app.customer.signup-form.password') }}" 
                                onfocus="this.placeholder = ''" 
                                onblur="this.placeholder = {{ __('shop::app.customer.signup-form.password') }}"
                            >
                            <span class="control-error">@if($errors->has('password')) {{$errors->first('password')}} @endif</span>
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="text" class="form-control" id="confirmPassword" 
                            required
                            name="password_confirmation" 
                            placeholder="{{ __('shop::app.customer.signup-form.confirm_pass') }}" 
                            onfocus="this.placeholder = ''" 
                            onblur="this.placeholder = {{ __('shop::app.customer.signup-form.confirm_pass') }}"
                            >
                            <span class="control-error">@if($errors->has('password_confirmation')) {{$errors->first('password_confirmation')}} @endif </span>
                        </div>
                        <div class="control-group">
                            {!! Captcha::render() !!}
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="creat_account">
                                <input type="checkbox" id="f-option2" name="selector">
                                <label for="f-option2">Keep me logged in</label>
                            </div>
                        </div>
                        @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                            <div class="control-group">
                                <input type="checkbox" id="checkbox2" name="is_subscribed">
                                <span>{{ __('shop::app.customer.signup-form.subscribe-to-newsletter') }}</span>
                            </div>
                        @endif
        
                        <div class="col-md-12 form-group">
                            <button type="submit" value="submit" class="button button-register w-100">{{ __('shop::app.customer.signup-form.button_title') }}</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Login Box Area =================-->


{{-- <div class="auth-content">

    <div class="sign-up-text">
        {{ __('shop::app.customer.signup-text.account_exists') }} - <a href="{{ route('shop.customer.session.index') }}">{{ __('shop::app.customer.signup-text.title') }}</a>
    </div>

    {!! view_render_event('bagisto.shop.customers.signup.before') !!}

    <form method="post" action="{{ route('shop.customer.register.create') }}" @submit.prevent="onSubmit">

        {{ csrf_field() }}

        <div class="login-form">
            <div class="login-text">{{ __('shop::app.customer.signup-form.title') }}</div>

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.before') !!}

            <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                <label for="first_name" class="required">{{ __('shop::app.customer.signup-form.firstname') }}</label>
                <input type="text" class="control" name="first_name" v-validate="'required'" value="{{ old('first_name') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.firstname') }}&quot;">
                <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
            </div>

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.firstname.after') !!}

            <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                <label for="last_name" class="required">{{ __('shop::app.customer.signup-form.lastname') }}</label>
                <input type="text" class="control" name="last_name" v-validate="'required'" value="{{ old('last_name') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.lastname') }}&quot;">
                <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
            </div>

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.lastname.after') !!}

            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                <label for="email" class="required">{{ __('shop::app.customer.signup-form.email') }}</label>
                <input type="email" class="control" name="email" required v-validate="'required|email'" value="{{ old('email') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.email') }}&quot;">
                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
            </div>

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.email.after') !!}

            <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                <label for="password" class="required">{{ __('shop::app.customer.signup-form.password') }}</label>
                <input type="password" class="control" name="password" v-validate="'required|min:6'" ref="password" value="{{ old('password') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.password') }}&quot;">
                <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
            </div>

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.password.after') !!}

            <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                <label for="password_confirmation" class="required">{{ __('shop::app.customer.signup-form.confirm_pass') }}</label>
                <input type="password" class="control" name="password_confirmation"  v-validate="'required|min:6|confirmed:password'" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.confirm_pass') }}&quot;">
                <span class="control-error" v-if="errors.has('password_confirmation')">@{{ errors.first('password_confirmation') }}</span>
            </div>

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.password_confirmation.after') !!}

            <div class="control-group">

                {!! Captcha::render() !!}

            </div>

            @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                <div class="control-group">
                    <input type="checkbox" id="checkbox2" name="is_subscribed">
                    <span>{{ __('shop::app.customer.signup-form.subscribe-to-newsletter') }}</span>
                </div>
            @endif

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.after') !!}

            <button class="btn btn-primary btn-lg" type="submit">
                {{ __('shop::app.customer.signup-form.button_title') }}
            </button>

        </div>
    </form>

    {!! view_render_event('bagisto.shop.customers.signup.after') !!}
    
</div>  --}}

@endsection

@push('scripts')

    <script>
        $(function(){
            $(":input[name=first_name]").focus();
        });
    </script>

@endpush