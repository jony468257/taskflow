<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. The task listing page loads successfully.
     */
    public function test_task_listing_page_loads_successfully(): void
    {
        $task = Task::factory()->create([
            'title' => 'Sample Onboarding Task',
            'priority' => 'High',
            'status' => 'Pending',
        ]);

        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertSee('Task Management Dashboard');
        $response->assertSee('Sample Onboarding Task');
        $response->assertSee('TaskFlow');
    }

    /**
     * 2. A valid task can be created.
     */
    public function test_valid_task_can_be_created(): void
    {
        $payload = [
            'title' => 'Build REST API for Mobile Clients',
            'description' => 'Comprehensive endpoints with rate limiting and JWT auth.',
            'priority' => 'High',
            'status' => 'In Progress',
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ];

        $response = $this->post(route('tasks.store'), $payload);

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'Task created successfully.');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Build REST API for Mobile Clients',
            'priority' => 'High',
            'status' => 'In Progress',
        ]);
    }

    /**
     * 3. Required title validation works.
     */
    public function test_required_title_validation_works(): void
    {
        $payload = [
            'title' => '', // empty title
            'priority' => 'Medium',
            'status' => 'Pending',
        ];

        $response = $this->post(route('tasks.store'), $payload);

        $response->assertSessionHasErrors(['title']);
        $this->assertEquals(0, Task::count());
    }

    /**
     * 4. Invalid priority or status is rejected.
     */
    public function test_invalid_priority_or_status_is_rejected(): void
    {
        // Invalid Priority
        $response = $this->post(route('tasks.store'), [
            'title' => 'Task with invalid priority',
            'priority' => 'UrgentCritical', // Not in Low, Medium, High
            'status' => 'Pending',
        ]);
        $response->assertSessionHasErrors(['priority']);

        // Invalid Status
        $response2 = $this->post(route('tasks.store'), [
            'title' => 'Task with invalid status',
            'priority' => 'Medium',
            'status' => 'Archived', // Not in Pending, In Progress, Completed
        ]);
        $response2->assertSessionHasErrors(['status']);

        $this->assertEquals(0, Task::count());
    }

    /**
     * 5. An existing task can be updated.
     */
    public function test_an_existing_task_can_be_updated(): void
    {
        $task = Task::factory()->create([
            'title' => 'Initial Task Name',
            'priority' => 'Low',
            'status' => 'Pending',
        ]);

        $updatePayload = [
            'title' => 'Updated Task Name',
            'description' => 'Updated description content.',
            'priority' => 'High',
            'status' => 'Completed',
            'due_date' => now()->addDays(3)->format('Y-m-d'),
        ];

        $response = $this->put(route('tasks.update', $task), $updatePayload);

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'Task updated successfully.');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Name',
            'priority' => 'High',
            'status' => 'Completed',
        ]);
    }

    /**
     * 6. A task can be deleted.
     */
    public function test_a_task_can_be_deleted(): void
    {
        $task = Task::factory()->create([
            'title' => 'Task to be deleted',
        ]);

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'Task deleted successfully.');

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /**
     * 7. Search by title works.
     */
    public function test_search_by_title_works(): void
    {
        Task::factory()->create(['title' => 'Alpha Special Feature']);
        Task::factory()->create(['title' => 'Beta Internal Audit']);

        $response = $this->get(route('tasks.index', ['search' => 'Special']));

        $response->assertStatus(200);
        $response->assertSee('Alpha Special Feature');
        $response->assertDontSee('Beta Internal Audit');
    }

    /**
     * 8. Status and priority filters work.
     */
    public function test_status_and_priority_filters_work(): void
    {
        $task1 = Task::factory()->create([
            'title' => 'Target Match Task',
            'status' => 'In Progress',
            'priority' => 'High',
        ]);

        $task2 = Task::factory()->create([
            'title' => 'Different Status Task',
            'status' => 'Completed',
            'priority' => 'High',
        ]);

        $task3 = Task::factory()->create([
            'title' => 'Different Priority Task',
            'status' => 'In Progress',
            'priority' => 'Low',
        ]);

        // Filter by Status only
        $responseStatus = $this->get(route('tasks.index', ['status' => 'In Progress']));
        $responseStatus->assertSee('Target Match Task');
        $responseStatus->assertSee('Different Priority Task');
        $responseStatus->assertDontSee('Different Status Task');

        // Filter by Priority only
        $responsePriority = $this->get(route('tasks.index', ['priority' => 'High']));
        $responsePriority->assertSee('Target Match Task');
        $responsePriority->assertSee('Different Status Task');
        $responsePriority->assertDontSee('Different Priority Task');

        // Combined Filter (Status = In Progress AND Priority = High)
        $responseCombined = $this->get(route('tasks.index', [
            'status' => 'In Progress',
            'priority' => 'High',
        ]));
        $responseCombined->assertSee('Target Match Task');
        $responseCombined->assertDontSee('Different Status Task');
        $responseCombined->assertDontSee('Different Priority Task');
    }

    /**
     * Test single task details page displays.
     */
    public function test_task_details_page_loads_with_all_fields(): void
    {
        $task = Task::factory()->create([
            'title' => 'Detailed System Analysis',
            'description' => 'Specific detailed operational notes.',
            'priority' => 'High',
            'status' => 'In Progress',
            'due_date' => '2026-11-15',
        ]);

        $response = $this->get(route('tasks.show', $task));

        $response->assertStatus(200);
        $response->assertSee('Detailed System Analysis');
        $response->assertSee('Specific detailed operational notes.');
        $response->assertSee('High Priority');
        $response->assertSee('In Progress');
    }

    /**
     * 10. The task creation page loads successfully.
     */
    public function test_task_create_page_loads_successfully(): void
    {
        $response = $this->get(route('tasks.create'));

        $response->assertStatus(200);
        $response->assertSee('Create New Task');
        $response->assertSee('Task Title');
    }

    /**
     * 11. The task edit page loads with existing values.
     */
    public function test_task_edit_page_loads_with_existing_values(): void
    {
        $task = Task::factory()->create([
            'title' => 'Task Being Edited',
            'description' => 'Original description for edit test',
            'priority' => 'Medium',
            'status' => 'Pending',
        ]);

        $response = $this->get(route('tasks.edit', $task));

        $response->assertStatus(200);
        $response->assertSee('Edit Task');
        $response->assertSee('Task Being Edited');
        $response->assertSee('Original description for edit test');
    }

    /**
     * 12. Non-existent task ID returns 404 cleanly.
     */
    public function test_missing_task_returns_404_not_found(): void
    {
        $nonExistentId = 999999;

        $this->get(route('tasks.show', $nonExistentId))->assertNotFound();
        $this->get(route('tasks.edit', $nonExistentId))->assertNotFound();
        $this->put(route('tasks.update', $nonExistentId), [
            'title' => 'Updated Non-Existent',
            'priority' => 'Low',
            'status' => 'Pending',
        ])->assertNotFound();
        $this->delete(route('tasks.destroy', $nonExistentId))->assertNotFound();
    }

    /**
     * 13. Empty search or filter results display a clean empty state.
     */
    public function test_empty_search_or_filter_results_display_clean_empty_state(): void
    {
        Task::factory()->create(['title' => 'Existing Unrelated Task']);

        $response = $this->get(route('tasks.index', ['search' => 'NonExistentTitleKeywordXYZ']));

        $response->assertStatus(200);
        $response->assertSee('No tasks found');
        $response->assertSee('Clear Filters');
    }

    /**
     * 14. Title max length validation rejects more than 255 characters.
     */
    public function test_title_max_length_validation_rejects_more_than_255_chars(): void
    {
        $longTitle = str_repeat('a', 256);

        $response = $this->post(route('tasks.store'), [
            'title' => $longTitle,
            'priority' => 'Medium',
            'status' => 'Pending',
        ]);

        $response->assertSessionHasErrors(['title']);
        $this->assertEquals(0, Task::count());
    }

    /**
     * 15. Pagination preserves search and filter query parameters.
     */
    public function test_pagination_preserves_query_parameters(): void
    {
        Task::factory()->count(15)->create([
            'title' => 'Bugfix Task',
            'priority' => 'High',
            'status' => 'Pending',
        ]);

        $response = $this->get(route('tasks.index', [
            'search' => 'Bugfix',
            'priority' => 'High',
            'status' => 'Pending',
            'page' => 2,
        ]));

        $response->assertStatus(200);
        $response->assertSee('search=Bugfix');
        $response->assertSee('priority=High');
        $response->assertSee('status=Pending');
    }
}
