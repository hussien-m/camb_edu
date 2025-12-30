@extends('frontend.layouts.app')

@section('title', '403 - Access Forbidden')
@section('description', 'Access to this resource is forbidden.')

@section('content')
<div class="container my-5 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="error-content">
                <div class="error-code mb-4">
                    <i class="fas fa-ban text-danger" style="font-size: 120px; opacity: 0.7;"></i>
                </div>
                <h1 class="display-1 fw-bold text-danger mb-3">403</h1>
                <h2 class="mb-4">Access Forbidden</h2>
                <p class="lead text-muted mb-5">
                    You don't have permission to access this resource.
                </p>
                
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i> Go to Homepage
                    </a>
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-graduation-cap me-2"></i> Browse Courses
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-content {
    padding: 60px 20px;
}

.error-code i {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { 
        transform: scale(1);
        opacity: 0.7;
    }
    50% { 
        transform: scale(1.05);
        opacity: 0.9;
    }
}
</style>
@endsection


