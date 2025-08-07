@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'reports'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Daily Summary - {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</h4>
        <hr>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card bg-primary text-white">
        <div class="card-body text-center">
          <h5 class="card-title">Total Sales</h5>
          <h3 class="card-text">Rp {{ number_format($totalSales, 2) }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-success text-white">
        <div class="card-body text-center">
          <h5 class="card-title">Total Transactions</h5>
          <h3 class="card-text">{{ $totalTransactions }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-info text-white">
        <div class="card-body text-center">
          <h5 class="card-title">Total Quantity</h5>
          <h3 class="card-text">{{ $totalQuantity }}</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Date Filter -->
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="GET" action="{{ route('reports.daily-summary') }}" class="row">
            <div class="col-md-4">
              <label for="date">Select Date:</label>
              <input type="date" name="date" id="date" class="form-control" value="{{ $date }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button type="submit" class="btn btn-primary">View Summary</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12 mb-3">
      <div class="bg-light text-dark card p-3 overflow-auto">
        <div class="d-flex justify-content-between mb-3">
          <h6 class="mb-0">Transaction Details for {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</h6>
          <a href="{{ route('reports') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>
            Back to Reports
          </a>
        </div>

        @if($reports->count() > 0)
        <table class="table table-light table-striped table-hover table-bordered text-center">
          <thead>
            <tr>
              <th scope="col" class="table-dark">#</th>
              <th scope="col" class="table-dark">Transaction ID</th>
              <th scope="col" class="table-dark">Product Name</th>
              <th scope="col" class="table-dark">Quantity</th>
              <th scope="col" class="table-dark">Unit Price</th>
              <th scope="col" class="table-dark">Total Sales</th>
              <th scope="col" class="table-dark">Payment Method</th>
              <th scope="col" class="table-dark">Cashier</th>
              <th scope="col" class="table-dark">Time</th>
            </tr>
          </thead>
          <tbody>
            @php $rowNumber = 1; @endphp
            @foreach ($reports as $transaction)
              @foreach ($transaction->transactionDetails as $detail)
              <tr>
                <th scope="row">{{ $rowNumber++ }}</th>
                <td>{{ $transaction->transaction_id }}</td>
                <td>{{ $detail->product->product_name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>Rp {{ number_format($detail->historical_unit_price, 2) }}</td>
                <td>Rp {{ number_format($detail->total_amount, 2) }}</td>
                <td>
                  <span class="badge badge-{{ $transaction->paymentMethod->payment_method_name == 'Cash' ? 'success' : 'primary' }}">
                    {{ $transaction->paymentMethod->payment_method_name }}
                  </span>
                </td>
                <td>{{ $transaction->cashier->name }}</td>
                <td>{{ $transaction->transaction_time }}</td>
              </tr>
              @endforeach
            @endforeach
          </tbody>
        </table>
        @else
        <div class="alert alert-info text-center">
          <h5>No transactions found for {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</h5>
          <p>There are no transaction records for this date.</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection 