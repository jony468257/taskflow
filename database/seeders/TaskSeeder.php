<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            [
                'title' => 'Set up MySQL database replication',
                'description' => 'Configure primary-replica replication for MySQL 8.0 cluster to ensure high availability.',
                'priority' => 'High',
                'status' => 'Completed',
                'due_date' => now()->subDays(2)->format('Y-m-d'),
            ],
            [
                'title' => 'Implement user authentication with Sanctum',
                'description' => 'Add token-based authentication and route middleware guards for internal APIs.',
                'priority' => 'High',
                'status' => 'In Progress',
                'due_date' => now()->addDays(5)->format('Y-m-d'),
            ],
            [
                'title' => 'Design responsive dashboard UI in Bootstrap 5',
                'description' => 'Build modern, mobile-friendly dashboard components with status cards, tables, and filters.',
                'priority' => 'Medium',
                'status' => 'In Progress',
                'due_date' => now()->addDays(7)->format('Y-m-d'),
            ],
            [
                'title' => 'Configure automated CI/CD deployment pipeline',
                'description' => 'Set up GitHub Actions workflow to run automated tests and deploy to staging environment.',
                'priority' => 'High',
                'status' => 'Pending',
                'due_date' => now()->addDays(12)->format('Y-m-d'),
            ],
            [
                'title' => 'Audit third-party API rate limits',
                'description' => 'Review outgoing rate limits for payment gateways and notification providers.',
                'priority' => 'Medium',
                'status' => 'Completed',
                'due_date' => now()->subDays(4)->format('Y-m-d'),
            ],
            [
                'title' => 'Write automated tests for TaskController',
                'description' => 'Cover CRUD operations, search, priority/status filtering, and validation edge cases.',
                'priority' => 'High',
                'status' => 'Completed',
                'due_date' => now()->subDays(1)->format('Y-m-d'),
            ],
            [
                'title' => 'Optimize SQL queries for task filtering',
                'description' => 'Analyze EXPLAIN plans and add composite database indexes for search and status filters.',
                'priority' => 'Medium',
                'status' => 'In Progress',
                'due_date' => now()->addDays(4)->format('Y-m-d'),
            ],
            [
                'title' => 'Update terms of service and privacy policy',
                'description' => 'Coordinate with legal advisor to refresh copyright year and compliance clauses.',
                'priority' => 'Low',
                'status' => 'Pending',
                'due_date' => null,
            ],
            [
                'title' => 'Fix mobile navigation bar toggle behavior',
                'description' => 'Ensure the hamburger menu closes automatically upon route navigation on small viewports.',
                'priority' => 'Medium',
                'status' => 'Completed',
                'due_date' => now()->subDays(3)->format('Y-m-d'),
            ],
            [
                'title' => 'Conduct quarterly security vulnerability scan',
                'description' => 'Run Composer and NPM audit checks to identify and patch known CVE dependencies.',
                'priority' => 'High',
                'status' => 'In Progress',
                'due_date' => now()->addDays(8)->format('Y-m-d'),
            ],
            [
                'title' => 'Draft weekly release notes for stakeholders',
                'description' => 'Summarize key feature additions, bug fixes, and performance improvements shipped this sprint.',
                'priority' => 'Low',
                'status' => 'Pending',
                'due_date' => now()->addDays(3)->format('Y-m-d'),
            ],
            [
                'title' => 'Clean up stale cache and unused build artifacts',
                'description' => 'Prune temporary files and expired cache records from the storage directory.',
                'priority' => 'Low',
                'status' => 'Completed',
                'due_date' => null,
            ],
            [
                'title' => 'Implement multi-criteria task search and filtering',
                'description' => 'Allow users to filter tasks simultaneously by title keywords, status, and priority level.',
                'priority' => 'High',
                'status' => 'Completed',
                'due_date' => now()->subDays(2)->format('Y-m-d'),
            ],
            [
                'title' => 'Prepare OpenAPI specifications for task endpoints',
                'description' => 'Document request schemas, response codes, and validation rules in Swagger YAML format.',
                'priority' => 'Medium',
                'status' => 'Pending',
                'due_date' => now()->addDays(14)->format('Y-m-d'),
            ],
            [
                'title' => 'Review pull request for webhook payload validation',
                'description' => 'Verify HMAC signature checking and replay protection on incoming webhook events.',
                'priority' => 'High',
                'status' => 'Pending',
                'due_date' => now()->addDays(6)->format('Y-m-d'),
            ],
            [
                'title' => 'Benchmark application response under load',
                'description' => 'Run load testing with 500 concurrent virtual users to evaluate response time percentiles.',
                'priority' => 'Medium',
                'status' => 'Pending',
                'due_date' => null,
            ],
            [
                'title' => 'Configure daily offsite database backup routine',
                'description' => 'Set up automated S3 export with AES-256 encryption for daily MySQL dumps.',
                'priority' => 'High',
                'status' => 'Completed',
                'due_date' => now()->subDays(5)->format('Y-m-d'),
            ],
            [
                'title' => 'Update application dependencies to latest patch releases',
                'description' => 'Safely bump non-breaking minor and patch package releases in composer.json.',
                'priority' => 'Low',
                'status' => 'In Progress',
                'due_date' => now()->addDays(9)->format('Y-m-d'),
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::updateOrCreate(
                ['title' => $taskData['title']],
                $taskData
            );
        }
    }
}
