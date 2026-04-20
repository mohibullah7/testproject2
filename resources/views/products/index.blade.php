@extends('index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">products Management</h5>
                    <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">
                        <i class="bx bx-plus"></i> Add New Item
                    </a>
                </div>
                <div class="card-body">
                    <!-- Success/Error Messages -->
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

                    <!-- products Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    {{-- <th>Status</th> --}}
                                    {{-- <th>Views</th> --}}
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $prodcut)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($prodcut->image)
                                            <img src="{{ Storage::url($prodcut->image) }}" alt="{{ $prodcut->title }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        @else
                                            <div class="avatar avatar-sm">
                                                <span class="avatar-initial rounded bg-label-secondary">
                                                    <i class="bx bx-image"></i>
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($prodcut->name, 50) }}</strong>
                                        <br>
                                        {{-- <small class="text-muted">Slug: {{ Str::limit($prodcut->slug, 30) }}</small> --}}
                                    </td>
                                    {{-- <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xs me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($prodcut->user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            {{ $prodcut->user->name }}
                                        </div>
                                    </td> --}}
                                    {{-- <td>
                                        @if($prodcut->status == 'published')
                                            <span class="badge bg-label-success">Published</span>
                                            @if($prodcut->published_at)
                                                <br>
                                                <small class="text-muted">{{ $prodcut->published_at->format('M d, Y') }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-label-warning">Draft</span>
                                        @endif
                                    </td> --}}
                                    {{-- <td>
                                        <span class="badge bg-label-info">
                                            <i class="bx bx-show"></i> {{ number_format($prodcut->views) }}
                                        </span>
                                    </td> --}}
                                    <td>{{ $prodcut->price }}</td>
                                    <td>{{ $prodcut->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('products.show', $prodcut->id) }}">
                                                    <i class="bx bx-show me-1"></i> View
                                                </a>
                                                <a class="dropdown-item" href="{{ route('products.edit', $prodcut->id) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('products.destroy', $prodcut->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this post?')">
                                                        <i class="bx bx-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="bx bx-file-blank bx-lg text-muted"></i>
                                        <h5 class="mt-3">No products found</h5>
                                        <p class="text-muted"> Add product to List</p>
                                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                                            <i class="bx bx-plus"></i> Add product
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .avatar-xs {
        width: 30px;
        height: 30px;
    }
    .avatar-initial {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .badge {
        font-weight: 500;
    }
</style>
@endpush