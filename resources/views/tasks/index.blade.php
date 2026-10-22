@extends('layouts.app')

@section('title', 'TaskFlow - Mini Task Management System')

@section('content')
    <!-- Dashboard Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Task Management Dashboard</h1>
            <p class="text-muted mb-0">Organize, track priorities, and manage project workflows efficiently.</p>
        </div>
        <div>
            <a href="{{ route('tasks.create') }}" class="btn btn-brand px-3 py-2 d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Create New Task</span>
            </a>
        </div>
    </div>

    <!-- Summary Count Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Tasks -->
        <div class="col-6 col-lg-3">
            <div class="stat-card p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Tasks</div>
                        <div class="h2 fw-bold text-dark mb-0 mt-1">{{ number_format($counts['total']) }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div class="col-6 col-lg-3">
            <div class="stat-card p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Pending</div>
                        <div class="h2 fw-bold text-secondary mb-0 mt-1">{{ number_format($counts['pending']) }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-secondary-subtle text-secondary">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Progress Tasks -->
        <div class="col-6 col-lg-3">
            <div class="stat-card p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">In Progress</div>
                        <div class="h2 fw-bold text-primary mb-0 mt-1">{{ number_format($counts['in_progress']) }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-info-subtle text-info">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="col-6 col-lg-3">
            <div class="stat-card p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Completed</div>
                        <div class="h2 fw-bold text-success mb-0 mt-1">{{ number_format($counts['completed']) }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-success-subtle text-success">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search Card -->
    <div class="card-main p-3 mb-4">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-2 align-items-center">
            <!-- Search by Title -->
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control border-start-0 ps-0" 
                        placeholder="Search tasks by title..." 
                        value="{{ $filters['search'] }}"
                    >
                </div>
            </div>

            <!-- Filter by Status -->
            <div class="col-6 col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ $filters['status'] === $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Priority -->
            <div class="col-6 col-md-3">
                <select name="priority" class="form-select">
                    <option value="">All Priorities</option>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority }}" {{ $filters['priority'] === $priority ? 'selected' : '' }}>
                            {{ $priority }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-1">
                    <i class="bi bi-funnel-fill"></i>
                    <span>Filter</span>
                </button>
                @if ($filters['search'] || $filters['status'] || $filters['priority'])
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center" title="Reset Filters">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tasks Listing Table Card -->
    <div class="card-main overflow-hidden">
        @if ($tasks->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 35%;">Task Details</th>
                            <th scope="col" style="width: 15%;">Priority</th>
                            <th scope="col" style="width: 15%;">Status</th>
                            <th scope="col" style="width: 18%;">Due Date</th>
                            <th scope="col" class="text-end" style="width: 17%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td>
                                    <a href="{{ route('tasks.show', $task) }}" class="fw-semibold text-decoration-none text-dark d-block">
                                        {{ $task->title }}
                                    </a>
                                    @if ($task->description)
                                        <div class="text-muted small text-truncate mt-1" style="max-width: 380px;">
                                            {{ Str::limit($task->description, 75) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <x-priority-badge :priority="$task->priority" />
                                </td>
                                <td>
                                    <x-status-badge :status="$task->status" />
                                </td>
                                <td>
                                    @if ($task->due_date)
                                        <div class="d-flex align-items-center gap-1 small {{ $task->due_date->isPast() && $task->status !== 'Completed' ? 'text-danger fw-semibold' : 'text-muted' }}">
                                            <i class="bi bi-calendar3"></i>
                                            <span>{{ $task->due_date->format('M d, Y') }}</span>
                                            @if ($task->due_date->isPast() && $task->status !== 'Completed')
                                                <span class="badge bg-danger-subtle text-danger ms-1" style="font-size: 0.65rem;">Overdue</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">&mdash; No due date</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                        <!-- View -->
                                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-secondary action-btn" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary action-btn" title="Edit Task">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <!-- Delete with Confirmation -->
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger action-btn" title="Delete Task">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            <div class="p-3 border-top d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                <div class="text-muted small">
                    Showing <span class="fw-semibold">{{ $tasks->firstItem() ?? 0 }}</span> to <span class="fw-semibold">{{ $tasks->lastItem() ?? 0 }}</span> of <span class="fw-semibold">{{ $tasks->total() }}</span> tasks
                </div>
                <div>
                    {{ $tasks->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-5 px-3">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle" style="width: 64px; height: 64px; font-size: 1.75rem;">
                        <i class="bi bi-clipboard-x"></i>
                    </span>
                </div>
                <h4 class="h5 fw-bold text-dark mb-1">No tasks found</h4>
                <p class="text-muted mb-3" style="max-width: 420px; margin: 0 auto;">
                    @if ($filters['search'] || $filters['status'] || $filters['priority'])
                        No tasks match your current search and filter criteria. Try adjusting or resetting your filters.
                    @else
                        You don't have any tasks right now. Create your first task to get started!
                    @endif
                </p>
                <div class="d-flex justify-content-center gap-2">
                    @if ($filters['search'] || $filters['status'] || $filters['priority'])
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Clear Filters
                        </a>
                    @endif
                    <a href="{{ route('tasks.create') }}" class="btn btn-brand">
                        <i class="bi bi-plus-lg me-1"></i> Add Task
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
