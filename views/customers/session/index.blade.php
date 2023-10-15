@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.login-form.page-title') }}
@endsection

@section('content-wrapper')
    <!--================Login Box Area =================-->
	<section class="login_box_area section-margin">
        {!! view_render_event('bagisto.shop.customers.login.before') !!}
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="login_box_img">
						<div class="hover">
							<h4>{{ __('shop::app.customer.login-text.no_account') }}</h4>
							
							<a class="button button-account" href="{{ route('shop.customer.register.index') }}">{{ __('shop::app.customer.login-text.title') }}</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="login_form_inner">
						<h3>{{ __('shop::app.customer.login-form.title') }}</h3>
                        <div  class="form-group">
                            {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}
                        </div>
						<form class="row login_form" method="POST" action="{{ route('shop.customer.session.create') }}">
                            {{ csrf_field() }}
                            
							<div class="col-md-12 form-group">
								<input type="email" required
                                class="form-control" 
                                name="email" 
                                placeholder="{{ __('shop::app.customer.login-form.email') }}"
                                >
                                <span class="control-error">@if($errors->has('email'))  {{ $errors->first('email') }} @endif</span>
                            </div>

							<div class="col-md-12 form-group">
								<input class="form-control" 
                                type="password" 
                                required min="6"
                                id="password" name="password" 
                                placeholder="{{ __('admin::app.users.sessions.password') }}"
                                >
                                <input type="checkbox"  id="showPassword" onclick="showPasswordCheck()" style="position: absolute; right: 20px; top: 0px;">
                                <span class="control-error">@if($errors->has('password'))  {{ $errors->first('password') }} @endif</span>
                            </div>

							<div class="col-md-12 form-group">
								<div class="creat_account">
									<input type="checkbox" checked id="f-option2" name="remember">
									<label for="f-option2">remember me</label>
								</div>
							</div>
                            
                            {!! Captcha::render() !!}
                            
							<div class="col-md-12 form-group">
								<button type="submit" value="submit" class="button button-login w-100">{{ __('shop::app.customer.login-form.button_title') }}</button>

                                @if (Cookie::has('enable-resend'))
                                    @if (Cookie::get('enable-resend') == true)
                                        <a href="{{ route('shop.customer.resend.verification_email', Cookie::get('email-for-resend')) }}">{{ __('shop::app.customer.login-form.resend-verification') }}</a>
                                    @endif
                                @endif
                                
								<a href="{{ route('shop.customer.forgot_password.create') }}">{{ __('shop::app.customer.login-form.forgot_pass') }}</a>
							</div>
						</form>
                        <div class="sign-up-text">
                            {{ __('shop::app.customer.login-text.no_account') }} - <a href="{{ route('shop.customer.register.index') }}">{{ __('shop::app.customer.login-text.title') }}</a>
                        </div>
					</div>
				</div>
			</div>
		</div>
        {!! view_render_event('bagisto.shop.customers.login.after') !!}
	</section>
	<!--============== Login Box Area End =================-->


    {{-- ---------------------------------------- --}}


    <div class="auth-content">

        {!! view_render_event('bagisto.shop.customers.login.before') !!}

        <form method="POST" action="{{ route('shop.customer.session.create') }}">

            {{ csrf_field() }}

            <div class="login-form">
                <div class="login-text">{{ __('shop::app.customer.login-form.title') }}</div>

                {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}

                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <label for="email" class="required">{{ __('shop::app.customer.login-form.email') }}</label>
                    <input type="email"  class="control" name="email" v-validate="'required|email'" value="{{ old('email') }}" data-vv-as="&quot;{{ __('shop::app.customer.login-form.email')}}&quot;">
                    <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                    <label for="password" class="required">{{ __('shop::app.customer.login-form.password') }}  </label>
                    <input type="password" v-validate="'required|min:6'" class="control" id="password" name="password" data-vv-as="&quot;{{ __('admin::app.users.sessions.password') }}&quot;" value=""/>
                    <input type="checkbox"  id="shoPassword" >{{ __('shop::app.customer.login-form.show-password') }}
                    <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <input type="checkbox"  id="shoPassword" >{{ __('shop::app.customer.login-form.show-password') }}  
                    </div>

                    <div class="col-md-6">
                        <div class="forgot-password-link">
                            <a href="{{ route('shop.customer.forgot_password.create') }}">{{ __('shop::app.customer.login-form.forgot_pass') }}</a>

                            <div class="mt-10">
                                @if (Cookie::has('enable-resend'))
                                    @if (Cookie::get('enable-resend') == true)
                                        <a href="{{ route('shop.customer.resend.verification_email', Cookie::get('email-for-resend')) }}">{{ __('shop::app.customer.login-form.resend-verification') }}</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="control-group">

                    {!! Captcha::render() !!}
                    
                </div>

                {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}

                <input class="btn btn-primary btn-lg" type="submit" value="{{ __('shop::app.customer.login-form.button_title') }}">
            </div>

        </form>

        {!! view_render_event('bagisto.shop.customers.login.after') !!}
    </div> 
@stop

@push('scripts')

{!! Captcha::renderJS() !!}

<script>
     function showPasswordCheck() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>

@endpush