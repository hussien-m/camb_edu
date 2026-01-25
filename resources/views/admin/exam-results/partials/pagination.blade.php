@if($attempts->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $attempts->links() }}
    </div>
@endif
