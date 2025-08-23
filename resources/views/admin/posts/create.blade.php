@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Create New Post</h1>
        <p class="text-muted mb-0">Write and publish your new blog post</p>
    </div>
    <div>
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
        <li class="breadcrumb-item active" aria-current="page">Create</li>
    </ol>
</nav>

<form method="POST" action="{{ route('admin.posts.store') }}" id="createPostForm" enctype="multipart/form-data" novalidate>
    @csrf

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Post Content Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text me-2"></i>Post Content
                    </h5>
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
                               value="{{ old('title') }}"
                               placeholder="Enter your post title..."
                               maxlength="255"
                               required>

                        <!-- Live character counter -->
                        <div class="form-text d-flex justify-content-between">
                            <span>A compelling title helps attract readers</span>
                            <span id="titleCounter" class="text-muted">0/255</span>
                        </div>

                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slug (Auto-generated) -->
                    <div class="mb-3">
                        <label for="slug" class="form-label">URL Slug</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ url('/') }}/posts/</span>
                            <input type="text"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   id="slug"
                                   name="slug"
                                   value="{{ old('slug') }}"
                                   placeholder="auto-generated-from-title">
                        </div>
                        <div class="form-text">Leave empty to auto-generate from title</div>
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
                                  placeholder="Write a brief description of your post...">{{ old('excerpt') }}</textarea>

                        <div class="form-text d-flex justify-content-between">
                            <span>Short summary shown in post listings and social shares</span>
                            <span id="excerptCounter" class="text-muted">0/500</span>
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
                                  placeholder="Start writing your amazing content...">{{ old('content') }}</textarea>

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
                                   value="{{ old('meta_title') }}"
                                   maxlength="60"
                                   placeholder="SEO title (leave empty to use post title)">
                            <div class="form-text">
                                <span id="metaTitleCounter" class="text-muted">0/60</span>
                                characters (optimal: 50-60)
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text"
                                   class="form-control"
                                   id="meta_keywords"
                                   name="meta_keywords"
                                   value="{{ old('meta_keywords') }}"
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
                                  placeholder="Brief description for search engines...">{{ old('meta_description') }}</textarea>
                        <div class="form-text">
                            <span id="metaDescCounter" class="text-muted">0/160</span>
                            characters (optimal: 150-160)
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
                                   {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_draft">
                                <i class="bi bi-file-earmark text-warning me-1"></i>
                                <strong>Draft</strong>
                                <small class="text-muted d-block">Save as draft for later editing</small>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input @error('status') is-invalid @enderror"
                                   type="radio"
                                   name="status"
                                   id="status_published"
                                   value="published"
                                   {{ old('status') === 'published' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_published">
                                <i class="bi bi-eye text-success me-1"></i>
                                <strong>Published</strong>
                                <small class="text-muted d-block">Make post visible to readers</small>
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
                        <select class="form-select @error('category_id') is-invalid @enderror"
                                id="category_id"
                                name="category_id"
                                required>
                            <option value="">Select a category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
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
                               value="{{ old('published_at') }}">
                        <div class="form-text">Leave empty to publish immediately</div>
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
                    <div id="imagePreview" class="mb-3" style="display: none;">
                        <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    </div>

                    <div id="imageUpload">
                        <i class="bi bi-cloud-upload text-muted" style="font-size: 3rem;"></i>
                        <h6 class="mt-2">Upload Featured Image</h6>
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

            <!-- Quick Tips -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-lightbulb me-2"></i>Writing Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Use clear, descriptive titles
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Write compelling excerpts
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Break content with headings
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Add relevant images
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Proofread before publishing
                        </li>
                    </ul>
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
                        <div>
                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                <i class="bi bi-x-lg me-2"></i>Cancel
                            </button>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" onclick="setStatus('draft')" class="btn btn-outline-warning">
                                <i class="bi bi-file-earmark me-2"></i>Save as Draft
                            </button>
                            <button type="submit" onclick="setStatus('published')" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>Publish Post
                            </button>
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
<script src="https://cdn.tiny.cloud/1/36qjgmfh0gd0l2w8xgvg9jruta92o4ey2kdf0pvv5wvsnp7a/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
// Handle status setting
function setStatus(status) {
    document.getElementById(`status_${status}`).checked = true;
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE
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

    // Character counters
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
            updateCount(); // Initial count
        }
    }

    // Initialize counters
    updateCounter('title', 'titleCounter', 255);
    updateCounter('excerpt', 'excerptCounter', 500);
    updateCounter('meta_title', 'metaTitleCounter', 60);
    updateCounter('meta_description', 'metaDescCounter', 160);

    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    titleInput.addEventListener('input', function() {
        if (!slugInput.dataset.manual) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^\w\s-]/g, '') // Remove special characters
                .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
                .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
            slugInput.value = slug;
        }
    });

    // Mark slug as manually edited
    slugInput.addEventListener('input', function() {
        this.dataset.manual = 'true';
    });

    // Featured image preview
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
            };
            reader.readAsDataURL(file);
        }
    });

    // Form submission handling
    const form = document.getElementById('createPostForm');
    form.addEventListener('submit', function(e) {
        // Update TinyMCE content
        tinymce.triggerSave();

        // Get the clicked button
        const submitter = e.submitter;
        const action = submitter?.value || 'draft';

        // Update status based on action
        if (action === 'publish') {
            document.getElementById('status_published').checked = true;
        } else if (action === 'draft') {
            document.getElementById('status_draft').checked = true;
        }

        // Show loading state
        submitter.disabled = true;
        submitter.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';
    });

    // Auto-save draft (optional feature)
    let autoSaveTimer;
    function scheduleAutoSave() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(autoSaveDraft, 30000); // Auto-save every 30 seconds
    }

    function autoSaveDraft() {
        // This would save to localStorage or make an AJAX call
        console.log('Auto-saving draft...');
    }

    // Schedule auto-save on input changes
    document.querySelectorAll('input, textarea, select').forEach(input => {
        input.addEventListener('input', scheduleAutoSave);
    });
});

// Remove featured image
function removeImage() {
    const imageInput = document.getElementById('featured_image');
    const imagePreview = document.getElementById('imagePreview');
    const imageUpload = document.getElementById('imageUpload');

    imageInput.value = '';
    imagePreview.style.display = 'none';
    imageUpload.style.display = 'block';
}

// Form validation before submit
function validateForm() {
    let isValid = true;
    const requiredFields = ['title', 'content', 'category', 'status'];

    requiredFields.forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}
</script>
@endpush
