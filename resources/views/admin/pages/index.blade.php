@extends('admin.layouts.app')

@section('title', 'Pages')
@section('page-title', 'Pages Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Pages</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Pages</h3>
            <div class="card-tools">
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Page
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th style="width: 150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>{{ $page->title }}</td>
                            <td><code>{{ $page->slug }}</code></td>
                            <td>
                                <span class="badge badge-{{ $page->is_published ? 'success' : 'secondary' }}">
                                    {{ $page->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>{{ $page->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST"
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
                            <td colspan="5" class="text-center">No pages found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $pages->links('vendor.pagination.adminlte') }}
        </div>
    </div>
@endsection
