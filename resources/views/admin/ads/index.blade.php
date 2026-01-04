@extends('admin.layouts.app')

@section('title', 'Ads')
@section('page-title', 'Ads Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Ads</li>
@endsection

@section('content')
    @if(isset($activeAdsCount))
    <div class="alert alert-info">
        <strong>Active Ads Status:</strong>
        Total Active: <strong>{{ $activeAdsCount }}</strong> | 
        Top: <strong>{{ $topAdsCount ?? 0 }}</strong> | 
        Middle: <strong>{{ $middleAdsCount ?? 0 }}</strong> | 
        Bottom: <strong>{{ $bottomAdsCount ?? 0 }}</strong>
    </div>
    @endif
    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Ads</h3>
            <div class="card-tools">
                <a href="{{ route('admin.ads.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Ad
                </a>
            </div>
        </div>
        <!-- Search and Filters -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.ads.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search ads..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="banner" {{ request('type') == 'banner' ? 'selected' : '' }}>Banner</option>
                        <option value="sidebar" {{ request('type') == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                        <option value="inline" {{ request('type') == 'inline' ? 'selected' : '' }}>Inline</option>
                        <option value="popup" {{ request('type') == 'popup' ? 'selected' : '' }}>Popup</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="position" class="form-control">
                        <option value="">All Positions</option>
                        <option value="top" {{ request('position') == 'top' ? 'selected' : '' }}>Top</option>
                        <option value="middle" {{ request('position') == 'middle' ? 'selected' : '' }}>Middle</option>
                        <option value="bottom" {{ request('position') == 'bottom' ? 'selected' : '' }}>Bottom</option>
                        <option value="sidebar-left" {{ request('position') == 'sidebar-left' ? 'selected' : '' }}>Sidebar Left</option>
                        <option value="sidebar-right" {{ request('position') == 'sidebar-right' ? 'selected' : '' }}>Sidebar Right</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort_by" class="form-control">
                        <option value="order" {{ request('sort_by') == 'order' ? 'selected' : '' }}>Order</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date</option>
                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title</option>
                        <option value="views_count" {{ request('sort_by') == 'views_count' ? 'selected' : '' }}>Views</option>
                        <option value="clicks_count" {{ request('sort_by') == 'clicks_count' ? 'selected' : '' }}>Clicks</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <select name="sort_order" class="form-control">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>ASC</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>DESC</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
                @if(request()->hasAny(['search', 'type', 'position', 'sort_by']))
                    <div class="col-md-12 mt-2">
                        <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear Filters
                        </a>
                    </div>
                @endif
            </form>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 80px">Image</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Position</th>
                        <th>Link</th>
                        <th>Views</th>
                        <th>Clicks</th>
                        <th>Status</th>
                        <th style="width: 180px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ads as $ad)
                        <tr>
                            <td>
                                @if($ad->image)
                                    <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->title }}"
                                         class="img-thumbnail" style="max-height: 60px;">
                                @elseif($ad->html_content)
                                    <span class="badge badge-info">HTML</span>
                                @else
                                    <span class="badge badge-secondary">No Image</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $ad->title }}</strong>
                                @if($ad->description)
                                    <br><small class="text-muted">{{ Str::limit($ad->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ ucfirst($ad->type) }}</span>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ ucfirst(str_replace('-', ' ', $ad->position)) }}</span>
                                @if(!$ad->image && !$ad->html_content && !$ad->title && !$ad->description)
                                    <br><small class="text-danger"><i class="fas fa-exclamation-triangle"></i> No content!</small>
                                @endif
                            </td>
                            <td>
                                @if($ad->link)
                                    <a href="{{ $ad->link }}" target="_blank" class="text-primary">
                                        <i class="fas fa-external-link-alt"></i> {{ Str::limit($ad->link, 30) }}
                                    </a>
                                @else
                                    <span class="text-muted">No link</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ number_format($ad->views_count) }}</span>
                            </td>
                            <td>
                                <span class="badge badge-success">{{ number_format($ad->clicks_count) }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.ads.toggle-status', $ad) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-{{ $ad->is_active ? 'success' : 'secondary' }}">
                                        {{ $ad->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.ads.edit', $ad) }}" class="btn btn-sm btn-info" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this ad?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-ad fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No ads found. <a href="{{ route('admin.ads.create') }}">Create your first ad</a></p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $ads->links('vendor.pagination.adminlte') }}
        </div>
    </div>
@endsection

