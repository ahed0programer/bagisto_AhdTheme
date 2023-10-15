@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.onepage.title') }}
@stop

@section('content-wrapper')
    <div id="checkout" class="checkout-process">
        <div class="container" style="margin-top: 30px ;">
            <div class="progress" style="margin-bottom: 10px; height: 0.5rem">
                <div class="progress-bar" style="width:1%;"></div>
            </div> 
            <ul class="checkout-steps row">
                <li id="step_1" class="active-step col" onclick="moveToStep(1)">
                    <div class="decorator address-info"></div>
                    <span  class="material-symbols-outlined active-icon">library_books</span>
                    <span class="step-text">{{ __('shop::app.checkout.onepage.information') }}</span>
                </li>

                @if ($cart->haveStockableItems())
                    <li id="step_2" class="col"  onclick="moveToStep(2)">
                        <div class="decorator shipping"></div>
                        <span id="step_2"  class="material-symbols-outlined active-icon">local_shipping</span>
                        <span class="step-text">{{ __('shop::app.checkout.onepage.shipping') }}</span>
                    </li>
                @endif

                <li id="step_3" class="col" onclick="moveToStep(3)">
                    <div class="decorator payment"></div>
                    <span class="material-symbols-outlined active-icon">paid</span>
                    <span class="step-text">{{ __('shop::app.checkout.onepage.payment') }}</span>
                </li>

                <li id="step_4" class="col" :class="[current_step == 4 ? 'active' : '']">
                    <div class="decorator review"></div>
                    <span id="step_icon4"  class="material-symbols-outlined active-icon">order_approve</span>
                    <span class="step-text">{{ __('shop::app.checkout.onepage.review') }}</span>
                </li> 
            </ul>

            <div class="row">
                <div id="steps_section" class="col-xl-9">
                    <div id="information" class="step-content information " v-show="current_step == 1" id="address-section">
                        <span class="material-symbols-outlined">
                            home
                        </span>
                        @include('shop::checkout.onepage.customer-info')
                        <div class="button-group">
                            <button type="button" class="btn btn-lg btn-primary" onclick="save_Address()" @click="validateForm('address-form')" :disabled="disable_button" id="checkout-address-continue-button">
                                {{ __('shop::app.checkout.onepage.continue') }}
                            </button>
                        </div>
                    </div>
        
                    <div id="shipping" class="step-content shipping" style="display: none;" id="shipping-section">
                        <button type="button" class="btn btn-primary btn-md" onclick="moveToStep(1)">
                            <span class="material-symbols-outlined align-middle">arrow_back</span>
                        </button>
                        <div id="shipping_HTML"></div>
                        <hr>
                        <div class="button-group">
                            <button type="button" class="btn btn-lg btn-primary" onclick="saveShipping()" id="checkout-shipping-continue-button">
                                {{ __('shop::app.checkout.onepage.continue') }}
                            </button>
                        </div>
                    </div>
        
                    <div id="payment" class="step-content payment" style="display: none;" id="payment-section">
                        <button type="button" class="btn btn-primary btn-md" onclick="moveToStep(2)">
                            <span class="material-symbols-outlined align-middle">arrow_back</span>
                         </button>
                        <div id="paymentHTML"></div>
                        <hr>
                        <div class="button-group">
                            <button type="button" class="btn btn-lg btn-primary" onclick="save_payment()" id="checkout-payment-continue-button">
                                {{ __('shop::app.checkout.onepage.continue') }}
                            </button>
                        </div>
                    </div>
        
                    <div id="review" class="step-content review" style="display: none;" v-show="current_step == 4" id="summary-section">
                        <review-section v-if="current_step == 4" :key="reviewComponentKey">
                            <div slot="summary-section">
                                <summary-section :key="summeryComponentKey"></summary-section>
        
                                <coupon-component
                                    @onApplyCoupon="getOrderSummary"
                                    @onRemoveCoupon="getOrderSummary">
                                </coupon-component>
                            </div>
                        </review-section>
                        <button type="button" class="btn btn-primary btn-md" onclick="moveToStep(3)">
                            <span class="material-symbols-outlined align-middle">arrow_back</span>
                        </button>

                        <div id="reviewHTML"></div>
                        <div class="button-group">
                            <button type="button" class="btn btn-lg btn-primary" onclick="placeOrder()"  @click="placeOrder()" :disabled="disable_button" id="checkout-place-order-button" v-if="selected_payment_method.method != 'paypal_smart_button'">
                                {{ __('shop::app.checkout.onepage.place-order') }}
                            </button>
        
                            <div class="paypal-button-container"></div>
                        </div>
                    </div>
                </div>
                
                <div id="loading_animation" class="col-xl-9 justify-content-center align-items-center" style="display: none;">
                    <div class="loading">
                        <div class="spinner-grow text-primary"></div>
                        <div class="spinner-grow spinner-grow-lg text-success"></div>
                        <div class="spinner-grow text-info"></div>
                    </div>
                </div>
               
                {{-- ----------- ORDER SUMMARY SECTION ------------ --}}

                <div class="col-xl-3" style="margin-top: 20px">
                    <div id="summary_section" class="card " style="padding: 15px">
                        @include('shop::checkout.total.summary')
                    </div>
                    <div style="margin-top: 20px">
                        <div class="card">
                            <h3>copoun code</h3>
                            @if(!$cart->coupon_code)
                                <form class="coupon-form" id="coupon_form">
                                    @csrf
                                    <div class="cupon_text d-flex align-items-center">
                                        <input id="coupon_code_id" type="text" required name="code" placeholder="{{  __('shop::app.checkout.onepage.enter-coupon-code') }}">
                                        <span id="code_error" style="color: red"></span>
                                    </div>
                                    <button id='applyCoupon' class="primary-btn" >{{ __('shop::app.checkout.onepage.apply-coupon') }}</button>
                                </form>
                            @else
                                <form  method="post" action="{{ route('shop.checkout.coupon.remove.coupon') }}">
                                    @csrf
                                    @method('delete')
                                    <div class="cupon_text d-flex align-items-center">
                                        <button id='applyCoupon' class="primary-btn" >{{  __('shop::app.checkout.total.remove-coupon') }}</button>
                                        <span id="code_error" style="color: red"></span>
                                        {{-- <a href="{{ route('admin.urls.delete') }}" 
                                            class="btn btn-sm pd-x-15 btn-danger btn-uppercase" 
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                            <i class="wd-10 mg-r-5" data-feather="trash"></i>
                                            remove coupon 
                                        </a> --}}
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>       
        </div>
    </div>
