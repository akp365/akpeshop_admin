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

            .form-group {
                margin-bottom: 1rem;
            }

            .form-group label {
                font-weight: 600;
                margin-bottom: 0.5rem;
                color: #495057;
            }

            .btn-container {
                display: flex;
                justify-content: space-between;
                margin-top: 2rem;
            }
        </style>
    @endpush

    <div class="container gift-balance-container">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0" style="color: white;">Edit Gift Balance</h4>
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

                <form action="{{ route('gift-balance.update', $giftBalance->id) }}" method="POST" style="padding: 20px;">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="customer">Customer</label>
                        <div class="search-container">
                            <input type="text"
                                   class="form-control customer-search-input"
                                   value="{{ $giftBalance->customer->name }} ({{ $giftBalance->customer->email }}) - {{ $giftBalance->customer->phone }}"
                                   readonly />
                            <input type="hidden"
                                   name="customer_id"
                                   value="{{ $giftBalance->customer_id }}"
                                   required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text"
                               name="description"
                               class="form-control"
                               value="{{ $giftBalance->description }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="currency_id">Currency</label>
                        <select name="currency_id" class="form-control" required>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}"
                                    {{ $currency->id == $giftBalance->currency_id ? 'selected' : '' }}>
                                    {{ $currency->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number"
                               name="amount"
                               class="form-control"
                               value="{{ $giftBalance->in }}"
                               step="0.01"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="gift_voucher" {{ $giftBalance->status == 'gift_voucher' ? 'selected' : '' }}>
                                Gift Voucher
                            </option>
                            <option value="bonus" {{ $giftBalance->status == 'bonus' ? 'selected' : '' }}>
                                Bonus
                            </option>
                            <option value="refund" {{ $giftBalance->status == 'refund' ? 'selected' : '' }}>
                                Refund
                            </option>
                        </select>
                    </div>

                    <div class="btn-container">
                        <a href="{{ route('gift-balance.index') }}" class="btn btn-primary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-success" style="background: green;">
                            Update Gift Balance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
