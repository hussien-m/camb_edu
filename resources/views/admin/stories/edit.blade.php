@extends('admin.layouts.app')

@section('title', 'Edit Success Story')
@section('page-title', 'Edit Success Story')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.stories.index') }}">Success Stories</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <form action="{{ route('admin.stories.update', $story) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="student_name">Student Name</label>
                            <input type="text" class="form-control @error('student_name') is-invalid @enderror"
                                   id="student_name" name="student_name" value="{{ old('student_name', $story->student_name) }}">
                            @error('student_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $story->title) }}">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="story">Story <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('story') is-invalid @enderror"
                                      id="story" name="story" required>{{ old('story', $story->story) }}</textarea>
                            @error('story')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Story Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror"
                                   id="country" name="country" value="{{ old('country', $story->country) }}">
                            @error('country')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="image">Student Image</label>
                            @if($story->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->student_name }}"
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
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_published"
                                       name="is_published" {{ old('is_published', $story->is_published) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_published">Published</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Update Story
                        </button>
                        <a href="{{ route('admin.stories.index') }}" class="btn btn-secondary btn-block">
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
        // Custom Upload Adapter
        class UploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return this.loader.file
                    .then(file => new Promise((resolve, reject) => {
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

            abort() {
                // Handle abort
            }
        }

        function UploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new UploadAdapter(loader);
            };
        }

        // CKEditor with Image Support
        ClassicEditor
            .create(document.querySelector('#story'), {
                extraPlugins: [UploadAdapterPlugin],
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
