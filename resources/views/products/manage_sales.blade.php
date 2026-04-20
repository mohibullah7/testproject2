@extends('index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                    <h5 class="card-title m-0 me-2">Sales Management</h5>
                    <div class="d-flex gap-2">
                        {{-- <a href="{{ route('products.export.sales') }}" class="btn btn-sm btn-success"> --}}
                        {{-- <a href="" class="btn btn-sm btn-success">
                            <i class="bx bx-download"></i> Export CSV
                        </a> --}}
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bx bx-arrow-back"></i> Back to Products
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-label-primary h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted d-block">Total Sales</small>
                                            <h3 class="mb-0">${{ number_format($totalSales, 2) }}</h3>
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-primary">
                                                <i class="bx bx-dollar bx-lg"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-label-success h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted d-block">Total Orders</small>
                                            <h3 class="mb-0">{{ number_format($totalOrders) }}</h3>
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-success">
                                                <i class="bx bx-shopping-bag bx-lg"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-label-info h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted d-block">Total Customers</small>
                                            <h3 class="mb-0">{{ number_format($totalCustomers) }}</h3>
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-info">
                                                <i class="bx bx-user bx-lg"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-label-warning h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted d-block">Average Order Value</small>
                                            <h3 class="mb-0">${{ number_format($averageOrderValue, 2) }}</h3>
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-warning">
                                                <i class="bx bx-chart bx-lg"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Purchase Date</th>
                                    <th>Completed At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($purchase->user->name ?? 'U', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div>{{ $purchase->user->name ?? 'Deleted User' }}</div>
                                                <small class="text-muted">{{ $purchase->user->email ?? 'No email' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($purchase->product && $purchase->product->image)
                                                <img src="{{ Storage::url($purchase->product->image) }}" 
                                                     alt="{{ $purchase->product->name }}"
                                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; margin-right: 8px;">
                                            @else
                                                <div class="avatar avatar-sm me-2">
                                                    <span class="avatar-initial rounded bg-label-secondary">
                                                        <i class="bx bx-package"></i>
                                                    </span>
                                                </div>
                                            @endif
                                            <div>
                                                <div>{{ $purchase->product->name ?? 'Product Deleted' }}</div>
                                                <small class="text-muted">ID: {{ $purchase->product_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">{{ $purchase->quantity }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-primary">${{ number_format($purchase->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($purchase->status == 'completed')
                                            <span class="badge bg-label-success">Completed</span>
                                        @elseif($purchase->status == 'pending')
                                            <span class="badge bg-label-warning">Pending</span>
                                        @else
                                            <span class="badge bg-label-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $purchase->created_at->format('M d, Y H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($purchase->completed_at)
                                            <small>{{ $purchase->completed_at->format('M d, Y H:i') }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewSaleModal" 
                                                        data-id="{{ $purchase->id }}"
                                                        data-customer="{{ $purchase->user->name ?? 'N/A' }}"
                                                        data-email="{{ $purchase->user->email ?? 'N/A' }}"
                                                        data-product="{{ $purchase->product->name ?? 'N/A' }}"
                                                        data-quantity="{{ $purchase->quantity }}"
                                                        data-amount="{{ number_format($purchase->total_amount, 2) }}"
                                                        data-status="{{ $purchase->status }}"
                                                        data-created="{{ $purchase->created_at->format('M d, Y H:i:s') }}"
                                                        data-completed="{{ $purchase->completed_at ? $purchase->completed_at->format('M d, Y H:i:s') : 'Not completed' }}">
                                                    <i class="bx bx-show me-1"></i> View Details
                                                </button>
                                                @if($purchase->status == 'pending')
                                                <button type="button" class="dropdown-item text-success mark-completed" data-id="{{ $purchase->id }}">
                                                    <i class="bx bx-check-circle me-1"></i> Mark as Completed
                                                </button>
                                                @endif
                                                <button type="button" class="dropdown-item text-danger delete-sale" data-id="{{ $purchase->id }}">
                                                    <i class="bx bx-trash me-1"></i> Delete Record
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="bx bx-shopping-bag bx-lg text-muted"></i>
                                        <h5 class="mt-3">No Sales Found</h5>
                                        <p class="text-muted">When customers make purchases, they will appear here.</p>
                                        <a href="{{ route('buy.items') }}" class="btn btn-primary btn-sm">
                                            <i class="bx bx-cart"></i> Go to Shop
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Sale Modal -->
<div class="modal fade" id="viewSaleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">Sale Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%">Order ID</th>
                            <td id="modal-order-id"></td>
                        </tr>
                        <tr>
                            <th>Customer Name</th>
                            <td id="modal-customer"></td>
                        </tr>
                        <tr>
                            <th>Customer Email</th>
                            <td id="modal-email"></td>
                        </tr>
                        <tr>
                            <th>Product</th>
                            <td id="modal-product"></td>
                        </tr>
                        <tr>
                            <th>Quantity</th>
                            <td id="modal-quantity"></td>
                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <td id="modal-amount"></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td id="modal-status"></td>
                        </tr>
                        <tr>
                            <th>Purchase Date</th>
                            <td id="modal-created"></td>
                        </tr>
                        <tr>
                            <th>Completed Date</th>
                            <td id="modal-completed"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // View Sale Modal - Populate data
    $('#viewSaleModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        
        $('#modal-order-id').text(button.data('id'));
        $('#modal-customer').text(button.data('customer'));
        $('#modal-email').text(button.data('email'));
        $('#modal-product').text(button.data('product'));
        $('#modal-quantity').text(button.data('quantity'));
        $('#modal-amount').text('$' + button.data('amount'));
        
        let statusHtml = '';
        if (button.data('status') === 'completed') {
            statusHtml = '<span class="badge bg-label-success">Completed</span>';
        } else if (button.data('status') === 'pending') {
            statusHtml = '<span class="badge bg-label-warning">Pending</span>';
        } else {
            statusHtml = '<span class="badge bg-label-danger">Failed</span>';
        }
        $('#modal-status').html(statusHtml);
        
        $('#modal-created').text(button.data('created'));
        $('#modal-completed').text(button.data('completed'));
    });
    
    // Delete sale record
    $('.delete-sale').click(function() {
        const saleId = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this sale record? This action cannot be undone.')) {
            $.ajax({
               url: "{{ route('delete.sale') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE",
                    sale_id: saleId
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        toastr.error(response.message || 'Failed to delete sale');
                    }
                },
                error: function() {
                    toastr.error('Failed to delete sale record');
                }
            });
        }
    });
    
    // Mark as completed
});
</script>
@endpush

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .bg-label-primary {
        background-color: #e7e7ff;
    }
    .bg-label-success {
        background-color: #e8f5e9;
    }
    .bg-label-info {
        background-color: #e3f2fd;
    }
    .bg-label-warning {
        background-color: #fff3e0;
    }
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
</style>
@endpush
@endsection