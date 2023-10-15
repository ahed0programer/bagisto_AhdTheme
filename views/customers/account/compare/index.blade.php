@extends('shop::customers.account.index')

@include('shop::guest.compare.compare-products')

@section('page_title')
    {{ __('shop::app.customer.compare.compare_similar_items') }}
@endsection

@section('account-content')
    <div class="account-layout col-xl-9 col-lg-9">
        {!! view_render_event('bagisto.shop.customers.account.comparison.list.before') !!}

        <div class="account-items-list">
            <div class="loading text-center">
                <div class="spinner-grow text-primary"></div>
                <div class="spinner-grow spinner-grow-lg text-success"></div>
                <div class="spinner-grow text-info"></div>
            </div>

            <div class="account-table-content" style="display: none;">
                <div>
                    <button class="btn btn-warning btn-md" onclick="removeFromCompare('all')">Remove All</button>
                </div>
                <table class="table table-striped table-hover table-responsive">
                    <thead>
                        <tr class="products_names">
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="products_images" >
                            <td></td>

                        </tr>
                        <tr class="products_actions">
                            <td></td>
                            
                        </tr>
                        <tr class="products_descriptions">
                            <td></td>
                            
                        </tr>
                        <tr class="products_prices">
                            <td></td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.comparison.list.after') !!}
    </div>
@endsection
@push('css')
    <style>
        .table td:first-child{
            background-color: #d7ffaf;
        }
        .table thead tr:first-child{
          background-color: #ffe3af;
          text-align: center;
        }

        .table thead tr td:first-child{
            background-color: #d7ffaf;
            text-align: initial;
        }
    </style>
    
@endpush

@push('scripts')
    <script>
        function getCompareList(){
            fetch("{{route('velocity.customer.product.compare')}}?data=1",{
                method:"GET"
            })
            .then(response=>response.json())
            .then(data=>{
                if(data.products.length>0){
                    console.log(data);
                    compare_names(data);
                    compare_images(data);
                    insert_actions(data);
                    compare_descriptions(data);
                    compare_prices(data);
                }
                else{
                    document.querySelector(".account-table-content").innerHTML="no products to compare";
                }
                
                document.querySelector(".loading").style.display = "none";
                document.querySelector(".account-table-content").style.display="block";
            })
            .catch(error=>{
                flashAlertMessage('error',error);
            })
        }
        getCompareList();
    </script>

    <script>
        function compare_names(data){
            const names_row = document.querySelector(".products_names");
            names_row.querySelector("td").innerHTML = "Name ";
            data.products.forEach(product => {
                let td = document.createElement("td");
                td.innerHTML = product.name;
                names_row.appendChild(td);
            });
        }
        function compare_images(data){
            const images_row = document.querySelector(".products_images");
            images_row.querySelector("td").innerHTML = "Image ";
            data.products.forEach(product => {
                let td = document.createElement("td");
                td.classList.add("text-center");
                td.innerHTML = "<img src='"+product.product_image+"' alt='cannot load image !'>" ;
                images_row.appendChild(td);
            });
        }
        function insert_actions(data){
            const actions_row = document.querySelector(".products_actions");
            actions_row.querySelector("td").innerHTML = "Actions ";
            data.products.forEach(product => {
                let td = document.createElement("td");
                td.innerHTML = product.addToCartHtml 
                    +"<button class='btn btn-md btn-primary' onclick='removeFromCompare("+product.id+")'>"
                    +"<span class='material-symbols-outlined'>disabled_by_default</span>"
                    +"</button>";
                actions_row.appendChild(td);
            });
        }
        function compare_descriptions(data){
            const description_row = document.querySelector(".products_descriptions");
            description_row.querySelector("td").innerHTML = "Description ";
            data.products.forEach(product => {
                let td = document.createElement("td");
                td.innerHTML = product.description ;
                description_row.appendChild(td);
            });
        }
        function compare_prices(data){
            const price_row = document.querySelector(".products_prices");
            price_row.querySelector("td").innerHTML = "Price ";
            data.products.forEach(product => {
                let td = document.createElement("td");
                td.innerHTML = product.priceHTML ;
                price_row.appendChild(td);
            });
        }
        function removeFromCompare(id){
            if(id=="all" & !confirm("Do you really want to remove all compare items ")){
                return;
            }
            fetch("{{ url()->to('/') }}/comparison?productId="+id,{
                method:"DELETE",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN":"{{csrf_token()}}",
                    "Accept":"application/json",
                }
            })
            .then(response=>response.json())
            .then(data=>{
                flashAlertMessage("success" , data.message);
            }).catch(error=>{
                flashAlertMessage("error",error);
            })
        }
    </script>
@endpush
