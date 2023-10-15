<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <title>@yield('page_title')</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url()->to('/') }}">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
    
    

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" /> 
    
    <link rel="stylesheet" href="{{ bagisto_asset('vendors/bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ bagisto_asset('vendors/fontawesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ bagisto_asset('vendors/themify-icons/themify-icons.css')}}">
    <link rel="stylesheet" href="{{ bagisto_asset('vendors/nice-select/nice-select.css')}}">
    <link rel="stylesheet" href="{{ bagisto_asset('vendors/owl-carousel/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{ bagisto_asset('vendors/owl-carousel/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ bagisto_asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ bagisto_asset('css/customeCss.css') }}">
   

    @if ($favicon = core()->getCurrentChannel()->favicon_url)
        <link rel="icon" sizes="16x16" href="{{ $favicon }}" />
    @else
        <link rel="icon" sizes="16x16" href="{{ bagisto_asset('images/favicon.ico') }}" />
    @endif

    @yield('head')

    @section('seo')
        @if (! request()->is('/'))
            <meta name="description" content="{{ core()->getCurrentChannel()->description }}"/>
        @endif
    @show

    @stack('css')

    {!! view_render_event('bagisto.shop.layout.head') !!}

    <style>
        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>
</head>


<body @if (core()->getCurrentLocale() && core()->getCurrentLocale()->direction == 'rtl') class="rtl" @endif style="scroll-behavior: smooth;">

    @include('shop::layouts.header.index')

    <div id="flashMessages">
        @if ($message= Session::get('error'))
            <div class="container alert_message">
                <div class="alert alert-danger" role="alert">
                    {{$message}}
                </div>
            </div>
        @endif
        @if ($message= Session::get('success'))
            <div class="container alert_message">
                <div class="alert alert-success" role="alert">
                    {{$message}}
                </div>
            </div>
        @endif
        @if ($message= Session::get('warning'))
            <div class="container alert_message">
                <div class="alert alert-warning" role="alert">
                    {{$message}}
                </div>
            </div>
        @endif
        @if ($message= Session::get('info'))
            <div class="container alert_message">
                <div class="alert alert-info" role="alert">
                    {{$message}}
                </div>
            </div>
        @endif
    </div>

    @yield('content-wrapper')

    @include('shop::layouts.footer.footer')

    @stack('scripts')

    <script>
        // Function to hide alert messages
        function hideAlertMessages() {
            // Get all elements with the class "alert_message"
            const alertMessages = document.querySelectorAll('.alert_message');

            alertMessages.forEach(function(alertMessage) {
                alertMessage.remove(alertMessage);
            });
        }
        // Set a timeout to hide the alert messages after 5 seconds (5000 milliseconds)
        setTimeout(hideAlertMessages, 8000);

        function flashAlertMessage(alertType , message) {

            let messageContainer = document.createElement("div");

            messageContainer.classList.add("container");
            messageContainer.classList.add("alert_message");

            let messageELement = document.createElement("div")
            messageELement.classList.add("alert");
            messageELement.classList.add("alert-"+alertType);

            messageELement.innerText = message;

            messageContainer.appendChild(messageELement);

            document.getElementById("flashMessages").appendChild(messageContainer);
            setTimeout(hideAlertMessages, 5000);
        }

    </script>

    <script>
    let customer = '{{ auth()->guard("customer")->user() ? "true" : "false" }}' == "true";

    function addProductToCompare(productID) {
        if (customer == "true" || customer == true) {
            fetch("{{ url()->to('/') }}/comparison",{
                method:"PUT",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN":"{{csrf_token()}}",
                    "Accept":"application/json",
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body:JSON.stringify({"productId": productID})
                
            })
            .then(response=>response.json())
            .then(data=>{
                flashAlertMessage(data.status , data.message)
                updateCompareCount()
            })
            .catch(error=>{
                flashAlertMessage("error","{{ __('shop::app.common.error') }}");
            })
        }else {
            let updatedItems = [productID];
            let existingItems = getStorageValue('compared_product');

            if(existingItems) {
                if (existingItems.indexOf(productID) == -1) {
                    updatedItems = existingItems.concat(updatedItems);

                    setStorageValue('compared_product', updatedItems);

                    flashAlertMessage('success',"{{ __('shop::app.customer.compare.added') }}")

                } else {
                    flashAlertMessage('success',"{{ __('shop::app.customer.compare.already_added') }}")
                }
            }else {
                setStorageValue('compared_product', updatedItems);
                flashAlertMessage('success',"{{ __('shop::app.customer.compare.added') }}")
            }
            updateCompareCount()
        }
        
    }

    function updateCompareCount() {
        if (customer == "true" || customer == true) {
            fetch(`{{ url()->to('/') }}/items-count`,{
                method:"get",
            })
            .then(response =>response.json())
            .then(data=>{
                console.log(data);
            })
            .catch(error => {
                flashAlertMessage("alert-error","{{ __('shop::app.common.error') }}");
            });
        } else {
            let comparedItems = JSON.parse(localStorage.getItem('compared_product'));

            comparedItemsCount = comparedItems ? comparedItems.length : 0;

            document.getElementById("compare-items-count").innerHTML(comparedItemsCount);
        }
    }

    function getStorageValue(key) {
        let value = window.localStorage.getItem(key);
        if (value) {
            value = JSON.parse(value);
        }
        return value;
    }

    function setStorageValue(key, value) {
        window.localStorage.setItem(key, JSON.stringify(value));
        return true;
    }
    </script>

    <script src="{{bagisto_asset('vendors/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <script src="{{bagisto_asset('vendors/jquery/jquery-3.2.1.min.js')}}"></script>
    <script src="{{bagisto_asset('vendors/skrollr.min.js')}}"></script>
    <script src="{{bagisto_asset('vendors/owl-carousel/owl.carousel.min.js')}}"></script>
    <script src="{{bagisto_asset('vendors/nice-select/jquery.nice-select.min.js')}}"></script>
    <script src="{{bagisto_asset('vendors/jquery.ajaxchimp.min.js')}}"></script>
    <script src="{{bagisto_asset('vendors/mail-script.js')}}"></script>
    <script src="{{bagisto_asset('js/main.js')}}"></script>
</body>

</html>