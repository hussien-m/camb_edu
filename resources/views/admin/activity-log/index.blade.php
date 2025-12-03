@extends('admin.layouts.app')

@section('title', 'Activity Log')
@section('page-title', 'Activity Log')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Activity Log</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Activities</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 150px">Date & Time</th>
                        <th>Admin</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>Model ID</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        <tr>
                            <td>
                                <small>{{ $activity->created_at->format('M d, Y') }}<br>{{ $activity->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>{{ $activity->admin->name ?? 'System' }}</td>
                            <td>
                                <span class="badge badge-{{ $activity->action === 'created' ? 'success' : ($activity->action === 'updated' ? 'info' : 'danger') }}">
                                    {{ ucfirst($activity->action) }}
                                </span>
                            </td>
                            <td>{{ $activity->model }}</td>
                            <td>{{ $activity->model_id ?? 'N/A' }}</td>
                            <td><small class="text-muted">{{ $activity->ip_address ?? 'N/A' }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No activities found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <small class="text-muted">Showing last 50 activities</small>
        </div>
    </div>
@endsection

