@extends('admin.layouts.app')

@section('title', 'Manual Certificates')
@section('page-title', 'Manual Certificates')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Manual Certificates</li>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<style>
.cert-stats .info-box { border-radius: 10px; box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06); }
.cert-form-card .card-body { padding: 1.5rem; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <span class="badge badge-light mr-3" style="font-size: 1.5rem; padding: 10px 15px; background: rgba(255,255,255,0.3);">5</span>
                        <div class="flex-grow-1">
                            <h3 class="card-title mb-0"><i class="fas fa-award mr-2"></i> Manual Certificates</h3>
                            <small class="text-muted">Add certificates for students without exam completion</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row cert-stats">
                        <div class="col-lg-4">
                            <div class="info-box bg-gradient-primary">
                                <span class="info-box-icon"><i class="fas fa-certificate"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Manual Certificates</span>
                                    <span class="info-box-number">{{ $stats['total'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="info-box bg-gradient-success">
                                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Active</span>
                                    <span class="info-box-number">{{ $stats['active'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="info-box bg-gradient-warning">
                                <span class="info-box-icon"><i class="fas fa-ban"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Inactive</span>
                                    <span class="info-box-number">{{ $stats['inactive'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Certificate Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card cert-form-card border-primary">
                <div class="card-header bg-primary">
                    <h5 class="mb-0"><i class="fas fa-plus mr-2"></i>Add Manual Certificate</h5>
                </div>
                <div class="card-body">
                    <form id="addCertificateForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_id">Student (search by name or email) <span class="text-danger">*</span></label>
                                <select name="student_id" id="student_id" class="form-control select2-student" required>
                                    <option value="">-- Search by name or email --</option>
                                </select>
                                <small class="text-muted">Type to search by student name or email</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="course_title">Course Title <span class="text-danger">*</span></label>
                                <input type="text" name="course_title" id="course_title" class="form-control" required
                                       placeholder="Enter course name (can be any course, not necessarily in system)">
                                <small class="text-muted">Type manually - course does not need to exist in the system</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="certificate_file">Certificate File (PDF/Image) <span class="text-danger">*</span></label>
                                <input type="file" name="certificate_file" id="certificate_file" class="form-control-file" accept=".pdf,.png,.jpg,.jpeg" required>
                                <small class="text-muted">Max 10MB. PDF, PNG, JPG allowed.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="transcript_file">Transcript / Grades Sheet (PDF/Image)</label>
                                <input type="file" name="transcript_file" id="transcript_file" class="form-control-file" accept=".pdf,.png,.jpg,.jpeg">
                                <small class="text-muted">Optional. Max 10MB.</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="issue_date">Issue Date <span class="text-danger">*</span></label>
                                <input type="date" name="issue_date" id="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" checked>
                                    <label for="is_active" class="form-check-label">Active (visible to student)</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-block" id="submitCertBtn">
                                    <i class="fas fa-plus mr-1"></i> Add Certificate
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificates Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list mr-2"></i>Manual Certificates List</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px" class="text-center">#</th>
                                    <th>Certificate #</th>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Issue Date</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 180px">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="certificatesTableBody">
                                @forelse($certificates as $i => $cert)
                                <tr data-id="{{ $cert->id }}">
                                    <td class="text-center"><strong class="text-primary">{{ $certificates->firstItem() + $i }}</strong></td>
                                    <td><code>{{ $cert->certificate_number }}</code></td>
                                    <td>{{ $cert->student->full_name ?? '-' }}</td>
                                    <td>{{ Str::limit($cert->display_course_title ?: '-', 35) }}</td>
                                    <td>{{ $cert->issue_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $cert->is_active ? 'success' : 'secondary' }} cert-status-badge">
                                            {{ $cert->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($cert->certificate_file)
                                            <a href="{{ asset('storage/' . $cert->certificate_file) }}" target="_blank" class="btn btn-sm btn-info" title="View Certificate">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                        @if($cert->transcript_file)
                                            <a href="{{ asset('storage/' . $cert->transcript_file) }}" target="_blank" class="btn btn-sm btn-secondary" title="View Transcript">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-sm {{ $cert->is_active ? 'btn-warning' : 'btn-success' }} toggle-cert-btn" data-id="{{ $cert->id }}" title="{{ $cert->is_active ? 'Disable' : 'Enable' }}">
                                            <i class="fas fa-{{ $cert->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-cert-btn" data-id="{{ $cert->id }}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No manual certificates yet. Add one using the form above.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($certificates->hasPages())
                <div class="card-footer">
                    {{ $certificates->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function() {
    $('.select2-student').select2({
        theme: 'bootstrap4',
        width: '100%',
        ajax: {
            url: '{{ route("admin.certificates.search-students") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) { return { q: params.term }; },
            processResults: function(data) { return data; }
        },
        placeholder: 'Search by name or email...',
        minimumInputLength: 1
    });

    $('#addCertificateForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#submitCertBtn');
        btn.prop('disabled', true).addClass('btn-loading');

        const formData = new FormData(this);

        fetch('{{ route("admin.certificates.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            btn.prop('disabled', false).removeClass('btn-loading');
            if (data.success) {
                toastr.success(data.message);
                $('#addCertificateForm')[0].reset();
                $('.select2-student').val(null).trigger('change');
                $('#course_title').val('');
                location.reload();
            } else {
                toastr.error(data.message || 'Error adding certificate');
            }
        })
        .catch(err => {
            btn.prop('disabled', false).removeClass('btn-loading');
            toastr.error('An error occurred. Please try again.');
        });
    });

    $(document).on('click', '.toggle-cert-btn', function() {
        const btn = $(this);
        const id = btn.data('id');
        btn.prop('disabled', true);

        fetch(`{{ url('admin/certificates/toggle-active') }}/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({})
        })
        .then(r => r.json())
        .then(data => {
            btn.prop('disabled', false);
            if (data.success) {
                toastr.success(data.message);
                const row = btn.closest('tr');
                const badge = row.find('.cert-status-badge');
                badge.removeClass('badge-success badge-secondary')
                    .addClass(data.is_active ? 'badge-success' : 'badge-secondary')
                    .text(data.is_active ? 'Active' : 'Inactive');
                btn.removeClass('btn-warning btn-success')
                    .addClass(data.is_active ? 'btn-warning' : 'btn-success')
                    .find('i').attr('class', 'fas fa-' + (data.is_active ? 'ban' : 'check'));
                btn.attr('title', data.is_active ? 'Disable' : 'Enable');
            } else {
                toastr.error(data.message || 'Error');
            }
        })
        .catch(() => {
            btn.prop('disabled', false);
            toastr.error('An error occurred.');
        });
    });

    $(document).on('click', '.delete-cert-btn', function() {
        if (!confirm('Are you sure you want to delete this certificate?')) return;
        const btn = $(this);
        const id = btn.data('id');

        fetch(`{{ url('admin/certificates/delete') }}/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                btn.closest('tr').fadeOut(300, function() { $(this).remove(); });
                const tbody = $('#certificatesTableBody');
                if (tbody.find('tr').length === 0) {
                    tbody.html('<tr><td colspan="7" class="text-center py-4 text-muted">No manual certificates yet.</td></tr>');
                }
            } else {
                toastr.error(data.message || 'Error');
            }
        })
        .catch(() => toastr.error('An error occurred.'));
    });
});
</script>
@endpush
