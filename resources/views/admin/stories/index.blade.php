@extends('admin.layouts.app')

@section('title', 'Success Stories')
@section('page-title', 'Success Stories')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Success Stories</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Success Stories</h3>
            <div class="card-tools">
                <a href="{{ route('admin.stories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Story
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 50px">Image</th>
                        <th>Student Name</th>
                        <th>Country</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th style="width: 150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stories as $story)
                        <tr>
                            <td>
                                @if($story->image)
                                    <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->student_name }}"
                                         class="img-thumbnail" style="max-height: 50px;">
                                @else
                                    <span class="badge badge-secondary">No Image</span>
                                @endif
                            </td>
                            <td>{{ $story->student_name ?? 'N/A' }}</td>
                            <td>{{ $story->country ?? 'N/A' }}</td>
                            <td>{{ $story->title ?? Str::limit($story->story, 50) }}</td>
                            <td>
                                <span class="badge badge-{{ $story->is_published ? 'success' : 'secondary' }}">
                                    {{ $story->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.stories.edit', $story) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.stories.destroy', $story) }}" method="POST"
                                          onsubmit="return confirm('Are you sure?');" class="d-inline">
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
                            <td colspan="6" class="text-center">No success stories found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $stories->links('vendor.pagination.adminlte') }}
        </div>
    </div>
@endsection
