@extends('layouts.app')

@section('title', 'Create Task - TaskFlow')

@section('content')
    <!-- Breadcrumb / Back Link -->
    <div class="mb-3">
        <a href="{{ route('tasks.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Tasks</span>
        </a>
    </div>

    <!-- Create Card -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card-main p-4">
                <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
                    <div>
                        <h2 class="h4 fw-bold text-dark mb-1">Create New Task</h2>
                        <p class="text-muted small mb-0">Fill in the task details to add it to the workflow board.</p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary p-2 rounded-3">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </span>
                </div>

                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf

                    @include('tasks.form')

                    <div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary px-3">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-brand px-4 d-inline-flex align-items-center gap-2">
                            <i class="bi bi-check-lg"></i>
                            <span>Save Task</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
