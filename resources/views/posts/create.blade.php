@extends('index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Create New Post</h5>
                    <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Posts
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Title Field -->
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">Post Title <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       placeholder="Enter post title"
                                       required>
                                <small class="text-muted">This will generate a unique URL for your post</small>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Slug Preview (Auto-generated) -->
                            <div class="col-12 mb-3">
                                <label class="form-label">Slug Preview</label>
                                <div class="form-control bg-light" id="slug_preview"></div>
                                <small class="text-muted">Slug is automatically generated from the title</small>
                            </div>

                            <!-- Featured Image Field -->
                            <div class="col-12 mb-3">
                                <label for="image" class="form-label">Featured Image</label>
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*"
                                       onchange="previewImage(this)">
                                <small class="text-muted">Allowed formats: JPG, PNG, GIF. Max size: 2MB</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                <!-- Image Preview -->
                                <div id="image_preview" class="mt-3" style="display: none;">
                                    <img id="preview_img" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 5px;">
                                </div>
                            </div>

                            <!-- Post Body Field -->
                            <div class="col-12 mb-3">
                                <label for="body" class="form-label">Post Content <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('body') is-invalid @enderror" 
                                          id="body" 
                                          name="body" 
                                          rows="10" 
                                          placeholder="Write your post content here...">{{ old('body') }}</textarea>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status and Publish Date -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3" id="publish_date_field" style="display: {{ old('status') == 'published' ? 'block' : 'none' }};">
                                <label for="published_at" class="form-label">Publish Date</label>
                                <input type="datetime-local" 
                                       class="form-control @error('published_at') is-invalid @enderror" 
                                       id="published_at" 
                                       name="published_at" 
                                       value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                                <small class="text-muted">Leave empty to publish immediately</small>
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Writing Tips Card -->
                            <div class="col-12 mb-3">
                                <div class="card bg-label-primary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded bg-primary">
                                                    <i class="bx bx-bulb"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Writing Tips</small>
                                                <h6 class="mb-0">Use clear headings, short paragraphs, and include images to make your post engaging!</h6>
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
                                    <i class="bx bx-save"></i> Publish Post
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bx bx-reset"></i> Reset
                                </button>
                                <a href="{{ route('posts.index') }}" class="btn btn-outline-danger">
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
    // Generate slug from title
    document.getElementById('title').addEventListener('keyup', function() {
        let title = this.value;
        let slug = title.toLowerCase()
            .replace(/[^\w\s-]/g, '')  // Remove special characters
            .replace(/\s+/g, '-')       // Replace spaces with hyphens
            .replace(/-+/g, '-')        // Replace multiple hyphens with single
            .trim();                    // Trim hyphens from ends
        
        document.getElementById('slug_preview').innerHTML = slug || 'slug-will-appear-here';
    });
    
    // Trigger slug generation on page load if title exists
    if(document.getElementById('title').value) {
        document.getElementById('title').dispatchEvent(new Event('keyup'));
    }
    
    // Show/hide publish date field based on status
    document.getElementById('status').addEventListener('change', function() {
        const publishDateField = document.getElementById('publish_date_field');
        if(this.value === 'published') {
            publishDateField.style.display = 'block';
        } else {
            publishDateField.style.display = 'none';
        }
    });
    
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
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        document.getElementById('image_preview').style.display = 'none';
        document.getElementById('preview_img').src = '#';
        document.getElementById('slug_preview').innerHTML = '';
    });
</script>
@endpush

@push('styles')
<style>
    #slug_preview {
        font-family: monospace;
        font-size: 14px;
        color: #696cff;
    }
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