@extends('index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Edit Product</h5>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Products
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Product Name Field -->
                            <div class="col-12 mb-3">
                                <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $product->name) }}" 
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
                                       value="{{ old('price', $product->price) }}" 
                                       placeholder="Enter product price"
                                       required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Current Image Display -->
                            @if($product->image)
                            <div class="col-12 mb-3">
                                <label class="form-label">Current Image</label>
                                <div class="mb-2">
                                    <img src="{{ Storage::url($product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         style="max-width: 200px; max-height: 200px; border-radius: 5px;">
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                                    <label class="form-check-label text-danger" for="remove_image">
                                        Remove this image
                                    </label>
                                </div>
                            </div>
                            @endif

                            <!-- New Image Field -->
                            <div class="col-12 mb-3">
                                <label for="image" class="form-label">Change Image (Optional)</label>
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*"
                                       onchange="previewImage(this)">
                                <small class="text-muted">Allowed formats: JPG, PNG, JPEG. Max size: 2MB. Leave empty to keep current image.</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                <!-- New Image Preview -->
                                <div id="image_preview" class="mt-3" style="display: none;">
                                    <label class="form-label">New Image Preview</label>
                                    <br>
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
                                          placeholder="Write your product description here...">{{ old('detail', $product->detail) }}</textarea>
                                @error('detail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Product Statistics Card -->
                            <div class="col-12 mb-3">
                                <div class="card bg-label-info">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <span class="avatar-initial rounded bg-info">
                                                            <i class="bx bx-calendar"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block">Created At</small>
                                                        <h6 class="mb-0">{{ $product->created_at->format('M d, Y H:i') }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <span class="avatar-initial rounded bg-info">
                                                            <i class="bx bx-edit"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block">Last Updated</small>
                                                        <h6 class="mb-0">{{ $product->updated_at->format('M d, Y H:i') }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product ID Card -->
                            <div class="col-12 mb-3">
                                <div class="card bg-label-primary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded bg-primary">
                                                    <i class="bx bx-info-circle"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Product Information</small>
                                                <h6 class="mb-0">Product ID: #{{ $product->id }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-update"></i> Update Product
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
    
    // Remove image checkbox functionality
    document.getElementById('remove_image')?.addEventListener('change', function() {
        const imageInput = document.getElementById('image');
        if(this.checked) {
            imageInput.disabled = true;
            imageInput.value = '';
            document.getElementById('image_preview').style.display = 'none';
        } else {
            imageInput.disabled = false;
        }
    });
    
    // Reset image preview on form reset
    document.querySelector('button[type="reset"]')?.addEventListener('click', function() {
        document.getElementById('image_preview').style.display = 'none';
        document.getElementById('preview_img').src = '#';
        
        // Re-enable image input if it was disabled
        const imageInput = document.getElementById('image');
        if(imageInput) {
            imageInput.disabled = false;
        }
        
        // Uncheck remove image checkbox if checked
        const removeImageCheckbox = document.getElementById('remove_image');
        if(removeImageCheckbox) {
            removeImageCheckbox.checked = false;
        }
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
    .bg-label-info {
        background-color: #e3f2fd;
    }
    .bg-label-primary {
        background-color: #e7e7ff;
    }
</style>
@endpush
@endsection