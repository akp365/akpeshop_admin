@extends('layout')

@push('styles')
<!-- Add DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endpush

@section('content')

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .col-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
        padding: 0 15px;
        margin-bottom: 20px;
    }

    .col-3 {
        flex: 0 0 25%;
        max-width: 25%;
        padding: 0 15px;
    }

    .form-control {
        width: 100%;
        height: 35px;
        padding: 5px 10px;
        font-size: 14px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #fff;
    }

    .filter-section {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .filter-btn {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        padding: 6px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        margin-top: 24px;
    }

    .filter-btn:hover {
        background-color: #e9ecef;
    }

    .stats-card {
        padding: 15px;
        color: white;
        text-align: center;
        border-radius: 4px;
        background: #008000;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .stats-card span:first-child {
        font-size: 14px;
        margin-bottom: 5px;
    }

    .stats-card span:last-child {
        font-size: 16px;
        font-weight: 500;
    }

    .section-title {
        color: black;
        box-shadow: 0px 5px 0px -2px green;
        border-top-left-radius: 15px;
        border-bottom-left-radius: 15px;
        padding: 8px;
        margin: 20px 0;
    }

    .section-title span {
        margin-left: 5px;
        font-weight: bold;
        font-size: 16px;
    }

    /* Table Styles */
    .table-section {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }

    .table th {
        background-color: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        font-weight: 500;
        border: 1px solid #dee2e6;
    }

    .table td {
        padding: 10px 15px;
        border: 1px solid #dee2e6;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper {
        padding: 20px 0;
    }

    .dataTables_length {
        float: left;
        margin-bottom: 15px;
    }

    .dataTables_filter {
        float: right;
        margin-bottom: 15px;
    }

    .dataTables_length select {
        padding: 5px;
        margin: 0 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .dataTables_filter input {
        padding: 5px 10px;
        margin-left: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .dataTables_info {
        clear: both;
        float: left;
        padding-top: 15px;
    }

    .dataTables_paginate {
        float: right;
        padding-top: 15px;
    }

    .paginate_button {
        padding: 5px 10px;
        margin: 0 2px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        color: #333;
    }

    .paginate_button.current {
        background-color: #008000;
        color: white;
        border-color: #008000;
    }

    .paginate_button.disabled {
        color: #999;
        cursor: not-allowed;
    }

    /* Clearfix */
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .col-4, .col-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .dataTables_length,
        .dataTables_filter,
        .dataTables_info,
        .dataTables_paginate {
            float: none;
            text-align: center;
        }
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-4">
            <div class="stats-card">
                <span>Approved</span>
                <span id="approvedTotal">RP 0.00 TK</span>
            </div>
        </div>
        <div class="col-4">
            <div class="stats-card">
                <span>Processing</span>
                <span id="processingTotal">RP 109800.00 TK</span>
            </div>
        </div>
        <div class="col-4">
            <div class="stats-card">
                <span>Declined</span>
                <span id="declinedTotal">RP 1952.00 TK</span>
            </div>
        </div>
        <div class="col-4">
            <div class="stats-card">
                <span>Redeemed</span>
                <span id="redeemedTotal">RP 253228.08 TK</span>
            </div>
        </div>
        <div class="col-4">
            <div class="stats-card">
                <span>Reversed</span>
                <span id="reversedTotal">RP 0.00 TK</span>
            </div>
        </div>
        <div class="col-4">
            <div class="stats-card">
                <span>Balance</span>
                <span id="balanceTotal">RP -253228.08 TK</span>
            </div>
        </div>
    </div>

    <div class="section-title">
        <span>Reward Point</span>
    </div>

    <div class="filter-section">
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label>From:</label>
                    <input type="date" class="form-control" id="dateFrom">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label>To:</label>
                    <input type="date" class="form-control" id="dateTo">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label>Status:</label>
                    <select class="form-control" id="statusFilter">
                        <option value="">All</option>
                        <option value="approved">Approved</option>
                        <option value="processing">Processing</option>
                        <option value="declined">Declined</option>
                        <option value="redeemed">Redeemed</option>
                        <option value="reversed">Reversed</option>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <button class="filter-btn" id="filterBtn">Filter</button>
            </div>
        </div>
    </div>

    <div class="table-section">
        <table id="ordersTable" class="table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Invoice No</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Earn/Redeem</th>
                    <th>Convert to</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@endsection

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery != 'undefined') {
            $(document).ready(function() {
                let table = $('#ordersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('reward_points') }}",
                        data: function(d) {
                            d.fromDate = $('#dateFrom').val();
                            d.toDate = $('#dateTo').val();
                            d.status = $('#statusFilter').val();
                        }
                    },
                    order: [[0, 'desc']],
                    columns: [
                        { data: 'data_time', name: 'data_time' },
                        { data: 'invoice_no', name: 'invoice_no' },
                        { data: 'user_name', name: 'user_name' },
                        { data: 'user_email', name: 'user_email' },
                        {
                            data: 'earn_redeem',
                            name: 'earn_redeem',
                            render: function(data, type, row) {
                                if (type === 'display') {
                                    const value = data.replace(/[^\d.-]/g, '');
                                    const currency = data.replace(/[\d.-]/g, '').trim();
                                    const formattedValue = parseFloat(value).toLocaleString('en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                    return formattedValue + ' ' + currency;
                                }
                                return data;
                            }
                        },
                        {
                            data: 'convert_rp_to_default_currency',
                            name: 'convert_rp_to_default_currency',
                            render: function(data, type, row) {
                                if (type === 'display') {
                                    const value = data.replace(/[^\d.-]/g, '');
                                    const currency = data.replace(/[\d.-]/g, '').trim();
                                    const formattedValue = parseFloat(value).toLocaleString('en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                    return formattedValue + ' ' + currency;
                                }
                                return data;
                            }
                        },
                        {
                            data: 'status',
                            name: 'status',
                            render: function(data, type, row) {
                                if (type === 'display') {
                                    let color = '';
                                    switch(data) {
                                        case 'Approved': color = '#28a745'; break;
                                        case 'Processing': color = '#ffc107'; break;
                                        case 'Declined': color = '#dc3545'; break;
                                        case 'Redeemed': color = '#17a2b8'; break;
                                        case 'Reversed': color = '#6c757d'; break;
                                    }
                                    return '<span class="status-badge" style="color: white; background-color: ' + color + '; padding: 5px 10px; border-radius: 4px; display: inline-block;">' + data + '</span>';
                                }
                                return data;
                            }
                        }
                    ],
                    drawCallback: function(settings) {
                        if (settings.json && settings.json.summary) {
                            const summary = settings.json.summary;
                            $('#approvedTotal').text(summary.total_approved);
                            $('#processingTotal').text(summary.total_processing);
                            $('#declinedTotal').text(summary.total_declined);
                            $('#redeemedTotal').text(summary.total_redeemed);
                            $('#reversedTotal').text(summary.total_reversed);
                            $('#balanceTotal').text(summary.total_balance);
                        }
                    }
                });

                // Filter button click handler
                $('#filterBtn').on('click', function() {
                    table.draw();
                });

                // Reset filter values when the page loads
                $('#dateFrom, #dateTo').val('');
                $('#statusFilter').val('');

                // Add event listeners for date inputs
                $('#dateFrom, #dateTo').on('change', function() {
                    if ($('#dateFrom').val() && $('#dateTo').val()) {
                        table.draw();
                    }
                });

                // Add event listener for status filter
                $('#statusFilter').on('change', function() {
                    table.draw();
                });
            });
        }
    });
</script>
