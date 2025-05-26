@extends('layout')
@push('styles')
    {{-- C:\xampp\htdocs\akp\admin.akpeshop.com\public\js\jquery.tableToExcel.js --}}

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

    <style>
        .date-filter-form {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: fit-content;
            width: 100%;
        }

        .date-label {
            font-weight: bold;
        }

        .date-input {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .filter-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .filter-btn:hover {
            background-color: #0056b3;
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
                <h2 class="mt-4" style="display: flex; justify-content: center;">
                    <span>Order Management</span>
                </h2>



                <div style="background-color: #f9f9f9; width:100%; padding: 15px; border-radius: 5px; display: flex; flex-direction: column; justify-content: center; align-items: center;">

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
                                            {{ $currency->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <br>


                            <div style="display: flex;">
                                <label for="allStatuses" style="margin-right: 5px;">Status:</label>
                            <select id="allStatuses" style="margin-bottom: 15px; width: 100%; margin-left: 40px;" name="order_status">
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
                            <a href="{{ route("exportOrder") }}?start_date={{ request()->query('start_date') }}&end_date={{ request()->end_date}}&country_id={{ request()->country_id}}&seller_id={{ request()->seller_id}}&ordered_currency={{ request()->ordered_currency}}&order_status={{ request()->order_status}}" target="_blank" class="btn btn-primary filter-btn" style="width: 100%; margin-right: 2px;">Export
                                as .CSV</a>
                            <a href="{{ route("exportOrder") }}?start_date={{ request()->query('start_date') }}&end_date={{ request()->end_date}}&country_id={{ request()->country_id}}&seller_id={{ request()->seller_id}}&ordered_currency={{ request()->ordered_currency}}&order_status={{ request()->order_status}}"  style="width: 100%; margin-left: 2px;"  target="_blank" class="btn btn-primary filter-btn">Export
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




    <style>
        .tracking-container {
            margin: auto;
            font-family: Arial, sans-serif;
            position: relative;
        }

        .tracking-item {
            display: flex;
            align-items: center;
            position: relative;
            margin-bottom: 15px;
        }

        .tracking-item::before {
            content: "";
            position: absolute;
            left: 10px;
            top: 25px;
            width: 2px;
            height: 100%;
            background-color: #ccc;
        }

        .tracking-item:last-child::before {
            display: none;
        }

        .status-circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #ccc;
            /* Default gray */
            margin-right: 15px;
            position: relative;
            z-index: 1;
        }

        .tracking-item.delivered .status-circle {
            background-color: orange;
            /* Orange for delivered */
        }

        .tracking-content {
            flex: 1;
            margin-top: 25px;
        }

        .tracking-item .date {
            font-weight: bold;
            display: block;
        }

        .tracking-item .status {
            font-size: 14px;
            font-weight: bold;
        }

        .tracking-item p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #555;
        }
    </style>

    <!-- Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Update Order Status</h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('updateOrder') }}">
                        @csrf


                        <label for="orderStatus">Order Status:</label>
                        <select id="orderStatus" onchange="updateStatus()">
                            <option value="Placed">Placed</option>
                            <option value="Cancel By Seller">Cancel By Seller</option>
                            <option value="Cancel By Customer">Cancel By Customer</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Dispatched">Dispatched</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Completed">Completed</option>
                            <option value="Returned">Returned</option>
                        </select>





                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea class="form-control" id="note" name="note" rows="3"></textarea>

                            <input type="hidden" class="form-control" name="checkout_id" id="checkout_id" />
                            <input type="hidden" class="form-control" name="seller_id" id="seller_id" />
                            <input type="hidden" class="form-control" name="status" id="status" />
                            <input type="hidden" class="form-control" name="order_id" id="order_id" />
                            {{-- <input type="text" class="form-control" name="added_by" value="{{ Auth::user()->id }}" />
                            --}}

                        </div>

                        <div style="margin-top: 15px; margin-bottom: 15px; font-weight: bold;">
                            History:

                        </div>






                        <div class="tracking-container" id="tracking-container">






                        </div>



                        <strong id="statusText" style="display: none;"></strong>
                        <strong id="history" style="display: none;"></strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="confirmStatusButton">Confirm</button>
                </div>
                </form>
            </div>
        </div>
    </div>



@endsection
@section('scripts')
    {{--
    <script>
        document.addEventListener("DOMContentLoaded", () => {

        });
    </script> --}}
    <script>
        function updateStatus() {

            document.getElementById("status").value = document.getElementById("orderStatus").value;
        }
        document.getElementById('dropdownButton').addEventListener('click', function (event) {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Close dropdown if clicking outside
        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('dropdownMenu');
            const button = document.getElementById('dropdownButton');
            if (!dropdown.contains(event.target) && event.target !== button) {
                dropdown.style.display = 'none';
            }
        });
    </script>
    <script>


        function openModal(status, checkoutId, orderId, sellerId, history) {
            document.getElementById('statusText').textContent = status;
            document.getElementById('tracking-container').innerHTML = history;
            document.getElementById('status').value = status;
            document.getElementById('seller_id').value = sellerId;
            document.getElementById('checkout_id').value = checkoutId;
            document.getElementById('order_id').value = orderId;




            const confirmButton = document.getElementById('confirmStatusButton');
            // confirmButton.onclick = function () {
            //     // console.log(`{{ route('updateOrder') }}/?checkout_id=${checkoutId}&seller_id=${sellerId}&status=${status}`);
            //     location.href = `{{ route('updateOrder') }}/?checkout_id=${checkoutId}&seller_id=${sellerId}&status=${status}`;

            // };

            // Show the modal
            $('#statusModal').modal('show');


            const trackingItems = document.querySelectorAll(".tracking-item");
            const lastTrackingItem = trackingItems[0];
            if (lastTrackingItem) {
                const statusCircle = lastTrackingItem.querySelector(".status-circle");
                if (statusCircle) {
                    statusCircle.style.backgroundColor = "#f86c47";
                }
            }
        }

    </script>



    <script type="text/javascript">
        $(function () {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                // start_date=2025-01-25&c=2025-01-31
                ajax: "{{ route('order.view') }}?start_date={{ request()->query('start_date') }}&end_date={{ request()->end_date}}&country_id={{ request()->country_id}}&seller_id={{ request()->seller_id}}&ordered_currency={{ request()->ordered_currency}}&order_status={{ request()->order_status}}",
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
                        title: 'Invoice No'
                    }, {
                        data: 'formatted_date',
                        name: 'formatted_date',
                        title: 'Date (dd-mm-yy)'
                    },
                    {
                        data: 'seller_details',
                        name: 'seller_details',
                        title: 'Sellers'
                    },
                    {
                        data: 'order_country_name',
                        name: 'order_country_name',
                        title: 'Order Country'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name',
                        title: 'Customer Name'
                    },
                    {
                        data: 'contract_number',
                        name: 'contract_number',
                        title: 'Contract Number'
                    },
                    {

                        name: 'order_details_btn',
                        data: 'order_details_btn',
                        title: 'Order Details'

                    },
                    {

                        name: 'order_status_dropdown',
                        data: 'order_status_dropdown',
                        title: 'Order Status'

                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status',
                        title: 'Payment Status'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method',
                        title: 'Payment Method'
                    },
                    {
                        data: 'billing_address',
                        name: 'billing_address',
                        title: 'Shipping Address'
                    },
                    {
                        data: 'currency',
                        name: 'currency',
                        title: 'Currency'
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





    <script src="{{ asset('js/jquery.tableToExcel.js') }}"></script>
    <script src="{{ asset('js/table2csv') }}"></script>

@endsection