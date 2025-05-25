@extends('layout')

@section('content')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main role="main" class=" px-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>
                <style>
                    .custom-row {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 10px;
                        /* Adjust spacing as needed */
                        margin-top: 50px;
                    }

                    .custom-col {
                        flex: 1;
                        min-width: 48%;
                        /* Ensures two columns per row */
                        background-color: white;
                        padding: 15px;
                        border-radius: 5px;
                    }
                </style>
                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3" style="border-radius: 5px;">
                            <div class="card-body"
                                style="padding: 10px; display: flex; flex-direction: column; justify-content: center;align-items:center; border-radius: 5px;">
                                <h5 class="card-title" style="color: white; font-size: 20px;">Total Sales</h5>
                                <p class="card-text" style="font-size: 25px;" id="total_sells_amnt_all_currency">Loading....
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success mb-3" style="border-radius: 5px;">
                            <div class="card-body"
                                style="padding: 10px; display: flex; flex-direction: column; justify-content: center;align-items:center; border-radius: 5px;">
                                <h5 class="card-title" style="color: white; font-size: 20px;">Total Orders</h5>
                                <p class="card-text" style="color: white;font-size: 25px;">{{$all_orders->count()}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning mb-3" style="border-radius: 5px;">
                            <div class="card-body"
                                style="padding: 10px; display: flex; flex-direction: column; justify-content: center;align-items:center; border-radius: 5px;">
                                <h5 class="card-title" style="color: white; font-size: 20px;">Total Customers TEST</h5>
                                <p class="card-text" style="color: white;font-size: 25px;">{{$total_customers}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger mb-3" style="border-radius: 5px;">
                            <div class="card-body"
                                style="padding: 10px; display: flex; flex-direction: column; justify-content: center;align-items:center; border-radius: 5px;">
                                <h5 class="card-title" style="color: white; font-size: 20px;">Total Vendors</h5>
                                <p class="card-text" style="color: white;font-size: 25px;">{{$total_seller}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" style="margin-top: 15px;">
                        <div class="card text-white bg-dark mb-3" style="border-radius: 5px; background: black!important">
                            <div class="card-body"
                                style="padding: 10px; display: flex; flex-direction: column; justify-content: center;align-items:center; border-radius: 5px;">
                                <h5 class="card-title" style="color: white; font-size: 20px;">Total Product</h5>
                                <p class="card-text" style="color: white;font-size: 25px;">{{$totalProduct}}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="row" style="margin-top: 20px;">


                    <div class="custom-row">
                        <div class="custom-col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Earning Overview</h5>
                                    <canvas id="salesChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="custom-col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Vendor Sells</h5>
                                    <canvas id="revenueChart" width="400" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Recent Orders Table -->
                <div class="row mt-4" style="background: white; margin-top: 20px;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title" style="font-size: 20px;">Recent Orders</h5>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Invoice value</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sl = 1;
                                        @endphp
                                        @foreach ($recent_orders as $order)

                                            <tr>
                                                <td>{{$sl++}}</td>
                                                <td>{{$order->customer->name}}</td>
                                                <td>{{$order->invoice_value}} {{ $order->currency }}</td>
                                                <td>
                                                    @if ($order->order_status == 'Placed')
                                                        <span class="badge bg-success">
                                                            {{$order->order_status}}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success">
                                                            {{$order->order_status}}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{$order->created_at->format('d-m-Y')}}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    @json(array_values($monthlyEarnings->toArray()))
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- Chart.js Scripts -->
    <script>
        // Sales Chart
        var salesCtx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Earnings',
                    // data: [125,254,45550],
                    data: @json(array_values($monthlyEarnings->toArray())), // Convert Collection to array
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });






        // Revenue Chart
        var vendorNames = @json($vendor_names);
        var vendorTotals = @json($vendor_totals);

        var revenueCtx = document.getElementById('revenueChart').getContext('2d');
        var revenueChart = new Chart(revenueCtx, {
            type: 'pie',
            data: {
                labels: vendorNames,  // Vendor names
                datasets: [{
                    label: 'Sells',
                    data: vendorTotals,  // Total sales for each vendor
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });





    </script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $.ajax({
            url: "{{route('get_total_sells')}}",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#total_sells_amnt_all_currency").html(response.total_selected_currency_amnt + " " + response.currency_name);
                console.log("Total Selected Currency Amount:", response.total_selected_currency_amnt + " " + response.currency_name);
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });

    </script>

@endsection