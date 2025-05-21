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
@section('scripts')
<script type="text/javascript">
    $(function () {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('order.details') }}?id={{ request()->query('id') }}&seller={{ request()->query('seller') }}",
            columns: [
                {
                    data: null,
                    title: 'Sl No',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                    searchable: false
                },
                {
                    data: 'item_name',
                    name: 'item_name',
                    title: 'Item Details'
                },
                {
                    data: 'qty',
                    name: 'qty',
                    title: 'Qty'
                },
                {
                    data: 'price',
                    name: 'price',
                    title: 'Price'
                },
                {
                    data: 'tax',
                    name: 'tax',
                    title: 'TAX'
                },
                {
                    data: 'final_price',
                    name: 'final_price',
                    title: 'Final Price'
                },
                {
                    data: 'product_price',
                    name: 'product_price',
                    title: 'Product Price'
                },
                {
                    data: 'commisonFee',
                    name: 'commisonFee',
                    title: 'Commison'
                },
                {
                    data: 'promoter_fee',
                    name: 'promoter_fee',
                    title: 'Promoter Fee'
                },
                {
                    data: 'vat_on_fee',
                    name: 'vat_on_fee',
                    title: 'VAT on fee'
                }
            ],
            drawCallback: function () {
                var api = this.api();

                // Remove any existing totals row
                $(".totals-row").remove();

                // Helper function to sum up a column's data
                var intVal = function (i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ? i : 0;
                };

                // Calculate totals
                var taxTotal = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var productPriceTotal = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var commissionTotal = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var promoterFeeTotal = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var vatOnFeeTotal = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Add totals row below the table
                var totalRow = `
                    <tr class="totals-row" style="background: #f8f9fa; font-weight: bold;">
                        <td style="text-align:right;">Totals:</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>${taxTotal.toFixed(2)}</td>
                        <td></td>
                        <td>${productPriceTotal.toFixed(2)}</td>
                        <td>${commissionTotal.toFixed(2)}</td>
                        <td>${promoterFeeTotal.toFixed(2)}</td>
                        <td>${vatOnFeeTotal.toFixed(2)}</td>
                    </tr>
                `;

                $(api.table().body()).append(totalRow);
            }
        });
    });
</script>

@endsection
