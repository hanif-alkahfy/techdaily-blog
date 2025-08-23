@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Post Preview</h1>
        <p class="text-muted mb-0">Preview how your post will appear to readers</p>
    </div>
    <div class="d-flex gap-2">
        @if($post->status === 'published')
            <a href="/posts/{{ $post->slug }}" target="_blank" class="btn btn-success">
                <i class="bi bi-box-arrow-up-right me-2"></i>View Live
            </a>
        @endif
        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>Edit Post
        </a>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Posts
        </a>
    </div>
</div>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.posts.index') }}" class="text-decoration-none">Posts</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ Str::limit($post->title, 30) }}
        </li>
    </ol>
</nav>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Post Content -->
        <div class="card mb-4">
            <div class="card-body">
                <!-- Post Header -->
                <div class="mb-4">
                    <!-- Status Badge -->
                    <div class="mb-3">
                        @if($post->status === 'published')
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-eye me-1"></i>Published
                            </span>
                        @else
                            <span class="badge bg-warning fs-6">
                                <i class="bi bi-file-earmark me-1"></i>Draft
                            </span>
                        @endif

                        <span class="badge bg-secondary fs-6 ms-2">
                            <i class="bi bi-tag me-1"></i>{{ ucfirst($post->category->name) }}
                        </span>
                    </div>

                    <!-- Post Title -->
                    <h1 class="display-6 fw-bold mb-3">{{ $post->title }}</h1>

                    <!-- Post Meta -->
                    <div class="d-flex flex-wrap align-items-center text-muted mb-4">
                        <div class="me-4 mb-2">
                            <i class="bi bi-person me-1"></i>
                            <span>{{ $post->user->name ?? 'Unknown Author' }}</span>
                        </div>

                        <div class="me-4 mb-2">
                            <i class="bi bi-calendar3 me-1"></i>
                            <span>
                                @if($post->published_at && $post->status === 'published')
                                    {{ $post->published_at->format('M d, Y') }}
                                @else
                                    {{ $post->created_at->format('M d, Y') }}
                                @endif
                            </span>
                        </div>

                        <div class="me-4 mb-2">
                            <i class="bi bi-clock me-1"></i>
                            <span>{{ $readingTime ?? '5' }} min read</span>
                        </div>

                        @if($post->updated_at->gt($post->created_at))
                            <div class="me-4 mb-2">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                <span>Updated {{ $post->updated_at->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Post Excerpt -->
                    @if($post->excerpt)
                        <div class="lead text-muted mb-4 p-3 bg-light rounded">
                            <i class="bi bi-quote me-2"></i>{{ $post->excerpt }}
                        </div>
                    @endif
                </div>

                <!-- Featured Image -->
                @if($post->featured_image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $post->featured_image) }}"
                             alt="{{ $post->title }}"
                             class="img-fluid rounded shadow-sm w-100"
                             style="max-height: 400px; object-fit: cover;">
                    </div>
                @endif

                <!-- Post Content -->
                <div class="post-content">
                    {!! $post->content !!}
                </div>

                <!-- Post Footer -->
                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center">
                    <!-- Tags/Keywords -->
                    <div>
                        @if($post->meta_keywords)
                            <h6 class="text-muted mb-2">Tags:</h6>
                            <div>
                                @foreach(explode(',', $post->meta_keywords) as $keyword)
                                    <span class="badge bg-light text-dark me-1 mb-1">
                                        #{{ trim($keyword) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Social Share Buttons (Preview) -->
                    <div>
                        <h6 class="text-muted mb-2">Share:</h6>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                <i class="bi bi-facebook"></i>
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" disabled>
                                <i class="bi bi-twitter"></i>
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" disabled>
                                <i class="bi bi-whatsapp"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="bi bi-linkedin"></i>
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">Preview only</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Section Preview -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-chat-dots me-2"></i>Comments
                    <small class="text-muted">(Preview)</small>
                </h5>
            </div>
            <div class="card-body text-center py-5">
                <i class="bi bi-chat-dots text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3 mb-2">Comments Section</h5>
                <p class="text-muted mb-3">
                    This is where reader comments would appear when the post is published.
                </p>
                <small class="text-muted">
                    Comments: {{ $post->comments_count ?? '0' }} |
                    Status: {{ $post->status === 'published' ? 'Live' : 'Preview Mode' }}
                </small>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Post Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-tools me-2"></i>Post Actions
                </h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Post
                </a>

                @if($post->status === 'draft')
                    <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="published">
                        <input type="hidden" name="quick_action" value="true">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-send me-2"></i>Publish Now
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="draft">
                        <input type="hidden" name="quick_action" value="true">
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-file-earmark me-2"></i>Unpublish
                        </button>
                    </form>
                @endif

                <button type="button" class="btn btn-outline-info" onclick="printPost()">
                    <i class="bi bi-printer me-2"></i>Print Preview
                </button>

                <form method="POST" action="{{ route('admin.posts.destroy', $post) }}"
                      class="d-inline"
                      onsubmit="return confirmDelete('Are you sure you want to delete this post? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-trash me-2"></i>Delete Post
                    </button>
                </form>
            </div>
        </div>

        <!-- Post Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Post Information
                </h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">ID:</dt>
                    <dd class="col-sm-7">#{{ $post->id }}</dd>

                    <dt class="col-sm-5">Slug:</dt>
                    <dd class="col-sm-7">
                        <code>{{ $post->slug }}</code>
                        <button type="button" class="btn btn-sm btn-outline-secondary ms-1"
                                onclick="copyToClipboard('{{ $post->slug }}')">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </dd>

                    <dt class="col-sm-5">Category:</dt>
                    <dd class="col-sm-7">{{ ucfirst($post->category->name) }}</dd>

                    <dt class="col-sm-5">Status:</dt>
                    <dd class="col-sm-7">
                        @if($post->status === 'published')
                            <span class="text-success">
                                <i class="bi bi-eye me-1"></i>Published
                            </span>
                        @else
                            <span class="text-warning">
                                <i class="bi bi-file-earmark me-1"></i>Draft
                            </span>
                        @endif
                    </dd>

                    <dt class="col-sm-5">Created:</dt>
                    <dd class="col-sm-7">
                        {{ $post->created_at->format('M d, Y H:i') }}
                        <small class="text-muted d-block">{{ $post->created_at->diffForHumans() }}</small>
                    </dd>

                    <dt class="col-sm-5">Updated:</dt>
                    <dd class="col-sm-7">
                        {{ $post->updated_at->format('M d, Y H:i') }}
                        <small class="text-muted d-block">{{ $post->updated_at->diffForHumans() }}</small>
                    </dd>

                    @if($post->published_at)
                        <dt class="col-sm-5">Published:</dt>
                        <dd class="col-sm-7">
                            {{ $post->published_at->format('M d, Y H:i') }}
                            <small class="text-muted d-block">{{ $post->published_at->diffForHumans() }}</small>
                        </dd>
                    @endif
                </dl>
            </div>
        </div>

        <!-- SEO Preview -->
        @if($post->meta_title || $post->meta_description)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-search me-2"></i>SEO Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="border rounded p-3" style="font-family: Arial, sans-serif;">
                        <h6 class="text-primary mb-1" style="font-size: 18px;">
                            {{ $post->meta_title ?: $post->title }}
                        </h6>
                        <p class="text-success mb-1" style="font-size: 14px;">
                            {{ url('/posts/' . $post->slug) }}
                        </p>
                        <p class="text-muted mb-0" style="font-size: 13px;">
                            {{ $post->meta_description ?: Str::limit($post->excerpt ?: strip_tags($post->content), 160) }}
                        </p>
                    </div>
                    <small class="text-muted">How this post appears in search results</small>
                </div>
            </div>
        @endif

        <!-- Post Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border-end">
                            <h5 class="mb-1">{{ $post->views ?? '0' }}</h5>
                            <small class="text-muted">Views</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-end">
                            <h5 class="mb-1">{{ $post->comments_count ?? '0' }}</h5>
                            <small class="text-muted">Comments</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <h5 class="mb-1">{{ $post->shares ?? '0' }}</h5>
                        <small class="text-muted">Shares</small>
                    </div>
                </div>

                @if($post->status === 'draft')
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Statistics will be available after publishing
                        </small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-arrow-repeat me-2"></i>Related Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-lg me-2"></i>Create New Post
                    </a>

                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="duplicatePost()">
                        <i class="bi bi-files me-2"></i>Duplicate This Post
                    </button>

                    <a href="{{ route('admin.posts.index', ['category' => $post->category]) }}"
                       class="btn btn-outline-info btn-sm">
                        <i class="bi bi-list me-2"></i>View {{ ucfirst($post->category->name) }} Posts
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success feedback
        const btn = event.target.closest('button');
        const originalIcon = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check"></i>';
        btn.classList.add('text-success');

        setTimeout(() => {
            btn.innerHTML = originalIcon;
            btn.classList.remove('text-success');
        }, 1500);
    }).catch(function(err) {
        console.error('Failed to copy: ', err);
        alert('Failed to copy to clipboard');
    });
}

