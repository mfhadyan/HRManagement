@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'reports'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Transaction Reports</h4>
        <hr>
    </div>
  </div>

  <!-- Filters -->
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h6 class="mb-0">Filters</h6>
        </div>
        <div class="card-body">
          <form method="GET" action="{{ route('reports') }}" class="row">
            <div class="col-md-2">
              <label for="date">Date:</label>
              <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
              <label for="start_date">Start Date:</label>
              <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-2">
              <label for="end_date">End Date:</label>
              <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2">
              <label for="cashier_id">Cashier:</label>
              <select name="cashier_id" id="cashier_id" class="form-control">
                <option value="">All Cashiers</option>
                @foreach($cashiers as $cashier)
                  <option value="{{ $cashier->id }}" {{ request('cashier_id') == $cashier->id ? 'selected' : '' }}>
                    {{ $cashier->name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label for="payment_method">Payment Method:</label>
              <select name="payment_method" id="payment_method" class="form-control">
                <option value="">All Methods</option>
                @foreach($paymentMethods as $method)
                  <option value="{{ $method->payment_method_id }}" {{ request('payment_method') == $method->payment_method_id ? 'selected' : '' }}>
                    {{ $method->payment_method_name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button type="submit" class="btn btn-primary mr-2">Filter</button>
              <a href="{{ route('reports') }}" class="btn btn-secondary">Clear</a>
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
          <a href="{{ route('reports.create') }}" class="btn btn-success">
            <i class="fas fa-plus mr-1"></i>
            <span>Add Transaction</span>
          </a>
          <div>
            <a href="{{ route('reports.daily-summary') }}" class="btn btn-info mr-2">
              <i class="fas fa-chart-bar mr-1"></i>
              <span>Daily Summary</span>
            </a>
            <a href="{{ route('reports.print') }}?{{ http_build_query(request()->all()) }}" class="btn btn-outline-dark" target="_blank">
              <i class="fas fa-print mr-1"></i>
              <span>Print</span>
            </a>
          </div>
        </div>

        <table class="table table-light table-striped table-hover table-bordered text-center">
          <thead>
            <tr>
              <th scope="col" class="table-dark">Date</th>
              <th scope="col" class="table-dark">Transaction ID</th>
              <th scope="col" class="table-dark">Products</th>
              <th scope="col" class="table-dark">Total Sales</th>
              <th scope="col" class="table-dark">Payment Method</th>
              <th scope="col" class="table-dark">Cashier</th>
              <th scope="col" class="table-dark">Time</th>
              <th scope="col" class="table-dark">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($reports as $report)
            <tr>
              <td>{{ $report->date }}</td>
              <td>{{ $report->transaction_id }}</td>
              <td>
                @foreach($report->transactionDetails as $detail)
                  <span class="badge badge-info">
                    {{ $detail->product->product_name ?? 'N/A' }} ({{ $detail->quantity }})
                  </span>
                @endforeach
              </td>
              <td>Rp {{ number_format($report->total_sales, 2) }}</td>
              <td>
                <span class="badge badge-{{ $report->paymentMethod->payment_method_name == 'Cash' ? 'success' : 'primary' }}">
                  {{ $report->paymentMethod->payment_method_name ?? 'N/A' }}
                </span>
              </td>
              <td>{{ $report->cashier->name ?? 'N/A' }}</td>
              <td>{{ $report->transaction_time }}</td>
              <td>
                <div class="btn-group" role="group">
                  <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="{{ route('reports.edit', $report) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                  </a>
                  <form action="{{ route('reports.destroy', $report) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this transaction?')">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        {{ $reports->links() }}  
      </div>
    </div>
  </div>
</div>
@endsection 