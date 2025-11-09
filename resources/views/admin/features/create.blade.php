@extends('admin.layouts.app')

@section('title', 'Create Feature')
@section('page-title', 'Create New Feature')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.features.index') }}">Features</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
    <form action="{{ route('admin.features.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="icon">Icon (FontAwesome Class)</label>
                            <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                   id="icon" name="icon" value="{{ old('icon', 'fas fa-star') }}"
                                   placeholder="e.g. fas fa-chalkboard-teacher">
                            <small class="form-text text-muted">
                                Visit <a href="https://fontawesome.com/icons" target="_blank">FontAwesome</a> to find icons
                            </small>
                            @error('icon')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Feature Settings</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="order">Display Order <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror"
                                   id="order" name="order" value="{{ old('order', 0) }}" min="0" required>
                            <small class="form-text text-muted">Lower numbers appear first</small>
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active"
                                       name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-save"></i> Create Feature
                        </button>
                        <a href="{{ route('admin.features.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
