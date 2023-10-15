@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

{!! view_render_event('bagisto.shop.products.view.attributes.before', ['product' => $product]) !!}

@if ($customAttributeValues = $productViewHelper->getAdditionalData($product))
        <div class=" table-responsive">
            <table class="table">
                @foreach ($customAttributeValues as $attribute)
                    <tr>
                        @if ($attribute['label'])
                            <td><h5>{{ $attribute['label'] }}</h5></td>
                        @else
                            <td><h5>{{ $attribute['admin_name'] }}</h5></td>
                        @endif

                        @if (
                            $attribute['type'] == 'file'
                            && $attribute['value']
                        )
                            <td>
                                <a  href="{{ route('shop.product.file.download', [$product->id, $attribute['id']])}}">
                                    <i class="icon sort-down-icon download"></i>
                                </a>
                            </td>
                        @elseif (
                            $attribute['type'] == 'image'
                            && $attribute['value']
                        )
                            <td>
                                <a href="{{ route('shop.product.file.download', [$product->id, $attribute['id']])}}">
                                    <img src="{{ Storage::url($attribute['value']) }}" style="height: 20px; width: 20px;" alt=""/>
                                </a>
                            </td>
                        @else
                            <td>{{ $attribute['value'] }}</td>
                        @endif
                    </tr>
                @endforeach

            </table>
        </div>
@endif

{!! view_render_event('bagisto.shop.products.view.attributes.after', ['product' => $product]) !!}