@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')

{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}

@if ($total = $reviewHelper->getTotalReviews($product))
    <div class="row">
        <div class="col-lg-6">
            <div class="row total_rate">
                <div class="col-lg-6">
                    <div class="box_total">
                        <h5>Overall</h5>
                        <h4>{{$reviewHelper->getAverageRating($product)}}</h4>
                        <h6>({{ __('shop::app.products.total-reviews', ['total' => $total]) }})</h6>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="rating_list">
                        <h3>Based on {{$total}} Reviews</h3>
                        <ul class="list">
                            @foreach ($reviewHelper->getPercentageRating($product) as $key => $count)
                                <li>{{$key}} Star 
                                    @for($i = 1; $i <= $key; $i++)
                                        <i class="fa fa-star" style="color: #fbd600;"></i>
                                    @endfor
                                    @for($i = $key; $i < 5; $i++)
                                        <i class="fa fa-star" style="color: #d4d4d4;"></i>
                                    @endfor
                                    {{$count}}%
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="review_list">
                @foreach ($reviewHelper->getReviews($product)->paginate(10) as $review)
                    <div class="review_item">
                        <div class="media">
                            <div class="d-flex">
                                <img src="img/product/review-1.png" alt="">
                            </div>
                            <div class="media-body">
                                <h4>{{$review->name}}</h4>
                                @for ($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fa fa-star"></i>
                                    @else
                                        <span class="fa fa-star" style="color: #d4d4d4"></span>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <div>
                            <span> {{ $review->title }}</span>
                            <p>{{ $review->comment }}</p>
                        </div>
                        <div class="d-flex justify-content-end">
                            <span class="when">
                                {{ core()->formatDate($review->created_at, 'F d, Y') }}
                            </span>
                        </div>
                    </div>
                @endforeach
                <hr>
                <a href="{{ route('shop.reviews.index', $product->url_key) }}" class="view-all">
                    {{ __('shop::app.products.view-all') }}
                </a>
            </div>
        </div>
        <div class="col-lg-6">
            @include('shop::products.reviews.create')
        </div>
    </div>
@else
    @if (
            core()->getConfigData('catalog.products.review.guest_review')
            || auth()->guard('customer')->check()
        )
        <div class="row">
            <div class="col-lg-6 d-flex align-items-center">
                <div class="review_box text-center ">
                    <p>no reviews yet !!</p>
                    <h2>Be the first one and add you Review</h2>
                    <h4>Thank You</h4>
                </div>
            </div>
            <div class="col-lg-6" id="review_rate_form">
                @include('shop::products.reviews.create')
            </div>
        </div>
    @endif
@endif

@push('css')
    <style>
        .when{
            font-size: 12px;
            color:#999;
        }
        .review_list{
            overflow-y: auto;
            height: 200px;
        }
    </style>
@endpush

{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}
