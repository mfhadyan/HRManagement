@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Methods</h3>
                    <div class="card-tools">
                        <a href="{{ route('payment-methods.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Payment Method
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Payment Method ID</th>
                                    <th>Payment Method Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentMethods as $paymentMethod)
                                    <tr>
                                        <td>{{ $paymentMethod->payment_method_id }}</td>
                                        <td>{{ $paymentMethod->payment_method_name }}</td>
                                        <td>
                                            <a href="{{ route('payment-methods.edit', $paymentMethod) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('payment-methods.destroy', $paymentMethod) }}" method="POST" class="d-inline">
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
                                        <td colspan="3" class="text-center">No payment methods found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $paymentMethods->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 