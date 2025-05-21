@extends('layout')
@push('styles')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: #fff;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }

        .sidebar a:hover {
            background-color: #495057;
            color: #fff;
        }

        .table-wrapper {
            margin-top: 20px;
        }

        .status-active {
            color: green;
            font-weight: bold;
        }


        /* Reset default margins and paddings */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Base Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .product-actions {
            margin-bottom: 20px;
        }

        /* Select2 dropdown */
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            color: #333;
            font-size: 16px;
        }

        /* Form Styles */
        .product-form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            color: #333;
            font-size: 16px;
        }

        button.submit-button {
            background-color: #28a745;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button.submit-button:hover {
            background-color: #218838;
        }

        /* Quantity Controls */
        .quantity-control {
            display: flex;
            align-items: center;
        }

        .quantity-control input {
            width: 50px;
            text-align: center;
            margin: 0 5px;
        }

        button.decrease-btn,
        button.increase-btn {
            background-color: #ddd;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
        }

        /* Existing Products List */
        .existing-products {
            margin-top: 30px;
        }

        ul#product-list {
            list-style-type: none;
        }

        ul#product-list li {
            background-color: #f9f9f9;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            color: #333;
            position: relative;
        }

        button.remove-button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        button.remove-button:hover {
            background-color: #c82333;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            select,
            input[type="text"],
            input[type="number"],
            input[type="file"] {
                font-size: 14px;
            }
        }
    </style>
@endpush
@section('content')
@if (session('success'))
    <div class="alert alert-success text-center">{{ session('success') }}</div>
