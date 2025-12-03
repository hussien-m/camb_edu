@extends('admin.layouts.app')

@section('title', 'Messages')
@section('page-title', 'Contact Messages')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Messages</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Messages</h3>
            <div class="card-tools">
                <a href="{{ route('admin.export.contacts') }}" class="btn btn-success btn-sm mr-2">
                    <i class="fas fa-download"></i> Export CSV
                </a>
                <div class="btn-group">
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-default' }}">
                        All
                    </a>
                    <a href="{{ route('admin.contacts.index', ['status' => 'unread']) }}" class="btn btn-sm {{ request('status') === 'unread' ? 'btn-warning' : 'btn-default' }}">
                        Unread
                    </a>
                    <a href="{{ route('admin.contacts.index', ['status' => 'read']) }}" class="btn btn-sm {{ request('status') === 'read' ? 'btn-success' : 'btn-default' }}">
                        Read
                    </a>
                </div>
            </div>
        </div>
        <!-- Search and Filters -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.contacts.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, subject..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort_order" class="form-control">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Newest First</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
                @if(request()->hasAny(['search', 'status', 'sort_by', 'sort_order']))
                    <div class="col-md-2">
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                @endif
            </form>
        </div>
        <div class="card-body">
            <!-- Bulk Actions -->
            <form id="bulkActionForm" method="POST" action="{{ route('admin.bulk.contacts') }}" style="display: none;">
                @csrf
                <div class="mb-3">
                    <select name="action" class="form-control form-control-sm d-inline-block" style="width: auto;">
                        <option value="">Select Action</option>
                        <option value="delete">Delete Selected</option>
                        <option value="mark_read">Mark as Read</option>
                        <option value="mark_unread">Mark as Unread</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="clearSelection()">Clear</button>
                </div>
                <input type="hidden" name="ids" id="bulkIds">
            </form>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 30px">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th style="width: 20px"></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th style="width: 150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr class="{{ !$contact->is_read ? 'font-weight-bold' : '' }}">
                            <td>
                                <input type="checkbox" class="row-checkbox" value="{{ $contact->id }}">
                            </td>
                            <td class="text-center">
                                @if(!$contact->is_read)
                                    <i class="fas fa-circle text-warning" style="font-size: 8px;"></i>
                                @endif
                            </td>
                            <td>{{ $contact->name ?? 'Anonymous' }}</td>
                            <td>{{ $contact->email ?? 'N/A' }}</td>
                            <td>{{ $contact->subject ?? 'No subject' }}</td>
                            <td>{{ $contact->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                                          onsubmit="return confirm('Are you sure?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No messages found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $contacts->links('vendor.pagination.adminlte') }}
        </div>
    </div>

@push('scripts')
<script>
    // Select All checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActions();
    });

    // Individual checkboxes
    document.querySelectorAll('.row-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const bulkForm = document.getElementById('bulkActionForm');

        if (checked.length > 0) {
            bulkForm.style.display = 'block';
            const ids = Array.from(checked).map(cb => cb.value);
            document.getElementById('bulkIds').value = JSON.stringify(ids);
        } else {
            bulkForm.style.display = 'none';
        }
    }

    function clearSelection() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('selectAll').checked = false;
        updateBulkActions();
    }

    // Bulk form submission
    document.getElementById('bulkActionForm')?.addEventListener('submit', function(e) {
        const action = this.querySelector('select[name="action"]').value;
        if (!action) {
            e.preventDefault();
            alert('Please select an action');
            return false;
        }
        if (!confirm(`Are you sure you want to ${action.replace('_', ' ')} the selected items?`)) {
            e.preventDefault();
            return false;
        }
    });
</script>
@endpush
@endsection
