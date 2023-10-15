
<div class="review_box">
    <h4>{{ __('shop::app.reviews.write-review') }}</h4>
    <p>  {{ __('admin::app.customers.reviews.rating') }}</p>
    <ul class="list">
        @for ($i = 1; $i <= 5; $i++)
            <li style="color: #fbd600;"><i class="fa fa-star" for="star-{{ $i }}" onclick="calculateRating(id)" id="{{ $i }}"></i></li>
        @endfor
    </ul>
    <p>Outstanding</p>

    <form method="POST" action="{{ route('shop.reviews.store', $product->id ) }}" enctype="multipart/form-data" class="form-contact form-review mt-3">
        @csrf
        <input type="hidden" id="rating" name="rating" value="5" >
        <div class="control-error">@isset($errors) @if($errors->has("rating")) {{$errors->first("rating")}} @endif @endisset </div>

        @if (core()->getConfigData('catalog.products.review.guest_review') && ! auth()->guard('customer')->user())
            <div class="form-group" :class="[errors.has('name') ? 'has-error' : '']">
                <input  class="form-control" name="name" type="text" placeholder="{{ __('shop::app.reviews.name') }}" value="{{ old('name') }}" required>
            </div>
        @endif

        <div class="form-group">
            <input class="form-control" name="title" type="text" placeholder="{{ __('shop::app.reviews.title') }}" required value="{{ old('title') }}" >
            <span class="control-error">@isset($errors) @if($errors->has("title")) {{$errors->first("title")}} @endif @endisset</span>
        </div>

        <div class="form-group">
            <textarea 
                class="form-control different-control w-100" 
                name="comment"  
                id="textarea" cols="30" rows="5" 
                placeholder="{{ __('admin::app.customers.reviews.comment') }}"
                required
                value="{{ old('comment') }}"
            ></textarea>
            <span class="control-error">@isset($errors) @if($errors->has("comment")) {{$errors->first("comment")}} @endif @endisset</span>
        </div>

        <div class="form-group text-center text-md-right mt-3">
            <button type="submit" class="button button--active button-review">{{ __('shop::app.reviews.submit') }}</button>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        function calculateRating(id) {
            var a = document.getElementById(id);
            document.getElementById("rating").value = id;

            for (let i=1 ; i <= 5 ; i++) {
                if (id >= i) {
                    document.getElementById(i).style.color="#fbd600";
                } else {
                    document.getElementById(i).style.color="#d4d4d4";
                }
            }
        }
    </script>
@endpush