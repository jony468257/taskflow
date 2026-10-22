@props(['priority', 'showSuffix' => false])

@php
    $class = match ($priority) {
        'High' => 'badge-priority-high',
        'Medium' => 'badge-priority-medium',
        'Low' => 'badge-priority-low',
        default => 'badge-priority-low',
    };

    $icon = match ($priority) {
        'High' => 'bi-arrow-up-circle-fill',
        'Medium' => 'bi-dash-circle-fill',
        default => 'bi-arrow-down-circle-fill',
    };
@endphp

<span class="badge-priority {{ $class }}">
    <i class="bi {{ $icon }}"></i>
    {{ $priority }}{{ $showSuffix ? ' Priority' : '' }}
</span>
