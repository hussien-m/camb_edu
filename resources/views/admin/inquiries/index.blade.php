@extends('admin.layouts.app')

@section('title', 'Course Inquiries')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Course Inquiries</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Inquiries ({{ $inquiries->total() }})</h6>
        </div>
        <!-- Search and Filters -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.inquiries.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone, or course..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
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
                    <div class="col-12">
                        <a href="{{ route('admin.inquiries.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-times"></i> Clear Filters
                        </a>
                    </div>
                @endif
            </form>
        </div>
        <div class="card-body">
            @if($inquiries->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Course</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inquiries as $inquiry)
                            <tr>
                                <td>{{ $inquiry->id }}</td>
                                <td>{{ $inquiry->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('courses.show', [$inquiry->course->category->slug, $inquiry->course->level->slug, $inquiry->course->slug]) }}"
                                       target="_blank" class="text-primary">
                                        {{ Str::limit($inquiry->course->title, 40) }}
                                    </a>
                                </td>
                                <td>{{ $inquiry->name }}</td>
                                <td>
                                    <a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a>
                                </td>
                                <td>{{ $inquiry->phone ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('admin.inquiries.update-status', $inquiry) }}" method="POST" class="d-inline">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm"
                                                onchange="this.form.submit()"
                                                style="width: auto;">
                                            <option value="new" {{ $inquiry->status == 'new' ? 'selected' : '' }}>
                                                New
                                            </option>
                                            <option value="contacted" {{ $inquiry->status == 'contacted' ? 'selected' : '' }}>
                                                Contacted
                                            </option>
                                            <option value="closed" {{ $inquiry->status == 'closed' ? 'selected' : '' }}>
                                                Closed
                                            </option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.inquiries.show', $inquiry) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.inquiries.destroy', $inquiry) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this inquiry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $inquiries->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No inquiries found.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
