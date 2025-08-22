@extends('layouts.admin')

@section('title', 'Manage Posts')
@section('subtitle', 'Create, edit, and manage your blog posts')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Posts</h1>
            <p class="text-muted mb-0">Manage your blog posts</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>New Post
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-3 align-items-center">
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="search" placeholder="Search posts..."
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Posts Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <h6 class="mb-0">
                    Posts List
                    @if($posts->total() > 0)
                        <span class="badge bg-light text-dark ms-2">{{ $posts->total() }} total</span>
                    @endif
                </div>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per page</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                </select>
            </div>
        </div>

        @if($posts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="{{ route('admin.posts.show', $post) }}"
                                               class="text-decoration-none">
                                                {{ Str::limit($post->title, 50) }}
                                            </a>
                                        </h6>
                                        @if($post->excerpt)
                                            <small class="text-muted">{{ Str::limit($post->excerpt, 80) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                             style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $post->user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        {{ $post->category->name }}
                                    </span>
                                </td>
                                <td>
                                    @if($post->status === 'published')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Published
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock me-1"></i>Draft
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $post->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $post->created_at->format('g:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.posts.show', $post) }}"
                                           class="btn btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           title="View Post">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.posts.edit', $post) }}"
                                           class="btn btn-outline-secondary"
                                           data-bs-toggle="tooltip"
                                           title="Edit Post">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger"
                                                    data-confirm-delete="Are you sure you want to delete '{{ $post->title }}'? This action cannot be undone."
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Post">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
                <div class="card-footer bg-white border-top-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $posts->firstItem() }} to {{ $posts->lastItem() }} of {{ $posts->total() }} results
                        </div>
                        <div>
                            {{ $posts->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="card-body text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-file-earmark-text display-1 opacity-25"></i>
                    @if(request()->hasAny(['search', 'status', 'category']))
                        <h5 class="mt-3 mb-2">No posts found</h5>
                        <p class="mb-3">Try adjusting your search criteria or filters.</p>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                        </a>
                    @else
                        <h5 class="mt-3 mb-2">No posts yet</h5>
                        <p class="mb-3">Create your first blog post to get started.</p>
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Create Post
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Bulk Actions (Future Enhancement) -->
    <div class="mt-3 d-none" id="bulkActions">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-2">
                <div class="d-flex align-items-center">
                    <span class="me-3"><span id="selectedCount">0</span> items selected</span>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-success" onclick="bulkAction('publish')">
                            <i class="bi bi-check-circle me-1"></i>Publish
                        </button>
                        <button class="btn btn-outline-warning" onclick="bulkAction('draft')">
                            <i class="bi bi-clock me-1"></i>Draft
                        </button>
                        <button class="btn btn-outline-danger"
                                onclick="bulkAction('delete')"
                                data-confirm="Are you sure you want to delete the selected posts? This action cannot be undone.">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
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

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .form-select-sm {
        font-size: 0.875rem;
    }

    .badge {
        font-size: 0.75em;
    }

    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
        }

        .btn-group .btn {
            border-radius: 0.375rem !important;
            margin-bottom: 0.125rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Change per page
    function changePerPage(perPage) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', perPage);
        url.searchParams.delete('page'); // Reset to first page
        window.location.href = url.toString();
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Bulk actions (future enhancement)
    function toggleBulkActions() {
        const checkboxes = document.querySelectorAll('input[name="selected_posts[]"]:checked');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');

        if (checkboxes.length > 0) {
            bulkActions.classList.remove('d-none');
            selectedCount.textContent = checkboxes.length;
        } else {
            bulkActions.classList.add('d-none');
        }
    }

    function bulkAction(action) {
        const checkboxes = document.querySelectorAll('input[name="selected_posts[]"]:checked');
        const postIds = Array.from(checkboxes).map(cb => cb.value);

        if (postIds.length === 0) {
            showToast('Please select at least one post.', 'warning');
            return;
        }

        let message = '';
        let confirmText = '';

        switch(action) {
            case 'publish':
                message = `Publish ${postIds.length} selected post(s)?`;
                confirmText = 'Publish';
                break;
            case 'draft':
                message = `Move ${postIds.length} selected post(s) to draft?`;
                confirmText = 'Move to Draft';
                break;
            case 'delete':
                message = `Delete ${postIds.length} selected post(s)? This action cannot be undone.`;
                confirmText = 'Delete';
                break;
        }

        showConfirm(message, function() {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.posts.bulk") }}'; // You'll need to create this route

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add action
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            form.appendChild(actionInput);

            // Add post IDs
            postIds.forEach(id => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'post_ids[]';
                idInput.value = id;
                form.appendChild(idInput);
            });

            document.body.appendChild(form);
            form.submit();
        }, 'Confirm Action', confirmText, action === 'delete' ? 'btn-danger' : 'btn-primary');
    }

    // Auto-refresh page if there are flash messages (to show updated data)
    @if(session('success') || session('error'))
        setTimeout(function() {
            // Optional: You can implement auto-refresh here if needed
        }, 2000);
    @endif
</script>
@endpush
