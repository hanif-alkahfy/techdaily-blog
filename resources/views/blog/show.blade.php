<x-blog-layout>
    @push('meta')
    <meta property="og:title" content="{{ $post->meta_title ?? $post->title }}">
    <meta property="og:description" content="{{ $post->meta_description ?? $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}">
    @if($post->featured_image)
        <meta property="og:image" content="{{ asset('storage/' . $post->featured_image) }}">
    @endif
    <meta name="description" content="{{ $post->meta_description ?? $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}">
    @if($post->meta_keywords)
        <meta name="keywords" content="{{ $post->meta_keywords }}">
    @endif
    @endpush
</x-blog-layout>
<article class="blog-post">
    <!-- Featured Image Header -->
    @if($post->featured_image)
        <div class="featured-image position-relative">
            <img src="{{ $post->featured_image_url }}"
                 alt="{{ $post->title }}"
                 class="w-100"
                 style="height: 400px; object-fit: cover;">
            <div class="position-absolute bottom-0 start-0 w-100 bg-gradient-dark p-4">
                <div class="container">
                    <h1 class="text-white mb-0">{{ $post->title }}</h1>
                </div>
            </div>
        </div>
    @endif

    <div class="container py-5">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Post Header (if no featured image) -->
                @if(!$post->featured_image)
                    <h1 class="mb-4">{{ $post->title }}</h1>
                @endif

                <!-- Post Meta -->
                <div class="d-flex flex-wrap align-items-center text-muted mb-4">
                    <div class="me-4 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px; font-size: 1rem;">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <div class="fw-medium text-dark">{{ $post->user->name }}</div>
                                <small>{{ $post->user->profile->title ?? 'Author' }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="me-4 mb-2">
                        <i class="bi bi-calendar3 me-1"></i>
                        <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                    </div>

                    <div class="me-4 mb-2">
                        <i class="bi bi-clock me-1"></i>
                        <span>{{ $readingTime ?? '5' }} min read</span>
                    </div>

                    <div class="me-4 mb-2">
                        <i class="bi bi-eye me-1"></i>
                        <span>{{ number_format($post->views ?? 0) }} views</span>
                    </div>

                    <div class="mb-2">
                        <a href="{{ route('blog.index', ['category' => $post->category_id]) }}"
                           class="text-decoration-none">
                            <span class="badge bg-primary">{{ $post->category->name }}</span>
                        </a>
                    </div>
                </div>

                <!-- Post Excerpt -->
                @if($post->excerpt)
                    <div class="lead text-muted mb-4 p-3 bg-light rounded">
                        {{ $post->excerpt }}
                    </div>
                @endif

                <!-- Post Content -->
                <div class="post-content mb-5">
                    {!! $post->content !!}
                </div>

                <!-- Tags -->
                @if($post->meta_keywords)
                    <div class="mb-5">
                        <h5>Tags:</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(explode(',', $post->meta_keywords) as $tag)
                                <a href="{{ route('blog.index', ['tag' => trim($tag)]) }}"
                                   class="text-decoration-none">
                                    <span class="badge bg-light text-dark">
                                        #{{ trim($tag) }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Share Buttons -->
                <div class="mb-5">
                    <h5>Share this post:</h5>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           target="_blank"
                           class="btn btn-outline-primary">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                           target="_blank"
                           class="btn btn-outline-info">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . request()->url()) }}"
                           target="_blank"
                           class="btn btn-outline-success">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($post->title) }}"
                           target="_blank"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <button type="button"
                                class="btn btn-outline-dark"
                                onclick="copyToClipboard('{{ request()->url() }}')">
                            <i class="bi bi-link-45deg"></i>
                        </button>
                    </div>
                </div>

                <!-- Author Bio -->
                <div class="card bg-light border-0 mb-5">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 80px; font-size: 2rem;">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-1">About {{ $post->user->name }}</h5>
                                <p class="text-muted mb-2">{{ $post->user->profile->title ?? 'Author' }}</p>
                                <p class="mb-2">{{ $post->user->profile->bio ?? 'No bio available.' }}</p>
                                @if($post->user->profile->social_links ?? null)
                                    <div class="d-flex gap-2">
                                        @foreach($post->user->profile->social_links as $platform => $url)
                                            <a href="{{ $url }}" target="_blank" class="text-muted">
                                                <i class="bi bi-{{ $platform }}"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                    <div class="mb-5">
                        <h4 class="mb-4">Related Posts</h4>
                        <div class="row g-4">
                            @foreach($relatedPosts as $relatedPost)
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                                        <div class="position-relative">
                                            @if($relatedPost->featured_image)
                                                <a href="{{ route('blog.show', $relatedPost->slug) }}" class="hover-overlay">
                                                    <img src="{{ asset('storage/' . $relatedPost->featured_image) }}"
                                                         alt="{{ $relatedPost->title }}"
                                                         class="card-img-top"
                                                         style="height: 200px; object-fit: cover;">
                                                </a>
                                            @else
                                                <div class="bg-light" style="height: 200px;">
                                                    <div class="d-flex align-items-center justify-content-center h-100">
                                                        <i class="bi bi-image text-muted display-4"></i>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($relatedPost->category)
                                                <div class="position-absolute top-0 start-0 m-3">
                                                    <span class="badge bg-primary">{{ $relatedPost->category->name }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <a href="{{ route('blog.show', $relatedPost->slug) }}"
                                                   class="text-decoration-none text-dark stretched-link">
                                                    {{ $relatedPost->title }}
                                                </a>
                                            </h5>
                                            <p class="card-text text-muted">
                                                {{ Str::limit($relatedPost->excerpt ?? strip_tags($relatedPost->content), 100) }}
                                            </p>
                                            <div class="d-flex align-items-center mt-3">
                                                <div class="avatar me-2">
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                        {{ strtoupper(substr($relatedPost->user->name ?? 'A', 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-medium">{{ $relatedPost->user->name ?? 'Anonymous' }}</div>
                                                    <div class="d-flex align-items-center small text-muted">
                                                        <span>{{ $relatedPost->published_at->format('M d, Y') }}</span>
                                                        <span class="mx-2">•</span>
                                                        <span><i class="bi bi-eye me-1"></i>{{ number_format($relatedPost->views ?? 0) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Comments Section -->
                <div class="mb-5">
                    <h4 class="mb-4">Comments</h4>
                    @auth
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="{{ route('blog.comments.store', $post) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Leave a comment</label>
                                        <textarea class="form-control @error('content') is-invalid @enderror"
                                                  id="comment"
                                                  name="content"
                                                  rows="3"
                                                  required></textarea>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Post Comment</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Please <a href="{{ route('login') }}">login</a> to leave a comment.
                        </div>
                    @endauth

                    <!-- Comments List -->
                    @forelse($post->comments ?? [] as $comment)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px; font-size: 1rem;">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $comment->user->name }}</h6>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            @if(auth()->id() === $comment->user_id)
                                                <button type="button"
                                                        class="btn btn-link text-danger p-0"
                                                        data-delete-url="{{ route('blog.comments.destroy', $comment) }}"
                                                        onclick="showDeleteConfirmation(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <form id="deleteForm-{{ $comment->id }}"
                                                      action="{{ route('blog.comments.destroy', $comment) }}"
                                                      method="POST"
                                                      class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </div>
                                        <p class="mb-0 mt-2">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-chat-dots text-muted display-4"></i>
                            <p class="mt-3">No comments yet. Be the first to comment!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- About the Author -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">About the Author</h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 64px; height: 64px; font-size: 1.5rem;">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $post->user->name }}</h6>
                                <p class="mb-0 text-muted">{{ $post->user->profile->title ?? 'Author' }}</p>
                            </div>
                        </div>
                        <p class="mb-0">{{ $post->user->profile->bio ?? 'No bio available.' }}</p>
                    </div>
                </div>

                <!-- Table of Contents -->
                @if($tableOfContents ?? null)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Table of Contents</h5>
                            <nav id="toc" class="nav flex-column">
                                @foreach($tableOfContents as $heading)
                                    <a class="nav-link ps-{{ $heading['level'] }}"
                                       href="#{{ $heading['id'] }}">
                                        {{ $heading['text'] }}
                                    </a>
                                @endforeach
                            </nav>
                        </div>
                    </div>
                @endif

                <!-- Popular Posts -->
                @if($popularPosts ?? null)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Popular Posts</h5>
                            <div class="list-group list-group-flush">
                                @foreach($popularPosts as $popularPost)
                                    <a href="{{ route('blog.show', $popularPost->slug) }}"
                                       class="list-group-item list-group-item-action {{ $popularPost->id === $post->id ? 'active' : '' }}">
                                        <div class="d-flex w-100 justify-content-between mb-1">
                                            <h6 class="mb-1">{{ Str::limit($popularPost->title, 50) }}</h6>
                                            <small>
                                                <i class="bi bi-eye"></i> {{ $popularPost->views }}
                                            </small>
                                        </div>
                                        <small class="{{ $popularPost->id === $post->id ? 'text-light' : 'text-muted' }}">
                                            {{ $popularPost->published_at->format('M d, Y') }}
                                        </small>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Newsletter Signup -->
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-envelope-paper display-4 mb-3"></i>
                        <h5 class="card-title">Subscribe to Our Newsletter</h5>
                        <p>Get the latest posts delivered right to your inbox.</p>
                        <form action="{{ route('newsletter.subscribe') }}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="email"
                                       class="form-control"
                                       name="email"
                                       placeholder="Your email address"
                                       required>
                                <button class="btn btn-light" type="submit">Subscribe</button>
                            </div>
                            <small class="text-white-50">
                                We'll never share your email. Unsubscribe at any time.
                            </small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
    @push('styles')
<style>
/* Post Content Styling */
.post-content {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #2c3e50;
}

.post-content h2 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.post-content h3 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.post-content p {
    margin-bottom: 1.5rem;
}

.post-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 1.5rem 0;
}

.post-content blockquote {
    border-left: 4px solid #3498db;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #7f8c8d;
}

.post-content code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
}

.post-content pre {
    background-color: #2c3e50;
    color: #fff;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.post-content ul, .post-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.post-content li {
    margin-bottom: 0.5rem;
}

.post-content a {
    color: #3498db;
    text-decoration: none;
}

.post-content a:hover {
    text-decoration: underline;
}

/* Table of Contents */
#toc .nav-link {
    padding: 0.25rem 1rem;
    color: #6c757d;
    border-left: 2px solid transparent;
}

#toc .nav-link:hover {
    color: #3498db;
    background-color: #f8f9fa;
}

#toc .nav-link.active {
    color: #3498db;
    border-left-color: #3498db;
}

