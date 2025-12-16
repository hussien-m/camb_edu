@extends('admin.layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header with Period Selector -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line me-2"></i>Analytics Dashboard
        </h1>
        <div>
            <select class="form-select" id="periodSelect" onchange="window.location.href='?period='+this.value">
                <option value="7" {{ $period == 7 ? 'selected' : '' }}>Last 7 Days</option>
                <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 Days</option>
                <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 Days</option>
                <option value="365" {{ $period == 365 ? 'selected' : '' }}>Last Year</option>
            </select>
        </div>
    </div>

    <!-- Overview Stats Cards -->
    <div class="row mb-4">
        <!-- Total Views -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Page Views
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_views']) }}
                            </div>
                            @if(isset($changes['total_views']))
                            <div class="mt-2">
                                <span class="badge {{ $changes['total_views'] >= 0 ? 'bg-success' : 'bg-danger' }}">
                                    <i class="fas fa-arrow-{{ $changes['total_views'] >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($changes['total_views']) }}%
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unique Visitors -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Unique Visitors
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['unique_visitors']) }}
                            </div>
                            @if(isset($changes['unique_visitors']))
                            <div class="mt-2">
                                <span class="badge {{ $changes['unique_visitors'] >= 0 ? 'bg-success' : 'bg-danger' }}">
                                    <i class="fas fa-arrow-{{ $changes['unique_visitors'] >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($changes['unique_visitors']) }}%
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Students -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                New Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_students']) }}
                            </div>
                            @if(isset($changes['total_students']))
                            <div class="mt-2">
                                <span class="badge {{ $changes['total_students'] >= 0 ? 'bg-success' : 'bg-danger' }}">
                                    <i class="fas fa-arrow-{{ $changes['total_students'] >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($changes['total_students']) }}%
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Inquiries -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Course Inquiries
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_inquiries']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Views Chart -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-area me-2"></i>Daily Views Overview
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="viewsChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Device & Page Type Distribution -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-mobile-alt me-2"></i>Device Distribution
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="deviceChart" height="200"></canvas>
                    <div class="mt-3">
                        @foreach($viewsByDevice as $device)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-capitalize">{{ $device->device_type }}</span>
                            <strong>{{ number_format($device->views_count) }}</strong>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Most Viewed Courses -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-star me-2"></i>Most Viewed Courses
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Course</th>
                                    <th>Category</th>
                                    <th class="text-end">Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mostViewedCourses as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ route('admin.courses.edit', $item['course']->id) }}" target="_blank">
                                            {{ Str::limit($item['course']->title, 40) }}
                                        </a>
                                    </td>
                                    <td>{{ $item['course']->category->name ?? 'N/A' }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-primary">{{ number_format($item['views']) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Views by Country -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-globe me-2"></i>Top Countries
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Country</th>
                                    <th class="text-end">Views</th>
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalCountryViews = $viewsByCountry->sum('views_count'); @endphp
                                @forelse($viewsByCountry as $index => $country)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                        {{ $country->country ?? 'Unknown' }}
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-info">{{ number_format($country->views_count) }}</span>
                                    </td>
                                    <td class="text-end">
                                        {{ $totalCountryViews > 0 ? round(($country->views_count / $totalCountryViews) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock me-2"></i>Recent Activities
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#students">New Students</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#enrollments">Enrollments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#inquiries">Inquiries</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <div id="students" class="tab-pane fade show active">
                            <div class="list-group">
                                @forelse($recentStudents as $student)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <i class="fas fa-user-graduate text-info me-2"></i>
                                            <strong>{{ $student->name }}</strong>
                                            <span class="text-muted">- {{ $student->email }}</span>
                                        </div>
                                        <small class="text-muted">{{ $student->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted">No recent students</p>
                                @endforelse
                            </div>
                        </div>

                        <div id="enrollments" class="tab-pane fade">
                            <div class="list-group">
                                @forelse($recentEnrollments as $enrollment)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <i class="fas fa-book text-success me-2"></i>
                                            <strong>{{ $enrollment->student->name }}</strong>
                                            <span class="text-muted">enrolled in</span>
                                            <strong>{{ $enrollment->course->title }}</strong>
                                        </div>
                                        <small class="text-muted">{{ $enrollment->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted">No recent enrollments</p>
                                @endforelse
                            </div>
                        </div>

                        <div id="inquiries" class="tab-pane fade">
                            <div class="list-group">
                                @forelse($recentInquiries as $inquiry)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <i class="fas fa-envelope text-warning me-2"></i>
                                            <strong>{{ $inquiry->name }}</strong>
                                            <span class="text-muted">inquired about</span>
                                            <strong>{{ $inquiry->course->title }}</strong>
                                        </div>
                                        <small class="text-muted">{{ $inquiry->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted">No recent inquiries</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Views Chart
const viewsCtx = document.getElementById('viewsChart').getContext('2d');
new Chart(viewsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Page Views',
            data: {!! json_encode($chartData) !!},
            borderColor: 'rgb(78, 115, 223)',
            backgroundColor: 'rgba(78, 115, 223, 0.05)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

// Device Chart
const deviceCtx = document.getElementById('deviceChart').getContext('2d');
const deviceData = {!! json_encode($viewsByDevice->pluck('views_count')) !!};
const deviceLabels = {!! json_encode($viewsByDevice->pluck('device_type')) !!};

new Chart(deviceCtx, {
    type: 'doughnut',
    data: {
        labels: deviceLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
        datasets: [{
            data: deviceData,
            backgroundColor: [
                'rgb(78, 115, 223)',
                'rgb(28, 200, 138)',
                'rgb(246, 194, 62)',
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush
@endsection
