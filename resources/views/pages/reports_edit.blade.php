@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'reports'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Edit Transaction</h4>
        <hr>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('reports.update', $report) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="transaction_date">Transaction Date <span class="text-danger">*</span></label>
                  <input type="date" name="transaction_date" id="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" value="{{ old('transaction_date', $report->transaction_date) }}" required>
                  @error('transaction_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="transaction_time">Transaction Time <span class="text-danger">*</span></label>
                  <input type="time" name="transaction_time" id="transaction_time" class="form-control @error('transaction_time') is-invalid @enderror" value="{{ old('transaction_time', $report->transaction_time) }}" required>
                  @error('transaction_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="transaction_id">Transaction ID</label>
                  <input type="text" id="transaction_id" class="form-control" value="{{ $report->transaction_id }}" readonly>
                  <small class="form-text text-muted">Transaction ID cannot be modified</small>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="product_name">Product Name/Item <span class="text-danger">*</span></label>
                  <input type="text" name="product_name" id="product_name" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name', $report->product_name) }}" placeholder="Enter product name" required>
                  @error('product_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="quantity">Quantity <span class="text-danger">*</span></label>
                  <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $report->quantity) }}" min="1" required>
                  @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="unit_price">Unit Price (Rp) <span class="text-danger">*</span></label>
                  <input type="number" name="unit_price" id="unit_price" class="form-control @error('unit_price') is-invalid @enderror" value="{{ old('unit_price', $report->unit_price) }}" step="0.01" min="0" placeholder="0.00" required>
                  @error('unit_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                  <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                    <option value="">Select Payment Method</option>
                    @foreach($paymentMethods as $method)
                      <option value="{{ $method }}" {{ old('payment_method', $report->payment_method) == $method ? 'selected' : '' }}>
                        {{ $method }}
                      </option>
                    @endforeach
                  </select>
                  @error('payment_method')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="cashier_id">Cashier/Server <span class="text-danger">*</span></label>
                  <select name="cashier_id" id="cashier_id" class="form-control @error('cashier_id') is-invalid @enderror" required>
                    <option value="">Select Cashier</option>
                    @foreach($cashiers as $cashier)
                      <option value="{{ $cashier->id }}" {{ old('cashier_id', $report->cashier_id) == $cashier->id ? 'selected' : '' }}>
                        {{ $cashier->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('cashier_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="comments">Comments/Notes</label>
                  <textarea name="comments" id="comments" class="form-control @error('comments') is-invalid @enderror" rows="3" placeholder="Additional notes...">{{ old('comments', $report->comments) }}</textarea>
                  @error('comments')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Total Sales Preview</label>
                  <div class="alert alert-info">
                    <strong>Total Sales: Rp <span id="total-sales-preview">{{ number_format($report->total_sales, 2) }}</span></strong>
                    <small class="d-block">(Quantity × Unit Price)</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save mr-1"></i>
                    Update Transaction
                  </button>
                  <a href="{{ route('reports') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to Reports
                  </a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const unitPriceInput = document.getElementById('unit_price');
    const totalSalesPreview = document.getElementById('total-sales-preview');
    
    function updateTotalSales() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitPrice = parseFloat(unitPriceInput.value) || 0;
        const totalSales = quantity * unitPrice;
        totalSalesPreview.textContent = totalSales.toFixed(2);
    }
    
    quantityInput.addEventListener('input', updateTotalSales);
    unitPriceInput.addEventListener('input', updateTotalSales);
    
    // Initialize on page load
    updateTotalSales();
});
</script>
@endsection 