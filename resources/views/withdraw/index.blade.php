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


            <!-- Main Content -->
            <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4">
                <h2 class="mt-4" style="display: flex; justify-content: center;">
                    <span>Widthdraw Management</span>
                </h2>






                <div class="table-wrapper">
                    <!-- Data table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Seller Name</th>
                                    <th>Seller Code</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Request Date</th>
                                    <th>Approve Date</th>
                                    <th>Details</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sl = 1;
                                @endphp
                                @foreach ($allWithdraw as $data)
                                    <tr>
                                        <td>{{$sl++}}</td>
                                        <td>{{$data->seller->name}}</td>
                                        <td>{{$data->seller->seller_code}}</td>
                                        <td>{{$data->amount . " " . $data->currency}}</td>
                                        <td>{{$data->status}}</td>
                                        <td>{{$data->created_at->format('d-m-Y')}}</td>
                                        <td>{{$data->updated_at == $data->created_at ? null : $data->updated_at->format('d-m-Y')}}
                                        </td>
                                        <td>
                                            <a href="{{route('SellerEarnings')}}?seller_id={{$data->seller_id}}"  class="btn btn-primary">View Seller Earnings</a>
                                        </td>
                                        <td>
                                            @if ($data->status == 'pending')
                                                <form class="col-md-12" method="POST" action="{{route('ApproveSellerWithdraw')}}">
                                                    @csrf

                                                    <div class="form-group form-control-default required" aria-required="true">
                                                        <!-- LABEL -->
                                                        <label for="productName">Admin Note</label>

                                                        <!-- INPUT BOX -->
                                                        <input type="text" value="{{$data->admin_note}}" name="adminNote"
                                                            class="form-control" id="admin_note" placeholder="Admin Note" value=""
                                                            required="" aria-required="true">
                                                    </div>
                                                    <input type="hidden" name="wid" value="{{$data->id}}">
                                                    <button type="submit" class="btn btn-primary">Approve</button>
                                                    <a href="{{route('DeleteSellerWithdraw')}}?wid={{$data->id}}"
                                                        class="btn btn-primary">Delete</a>
                                                </form>

                                            @elseif ($data->status == 'approve')
                                                <form class="col-md-12" action="{{route('PendingSellerWithdraw')}}" method="POST">
                                                    @csrf
                                                    <div class="form-group form-control-default required" aria-required="true">
                                                        <!-- LABEL -->
                                                        <label for="productName">Admin Note</label>

                                                        <!-- INPUT BOX -->
                                                        <input type="text" value="{{$data->admin_note}}" name="adminNote"
                                                            class="form-control" id="admin_note" placeholder="Admin Note" value=""
                                                            required="" aria-required="true">
                                                    </div>
                                                    <input type="hidden" name="wid" value="{{$data->id}}">
                                                    <button type="submit" class="btn btn-primary">Mark as pending</button>

                                                    <a href="{{route('DeleteSellerWithdraw')}}?wid={{$data->id}}"
                                                        class="btn btn-primary">Delete</a>
                                                </form>

                                            @endif

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>


                </div>
            </main>
        </div>
    </div>



@endsection
@section('scripts')


@endsection