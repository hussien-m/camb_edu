@extends('admin.layouts.app')

@section('title', 'Edit Banner')
@section('page-title', 'Edit Banner')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.banners.index') }}">Banners</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $banner->title) }}">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="subtitle">Subtitle</label>
                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                                   id="subtitle" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}">
                            @error('subtitle')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="link">Link URL</label>
                            <input type="url" class="form-control @error('link') is-invalid @enderror"
                                   id="link" name="link" value="{{ old('link', $banner->link) }}"
                                   placeholder="https://example.com">
                            @error('link')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="image">Banner Image</label>
                            @if($banner->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}"
                                         class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                       id="image" name="image" accept="image/*">
                                <label class="custom-file-label" for="image">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Recommended size: 1920x600 pixels</small>
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div id="imagePreview" class="mt-2"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">Display Order</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror"
                                           id="order" name="order" value="{{ old('order', $banner->order) }}" min="0">
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                    @error('order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="is_active"
                                               name="is_active" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Banner
                        </button>
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').innerHTML =
                        '<img src="' + e.target.result + '" class="img-thumbnail" style="max-height: 200px;">';
                };
                reader.readAsDataURL(file);
            }
        });

        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
    </script>
@endpush
