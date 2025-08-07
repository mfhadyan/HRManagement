@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'reports'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Transaction Details</h4>
        <hr>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Transaction ID: {{ $report->transaction_id }}</h6>
            <div>
              <a href="{{ route('reports.edit', $report) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit mr-1"></i>
                Edit
              </a>
              <a href="{{ route('reports') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>
                Back
              </a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <table class="table table-borderless">
                <tr>
                  <td><strong>Transaction Date:</strong></td>
                  <td>{{ $report->transaction_date }}</td>
                </tr>
                <tr>
                  <td><strong>Transaction Time:</strong></td>
                  <td>{{ $report->transaction_time }}</td>
                </tr>
                <tr>
                  <td><strong>Product Name:</strong></td>
                  <td>{{ $report->product_name }}</td>
                </tr>
                <tr>
                  <td><strong>Quantity:</strong></td>
                  <td>{{ $report->quantity }}</td>
                </tr>
                <tr>
                  <td><strong>Unit Price:</strong></td>
                  <td>Rp {{ $report->formatted_unit_price }}</td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <table class="table table-borderless">
                <tr>
                  <td><strong>Total Sales:</strong></td>
                  <td><span class="badge badge-success">Rp {{ $report->total_sales }}</span></td>
                </tr>
                <tr>
                  <td><strong>Payment Method:</strong></td>
                  <td>
                    <span class="badge badge-{{ $report->payment_method == 'Cash' ? 'success' : 'primary' }}">
                      {{ $report->payment_method }}
                    </span>
                  </td>
                </tr>
                <tr>
                  <td><strong>Cashier/Server:</strong></td>
                  <td>{{ $report->cashier->name }}</td>
                </tr>
                <tr>
                  <td><strong>Created At:</strong></td>
                  <td>{{ $report->created_at }}</td>
                </tr>
                <tr>
                  <td><strong>Last Updated:</strong></td>
                  <td>{{ $report->updated_at }}</td>
                </tr>
              </table>
            </div>
          </div>
          
          @if($report->comments)
          <div class="row mt-3">
            <div class="col-12">
              <div class="alert alert-info">
                <strong>Comments/Notes:</strong><br>
                {{ $report->comments }}
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