@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'dashboard'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Dashboard</h4>
        <hr>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Employees</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $employeesCount }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Recruitment Candidates</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $recruitmentCandidatesCount }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-plus fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Ending Contracts</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $endingEmployees->count() }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Active Employees</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $checkForAttendance->count() }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-check fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Recent Transactions</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Transaction ID</th>
                  <th>Product</th>
                  <th>Cashier</th>
                  <th>Total Sales</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($reports as $report)
                <tr>
                  <th scope="row">{{ $loop->iteration }}</th>
                  <td>
                    <a href="{{ route('reports.show', $report) }}">{{ $report->transaction_id }}</a>
                  </td>
                  <td>
                    @foreach($report->transactionDetails as $detail)
                      <span class="badge badge-info">
                        {{ $detail->product->product_name ?? 'N/A' }} ({{ $detail->quantity }})
                      </span>
                    @endforeach
                  </td>
                  <td>{{ $report->cashier->name ?? 'N/A' }}</td>
                  <td>Rp {{ number_format($report->total_sales, 2) }}</td>
                  <td>{{ $report->date }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="text-center mt-3">
            <a href="{{ route('reports') }}" class="btn btn-primary">View All Reports</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
