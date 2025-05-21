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

            <div class="table-wrapper">
                <!-- Data table -->
                <table class="table table-bordered data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
@endsection
@section('scripts')<script type="text/javascript">
    $(function () {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('yajra') }}?id={{ request()->query('id') }}&seller={{ request()->query('seller') }}",
            columns: [
                { data: 'formatted_date', name: 'formatted_date' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'order_country_name', name: 'order_country_name' },
                { data: 'billing_address', name: 'billing_address' },
                { data: 'contract_number', name: 'contract_number' },
                { data: 'invoice_no', name: 'invoice_no' },
                { data: 'payment_method', name: 'payment_method' },
                { data: 'payment_status', name: 'payment_status' },
                { data: 'currency', name: 'currency' },
                { data: 'invoice_value', name: 'invoice_value' },
                { data: 'tax', name: 'tax' },
                { data: 'shipping_fee', name: 'shipping_fee' },
                { data: 'total_product_price', name: 'total_product_price' },
                { data: 'total_price', name: 'total_price' },
                { data: 'seller_details', name: 'seller_details' },
                { data: 'order_details_btn', name: 'order_details_btn', orderable: false, searchable: false },
                { data: 'order_status_dropdown', name: 'order_status_dropdown', orderable: false, searchable: false },
                { data: 'checkout_items', name: 'checkout_items', orderable: false, searchable: false }
            ]
        });
    });
</script>



@endsection
