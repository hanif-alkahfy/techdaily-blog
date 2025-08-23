@extends('layouts.admin')

@section('title', 'Categories')
@section('subtitle', 'Create, edit, and manage your categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Categories</h1>
        <p class="text-muted mb-0">Manage your blog categories</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="bi bi-plus-lg me-2"></i>Add Category
        </button>
    </div>
</div>

<!-- Categories List -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Posts</th>
                        <th style="width: 150px;">Created</th>
                        <th style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>
                            <div class="fw-medium">{{ $category->name }}</div>
                            @if($category->description)
                                <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <code>{{ $category->slug }}</code>
                        </td>
                        <td>
                            <a href="{{ route('admin.posts.index', ['category' => $category->id]) }}" class="text-decoration-none">
                                {{ $category->posts_count }}
                                {{ Str::plural('post', $category->posts_count) }}
                            </a>
                        </td>
                        <td>
                            <div>{{ $category->created_at->format('M d, Y') }}</div>
                            <small class="text-muted">{{ $category->created_at->format('H:i A') }}</small>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCategoryModal"
                                        data-category-id="{{ $category->id }}"
                                        data-category-name="{{ $category->name }}"
                                        data-category-slug="{{ $category->slug }}"
                                        data-category-description="{{ $category->description }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-folder2-open display-6"></i>
                                <p class="mt-3">No categories found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div class="mt-4">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label required">Category Name</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text"
                               class="form-control @error('slug') is-invalid @enderror"
                               id="slug"
                               name="slug"
                               placeholder="auto-generated-from-name">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Leave empty to auto-generate from name</div>
                    </div>
                    <div class="mb-0">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="3"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label required">Category Name</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="edit_name"
                               name="name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit_slug" class="form-label">Slug</label>
                        <input type="text"
                               class="form-control @error('slug') is-invalid @enderror"
                               id="edit_slug"
                               name="slug">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Be careful when changing slugs of existing categories</div>
                    </div>
                    <div class="mb-0">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="edit_description"
                                  name="description"
                                  rows="3"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const editNameInput = document.getElementById('edit_name');
    const editSlugInput = document.getElementById('edit_slug');

    function generateSlug(name) {
        return name.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    // For create form
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.value === generateSlug(this.value.trim())) {
                slugInput.value = generateSlug(this.value.trim());
            }
        });
    }

    // For edit form
    if (editNameInput && editSlugInput) {
        let originalSlug = '';
        editNameInput.addEventListener('input', function() {
            if (editSlugInput.value === originalSlug || editSlugInput.value === generateSlug(originalSlug)) {
                editSlugInput.value = generateSlug(this.value.trim());
            }
        });
    }

    // Handle edit modal
    const editModal = document.getElementById('editCategoryModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const categoryId = button.dataset.categoryId;
            const name = button.dataset.categoryName;
            const slug = button.dataset.categorySlug;
            const description = button.dataset.categoryDescription;

            const form = this.querySelector('#editCategoryForm');
            form.action = `/admin/categories/${categoryId}`;

            const nameInput = form.querySelector('#edit_name');
            const slugInput = form.querySelector('#edit_slug');
            const descriptionInput = form.querySelector('#edit_description');

            nameInput.value = name;
            slugInput.value = slug;
            originalSlug = slug;
            descriptionInput.value = description || '';
        });
    }
});

// Delete category confirmation
function deleteCategory(id, name) {
    if (confirm(`Are you sure you want to delete the category "${name}"? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/categories/${id}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
