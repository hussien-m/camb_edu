@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <!-- Stats Boxes -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['courses'] }}</h3>
                    <p>Total Courses</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="{{ route('admin.courses.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['categories'] }}</h3>
                    <p>Categories</p>
                </div>
                <div class="icon">
                    <i class="fas fa-folder"></i>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['unread_messages'] }}</h3>
                    <p>Unread Messages</p>
                </div>
                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <a href="{{ route('admin.contacts.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['stories'] }}</h3>
                    <p>Success Stories</p>
                </div>
                <div class="icon">
                    <i class="fas fa-star"></i>
                </div>
                <a href="{{ route('admin.stories.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Courses by Status</h3>
                </div>
                <div class="card-body">
                    <canvas id="coursesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Students by Status</h3>
                </div>
                <div class="card-body">
                    <canvas id="studentsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Courses -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Courses</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCourses as $course)
                                <tr>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->category->name ?? 'N/A' }}</td>
                                    <td>{{ $course->level->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $course->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($course->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No courses found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Messages -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Messages</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @forelse($recentMessages as $message)
                            <li class="item">
                                <div class="product-info">
                                    <a href="{{ route('admin.contacts.show', $message) }}" class="product-title">
                                        {{ $message->name ?? 'Anonymous' }}
                                        @if(!$message->is_read)
                                            <span class="badge badge-warning float-right">New</span>
                                        @endif
                                    </a>
                                    <span class="product-description">
                                        {{ Str::limit($message->subject ?? 'No subject', 30) }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="item">
                                <div class="product-info">
                                    <span class="product-description text-center">No messages</span>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.contacts.index') }}" class="uppercase">View All Messages</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    @if(isset($recentActivities) && $recentActivities->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Activities</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @foreach($recentActivities as $activity)
                            <li class="item">
                                <div class="product-info">
                                    <span class="product-title">
                                        <strong>{{ $activity->admin->name ?? 'System' }}</strong>
                                        {{ $activity->action }} {{ $activity->model }}
                                        @if($activity->model_id)
                                            #{{ $activity->model_id }}
                                        @endif
                                    </span>
                                    <span class="product-description">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Courses Chart
    const coursesCtx = document.getElementById('coursesChart');
    if (coursesCtx) {
        new Chart(coursesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Inactive'],
                datasets: [{
                    data: [{{ $coursesByStatus['active'] }}, {{ $coursesByStatus['inactive'] }}],
                    backgroundColor: ['#28a745', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Students Chart
    const studentsCtx = document.getElementById('studentsChart');
    if (studentsCtx) {
        new Chart(studentsCtx, {
            type: 'bar',
            data: {
                labels: ['Active', 'Pending', 'Suspended'],
                datasets: [{
                    label: 'Students',
                    data: [
                        {{ $studentsByStatus['active'] }},
                        {{ $studentsByStatus['pending'] }},
                        {{ $studentsByStatus['suspended'] ?? 0 }}
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>
@endpush
