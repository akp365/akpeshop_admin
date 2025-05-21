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
            <div style="background-color: #f9f9f9; width:fit-content; padding: 15px; border-radius: 5px;" >
                <form class="mt-5" >

                    <div class="date-filter-form" style="margin-bottom: 20px;">
                        <label for="from-date" class="date-label">From:</label>
                        <input type="date" id="from-date" name="start_date" class="form-control date-input" value="{{ request()->query('start_date') ?? null }}" min="1997-01-01" max="2030-12-31">

                        <label for="to-date" class="date-label">To:</label>
                        <input type="date" id="to-date" name="end_date" class="form-control date-input" value="{{ request()->query('end_date') ?? null }}"min="1997-01-01" max="2030-12-31">
                    </div>

                    <label for="allCountries">Country:</label>
                    <select id="allCountries" style="margin-bottom: 15px;" name="country_id">
                        <option value="none">None</option>
                        @foreach ($allCountries as $country)
                        <option value="{{ $country->id }}" {{ request()->query('country_id') == $country->id ? "selected" : '' }}>{{ $country->country_name }}</option>
                        @endforeach
                    </select>
                    <br>

                    <label for="allSellers">Seller:</label>
                    <select id="allSellers" style="margin-bottom: 15px;" name="seller_id">
                        <option value="none">None</option>
                        @foreach ($allSellers as $seller)
                        <option value="{{ $seller->id }}" {{ request()->query('seller_id') == $seller->id ? "selected" : '' }}>{{ $seller->name }}</option>
                        @endforeach
                    </select>
                    <br>

                    <label for="allSellers">Currency:</label>
                    <select id="allSellers" style="margin-bottom: 15px;" name="ordered_currency">
                        <option value="none">None</option>
                        @foreach ($allCurrency as $currency)
                        <option value="{{ $currency->title }}" {{ request()->query('ordered_currency') == $currency->title ? "selected" : '' }}>{{ $currency->title }}</option>
                        @endforeach
                    </select>
                    <br>


                    <label for="allStatuses">Status:</label>
                    <select id="allStatuses" style="margin-bottom: 15px;" name="order_status">
                        <option value="none" selected>None</option>
                        <option value="Placed" {{ request()->query('order_status') == "Placed" ? "selected" : '' }}>Placed</option>
                        <option value="Cancel By Seller" {{ request()->query('order_status') == "Cancel By Seller" ? "selected" : '' }}>Cancel By Seller</option>
                        <option value="Cancel By Customer" {{ request()->query('order_status') == "Cancel By Customer" ? "selected" : '' }}>Cancel By Customer</option>
                        <option value="Confirmed" {{ request()->query('order_status') == "Confirmed" ? "selected" : '' }}>Confirmed</option>
                        <option value="Dispatched" {{ request()->query('order_status') == "Dispatched" ? "selected" : '' }}>Dispatched</option>
                        <option value="Delivered" {{ request()->query('order_status') == "Delivered" ? "selected" : '' }}>Delivered</option>
                        <option value="Returned" {{ request()->query('order_status') == "Returned" ? "selected" : '' }}>Returned</option>
                    </select>

                    <br>
                    <button type="submit" class="btn btn-primary filter-btn">Filter</button>
                </form>
                <hr>
                <div class="d-flex justify-content-between">
                    <a href="{{ route("exportOrder") }}" target="_blank" class="btn btn-primary filter-btn">Export as .CSV</a>
                    <a href="{{ route("exportOrder") }}" target="_blank" class="btn btn-primary filter-btn" >Export as .XLSX</a>
                </div>
            </div>
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
@section('scripts')
<script type="text/javascript">
    $(function () {
        $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('order_more_info') }}",
            columns: [
                {
                    data: null,
                    title: 'Sl No',
                    render: function (data, type, row, meta) {
                        return meta.row + 1
                    },
                    searchable: false
                },

                {
                    data: 'invoice_value',
                    name: 'invoice_value',
                    title: 'Invoice Value'
                },
                {
                    data: 'tax',
                    name: 'tax',
                    title: 'Tax'
                },
                {
                    data: 'seller_total_shipping_fee',
                    name: 'seller_total_shipping_fee',
                    title: 'Shipping Fee'
                },
                {
                    data: 'checkout_cod_charge',
                    name: 'checkout_cod_charge',
                    title: 'COD Charge'
                },
                {
                    data: 'discount',
                    name: 'discount',
                    title: 'Coupon Discount'
                },
                {
                    data: 'total_price_without_vat',
                    name: 'total_price_without_vat',
                    title: 'Product Price'
                },
                {
                    data: 'commisonFee',
                    name: 'commisonFee',
                    title: 'Commison'
                },
                {
                    data: 'promoterClubFee',
                    name: 'promoterClubFee',
                    title: 'Promoter Fee'
                },
                {
                    data: 'vatOnFee',
                    name: 'vatOnFee',
                    title: 'VAT on fee'
                },
                {
                    data: 'seller_payable',
                    name: 'seller_payable',
                    title: 'Seller/Agent Payable'
                },
                {
                    data: 'subcidy',
                    name: 'subcidy',
                    title: 'Subcidy'
                },
                {
                    data: 'earnings',
                    name: 'earnings',
                    title: 'Earning'
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    title: 'More',
                    //         render: function (data, type, row) {
                    //             return `
                    // <div class="d-flex flex-column">
                    //                     <a href="{{ route('order.edit_view') }}?order_id=${row.id}" class="btn btn-sm btn-primary update-btn" style="margin-bottom:10px;">Update</a>
                    //     <a href="{{ route('delete_order') }}?delete_order=${row.id}" class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</a>
                    //     </div>
                    // `;
                    //         }
                    render: function (data, type, row) {
                        return `
            <div class="d-flex flex-column">
                <a href="{{ route('order_more_info') }}?id=${row.id}" class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">More Info</a>
                </div>
            `;
                    }
                },
            ],



        });
    });
</script>
@endsection
