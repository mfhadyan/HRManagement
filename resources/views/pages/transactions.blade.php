@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transactions</h3>
                    <div class="card-tools">
                        <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Transaction
                        </a>
                        <a href="{{ route('transactions.print') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-print"></i> Print
                        </a>
                        <a href="{{ route('transactions.summary') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-bar"></i> Summary
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('transactions') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="date" name="date" class="form-control" placeholder="Date" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="start_date" class="form-control" placeholder="Start Date" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="end_date" class="form-control" placeholder="End Date" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="cashier_id" class="form-control">
                                    <option value="">All Cashiers</option>
                                    @foreach($cashiers as $cashier)
                                        <option value="{{ $cashier->id }}" {{ request('cashier_id') == $cashier->id ? 'selected' : '' }}>
                                            {{ $cashier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="payment_method_id" class="form-control">
                                    <option value="">All Payment Methods</option>
                                    @foreach($paymentMethods as $paymentMethod)
                                        <option value="{{ $paymentMethod->payment_method_id }}" {{ request('payment_method_id') == $paymentMethod->payment_method_id ? 'selected' : '' }}>
                                            {{ $paymentMethod->payment_method_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('transactions') }}" class="btn btn-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    <!-- Transactions Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Cashier</th>
                                    <th>Payment Method</th>
                                    <th>Products</th>
                                    <th>Total Sales</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_id }}</td>
                                        <td>{{ $transaction->date }}</td>
                                        <td>{{ $transaction->transaction_time }}</td>
                                        <td>{{ $transaction->cashier->name ?? 'N/A' }}</td>
                                        <td>{{ $transaction->paymentMethod->payment_method_name ?? 'N/A' }}</td>
                                        <td>
                                            @foreach($transaction->transactionDetails as $detail)
                                                <span class="badge badge-info">
                                                    {{ $detail->product->product_name ?? 'N/A' }} ({{ $detail->quantity }})
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>${{ number_format($transaction->total_sales, 2) }}</td>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No transactions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 