@extends('admin.layouts.app')

@section('title', 'Features')
@section('page-title', 'Features Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Features</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Features</h3>
            <div class="card-tools">
                <a href="{{ route('admin.features.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Feature
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 50px">Icon</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th style="width: 80px">Order</th>
                        <th style="width: 80px">Status</th>
                        <th style="width: 150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($features as $feature)
                        <tr>
                            <td class="text-center">
                                @if($feature->icon)
                                    <i class="{{ $feature->icon }} fa-2x text-primary"></i>
                                @else
                                    <span class="badge badge-secondary">No Icon</span>
                                @endif
                            </td>
                            <td>{{ $feature->title }}</td>
                            <td>{{ Str::limit($feature->description, 100) }}</td>
                            <td class="text-center">{{ $feature->order }}</td>
                            <td>
                                <span class="badge badge-{{ $feature->is_active ? 'success' : 'secondary' }}">
                                    {{ $feature->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.features.edit', $feature) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.features.destroy', $feature) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this feature?');" class="d-inline">
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
                            <td colspan="6" class="text-center">No features found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
