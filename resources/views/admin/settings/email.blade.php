@extends('admin.layouts.app')

@section('title', 'Email Configuration')
@section('page-title', 'Email Configuration')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Email Settings</li>
@endsection

@push('css')
<style>
.email-settings-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.08);
}

.email-settings-card .card-header {
    background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
    color: white;
    border-radius: 10px 10px 0 0 !important;
    padding: 1.5rem;
}

.email-settings-card .card-header h3 {
    margin: 0;
    font-weight: 600;
}

.email-settings-card .card-body {
    padding: 2rem;
}

.form-group label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-control {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn-test-email {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
}

.btn-test-email:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    color: white;
}

.btn-save-settings {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
}

.btn-save-settings:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
}

.info-box {
    background: #dbeafe;
    border-left: 4px solid #3b82f6;
    padding: 1rem;
    border-radius: 0 8px 8px 0;
    margin-bottom: 1.5rem;
}

.info-box i {
    color: #3b82f6;
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- SMTP Settings Card -->
        <div class="card email-settings-card">
            <div class="card-header">
                <h3><i class="fas fa-envelope mr-2"></i> SMTP Email Configuration</h3>
            </div>
            <div class="card-body">

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                @endif

                <div class="info-box">
                    <i class="fas fa-info-circle mr-2"></i>
                    Configure your SMTP settings to enable email sending from your application.
                </div>

                <form method="POST" action="{{ route('admin.settings.save') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label><i class="fas fa-server mr-1"></i> SMTP Host</label>
                                <input type="text" name="mail_host" class="form-control"
                                       value="{{ config('mail.mailers.smtp.host') }}"
                                       placeholder="mail.example.com" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="fas fa-plug mr-1"></i> Port</label>
                                <input type="number" name="mail_port" class="form-control"
                                       value="{{ config('mail.mailers.smtp.port') }}"
                                       placeholder="587" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-at mr-1"></i> From Email Address</label>
                                <input type="email" name="mail_from_address" class="form-control"
                                       value="{{ config('mail.from.address') }}"
                                       placeholder="info@example.com" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user mr-1"></i> From Name</label>
                                <input type="text" name="mail_from_name" class="form-control"
                                       value="{{ config('mail.from.name') }}"
                                       placeholder="Your Company Name" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user-circle mr-1"></i> SMTP Username</label>
                                <input type="text" name="mail_username" class="form-control"
                                       value="{{ config('mail.mailers.smtp.username') }}"
                                       placeholder="username@example.com" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-key mr-1"></i> SMTP Password</label>
                                <input type="password" name="mail_password" class="form-control"
                                       value="{{ config('mail.mailers.smtp.password') }}"
                                       placeholder="Enter password">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-test-email" data-toggle="modal" data-target="#testEmailModal">
                            <i class="fas fa-paper-plane mr-1"></i> Send Test Email
                        </button>
                        <button type="submit" class="btn btn-save-settings">
                            <i class="fas fa-save mr-1"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Help Card -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-question-circle mr-2"></i> Help</h5>
            </div>
            <div class="card-body">
                <h6>Common SMTP Ports:</h6>
                <ul class="mb-3">
                    <li><strong>587</strong> - TLS (Recommended)</li>
                    <li><strong>465</strong> - SSL</li>
                    <li><strong>25</strong> - Non-encrypted</li>
                </ul>

                <h6>Testing:</h6>
                <p class="text-muted small">
                    Use the "Send Test Email" button to verify your configuration before saving.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Test Email</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="testEmailForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Recipient Email Address</label>
                        <input type="email" name="test_email" class="form-control"
                               placeholder="Enter email to receive test" required>
                    </div>
                    <div class="form-group">
                        <label>Message (Optional)</label>
                        <textarea name="test_message" class="form-control" rows="3"
                                  placeholder="Add a custom message..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning" id="sendTestBtn">
                        <i class="fas fa-paper-plane mr-1"></i> Send Test
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#testEmailForm').on('submit', function(e) {
        e.preventDefault();

        var btn = $('#sendTestBtn');
        var originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Sending...').prop('disabled', true);

        $.ajax({
            url: '{{ route("admin.settings.test-email") }}',
            method: 'POST',
            timeout: 60000, // 60 seconds timeout
            data: {
                _token: '{{ csrf_token() }}',
                test_email: $('input[name="test_email"]').val(),
                test_message: $('textarea[name="test_message"]').val()
            },
            success: function(response) {
                btn.html(originalText).prop('disabled', false);
                if(response.success) {
                    $('#testEmailModal').modal('hide');

                    // Show detailed success message
                    var successMsg = response.message;
                    if(response.details) {
                        successMsg += '\n\nDetails:\n' +
                            'Sent at: ' + response.details.sent_at + '\n' +
                            'To: ' + response.details.to + '\n' +
                            'From: ' + response.details.from;
                    }

                    if(typeof toastr !== 'undefined') {
                        toastr.success(successMsg);
                    } else {
                        alert('✅ ' + successMsg);
                    }
                } else {
                    showDetailedError(response);
                }
            },
            error: function(xhr, status, error) {
                btn.html(originalText).prop('disabled', false);

                if (status === 'timeout') {
                    var timeoutMsg = '⏱️ Connection Timeout\n\n' +
                        'The email server is taking too long to respond.\n\n' +
                        'Possible causes:\n' +
                        '• SMTP server is slow or unresponsive\n' +
                        '• Port may be blocked by firewall\n' +
                        '• Server is under heavy load\n\n' +
                        'Try:\n' +
                        '1. Check SMTP settings\n' +
                        '2. Try a different port (587, 465, or 25)\n' +
                        '3. Contact your hosting provider';

                    if(typeof toastr !== 'undefined') {
                        toastr.error(timeoutMsg, 'Connection Timeout', {timeOut: 0, extendedTimeOut: 0, closeButton: true});
                    } else {
                        alert('❌ ' + timeoutMsg);
                    }
                } else if (xhr.responseJSON) {
                    showDetailedError(xhr.responseJSON);
                } else {
                    var genericMsg = 'An error occurred while sending the email.\n\nError: ' + error;
                    if(typeof toastr !== 'undefined') {
                        toastr.error(genericMsg);
                    } else {
                        alert('❌ ' + genericMsg);
                    }
                }


                console.error('Email test error:', xhr);
                if(typeof toastr !== 'undefined') {
                    toastr.warning(message);
                } else {
                    alert('⚠️ ' + message);
                }
            }
        });
    });

    // Function to show detailed error
    function showDetailedError(response) {
        var errorMsg = response.message || 'Unknown error occurred';
        var errorHtml = '<div style="text-align: left;">';
        errorHtml += '<strong>' + errorMsg + '</strong><br><br>';

        if(response.error) {
            errorHtml += '<strong>Error:</strong><br>' + response.error + '<br><br>';
        }

        if(response.details) {
            errorHtml += '<strong>Details:</strong><br>';
            errorHtml += 'Type: ' + (response.details.type || 'N/A') + '<br>';

            if(response.details.suggestion) {
                errorHtml += 'Suggestion: ' + response.details.suggestion + '<br>';
            }

            if(response.details.config) {
                errorHtml += '<br><strong>Configuration:</strong><br>';
                for(var key in response.details.config) {
                    errorHtml += key + ': ' + response.details.config[key] + '<br>';
                }
            }
        }

        errorHtml += '</div>';

        if(typeof toastr !== 'undefined') {
            toastr.error(errorHtml, 'Email Test Failed', {
                timeOut: 0,
                extendedTimeOut: 0,
                closeButton: true,
                escapeHtml: false
            });
        } else {
            // Fallback to alert with plain text
            var plainMsg = errorMsg + '\n\n';
            if(response.error) plainMsg += 'Error: ' + response.error + '\n\n';
            if(response.details && response.details.suggestion) {
                plainMsg += 'Suggestion: ' + response.details.suggestion;
            }
            alert('❌ ' + plainMsg);
        }
    }
});
</script>
@endpush