// Print post function
function printPost() {
    const printWindow = window.open('', '_blank');
    const postContent = document.querySelector('.post-content').innerHTML;
    const postTitle = '{{ $post->title }}';
    const postMeta = 'Published: {{ $post->created_at->format("M d, Y") }} | Author: {{ $post->user->name ?? "Unknown" }}';

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>${postTitle} - Print</title>
            <style>
                body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
                h1 { border-bottom: 2px solid #333; padding-bottom: 10px; }
                .meta { color: #666; font-size: 14px; margin-bottom: 20px; }
                img { max-width: 100%; height: auto; }
                @media print { body { margin: 0; } }
            </style>
        </head>
        <body>
            <h1>${postTitle}</h1>
            <div class="meta">${postMeta}</div>
            <div class="content">${postContent}</div>
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.print();
}

// Duplicate post function
function duplicatePost() {
    if (confirm('This will create a copy of this post as a draft. Continue?')) {
        // Create form to submit duplication request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.posts.duplicate", $post) }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        document.body.appendChild(form);
        form.submit();
    }
}

// Enhanced content styling
document.addEventListener('DOMContentLoaded', function() {
    // Add responsive classes to content images
    const contentImages = document.querySelectorAll('.post-content img');
    contentImages.forEach(img => {
        img.classList.add('img-fluid', 'rounded', 'shadow-sm', 'my-3');
    });

    // Style content tables
    const contentTables = document.querySelectorAll('.post-content table');
    contentTables.forEach(table => {
        table.classList.add('table', 'table-striped', 'table-responsive');
        const wrapper = document.createElement('div');
        wrapper.classList.add('table-responsive');
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
    });

    // Style blockquotes
    const blockquotes = document.querySelectorAll('.post-content blockquote');
    blockquotes.forEach(quote => {
        quote.classList.add('border-start', 'border-4', 'border-primary', 'ps-3', 'fst-italic', 'my-3');
    });

    // Add syntax highlighting class to code blocks
    const codeBlocks = document.querySelectorAll('.post-content pre code');
    codeBlocks.forEach(code => {
        code.parentElement.classList.add('bg-dark', 'text-light', 'p-3', 'rounded', 'my-3');
    });
});
</script>

<style>
/* Custom post content styling */
.post-content {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #333;
}

.post-content h2, .post-content h3, .post-content h4 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.post-content p {
    margin-bottom: 1.5rem;
}

.post-content ul, .post-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.post-content li {
    margin-bottom: 0.5rem;
}

.post-content a {
    color: #0d6efd;
    text-decoration: underline;
}

.post-content a:hover {
    color: #0a58ca;
}

/* Print styles */
@media print {
    .card, .btn, nav, .alert {
        display: none !important;
    }

    .post-content {
        font-size: 12pt;
        line-height: 1.5;
    }
}
</style>
@endpush
