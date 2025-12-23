@extends('admin.layouts.app')

@section('title', 'Exam Reminders')
@section('page-title', 'Exam Reminders Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Exam Reminders</li>
@endsection

@section('content')
<div class="container-fluid">
    
    <!-- Time Diagnostics -->
    <div class="alert alert-info">
        <h5><i class="fas fa-clock mr-2"></i>Time Diagnostics</h5>
        <div class="row">
            <div class="col-md-3">
                <strong>Server Time:</strong><br>
                <span class="badge badge-primary">{{ $timeDiagnostics['server_time'] }}</span>
            </div>
            <div class="col-md-3">
                <strong>Laravel Timezone:</strong><br>
                <span class="badge badge-success">{{ $timeDiagnostics['laravel_timezone'] }}</span>
            </div>
            <div class="col-md-3">
                <strong>Database Time:</strong><br>
                <span class="badge badge-warning">{{ $timeDiagnostics['db_time'] }}</span>
            </div>
            <div class="col-md-3">
                <strong>PHP Timezone:</strong><br>
                <span class="badge badge-info">{{ $timeDiagnostics['php_timezone'] }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3>{{ $stats['total'] }}</h3>
                    <p class="mb-0">Total Reminders</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h3>{{ $stats['due'] }}</h3>
                    <p class="mb-0">Due Now (Must Send)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h3>{{ $stats['pending'] }}</h3>
                    <p class="mb-0">Pending (Future)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h3>{{ $stats['sent'] }}</h3>
                    <p class="mb-0">Already Sent</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-cogs mr-2"></i>Manual Actions</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <form action="{{ route('admin.exam-reminders.create') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-plus mr-2"></i>Create Reminders
                        </button>
                    </form>
                    <small class="text-muted">Create reminders for upcoming exams</small>
                </div>

                <div class="col-md-3 mb-2">
                    <form action="{{ route('admin.exam-reminders.send') }}" method="POST" class="d-inline" 
                          onsubmit="return confirm('Send all due reminders now?');">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-paper-plane mr-2"></i>Send Due Reminders
                        </button>
                    </form>
                    <small class="text-muted">Send reminders that are due now ({{ $stats['due'] }})</small>
                </div>

                <div class="col-md-3 mb-2">
                    <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#testEmailModal">
                        <i class="fas fa-envelope mr-2"></i>Test Email
                    </button>
                    <small class="text-muted">Send a test email</small>
                </div>

                <div class="col-md-3 mb-2">
                    <form action="{{ route('admin.exam-reminders.delete-unsent') }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Delete ALL unsent reminders? This cannot be undone!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash mr-2"></i>Delete Unsent
                        </button>
                    </form>
                    <small class="text-muted">Delete all pending reminders</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Due Reminders -->
    @if($dueReminders->isNotEmpty())
    <div class="card mb-4 border-danger">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-exclamation-triangle mr-2"></i>Due Reminders (Should Send Now!)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Email</th>
                            <th>Exam</th>
                            <th>Type</th>
                            <th>Scheduled For</th>
                            <th>Overdue By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dueReminders as $reminder)
                        <tr>
                            <td>{{ $reminder->id }}</td>
                            <td>{{ $reminder->student->full_name }}</td>
                            <td>{{ $reminder->student->email }}</td>
                            <td>{{ $reminder->exam->title }}</td>
                            <td><span class="badge badge-warning">{{ $reminder->getReminderLabel() }}</span></td>
                            <td>{{ $reminder->scheduled_for->format('Y-m-d H:i:s') }}</td>
                            <td class="text-danger">{{ $reminder->scheduled_for->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Upcoming Reminders -->
    @if($upcomingReminders->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-clock mr-2"></i>Upcoming Reminders</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Exam</th>
                            <th>Type</th>
                            <th>Scheduled For</th>
                            <th>Time Until</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingReminders as $reminder)
                        <tr>
                            <td>{{ $reminder->id }}</td>
                            <td>{{ $reminder->student->full_name }}</td>
                            <td>{{ $reminder->exam->title }}</td>
                            <td><span class="badge badge-info">{{ $reminder->getReminderLabel() }}</span></td>
                            <td>{{ $reminder->scheduled_for->format('Y-m-d H:i:s') }}</td>
                            <td class="text-primary">{{ $reminder->scheduled_for->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Sent -->
    @if($recentSent->isNotEmpty())
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-check-circle mr-2"></i>Recently Sent Reminders</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Exam</th>
                            <th>Type</th>
                            <th>Sent At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSent as $reminder)
                        <tr>
                            <td>{{ $reminder->id }}</td>
                            <td>{{ $reminder->student->full_name }}</td>
                            <td>{{ $reminder->exam->title }}</td>
                            <td><span class="badge badge-success">{{ $reminder->getReminderLabel() }}</span></td>
                            <td>{{ $reminder->sent_at->format('Y-m-d H:i:s') }} ({{ $reminder->sent_at->diffForHumans() }})</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    @if($dueReminders->isEmpty() && $upcomingReminders->isEmpty() && $recentSent->isEmpty())
    <div class="alert alert-info text-center">
        <h5>No Reminders Found</h5>
        <p>Click "Create Reminders" to generate reminders for scheduled exams.</p>
    </div>
    @endif

</div>

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Test Email</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.exam-reminders.test-email') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="test_email">Email Address</label>
                        <input type="email" class="form-control" id="test_email" name="email" required 
                               placeholder="your-email@example.com">
                        <small class="text-muted">A test email will be sent to this address</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Test Email</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh every 60 seconds
setTimeout(function() {
    location.reload();
}, 60000);
</script>
@endpush

