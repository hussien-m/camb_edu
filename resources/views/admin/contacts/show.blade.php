@extends('admin.layouts.app')

@section('title', 'View Message')
@section('page-title', 'Message Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}">Messages</a></li>
    <li class="breadcrumb-item active">View</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $contact->subject ?? 'No Subject' }}
                        @if($contact->is_read)
                            <span class="badge badge-success ml-2">Read</span>
                        @else
                            <span class="badge badge-warning ml-2">Unread</span>
                        @endif
                    </h3>
                    <div class="card-tools">
                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                              onsubmit="return confirm('Are you sure?');" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>From:</strong><br>
                            {{ $contact->name ?? 'Anonymous' }}<br>
                            @if($contact->email)
                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a><br>
                            @endif
                            @if($contact->phone)
                                <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                            @endif
                        </div>
                        <div class="col-md-6 text-right">
                            <strong>Date:</strong><br>
                            {{ $contact->created_at->format('F d, Y') }}<br>
                            {{ $contact->created_at->format('h:i A') }}
                        </div>
                    </div>

                    <hr>

                    <div class="message-content">
                        <h5>Message:</h5>
                        <p style="white-space: pre-wrap;">{{ $contact->message }}</p>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Messages
                    </a>
                    @if($contact->email)
                        <a href="mailto:{{ $contact->email }}" class="btn btn-primary">
                            <i class="fas fa-reply"></i> Reply via Email
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
