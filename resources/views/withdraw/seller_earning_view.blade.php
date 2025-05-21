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

            <div class="row">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mt-5 pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Seller Earnings</h1>
                </div>
                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3" style="border-radius: 5px;">
                            <div class="card-body"
                                style="padding: 10px; display: flex; flex-direction: column; justify-content: center;align-items:center; border-radius: 5px;">
                                <h5 class="card-title" style="color: white; font-size: 20px;">Total Earnings</h5>
                                <p class="card-text" id="total_sells_amnt_all_currency" style="font-size: 25px;">
                                    {{$formatted_total_current_currency_earnings . " " . $seller_current_currency_name}}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger mb-3" style="border-radius: 5px;">
                            <div class="card-body"
                                style="padding: 10px; display: flex; flex-direction: column; justify-content: center;align-items:center; border-radius: 5px;">
                                <h5 class="card-title" style="color: white; font-size: 20px;">Total Withdraw</h5>
                                <p class="card-text" style="color: white;font-size: 25px;">
                                    {{$withdraw . " " . $seller_current_currency_name}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning mb-3" style="border-radius: 5px;">
                            <div class="card-body"
                                style="padding: 10px; display: flex; flex-direction: column; justify-content: center;align-items:center; border-radius: 5px;">
                                <h5 class="card-title" style="color: white; font-size: 20px;">Total Balance</h5>
                                <p class="card-text" style="color: white;font-size: 25px;">
                                    {{$total_balance_data . " " . $seller_current_currency_name}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-dark mb-3" style="border-radius: 5px; background: black!important">
                            <div class="card-body"
                                style="padding: 10px; display: flex; flex-direction: column; justify-content: center;align-items:center; border-radius: 5px;">
                                <h5 class="card-title" style="color: white; font-size: 20px;">Withdraw Request</h5>
                                <p class="card-text" style="color: white;font-size: 25px;">
                                    {{$withdraw_request . " " . $seller_current_currency_name}}</p>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>



@endsection
    @section('scripts')


    @endsection