@if($enrollments->hasPages())
    <div class="card-footer clearfix">
        <div class="float-left">
            {{ $enrollments->links() }}
        </div>
        <div class="float-right text-muted">
            Showing {{ $enrollments->firstItem() }} to {{ $enrollments->lastItem() }} of {{ $enrollments->total() }} enrollments
        </div>
    </div>
@endif
