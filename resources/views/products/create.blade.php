@extends('index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Create New Product</h5>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Products
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Product Name Field -->
                            <div class="col-12 mb-3">
                                <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Enter product name"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Product Price Field -->
                            <div class="col-12 mb-3">
                                <label for="price" class="form-label">Product Price <span class="text-danger">*</span></label>
                                <input type="number" 
                                       step="0.01"
                                       class="form-control @error('price') is-invalid @enderror" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price') }}" 
                                       placeholder="Enter product price"
                                       required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Product Image Field -->
                            <div class="col-12 mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*"
                                       onchange="previewImage(this)">
                                <small class="text-muted">Allowed formats: JPG, PNG, JPEG. Max size: 2MB</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                <!-- Image Preview -->
                                <div id="image_preview" class="mt-3" style="display: none;">
                                    <img id="preview_img" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 5px;">
                                </div>
                            </div>

                            <!-- Product Details Field -->
                            <div class="col-12 mb-3">
                                <label for="detail" class="form-label">Product Details</label>
                                <textarea class="form-control @error('detail') is-invalid @enderror" 
                                          id="detail" 
                                          name="detail" 
                                          rows="10" 
                                          placeholder="Write your product description here...">{{ old('detail') }}</textarea>
                                @error('detail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> Save Product
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bx bx-reset"></i> Reset
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-danger">
                                    <i class="bx bx-x"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview function
    function previewImage(input) {
        const preview = document.getElementById('image_preview');
        const previewImg = document.getElementById('preview_img');
        
        if(input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }
    
    // Reset image preview on form reset
    document.querySelector('button[type="reset"]')?.addEventListener('click', function() {
        document.getElementById('image_preview').style.display = 'none';
        document.getElementById('preview_img').src = '#';
    });
</script>
@endpush

@push('styles')
<style>
    textarea {
        font-family: monospace;
        line-height: 1.6;
    }
    .form-control:focus, .form-select:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.1);
    }
</style>
@endpush
@endsection