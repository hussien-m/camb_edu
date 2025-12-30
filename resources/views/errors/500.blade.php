@extends('frontend.layouts.app')

@section('title', '500 - Server Error')
@section('description', 'An internal server error occurred.')

@section('content')
<div class="container my-5 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="error-content">
                <div class="error-code mb-4">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 120px; opacity: 0.7;"></i>
                </div>
                <h1 class="display-1 fw-bold text-warning mb-3">500</h1>
                <h2 class="mb-4">Internal Server Error</h2>
                <p class="lead text-muted mb-5">
                    Something went wrong on our end. We're working to fix it.
                </p>
                
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i> Go to Homepage
                    </a>
                    <a href="javascript:history.back()" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i> Go Back
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
    animation: shake 3s infinite;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}
</style>
@endsection


