<x-blog-layout>
    @push('styles')
    <style>
        /* Card hover effects */
        .hover-shadow {
            transition: all 0.3s ease;
        }
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        /* Image overlay on hover */
        .hover-overlay {
            display: block;
            position: relative;
            overflow: hidden;
        }
        .hover-overlay::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .hover-overlay:hover::after {
            opacity: 1;
        }

        /* Card title line clamp */
        .card-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 3rem;
        }

        /* Card excerpt line clamp */
        .card-text {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 4.5rem;
        }

        /* Avatar */
        .avatar {
            flex-shrink: 0;
        }

        /* Category badge position */
        .position-absolute.top-0.start-0 {
            z-index: 2;
        }

        /* Featured post section */
        .featured-post {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
        }
        .featured-post img {
            transition: transform 0.3s ease;
        }
        .featured-post:hover img {
            transform: scale(1.05);
        }
    </style>
    @endpush

<div class="container py-5">
    <!-- Featured Post -->
    @if($featuredPost ?? null)
    <div class="mb-5">
        <div class="featured-post position-relative">
            <div class="card border-0 shadow overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-8">
                        @if($featuredPost->featured_image)
                            <img src="{{ asset('storage/' . $featuredPost->featured_image) }}"
                                 alt="{{ $featuredPost->title }}"
                                 class="img-fluid w-100 h-100"
                                 style="object-fit: cover; max-height: 500px;">
                        @else
                            <div class="bg-light h-100 d-flex align-items-center justify-content-center" style="min-height: 500px;">
                                <i class="bi bi-image display-1 text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <div class="card-body d-flex flex-column h-100 p-4">
                            <div class="mb-4">
                                <span class="badge bg-danger">Featured</span>
                                @if($featuredPost->category)
                                    <span class="badge bg-primary ms-2">{{ $featuredPost->category->name }}</span>
                                @endif
                            </div>
                            <h2 class="card-title h3 mb-3">
                                <a href="{{ route('blog.show', $featuredPost->slug) }}"
                                   class="text-decoration-none text-dark stretched-link">
                                    {{ $featuredPost->title }}
                                </a>
                            </h2>
                            <p class="card-text text-muted flex-grow-1">
                                {{ Str::limit($featuredPost->excerpt ?? strip_tags($featuredPost->content), 200) }}
                            </p>
                            <div class="mt-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 48px; height: 48px; font-size: 1.2rem;">
                                            {{ strtoupper(substr($featuredPost->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-medium fs-5">{{ $featuredPost->user->name ?? 'Anonymous' }}</div>
                                        <div class="d-flex align-items-center text-muted small">
                                            <span>{{ $featuredPost->published_at ? $featuredPost->published_at->format('M d, Y') : 'Draft' }}</span>
                                            <span class="mx-2">•</span>
                                            <span><i class="bi bi-eye me-1"></i>{{ number_format($featuredPost->views ?? 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('blog.show', $featuredPost->slug) }}"
                                   class="btn btn-primary w-100">
                                   Read Full Article <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Blog Posts List -->
        <div class="col-lg-8">
            <!-- Search and Filters -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('blog.index') }}" method="GET" class="row g-3">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="search"
                                       name="search"
                                       class="form-control border-start-0"
                                       placeholder="Search articles..."
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                        ({{ $category->posts_count ?? 0 }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel me-2"></i>Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Section Title -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Latest Articles</h4>
                    <p class="text-muted mb-0">
                        @if(request('search'))
                            Search results for "{{ request('search') }}"
                        @elseif(request('category'))
                            Posts in {{ $categories->firstWhere('id', request('category'))?->name }}
                        @else
                            Discover our latest stories and insights
                        @endif
                    </p>
                </div>
                <div>
                    {{ $posts->total() }}
                    {{ Str::plural('article', $posts->total()) }}
                </div>
            </div>

            <!-- Posts Grid -->
            <div class="row g-4">
                @forelse($posts as $post)
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm hover-shadow">
                            <div class="position-relative">
                                @if($post->featured_image)
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover-overlay">
                                        <img src="{{ asset('storage/' . $post->featured_image) }}"
                                             alt="{{ $post->title }}"
                                             class="card-img-top"
                                             style="height: 200px; object-fit: cover;">
                                    </a>
                                @else
                                    <div class="bg-light" style="height: 200px;">
                                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                            <i class="bi bi-image fs-1"></i>
                                        </div>
                                    </div>
                                @endif
                                @if($post->category)
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="badge bg-primary">{{ $post->category->name }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <a href="{{ route('blog.show', $post->slug) }}"
                                       class="text-decoration-none text-dark stretched-link">
                                        {{ $post->title }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted mb-3">
                                    {{ Str::limit($post->excerpt ?? strip_tags($post->content), 120) }}
                                </p>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($post->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-medium">{{ $post->user->name ?? 'Anonymous' }}</div>
                                        <div class="d-flex align-items-center small text-muted">
                                            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</span>
                                            <span class="mx-2">•</span>
                                            <span><i class="bi bi-eye me-1"></i>{{ number_format($post->views ?? 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-text display-1 text-muted opacity-50"></i>
                            <h4 class="mt-3">No Posts Found</h4>
                            <p class="text-muted">
                                @if(request('category'))
                                    There are no posts in this category yet.
                                @else
                                    Check back later for new content.
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
                <div class="mt-4">
                    {{ $posts->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Popular Posts -->
            @if($popularPosts ?? null)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-graph-up me-2 text-primary"></i>
                        Trending Articles
                    </h5>
                    <div class="vstack gap-4">
                        @foreach($popularPosts as $post)
                            <div class="d-flex gap-3">
                                @if($post->featured_image)
                                    <a href="{{ route('blog.show', $post->slug) }}" class="flex-shrink-0">
                                        <img src="{{ asset('storage/' . $post->featured_image) }}"
                                             alt="{{ $post->title }}"
                                             class="rounded"
                                             width="80"
                                             height="80"
                                             style="object-fit: cover;">
                                    </a>
                                @endif
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('blog.show', $post->slug) }}"
                                           class="text-decoration-none text-dark stretched-link">
                                            {{ Str::limit($post->title, 60) }}
                                        </a>
                                    </h6>
                                    <div class="d-flex align-items-center small text-muted">
                                        <span>{{ $post->published_at->format('M d, Y') }}</span>
                                        <span class="mx-2">•</span>
                                        <span>
                                            <i class="bi bi-eye me-1"></i>
                                            {{ number_format($post->views ?? 0) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Categories -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-folder me-2 text-primary"></i>
                        Categories
                    </h5>
                    <div class="d-grid gap-2">
                        @foreach($categories as $category)
                            <a href="{{ route('blog.index', ['category' => $category->id]) }}"
                               class="btn btn-outline-secondary {{ request('category') == $category->id ? 'active' : '' }}"
                               title="{{ $category->description }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>{{ $category->name }}</span>
                                    <span class="badge bg-primary ms-2">{{ $category->posts_count ?? 0 }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tags Cloud -->
            @if($tags ?? null)
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-hash me-2 text-primary"></i>
                        Popular Tags
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($tags as $tag => $count)
                            <a href="{{ route('blog.index', ['tag' => $tag]) }}"
                               class="text-decoration-none">
                                <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                    #{{ $tag }}
                                    <span class="text-muted ms-1">{{ $count }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
</x-blog-layout>
