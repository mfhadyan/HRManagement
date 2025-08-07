@extends('layouts.print')

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold text-center">Transaction Reports</h4>
        <hr>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12 mb-3">
      <div class="bg-light text-dark card p-3 overflow-auto">
        <table class="table table-light table-striped table-hover table-bordered text-center">
          <thead>
            <tr>
              <th scope="col" class="table-dark">#</th>
              <th scope="col" class="table-dark">Date</th>
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
            @foreach ($reports as $report)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td>{{ $report->transaction_date }}</td>
              <td>{{ $report->transaction_id }}</td>
              <td>{{ $report->product_name }}</td>
              <td>{{ $report->quantity }}</td>
              <td>Rp {{ $report->formatted_unit_price }}</td>
              <td>Rp {{ $report->total_sales }}</td>
              <td>{{ $report->payment_method }}</td>
              <td>{{ $report->cashier->name }}</td>
              <td>{{ $report->transaction_time }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        
        @if($reports->count() > 0)
        <div class="row mt-3">
          <div class="col-12">
            <div class="alert alert-info">
              <strong>Summary:</strong><br>
              Total Transactions: {{ $reports->count() }}<br>
              Total Sales: Rp {{ number_format($reports->sum(function($report) { return $report->quantity * $report->unit_price; }), 2) }}<br>
              Total Quantity: {{ $reports->sum('quantity') }}
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('_script')
    <script>
      window.onload = function () {
        window.print();
      }
    </script>
@endsection 