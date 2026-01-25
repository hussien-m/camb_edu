<tbody id="enrollmentsTableBody">
    @forelse($enrollments as $item)
    @php
    $enrollment = $item['enrollment'];
    $student = $item['student'];
    $course = $item['course'];
    $hasExam = $item['hasExam'];
    $examsCount = $item['examsCount'];
    $enrolledAt = $item['enrolledAt'];
    @endphp
    <tr>
        <td class="text-center">
            <strong class="text-primary">{{ ($enrollments->currentPage() - 1) * $enrollments->perPage() + $loop->iteration }}</strong>
        </td>
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
                <button type="button" 
                        class="btn btn-sm {{ $enrollment->content_disabled ? 'btn-warning' : 'btn-success' }} toggle-content-btn" 
                        data-enrollment-id="{{ $enrollment->id }}"
                        data-disabled="{{ $enrollment->content_disabled ? '1' : '0' }}"
                        title="{{ $enrollment->content_disabled ? 'Enable Content' : 'Disable Content' }}">
                    <i class="fas fa-{{ $enrollment->content_disabled ? 'unlock' : 'lock' }}"></i>
                </button>
                <button type="button" 
                        class="btn btn-sm {{ $enrollment->exam_disabled ? 'btn-danger' : 'btn-success' }} toggle-exam-btn" 
                        data-enrollment-id="{{ $enrollment->id }}"
                        data-disabled="{{ $enrollment->exam_disabled ? '1' : '0' }}"
                        title="{{ $enrollment->exam_disabled ? 'Enable Exams' : 'Disable Exams' }}">
                    <i class="fas fa-{{ $enrollment->exam_disabled ? 'ban' : 'check-circle' }}"></i>
                </button>
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
        <td colspan="6" class="text-center py-5">
            <div class="text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i>
                <div class="h5">No Enrollments Found</div>
                <p class="mb-0">Try adjusting your filters or add new enrollments</p>
            </div>
        </td>
    </tr>
    @endforelse
</tbody>
