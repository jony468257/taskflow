<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Root url redirects to tasks dashboard.
     */
    public function test_the_application_redirects_to_tasks_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('tasks.index'));
    }
}
