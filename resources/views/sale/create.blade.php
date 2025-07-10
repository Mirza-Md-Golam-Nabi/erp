<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sale Create') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div><a href="{{ route('sale.list') }}" class="btn btn-sm btn-primary">Sale List</a></div>
                    <div id="response-message" class="mt-2 text-end"></div>
                    <form id="sales_form">
                        @csrf
                        <div class="mb-3">
                            <label for="customer" class="form-label">Customer Name</label>
                            <select class="form-select" aria-label="Customer Name" id="customer" name="customer_id">
                                <option>Select Customer</option>
                                @forelse ($customers as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @empty
                                    <option>There is no data</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sale_date" class="form-label">Sale Date</label>
                            <input type="date" class="form-control" name="sale_date" value={{ now() }}>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <input type="text" class="form-control" name="note">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <div id="product-container">
                                <div class="row product-row mb-2">
                                    <div class="col-3">
                                        <select class="form-select product-select" aria-label="Product Name"
                                            id="product" name="products[0][product_id]">
                                            <option>Select Product</option>
                                            @forelse ($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                    {{ $product->name }}</option>
                                            @empty
                                                <option>There is no product</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <input type="text" class="form-control quantity" name="products[0][quantity]"
                                            id="quantity" placeholder="Quantity">
                                    </div>
                                    <div class="col-2">
                                        <input type="text" class="form-control price bg-light"
                                            name="products[0][price]" id="price" placeholder="Price" readonly>
                                    </div>
                                    <div class="col-2">
                                        <input type="text" class="form-control discount" name="products[0][discount]"
                                            id="discount" placeholder="Discount">
                                    </div>
                                    <div class="col-2">
                                        <input type="text" class="form-control subtotal bg-light"
                                            placeholder="Subtotal" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-auto" style="margin-right: 8.33%;">
                                    <label><strong>Total:</strong></label>
                                    <input type="text" class="form-control d-inline w-auto bg-light" id="total"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success btn-sm" id="add_more">Add More</button>
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        function calculateSubtotal(row) {
            let qty = parseFloat(row.find('.quantity').val()) || 0;
            let price = parseFloat(row.find('.price').val()) || 0;
            let discount = parseFloat(row.find('.discount').val()) || 0;

            let subtotal = (qty * price) - discount;
            row.find('.subtotal').val(subtotal.toFixed(2));
        }

        function calculateTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#total').val(total.toFixed(2));
        }

        $(document).ready(function() {
            $(document).on('change', '.product-select', function() {
                const price = $(this).find('option:selected').data('price');
                const row = $(this).closest('.product-row');
                row.find('.price').val(price ?? '');
                calculateSubtotal(row);
                calculateTotal();
            });

            $(document).on('input', '.quantity, .discount', function() {
                const row = $(this).closest('.product-row');
                calculateSubtotal(row);
                calculateTotal();
            });

            let rowIndex = 1;

            $('#add_more').click(function() {
                let row = `
    <div class="row product-row mb-2 align-items-center">
        <div class="col-3">
            <select class="form-select product-select" name="products[${rowIndex}][product_id]">
                <option>Select Product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-2">
            <input type="text" class="form-control quantity" name="products[${rowIndex}][quantity]" placeholder="Quantity">
        </div>
        <div class="col-2">
            <input type="text" class="form-control price bg-light" name="products[${rowIndex}][price]" placeholder="Price" readonly>
        </div>
        <div class="col-2">
            <input type="text" class="form-control discount" name="products[${rowIndex}][discount]" placeholder="Discount">
        </div>
        <div class="col-2">
            <input type="text" class="form-control subtotal bg-light" placeholder="Subtotal" readonly>
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
        </div>
    </div>
`;
                $('#product-container').append(row);
                rowIndex++;
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('.product-row').remove();
                calculateTotal();
            });

            $('#sales_form').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: '{{ route('sales.store') }}',
                    method: 'POST',
                    data: formData,
                    success: handleSuccessResponse,
                    error: handleErrorResponse
                });
            });

            const handleSuccessResponse = (response) => {
                const messageClass = response.status ?
                    'bg-success' :
                    'bg-danger';

                showResponseMessage(response.message, messageClass);
                resetForm();
            };

            const handleErrorResponse = (xhr) => {
                const errorMessage = xhr.responseJSON?.message || 'An error occurred';
                showResponseMessage(errorMessage, 'bg-danger');
            };

            const showResponseMessage = (message, bgClass) => {
                $('#response-message').html(
                    `<span class="${bgClass} bg-opacity-75 text-white px-4 py-2 rounded-2">${message}</span>`
                );

                setTimeout(() => {
                    $('#response-message').fadeOut('slow', function() {
                        $(this).html('').show();
                    });
                }, 2000);
            };

            const resetForm = () => {
                $('#sales_form').trigger('reset');
                $('#product-container .product-row:not(:first)').remove();
                rowIndex = 1;
            };
        });
    </script>
</x-app-layout>
