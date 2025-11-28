@extends('admin.layouts.app')

@section('title', 'Inquiry Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inquiry Details</h1>
        <a href="{{ route('admin.inquiries.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Message</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Subject:</strong> Inquiry about {{ $inquiry->course->title }}
                    </div>
                    <div class="mb-3">
                        <strong>Message:</strong>
                        <div class="border rounded p-3 mt-2 bg-light">
                            {{ $inquiry->message }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Name:</strong><br>
                        {{ $inquiry->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong><br>
                        <a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a>
                    </div>
                    @if($inquiry->phone)
                    <div class="mb-3">
                        <strong>Phone:</strong><br>
                        <a href="tel:{{ $inquiry->phone }}">{{ $inquiry->phone }}</a>
                    </div>
                    @endif
                    <div class="mb-3">
                        <strong>Date:</strong><br>
                        {{ $inquiry->created_at->format('F d, Y - H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        <form action="{{ route('admin.inquiries.update-status', $inquiry) }}" method="POST">
                            @csrf
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="new" {{ $inquiry->status == 'new' ? 'selected' : '' }}>New</option>
                                <option value="contacted" {{ $inquiry->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="closed" {{ $inquiry->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Course Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Course:</strong><br>
                        <a href="{{ route('courses.show', [$inquiry->course->category->slug, $inquiry->course->level->slug, $inquiry->course->slug]) }}"
                           target="_blank" class="text-primary">
                            {{ $inquiry->course->title }}
                        </a>
                    </div>
                    <div class="mb-3">
                        <strong>Category:</strong><br>
                        {{ $inquiry->course->category->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Level:</strong><br>
                        {{ $inquiry->course->level->name }}
                    </div>
                    @if($inquiry->course->fee > 0)
                    <div class="mb-3">
                        <strong>Fee:</strong><br>
                        {{ number_format($inquiry->course->fee) }} LYD
                    </div>
                    @endif
                </div>
            </div>

            <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this inquiry?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-trash"></i> Delete Inquiry
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
