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
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-globe me-2"></i>Top Countries
                    </h6>
                    <span class="badge bg-primary">{{ $viewsByCountry->count() }} Countries</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Country</th>
                                    <th class="text-center" style="width: 100px;">Visitors</th>
                                    <th class="text-center" style="width: 100px;">Views</th>
                                    <th class="text-end" style="width: 80px;">%</th>
                                    <th style="width: 200px;">Distribution</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalCountryViews = $viewsByCountry->sum('views_count');
                                    $countryFlags = [
                                        'Syria' => '🇸🇾',
                                        'Jordan' => '🇯🇴',
                                        'Lebanon' => '🇱🇧',
                                        'Egypt' => '🇪🇬',
                                        'Saudi Arabia' => '🇸🇦',
                                        'United Arab Emirates' => '🇦🇪',
                                        'Kuwait' => '🇰🇼',
                                        'Qatar' => '🇶🇦',
                                        'Bahrain' => '🇧🇭',
                                        'Oman' => '🇴🇲',
                                        'Iraq' => '🇮🇶',
                                        'Palestine' => '🇵🇸',
                                        'United States' => '🇺🇸',
                                        'United Kingdom' => '🇬🇧',
                                        'Germany' => '🇩🇪',
                                        'France' => '🇫🇷',
                                        'Turkey' => '🇹🇷',
                                        'Canada' => '🇨🇦',
                                        'Australia' => '🇦🇺',
                                    ];
                                @endphp
                                @forelse($viewsByCountry as $index => $country)
                                @php
                                    $percentage = $totalCountryViews > 0 ? round(($country->views_count / $totalCountryViews) * 100, 1) : 0;
                                    $uniqueVisitors = \App\Models\PageView::where('created_at', '>=', now()->subDays($period))
                                        ->where('country', $country->country)
                                        ->distinct('ip_address')
                                        ->count('ip_address');
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <strong class="text-primary">{{ $index + 1 }}</strong>
                                    </td>
                                    <td>
                                        <span style="font-size: 1.2em; margin-right: 5px;">{{ $countryFlags[$country->country] ?? '🌍' }}</span>
                                        <strong>{{ $country->country ?? 'Unknown' }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ number_format($uniqueVisitors) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ number_format($country->views_count) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ $percentage }}%</strong>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-gradient-primary" role="progressbar"
                                                 style="width: {{ $percentage }}%;"
                                                 aria-valuenow="{{ $percentage }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                                {{ $percentage }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-globe fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No country data available</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($viewsByCountry->count() > 0)
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th class="text-center">
                                        <span class="badge bg-dark">
                                            {{ number_format(\App\Models\PageView::where('created_at', '>=', now()->subDays($period))->distinct('ip_address')->count('ip_address')) }}
                                        </span>
                                    </th>
                                    <th class="text-center">
                                        <span class="badge bg-dark">{{ number_format($totalCountryViews) }}</span>
                                    </th>
                                    <th class="text-end"><strong>100%</strong></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Analytics -->
    <div class="row">
        <!-- Top Cities -->
        <div class="col-xl-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-city me-2"></i>Top Cities
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>City</th>
                                    <th>Country</th>
                                    <th class="text-end">Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCities as $city)
                                <tr>
                                    <td>
                                        <i class="fas fa-map-pin text-muted me-1"></i>
                                        {{ $city->city }}
                                    </td>
                                    <td><small class="text-muted">{{ $city->country }}</small></td>
                                    <td class="text-end">
                                        <span class="badge bg-info">{{ number_format($city->views) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Browser & OS Stats -->
        <div class="col-xl-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-desktop me-2"></i>Browser & OS
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="text-sm font-weight-bold mb-2">Browsers</h6>
                    @forelse($browserStats as $browser)
                    <div class="d-flex justify-content-between mb-2">
                        <span>
                            @if($browser->browser == 'Chrome')
                                <i class="fab fa-chrome text-warning me-1"></i>
                            @elseif($browser->browser == 'Firefox')
                                <i class="fab fa-firefox text-danger me-1"></i>
                            @elseif($browser->browser == 'Safari')
                                <i class="fab fa-safari text-info me-1"></i>
                            @elseif($browser->browser == 'Edge')
                                <i class="fab fa-edge text-primary me-1"></i>
                            @else
                                <i class="fas fa-globe me-1"></i>
                            @endif
                            {{ $browser->browser }}
                        </span>
                        <strong>{{ number_format($browser->count) }}</strong>
                    </div>
                    @empty
                    <p class="text-muted text-center small">No data</p>
                    @endforelse

                    <hr class="my-3">

                    <h6 class="text-sm font-weight-bold mb-2">Operating Systems</h6>
                    @forelse($osStats as $os)
                    <div class="d-flex justify-content-between mb-2">
                        <span>
                            @if($os->os == 'Windows')
                                <i class="fab fa-windows text-primary me-1"></i>
                            @elseif($os->os == 'macOS')
                                <i class="fab fa-apple text-dark me-1"></i>
                            @elseif($os->os == 'Linux')
                                <i class="fab fa-linux text-dark me-1"></i>
                            @elseif($os->os == 'Android')
                                <i class="fab fa-android text-success me-1"></i>
                            @elseif($os->os == 'iOS')
                                <i class="fab fa-apple text-secondary me-1"></i>
                            @else
                                <i class="fas fa-desktop me-1"></i>
                            @endif
                            {{ $os->os }}
                        </span>
                        <strong>{{ number_format($os->count) }}</strong>
                    </div>
                    @empty
                    <p class="text-muted text-center small">No data</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ISP & Metrics -->
        <div class="col-xl-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-network-wired me-2"></i>ISP & Metrics
                    </h6>
                </div>
                <div class="card-body">
                    @if($avgTimeOnPage)
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Avg. Time on Page:</strong>
                        {{ gmdate('i:s', $avgTimeOnPage) }} min
                    </div>
                    @endif

                    <h6 class="text-sm font-weight-bold mb-2">Top ISPs</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                @forelse($topISPs->take(5) as $isp)
                                <tr>
                                    <td>
                                        <small>{{ Str::limit($isp->isp, 30) }}</small>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-secondary">{{ $isp->views }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted small">No data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timezone & Region Stats -->
    <div class="row">
        <!-- Timezone Distribution -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock me-2"></i>Timezone Distribution
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Timezone</th>
                                    <th class="text-end">Visitors</th>
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalTimezone = $timezoneStats->sum('count'); @endphp
                                @forelse($timezoneStats as $tz)
                                <tr>
                                    <td>
                                        <i class="fas fa-globe-americas text-info me-1"></i>
                                        <small>{{ $tz->timezone }}</small>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-info">{{ number_format($tz->count) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <small>{{ $totalTimezone > 0 ? round(($tz->count / $totalTimezone) * 100, 1) : 0 }}%</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No timezone data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Region Distribution -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marked-alt me-2"></i>Top Regions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Region</th>
                                    <th>Country</th>
                                    <th class="text-end">Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($regionStats as $region)
                                <tr>
                                    <td>
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                        {{ $region->region }}
                                    </td>
                                    <td><small class="text-muted">{{ $region->country }}</small></td>
                                    <td class="text-end">
                                        <span class="badge bg-danger">{{ number_format($region->count) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No region data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hourly Traffic Pattern -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Hourly Traffic Pattern (24h)
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="hourlyChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Traffic Sources -->
    <div class="row">
        <div class="col-xl-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-share-alt me-2"></i>Traffic Sources
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="trafficSourceChart" height="200"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-link text-success me-2"></i>Direct</span>
                            <strong>{{ number_format($trafficSources['direct']) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-home text-primary me-2"></i>Internal</span>
                            <strong>{{ number_format($trafficSources['internal']) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-external-link-alt text-warning me-2"></i>External</span>
                            <strong>{{ number_format($trafficSources['external']) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Referrers -->
        <div class="col-xl-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-link me-2"></i>Top Referring URLs
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Referrer URL</th>
                                    <th class="text-end">Visits</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topReferrers as $index => $ref)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($ref->referer, 70) }}</small>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-warning">{{ number_format($ref->count) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No external referrers</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Visitors Detail -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-users me-2"></i>Recent Visitors Detail
                        </h6>
                        <span class="badge bg-white text-primary">Last 30 Visitors (Public Pages)</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="min-width: 900px;">
                            <thead style="background-color: #f8f9fc; border-bottom: 2px solid #e3e6f0;">
                                <tr>
                                    <th class="py-3 px-3" style="width: 140px;">
                                        <div class="text-uppercase text-primary" style="font-size: 0.75rem; font-weight: 700;">
                                            <i class="fas fa-clock me-1"></i>Date & Time
                                        </div>
                                    </th>
                                    <th class="py-3 px-3" style="width: 130px;">
                                        <div class="text-uppercase text-primary" style="font-size: 0.75rem; font-weight: 700;">
                                            <i class="fas fa-network-wired me-1"></i>IP Address
                                        </div>
                                    </th>
                                    <th class="py-3 px-3" style="width: 250px;">
                                        <div class="text-uppercase text-primary" style="font-size: 0.75rem; font-weight: 700;">
                                            <i class="fas fa-map-marked-alt me-1"></i>Location
                                        </div>
                                    </th>
                                    <th class="py-3 px-3" style="width: 130px;">
                                        <div class="text-uppercase text-primary" style="font-size: 0.75rem; font-weight: 700;">
                                            <i class="fas fa-globe me-1"></i>Browser
                                        </div>
                                    </th>
                                    <th class="py-3 px-3" style="width: 130px;">
                                        <div class="text-uppercase text-primary" style="font-size: 0.75rem; font-weight: 700;">
                                            <i class="fas fa-laptop me-1"></i>System
                                        </div>
                                    </th>
                                    <th class="py-3 px-3 text-center" style="width: 100px;">
                                        <div class="text-uppercase text-primary" style="font-size: 0.75rem; font-weight: 700;">
                                            <i class="fas fa-mobile-alt me-1"></i>Device
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $countryFlags = [
                                        'Syria' => '🇸🇾', 'Jordan' => '🇯🇴', 'Lebanon' => '🇱🇧', 'Egypt' => '🇪🇬',
                                        'Saudi Arabia' => '🇸🇦', 'United Arab Emirates' => '🇦🇪', 'Kuwait' => '🇰🇼',
                                        'Qatar' => '🇶🇦', 'Bahrain' => '🇧🇭', 'Oman' => '🇴🇲', 'Iraq' => '🇮🇶',
                                        'Palestine' => '🇵🇸', 'United States' => '🇺🇸', 'United Kingdom' => '🇬🇧',
                                        'Germany' => '🇩🇪', 'France' => '🇫🇷', 'Turkey' => '🇹🇷', 'Canada' => '🇨🇦',
                                        'Australia' => '🇦🇺', 'India' => '🇮🇳', 'China' => '🇨🇳', 'Japan' => '🇯🇵',
                                    ];
                                @endphp
                                @forelse($recentVisitors as $index => $visitor)
                                <tr style="border-bottom: 1px solid #e3e6f0;">
                                    <td class="py-3 px-3">
                                        <div style="font-size: 0.875rem; line-height: 1.4;">
                                            <div class="text-dark fw-bold">{{ $visitor->created_at->format('M d, Y') }}</div>
                                            <div class="text-muted" style="font-size: 0.8rem;">{{ $visitor->created_at->format('h:i:s A') }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <code class="d-inline-block px-2 py-1 rounded" style="background-color: #e3e6f0; color: #4e73df; font-size: 0.8rem; font-weight: 600;">
                                            {{ $visitor->ip_address }}
                                        </code>
                                    </td>
                                    <td class="py-3 px-3">
                                        <div style="line-height: 1.5;">
                                            <div class="mb-1">
                                                <span style="font-size: 1.2em; margin-right: 4px;">{{ $countryFlags[$visitor->country] ?? '🌍' }}</span>
                                                <span class="fw-bold text-dark">{{ $visitor->country }}</span>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.8rem;">
                                                <i class="fas fa-map-pin me-1" style="font-size: 0.75rem;"></i>{{ $visitor->city }}@if($visitor->region && $visitor->region != 'Unknown' && $visitor->region != $visitor->city), {{ $visitor->region }}@endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="d-flex align-items-center">
                                            @if($visitor->browser == 'Chrome')
                                                <i class="fab fa-chrome text-warning" style="font-size: 1.3rem; width: 24px;"></i>
                                            @elseif($visitor->browser == 'Firefox')
                                                <i class="fab fa-firefox text-danger" style="font-size: 1.3rem; width: 24px;"></i>
                                            @elseif($visitor->browser == 'Safari')
                                                <i class="fab fa-safari text-info" style="font-size: 1.3rem; width: 24px;"></i>
                                            @elseif($visitor->browser == 'Edge')
                                                <i class="fab fa-edge text-primary" style="font-size: 1.3rem; width: 24px;"></i>
                                            @elseif($visitor->browser == 'Opera')
                                                <i class="fab fa-opera text-danger" style="font-size: 1.3rem; width: 24px;"></i>
                                            @else
                                                <i class="fas fa-globe text-secondary" style="font-size: 1.3rem; width: 24px;"></i>
                                            @endif
                                            <span class="ms-2 text-dark" style="font-size: 0.875rem;">{{ $visitor->browser }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="d-flex align-items-center">
                                            @if($visitor->os == 'Windows')
                                                <i class="fab fa-windows text-primary" style="font-size: 1.3rem; width: 24px;"></i>
                                            @elseif($visitor->os == 'macOS')
                                                <i class="fab fa-apple" style="font-size: 1.3rem; width: 24px; color: #333;"></i>
                                            @elseif($visitor->os == 'Linux')
                                                <i class="fab fa-linux" style="font-size: 1.3rem; width: 24px; color: #333;"></i>
                                            @elseif($visitor->os == 'Android')
                                                <i class="fab fa-android text-success" style="font-size: 1.3rem; width: 24px;"></i>
                                            @elseif($visitor->os == 'iOS')
                                                <i class="fab fa-apple text-secondary" style="font-size: 1.3rem; width: 24px;"></i>
                                            @else
                                                <i class="fas fa-desktop text-secondary" style="font-size: 1.3rem; width: 24px;"></i>
                                            @endif
                                            <span class="ms-2 text-dark" style="font-size: 0.875rem;">{{ $visitor->os }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        @if($visitor->device_type == 'mobile')
                                            <span class="badge" style="background-color: #1cc88a; padding: 0.4rem 0.6rem; font-size: 0.75rem;">
                                                <i class="fas fa-mobile-alt me-1"></i>Mobile
                                            </span>
                                        @elseif($visitor->device_type == 'tablet')
                                            <span class="badge" style="background-color: #36b9cc; padding: 0.4rem 0.6rem; font-size: 0.75rem;">
                                                <i class="fas fa-tablet-alt me-1"></i>Tablet
                                            </span>
                                        @else
                                            <span class="badge" style="background-color: #4e73df; padding: 0.4rem 0.6rem; font-size: 0.75rem;">
                                                <i class="fas fa-desktop me-1"></i>Desktop
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div style="padding: 2rem;">
                                            <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                            <p class="text-muted mb-1" style="font-size: 1rem;">No recent visitors found</p>
                                            <small class="text-muted" style="font-size: 0.85rem;">Only public page visits are displayed here</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($recentVisitors->count() > 0)
                <div class="card-footer bg-light text-center py-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Displaying visitors from public pages only • Admin panel activity excluded
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Search Queries -->
    @if($searchQueries->count() > 0)
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-search me-2"></i>Popular Search Queries
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($searchQueries as $query)
                        <div class="col-md-3 mb-2">
                            <span class="badge bg-light text-dark border">
                                {{ $query->search_query }}
                                <span class="badge bg-primary ms-1">{{ $query->count }}</span>
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

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
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <i class="fas fa-envelope text-warning me-2"></i>
                                            <strong>{{ $inquiry->student_name ?? 'Guest' }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-at me-1"></i>{{ $inquiry->email }}
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-book me-1"></i>
                                                {{ $inquiry->course ? $inquiry->course->title : 'N/A' }}
                                            </small>
                                            @if($inquiry->message)
                                            <br>
                                            <small class="text-muted" style="display: block; margin-top: 5px;">
                                                {{ Str::limit($inquiry->message, 50) }}
                                            </small>
                                            @endif
                                        </div>
                                        <small class="text-muted text-nowrap ms-2">{{ $inquiry->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted text-center py-3">No recent inquiries</p>
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

// Hourly Traffic Chart
const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
const hourlyData = {!! json_encode($hourlyData) !!};
const hourLabels = Array.from({length: 24}, (_, i) => i + ':00');

new Chart(hourlyCtx, {
    type: 'bar',
    data: {
        labels: hourLabels,
        datasets: [{
            label: 'Views per Hour',
            data: hourlyData,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgb(54, 162, 235)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    title: function(context) {
                        return 'Hour: ' + context[0].label;
                    }
                }
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

// Traffic Source Chart
const trafficCtx = document.getElementById('trafficSourceChart').getContext('2d');
const trafficData = {!! json_encode(array_values($trafficSources)) !!};
const trafficLabels = ['Direct', 'Internal', 'External'];

new Chart(trafficCtx, {
    type: 'pie',
    data: {
        labels: trafficLabels,
        datasets: [{
            data: trafficData,
            backgroundColor: [
                'rgb(28, 200, 138)',
                'rgb(78, 115, 223)',
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
