@props(['status'])

@php
    $class = match ($status) {
        'Completed' => 'badge-status-completed',
        'In Progress' => 'badge-status-in-progress',
        'Pending' => 'badge-status-pending',
        default => 'badge-status-pending',
    };

    $icon = match ($status) {
        'Completed' => 'bi-check-circle-fill',
        'In Progress' => 'bi-arrow-clockwise',
        default => 'bi-clock-fill',
    };
@endphp

<span class="badge-status {{ $class }}">
    <i class="bi {{ $icon }}"></i>
    {{ $status }}
</span>
