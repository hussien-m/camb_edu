@extends('admin.layouts.app')

@section('title', 'Edit Student')
@section('page-title', 'Edit Student')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Student Information</h3>
                </div>
                <form action="{{ route('admin.students.update', $student) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                           value="{{ old('first_name', $student->first_name) }}" required>
                                    @error('first_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                           value="{{ old('last_name', $student->last_name) }}" required>
                                    @error('last_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $student->email) }}" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $student->phone) }}">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                                           value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}">
                                    @error('date_of_birth')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Country</label>
                                    <select name="country" class="form-control @error('country') is-invalid @enderror">
                                        <option value="">Select Country</option>
                                        <option value="Libya" {{ old('country', $student->country) == 'Libya' ? 'selected' : '' }}>Libya</option>
                                        <option value="Egypt" {{ old('country', $student->country) == 'Egypt' ? 'selected' : '' }}>Egypt</option>
                                        <option value="Tunisia" {{ old('country', $student->country) == 'Tunisia' ? 'selected' : '' }}>Tunisia</option>
                                        <option value="Algeria" {{ old('country', $student->country) == 'Algeria' ? 'selected' : '' }}>Algeria</option>
                                        <option value="Morocco" {{ old('country', $student->country) == 'Morocco' ? 'selected' : '' }}>Morocco</option>
                                        <option value="Other" {{ old('country', $student->country) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('country')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ old('status', $student->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Update Student
                        </button>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> {{ $student->id }}</p>
                    <p><strong>Registered:</strong> {{ $student->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Last Updated:</strong> {{ $student->updated_at->format('M d, Y H:i') }}</p>
                    <p><strong>Last Login:</strong>
                        @if($student->last_login_at)
                            {{ $student->last_login_at->format('M d, Y H:i') }}
                        @else
                            <span class="text-muted">Never</span>
                        @endif
                    </p>
                    <p><strong>Login IP:</strong>
                        <span class="text-monospace">{{ $student->last_login_ip ?? 'N/A' }}</span>
                    </p>
                    <p><strong>Current Status:</strong>
                        @if($student->status === 'active')
                            <span class="badge badge-success">Active</span>
                        @elseif($student->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
