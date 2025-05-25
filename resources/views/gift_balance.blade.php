@extends('layout')

@section('content')
    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .gift-balance-container {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .card {
            background: #fff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background: #28a745;
            color: white;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            border-bottom: none;
        }
        .card-header h4 {
            margin: 0;
            font-weight: 600;
        }
        .card-body {
            padding: 20px;
        }
        .table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 0;
        }
        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
            font-weight: 600;
            padding: 12px;
        }
        .table td {
            padding: 12px;
            vertical-align: middle;
        }
        .form-control {
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 8px 12px;
            height: auto;
        }
        .select2-container {
            width: 100% !important;
        }
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
            padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .btn-success {
            background: #28a745;
            border: none;
            padding: 8px 20px;
            transition: all 0.3s;
        }
        .btn-success:hover {
            background: #218838;
            transform: translateY(-1px);
        }
        .btn-danger {
            background: #dc3545;
            border: none;
            padding: 8px 20px;
            transition: all 0.3s;
        }
        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-1px);
        }
        .alert {
            border-radius: 4px;
            padding: 12px 20px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .existing-balances {
            margin-top: 30px;
        }
        .existing-balances h5 {
            color: #495057;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
        }
        .badge-success {
            background: #28a745;
        }
        .badge-info {
            background: #17a2b8;
        }
        .badge-warning {
            background: #ffc107;
            color: #000;
        }
    </style>
    @endpush

    <div class="container gift-balance-container">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Add Gift Balance</h4>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('gift-balance.store') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Customer Search</th>
                                    <th>Description</th>
                                    <th>Currency</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                
                            </thead>
                            <tbody id="giftBalanceRows">
                                <tr>
                                    <td>
                                        <select name="customer_email[]" class="form-control customer-select" required>
                                            <option value="">Search customer...</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="description[]" class="form-control" placeholder="Enter description" required>
                                    </td>
                                    <td>
                                        <select name="currency_id[]" class="form-control" required>
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->title }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="amount[]" class="form-control" step="0.01" placeholder="0.00" required>
                                    </td>
                                    <td>
                                        <select name="status[]" class="form-control" required>
                                            <option value="gift_voucher">Gift Voucher</option>
                                            <option value="bonus">Bonus</option>
                                            <option value="refund">Refund</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm add-row">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm delete-row">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success px-5">
                            <i class="fa fa-check-circle"></i> APPROVE
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Display existing gift balances -->
        <div class="card existing-balances">
            <div class="card-body">
                <h5>Existing Gift Balances</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Customer Email</th>
                                <th>Description</th>
                                <th>Currency</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($giftBalances as $balance)
                                <tr>
                                    <td>{{ $balance->customer ? $balance->customer->email : 'N/A' }}</td>
                                    <td>{{ $balance->description }}</td>
                                    <td>{{ $balance->currency ? $balance->currency->title : 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $balance->in > 0 ? 'badge-success' : 'badge-info' }}">
                                            {{ $balance->in > 0 ? '+' . $balance->in : $balance->out }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $balance->status === 'gift_voucher' ? 'badge-success' : ($balance->status === 'bonus' ? 'badge-info' : 'badge-warning') }}">
                                            {{ ucfirst($balance->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $balance->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(function() {
            function initializeSelect2(element) {
                $(element).select2({
                    placeholder: 'Search customer...',
                    allowClear: true,
                    ajax: {
                        url: '{{ route("search.customers") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                page: params.page
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.items,
                                pagination: {
                                    more: data.total_count > 0
                                }
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 2
                });
            }

            // Initialize Select2 for existing elements
            $('.customer-select').each(function() {
                initializeSelect2(this);
            });

            // Add row functionality
            $(document).on('click', '.add-row', function() {
                const row = $(this).closest('tr').clone(true);
                row.find('input').val('');
                row.find('select').prop('selectedIndex', 0);

                // Remove existing Select2
                row.find('.customer-select').select2('destroy');

                $('#giftBalanceRows').append(row);

                // Initialize Select2 for the new row
                initializeSelect2(row.find('.customer-select'));
            });

            // Delete row functionality
            $(document).on('click', '.delete-row', function() {
                if ($('#giftBalanceRows tr').length > 1) {
                    $(this).closest('tr').remove();
                }
            });
        });
    </script>
    @endpush
@endsection