/* Featured Image Header */
.bg-gradient-dark {
    background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.8) 100%);
}

/* Comments */
.comment-avatar {
    width: 40px;
    height: 40px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .post-content {
        font-size: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Copy URL to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show success feedback
        const copyBtn = document.querySelector('.btn-outline-dark');
        const originalHtml = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="bi bi-check"></i>';

        setTimeout(() => {
            copyBtn.innerHTML = originalHtml;
        }, 2000);
    });
}

// Highlight active section in Table of Contents
document.addEventListener('DOMContentLoaded', function() {
    // Get all headings
    const headings = document.querySelectorAll('.post-content h2, .post-content h3');
    const tocLinks = document.querySelectorAll('#toc .nav-link');

    if (headings.length && tocLinks.length) {
        const observerCallback = (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    tocLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + entry.target.id) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        };

        const observer = new IntersectionObserver(observerCallback, {
            rootMargin: '-100px 0px -66%'
        });

        headings.forEach(heading => observer.observe(heading));
    }

    // Smooth scroll to anchor
    document.querySelectorAll('#toc .nav-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});

// Delete confirmation functionality
let deleteForm;

function showDeleteConfirmation(button) {
    const deleteUrl = button.getAttribute('data-delete-url');
    deleteForm = document.querySelector(`form[action="${deleteUrl}"]`);
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    if (confirmDeleteButton) {
        confirmDeleteButton.addEventListener('click', function() {
            if (deleteForm) {
                deleteForm.submit();
            }
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
            if (modal) {
                modal.hide();
            }
        });
    }
});
</script>
@endpush

<x-delete-confirmation-modal title="Delete Comment">
    <p class="mb-0">Are you sure you want to delete this comment? This action cannot be undone.</p>
</x-delete-confirmation-modal>
