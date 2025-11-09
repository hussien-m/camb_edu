@extends('admin.layouts.app')

@section('title', 'Edit Course')
@section('page-title', 'Edit Course')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Courses</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $course->title) }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                   id="slug" name="slug" value="{{ old('slug', $course->slug) }}">
                            @error('slug')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="short_description">Short Description</label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror"
                                      id="short_description" name="short_description" rows="3">{{ old('short_description', $course->short_description) }}</textarea>
                            @error('short_description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Full Description</label>
                            <textarea class="form-control tinymce @error('description') is-invalid @enderror"
                                      id="description" name="description">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Course Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select class="form-control @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="level_id">Level</label>
                            <select class="form-control @error('level_id') is-invalid @enderror"
                                    id="level_id" name="level_id">
                                <option value="">Select Level</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}"
                                        {{ old('level_id', $course->level_id) == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('level_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="duration">Duration</label>
                            <input type="text" class="form-control @error('duration') is-invalid @enderror"
                                   id="duration" name="duration" value="{{ old('duration', $course->duration) }}">
                            @error('duration')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="mode">Mode</label>
                            <select class="form-control @error('mode') is-invalid @enderror"
                                    id="mode" name="mode">
                                <option value="">Select Mode</option>
                                <option value="online" {{ old('mode', $course->mode) == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="offline" {{ old('mode', $course->mode) == 'offline' ? 'selected' : '' }}>Offline</option>
                                <option value="hybrid" {{ old('mode', $course->mode) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                            </select>
                            @error('mode')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="fee">Fee ($)</label>
                            <input type="number" step="0.01" class="form-control @error('fee') is-invalid @enderror"
                                   id="fee" name="fee" value="{{ old('fee', $course->fee) }}">
                            @error('fee')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="image">Course Image</label>
                            @if($course->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}"
                                         class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                       id="image" name="image" accept="image/*">
                                <label class="custom-file-label" for="image">Choose file</label>
                            </div>
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div id="imagePreview" class="mt-2"></div>
                        </div>

                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                <option value="active" {{ old('status', $course->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $course->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_featured"
                                       name="is_featured" {{ old('is_featured', $course->is_featured) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_featured">Featured Course</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Update Course
                        </button>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        .ck-editor__editable {
            min-height: 400px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
    <script>
        // CKEditor with Image Upload Support
        class UploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return this.loader.file.then(file => new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('upload', file);
                    data.append('_token', '{{ csrf_token() }}');

                    fetch('{{ route("admin.upload.image") }}', {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(result => {
                        resolve({
                            default: result.url
                        });
                    })
                    .catch(error => {
                        reject(error);
                    });
                }));
            }
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new UploadAdapter(loader);
            };
        }

        ClassicEditor
            .create(document.querySelector('#description'), {
                extraPlugins: [MyCustomUploadAdapterPlugin],
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'uploadImage', 'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                    ]
                },
                image: {
                    toolbar: [
                        'imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'
                    ]
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                }
            })
            .catch(error => {
                console.error(error);
            });

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
