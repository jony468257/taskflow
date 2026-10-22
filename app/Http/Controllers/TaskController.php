<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks with search, filters, pagination, and counts.
     */
    public function index(Request $request): View
    {
        $query = Task::query();

        // Search by task title (safely escaping SQL LIKE wildcards)
        if ($request->filled('search')) {
            $searchTerm = trim((string) $request->input('search'));
            $query->where('title', 'like', '%'.addcslashes($searchTerm, '%_\\').'%');
        }

        // Filter by status (safely ignore invalid values)
        if ($request->filled('status') && in_array($request->status, Task::STATUSES, true)) {
            $query->where('status', $request->status);
        }

        // Filter by priority (safely ignore invalid values)
        if ($request->filled('priority') && in_array($request->priority, Task::PRIORITIES, true)) {
            $query->where('priority', $request->priority);
        }

        // Paginate results and preserve query strings for pagination links
        $tasks = $query->latest('id')->paginate(10)->withQueryString();

        // Dashboard summary counts (overall counts, not affected by current filter)
        $counts = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'Pending')->count(),
            'in_progress' => Task::where('status', 'In Progress')->count(),
            'completed' => Task::where('status', 'Completed')->count(),
        ];

        return view('tasks.index', [
            'tasks' => $tasks,
            'counts' => $counts,
            'priorities' => Task::PRIORITIES,
            'statuses' => Task::STATUSES,
            'filters' => [
                'search' => (string) $request->input('search', ''),
                'status' => (string) $request->input('status', ''),
                'priority' => (string) $request->input('priority', ''),
            ],
        ]);
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(): View
    {
        return view('tasks.create', [
            'task' => new Task([
                'priority' => 'Medium',
                'status' => 'Pending',
            ]),
            'priorities' => Task::PRIORITIES,
            'statuses' => Task::STATUSES,
        ]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(TaskRequest $request): RedirectResponse
    {
        Task::create($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified task details.
     */
    public function show(Task $task): View
    {
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task): View
    {
        return view('tasks.edit', [
            'task' => $task,
            'priorities' => Task::PRIORITIES,
            'statuses' => Task::STATUSES,
        ]);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(TaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
