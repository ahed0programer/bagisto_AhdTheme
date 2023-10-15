@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('account-content')
    <div class="account-layout col-xl-9 col-lg-9">
        <div class="account-head mb-10">
            <span class="back-icon"><a href="{{ route('shop.customer.profile.index') }}"><i class="icon icon-menu-back"></i></a></span>

            <span class="account-heading">
                {{ __('shop::app.customer.account.order.index.title') }}
            </span>

            <div class="horizontal-rule"></div>

            <div id="page_data">

            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}

        <div class="account-items-list">
            <div class="loading text-center">
                <div class="spinner-grow text-primary"></div>
                <div class="spinner-grow spinner-grow-lg text-success"></div>
                <div class="spinner-grow text-info"></div>
            </div>

            <div class="table-responsive-lg account-table-content"  style="display: none;">
                <table class="table table-striped table-hover">
                    <thead id="thead" class="table-secondary">
                    </thead>
                    <tbody id="tbody">
                    </tbody>
                </table>
            </div>

            <div>
                <ul class="pagniation_links">
                </ul>
            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}

    </div>
@endsection

@push('css')
    <style>
        .pagniation_links{
            width: 100%;
        }

        .pagniation_links li{
            display: inline-block;
            border: 1px solid;
            background-color: white;
            padding: 2px 5px;
        }

        .pagniation_links li.active{
            background-color: blue;
            color: white;
        }

        .pagniation_links li:hover{
            background-color: blue;
            color: white;
        }

        .pagniation_links li a{
            color: inherit;
        }
        
    </style>
@endpush

<script>
    function populateDataInTable(data){
        let thead = document.getElementById('thead');
        let row = document.createElement("tr");

        data.columns.forEach(element => {
            const cell = document.createElement("td");
            cell.textContent = element.label;

            row.appendChild(cell);
        });

        if(data.enableActions){
            const cell = document.createElement("td");
            cell.textContent = "Action";
            row.appendChild(cell);
        }

        thead.appendChild(row);

        let tbody = document.getElementById('tbody');

        data.records.data.forEach(record => {
            let row = document.createElement("tr");

            data.columns.forEach(col => {
                const cell = document.createElement("td");
                cell.innerHTML = record[col.index];
                row.appendChild(cell);
            });

            const cell = document.createElement("td");
            cell.innerHTML = "<a href='"+record.view_url+"'>view</a>"
            row.appendChild(cell);

            tbody.appendChild(row)
        });
    }

    function populateFilters(data){

       let pager = document.getElementById("page_data");

       pager.innerHTML = "<span>"+data.records.from+" - "+data.records.to+" "+data.translations.of+" "+data.records.total;
    }

    function populatePagnation(data) {
        let paginator = document.querySelector(".pagniation_links");

        if (data.records.total>3) {
            data.records.links.forEach(link=>{
                if(link.url){
                    let liElement = document.createElement("li");
                    let aElement = document.createElement("a");

                    aElement.href = link.url;
                    aElement.innerHTML = link.label;
                    if(link.active) {
                        liElement.classList.add("active");
                    }

                    liElement.appendChild(aElement)
                    paginator.appendChild(liElement)
                }
            })
        }

    }
</script>

<script>
    function fetchCompareList(){
        fetch('{{route("shop.customer.orders.index") }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token if your application uses it
                'X-Requested-With': 'XMLHttpRequest' // Indicate an AJAX request
            }
        })
        .then(response => response.json())
        .then(data => {
            // Handle the data returned from the server
            // console.log(data);
            populateFilters(data);
            populateDataInTable(data);
            populatePagnation(data);

            document.querySelector(".loading").style.display = "none";
            document.querySelector(".account-table-content").style.display="block";
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    fetchCompareList();
</script>


