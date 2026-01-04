@extends('admin.layouts.app')

@section('title', 'Edit Ad')
@section('page-title', 'Edit Ad')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.ads.index') }}">Ads</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <form action="{{ route('admin.ads.update', $ad) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $ad->title) }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Ad Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="banner" {{ old('type', $ad->type) == 'banner' ? 'selected' : '' }}>Banner</option>
                                        <option value="sidebar" {{ old('type', $ad->type) == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                                        <option value="inline" {{ old('type', $ad->type) == 'inline' ? 'selected' : '' }}>Inline</option>
                                        <option value="popup" {{ old('type', $ad->type) == 'popup' ? 'selected' : '' }}>Popup</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="position">Position <span class="text-danger">*</span></label>
                                    <select class="form-control @error('position') is-invalid @enderror" id="position" name="position" required>
                                        <option value="">Select Position</option>
                                        <option value="top" {{ old('position', $ad->position) == 'top' ? 'selected' : '' }}>Top</option>
                                        <option value="middle" {{ old('position', $ad->position) == 'middle' ? 'selected' : '' }}>Middle</option>
                                        <option value="bottom" {{ old('position', $ad->position) == 'bottom' ? 'selected' : '' }}>Bottom</option>
                                        <option value="sidebar-left" {{ old('position', $ad->position) == 'sidebar-left' ? 'selected' : '' }}>Sidebar Left</option>
                                        <option value="sidebar-right" {{ old('position', $ad->position) == 'sidebar-right' ? 'selected' : '' }}>Sidebar Right</option>
                                    </select>
                                    @error('position')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">Display Order</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror"
                                           id="order" name="order" value="{{ old('order', $ad->order) }}" min="0">
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                    @error('order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description', $ad->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="link">Link URL</label>
                            <input type="url" class="form-control @error('link') is-invalid @enderror"
                                   id="link" name="link" value="{{ old('link', $ad->link) }}"
                                   placeholder="https://example.com">
                            @error('link')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Ad Image</label>
                                    @if($ad->image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->title }}"
                                                 class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    @endif
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                               id="image" name="image" accept="image/*">
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Max size: 5MB (JPEG, PNG, GIF, WebP)</small>
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <div id="imagePreview" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="html_content">Or HTML Content</label>
                                    <textarea class="form-control @error('html_content') is-invalid @enderror"
                                              id="html_content" name="html_content" rows="5"
                                              placeholder="Enter custom HTML code for the ad...">{{ old('html_content', $ad->html_content) }}</textarea>
                                    <small class="form-text text-muted">Use this for custom HTML ads (Google AdSense, etc.)</small>
                                    @error('html_content')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror"
                                           id="start_date" name="start_date" 
                                           value="{{ old('start_date', $ad->start_date ? $ad->start_date->format('Y-m-d\TH:i') : '') }}">
                                    <small class="form-text text-muted">Leave empty to start immediately</small>
                                    @error('start_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror"
                                           id="end_date" name="end_date"
                                           value="{{ old('end_date', $ad->end_date ? $ad->end_date->format('Y-m-d\TH:i') : '') }}">
                                    <small class="form-text text-muted">Leave empty for no expiration</small>
                                    @error('end_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" id="open_in_new_tab"
                                               name="open_in_new_tab" {{ old('open_in_new_tab', $ad->open_in_new_tab) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="open_in_new_tab">Open link in new tab</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" id="is_active"
                                               name="is_active" {{ old('is_active', $ad->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <strong>Statistics:</strong><br>
                            Views: <strong>{{ number_format($ad->views_count) }}</strong> | 
                            Clicks: <strong>{{ number_format($ad->clicks_count) }}</strong>
                            @if($ad->clicks_count > 0)
                                | CTR: <strong>{{ number_format(($ad->clicks_count / max($ad->views_count, 1)) * 100, 2) }}%</strong>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Ad
                        </button>
                        <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">
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

