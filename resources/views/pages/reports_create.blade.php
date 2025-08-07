@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'reports'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Add New Transaction</h4>
        <hr>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('reports.store') }}" method="POST">
            @csrf
            
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="date">Transaction Date <span class="text-danger">*</span></label>
                  <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                  @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="transaction_time">Transaction Time <span class="text-danger">*</span></label>
                  <input type="time" name="transaction_time" id="transaction_time" class="form-control @error('transaction_time') is-invalid @enderror" value="{{ old('transaction_time', date('H:i')) }}" required>
                  @error('transaction_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="payment_method_id">Payment Method <span class="text-danger">*</span></label>
                  <select name="payment_method_id" id="payment_method_id" class="form-control @error('payment_method_id') is-invalid @enderror" required>
                    <option value="">Select Payment Method</option>
                    @foreach($paymentMethods as $method)
                      <option value="{{ $method->payment_method_id }}" {{ old('payment_method_id') == $method->payment_method_id ? 'selected' : '' }}>
                        {{ $method->payment_method_name }}
                      </option>
                    @endforeach
                  </select>
                  @error('payment_method_id')
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
                      <option value="{{ $cashier->id }}" {{ old('cashier_id') == $cashier->id ? 'selected' : '' }}>
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
              <div class="col-md-12">
                <div class="form-group">
                  <label for="comments">Comments/Notes</label>
                  <textarea name="comments" id="comments" class="form-control @error('comments') is-invalid @enderror" rows="3" placeholder="Additional notes...">{{ old('comments') }}</textarea>
                  @error('comments')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Products <span class="text-danger">*</span></label>
                  <div id="products-container">
                    <div class="product-row row mb-2">
                      <div class="col-md-4">
                        <select name="products[0][product_id]" class="form-control product-select" required>
                          <option value="">Select Product</option>
                          @foreach($products as $product)
                            <option value="{{ $product->product_id }}" data-price="{{ $product->unit_price }}">
                              {{ $product->product_name }} - Rp {{ number_format($product->unit_price, 2) }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-3">
                        <input type="number" name="products[0][quantity]" class="form-control quantity-input" value="1" min="1" placeholder="Qty" required>
                      </div>
                      <div class="col-md-3">
                        <input type="text" class="form-control subtotal-display" readonly placeholder="Subtotal">
                      </div>
                      <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-product">Remove</button>
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-success btn-sm" id="add-product">Add Product</button>
                </div>
              </div>
            </div>



            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Total Sales Preview</label>
                  <div class="alert alert-info">
                    <strong>Total Sales: Rp <span id="total-sales-preview">0.00</span></strong>
                    <small class="d-block">(Quantity × Unit Price)</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <button type="submit" class="btn btn-success">
                    <i class="fas fa-save mr-1"></i>
                    Save Transaction
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
    let productIndex = 1;
    const productsContainer = document.getElementById('products-container');
    const addProductBtn = document.getElementById('add-product');
    const totalSalesPreview = document.getElementById('total-sales-preview');
    
    function updateSubtotal(row) {
        const productSelect = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const subtotalDisplay = row.querySelector('.subtotal-display');
        
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price) || 0;
        const quantity = parseFloat(quantityInput.value) || 0;
        const subtotal = price * quantity;
        
        subtotalDisplay.value = 'Rp ' + subtotal.toFixed(2);
        updateTotalSales();
    }
    
    function updateTotalSales() {
        let total = 0;
        document.querySelectorAll('.subtotal-display').forEach(display => {
            const value = display.value.replace('Rp ', '');
            total += parseFloat(value) || 0;
        });
        totalSalesPreview.textContent = total.toFixed(2);
    }
    
    function addProductRow() {
        const newRow = document.createElement('div');
        newRow.className = 'product-row row mb-2';
        newRow.innerHTML = `
            <div class="col-md-4">
                <select name="products[${productIndex}][product_id]" class="form-control product-select" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->product_id }}" data-price="{{ $product->unit_price }}">
                            {{ $product->product_name }} - Rp {{ number_format($product->unit_price, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="products[${productIndex}][quantity]" class="form-control quantity-input" value="1" min="1" placeholder="Qty" required>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control subtotal-display" readonly placeholder="Subtotal">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-product">Remove</button>
            </div>
        `;
        
        productsContainer.appendChild(newRow);
        productIndex++;
        
        // Add event listeners to new row
        const productSelect = newRow.querySelector('.product-select');
        const quantityInput = newRow.querySelector('.quantity-input');
        const removeBtn = newRow.querySelector('.remove-product');
        
        productSelect.addEventListener('change', () => updateSubtotal(newRow));
        quantityInput.addEventListener('input', () => updateSubtotal(newRow));
        removeBtn.addEventListener('click', () => {
            newRow.remove();
            updateTotalSales();
        });
    }
    
    // Add event listeners to initial row
    const initialRow = document.querySelector('.product-row');
    const initialProductSelect = initialRow.querySelector('.product-select');
    const initialQuantityInput = initialRow.querySelector('.quantity-input');
    const initialRemoveBtn = initialRow.querySelector('.remove-product');
    
    initialProductSelect.addEventListener('change', () => updateSubtotal(initialRow));
    initialQuantityInput.addEventListener('input', () => updateSubtotal(initialRow));
    initialRemoveBtn.addEventListener('click', () => {
        if (document.querySelectorAll('.product-row').length > 1) {
            initialRow.remove();
            updateTotalSales();
        }
    });
    
    // Add product button
    addProductBtn.addEventListener('click', addProductRow);
    
    // Initialize
    updateTotalSales();
});
</script>
@endsection 