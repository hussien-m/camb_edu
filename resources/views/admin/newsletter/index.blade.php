@extends('admin.layouts.app')

@section('title', 'Newsletter Subscribers')
@section('page-title', 'Newsletter Subscribers')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Newsletter Subscribers</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Newsletter Subscribers</h3>
            <div class="card-tools">
                <a href="{{ route('admin.newsletter.export') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        </div>
        <!-- Search and Filters -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.newsletter.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="sort_by" class="form-control">
                        <option value="subscribed_at" {{ request('sort_by') == 'subscribed_at' ? 'selected' : '' }}>Subscription Date</option>
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
                @if(request()->hasAny(['search', 'sort_by', 'sort_order']))
                    <div class="col-md-2">
                        <a href="{{ route('admin.newsletter.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                @endif
            </form>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 60px">ID</th>
                        <th>Email</th>
                        <th>Subscribed At</th>
                        <th style="width: 100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscribers as $subscriber)
                        <tr>
                            <td>{{ $subscriber->id }}</td>
                            <td>{{ $subscriber->email }}</td>
                            <td>{{ $subscriber->subscribed_at->format('M d, Y H:i') }}</td>
                            <td>
                                <form action="{{ route('admin.newsletter.destroy', $subscriber->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this subscriber?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No subscribers found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $subscribers->links('vendor.pagination.adminlte') }}
        </div>
    </div>
@endsection
