<tbody id="resultsTableBody">
    @forelse($attempts as $attempt)
        <tr>
            <td class="text-center">
                <strong class="text-primary">{{ ($attempts->currentPage() - 1) * $attempts->perPage() + $loop->iteration }}</strong>
            </td>
            <td>
                <strong>{{ $attempt->student->full_name ?? ($attempt->student->first_name . ' ' . $attempt->student->last_name) }}</strong><br>
                <small class="text-muted">{{ $attempt->student->email }}</small>
            </td>
            <td>
                <strong>{{ $attempt->exam->title }}</strong><br>
                <small class="text-muted">Attempt #{{ $attempt->attempt_number }}</small>
            </td>
            <td>
                <strong>{{ $attempt->score }}</strong> / {{ $attempt->exam->total_marks }}
            </td>
            <td>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar {{ $attempt->passed ? 'bg-success' : 'bg-danger' }}"
                         role="progressbar"
                         style="width: {{ $attempt->percentage }}%;"
                         aria-valuenow="{{ $attempt->percentage }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                        {{ number_format($attempt->percentage, 2) }}%
                    </div>
                </div>
            </td>
            <td>
                @if($attempt->passed)
                    <span class="badge bg-success">
                        <i class="fas fa-check"></i> Passed
                    </span>
                @else
                    <span class="badge bg-danger">
                        <i class="fas fa-times"></i> Failed
                    </span>
                @endif
            </td>
            <td>
                @if($attempt->certificate)
                    <span class="badge bg-primary">
                        <i class="fas fa-certificate"></i> Issued
                    </span>
                @else
                    <span class="badge bg-secondary">
                        <i class="fas fa-minus"></i> None
                    </span>
                @endif
            </td>
            <td>
                @if($attempt->certificate_enabled)
                    <span class="badge bg-success">Enabled</span>
                @else
                    <span class="badge bg-secondary">Disabled</span>
                @endif
            </td>
            <td>
                {{ $attempt->created_at->format('Y-m-d') }}<br>
                <small class="text-muted">{{ $attempt->created_at->format('h:i A') }}</small>
            </td>
            <td>
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.exam-results.show', $attempt->id) }}"
                       class="btn btn-sm btn-info" title="View Details">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.exam-results.edit', $attempt->id) }}"
                       class="btn btn-sm btn-warning" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.exam-results.recalculate', $attempt->id) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Are you sure you want to recalculate the score?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary" title="Recalculate">
                            <i class="fas fa-calculator"></i>
                        </button>
                    </form>
                    <form action="{{ route('admin.exam-results.toggle-certificate', $attempt->id) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Toggle certificate access for this student?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success" title="Toggle Certificate Access">
                            <i class="fas fa-certificate"></i>
                        </button>
                    </form>
                    <form action="{{ route('admin.exam-results.destroy', $attempt->id) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this attempt? All answers and certificate will be deleted.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center text-muted">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <p>No results available</p>
            </td>
        </tr>
    @endforelse
</tbody>
