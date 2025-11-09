@extends('admin.layouts.app')

@section('title', 'Courses')
@section('page-title', 'Courses Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Courses</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Courses</h3>
            <div class="card-tools">
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Course
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 50px">Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Level</th>
                        <th>Duration</th>
                        <th>Fee</th>
                        <th>Status</th>
                        <th style="width: 150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>
                                @if($course->image)
                                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="img-thumbnail" style="max-height: 50px;">
                                @else
                                    <span class="badge badge-secondary">No Image</span>
                                @endif
                            </td>
                            <td>
                                {{ $course->title }}
                                @if($course->is_featured)
                                    <span class="badge badge-warning">Featured</span>
                                @endif
                            </td>
                            <td>{{ $course->category->name ?? 'N/A' }}</td>
                            <td>{{ $course->level->name ?? 'N/A' }}</td>
                            <td>{{ $course->duration ?? 'N/A' }}</td>
                            <td>{{ $course->fee ? '$' . number_format($course->fee, 2) : 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $course->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this course?');" class="d-inline">
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
                            <td colspan="8" class="text-center">No courses found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $courses->links('vendor.pagination.adminlte') }}
        </div>
    </div>
@endsection
