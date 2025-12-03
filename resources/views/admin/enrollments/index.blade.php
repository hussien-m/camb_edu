@extends('admin.layouts.app')

@section('title', 'Enrollments')
@section('page-title', 'Student Enrollments')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Enrollments</li>
@endsection

@push('styles')
<!-- Select2 for Bootstrap 4 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

<!-- DataTables for Bootstrap 4 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css" />

<style>
/* Minor customizations to integrate AdminLTE look & feel */
.enrollment-stats .info-box {
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
}

.student-avatar {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    object-fit: cover;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
}

.table-responsive {
    margin-bottom: 0;
}

/* status badges */
.badge-status {
    font-weight: 700;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    font-size: 0.75rem;
}

/* improve filters card */
.filters-card .card-body {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: end;
}

.filters-card .form-group {
    margin-bottom: 0;
    min-width: 220px;
    flex: 1;
}

@media (max-width: 768px) {
    .filters-card .card-body {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- HEADER / TITLE -->
        <div class="col-12 mb-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-book-reader mr-2"></i> Enrollments</h3>
                    <div class="card-tools">
                        <small class="text-muted">Manage and monitor student enrollments and exam status</small>
                    </div>
                </div>
                <div class="card-body p-3">
                    <!-- STATS: use AdminLTE info-box -->
                    <div class="row enrollment-stats">
                        <div class="col-md-4 col-sm-6">
                            <div class="info-box bg-white">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Enrollments</span>
                                    <span class="info-box-number h3 mb-0">{{ $enrollments->total() }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class="info-box bg-white">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">With Active Exams</span>
                                    <span class="info-box-number h3 mb-0">{{ $enrollments->getCollection()->filter(fn($item) => $item['hasExam'])->count() }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class="info-box bg-white">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Missing Exams</span>
                                    <span class="info-box-number h3 mb-0">{{ $enrollments->getCollection()->filter(fn($item) => !$item['hasExam'])->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTERS -->
        <div class="col-12">
            <div class="card filters-card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-filter mr-2"></i> Advanced Filters</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" id="filterForm" action="{{ route('admin.enrollments.index') }}" class="w-100 d-flex flex-wrap">
                        <div class="form-group">
                            <label for="studentSearch">Search Student</label>
                            <div class="input-group">
                                <input id="studentSearch" type="text" name="student" class="form-control" placeholder="Name or email..." value="{{ request('student') }}">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="levelSelect">Level / Stage</label>
                            <select name="level_id" id="levelSelect" class="form-control select2-basic">
                                <option value="">All Levels</option>
                                @foreach($levels ?? [] as $level)
                                <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="examSelect">Exam Status</label>
                            <select name="exam_status" id="examSelect" class="form-control select2-basic">
                                <option value="">All Status</option>
                                <option value="has_exam" {{ request('exam_status') == 'has_exam' ? 'selected' : '' }}>Has Exam</option>
                                <option value="no_exam" {{ request('exam_status') == 'no_exam' ? 'selected' : '' }}>Missing Exam</option>
                            </select>
                        </div>

                        <div class="form-group ml-auto d-flex align-items-center gap-2">
                            <a href="{{ route('admin.enrollments.index') }}" class="btn btn-outline-secondary"><i class="fas fa-redo"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="col-12">
            <div class="card card-primary card-outline table-card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list"></i> Enrollment List</h3>
                    <div class="card-tools">
                        <span class="text-muted me-3">Showing {{ $enrollments->count() }} of {{ $enrollments->total() }}</span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table id="enrollmentsTable" class="table table-hover table-striped table-valign-middle w-100">
                        <thead class="bg-primary">
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Enrolled Date</th>
                                <th>Exam Status</th>
                                <th style="width:120px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $item)
                            @php
                            $student = $item['student'];
                            $course = $item['course'];
                            $hasExam = $item['hasExam'];
                            $examsCount = $item['examsCount'];
                            $enrolledAt = $item['enrolledAt'];
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->full_name) }}&size=64&background=3b82f6&color=fff&bold=true" alt="{{ $student->full_name }}" class="student-avatar mr-3">
                                        <div>
                                            <div class="font-weight-bold">{{ $student->full_name }}</div>
                                            <div class="text-muted small">{{ $student->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="font-weight-bold">{{ $course->title }}</div>
                                    <div><span class="badge badge-light">{{ $course->level?->name ?? 'N/A' }}</span></div>
                                </td>

                                <td>
                                    <div class="font-weight-bold">{{ $enrolledAt->format('M d, Y') }}</div>
                                    <div class="text-muted small">{{ $enrolledAt->diffForHumans() }}</div>
                                </td>

                                <td>
                                    @if($hasExam)
                                    <span class="badge badge-success badge-status"><i class="fas fa-check mr-1"></i> {{ $examsCount }} Active</span>
                                    @else
                                    <span class="badge badge-danger badge-status"><i class="fas fa-times mr-1"></i> No Exam</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="btn-group">
                                        @if(!$hasExam)
                                        <a href="{{ route('admin.exams.create') }}?course_id={{ $course->id }}" class="btn btn-sm btn-warning" title="Add Exam"><i class="fas fa-plus"></i></a>
                                        @else
                                        <a href="{{ route('admin.exams.index') }}?course_id={{ $course->id }}" class="btn btn-sm btn-info" title="View Exams"><i class="fas fa-eye"></i></a>
                                        @endif
                                        <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-sm btn-primary" title="Edit Course"><i class="fas fa-edit"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <div class="h5">No Enrollments Found</div>
                                        <p class="mb-0">Try adjusting your filters or add new enrollments</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($enrollments->hasPages())
                <div class="card-footer clearfix">
                    <div class="float-left">
                        {{ $enrollments->links() }}
                    </div>
                    <div class="float-right text-muted">
                        Page {{ $enrollments->currentPage() }} of {{ $enrollments->lastPage() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- DataTables + Buttons -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select2 init
    $('.select2-basic').select2({
        theme: 'bootstrap4',
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 6
    });

    // Auto-submit when selects change (keeps student input manual via button)
    $('#levelSelect, #examSelect').on('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Initialize DataTable
    if (typeof $.fn.DataTable !== 'undefined') {
        setTimeout(function() {
            if ($.fn.DataTable.isDataTable('#enrollmentsTable')) {
                $('#enrollmentsTable').DataTable().destroy();
            }
            
            $('#enrollmentsTable').DataTable({
                destroy: true,
                paging: false,
                info: false,
                searching: false,
                ordering: true,
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    { orderable: false, targets: 4 }
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-sm btn-outline-secondary',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-sm btn-outline-success',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-sm btn-outline-danger',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-sm btn-outline-primary',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    }
                ],
                initComplete: function() {
                    $('.dt-buttons').addClass('float-left mt-2').prependTo('#enrollmentsTable_wrapper .row:first');
                }
            });
        }, 200);
    }
});
</script>
@endpush
