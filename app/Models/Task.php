<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    public const PRIORITIES = ['Low', 'Medium', 'High'];

    public const STATUSES = ['Pending', 'In Progress', 'Completed'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'due_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    /**
     * Priority badge CSS classes for Bootstrap 5.
     */
    public function getPriorityBadgeClass(): string
    {
        return match ($this->priority) {
            'High' => 'bg-danger text-white',
            'Medium' => 'bg-warning text-dark',
            'Low' => 'bg-info text-dark',
            default => 'bg-secondary text-white',
        };
    }

    /**
     * Status badge CSS classes for Bootstrap 5.
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'Completed' => 'bg-success text-white',
            'In Progress' => 'bg-primary text-white',
            'Pending' => 'bg-secondary text-white',
            default => 'bg-light text-dark',
        };
    }
}
