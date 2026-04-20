@extends('index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Post Details</h5>
                    <div>
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-primary">
                            <i class="bx bx-edit"></i> Edit Post
                        </a>
                        <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bx bx-arrow-back"></i> Back to Posts
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Post Header -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h2 class="mb-2">{{ $post->title }}</h2>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <span>By <strong>{{ $post->user->name }}</strong></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-calendar me-1"></i>
                                        <span>Created: {{ $post->created_at->format('F d, Y \a\t H:i') }}</span>
                                    </div>
                                    @if($post->status == 'published' && $post->published_at)
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-time me-1"></i>
                                        <span>Published: {{ $post->published_at->format('F d, Y \a\t H:i') }}</span>
                                    </div>
                                    @endif
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-show me-1"></i>
                                        <span>{{ number_format($post->views) }} views</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                @if($post->status == 'published')
                                    <span class="badge bg-label-success">Published</span>
                                @else
                                    <span class="badge bg-label-warning">Draft</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Slug Information -->
                        <div class="alert alert-info">
                            <i class="bx bx-link-alt me-2"></i>
                            <strong>Permalink:</strong> 
                            <code>{{ url('/posts/' . $post->slug) }}</code>
                            <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ url('/posts/' . $post->slug) }}')">
                                <i class="bx bx-copy"></i> Copy
                            </button>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    @if($post->image)
                    <div class="mb-4">
                        <label class="form-label text-muted">Featured Image</label>
                        <div class="text-center bg-light p-3 rounded">
                            <img src="{{ Storage::url($post->image) }}" 
                                 alt="{{ $post->title }}" 
                                 class="img-fluid" 
                                 style="max-height: 400px; border-radius: 8px;">
                        </div>
                    </div>
                    @endif

                    <!-- Post Content -->
                    <div class="mb-4">
                        <label class="form-label text-muted">Post Content</label>
                        <div class="border rounded p-4 bg-white" style="min-height: 300px;">
                            {!! nl2br(e($post->body)) !!}
                        </div>
                    </div>

                    <!-- Post Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-label-primary">
                                <div class="card-body text-center">
                                    <div class="avatar flex-shrink-0 mb-2">
                                        <span class="avatar-initial rounded bg-primary">
                                            <i class="bx bx-show"></i>
                                        </span>
                                    </div>
                                    <h5 class="mb-1">{{ number_format($post->views) }}</h5>
                                    <small class="text-muted">Total Views</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-label-success">
                                <div class="card-body text-center">
                                    <div class="avatar flex-shrink-0 mb-2">
                                        <span class="avatar-initial rounded bg-success">
                                            <i class="bx bx-calendar"></i>
                                        </span>
                                    </div>
                                    <h5 class="mb-1">{{ $post->created_at->format('M d, Y') }}</h5>
                                    <small class="text-muted">Created Date</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-label-info">
                                <div class="card-body text-center">
                                    <div class="avatar flex-shrink-0 mb-2">
                                        <span class="avatar-initial rounded bg-info">
                                            <i class="bx bx-edit"></i>
                                        </span>
                                    </div>
                                    <h5 class="mb-1">{{ $post->updated_at->format('M d, Y') }}</h5>
                                    <small class="text-muted">Last Updated</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-label-warning">
                                <div class="card-body text-center">
                                    <div class="avatar flex-shrink-0 mb-2">
                                        <span class="avatar-initial rounded bg-warning">
                                            <i class="bx bx-file"></i>
                                        </span>
                                    </div>
                                    <h5 class="mb-1">{{ Str::words($post->body, 10) }}...</h5>
                                    <small class="text-muted">Content Preview</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Post Meta Information -->
                    <div class="mb-4">
                        <h6 class="mb-3">Additional Information</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px;">Post ID</th>
                                        <td>{{ $post->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Slug</th>
                                        <td><code>{{ $post->slug }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Author ID</th>
                                        <td>{{ $post->user_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($post->status == 'published')
                                                <span class="badge bg-label-success">Published</span>
                                            @else
                                                <span class="badge bg-label-warning">Draft</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($post->published_at)
                                    <tr>
                                        <th>Published At</th>
                                        <td>{{ $post->published_at->format('F d, Y H:i:s') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $post->created_at->format('F d, Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>{{ $post->updated_at->format('F d, Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Word Count</th>
                                        <td>{{ str_word_count($post->body) }} words</td>
                                    </tr>
                                    <tr>
                                        <th>Character Count</th>
                                        <td>{{ strlen($post->body) }} characters</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">
                                <i class="bx bx-edit"></i> Edit Post
                            </a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">
                                    <i class="bx bx-trash"></i> Delete Post
                                </button>
                            </form>
                        </div>
                        <div>
                            <button onclick="window.print()" class="btn btn-outline-secondary">
                                <i class="bx bx-printer"></i> Print
                            </button>
                            <button onclick="copyToClipboard('{{ url('/posts/' . $post->slug) }}')" class="btn btn-outline-primary">
                                <i class="bx bx-copy"></i> Copy Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Copy to clipboard function
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success toast or alert
            toastr.success('Link copied to clipboard!');
        }, function(err) {
            // Fallback for older browsers
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            alert('Link copied to clipboard!');
        });
    }
    
    // Add print styles
    window.onbeforeprint = function() {
        document.body.classList.add('printing');
    };
    
    window.onafterprint = function() {
        document.body.classList.remove('printing');
    };
</script>
@endpush

@push('styles')
<style>
    @media print {
        .card-header,
        .btn,
        .alert-info,
        .dropdown,
        .action-buttons {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .bg-light {
            background: none !important;
        }
        body.printing {
            padding: 0 !important;
            margin: 0 !important;
        }
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
    
    .table-bordered {
        border: 1px solid #d9dee3;
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #d9dee3;
        padding: 0.75rem;
    }
    
    .table-bordered th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    code {
        background-color: #f5f5f9;
        padding: 0.2rem 0.4rem;
        border-radius: 3px;
        font-size: 0.875rem;
    }
    
    .border {
        border: 1px solid #d9dee3 !important;
    }
    
    .bg-white {
        background-color: #fff;
    }
    
    .img-fluid {
        max-width: 100%;
        height: auto;
    }
</style>
@endpush
@endsection