@endif
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="p-3">
                <h4>Seller Dashboard</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="#">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a href="#">Products</a>
                    </li>
                    <li class="nav-item">
                        <a href="#">Shipping</a>
                    </li>
                    <li class="nav-item">
                        <a href="#">Settings</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4">
            <h2 class="mt-4">Order Management</h2>









            <style>
                /* Global Reset */
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Arial', sans-serif;
                    background-color: #f4f4f4;
                    padding: 40px 20px;
                }

                .cart-container {
                    max-width: 900px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }

                .cart-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-bottom: 1px solid #ddd;
                    padding-bottom: 20px;
                }

                .cart-header h1 {
                    color: #333;
                    font-size: 24px;
                }

                .cart-item {
                    display: flex;
                    justify-content: space-between;
                    background-color: #f9f9f9;
                    padding: 15px;
                    margin-bottom: 15px;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                }

                .cart-item img {
                    max-width: 80px;
                    border-radius: 8px;
                }

                .item-details {
                    flex-grow: 1;
                    padding-left: 15px;
                }

                .item-details h4 {
                    color: #555;
                    margin-bottom: 10px;
                }

                .item-details p {
                    color: #777;
                }

                .item-price {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: flex-end;
                    color: #333;
                }

                .item-price span {
                    font-size: 16px;
                    font-weight: bold;
                }

                .item-charges {
                    display: flex;
                    flex-direction: column;
                    align-items: flex-end;
                    color: #555;
                    margin-top: 10px;
                }

                .item-charges p {
                    margin: 4px 0;
                    font-size: 14px;
                }

                .item-quantity {
                    display: flex;
                    align-items: center;
                }

                .item-quantity button {
                    background-color: #e0e0e0;
                    border: none;
                    padding: 6px 12px;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 14px;
                    margin: 0 5px;
                    transition: background-color 0.3s ease;
                }

                .item-quantity button:hover {
                    background-color: #ccc;
                }

                .item-quantity input {
                    width: 40px;
                    text-align: center;
                    border: 1px solid #ddd;
                    padding: 4px 8px;
                    border-radius: 4px;
                    font-size: 14px;
                }

                .item-actions {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: flex-end;
                }

                .item-actions button {
                    background-color: #ff5a5a;
                    color: white;
                    border: none;
                    padding: 8px 12px;
                    margin-bottom: 8px;
                    border-radius: 4px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }

                .item-actions button:hover {
                    background-color: #ff3a3a;
                }

                .cart-total {
                    display: flex;
                    justify-content: space-between;
                    padding-top: 20px;
                    border-top: 1px solid #ddd;
                    margin-top: 20px;
                    padding-bottom: 20px;
                    font-size: 18px;
                    color: #333;
                }

                .shipping-section {
                    margin-top: 20px;
                    border-top: 1px solid #ddd;
                    padding-top: 20px;
                }

                .shipping-section h3 {
                    color: #333;
                    margin-bottom: 10px;
                }

                .shipping-input {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 10px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    font-size: 14px;
                }

                .checkout-button {
                    display: block;
                    width: 100%;
                    background-color: #5a9fff;
                    color: white;
                    padding: 15px;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 18px;
                    margin-top: 20px;
                    transition: background-color 0.3s ease;
                }

                .checkout-button:hover {
                    background-color: #4a7edf;
                }
            </style>

            <!-- Include Select2 CSS -->
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

            <!-- Include jQuery (required for Select2) -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <!-- Include Select2 JS -->
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

            <div class="cart-container">
                <form action="{{ route('OrderUpdate') }}" method="POST" id="order-update-form">
                    @csrf

                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <input type="hidden" name="checkout_id" value="{{ $order->checkout_details->checkout_id }}">

                    <div class="cart-header">
                        <h1>Edit Order</h1>
                    </div>

                    <select id="product-select" class="shipping-input select2" placeholder="Select a product">
                        <option value="">Select a product</option>
                        @foreach ($products as $product)
                            <option value='{"id": {{ $product->id }},
                                                "name": "{{ $product->name }}",
                                                "description": "{{ strip_tags($product->product_description) }}",
                                                "price": {{ $product->selling_price }},
                                                "tax": {{ $product->tax_as_price }},
                                                "weight": {{ $product->weight }},
                                                "img": "{{ env('AKP_STORAGE') . 'products/' . $product->seller->seller_code . '/' . $product->product_code . '/images/' . $product->image->image_1 }}"
                                            }'>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>

                    <div id="cart-items">
                        <!-- Cart items will be rendered here -->
                    </div>

                    <!-- Total Amount Section -->
                    <div class="total-section" style="float: right;">
                        <h2>Total Amount</h2>
                        <div class="total-amount">
                            <span id="total-price" style="font-size: 20px;">AED 0.00</span>
                            <br>
                            <span id="shipping-fee" style="font-size: 16px; color: grey;">Shipping Fee: AED 0.00</span>
                        </div>
                    </div>

                    <div class="shipping-section">
                        <h3 style="margin-top: 40px;">Shipping Address</h3>
                        <input type="text" id="name" name="billing_name" class="shipping-input" placeholder="Full Name"
                            value="{{ $order->checkout_details->billing_name }}">
                        <input type="text" id="address" name="billing_address" class="shipping-input" placeholder="Address"
                            value="{{ $order->checkout_details->billing_address }}">

                        <select id="country" name="country_id" class="shipping-input select2" placeholder="Select Country">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" {{ $country->id == $order->checkout_details->country->id ? 'selected' : '' }}>
                                    {{ $country->country_name }}
                                </option>
                            @endforeach
                        </select>

                        <select id="city" name="city_id" class="shipping-input select2" placeholder="Select City">
                            <option value="">Select City</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ $city->id == $order->checkout_details->city->id ? 'selected' : '' }}>
                                    {{ $city->city_name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="text" id="zip" name="billing_zip_code" class="shipping-input" placeholder="ZIP Code"
                            value="{{ $order->checkout_details->billing_zip_code }}">
                    </div>

                    <!-- Hidden input to store cart items -->
                    <input type="hidden" id="cart-data" name="cart_data">

                    <!-- Submit Button -->
                    <button type="submit" class="checkout-button">Update</button>
                </form>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
            <script>
                $(document).ready(function () {
                    $('#country').select2({ placeholder: "Select Country", allowClear: true });
                    $('#city').select2({ placeholder: "Select City", allowClear: true });
                    $('#product-select').select2({ placeholder: "Select a product", allowClear: true });

                    $('#country').on('change', function () {
                        const countryId = $(this).val();
                        if (countryId) {
                            $.ajax({
                                url: `{{ route('GetCities') }}?country_id=${countryId}`,
                                type: 'GET',
                                success: function (cities) {
                                    $('#city').empty().append('<option value="">Select City</option>');
                                    cities.forEach(city => {
                                        $('#city').append(new Option(city.city_name, city.id));
                                    });
                                }
                            });
                        } else {
                            $('#city').empty().append('<option value="">Select City</option>');
                        }
                    });

                    $('#product-select').change(function () {
                        const selectedProduct = JSON.parse(this.value);
                        if (selectedProduct.id) {
                            addProductToCart(selectedProduct);
                        }
                    });

                    // Serialize cart data on form submission
                    $('#order-update-form').on('submit', function () {
                        cart.forEach(item => {
                            item.totalPriceWithShipping = (item.price * item.quantity) + item.shipping_charge;
                        });
                        $('#cart-data').val(JSON.stringify(cart));
                    });

                });

                const cartItemsContainer = document.getElementById('cart-items');
                let cart = @json($checkoutItems);

                function renderCartItems() {
                    if (cart.length === 0) {
                        cartItemsContainer.innerHTML = `<p>Your cart is empty.</p>`;
                        return;
                    }

                    cartItemsContainer.innerHTML = '';
                    let subtotal = 0;
                    let totalTax = 0;
                    let totalShipping = 0;

                    cart.forEach(item => {
                        const productTotal = item.price * item.quantity;
                        const vatTotal = item.tax * item.quantity;
                        const totalWeight = item.weight * item.quantity;
                        const shippingFee = calculateShippingFee(totalWeight);
                        totalShipping += shippingFee;

                        subtotal += productTotal;
                        totalTax += vatTotal;

                        const cartItemHTML = `
                            <div class="cart-item">
                                <img src="${item.img}" alt="${item.name}">
                                <div class="item-details">
                                    <h4>${item.name}</h4>
                                    <p>${item.description}</p>
                                </div>
                                <div class="item-price">
                                    <span>Weight (total): ${totalWeight.toFixed(2)} g</span>
                                    <span>VAT: AED ${vatTotal.toFixed(2)}</span>
                                    <span>Price: AED ${productTotal.toFixed(2)}</span>
                                    <span>Shipping Fee: AED ${shippingFee.toFixed(2)}</span>
                                </div>
                                <div class="item-quantity">
                                    <button onclick="changeQuantity(${item.id}, -1)">-</button>
                                    <input type="number" value="${item.quantity}" readonly>
                                    <button onclick="changeQuantity(${item.id}, 1)">+</button>
                                </div>
                                <div class="item-actions">
                                    <button onclick="removeFromCart(${item.id})">Remove</button>
                                </div>
                            </div>
                        `;
                        cartItemsContainer.innerHTML += cartItemHTML;

                        // Add shipping_charge to each item
                        item.shipping_charge = shippingFee;
                    });

                    // Update Total Display
                    document.getElementById('total-price').textContent = `AED ${(subtotal + totalTax + totalShipping).toFixed(2)}`;
                    document.getElementById('shipping-fee').textContent = `Shipping Fee: AED ${totalShipping.toFixed(2)}`;
                }

                function calculateShippingFee(weight) {
                    if (weight <= 1000) return 60;  // Example fee for 1g to 1000g
                    if (weight <= 3000) return 60;  // Example fee for 1001g to 3000g
                    if (weight <= 5000) return 60;  // Example fee for 3001g to 5000g
                    if (weight <= 10000) return 80;  // Example fee for 5001g to 10000g
                    if (weight <= 15000) return 80;  // Example fee for 10001g to 15000g
                    return 150;  // Example fee above 15000g
                }

                function addProductToCart(product) {
                    const existingProduct = cart.find(item => item.id === product.id);
                    if (existingProduct) {
                        existingProduct.quantity += 1;
                    } else {
                        cart.push({
                            id: product.id,
                            name: product.name,
                            description: product.description,
                            price: parseFloat(product.price),
                            tax: parseFloat(product.tax),
                            weight: product.weight,
                            img: product.img,
                            quantity: 1
                        });
                    }
                    renderCartItems();
                }

                function changeQuantity(id, change) {
                    const item = cart.find(i => i.id === id);
                    if (item) {
                        item.quantity += change;
                        if (item.quantity < 1) item.quantity = 1;
                        renderCartItems();
                    }
                }

                function removeFromCart(id) {
                    cart = cart.filter(item => item.id !== id);
                    renderCartItems();
                }

                // Initial render
                renderCartItems();
            </script>





        </main>






    </div>
</div>
@endsection
@section('scripts')
@endsection
