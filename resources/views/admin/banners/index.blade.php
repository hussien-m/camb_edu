@extends('admin.layouts.app')

@section('title', 'Banners')
@section('page-title', 'Banners Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Banners</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Banners</h3>
            <div class="card-tools">
                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Banner
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 100px">Image</th>
                        <th>Title</th>
                        <th>Subtitle</th>
                        <th>Link</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th style="width: 150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td>
                                @if($banner->image)
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}"
                                         class="img-thumbnail" style="max-height: 60px;">
                                @else
                                    <span class="badge badge-secondary">No Image</span>
                                @endif
                            </td>
                            <td>{{ $banner->title ?? 'N/A' }}</td>
                            <td>{{ $banner->subtitle ?? 'N/A' }}</td>
                            <td>
                                @if($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank">
                                        {{ Str::limit($banner->link, 30) }}
                                    </a>
                                @else
                                    <span class="text-muted">No link</span>
                                @endif
                            </td>
                            <td>{{ $banner->order }}</td>
                            <td>
                                <span class="badge badge-{{ $banner->is_active ? 'success' : 'secondary' }}">
                                    {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST"
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
                            <td colspan="7" class="text-center">No banners found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $banners->links('vendor.pagination.adminlte') }}
        </div>
    </div>
@endsection
