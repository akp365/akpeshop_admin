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
                <h2 class="mt-4" style="display: flex; justify-content: center;"><span>Order Management</span></h2>


                <div
                    style="background-color: #f9f9f9; width:100%; padding: 15px; border-radius: 5px; display: flex; flex-direction: column; justify-content: center; align-items: center;">

                    <div>
                        <form class="mt-5">

                            <div class="date-filter-form" style="margin-bottom: 20px;">
                                <label for="from-date" class="date-label">From:</label>
                                <input type="date" id="from-date" name="start_date" class="form-control date-input"
                                    value="{{ request()->query('start_date') ?? null }}" min="1997-01-01" max="2030-12-31">

                                <label for="to-date" class="date-label">To:</label>
                                <input type="date" id="to-date" name="end_date" class="form-control date-input"
                                    value="{{ request()->query('end_date') ?? null }}" min="1997-01-01" max="2030-12-31">
                            </div>

                            <label for="allCountries">Country:</label>
                            <select id="allCountries" style="margin-bottom: 15px;margin-left: 29px;" name="country_id">
                                <option value="none">None</option>
                                @foreach ($allCountries as $country)
                                    <option value="{{ $country->id }}" {{ request()->query('country_id') == $country->id ? "selected" : '' }}>{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                            <br>

                            <div style="display: flex">
                                <label for="allSellers" style="margin-right: 5px;">Seller:</label>
                                <select id="allSellers" style="width: 100%; margin-left: 43px;" name="seller_id">
                                    <option value="none">None</option>
                                    @foreach ($allSellers as $seller)
                                        <option value="{{ $seller->id }}" {{ request()->query('seller_id') == $seller->id ? "selected" : '' }}>{{ $seller->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>


                            <div style="display: flex;">
                                <label for="allSellers" style="margin-right: 5px;">Currency:</label>
                                <select id="allSellers" style="width: 100%; margin-left: 23px;;" name="ordered_currency">
                                    <option value="none">None</option>
                                    @foreach ($allCurrency as $currency)
                                        <option value="{{ $currency->title }}" {{ request()->query('ordered_currency') == $currency->title ? "selected" : '' }}>
                                            {{ $currency->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <br>


                            <div style="display: flex;">
                                <label for="allStatuses" style="margin-right: 5px;">Status:</label>
                                <select id="allStatuses" style="margin-bottom: 15px; width: 100%; margin-left: 40px;"
                                    name="order_status">
                                    <option value="none" selected>None</option>
                                    <option value="Placed" {{ request()->query('order_status') == "Placed" ? "selected" : '' }}>
                                        Placed</option>
                                    <option value="Cancel By Seller" {{ request()->query('order_status') == "Cancel By Seller" ? "selected" : '' }}>Cancel By Seller</option>
                                    <option value="Cancel By Customer" {{ request()->query('order_status') == "Cancel By Customer" ? "selected" : '' }}>Cancel By Customer</option>
                                    <option value="Confirmed" {{ request()->query('order_status') == "Confirmed" ? "selected" : '' }}>Confirmed</option>
                                    <option value="Dispatched" {{ request()->query('order_status') == "Dispatched" ? "selected" : '' }}>Dispatched</option>
                                    <option value="Delivered" {{ request()->query('order_status') == "Delivered" ? "selected" : '' }}>Delivered</option>
                                    <option value="Completed" {{ request()->query('order_status') == "Completed" ? "selected" : '' }}>Completed</option>
                                    <option value="Returned" {{ request()->query('order_status') == "Returned" ? "selected" : '' }}>Returned</option>
                                </select>
                            </div>

                            <br>
                            <button type="submit" class="btn btn-primary filter-btn" style="width: 100%;">Filter</button>
                        </form>
                        <hr>
                        <div style="width: 100%; display: flex; justify-content: space-between;">
                            <a href="{{ route("exportOrder") }}" target="_blank" class="btn btn-primary filter-btn"
                                style="width: 100%; margin-right: 2px;">Export
                                as .CSV</a>
                            <a href="{{ route("exportOrder") }}" style="width: 100%; margin-left: 2px;" target="_blank"
                                class="btn btn-primary filter-btn">Export
                                as .XLSX</a>
                        </div>
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
                        data: 'invoice_no',
                        name: 'invoice_no',
                        title: 'Invoice No',
                        // visible: false // This hides the column
                    },


                    {
                        data: 'final_invoice_value',
                        name: 'final_invoice_value',
                        title: 'Invoice Value',

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
                        data: 'total_discount',
                        name: 'total_discount',
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
                        data: 'vat_on_fee',
                        name: 'vat_on_fee',
                        title: 'VAT on fee'
                    },
                    {
                        data: 'seller_payable_amnt',
                        name: 'seller_payable_amnt',
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
                        name: 'admin_currency_earning',
                        title: 'Currency Earning',
                        render: function (data, type, row) {
                            return row.admin_currency_earning + ' ' + row.admin_currency_earning_currency;
                        }
                    }


                ],



            });
        });
    </script>
@endsection