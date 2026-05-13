<input type="hidden" id="barcodeDigits">
<input type="hidden" id="saleId" value="{{ request('id') ?? '' }}">
<script src="{{ asset('assets/scripts/jquery-3.6.3.min.js') }}"></script>
<script src="{{ asset('assets/scripts/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/scripts/sweetalert_modify.js') }}"></script>
<!-- Currency JS -->
<script>
    function currencySymbol(number) {
        var symbol = (settings().default_currency && settings().default_currency.symbol) ? settings().default_currency
            .symbol : '$';

        if (settings().currency_position && settings().currency_position === "Prefix") {
            return symbol + ' ' + Number(number).toFixed(2);
        }

        return Number(number).toFixed(2) + ' ' + symbol;
    }

    function settings() {
        return {!! json_encode($general_settings) !!};
    }
</script>
<script>
    const feature = @json(feature('purchases'));
    const featurebarcode = @json(feature('barcodes'));
    $(document).ready(function() {
        $('#zoom-out').hide();
        $('#logout').on('click', function(e) {
            $('#logoutModal').removeClass('invisible');
        });
        $('#closeModalLogout').on('click', function(e) {
            $('#logoutModal').addClass('invisible');
        });
        $(document).on('click', '.productVariantOrSerial', function(e) {
            var id = $(this).attr('data-id');
            $('#productVariantOrSerialModal_' + id).removeClass('invisible');
        })
        $(document).on('click', '.closeProductVariantOrSerial', function(e) {
            var id = $(this).attr('data-id');
            $('#productVariantOrSerialModal_' + id).addClass('invisible');
        });
        $('#confirmLogout').on('click', function(e) {
            window.location.href = '/signout';
        });
        $('#wallet').on('click', function(e) {
            $('#storeWalletModal').removeClass('invisible');
        });
        $('#closeModalWallet').on('click', function(e) {
            $('#storeWalletModal').addClass('invisible');
        });
        $('#zoom-in').on('click', function(e) {
            $('#zoom-in').hide();
            $('#zoom-out').show();
            var elem = document.documentElement; // Fullscreen the entire document
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                /* Firefox */
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
                /* Chrome, Safari & Opera */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                /* IE/Edge */
                elem.msRequestFullscreen();
            }
        });
        $('#zoom-out').on('click', function(e) {
            $('#zoom-in').show();
            $('#zoom-out').hide();
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                /* Safari */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                /* IE11 */
                document.msExitFullscreen();
            }
        });

        function updateTime() {
            var now = new Date();
            var hours = ('0' + now.getHours()).slice(-2);
            var minutes = ('0' + now.getMinutes()).slice(-2);
            var date = now.getDate();
            var month = now.toLocaleString('default', {
                month: 'short'
            });
            var year = now.getFullYear();

            $('#date').text(date + ' ' + month + ', ' + year);
            $('#hours').text(hours);
            $('#minutes').text(minutes);
        }

        updateTime();
        setInterval(updateTime, 1000);

        $('#searchFeaturedProducts').hide();
        $('#searchProducts').hide();
        $('#searchCustomers').hide();
        $('#couponCodeInput').hide();
        $('#removeCouponBtn').hide();
        $('#applyCouponBtn').hide();
        $.ajax({
            url: '/pos/data',
            type: 'GET',
            success: function(response) {
                $('#barcodeDigits').val(response.data.barcodeDigits);
                var categories = $('#categories');
                var brands = $('#brands');
                var featuredProducts = $('#featuredProducts');
                var customerGroupId = $('#customer_group_id');
                response.data.customerGroups.forEach(function(item) {
                    customerGroupId.append(
                        `<option value="${item.id}">${item.name}</option>`);
                });
                console.log(customerGroupId);
                response.data.categories.forEach(function(item) {
                    categories.append(`<div class="relative category-select" data-id="${item.id}">
                                <input type="radio" name="category" class="peer sr-only"/>
                                <label class="w-full p-2 bg-slate-50 dark:bg-slate-600 justify-center items-center gap-2 flex flex-wrap m-0 border-2 border-slate-50 dark:border-slate-600 cursor-pointer hover:primary-border-color-light dark:hover:border-slate-600 dark:hover:bg-slate-700 hover-primary-border-color">
                                    <div class="items-center flex overflow-hidden">
                                        <img class="rounded-[7px] max-h-32" src="${item.thumbnail}" />
                                    </div>
                                    <span class="text-cyan-900 dark:text-slate-100 text-base font-medium leading-tight w-[150px] text-center">
                                        ${item.name}
                                    </span>
                                </label>
                            </div>`);
                });
                response.data.brands.forEach(function(item) {
                    brands.append(`<div class="relative brand-select" data-id="${item.id}">
                                <input type="radio" name="category" class="peer sr-only"/>
                                <label class="w-full p-2 bg-slate-50 dark:bg-slate-600 justify-center items-center gap-2 flex flex-wrap m-0 border-2 border-slate-50 dark:border-slate-600 cursor-pointer hover:primary-border-color-light dark:hover:border-slate-600 dark:hover:bg-slate-700 hover-primary-border-color">
                                    <div class="items-center flex overflow-hidden">
                                        <img class="rounded-[7px] max-h-32" src="${item.thumbnail}" />
                                    </div>
                                    <span class="text-cyan-900 dark:text-slate-100 text-base font-medium leading-tight w-[150px] text-center">
                                        ${item.name}
                                    </span>
                                </label>
                            </div>`);
                });
                response.data.featuredProducts.forEach(function(item) {
                    let stock = `<div class="bg-slate-200 py-1 px-2 rounded-full text-sm text-red-600">
                            Out of Stock
                        </div>`;
                    if (item.stock > 0 || !feature) {
                        stock = `<div class="flex justify-center items-center gap-4 grow">
                            <button class="w-8 h-8 bg-white rounded-[5px] flex justify-center items-center" onclick="minusProduct(${item.id})">
                                <img src="{{ asset('/icons/minus.svg') }}" class="w-4 h-4" />
                            </button>
                            <span class="text-white text-lg font-semibold leading-snug tracking-tight" id="featuredProducts_${item.id}">0</span>
                            <button class="w-8 h-8 bg-white rounded-[5px] flex justify-center items-center" onclick="addProduct(${item.id}, ${item.stock})">
                                <img src="{{ asset('/icons/plus.png') }}" class="w-4 h-4" />
                            </button>
                        </div>`;
                    }
                    let productBarcode = '';
                    if (featurebarcode) {
                        productBarcode = `<div class="text-slate-500 dark:text-slate-400 text-[10px] font-normal leading-3">
                                                ${item.code}
                                            </div>`;
                    }
                    let productDetails = `<div class="group p-2 bg-white dark:bg-slate-600 flex-col justify-center items-center gap-1 flex relative border-2 border-slate-50 dark:border-slate-600" id="featuredProductsborder_${item.id}" data-id="${item.id}">
                                        <div class="items-center flex overflow-hidden">
                                            <img class="rounded-[7px] max-h-36" src="${item.thumbnail}" />
                                        </div>
                                        <div class="flex-col justify-center items-center gap-0.5 flex w-full pt-1">
                                            <div class="text-cyan-900 dark:text-slate-100 text-xs font-bold leading-tight truncate w-full text-center">
                                                ${item.name}
                                            </div>
                                            ${productBarcode}
                                        </div>

                                        <div class="hover-bg-primary-color-rgb h-full w-full group-hover:flex transition justify-center items-center absolute hidden" id="featuredProductsborderhover_${item.id}">
                                            ${stock}
                                        </div>
                                    </div>`;
                    if (feature) {
                        productDetails = `<div class="group p-2 bg-white dark:bg-slate-600 flex-col justify-center items-center gap-1 flex relative border-2 border-slate-50 dark:border-slate-600" id="featuredProductsborder_${item.id}" data-id="${item.id}">
                                        <div class="items-center flex overflow-hidden">
                                            <img class="rounded-[7px] max-h-36" src="${item.thumbnail}" />
                                        </div>
                                        <div class="flex-col justify-center items-center gap-0.5 flex w-full pt-1">
                                            <div class="text-cyan-900 dark:text-slate-100 text-xs font-bold leading-tight truncate w-full text-center">
                                                ${item.name}
                                            </div>
                                            ${productBarcode}
                                            <div class="text-cyan-900 dark:text-slate-100 text-xs font-medium leading-[14.40px]">
                                                In Stock: ${item.stock}
                                            </div>
                                        </div>

                                        <div class="hover-bg-primary-color-rgb h-full w-full group-hover:flex transition justify-center items-center absolute hidden" id="featuredProductsborderhover_${item.id}">
                                            ${stock}
                                        </div>
                                    </div>`;
                    }

                    featuredProducts.append(productDetails);
                });
                selectedProducts();

                response.data.tips.forEach(function(item, index) {
                    $('#tipSelect').append(`<button
                    class="text-xl font-medium border border-slate-300 rounded primary-text-color p-2 flex-grow text-center cursor-pointer tip_button" id="tip_button_${item.id}" onclick="selectTipButton(${item.id}, ${item.percentage})">
                                            ${item.percentage}%
                                            </button>`);
                })
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });

        // Search product
        $(document).ready(function() {
            // Debounce function definition
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            $('#searchFeaturedProductInput').on('keyup', debounce(function(e) {
                $('#searchFeaturedProducts').show().html('');
                var value = $(this).val().trim();
                const barcodeDigits = $('#barcodeDigits').val();

                $.ajax({
                    url: '/product/search',
                    type: 'GET',
                    data: {
                        search: value
                    },
                    success: function(response) {
                        let html = ``;
                        if (response.data.products.length > 0) {
                            console.log(response.data.products);

                            $.each(response.data.products, function(index,
                                item) {
                                html += `<div class="border border-slate-200 bg-slate-50 rounded p-2 cursor-pointer hover:bg-slate-200 product-select" data-id="${item.id}" data-stock="${item.qty}">
                        ${item.name}
                    </div>`;
                            });
                        } else {
                            html = `<div class="border border-slate-200 bg-slate-50 rounded p-2 cursor-pointer hover:bg-slate-200">
                                {{ __('no_result_found') }}
                            </div>`;
                        }

                        $('#searchFeaturedProducts').html(html);
                        // Automatic click if barcode length matches
                        if (value.length === Number(barcodeDigits)) {
                            $('.product-select').first().trigger('click');
                            $('#searchFeaturedProducts').data('request-sent',
                                true);
                        } else {
                            $('#searchFeaturedProducts').removeData(
                                'request-sent');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }, 300)); // Debounce time of 300ms
        });

        $('#searchCustomerInput').on('keyup', function(e) {
            $('#searchCustomers').show();
            var value = $(this).val();
            $.ajax({
                url: '/customer/search',
                type: 'GET',
                data: {
                    search: value
                },
                success: function(response) {
                    var searchCustomers = $('#searchCustomers');
                    response.data.customers.forEach(function(item) {
                        searchCustomers.append(`<div class="border border-slate-200 bg-slate-50 rounded p-2 cursor-pointer hover:bg-slate-200 customer-select" data-name="${item.name}" data-id="${item.id}">
                                            ${item.name}
                                        </div>`);
                    });
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });
        $('body').on('click', function(e) {
            $('#searchFeaturedProductInput').val('');
            $('#searchFeaturedProducts').hide();
            $('#searchProducts').hide();
            $('#searchCustomers').hide();
        });
        $(document).on('click', '.category-select', function(e) {
            var id = $(this).attr('data-id');
            $.ajax({
                url: '/product/search',
                type: 'GET',
                data: {
                    category_id: id
                },
                success: function(response) {
                    var featuredProducts = $('#featuredProducts');
                    featuredProducts.children().remove();
                    response.data.products.forEach(function(item) {
                        let stock = `<div class="bg-slate-200 py-1 px-2 rounded-full text-sm text-red-600">
                            Out of Stock
                        </div>`;
                        if (item.stock > 0 || !feature) {
                            stock = `<div class="flex justify-center items-center gap-4 grow">
                            <button class="w-8 h-8 bg-white rounded-[5px] flex justify-center items-center" onclick="minusProduct(${item.id})">
                                <img src="{{ asset('/icons/minus.svg') }}" class="w-4 h-4" />
                            </button>
                            <span class="text-white text-lg font-semibold leading-snug tracking-tight" id="featuredProducts_${item.id}">0</span>
                            <button class="w-8 h-8 bg-white rounded-[5px] flex justify-center items-center" onclick="addProduct(${item.id}, ${item.stock})">
                                <img src="{{ asset('/icons/plus.png') }}" class="w-4 h-4" />
                            </button>
                        </div>`;
                        }
                        let productBarcode = '';
                        if (featurebarcode) {
                            productBarcode = `<div class="text-slate-500 dark:text-slate-400 text-[10px] font-normal leading-3">
                                                ${item.code}
                                            </div>`;
                        }
                        let productDetails = `<div class="group p-2 bg-white dark:bg-slate-600 flex-col justify-center items-center gap-1 flex relative border-2 border-slate-50 dark:border-slate-600" id="featuredProductsborder_${item.id}" data-id="${item.id}">
                                        <div class="items-center flex overflow-hidden">
                                            <img class="rounded-[7px] max-h-36" src="${item.thumbnail}" />
                                        </div>
                                        <div class="flex-col justify-center items-center gap-0.5 flex w-full pt-1">
                                            <div class="text-cyan-900 dark:text-slate-100 text-xs font-bold leading-tight truncate w-full text-center">
                                                ${item.name}
                                            </div>
                                            ${productBarcode}
                                        </div>

                                        <div class="hover-bg-primary-color-rgb h-full w-full group-hover:flex transition justify-center items-center absolute hidden" id="featuredProductsborderhover_${item.id}">
                                            ${stock}
                                        </div>
                                    </div>`;
                        if (feature) {
                            productDetails = `<div class="group p-2 bg-white dark:bg-slate-600 flex-col justify-center items-center gap-1 flex relative border-2 border-slate-50 dark:border-slate-600" id="featuredProductsborder_${item.id}" data-id="${item.id}">
                                        <div class="items-center flex overflow-hidden">
                                            <img class="rounded-[7px] max-h-36" src="${item.thumbnail}" />
                                        </div>
                                        <div class="flex-col justify-center items-center gap-0.5 flex w-full pt-1">
                                            <div class="text-cyan-900 dark:text-slate-100 text-xs font-bold leading-tight truncate w-full text-center">
                                                ${item.name}
                                            </div>
                                            ${productBarcode}
                                            <div class="text-cyan-900 dark:text-slate-100 text-xs font-medium leading-[14.40px]">
                                                In Stock: ${item.stock}
                                            </div>
                                        </div>

                                        <div class="hover-bg-primary-color-rgb h-full w-full group-hover:flex transition justify-center items-center absolute hidden" id="featuredProductsborderhover_${item.id}">
                                            ${stock}
                                        </div>
                                    </div>`;
                        }

                        featuredProducts.append(productDetails);
                    });
                    selectedProducts();
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });
        $('#categoryBtn').on('click', function(e) {
            $(this).addClass('customActive');
            $('#brandBtn').removeClass('customActive');
            $('#categories').show();
            $('#brands').hide();

            selectedProducts();
        });
        $('#brandBtn').on('click', function(e) {
            $(this).addClass('customActive');
            $('#categoryBtn').removeClass('customActive');
            $('#brands').show();
            $('#categories').hide();

            selectedProducts();
        });
        $(document).on('click', '.brand-select', function(e) {
            var id = $(this).attr('data-id');
            $.ajax({
                url: '/product/search',
                type: 'GET',
                data: {
                    brand_id: id
                },
                success: function(response) {
                    var featuredProducts = $('#featuredProducts');
                    featuredProducts.children().remove();

                    response.data.products.forEach(function(item) {
                        let stock = `<div class="bg-slate-200 py-1 px-2 rounded-full text-sm text-red-600">
                            Out of Stock
                        </div>`;
                        if (item.stock > 0 || !feature) {
                            stock = `<div class="flex justify-center items-center gap-4 grow">
                            <button class="w-8 h-8 bg-white rounded-[5px] flex justify-center items-center" onclick="minusProduct(${item.id})">
                                <img src="{{ asset('/icons/minus.svg') }}" class="w-4 h-4" />
                            </button>
                            <span class="text-white text-lg font-semibold leading-snug tracking-tight" id="

                            ${item.id}">0</span>
                            <button class="w-8 h-8 bg-white rounded-[5px] flex justify-center items-center" onclick="addProduct(${item.id}, ${item.stock})">
                                <img src="{{ asset('/icons/plus.png') }}" class="w-4 h-4" />
                            </button>
                        </div>`;
                        }
                        let productBarcode = '';
                        if (featurebarcode) {
                            productBarcode = `<div class="text-slate-500 dark:text-slate-400 text-[10px] font-normal leading-3">
                                                ${item.code}
                                            </div>`;
                        }
                        let productDetails = `<div class="group p-2 bg-white dark:bg-slate-600 flex-col justify-center items-center gap-1 flex relative border-2 border-slate-50 dark:border-slate-600" id="featuredProductsborder_${item.id}" data-id="${item.id}">
                                        <div class="items-center flex overflow-hidden">
                                            <img class="rounded-[7px] max-h-36" src="${item.thumbnail}" />
                                        </div>
                                        <div class="flex-col justify-center items-center gap-0.5 flex w-full pt-1">
                                            <div class="text-cyan-900 dark:text-slate-100 text-xs font-bold leading-tight truncate w-full text-center">
                                                ${item.name}
                                            </div>
                                            ${productBarcode}
                                        </div>

                                        <div class="hover-bg-primary-color-rgb h-full w-full group-hover:flex transition justify-center items-center absolute hidden" id="featuredProductsborderhover_${item.id}">
                                            ${stock}
                                        </div>
                                    </div>`;
                        if (feature) {
                            productDetails = `<div class="group p-2 bg-white dark:bg-slate-600 flex-col justify-center items-center gap-1 flex relative border-2 border-slate-50 dark:border-slate-600" id="featuredProductsborder_${item.id}" data-id="${item.id}">
                                        <div class="items-center flex overflow-hidden">
                                            <img class="rounded-[7px] max-h-36" src="${item.thumbnail}" />
                                        </div>
                                        <div class="flex-col justify-center items-center gap-0.5 flex w-full pt-1">
                                            <div class="text-cyan-900 dark:text-slate-100 text-xs font-bold leading-tight truncate w-full text-center">
                                                ${item.name}
                                            </div>
                                            ${productBarcode}
                                            <div class="text-cyan-900 dark:text-slate-100 text-xs font-medium leading-[14.40px]">
                                                In Stock: ${item.stock}
                                            </div>
                                        </div>

                                        <div class="hover-bg-primary-color-rgb h-full w-full group-hover:flex transition justify-center items-center absolute hidden" id="featuredProductsborderhover_${item.id}">
                                            ${stock}
                                        </div>
                                    </div>`;
                        }

                        featuredProducts.append(productDetails);
                    });
                    selectedProducts();
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });
        $(document).on('click', '.product-select', function(e) {
            var id = $(this).attr('data-id');
            var stock = Number($(this).attr('data-stock'));
            var qty = Number($('#productQty_' + id).text() ?? 0);

            if (stock > 0 && stock > qty || !feature) {
                productSelect(id);
            } else {
                Toast.fire({
                    icon: 'error',
                    title: "{{ __('out_of_stock') }}"
                })
            }
        });
        removeProductFromCart = (id) => {
            $(`#productSaleRow_${id}`).remove();
            const totalElement = document.getElementsByClassName('productSaleRow')
            if (totalElement.length == 0) {
                $('#noProducts').show();
            }
            $('#featuredProductsborder_' + id).removeClass('primary-boder-color');
            $('#featuredProductsborderhover_' + id).addClass('hidden');
            $('#featuredProductsborderhover_' + id).removeClass('flex');
            $('#featuredProducts_' + id).text(0);
            countQty();
        }
        minusProduct = (id) => {
            if (Number($('#productQty_' + id).text()) > 1)
                $('#productQty_' + id).text(Number($('#productQty_' + id).text()) - 1)

            $('#featuredProducts_' + id).text(Number($('#productQty_' + id).text()));
            countQty();
        }
        addProduct = (id, stock) => {
            if (document.getElementById(`productQty_${id}`)) {
                if (Number($('#productQty_' + id).text()) < stock || !feature) {
                    $('#productQty_' + id).text(Number($('#productQty_' + id).text()) + 1);
                    countQty();
                }
                $('#featuredProductsborder_' + id).addClass('primary-boder-color');
                $('#featuredProductsborderhover_' + id).removeClass('hidden');
                $('#featuredProductsborderhover_' + id).addClass('flex');
                $('#featuredProducts_' + id).text(Number($('#productQty_' + id).text()));
            } else {
                productSelect(id);
            }
        }

        function productSelect(id) {
            $('#noProducts').hide();
            $.ajax({
                url: '/product/details',
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    var product = response.data.product;
                    if (product) {
                        var selectProduct = $(`#productSaleRow_${product.id}`);
                        if (selectProduct.length) {
                            var qty = Number($(`#productQty_${product.id}`).text());
                            $(`#productQty_${product.id}`).text(qty + 1)
                        } else {
                            let hasVarint = `N/A`;
                            if (product.product_variants.length > 0 || product
                                .product_serial_imei_numbers.length > 0) {
                                let serial_numbers = ``;
                                if (product.product_serial_imei_numbers.length > 0) {
                                    product.product_serial_imei_numbers.forEach((serial_number) => {
                                        serial_numbers +=
                                            `<option value="${serial_number.id}">${serial_number.serial_number}</option>`;
                                    });
                                }
                                let variants = '';
                                if (product.product_variants.length > 0) {
                                    product.product_variants.forEach((variant) => {
                                        variants +=
                                            `<option value="${variant.id}">${variant.name}</option>`;
                                    });
                                }
                                hasVarint = `<button class="w-6 h-6 rounded-md bg-blue-500 cursor-pointer hover:bg-blue-700 flex justify-center items-center productVariantOrSerial" data-id="${product.id}">
                                                <img src="{{ asset('/icons/tag.svg') }}" class="w-4 h-4" />
                                            </button>
                                            <div class="fixed z-10 inset-0 invisible overflow-y-auto" aria-labelledby="productVariantOrSerialModalLabel_${product.id}" role="dialog" aria-modal="true"
                                                id="productVariantOrSerialModal_${product.id}">
                                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
                                                    <div
                                                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <div>
                                                                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="productVariantOrSerialModalLabel_${product.id}">
                                                                        {{ __('product_variant_or_serial') }}
                                                                    </h3>
                                                                    <div class="mt-2">
                                                                        <p class="text-sm text-gray-500">
                                                                            {{ __('product_variant_or_serial_description') }}
                                                                        </p>
                                                                    </div>
                                                                    <div class="mt-4">
                                                                        <label for="serial_number_${product.id}" class="block text-sm font-medium text-gray-700 mb-2">{{ __('product_variant') }}</label>
                                                                        <select class="border border-gray-300 rounded-md shadow-sm w-full select2 productVariant" name="variant_ids[]">
                                                                            <option value="">{{ __('select_product_variant') }}</option>
                                                                            ${variants}
                                                                        </select>
                                                                    </div>
                                                                    <div class="mt-4">
                                                                        <label for="serial_number_${product.id}" class="block text-sm font-medium text-gray-700 mb-2">{{ __('serial_imei_numbers') }}</label>
                                                                        <select class="border border-gray-300 rounded-md shadow-sm w-full select2 productSerialNumber" name="serial_imei_numbers[]">
                                                                            <option value="">{{ __('select_serial_imei_numbers') }}</option>
                                                                            ${serial_numbers}
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <button type="button"
                                                                class="mt-3 w-full flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm closeProductVariantOrSerial" data-id="${product.id}">
                                                                {{ __('confirm') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                            }
                            var selectProducts = $('#selectProducts');
                            selectProducts.append(`<tr class="productSaleRow" id="productSaleRow_${product.id}" data-id="${product.id}">
                                <td class="p-2 h-12 text-cyan-900 text-base font-normal border-b primary-border-color">
                                    <a href="javascript:void(0)" class="productPriceCustomizeModal" data-id="${product.id}" style="display: inline-block; max-width: 180px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                    ${product.name}
                                </a>

                            <!-- Product price customization Modal Start -->
                            <div class="fixed z-10 inset-0 invisible overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                                aria-modal="true" id="productPriceCustomizationModal_${product.id}">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                        aria-hidden="true"></div>
                                    <span
                                        class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                        aria-hidden="true">​</span>
                                    <div
                                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="sm:flex sm:items-start">
                                                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900"
                                                        id="modal-title">
                                                        {{ __('product_price_customization') }}
                                                    </h3>
                                                    <div class="mt-2">
                                                        <div
                                                            class="grid gap-4 md:grid-cols-2 mt-2 w-full">
                                                            <div class="mt-3">
                                                                <label class="text-slate-500"
                                                                    for="name">{{ __('product_name') }}
                                                                </label>
                                                                <input type="text"
                                                                    value="${product.name }"
                                                                    disabled
                                                                    id="product_name_${product.id }"
                                                                    placeholder="{{ __('enter_your_product_name') }}"
                                                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2" />
                                                            </div>
                                                            <div class="mt-3">
                                                                <label class="text-slate-500"
                                                                    for="product_price">{{ __('product_price') }}
                                                                    <span
                                                                        class="text-red-500">*</span></label>
                                                                <input type="text"
                                                                    value="${product.price }"
                                                                    id="product_price_${product.id }"
                                                                    data-tax-rate="${product.tax_rate }"
                                                                    name="price"
                                                                    class="border-slate-200 bg-slate-50 rounded-lg placeholder:text-slate-400 focus:ring-slate-300 focus:border-transparent focus:ring-1 w-full mt-2"
                                                                    placeholder="{{ __('enter_your_product_price') }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="button"
                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm submitProductPriceCustomizationModal"
                                                data-id="${product.id}">
                                                {{ __('update_and_save') }}
                                            </button>
                                            <button type="button"
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm closeProductPriceCustomizationModal"
                                                data-id="${product.id}">
                                                {{ __('cancel') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Product price customization Modal End -->
                        </td>
                        <td
                            class="p-2 h-12 text-center text-cyan-900 text-base font-normal border-b primary-border-color flex justify-center items-center">
                            ${hasVarint}
                        </td>
                        <td
                        <td class="p-2 h-12 text-center text-cyan-900 text-base font-normal border-b primary-border-color"
                                            id="productTax_${product.id}">
                            ${currencySymbol(product.tax)}
                        </td>
                        <td class="p-2 h-12 text-center text-cyan-900 text-base font-normal border-b primary-border-color productPrice"
                                            data-price="${product.price}"
                                            id="productPrice_${product.id}">
                            ${currencySymbol(product.price)}
                        </td>
                        <td
                            class="p-2 h-12 text-center text-cyan-900 text-base font-normal border-b primary-border-color">
                            <div class="flex justify-center items-center gap-2">
                                <button
                                    class="w-4 h-4 primary-bg-color rounded-sm flex justify-center items-center" onclick="minusProduct(${product.id})">
                                    <img src="{{ asset('/icons/minus.svg') }}" class="w-4 h-4" />
                                </button>
                                <span class="text-cyan-900 text-base font-normal tracking-tight productQty" id="productQty_${product.id}">
                                    1
                                </span>
                                <button
                                    class="w-4 h-4 primary-bg-color rounded-sm flex justify-center items-center" onclick="addProduct(${product.id}, ${product.stock})">
                                    <img src="{{ asset('/icons/plus.png') }}" class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                        <td
                            class="p-2 h-12 w-24 text-right text-cyan-900 text-base font-normal border-b primary-border-color productSubtotal" id="productSubtotal_${product.id}" data-subtotal="${product.subtotal}">
                            ${currencySymbol(product.subtotal)}
                        </td>
                        <td class="w-12 h-12 border-b primary-border-color text-center">
                            <div class="p-0 m-auto flex w-5 h-5 bg-red-400 rounded-full justify-center items-center cursor-pointer" onclick="removeProductFromCart(${product.id})">
                                <img src="{{ asset('/icons/remove.svg') }}" class="w-2 h-2" />
                            </div>
                        </td>
                    </tr>`);
                        }
                        $('#featuredProductsborder_' + id).addClass('primary-boder-color');
                        $('#featuredProductsborderhover_' + id).removeClass('hidden');
                        $('#featuredProductsborderhover_' + id).addClass('flex');
                        $('#featuredProducts_' + id).text(Number($('#productQty_' + id).text()));
                    }

                    countQty();
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        }
        countQty = function() {
            let totalElement = document.getElementsByClassName('productSaleRow')
            var totalQty = 0;
            var grandSubtotal = 0;
            for (var i = 0; i < totalElement.length; i++) {
                var productQty = totalElement[i].getElementsByClassName('productQty')[0].innerHTML;
                var productSubtotal = totalElement[i].getElementsByClassName('productSubtotal')[0]
                    .getAttribute('data-subtotal');
                var subTotal = Number(productQty) * Number(productSubtotal);
                var grandSubtotal = Number(grandSubtotal) + Number(totalElement[i].getElementsByClassName(
                    'productSubtotal')[0].innerText = subTotal.toFixed(2));
                totalElement[i].getElementsByClassName('productSubtotal')[0].innerText = currencySymbol(
                    subTotal);
                var totalQty = (Number(totalQty) + Number(totalElement[i].getElementsByClassName(
                    'productQty')[0].innerHTML));
            }
            $('#totalDiscount').html(currencySymbol(0.00));
            $('.discount').html(currencySymbol(0.00));
            $('#totalProduct').html(totalElement.length);
            $('#totalItem').html(totalQty);
            $('#totalAmount').html(currencySymbol(grandSubtotal));
            $('#totalGrand').html(currencySymbol(grandSubtotal)).attr('data-grand-price', grandSubtotal);
        }
        $(document).on('click', '#addCouponBtn', function(e) {
            $('#couponCodeInput').show();
            $('#removeCouponBtn').show();
            $('#addCouponBtn').hide();
        });
        $(document).on('click', '#removeCouponBtn', function(e) {
            $('#couponCodeInput').hide();
            $('#removeCouponBtn').hide();
            $('#addCouponBtn').show();
        });
        $(document).on('keyup', '#couponCodeInput', function(e) {
            var value = $(this).val();
            if (value == '') {
                $('#applyCouponBtn').hide();
                $('#removeCouponBtn').show();
            } else {
                $('#applyCouponBtn').show();
                $('#removeCouponBtn').hide();
            }
        });
        $(document).on('click', '#applyCouponBtn', function(e) {
            var value = $('#couponCodeInput').val();
            var price = $('#totalGrand').attr('data-grand-price');
            if (price == 0) {
                Toast.fire({
                    icon: 'error',
                    title: "{{ __('no_product_selected') }}"
                })
            }
            if (value && price > 0) {
                $.ajax({
                    url: '/coupon/apply',
                    type: 'GET',
                    data: {
                        code: value,
                        price: price
                    },
                    success: function(response) {
                        $('#couponCodeInput').hide();
                        $('#removeCouponBtn').hide();
                        $('#applyCouponBtn').hide();
                        $('#addCouponBtn').show();

                        $('#totalDiscount').html(currencySymbol(response.data.discount));
                        $('.discount').html(currencySymbol(response.data.discount)).attr(
                            'data-coupon-id',
                            response.data.id);
                        const grandTotal = $('#totalGrand').attr('data-grand-price');
                        const newGrandTotal = Number(grandTotal) - Number(response.data
                            .discount);
                        $('#totalGrand').html(currencySymbol(newGrandTotal)).attr(
                            'data-grand-price',
                            newGrandTotal);
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                    },
                    error: function(xhr, status, error) {
                        var response = JSON.parse(xhr.responseText);
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        })
                    }
                });
            }
        });
        $('#addCustomerBtn').on('click', function(e) {
            $('#customerAddModal').removeClass('invisible');
        });
        $('#closeModalCustomer').on('click', function(e) {
            $('#customerAddModal').addClass('invisible');
        });
        $(document).on('click', '#submitCustomer', function(e) {
            var customerGroup = $('#customer_group_id').find(":selected").val();
            var name = $('#name').val();
            var phone_number = $('#phone_number').val();
            var email = $('#email').val();
            var password = $('#password').val();
            var tax_number = $('#tax_number').val();
            var address = $('#address').val();
            var country = $('#country').val();
            var city = $('#city').val();
            var state = $('#state').val();
            var post_code = $('#post_code').val();
            $.ajax({
                url: '/customer/add',
                type: 'GET',
                data: {
                    customerGroup: customerGroup,
                    name: name,
                    phone_number: phone_number,
                    email: email,
                    password: password,
                    tax_number: tax_number,
                    address: address,
                    country: country,
                    city: city,
                    state: state,
                    post_code: post_code
                },
                success: function(response) {
                    $('#customer_group_id, #name, #phone_number, #email, #password, #tax_number, #address, #country, #city, #state, #post_code')
                        .val('');

                    $('#customerAddModal').addClass('invisible');
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                },
                error: function(xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    })
                }
            })
        });
        $(document).on('click', '.customer-select', function(e) {
            var name = $(this).attr('data-name');
            var id = $(this).attr('data-id');

            $('#searchCustomerInput').val(name).attr('data-id', id);
        });
        let paypalInitialized = false;

        complate = (type) => {
            const totalGrand = $('#totalGrand').attr('data-grand-price');
            const customer_id = $('#searchCustomerInput').attr('data-id');
            const coupon_id = $('.discount').attr('data-coupon-id');
            const payment_method = $('input[name="payment"]:checked').val();
            let totalElement = document.getElementsByClassName('productSaleRow');
            let qtyArray = [];
            let ProductIdArray = [];
            let ProductPriceArray = [];
            let ProductVariantArray = [];
            let productSerialNumberArray = [];

            for (var i = 0; i < totalElement.length; i++) {
                var productQty = totalElement[i].getElementsByClassName('productQty')[0].innerHTML;
                var productPrice = totalElement[i].getElementsByClassName('productPrice')[0].getAttribute(
                    'data-price');
                var productId = totalElement[i].getAttribute('data-id');
                if (totalElement[i].getElementsByClassName('productVariant')[0]) {
                    var productVariant = totalElement[i].getElementsByClassName('productVariant')[0].value;
                }
                if (totalElement[i].getElementsByClassName('productSerialNumber')[0]) {
                    var productSerialNumber = totalElement[i].getElementsByClassName('productSerialNumber')[
                        0].value;
                }

                qtyArray.push(Number(productQty));
                ProductIdArray.push(Number(productId));
                ProductPriceArray.push(Number(productPrice));
                ProductVariantArray.push(productVariant || null);
                productSerialNumberArray.push(productSerialNumber || null);

                $('#featuredProductsborder_' + productId).removeClass('primary-boder-color');
                $('#featuredProductsborderhover_' + productId).addClass('hidden');
                $('#featuredProductsborderhover_' + productId).removeClass('flex');
                $('#featuredProducts_' + productId).text(0);

            }
            if (totalElement.length == 0) {
                Toast.fire({
                    icon: 'error',
                    title: "{{ __('no_product_selected') }}"
                })
            } else {
                if (payment_method && type === 'Sales') {
                    if (payment_method === 'stripe') {
                        $('#stripeModal').removeClass('hidden');
                        handleStripePayment(totalGrand, type, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id);
                    } else if (payment_method === 'paypal') {
                        $('#paypal-button-container').show();
                        $('.flex-wrap > button').hide()
                        initiatePaypal(totalGrand, type, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id);
                    }else if (payment_method === 'paystack') {
                        initiatePayStack(totalGrand, type, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id);
                    }else if (payment_method === 'razorpay') {
                        initiateRazorpay(totalGrand, type, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id);
                    }else if (payment_method === 'payfast') {
                        initiatePayFastWidget(totalGrand, type, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id);
                    }else if (payment_method === 'orangepay') {
                        $('#orangePayModal').removeClass('hidden');
                        handleOrangePayPayment(totalGrand, type, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id);
                    }else if (payment_method === 'cash') {
                        processSale(type, totalGrand, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id, '', 'cash');
                    }
                }else{
                    $.ajax({
                        url: '/sale/pos',
                        type: 'GET',
                        data: {
                            sale_id: $('#saleId').val(),
                            type: type,
                            paid_amount: totalGrand,
                            qty: qtyArray,
                            price: ProductPriceArray,
                            product_ids: ProductIdArray,
                            product_variant_ids: ProductVariantArray,
                            product_serial_numbers: productSerialNumberArray,
                            customer_id: customer_id,
                            coupon_id: coupon_id,
                            payment_method: null,
                        },
                        success: function(response) {
                            $('#saleId').val(response.data.sale.id);
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                            cancelSale();
                            if (type == 'Sales') {
                                window.location = 'sales/invoice/' + response.data.sale.id;
                            }
                        },
                        error: function(xhr, status, error) {
                            var response = JSON.parse(xhr.responseText);
                            Toast.fire({
                                icon: 'error',
                                title: response.message
                            })
                        }
                    });
                }

        }

        // Stripe payment handling function
        function handleStripePayment(totalGrand, type, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id) {
            var stripe      = Stripe('{{ $stripe_publish_key ?? '' }}');
            var elements    = stripe.elements();
            var cardElement = elements.create('card');
            cardElement.mount('#card-element');

            $('#submit-payment').on('click', function() {
                stripe.createToken(cardElement).then(function(result) {
                    if (result.error) {
                        $('#card-errors').text(result.error.message);
                    } else {
                        var tokenId     = result.token.id;
                        processSale(type, totalGrand, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id, tokenId, 'stripe');
                        $('#stripeModal').addClass('hidden');
                    }
                });
            });

            $('#close-modal-btn, #close-modal-btn-footer').on('click', function() {
                $('#stripeModal').addClass('hidden');
            });
        }

        // PayPal initialization and handling
        function initiatePaypal(totalGrand, type, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id) {
            if (!paypalInitialized) {
                if (typeof paypal !== 'undefined') {
                    $('#paypal-button-container').empty();
                        paypal.Buttons({
                            style: {
                                layout: 'horizontal',
                                color:  'blue',
                                label:  'pay',
                                height: 40
                            },

                            createOrder: function(data, actions) {
                            let items = [];
                            for (let i = 0; i < qtyArray.length; i++) {
                                items.push({
                                    name: "Product ID: " + ProductIdArray[i],
                                    unit_amount: {
                                        currency_code: "USD",
                                        value: ProductPriceArray[i]
                                    },
                                    quantity: qtyArray[i],
                                });
                            }

                            return actions.order.create({
                                intent: "CAPTURE",
                                purchase_units: [{
                                    amount: {
                                        currency_code: "USD",
                                        value: totalGrand * 100,
                                        breakdown: {
                                            item_total: {
                                                currency_code: "USD",
                                                value: totalGrand
                                            }
                                        }
                                    },
                                    items: items
                                }]
                            });
                        },

                        onApprove: function(data, actions) {
                            return actions.order.capture().then(function(details) {
                                var tokenId = details.id;
                                processSale(type, totalGrand, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id, tokenId, 'paypal');
                            }).catch(function(err) {
                                console.error('Capture failed:', err.message, err.stack);
                            });
                        },
                        onError: function(err) {
                            console.error("PayPal Checkout error:", err.message, err.stack);
                            alert('An error occurred during PayPal Checkout. Please try again.');
                        }
                    }).render('#paypal-button-container');
                    paypalInitialized = true;
                } else {
                    console.error('PayPal script is not loaded!');
                    alert('Unable to load PayPal script. Please reload the page.');
                }
            }
        }

        // Razorpay initialization
        function initiateRazorpay(totalGrand, type, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id) {
            let options = {
                key: "{{ $razorpay_publish_key ?? ''}}",
                amount: totalGrand * 100,
                currency: "INR",
                description: "Subscription Payment",
                handler: function(response) {
                    $('#razorpay-payment-id').val(response.razorpay_payment_id);
                    var tokenId = response.razorpay_payment_id;
                    processSale(type, totalGrand, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id, tokenId, 'razorpay');

                },
                prefill: {
                    email: "{{ auth()->user()->email }}",
                    contact: "{{ auth()->user()->phone ?? '' }}"
                },
                theme: {
                    color: "#304FFE"
                }
            };
            let rzp = new Razorpay(options);
            rzp.open();
        }

        // Paystack initialization
        function initiatePayStack(totalGrand, type, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id) {
                let selectedCurrency = "ZAR";
                let handler = PaystackPop.setup({
                    key: "{{ $paystack_publish_key ?? '' }}",
                    email: "{{ auth()->user()->email }}",
                    amount: totalGrand * 100,
                    currency: selectedCurrency,
                    callback: function(response) {
                        var tokenId = response.reference;
                        processSale(type, totalGrand, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id, tokenId, 'paystack');
                    },
                    onClose: function() {
                        alert('Transaction cancelled.');
                    }
                });
                handler.openIframe();
            }
        }

        function initiatePayFastWidget(totalGrand, type, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id) {
                let transactionData = {
                    'merchant_id': payfast_client_id,
                    'merchant_key': payfast_client_secret,
                    'item_name': 'Subscription Payment',
                    'amount': totalGrand,
                    'email_address': '{{ auth()->user()->email }}',
                    'cancel_url': '{{ route("payment.cancel") }}',
                    'notify_url': '{{ route("payment.notify") }}',
                }
                let queryString = $.param(transactionData);
                let payfastUrl = `https://sandbox.payfast.co.za/eng/process?${queryString}`;
                let width = 800;
                let height = 600;
                let left = window.screenLeft + (window.innerWidth - width) / 2;
                let top = window.screenTop + (window.innerHeight - height) / 2;
                if (window.outerWidth && window.outerHeight) {
                    left = window.screenX + (window.outerWidth - width) / 2;
                    top = window.screenY + (window.outerHeight - height) / 2;
                }
                let payfastWindow = window.open(
                    payfastUrl,
                    'PayFast Payment',
                    `width=${width},height=${height},scrollbars=no,toolbar=no,location=no,status=no,menubar=no,left=${Math.max(left, 0)},top=${Math.max(top, 0)}`
                );
                if (!payfastWindow || payfastWindow.closed || typeof payfastWindow.closed === 'undefined') {
                    alert('Pop-up blocked. Please allow pop-ups for this website.');
                    return;
                }
                let pollTimer = window.setInterval(function () {
                    if (payfastWindow.closed) {
                        window.clearInterval(pollTimer);
                        $.ajax({
                            url: '{{ route("payment.notify") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                paymentMethod: 'payfast',
                                transaction_id: '123456789',
                                payment_status: 'success',
                                merchant_id: payfast_client_id,
                                merchant_key: payfast_client_secret,
                                amount: totalGrand,
                                item_name: 'Subscription Payment',
                                email_address: '{{ auth()->user()->email }}',
                            },
                            success: function (response) {
                                if (response.status === 'success') {
                                    var tokenId = response.transaction_id;
                                    processSale(type, totalGrand, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id, tokenId, 'payfast');
                                } else {
                                    alert('Payment verification failed. Please try again.');
                                }
                            },
                            error: function () {
                                alert('An error occurred during payment verification. Please try again.');
                            }
                        });
                    }
                }, 500);

        }

        // Orange payment handling function
        function handleOrangePayPayment(totalGrand, type, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id) {
            $('#orangePayForm').on('submit', function(e) {
                e.preventDefault();
                var phoneNumber = $('input[name="phone_number"]').val();
                var pinCode = $('input[name="pin_code"]').val();
                var tokenId = '32545';
                processSale(
                    type,
                    totalGrand,
                    qtyArray,
                    ProductPriceArray,
                    ProductIdArray,
                    ProductVariantArray,
                    productSerialNumberArray,
                    customer_id,
                    coupon_id,
                    tokenId,
                    'orangepay',
                    phoneNumber,
                    pinCode
                );
                $('#orangePayModal').addClass('hidden');
            });

            $('#close-orange-pay').on('click', function() {
                $('#orangePayModal').addClass('hidden');
            });
        }


        function processSale(type, totalGrand, qtyArray, ProductPriceArray, ProductIdArray, ProductVariantArray, productSerialNumberArray, customer_id, coupon_id, tokenId=null, payment_method, phoneNumber = null, pinCode = null){
            $.ajax({
                url: '/sale/pos',
                type: 'GET',
                data: {
                    sale_id: $('#saleId').val(),
                    type: type,
                    paid_amount: totalGrand,
                    qty: qtyArray,
                    price: ProductPriceArray,
                    product_ids: ProductIdArray,
                    product_variant_ids: ProductVariantArray,
                    product_serial_numbers: productSerialNumberArray,
                    customer_id: customer_id,
                    coupon_id: coupon_id,
                    payment_method: payment_method,
                    token_id: tokenId,
                    phoneNumber: phoneNumber ?? null,
                    pinCode: pinCode ?? null,
                },
                success: function(response) {
                    $('#saleId').val(response.data.sale.id);
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                    cancelSale();
                    if (type == 'Sales') {
                        window.location = 'sales/invoice/' + response.data.sale.id;
                    }
                },
                error: function(xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    })
                }
            });
        }

        $('#close-payment').on('click', function () {
            $('#stripeModal').addClass('hidden');
        });
        $('#close-orange-pay').on('click', function () {
            $('#orangePayModal').addClass('hidden');
        });
        $('.payment_method').on('click', function () {
            $('.save_and_complate').removeAttr('disabled');
            var method = $(this).val();
            if (method != 'paypal') {
                $('#paypal-button-container').hide();
                $('.flex-wrap > button').show();
            }
        });

        cancelSale = () => {
            let totalElement = document.getElementsByClassName('productSaleRow');
            for (var i = 0; i < totalElement.length; i++) {
                var productId = totalElement[i].getAttribute('data-id');
                $('#featuredProductsborder_' + productId).removeClass('primary-boder-color');
                $('#featuredProductsborderhover_' + productId).addClass('hidden');
                $('#featuredProductsborderhover_' + productId).removeClass('flex');
                $('#featuredProducts_' + productId).text(0);
            }
            $('.productSaleRow').remove();
            $('#noProducts').show();
            countQty();
        }
        selectedProducts = () => {
            let totalElement = document.getElementsByClassName('productSaleRow');
            for (var i = 0; i < totalElement.length; i++) {
                var id = totalElement[i].getAttribute('data-id');
                $('#featuredProductsborder_' + id).addClass('primary-boder-color');
                $('#featuredProductsborderhover_' + id).removeClass('hidden');
                $('#featuredProductsborderhover_' + id).addClass('flex');
                $('#featuredProducts_' + id).text(Number($('#productQty_' + id).text()));

            }
        }
        $(document).on('click', '.productPriceCustomizeModal', function(e) {
            let id = $(this).attr('data-id');
            $('#productPriceCustomizationModal_' + id).removeClass('invisible');
        });
        $(document).on('click', '.closeProductPriceCustomizationModal', function(e) {
            let id = $(this).attr('data-id');
            $('#productPriceCustomizationModal_' + id).addClass('invisible');
        });
        $(document).on('click', '.submitProductPriceCustomizationModal', function(e) {
            var id = $(this).attr('data-id');
            var price = $('#productPriceCustomizationModal_' + id + ' input[name="price"]').val();
            var taxRate = $('#productPriceCustomizationModal_' + id + ' input[name="price"]').attr(
                'data-tax-rate');
            var tax = taxRate ?? 0;
            if (tax > 0) {
                tax = Number(price) * Number(taxRate) / 100;
            }
            var subtotal = Number(price) + Number(tax);
            var qty = $('#productQty_' + id).text();
            $('#productTax_' + id).text(currencySymbol(Number(tax)));
            $('#productPrice_' + id).text(currencySymbol(Number(price))).attr('data-price', Number(
                price));
            $('#productSubtotal_' + id).text(currencySymbol(Number(subtotal) * Number(qty))).attr(
                'data-subtotal', subtotal);
            $('#productPriceCustomizationModal_' + id).addClass('invisible');
            countQty();
        });




        selectTipButton = (id, percentage = null) => {
            const amount = $('#payment_terminal_amount').attr('data-amount');
            let tip = 0;
            if (percentage) {
                tip = Number(amount) * Number(percentage) / 100;
            }
            tipCalculation(amount, tip, id);
            $('.tip_button').removeClass('tipActive');
            $('#tip_button_' + id).addClass('tipActive');
        }
        tipCalculation = (amount, tip = 0, id = null) => {
            let tip_id = id;
            if (typeof id === 'string') {
                tip_id = null;
            }
            $('#tips_value').val(tip).attr('data-tips-id', tip_id);
            const totalAmount = Number(amount) + Number(tip);
            $('#payment_terminal_total').text(currencySymbol(totalAmount));
            $('#payment_terminal_subtotal').text(currencySymbol(amount));
            $('#payment_terminal_tip_amount').text(currencySymbol(tip));
        }
        keyClick = (key) => {
            formatNumber(key);
        }
        formatNumber = (newDigit) => {
            let display = $('#payment_terminal_amount').attr('data-amount');
            display = display.replace('.', '');
            display += newDigit;
            display = parseFloat(display) / 100;
            $('#payment_terminal_amount').text(display.toFixed(2)).attr('data-amount', display.toFixed(
                2));
        }
        $('#backspace').on('click', function() {
            let display = $('#payment_terminal_amount').attr('data-amount');
            display = display.replace('.', '');
            display = display.slice(0, -1);
            if (display === '') {
                display = '0';
            }
            display = parseFloat(display) / 100;
            $('#payment_terminal_amount').text(display.toFixed(2)).attr('data-amount', display.toFixed(
                2));
        });
    });
</script>
