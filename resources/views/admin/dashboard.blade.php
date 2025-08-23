@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Overview of your blog')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="card-title mb-2 text-black">Welcome back, {{ auth()->user()->name }}! 👋</h2>
                            <p class="card-text mb-0 opacity-75 text-black">
                                Here's what's happening with your blog today. You have {{ $stats['total_posts'] }} posts total.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Posts</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['total_posts'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-file-text fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Published Posts</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['published_posts'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Categories</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['total_categories'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['total_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Posts Table -->
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">Recent Posts</h6>
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> New Post
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_posts as $post)
                                    <tr>
                                        <td>{{ $post->title }}</td>
                                        <td>{{ $post->user->name }}</td>
                                        <td>{{ $post->category->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'warning' }}">
                                                {{ ucfirst($post->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirmDelete()">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No posts found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Actions Row -->
        <div class="row mb-4">
            <!-- Posts Chart -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-pie-chart me-2 text-primary"></i>Posts Distribution
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="position-relative d-inline-block">
                            <canvas id="postsChart" width="150" height="150"></canvas>
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <div class="text-center">
                                    <h4 class="mb-0">{{ $stats['total_posts'] }}</h4>
                                    <small class="text-muted">Total Posts</small>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="fw-bold text-success">{{ $stats['published_posts'] }}</div>
                                    <small class="text-muted">Published</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="fw-bold text-warning">{{ $stats['draft_posts'] }}</div>
                                    <small class="text-muted">Drafts</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-lightning me-2 text-primary"></i>Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Create New Post
                            </a>
                            <a href="{{ route('admin.posts.index', ['status' => 'draft']) }}" class="btn btn-outline-warning">
                                <i class="bi bi-clock me-2"></i>Review Drafts ({{ $stats['draft_posts'] }})
                            </a>
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-list-ul me-2"></i>Manage All Posts
                            </a>
                            <hr class="my-2">
                            <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-globe me-2"></i>View Website
                                <i class="bi bi-box-arrow-up-right ms-auto small"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Popular Posts (if you have analytics) -->
    @if($popular_posts->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-star me-2 text-warning"></i>Popular Posts
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($popular_posts as $post)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border border-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="{{ route('admin.posts.show', $post) }}" class="text-decoration-none">
                                                {{ Str::limit($post->title, 40) }}
                                            </a>
                                        </h6>
                                        <p class="card-text text-muted small mb-2">
                                            {{ Str::limit(strip_tags($post->excerpt ?: $post->content), 80) }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $post->created_at->format('M d') }}
                                            </small>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                {{ $post->category->name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .text-xs {
        font-size: 0.75rem;
    }

    .font-weight-bold {
        font-weight: 700 !important;
    }

    .text-gray-800 {
        color: #343a40 !important;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    #postsChart {
        max-width: 150px;
        max-height: 150px;
    }

    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Posts Distribution Chart
    const ctx = document.getElementById('postsChart').getContext('2d');
    const postsChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Published', 'Drafts'],
            datasets: [{
                data: [{{ $posts_by_status['published'] }}, {{ $posts_by_status['draft'] }}],
                backgroundColor: ['#198754', '#ffc107'],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed * 100) / total).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
