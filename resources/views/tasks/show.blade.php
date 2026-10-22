@extends('layouts.app')

@section('title', $task->title . ' - TaskFlow')

@section('content')
    <!-- Breadcrumb / Back Link -->
    <div class="mb-3">
        <a href="{{ route('tasks.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Tasks</span>
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card-main p-4">
                <!-- Task Header -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 pb-3 mb-4 border-bottom">
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <x-priority-badge :priority="$task->priority" :show-suffix="true" />
                            <x-status-badge :status="$task->status" />
                        </div>

                        <h2 class="h3 fw-bold text-dark mb-0">{{ $task->title }}</h2>
                    </div>

                    <!-- Action buttons -->
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                            <i class="bi bi-pencil"></i>
                            <span>Edit</span>
                        </a>

                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1">
                                <i class="bi bi-trash3"></i>
                                <span>Delete</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted fw-bold small mb-2">Description</h6>
                    @if ($task->description)
                        <div class="p-3 bg-light rounded-3 text-secondary" style="white-space: pre-wrap; line-height: 1.6;">{{ $task->description }}</div>
                    @else
                        <div class="p-3 bg-light rounded-3 text-muted fst-italic">No description provided for this task.</div>
                    @endif
                </div>

                <!-- Meta Details Grid -->
                <div class="row g-3 pt-3 border-top">
                    <!-- Due Date -->
                    <div class="col-sm-4">
                        <div class="text-muted small fw-semibold">Due Date</div>
                        <div class="d-flex align-items-center gap-1 mt-1 {{ $task->due_date && $task->due_date->isPast() && $task->status !== 'Completed' ? 'text-danger fw-bold' : 'text-dark' }}">
                            <i class="bi bi-calendar-event"></i>
                            <span>{{ $task->due_date ? $task->due_date->format('F d, Y') : 'No due date set' }}</span>
                            @if ($task->due_date && $task->due_date->isPast() && $task->status !== 'Completed')
                                <span class="badge bg-danger-subtle text-danger ms-1" style="font-size: 0.7rem;">Overdue</span>
                            @endif
                        </div>
                    </div>

                    <!-- Created At -->
                    <div class="col-sm-4">
                        <div class="text-muted small fw-semibold">Created Date</div>
                        <div class="text-dark mt-1 d-flex align-items-center gap-1">
                            <i class="bi bi-clock-history"></i>
                            <span>{{ $task->created_at ? $task->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Updated At -->
                    <div class="col-sm-4">
                        <div class="text-muted small fw-semibold">Last Updated</div>
                        <div class="text-dark mt-1 d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>{{ $task->updated_at ? $task->updated_at->format('M d, Y h:i A') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Back to Tasks Footer Link -->
                <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Tasks
                    </a>
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-brand">
                        <i class="bi bi-pencil me-1"></i> Edit Task
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
