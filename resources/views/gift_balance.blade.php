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

            .search-container {
                position: relative;
                width: 100%;
                min-height: 38px;
            }
            .customer-search-input {
                width: 100%;
            }
            .customer-search-results {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                max-height: 200px;
                overflow-y: auto;
                z-index: 9999;
            }
            .search-result-item {
                padding: 8px 12px;
                cursor: pointer;
                border-bottom: 1px solid #eee;
            }
            .search-result-item:last-child {
                border-bottom: none;
            }
            .search-result-item:hover {
                background-color: #f8f9fa;
            }
            .table td {
                vertical-align: top;
                padding: 8px;
                position: relative;
            }
            .table th {
                padding: 12px 8px;
            }
            .form-control {
                height: 38px;
            }
            .btn-sm {
                padding: 0.25rem 0.5rem;
            }
        </style>
    @endpush

    <div class="container gift-balance-container">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0 text-light" style="color: white;">Add Gift Balancea</h4>
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
                                    <td style="min-width: 250px;">
                                        <div class="search-container">
                                            <input type="text" placeholder="Search customer" class="form-control customer-search-input" />
                                            <div class="customer-search-results"></div>
                                            <input type="hidden" name="customer_email[]" class="customer-id" required>
                                        </div>
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
                            APPROVE
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($giftBalances as $balance)
                                <tr>
                                    <td>{{ $balance->customer ? $balance->customer->email : 'N/A' }}</td>
                                    <td>{{ $balance->description }}</td>
                                    <td>{{ $balance->currency ? $balance->currency->title : 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $balance->in > 0 ? 'badge-success' : 'badge-info' }}" style="color: white;">
                                            {{ $balance->in > 0 ? '+' . $balance->in : $balance->out }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $balance->status === 'gift_voucher' ? 'badge-success' : ($balance->status === 'bonus' ? 'badge-info' : 'badge-warning') }}" style="color: white;">
                                            {{ ucfirst($balance->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $balance->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('gift-balance.edit', $balance->id) }}"
                                               class="btn btn-sm btn-info mr-1">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('gift-balance.destroy', $balance->id) }}"
                                                  method="POST"
                                                  style="display: inline-block; margin-left: 10px;"
                                                  onsubmit="return confirm('Are you sure you want to delete this gift balance?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript">
            if (typeof jQuery === 'undefined') {
                console.error('jQuery is not loaded!');
            } else {
                console.log('jQuery is loaded, version:', jQuery.fn.jquery);
            }

            $(document).ready(function () {
                console.log('Document ready!'); // Debug log

                // Initialize customer search functionality
                function initializeCustomerSearch(row) {
                    const searchInput = row.find('.customer-search-input');
                    const searchResults = row.find('.customer-search-results');
                    const customerIdInput = row.find('.customer-id');
                    let searchTimeout;

                    // Remove any existing event handlers
                    searchInput.off('input');
                    $(document).off('click.searchResults');

                    searchInput.on('input', function(e) {
                        e.stopPropagation();
                        const currentRow = $(this).closest('tr');
                        const currentResults = currentRow.find('.customer-search-results');

                        // Hide all other search results
                        $('.customer-search-results').not(currentResults).hide();

                        const searchTerm = $(this).val();
                        console.log('Search term:', searchTerm); // Debug log

                        clearTimeout(searchTimeout);

                        if (searchTerm.length >= 2) {
                            searchTimeout = setTimeout(function() {
                                $.ajax({
                                    type: 'GET',
                                    url: '{{ route("search.customers") }}',
                                    data: { q: searchTerm },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        currentResults.html('<div class="p-2">Searching...</div>');
                                        currentResults.show();
                                        const tdHeight = searchInput.outerHeight() + currentResults.outerHeight();
                                        currentRow.find('td:first').css('height', tdHeight + 'px');
                                    },
                                    success: function(data) {
                                        currentResults.empty();

                                        if (data.items && data.items.length > 0) {
                                            data.items.forEach(function(item) {
                                                const resultItem = $('<div>', {
                                                    class: 'search-result-item p-2 cursor-pointer hover:bg-gray-100',
                                                    'data-id': item.id,
                                                    'data-text': item.text
                                                }).text(item.text);

                                                resultItem.on('click', function(e) {
                                                    e.stopPropagation();
                                                    const selectedId = $(this).data('id');
                                                    const selectedText = $(this).data('text');

                                                    const targetInput = currentRow.find('.customer-search-input');
                                                    const targetIdInput = currentRow.find('.customer-id');

                                                    targetInput.val(selectedText);
                                                    targetIdInput.val(selectedId);
                                                    currentResults.hide();
                                                    currentRow.find('td:first').css('height', 'auto');
                                                });

                                                currentResults.append(resultItem);
                                            });

                                            currentResults.show();
                                            const tdHeight = searchInput.outerHeight() + currentResults.outerHeight();
                                            currentRow.find('td:first').css('height', tdHeight + 'px');
                                        } else {
                                            currentResults.html('<div class="p-2">No results found</div>');
                                            currentResults.show();
                                            const tdHeight = searchInput.outerHeight() + currentResults.outerHeight();
                                            currentRow.find('td:first').css('height', tdHeight + 'px');
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        currentResults.html('<div class="p-2 text-danger">Error occurred while searching</div>');
                                        currentResults.show();
                                    }
                                });
                            }, 300);
                        } else {
                            currentResults.hide();
                            currentRow.find('.customer-id').val('');
                            currentRow.find('td:first').css('height', 'auto');
                        }
                    });

                    // Handle clicks outside of search results
                    $(document).on('click.searchResults', function(e) {
                        if (!$(e.target).closest('.search-container').length) {
                            searchResults.hide();
                            row.find('td:first').css('height', 'auto');
                        }
                    });
                }

                // Initialize for existing rows
                $('#giftBalanceRows tr').each(function() {
                    initializeCustomerSearch($(this));
                });

                // Add row functionality
                $(document).on('click', '.add-row', function() {
                    const newRow = $(this).closest('tr').clone(true);
                    newRow.find('input').val('');
                    newRow.find('.customer-search-results').empty().hide();
                    newRow.find('td:first').css('height', 'auto');
                    $('#giftBalanceRows').append(newRow);
                    initializeCustomerSearch(newRow);
                });

                // Delete row functionality
                $(document).on('click', '.delete-row', function() {
                    if ($('#giftBalanceRows tr').length > 1) {
                        $(this).closest('tr').remove();
                    }
                });
            });
        </script>
@endsection