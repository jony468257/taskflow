<div class="row g-3">
    <!-- Task Title -->
    <div class="col-12">
        <label for="title" class="form-label fw-semibold">
            Task Title <span class="text-danger">*</span>
        </label>
        <input 
            type="text" 
            name="title" 
            id="title" 
            class="form-control @error('title') is-invalid @enderror" 
            placeholder="e.g. Implement user authentication module" 
            value="{{ old('title', $task->title ?? '') }}" 
            required 
            maxlength="255"
        >
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="form-text">A concise summary of the task (max 255 characters).</div>
        @enderror
    </div>

    <!-- Description -->
    <div class="col-12">
        <label for="description" class="form-label fw-semibold">
            Description <span class="text-muted fw-normal">(Optional)</span>
        </label>
        <textarea 
            name="description" 
            id="description" 
            rows="4" 
            class="form-control @error('description') is-invalid @enderror" 
            placeholder="Provide context, acceptance criteria, or relevant technical details..."
        >{{ old('description', $task->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Priority -->
    <div class="col-md-4">
        <label for="priority" class="form-label fw-semibold">
            Priority <span class="text-danger">*</span>
        </label>
        <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
            @foreach ($priorities as $p)
                <option value="{{ $p }}" {{ old('priority', $task->priority ?? 'Medium') === $p ? 'selected' : '' }}>
                    {{ $p }}
                </option>
            @endforeach
        </select>
        @error('priority')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Status -->
    <div class="col-md-4">
        <label for="status" class="form-label fw-semibold">
            Status <span class="text-danger">*</span>
        </label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach ($statuses as $s)
                <option value="{{ $s }}" {{ old('status', $task->status ?? 'Pending') === $s ? 'selected' : '' }}>
                    {{ $s }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Due Date -->
    <div class="col-md-4">
        <label for="due_date" class="form-label fw-semibold">
            Due Date <span class="text-muted fw-normal">(Optional)</span>
        </label>
        <input 
            type="date" 
            name="due_date" 
            id="due_date" 
            class="form-control @error('due_date') is-invalid @enderror" 
            value="{{ old('due_date', $task->due_date?->format('Y-m-d') ?? '') }}"
        >
        @error('due_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
