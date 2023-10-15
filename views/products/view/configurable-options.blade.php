@if (Webkul\Product\Helpers\ProductType::hasVariants($product->type))

    @inject ('configurableOptionHelper', 'Webkul\Product\Helpers\ConfigurableOption')

    {!! view_render_event('bagisto.shop.products.view.configurable-options.before', ['product' => $product]) !!}

    @php
        $defaultVariant = $product->getTypeInstance()->getDefaultVariant();
        
        $config = $configurableOptionHelper->getConfigurationConfig($product);
    @endphp

    <div class="attributes">

        
        {{json_encode($config["attributes"])}}
        /n /n /n /n 
        {{json_encode($defaultVariant)}}

        <div>
            <br>
            {{json_encode($config)}}
        </div>

        <input type="hidden" id="selected_configurable_option" name="selected_configurable_option" :value="selectedProductId">

        <div v-for='(attribute, index) in childAttributes' class="attribute control-group" :class="[errors.has('super_attribute[' + attribute.id + ']') ? 'has-error' : '']">
            @foreach ($config["attributes"] as $attribute)  
                <label class="required">{{ $attribute['label'] }}</label>

                @if( !$attribute['swatch_type'] || $attribute['swatch_type'] == '' || $attribute['swatch_type'] == 'dropdown')
                    <span>
                        <select
                            class="control"
                            v-validate="'required'"
                            :name="['super_attribute[' + attribute.id + ']']"
                            :disabled="attribute.disabled"
                            onchange="configure(attribute, $event.target.value)"
                            :id="['attribute_' + attribute.id]"
                            :data-vv-as="'&quot;' + attribute.label + '&quot;'">

                            @foreach ($attribute["options"] as $option)
                                <option
                                    v-for='(option, index) in attribute.options' value="{{$option['id']}}"
                                    :selected="index == attribute.selectedIndex">
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </span>
                @else
                    <span class="swatch-container" v-else>
                        <label class="swatch"
                            v-for='(option, index) in attribute.options'
                            v-if="option.id"
                            :data-id="option.id"
                            :for="['attribute_' + attribute.id + '_option_' + option.id]">
                            @foreach ($collection as $item)

                                <input type="radio"
                                    v-validate="'required'"
                                    :name="['super_attribute[' + attribute.id + ']']"
                                    :id="['attribute_' + attribute.id + '_option_' + option.id]"
                                    :value="option.id"
                                    :data-vv-as="'&quot;' + attribute.label + '&quot;'"
                                    @change="configure(attribute, $event.target.value)"
                                    :checked="index == attribute.selectedIndex"/>

                                <span v-if="attribute.swatch_type == 'color'" :style="{ background: option.swatch_value }"></span>

                                <img v-if="attribute.swatch_type == 'image'" :src="option.swatch_value" :title="option.label" alt="" />

                                <span v-if="attribute.swatch_type == 'text'">
                                    @{{ option.label }}
                                </span>

                            @endforeach
                        </label>

                        <span v-if="! attribute.options.length" class="no-options">{{ __('shop::app.products.select-above-options') }}</span>
                    </span>
                @endif

                @isset($errors)
                    <span class="control-error">
                        {{$errors->first('super_attribute['.$attribute["id"].']')}}
                    </span>
                @endisset

            @endforeach
        </div>
    </div>

    <product-options></product-options>

    {!! view_render_event('bagisto.shop.products.view.configurable-options.after', ['product' => $product]) !!}

    @push('scripts')

        <script type="text/x-template" id="product-options-template">
            <div class="attributes">

                <input type="hidden" id="selected_configurable_option" name="selected_configurable_option" :value="selectedProductId">

                <div v-for='(attribute, index) in childAttributes' class="attribute control-group" :class="[errors.has('super_attribute[' + attribute.id + ']') ? 'has-error' : '']">
                    <label class="required">@{{ attribute.label }}</label>

                    <span v-if="! attribute.swatch_type || attribute.swatch_type == '' || attribute.swatch_type == 'dropdown'">
                        <select
                            class="control"
                            v-validate="'required'"
                            :name="['super_attribute[' + attribute.id + ']']"
                            :disabled="attribute.disabled"
                            @change="configure(attribute, $event.target.value)"
                            :id="['attribute_' + attribute.id]"
                            :data-vv-as="'&quot;' + attribute.label + '&quot;'">

                            <option
                                v-for='(option, index) in attribute.options' :value="option.id"
                                :selected="index == attribute.selectedIndex">
                                @{{ option.label }}
                            </option>

                        </select>
                    </span>

                    <span class="swatch-container" v-else>
                        <label class="swatch"
                            v-for='(option, index) in attribute.options'
                            v-if="option.id"
                            :data-id="option.id"
                            :for="['attribute_' + attribute.id + '_option_' + option.id]">

                            <input type="radio"
                                v-validate="'required'"
                                :name="['super_attribute[' + attribute.id + ']']"
                                :id="['attribute_' + attribute.id + '_option_' + option.id]"
                                :value="option.id"
                                :data-vv-as="'&quot;' + attribute.label + '&quot;'"
                                @change="configure(attribute, $event.target.value)"
                                :checked="index == attribute.selectedIndex"/>

                            <span v-if="attribute.swatch_type == 'color'" :style="{ background: option.swatch_value }"></span>

                            <img v-if="attribute.swatch_type == 'image'" :src="option.swatch_value" :title="option.label" alt="" />

                            <span v-if="attribute.swatch_type == 'text'">
                                @{{ option.label }}
                            </span>

                        </label>

                        <span v-if="! attribute.options.length" class="no-options">{{ __('shop::app.products.select-above-options') }}</span>
                    </span>

                    <span class="control-error" v-if="errors.has('super_attribute[' + attribute.id + ']')">
                        @{{ errors.first('super_attribute[' + attribute.id + ']') }}
                    </span>
                </div>

            </div>
        </script>

        <script>

            Vue.component('product-options', {
                template: '#product-options-template',

                inject: ['$validator'],

                data: function() {
                    return {
                        defaultVariant: @json($defaultVariant),

                        config: @json($config),

                        childAttributes: [],

                        selectedProductId: '',

                        simpleProduct: null,

                        galleryImages: [],
                    }
                },

                mounted: function() {
                    this.init();

                    this.initDefaultSelection();
                },

                methods: {
                    init: function () {
                        let config = @json($config);

                        let childAttributes = this.childAttributes,
                            attributes = config.attributes.slice(),
                            index = attributes.length,
                            attribute;

                        while (index--) {
                            attribute = attributes[index];

                            attribute.options = [];

                            if (index) {
                                attribute.disabled = true;
                            } else {
                                this.fillSelect(attribute);
                            }

                            attribute = Object.assign(attribute, {
                                childAttributes: childAttributes.slice(),
                                prevAttribute: attributes[index - 1],
                                nextAttribute: attributes[index + 1]
                            });

                            childAttributes.unshift(attribute);
                        }
                    },

                    initDefaultSelection: function() {
                        if (this.defaultVariant) {
                            this.childAttributes.forEach((attribute) => {
                                let attributeValue = this.defaultVariant[attribute.code];

                                this.configure(attribute, attributeValue);
                            });
                        }
                    },

                    configure: function(attribute, value) {
                        this.simpleProduct = this.getSelectedProductId(attribute, value);

                        if (value) {
                            attribute.selectedIndex = this.getSelectedIndex(attribute, value);

                            if (attribute.nextAttribute) {
                                attribute.nextAttribute.disabled = false;

                                this.fillSelect(attribute.nextAttribute);

                                this.resetChildren(attribute.nextAttribute);
                            } else {
                                this.selectedProductId = this.simpleProduct;
                            }
                        } else {
                            attribute.selectedIndex = 0;

                            this.resetChildren(attribute);

                            this.clearSelect(attribute.nextAttribute)
                        }

                        this.reloadPrice();
                        this.changeProductImages();
                        this.changeStock(this.simpleProduct);
                    },

                    getSelectedIndex: function(attribute, value) {
                        let selectedIndex = 0;

                        attribute.options.forEach(function(option, index) {
                            if (option.id == value) {
                                selectedIndex = index;
                            }
                        })

                        return selectedIndex;
                    },

                    getSelectedProductId: function(attribute, value) {
                        let options = attribute.options,
                            matchedOptions;

                        matchedOptions = options.filter(function (option) {
                            return option.id == value;
                        });

                        if (matchedOptions[0] != undefined && matchedOptions[0].allowedProducts != undefined) {
                            return matchedOptions[0].allowedProducts[0];
                        }

                        return undefined;
                    },

                    fillSelect: function(attribute) {
                        let options = this.getAttributeOptions(attribute.id),
                            prevOption,
                            index = 1,
                            allowedProducts,
                            i,
                            j;

                        this.clearSelect(attribute)

                        attribute.options = [{'id': '', 'label': this.config.chooseText, 'products': []}];

                        if (attribute.prevAttribute) {
                            prevOption = attribute.prevAttribute.options[attribute.prevAttribute.selectedIndex];
                        }

                        if (options) {
                            for (i = 0; i < options.length; i++) {
                                allowedProducts = [];

                                if (prevOption) {
                                    for (j = 0; j < options[i].products.length; j++) {
                                        if (prevOption.allowedProducts && prevOption.allowedProducts.indexOf(options[i].products[j]) > -1) {
                                            allowedProducts.push(options[i].products[j]);
                                        }
                                    }
                                } else {
                                    allowedProducts = options[i].products.slice(0);
                                }

                                if (allowedProducts.length > 0) {
                                    options[i].allowedProducts = allowedProducts;

                                    attribute.options[index] = options[i];

                                    index++;
                                }
                            }
                        }
                    },

                    resetChildren: function(attribute) {
                        if (attribute.childAttributes) {
                            attribute.childAttributes.forEach(function (set) {
                                set.selectedIndex = 0;
                                set.disabled = true;
                            });
                        }
                    },

                    clearSelect: function (attribute) {
                        if (! attribute)
                            return;

                        if (! attribute.swatch_type || attribute.swatch_type == '' || attribute.swatch_type == 'dropdown') {
                            let element = document.getElementById("attribute_" + attribute.id);

                            if (element) {
                                element.selectedIndex = "0";
                            }
                        } else {
                            let elements = document.getElementsByName('super_attribute[' + attribute.id + ']');

                            let self = this;

                            elements.forEach(function(element) {
                                element.checked = false;
                            })
                        }
                    },

                    getAttributeOptions: function (attributeId) {
                        let self = this,
                            options;

                        this.config.attributes.forEach(function(attribute, index) {
                            if (attribute.id == attributeId) {
                                options = attribute.options;
                            }
                        })

                        return options;
                    },

                    reloadPrice: function () {
                        let selectedOptionCount = 0;

                        this.childAttributes.forEach(function(attribute) {
                            if (attribute.selectedIndex) {
                                selectedOptionCount++;
                            }
                        });

                        let priceLabelElement = document.querySelector('.price-label');
                        let priceElement = document.querySelector('.special-price') ? document.querySelector('.special-price') : document.querySelector('.final-price');
                        let regularPriceElement = document.querySelector('.regular-price');

                        if (this.childAttributes.length == selectedOptionCount) {
                            priceLabelElement.style.display = 'none';

                            if (regularPriceElement) {
                                regularPriceElement.style.display = 'none';
                            }

                            priceElement.innerHTML = this.config.variant_prices[this.simpleProduct].final_price.formatted_price;

                            if (regularPriceElement && this.config.variant_prices[this.simpleProduct].final_price.price < this.config.variant_prices[this.simpleProduct].regular_price.price) {
                                regularPriceElement.innerHTML = this.config.variant_prices[this.simpleProduct].regular_price.formatted_price;
                                regularPriceElement.style.display = 'inline-block';
                            }

                            eventBus.$emit('configurable-variant-selected-event', this.simpleProduct)
                        } else {
                            priceLabelElement.style.display = 'inline-block';

                            priceElement.innerHTML = this.config.regular_price.formatted_price;

                            eventBus.$emit('configurable-variant-selected-event', 0)
                        }
                    },

                    changeProductImages: function () {
                        galleryImages.splice(0, galleryImages.length)

                        if (this.simpleProduct) {
                            this.config.variant_images[this.simpleProduct].forEach(function(image) {
                                galleryImages.push(image)
                            });

                            this.config.variant_videos[this.simpleProduct].forEach(function(video) {
                                galleryImages.push(video)
                            });
                        }

                        this.galleryImages.forEach(function(image) {
                            galleryImages.push(image)
                        });
                    },

                    changeStock: function (productId) {
                        let inStockElement = document.querySelector('.stock-status');

                        if (productId) {
                            inStockElement.style.display= "block";
                        } else {
                            inStockElement.style.display= "none";
                        }
                    },
                }
            });

        </script>

        <script>
            let defaultVariant= @json($defaultVariant);

            let config= @json($config);

            let childAttributes= [];

            let selectedProductId= '';

            let simpleProduct= null;

            let galleryImages= [];

            function init(){
                let config1 = @json($config);

                let childAttributes2 = childAttributes,
                    attributes = config1.attributes.slice(),
                    index = attributes.length,
                    attribute;

                while (index--) {
                    attribute = attributes[index];

                    attribute.options = [];

                    if (index) {
                        attribute.disabled = true;
                    } else {
                        fillSelect(attribute);
                    }

                    attribute = Object.assign(attribute, {
                        childAttributes: childAttributes2.slice(),
                        prevAttribute: attributes[index - 1],
                        nextAttribute: attributes[index + 1]
                    });

                    childAttributes2.unshift(attribute);
                }
            }

            function initDefaultSelection() {
                if (defaultVariant) {
                    childAttributes.forEach((attribute) => {
                        let attributeValue = defaultVariant[attribute.code];

                        configure(attribute, attributeValue);
                    });
                }
            }

            function configure(attribute, value) {
                simpleProduct = getSelectedProductId(attribute, value);
               
                if (value) {
                    attribute.selectedIndex = getSelectedIndex(attribute, value);

                    if (attribute.nextAttribute) {
                        attribute.nextAttribute.disabled = false;
                        fillSelect(attribute.nextAttribute);
                        resetChildren(attribute.nextAttribute);
                    }else {
                        selectedProductId = simpleProduct;
                    }

                }else {
                    attribute.selectedIndex = 0;

                    resetChildren(attribute);

                    clearSelect(attribute.nextAttribute)
                }

                reloadPrice();
                changeProductImages();
                changeStock(simpleProduct);
            }

            function getSelectedIndex(attribute, value) {
                let selectedIndex = 0;

                attribute.options.forEach(function(option, index) {
                    if (option.id == value) {
                        selectedIndex = index;
                    }
                })

                return selectedIndex;
            }

            function getSelectedProductId(attribute, value) {
                let options = attribute.options,
                    matchedOptions;

                matchedOptions = options.filter(function (option) {
                    return option.id == value;
                });

                if (matchedOptions[0] != undefined && matchedOptions[0].allowedProducts != undefined) {
                    return matchedOptions[0].allowedProducts[0];
                }

                return undefined;
            }
 
            function fillSelect(attribute) {
                let options = getAttributeOptions(attribute.id),
                    prevOption,
                    index = 1,
                    allowedProducts,
                    i,
                    j;

                clearSelect(attribute)

                attribute.options = [{'id': '', 'label': config.chooseText, 'products': []}];

                if (attribute.prevAttribute) {
                    prevOption = attribute.prevAttribute.options[attribute.prevAttribute.selectedIndex];
                }

                if (options) {
                    for (i = 0; i < options.length; i++) {
                        allowedProducts = [];

                        if (prevOption) {
                            for (j = 0; j < options[i].products.length; j++) {
                                if (prevOption.allowedProducts && prevOption.allowedProducts.indexOf(options[i].products[j]) > -1) {
                                    allowedProducts.push(options[i].products[j]);
                                }
                            }
                        } else {
                            allowedProducts = options[i].products.slice(0);
                        }

                        if (allowedProducts.length > 0) {
                            options[i].allowedProducts = allowedProducts;

                            attribute.options[index] = options[i];

                            index++;
                        }
                    }
                }
            }

            function resetChildren(attribute) {
                if (attribute.childAttributes) {
                    attribute.childAttributes.forEach(function (set) {
                        set.selectedIndex = 0;
                        set.disabled = true;
                    });
                }
            }

            function clearSelect(attribute) {
                if (! attribute)
                    return;

                if (! attribute.swatch_type || attribute.swatch_type == '' || attribute.swatch_type == 'dropdown') {
                    let element = document.getElementById("attribute_" + attribute.id);

                    if (element) {
                        element.selectedIndex = "0";
                    }
                } else {
                    let elements = document.getElementsByName('super_attribute[' + attribute.id + ']');

                    let self = this;

                    elements.forEach(function(element) {
                        element.checked = false;
                    })
                }
            }

            function getAttributeOptions(attributeId) {
                let self = this,
                    options;

                this.config.attributes.forEach(function(attribute, index) {
                    if (attribute.id == attributeId) {
                        options = attribute.options;
                    }
                })

                return options;
            }

            function reloadPrice() {
                let selectedOptionCount = 0;

                this.childAttributes.forEach(function(attribute) {
                    if (attribute.selectedIndex) {
                        selectedOptionCount++;
                    }
                });

                let priceLabelElement = document.querySelector('.price-label');
                let priceElement = document.querySelector('.special-price') ? document.querySelector('.special-price') : document.querySelector('.final-price');
                let regularPriceElement = document.querySelector('.regular-price');

                if (this.childAttributes.length == selectedOptionCount) {
                    priceLabelElement.style.display = 'none';

                    if (regularPriceElement) {
                        regularPriceElement.style.display = 'none';
                    }

                    priceElement.innerHTML = this.config.variant_prices[this.simpleProduct].final_price.formatted_price;

                    if (regularPriceElement && this.config.variant_prices[this.simpleProduct].final_price.price < this.config.variant_prices[this.simpleProduct].regular_price.price) {
                        regularPriceElement.innerHTML = this.config.variant_prices[this.simpleProduct].regular_price.formatted_price;
                        regularPriceElement.style.display = 'inline-block';
                    }

                    eventBus.$emit('configurable-variant-selected-event', this.simpleProduct)
                } else {
                    priceLabelElement.style.display = 'inline-block';

                    priceElement.innerHTML = this.config.regular_price.formatted_price;

                    eventBus.$emit('configurable-variant-selected-event', 0)
                }
            }

            function changeProductImages() {
                galleryImages.splice(0, galleryImages.length)

                if (this.simpleProduct) {
                    this.config.variant_images[this.simpleProduct].forEach(function(image) {
                        galleryImages.push(image)
                    });

                    this.config.variant_videos[this.simpleProduct].forEach(function(video) {
                        galleryImages.push(video) 
                    });
                }

                this.galleryImages.forEach(function(image) {
                    galleryImages.push(image)
                });
            }

            function changeStock(productId) {
                let inStockElement = document.querySelector('.stock-status');

                if (productId) {
                    inStockElement.style.display= "block";
                } else {
                    inStockElement.style.display= "none";
                }
            }

            init();
            initDefaultSelection();
        </script>
    @endpush
@endif