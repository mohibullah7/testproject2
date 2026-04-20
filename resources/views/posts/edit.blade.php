@extends('index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Edit Post</h5>
                    <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Posts
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Title Field -->
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">Post Title <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $post->title) }}" 
                                       placeholder="Enter post title"
                                       required>
                                <small class="text-muted">This will generate a unique URL for your post</small>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Slug Preview (Auto-generated) -->
                            <div class="col-12 mb-3">
                                <label class="form-label">Current Slug</label>
                                <div class="form-control bg-light" id="slug_preview">{{ $post->slug }}</div>
                                <small class="text-muted">Slug will update automatically if you change the title</small>
                            </div>

                            <!-- Current Image Display -->
                            @if($post->image)
                            <div class="col-12 mb-3">
                                <label class="form-label">Current Image</label>
                                <div class="mb-2">
                                    <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" style="max-width: 200px; max-height: 200px; border-radius: 5px;">
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
                                <small class="text-muted">Allowed formats: JPG, PNG, GIF. Max size: 2MB. Leave empty to keep current image.</small>
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

                            <!-- Post Body Field -->
                            <div class="col-12 mb-3">
                                <label for="body" class="form-label">Post Content <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('body') is-invalid @enderror" 
                                          id="body" 
                                          name="body" 
                                          rows="15" 
                                          placeholder="Write your post content here...">{{ old('body', $post->body) }}</textarea>
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
                                    <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3" id="publish_date_field" style="display: {{ old('status', $post->status) == 'published' ? 'block' : 'none' }};">
                                <label for="published_at" class="form-label">Publish Date</label>
                                <input type="datetime-local" 
                                       class="form-control @error('published_at') is-invalid @enderror" 
                                       id="published_at" 
                                       name="published_at" 
                                       value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
                                <small class="text-muted">Set when the post should be published</small>
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Post Statistics Card -->
                            <div class="col-12 mb-3">
                                <div class="card bg-label-info">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <span class="avatar-initial rounded bg-info">
                                                            <i class="bx bx-show"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block">Total Views</small>
                                                        <h6 class="mb-0">{{ number_format($post->views) }} views</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <span class="avatar-initial rounded bg-info">
                                                            <i class="bx bx-calendar"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block">Created At</small>
                                                        <h6 class="mb-0">{{ $post->created_at->format('M d, Y H:i') }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <span class="avatar-initial rounded bg-info">
                                                            <i class="bx bx-edit"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block">Last Updated</small>
                                                        <h6 class="mb-0">{{ $post->updated_at->format('M d, Y H:i') }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                                <small class="text-muted d-block">Pro Tip</small>
                                                <h6 class="mb-0">Update your post regularly to keep content fresh and improve SEO ranking!</h6>
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
                                    <i class="bx bx-update"></i> Update Post
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
        
        if(slug) {
            document.getElementById('slug_preview').innerHTML = slug;
            document.getElementById('slug_preview').classList.add('text-primary');
        } else {
            document.getElementById('slug_preview').innerHTML = '{{ $post->slug }}';
            document.getElementById('slug_preview').classList.remove('text-primary');
        }
    });
    
    // Show/hide publish date field based on status
    document.getElementById('status').addEventListener('change', function() {
        const publishDateField = document.getElementById('publish_date_field');
        if(this.value === 'published') {
            publishDateField.style.display = 'block';
            // If no publish date set, set to current datetime
            const publishDateInput = document.getElementById('published_at');
            if(!publishDateInput.value) {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                publishDateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
            }
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
        
        // Reset slug preview to original
        document.getElementById('slug_preview').innerHTML = '{{ $post->slug }}';
        document.getElementById('slug_preview').classList.remove('text-primary');
    });
    
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
</script>
@endpush

@push('styles')
<style>
    #slug_preview {
        font-family: monospace;
        font-size: 14px;
        transition: all 0.3s ease;
    }
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
</style>
@endpush
@endsection