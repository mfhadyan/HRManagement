@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaction Summary</h3>
                    <div class="card-tools">
                        <a href="{{ route('transactions') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Transactions
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Period Selection -->
                    <form method="GET" action="{{ route('transactions.summary') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="period" class="form-control">
                                    <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date" class="form-control" value="{{ $date }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Generate Summary</button>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Sales</h5>
                                    <h3 class="card-text">${{ number_format($totalSales, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Transactions</h5>
                                    <h3 class="card-text">{{ $totalTransactions }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Quantity</h5>
                                    <h3 class="card-text">{{ $totalQuantity }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Period Info -->
                    <div class="alert alert-info">
                        <strong>{{ ucfirst($period) }} Summary:</strong> 
                        @if($period == 'daily')
                            {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
                        @elseif($period == 'weekly')
                            Week of {{ \Carbon\Carbon::parse($date)->startOfWeek()->format('F j, Y') }} to {{ \Carbon\Carbon::parse($date)->endOfWeek()->format('F j, Y') }}
                        @else
                            {{ \Carbon\Carbon::parse($date)->format('F Y') }}
                        @endif
                    </div>

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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No transactions found for this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary by Payment Method -->
                    @if($transactions->count() > 0)
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Sales by Payment Method</h5>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            $paymentMethodSales = $transactions->groupBy('paymentMethod.payment_method_name')
                                                ->map(function($group) {
                                                    return $group->sum('total_sales');
                                                });
                                        @endphp
                                        @foreach($paymentMethodSales as $method => $sales)
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>{{ $method ?? 'Unknown' }}</span>
                                                <span class="font-weight-bold">${{ number_format($sales, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Top Products</h5>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            $productSales = collect();
                                            foreach($transactions as $transaction) {
                                                foreach($transaction->transactionDetails as $detail) {
                                                    $productName = $detail->product->product_name ?? 'Unknown';
                                                    $total = $detail->quantity * $detail->historical_unit_price;
                                                    if($productSales->has($productName)) {
                                                        $productSales[$productName] += $total;
                                                    } else {
                                                        $productSales[$productName] = $total;
                                                    }
                                                }
                                            }
                                            $productSales = $productSales->sortDesc()->take(5);
                                        @endphp
                                        @foreach($productSales as $product => $sales)
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>{{ $product }}</span>
                                                <span class="font-weight-bold">${{ number_format($sales, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 