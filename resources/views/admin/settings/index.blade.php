@extends('admin.layouts.app')

@section('title', 'Site Settings')
@section('page-title', 'Site Settings')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach($settings as $group => $groupSettings)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog"></i>
                        {{ ucfirst($group) }} Settings
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($groupSettings as $setting)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="{{ $setting->key }}">
                                        {{ $setting->label ?? ucwords(str_replace('_', ' ', $setting->key)) }}
                                    </label>

                                    @if($setting->type === 'text' || $setting->type === 'number')
                                        <input type="{{ $setting->type }}"
                                               class="form-control @error('settings.'.$setting->key) is-invalid @enderror"
                                               id="{{ $setting->key }}"
                                               name="settings[{{ $setting->key }}]"
                                               value="{{ old('settings.'.$setting->key, $setting->value) }}">

                                    @elseif($setting->type === 'textarea')
                                        <textarea class="form-control @error('settings.'.$setting->key) is-invalid @enderror"
                                                  id="{{ $setting->key }}"
                                                  name="settings[{{ $setting->key }}]"
                                                  rows="3">{{ old('settings.'.$setting->key, $setting->value) }}</textarea>

                                    @elseif($setting->type === 'image')
                                        @if($setting->value)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $setting->value) }}"
                                                     alt="{{ $setting->label }}"
                                                     class="img-thumbnail"
                                                     style="max-height: 100px;">
                                            </div>
                                        @endif
                                        <div class="custom-file">
                                            <input type="file"
                                                   class="custom-file-input @error('settings.'.$setting->key) is-invalid @enderror"
                                                   id="{{ $setting->key }}"
                                                   name="settings[{{ $setting->key }}]"
                                                   accept="image/*">
                                            <label class="custom-file-label" for="{{ $setting->key }}">Choose file</label>
                                        </div>

                                    @elseif($setting->type === 'boolean')
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   class="custom-control-input"
                                                   id="{{ $setting->key }}"
                                                   name="settings[{{ $setting->key }}]"
                                                   {{ old('settings.'.$setting->key, $setting->value) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="{{ $setting->key }}">
                                                Enable
                                            </label>
                                        </div>
                                    @endif

                                    @if($setting->description)
                                        <small class="form-text text-muted">{{ $setting->description }}</small>
                                    @endif

                                    @error('settings.'.$setting->key)
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <div class="card">
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Update file input labels
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
    </script>
@endpush
