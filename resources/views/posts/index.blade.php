@extends('index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Posts Management</h5>
                    <a href="{{ route('posts.create') }}" class="btn btn-sm btn-primary">
                        <i class="bx bx-plus"></i> Create New Post
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

                    <div class="mb-3">
    <select id="statusFilter" class="form-select w-auto">
        <option value="">All Status</option>
        <option value="published">Published</option>
        <option value="draft">Draft</option>
    </select>
</div>

                    <!-- Posts Table -->
                    <div class="table-responsive">
                       <table class="table table-hover" id="posts-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>Title</th>
            <th>Author</th>
            <th>Status</th>
            <th>Views</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>
                    </div>

                    <!-- Pagination -->
                    {{-- <div class="d-flex justify-content-center mt-4">
                        {{ $posts->links() }}
                    </div> --}}
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

@push('scripts')
    <script>
$(document).ready(function() {

    var table = $('#posts-table').DataTable({
        processing: true,
        serverSide: true,

        ajax: {
            url: "{{ route('posts.index') }}",
            data: function (d) {
                d.status = $('#statusFilter').val(); // 🔍 filter
            }
        },

        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'image', name: 'image', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'author', name: 'user.name' },
            { data: 'status', name: 'status' },
            { data: 'views', name: 'views' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', orderable: false, searchable: false },
        ]
    });

    // 🔥 Reload on filter change
    $('#statusFilter').change(function() {
        table.draw();
    });

});
</script>
@endpush