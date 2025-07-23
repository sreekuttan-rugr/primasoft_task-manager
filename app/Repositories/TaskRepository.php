<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Task::with(['assignedUser', 'category']);

        // Apply filters
        if (!empty($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->byCategory($filters['category_id']);
        }

        if (!empty($filters['due_date'])) {
            $query->byDueDate($filters['due_date']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        // Search by title
        if (!empty($filters['search'])) {
            $query->where('title', 'LIKE', '%' . $filters['search'] . '%');
        }

        // Sort by priority by default
        $query->highPriority();

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Task
    {
        return Task::with(['assignedUser', 'category'])->find($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Task::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Task::destroy($id) > 0;
    }

    public function getByStatus(string $status): Collection
    {
        return Task::byStatus($status)->with(['assignedUser', 'category'])->get();
    }

    public function getByCategory(int $categoryId): Collection
    {
        return Task::byCategory($categoryId)->with(['assignedUser', 'category'])->get();
    }

    public function getDueThisWeek(): Collection
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        return Task::whereBetween('due_date', [$startOfWeek, $endOfWeek])
            ->with(['assignedUser', 'category'])
            ->get();
    }

    public function getHighPriority(int $limit = 5): Collection
    {
        return Task::highPriority()
            ->with(['assignedUser', 'category'])
            ->limit($limit)
            ->get();
    }

    public function bulkCreate(array $tasks): void
    {
        Task::insert($tasks);
    }

    public function getTaskStats(): array
    {
        return [
            'total' => Task::count(),
            'pending' => Task::byStatus('pending')->count(),
            'in_progress' => Task::byStatus('in_progress')->count(),
            'completed' => Task::byStatus('completed')->count(),
            'due_this_week' => $this->getDueThisWeek()->count(),
        ];
    }
}