@extends('index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Shop Products</h5>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Products
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Products Grid -->
                    <div class="row">
                        @forelse($products as $product)
                        <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                            <div class="card h-100 product-card">
                                <!-- Product Image -->
                                <div class="card-img-top text-center p-3" style="height: 200px; overflow: hidden;">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-fluid" 
                                             style="height: 100%; object-fit: cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                            <i class="bx bx-image bx-lg text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="card-body">
                                    <h5 class="card-title mb-2">{{ Str::limit($product->name, 50) }}</h5>
                                    
                                    <div class="mb-2">
                                        <span class="h4 text-primary">${{ number_format($product->price, 2) }}</span>
                                    </div>
                                    
                                    <p class="card-text text-muted small">
                                        {{ Str::limit($product->detail, 80) }}
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <small class="text-muted">
                                            <i class="bx bx-calendar"></i> {{ $product->created_at->format('M d, Y') }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="bx bx-box"></i> In Stock
                                        </small>
                                    </div>
                                    
                                    <button type="button" 
                                            class="btn btn-primary w-100 buy-btn"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-product-price="{{ $product->price }}"
                                            data-product-image="{{ $product->image ? Storage::url($product->image) : '' }}">
                                        <i class="bx bx-cart"></i> Buy Now
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bx bx-package bx-lg text-muted"></i>
                                <h5 class="mt-3">No Products Available</h5>
                                <p class="text-muted">Check back later for new products!</p>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Purchase Modal -->
<div class="modal fade" id="purchaseModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">Complete Your Purchase</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Order Summary Section -->
                <div id="order-summary">
                    <div class="text-center mb-4">
                        <i class="bx bx-shopping-bag bx-lg text-primary"></i>
                        <h4 class="mt-2">Order Summary</h4>
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0">
                                    <img id="modal-product-image" src="" alt="Product" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 id="modal-product-name" class="mb-2"></h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Quantity:</span>
                                        <div class="input-group" style="width: 150px;">
                                            <button class="btn btn-outline-secondary decrement-qty" type="button">-</button>
                                            <input type="number" id="quantity" class="form-control text-center" value="1" min="1" max="99">
                                            <button class="btn btn-outline-secondary increment-qty" type="button">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Price per item:</span>
                                <span>$<span id="modal-product-price">0.00</span></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Quantity:</span>
                                <span id="display-quantity">1</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total Amount:</strong>
                                <strong class="text-primary">$<span id="total-amount">0.00</span></strong>
                            </div>
                            
                            <div class="alert alert-info mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-credit-card me-2"></i>
                                    <small>Secure payment via Stripe. Your card details are encrypted.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmPurchaseBtn">
                    <i class="bx bx-lock"></i> Proceed to Payment
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .product-card .card-img-top {
        background-color: #f8f9fa;
    }
    .buy-btn {
        transition: all 0.3s ease;
    }
    .buy-btn:hover {
        transform: scale(1.02);
    }
    .input-group .btn {
        cursor: pointer;
    }
    .input-group .btn:hover {
        background-color: #e9ecef;
    }
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
    .StripeElement {
        padding: 10px;
        border: 1px solid #d9dee3;
        border-radius: 5px;
        background-color: white;
    }
    .StripeElement--focus {
        border-color: #696cff;
        box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.1);
    }
    .payment-form-card {
        margin-top: 1rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
$(document).ready(function() {
    // Initialize toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };
    
    // Initialize Stripe
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    let selectedProduct = null;
    
    // Buy button click handler
    $('.buy-btn').click(function() {
        selectedProduct = {
            id: $(this).data('product-id'),
            name: $(this).data('product-name'),
            price: parseFloat($(this).data('product-price')),
            image: $(this).data('product-image') || '/assets/img/default-product.png'
        };
        
        $('#modal-product-name').text(selectedProduct.name);
        $('#modal-product-price').text(selectedProduct.price.toFixed(2));
        $('#modal-product-image').attr('src', selectedProduct.image);
        $('#quantity').val(1);
        updateTotalAmount();
        
        // Make sure order summary is visible
        $('#order-summary').show();
        $('.payment-form-card').remove();
        
        $('#purchaseModal').modal('show');
    });
    
    // Update total amount function
    function updateTotalAmount() {
        const quantity = parseInt($('#quantity').val());
        const total = selectedProduct.price * quantity;
        $('#total-amount').text(total.toFixed(2));
        $('#display-quantity').text(quantity);
    }
    
    // Quantity handlers
    $('.increment-qty').click(function() {
        let currentVal = parseInt($('#quantity').val());
        if (currentVal < 99) {
            $('#quantity').val(currentVal + 1);
            updateTotalAmount();
        }
    });
    
    $('.decrement-qty').click(function() {
        let currentVal = parseInt($('#quantity').val());
        if (currentVal > 1) {
            $('#quantity').val(currentVal - 1);
            updateTotalAmount();
        }
    });
    
    $('#quantity').on('change', function() {
        let val = parseInt($(this).val());
        if (isNaN(val) || val < 1) {
            $(this).val(1);
        } else if (val > 99) {
            $(this).val(99);
        }
        updateTotalAmount();
    });
    
    // Confirm purchase button click
    $('#confirmPurchaseBtn').click(function() {
        if (!selectedProduct) return;
        
        const quantity = parseInt($('#quantity').val());
        const totalAmount = selectedProduct.price * quantity;
        
        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Processing...');
        
        $.ajax({
            url: "{{ route('products.create-payment-intent') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: selectedProduct.id,
                quantity: quantity,
                total_amount: totalAmount
            },
            success: function(response) {
                if (response.success && response.client_secret) {
                    showCardForm(response.client_secret);
                    btn.prop('disabled', false).html(originalText);
                } else {
                    toastr.error(response.message || 'Failed to initialize payment');
                    btn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                let errorMsg = 'Something went wrong. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                toastr.error(errorMsg);
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Show card collection form
    function showCardForm(clientSecret) {
        // Hide order summary
        $('#order-summary').hide();
        
        // Remove existing payment form if any
        $('.payment-form-card').remove();
        
        // Create payment form
        const formHtml = `
            <div class="payment-form-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">Enter Card Details</h6>
                        <div id="card-element" class="StripeElement"></div>
                        <div id="card-errors" class="text-danger mt-2" style="font-size: 14px;"></div>
                        <button type="button" id="submit-payment" class="btn btn-primary w-100 mt-3">
                            <i class="bx bx-lock"></i> Pay $${$('#total-amount').text()}
                        </button>
                        <button type="button" id="back-to-summary" class="btn btn-link w-100 mt-2">
                            ← Back
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        $('.modal-body').append(formHtml);
        
        // Create Stripe Elements
        const elements = stripe.elements();
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                }
            },
            hidePostalCode: true
        });
        cardElement.mount('#card-element');
        
        // Handle payment submission
        $('#submit-payment').click(function() {
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Processing...');
            
            stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: '{{ auth()->user()->name ?? "" }}',
                        email: '{{ auth()->user()->email ?? "" }}'
                    }
                }
            }).then(function(result) {
                if (result.error) {
                    $('#card-errors').text(result.error.message);
                    btn.prop('disabled', false).html('<i class="bx bx-lock"></i> Pay $' + $('#total-amount').text());
                } else {
                    if (result.paymentIntent.status === 'succeeded') {
                        // Confirm payment on server
                        $.ajax({
                            url: "{{ route('products.confirm-payment') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                payment_intent_id: result.paymentIntent.id,
                                product_id: selectedProduct.id,
                                quantity: $('#quantity').val()
                            },
                            success: function(response) {
                                if (response.success) {
                                    $('#purchaseModal').modal('hide');
                                    toastr.success('Payment successful! Thank you for your purchase.');
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000);
                                } else {
                                    toastr.error('Payment succeeded but verification failed');
                                }
                            },
                            error: function() {
                                toastr.error('Payment succeeded but verification failed');
                            }
                        });
                    }
                }
            });
        });
        
        $('#back-to-summary').click(function() {
            $('.payment-form-card').remove();
            $('#order-summary').show();
        });
    }
    
    // Reset modal when closed
    $('#purchaseModal').on('hidden.bs.modal', function() {
        selectedProduct = null;
        $('#quantity').val(1);
        $('.payment-form-card').remove();
        $('#order-summary').show();
        $('#confirmPurchaseBtn').prop('disabled', false).html('<i class="bx bx-lock"></i> Proceed to Payment');
    });
});
</script>
@endpush
@endsection