@endsection

@push('css')
    <style>
        .pd-5-25{
            padding: 5px 20px;
        }
        .checkout-steps{
            margin-bottom: 30px;
            text-align: center;
        }
        .control-error{
            color: red;
        }
        .checkout-steps li{
            display: inline;
            padding: 5px 10px;
            cursor: default;
        }

        .checkout-steps .material-symbols-outlined{
            font-size: 49px;
        }

        .checkout-step-circle{
            font-size: 14px;
            display: inline-block;
            background: #384aeb;
            color: #fff;
            padding: 0px 5px;
            border-radius: 50%;
            width: 20px;
            height: 24px;
            margin-right: 35px;

        }
        .active-step{
            color: #384aeb;
        }
        .step-text{
            display: block;
        }

        @media (max-width: 991px){
                .step-text{
                display: none;
            }
        }

        .active-step .active-icon {
            animation-name: active;
            animation-duration: 2s;
            animation-iteration-count: infinite;
        }

        @keyframes active {
            0%  { rotate: 15deg;}
            50% { rotate: -15deg;} 
            100%{ rotate: 15deg;}
        }
        
    </style>
@endpush

@push('scripts')
    @include('shop::checkout.cart.coupon')

    {{-- address handling form script --}}
    <script>
        var new_billing_address=true;
        var new_shipping_address=true;
       @auth('customer')
           @if(count(auth('customer')->user()->addresses))
               new_billing_address=false;
               new_shipping_address=false;
           @endif
       @endauth

       if(new_billing_address){
           document.getElementById("user_billing_address").style.display="none";
           document.getElementById("new_billing_address").style.display="block";
       }else{
           document.getElementById("user_billing_address").style.display="block";
           document.getElementById("new_billing_address").style.display="none";
       }

       if(new_shipping_address){
           document.getElementById("user_shipping_address").style.display="none";
           document.getElementById("new_shipping_address").style.display="block";
       }else{
           document.getElementById("user_shipping_address").style.display="block";
           document.getElementById("new_shipping_address").style.display="none";
       }

       function update_address_display(){
           if(new_billing_address){
               document.getElementById("user_billing_address").style.display="none";
               document.getElementById("new_billing_address").style.display="block";
           }else{
               document.getElementById("user_billing_address").style.display="block";
               document.getElementById("new_billing_address").style.display="none";
           }

           if(new_shipping_address){
               document.getElementById("user_shipping_address").style.display="none";
               document.getElementById("new_shipping_address").style.display="block";
           }else{
               document.getElementById("user_shipping_address").style.display="block";
               document.getElementById("new_shipping_address").style.display="none";
           }
       }

       function new_BillingAddress(flag) {
           new_billing_address = flag;
           update_address_display();
       }
       function new_ShippingAddress(flag) {
           new_shipping_address = flag;
           update_address_display();
       }
    </script>

    <script>     
        const shippingCheck = document.getElementById("billing_use_for_shipping");
        const shippingAdressForm = document.getElementById("shipping_form");
        if(shippingCheck.checked){
            shippingAdressForm.style.display="none";
        }
        else{
            shippingAdressForm.style.display="block";
        }

        shippingCheck.addEventListener('change', function(event) {
            if(shippingCheck.checked){
                shippingAdressForm.style.display="none";
            }
            else{
                shippingAdressForm.style.display="block";
            }
        });
        
    </script>
    {{-- end addres handling form script --}}

    {{-- -------------------------------- --}}

    {{-- procced to checkout script --}}
    <script>
        let shippingHtml = '';
        let paymentHtml = '';
        let reviewHtml = '';
        let summaryHtml = '';
        let customerAddress = '';

        @auth('customer')
            @if(auth('customer')->user()->addresses)
                customerAddress = @json(auth('customer')->user()->addresses);
                customerAddress.email = "{{ auth('customer')->user()->email }}";
                customerAddress.first_name = "{{ auth('customer')->user()->first_name }}";
                customerAddress.last_name = "{{ auth('customer')->user()->last_name }}";
            @endif
        @endauth

        let step_numbers = {
            'information': 1,
            'shipping': 2,
            'payment': 3,
            'review': 4
        };
        current_step=1;
        completed_step=0;

        
        function moveToStep(step){
            if(step_numbers["information"] == step){
                document.getElementById("information").style.display="block";
            }
            else{document.getElementById("information").style.display="none"; }

            if(step_numbers["shipping"] == step){
                document.getElementById("shipping").style.display="block";
                document.getElementById("shipping_HTML").innerHTML=shippingHtml;
            }
            else{document.getElementById("shipping").style.display="none"; }

            if(step_numbers["payment"] == step){
                document.getElementById("payment").style.display="block";
                document.getElementById("paymentHTML").innerHTML = paymentHtml; 
            }
            else{document.getElementById("payment").style.display="none"; }
            
            if(step_numbers["review"] == step){
                document.getElementById("review").style.display="block";
                document.getElementById("reviewHTML").innerHTML=reviewHtml;
            }
            else{document.getElementById("review").style.display="none"; }

            current_step = step;
            update_styles();

        }

        function update_styles(){
            document.querySelector(".progress-bar").style.width=(25*completed_step)+"%";
            for (let i = 1; i < 4; i++) {
                document.getElementById("step_"+i).classList.remove("active-step");
            }

            if(!(current_step>4)){
                document.getElementById("step_"+(current_step)).classList.add("active-step");
            }
        }

        function show_loading_animation(is_show){
            if(is_show){
                document.getElementById("steps_section").style.display= "none";
                document.getElementById("loading_animation").style.display= "flex";
            }else
            {
                document.getElementById("steps_section").style.display= "block";
                document.getElementById("loading_animation").style.display= "none";
            }

        }

        function handleErrorResponse(ErrorResponse , input_fields){
            input_fields.forEach(field => {
                if(ErrorResponse.errors[field]){
                    document.getElementById(field).innerText = ErrorResponse.errors[field][0];
                }
            });
        }

        function clearErrorMessages(fields){ 
            fields.forEach(field => {
                if(document.getElementById(field)){
                    document.getElementById(field).innerText = " ";
                }
                
            });   
        }

        function updateOrderSummary(){
            let summary = document.getElementById("summary_section");

            fetch("{{ route('shop.checkout.summary') }}",{
                method:"GET"
            })
            .then(response => response.json())
            .then(data => {
                summary.innerHTML = data.html;

            }).catch(error=>{
                alert(error);
            })
        }

        function placeOrder(){
            show_loading_animation(true);
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch("{{ route('shop.checkout.save_order') }}",{
                method:"POST",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN":csrfToken,
                    "Accept":"application/json",
                },
                body:JSON.stringify({'_token' : "{{ csrf_token() }}"})
            })
            .then(response=>{
                if(response.ok)
                    return response.json();
                else{
                   // Handle other status codes here
                   alert("Unexpected status code: " + response.status);
                }

            })
            .then(data=>{   
                if (data.success) {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.href = "{{ route('shop.checkout.success') }}";
                    }
                }
            })
            .catch(error=>{
                alert(error)
            })
        }
    </script>
  
    {{-- address step script  --}}
    <script>
       
        let address_fields = [
            "billing.phone",
            "billing.city",
            "billing.email",
            "billing.first_name",
            "billing.last_name",
            "billing.country",
            "billing.postcode",
            "billing.company_name",
            "billing.state",
            "shipping.phone",
            "shipping.city",
            "shipping.email",
            "shipping.first_name",
            "shipping.last_name",
            "shipping.country",
            "shipping.postcode",
            "shipping.address1",
            "shipping.state"
        ];
        
        let address = {
            billing : {
                address1: [""],
                use_for_shipping: true,
                city:"damas",
                phone:"0996840955"
            },
            shipping: {
                address1: ['']
            },
        };

        let allAddress=[''];

        if(! customerAddress) {
                new_shipping_address = true;
                new_billing_address = true;
        } 
        else {
            address.billing.first_name = address.shipping.first_name = customerAddress.first_name;
            address.billing.last_name = address.shipping.last_name = customerAddress.last_name;
            address.billing.email = address.shipping.email = customerAddress.email;

            if (customerAddress.length < 1) {
                new_shipping_address = true;
                new_billing_address = true;
            } else {
                allAddress = customerAddress;
            }
        }
        
        function save_Address(){

            clearErrorMessages(address_fields);
            show_loading_animation(true);

            update_addressData();

            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('shop.checkout.save_address') }}",{
                method: "POST",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN":csrfToken,
                    "Accept":"application/json",
                },
                body: JSON.stringify(address),
            })
            .then(response=>{
                // Check the status code
                if (response.status === 403) {
                    response.json().then(data=>{
                        alert("403 Error | \n you will be redirected")
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        }
                    });
                    
                } else if (response.status === 422) {
                    response.json().then(R => {
                        // Assuming "errors" is an object containing field-specific error messages
                        handleErrorResponse(R,address_fields);
                        show_loading_animation(false);
                    });
                } else if (response.ok) {
                    // Response status is OK (e.g., 200)
                    return response.json();
                } else {
                    // Handle other status codes here
                    alert("Unexpected status code: " + response.status);
                }
            })
            .then(data=>{
                if(data){
                    if (step_numbers[data.jump_to_section] == 2)
                        shippingHtml = data.html;
                    else
                        paymentHtml  = data.html;

                    moveToStep(step_numbers[data.jump_to_section])

                    current_step = step_numbers[data.jump_to_section];
                    completed_step = step_numbers[data.jump_to_section]-1;

                    update_styles();
                    updateOrderSummary();

                    show_loading_animation(false)
                }
            })
            .catch(error=>{
                alert(error);
            })
        }
        
        function update_addressData(){
               
            address.billing.use_for_shipping = document.getElementById("billing_use_for_shipping").checked
            
            if(new_billing_address){
                var billingEmail = document.getElementById('billing[email]').value;
                var billingAddress1 = document.getElementById("billing_address_0").value;
                var billingCity  = document.getElementById("billing[city]").value;
                var billingCountry= document.getElementById("billing[country]").value;
                var billingPostcode= document.getElementById("billing[postcode]").value;
                var billingPhone= document.getElementById("billing[phone]").value;
                

                address.billing.email    = billingEmail;
                address.billing.address1 = [billingAddress1];
                address.billing.phone    = billingPhone;
                address.billing.postcode = billingPostcode;
                address.billing.country  = billingCountry;

                if(address.billing.use_for_shipping){  
                    address.shipping.email = billingEmail;
                    address.shipping.address1 =[billingAddress1]  
                }
                else{
                    if(new_shipping_address){
                        var shippingEmail = document.getElementById('shipping[email]').value;
                        var shippingAdress1 = document.getElementById('shipping_address_0').value;
                        var shippingCity  = document.getElementById("shipping[city]").value;
                        var shippingCountry= document.getElementById("shipping[country]").value;
                        var shippingPostcode= document.getElementById("shipping[postcode]").value;
                        var shippingPhone= document.getElementById("shipping[phone]").value;

                        address.shipping.email    =  shippingEmail;
                        address.shipping.address1 = [shippingAdress1]
                    }else{
                        take_existing_address();
                    }
                }
            }
            else{
               take_existing_address();
            }
        }

        function take_existing_address(){
            if (allAddress.length > 0) {
                allAddress.forEach(element => {
                    if (element.id == address.billing.address_id) {
                        address.billing.address1 = [element.address1];
                    }

                    if (element.id == address.shipping.address_id) {
                        address.shipping.address1 = [element.address1];
                    }
                });
            }
        }

        function isCustomerExist(){

            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            update_addressData();

            fetch("{{ route('shop.customer.checkout.exist') }}", {
                method:"POST",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN":csrfToken,
                    "Accept":"application/json",
                },
                body:JSON.stringify({email: address.billing.email})
            })
            .then(response=>{
                if(response.ok){
                    return response.json();
                }
                else{
                    alert("woops |\n somthing went wrong")
                }
            })
            .then(data=>{

                let iscustomerexist = data ? true : false;

                if(!iscustomerexist){
                    document.getElementById("billing.email").innerText = "this email does not exist !"
                }else{
                    document.getElementById("billing.email").innerText = "";
                }
            }).catch(error=>{
                alert(error)
            })
        }

        function selectBillingAdress(address_id){
            console.log(address_id);
            address.billing.address_id = address_id;
        }

        function selectShippingAdress(address_id){
            console.log(address_id);
            address.shipping.address_id = address_id;
        }

    </script>

    {{-- shipping step script  --}}
    <script>
        let selected_shipping_method;
        let shipping_fields = [
            "shipping_method"
        ]

        function saveShipping(){
            clearErrorMessages(shipping_fields);
            show_loading_animation(true);

            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log(selected_shipping_method+" ll");

            fetch("{{ route('shop.checkout.save_shipping') }}",{
                method:"POST",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN":csrfToken,
                    "Accept":"application/json",
                },
                body:JSON.stringify({'shipping_method':selected_shipping_method})
            })
            .then(response=>{
                // Check the status code
                if (response.status === 403) {
                    response.json().then(data=>{
                        alert("403 Error | \n you will be redirected")
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        }
                    });
                    
                }else if (response.status === 422) {
                    response.json().then(R => {
                        // Assuming "errors" is an object containing field-specific error messages
                        handleErrorResponse(R,shipping_fields);
                        show_loading_animation(false);
                    });
                } else if (response.ok) {
                    // Response status is OK (e.g., 200)
                    return response.json();
                } else {
                    // Handle other status codes here
                    alert("Unexpected status code: " + response.status);
                }
            }).then(data=>{
                if(data){
                    paymentHtml = data.html;

                    moveToStep(step_numbers[data.jump_to_section])

                    current_step = step_numbers[data.jump_to_section];
                    completed_step = step_numbers[data.jump_to_section]-1;

                    update_styles();
                    updateOrderSummary();

                    show_loading_animation(false)
                }
                
            }).catch(error=>{
                alert(error);
            })
        }

        function selectMethod(method){
            selected_shipping_method = method;
        }
    </script>

    {{-- payment step script --}}
    <script>
        let payment = {
            method:"",
        };

        let payment_error_fields = [
            "payment-form.payment[method]"
        ]

        function save_payment(){
            clearErrorMessages(payment_error_fields);
            show_loading_animation(true);

            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('shop.checkout.save_payment') }}",{
                method:"POST",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN":csrfToken,
                    "Accept":"application/json",
                },
                body:JSON.stringify({"payment":payment})
            })
            .then(response=>{
                // Check the status code
                if (response.status === 403) {
                    response.json().then(data=>{
                        alert("403 Error | \n you will be redirected")
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        }
                    });
                    
                }else if (response.status === 422) {
                    response.json().then(R => {
                        // Assuming "errors" is an object containing field-specific error messages
                        handleErrorResponse(R,payment_error_fields);
                        show_loading_animation(false);
                    });
                } else if (response.ok) {
                    // Response status is OK (e.g., 200)
                    return response.json();
                } else {
                    // Handle other status codes here
                    alert("Unexpected status code: " + response.status);
                }
            })
            .then(data=>{
                if(data){
                    reviewHtml = data.html;

                    moveToStep(step_numbers[data.jump_to_section])

                    current_step = step_numbers[data.jump_to_section];
                    completed_step = step_numbers[data.jump_to_section]-1;

                    update_styles();
                    updateOrderSummary();

                    show_loading_animation(false)
                }
                
            })
            .catch(error=>{
                alert(error);
            })
        }

        function selectPaymentMethod(method){
            payment.method = method;
        }
    </script>
@endpush
