@extends('admin.layouts.app')

@section('title', 'Verification Reminders')
@section('page-title', 'Email Verification Reminders')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">Verification Reminders</li>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Unverified</span>
                    <span class="info-box-number">{{ $totalUnverified }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Last 3 Days</span>
                    <span class="info-box-number">{{ $unverifiedLast3Days }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-calendar-week"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Last 7 Days</span>
                    <span class="info-box-number">{{ $unverifiedLast7Days }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card mb-4">
        <div class="card-header bg-gradient-primary">
            <h3 class="card-title"><i class="fas fa-paper-plane mr-2"></i>Send Reminders</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <form method="POST" action="{{ route('admin.verification-reminders.send-all') }}" onsubmit="return confirm('Are you sure you want to send reminders to ALL unverified students?');">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-paper-plane mr-2"></i>Send to All Unverified
                        </button>
                    </form>
                </div>
                <div class="col-md-4">
                    <form method="POST" action="{{ route('admin.verification-reminders.send-recent') }}" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        <div class="input-group">
                            <input type="number" name="days" class="form-control" placeholder="Days" value="3" min="1" max="30" required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-paper-plane mr-2"></i>Send to Recent
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">Send to students registered in the last N days</small>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Auto Reminders:</strong> Sent automatically every 3 days via scheduled task.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unverified Students List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Unverified Students</h3>
            <div class="card-tools">
                <span class="badge badge-warning">{{ $unverifiedStudents->total() }} Total</span>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show">
                    {{ session('info') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @if($unverifiedStudents->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>No unverified students found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Country</th>
                                <th>Registered</th>
                                <th>Days Since</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unverifiedStudents as $student)
                                <tr>
                                    <td>{{ $student->id }}</td>
                                    <td>
                                        <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->phone ?? 'N/A' }}</td>
                                    <td>{{ $student->country ?? 'N/A' }}</td>
                                    <td>{{ $student->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-warning">
                                            {{ $student->created_at->diffInDays(now()) }} days
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.verification-reminders.send-student', $student) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary" title="Send Reminder">
                                                <i class="fas fa-paper-plane"></i> Send Reminder
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $unverifiedStudents->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

