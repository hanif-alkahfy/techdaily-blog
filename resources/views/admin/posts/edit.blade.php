@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Edit Post</h1>
        <p class="text-muted mb-0">Update your blog post content and settings</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-outline-info">
            <i class="bi bi-eye me-2"></i>Preview
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
            Edit: {{ Str::limit($post->title, 30) }}
        </li>
    </ol>
</nav>

<!-- Post Status Banner -->
<div class="alert alert-{{ $post->status === 'published' ? 'success' : 'warning' }} alert-dismissible fade show mb-4" role="alert">
    <div class="d-flex align-items-center">
        <div class="flex-grow-1">
            @if($post->status === 'published')
                <i class="bi bi-eye me-2"></i>
                <strong>Published Post</strong> - This post is currently live and visible to readers.
                @if($post->published_at)
                    <small class="text-muted">Published on {{ $post->published_at->format('M d, Y \a\t H:i') }}</small>
                @endif
            @else
                <i class="bi bi-file-earmark me-2"></i>
                <strong>Draft Post</strong> - This post is not yet published and only visible to you.
            @endif
        </div>
        <div>
            @if($post->status === 'published')
                <a href="/posts/{{ $post->slug }}" target="_blank" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-box-arrow-up-right me-1"></i>View Live
                </a>
            @endif
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<form method="POST" action="{{ route('admin.posts.update', $post) }}" id="editPostForm" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Post Content Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text me-2"></i>Post Content
                    </h5>
                    <small class="text-muted">
                        Last updated: {{ $post->updated_at->format('M d, Y \a\t H:i') }}
                    </small>
                </div>
                <div class="card-body">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label required">
                            Post Title <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title', $post->title) }}"
                               placeholder="Enter your post title..."
                               maxlength="255"
                               required>

                        <!-- Live character counter -->
                        <div class="form-text d-flex justify-content-between">
                            <span>A compelling title helps attract readers</span>
                            <span id="titleCounter" class="text-muted">{{ strlen(old('title', $post->title)) }}/255</span>
                        </div>

                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-3">
                        <label for="slug" class="form-label">URL Slug</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ url('/') }}/posts/</span>
                            <input type="text"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   id="slug"
                                   name="slug"
                                   value="{{ old('slug', $post->slug) }}"
                                   data-original="{{ $post->slug }}"
                                   placeholder="url-slug">
                        </div>
                        <div class="form-text">
                            @if($post->status === 'published')
                                <span class="text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Changing the slug will break existing links to this post
                                </span>
                            @else
                                URL-friendly version of your title
                            @endif
                        </div>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Excerpt -->
                    <div class="mb-3">
                        <label for="excerpt" class="form-label">
                            Excerpt
                            <small class="text-muted">(Optional)</small>
                        </label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                  id="excerpt"
                                  name="excerpt"
                                  rows="3"
                                  maxlength="500"
                                  placeholder="Write a brief description of your post...">{{ old('excerpt', $post->excerpt) }}</textarea>

                        <div class="form-text d-flex justify-content-between">
                            <span>Short summary shown in post listings and social shares</span>
                            <span id="excerptCounter" class="text-muted">{{ strlen(old('excerpt', $post->excerpt ?? '')) }}/500</span>
                        </div>

                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label required">
                            Post Content <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('content') is-invalid @enderror"
                                  id="content"
                                  name="content"
                                  rows="15"
                                  required
                                  placeholder="Start writing your amazing content...">{{ old('content', $post->content) }}</textarea>

                        <div class="form-text">
                            Use the rich text editor to format your content with headings, lists, links, and more.
                        </div>

                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SEO Settings Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-search me-2"></i>SEO Settings
                        <small class="text-muted">(Optional)</small>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text"
                                   class="form-control"
                                   id="meta_title"
                                   name="meta_title"
                                   value="{{ old('meta_title', $post->meta_title) }}"
                                   maxlength="60"
                                   placeholder="SEO title (leave empty to use post title)">
                            <div class="form-text">
                                <span id="metaTitleCounter" class="text-muted">{{ strlen(old('meta_title', $post->meta_title ?? '')) }}/60</span>
                                characters (optimal: 50-60)
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text"
                                   class="form-control"
                                   id="meta_keywords"
                                   name="meta_keywords"
                                   value="{{ old('meta_keywords', $post->meta_keywords) }}"
                                   placeholder="keyword1, keyword2, keyword3">
                            <div class="form-text">Separate keywords with commas</div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control"
                                  id="meta_description"
                                  name="meta_description"
                                  rows="2"
                                  maxlength="160"
                                  placeholder="Brief description for search engines...">{{ old('meta_description', $post->meta_description) }}</textarea>
                        <div class="form-text">
                            <span id="metaDescCounter" class="text-muted">{{ strlen(old('meta_description', $post->meta_description ?? '')) }}/160</span>
                            characters (optimal: 150-160)
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revision History Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Post History
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="border-end">
                                <h6 class="text-muted mb-1">Created</h6>
                                <p class="mb-0">{{ $post->created_at->format('M d, Y') }}</p>
                                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-end">
                                <h6 class="text-muted mb-1">Last Modified</h6>
                                <p class="mb-0">{{ $post->updated_at->format('M d, Y') }}</p>
                                <small class="text-muted">{{ $post->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-1">Status Changes</h6>
                            <p class="mb-0">
                                @if($post->published_at)
                                    Published {{ $post->published_at->diffForHumans() }}
                                @else
                                    Never published
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Publish Options -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-gear me-2"></i>Publish Options
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label required">
                            Status <span class="text-danger">*</span>
                        </label>
                        <div class="form-check">
                            <input class="form-check-input @error('status') is-invalid @enderror"
                                   type="radio"
                                   name="status"
                                   id="status_draft"
                                   value="draft"
                                   {{ old('status', $post->status) === 'draft' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_draft">
                                <i class="bi bi-file-earmark text-warning me-1"></i>
                                <strong>Draft</strong>
                                <small class="text-muted d-block">
                                    @if($post->status === 'published')
                                        Change to draft (will unpublish)
                                    @else
                                        Keep as draft for later editing
                                    @endif
                                </small>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input @error('status') is-invalid @enderror"
                                   type="radio"
                                   name="status"
                                   id="status_published"
                                   value="published"
                                   {{ old('status', $post->status) === 'published' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_published">
                                <i class="bi bi-eye text-success me-1"></i>
                                <strong>Published</strong>
                                <small class="text-muted d-block">
                                    @if($post->status === 'draft')
                                        Publish and make visible to readers
                                    @else
                                        Keep published and visible
                                    @endif
                                </small>
                            </label>
                        </div>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category" class="form-label required">
                            Category <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('category') is-invalid @enderror"
                                id="category"
                                name="category"
                                required>
                            <option value="">Select a category...</option>
                            <option value="tutorial" {{ old('category', $post->category) === 'tutorial' ? 'selected' : '' }}>
                                📚 Tutorial
                            </option>
                            <option value="review" {{ old('category', $post->category) === 'review' ? 'selected' : '' }}>
                                ⭐ Review
                            </option>
                            <option value="news" {{ old('category', $post->category) === 'news' ? 'selected' : '' }}>
                                📰 News
                            </option>
                            <option value="opinion" {{ old('category', $post->category) === 'opinion' ? 'selected' : '' }}>
                                💭 Opinion
                            </option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Publish Date -->
                    <div class="mb-3">
                        <label for="published_at" class="form-label">
                            Publish Date
                            <small class="text-muted">(Optional)</small>
                        </label>
                        <input type="datetime-local"
                               class="form-control @error('published_at') is-invalid @enderror"
                               id="published_at"
                               name="published_at"
                               value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                        <div class="form-text">
                            @if($post->published_at)
                                Current: {{ $post->published_at->format('M d, Y \a\t H:i') }}
                            @else
                                Leave empty to publish immediately
                            @endif
                        </div>
                        @error('published_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-image me-2"></i>Featured Image
                        <small class="text-muted">(Optional)</small>
                    </h6>
                </div>
                <div class="card-body text-center">
                    <!-- Current Image Display -->
                    @if($post->featured_image)
                        <div id="currentImage" class="mb-3">
                            <img src="{{ asset('storage/' . $post->featured_image) }}"
                                 alt="Current featured image"
                                 class="img-fluid rounded"
                                 style="max-height: 200px;">
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="showImageUpload()">
                                    <i class="bi bi-arrow-repeat"></i> Change Image
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCurrentImage()">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                            <input type="hidden" name="remove_image" id="removeImageFlag" value="0">
                        </div>
                    @endif

                    <!-- New Image Preview -->
                    <div id="imagePreview" class="mb-3" style="display: none;">
                        <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeNewImage()">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div id="imageUpload" style="display: {{ $post->featured_image ? 'none' : 'block' }};">
                        <i class="bi bi-cloud-upload text-muted" style="font-size: 3rem;"></i>
                        <h6 class="mt-2">
                            {{ $post->featured_image ? 'Upload New Image' : 'Upload Featured Image' }}
                        </h6>
                        <p class="text-muted small mb-3">
                            Drag and drop an image here, or click to select<br>
                            <small>Recommended: 1200x630px, Max: 5MB</small>
                        </p>
                        <input type="file"
                               class="form-control @error('featured_image') is-invalid @enderror"
                               id="featured_image"
                               name="featured_image"
                               accept="image/*"
                               style="display: none;">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('featured_image').click()">
                            <i class="bi bi-upload me-1"></i>Choose Image
                        </button>
                    </div>

                    @error('featured_image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Post Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Post Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-muted mb-1">Views</h6>
                                <h4 class="mb-0">{{ $post->views ?? '0' }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted mb-1">Comments</h6>
                            <h4 class="mb-0">{{ $post->comments_count ?? '0' }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                <i class="bi bi-x-lg me-2"></i>Cancel
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="showPreview()">
                                <i class="bi bi-eye me-2"></i>Preview Changes
                            </button>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="action" value="save" class="btn btn-success">
                                <i class="bi bi-check-lg me-2"></i>Save Changes
                            </button>
                            @if($post->status !== 'published')
                                <button type="submit" name="action" value="publish" class="btn btn-primary">
                                    <i class="bi bi-send me-2"></i>Save & Publish
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE with existing content
    tinymce.init({
        selector: '#content',
        height: 400,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | link image | preview code',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
        paste_data_images: true,
        image_title: true,
        automatic_uploads: true,
        file_picker_types: 'image',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });

    // Character counters with initial values
    function updateCounter(inputId, counterId, maxLength) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(counterId);

        if (input && counter) {
            const updateCount = () => {
                const length = input.value.length;
                counter.textContent = `${length}/${maxLength}`;

                if (length > maxLength * 0.9) {
                    counter.classList.add('text-warning');
                } else {
                    counter.classList.remove('text-warning');
                }

                if (length > maxLength) {
                    counter.classList.add('text-danger');
                } else {
                    counter.classList.remove('text-danger');
                }
            };

            input.addEventListener('input', updateCount);
            updateCount(); // Initial count with existing data
        }
    }

    // Initialize counters
    updateCounter('title', 'titleCounter', 255);
    updateCounter('excerpt', 'excerptCounter', 500);
    updateCounter('meta_title', 'metaTitleCounter', 60);
    updateCounter('meta_description', 'metaDescCounter', 160);

    // Slug generation (only if manually changed)
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const originalSlug = slugInput.dataset.original;

    titleInput.addEventListener('input', function() {
        if (!slugInput.dataset.manual && slugInput.value === originalSlug) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        }
    });

    slugInput.addEventListener('input', function() {
        this.dataset.manual = 'true';
    });

    // Image handling
    const imageInput = document.getElementById('featured_image');
    const imagePreview = document.getElementById('imagePreview');
    const imageUpload = document.getElementById('imageUpload');
    const previewImg = document.getElementById('previewImg');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
                imageUpload.style.display = 'none';

                // Hide current image if exists
                const currentImage = document.getElementById('currentImage');
                if (currentImage) {
                    currentImage.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Form submission handling
    const form = document.getElementById('editPostForm');
    form.addEventListener('submit', function(e) {
        // Update TinyMCE content
        tinymce.triggerSave();

        // Get the clicked button
        const submitter = e.submitter;
        const action = submitter?.value || 'save';

        // Update status based on action
        if (action === 'publish') {
            document.getElementById('status_published').checked = true;
        }

        // Show loading state
        submitter.disabled = true;
        const originalText = submitter.innerHTML;
        submitter.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';

        // Restore button if form validation fails
        setTimeout(() => {
            if (!form.checkValidity()) {
                submitter.disabled = false;
                submitter.innerHTML = originalText;
            }
        }, 100);
    });
});

// Image management functions
function showImageUpload() {
    document.getElementById('currentImage').style.display = 'none';
    document.getElementById('imageUpload').style.display = 'block';
}

function removeCurrentImage() {
    if (confirm('Are you sure you want to remove the current featured image?')) {
        document.getElementById('currentImage').style.display = 'none';
        document.getElementById('imageUpload').style.display = 'block';
        document.getElementById('removeImageFlag').value = '1';
    }
}

function removeNewImage() {
    const imageInput = document.getElementById('featured_image');
    const imagePreview = document.getElementById('imagePreview');
    const imageUpload = document.getElementById('imageUpload');
    const currentImage = document.getElementById('currentImage');

    imageInput.value = '';
    imagePreview.style.display = 'none';

    if (currentImage) {
        currentImage.style.display = 'block';
    } else {
        imageUpload.style.display = 'block';
    }
}

// Preview functionality
function showPreview() {
    // Save current form data to localStorage for preview
    const formData = new FormData(document.getElementById('editPostForm'));
    const previewData = {
        title: formData.get('title'),
        content: tinymce.get('content').getContent(),
        excerpt: formData.get('excerpt'),
        category: formData.get('category'),
        status: formData.get('status')
    };

    localStorage.setItem('previewData', JSON.stringify(previewData));

    // Open preview in new tab (would need backend route)
    // window.open('/admin/posts/preview', '_blank');

    // For now, show alert
    alert('Preview functionality would open in a new tab');
}
</script>
@endpush
