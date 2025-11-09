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
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
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
@endsection
