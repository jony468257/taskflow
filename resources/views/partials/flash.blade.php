@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert">
        <i class="bi bi-exclamation-octagon-fill text-danger fs-5"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
        <div>{{ session('warning') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
