@extends('admin.layouts.app')

@section('title', 'Levels')
@section('page-title', 'Course Levels')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Levels</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Levels</h3>
            <div class="card-tools">
                <a href="{{ route('admin.levels.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Level
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Courses Count</th>
                        <th style="width: 150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($levels as $level)
                        <tr>
                            <td>{{ $level->sort_order }}</td>
                            <td>{{ $level->name }}</td>
                            <td><code>{{ $level->slug }}</code></td>
                            <td>
                                <span class="badge badge-info">{{ $level->courses_count }} courses</span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.levels.edit', $level) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.levels.destroy', $level) }}" method="POST"
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
                            <td colspan="5" class="text-center">No levels found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $levels->links('vendor.pagination.adminlte') }}
        </div>
    </div>
@endsection
