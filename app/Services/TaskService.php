<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Strategies\Scoring\ScoringContext;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    private TaskRepositoryInterface $taskRepository;
    private ScoringContext $scoringContext;

    public function __construct(TaskRepositoryInterface $taskRepository, ScoringContext $scoringContext)
    {
        $this->taskRepository = $taskRepository;
        $this->scoringContext = $scoringContext;
    }

    public function getAllTasks(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->taskRepository->getAll($filters, $perPage);
    }

    public function getTaskById(int $id): ?Task
    {
        return $this->taskRepository->findById($id);
    }

    public function createTask(array $data): Task
    {
        if (isset($data['urgency'], $data['impact'], $data['effort'])) {
            $data['priority_score'] = $this->scoringContext->calculateScore(
                $data['urgency'],
                $data['impact'],
                $data['effort']
            );
        }

        return $this->taskRepository->create($data);
    }

    public function updateTask(int $id, array $data): bool
    {
        $task = $this->taskRepository->findById($id);

        if (!$task) {
            return false;
        }

        if (isset($data['urgency']) || isset($data['impact']) || isset($data['effort'])) {
            $urgency = $data['urgency'] ?? $task->urgency;
            $impact  = $data['impact']  ?? $task->impact;
            $effort  = $data['effort']  ?? $task->effort;

            $data['priority_score'] = $this->scoringContext->calculateScore($urgency, $impact, $effort);
        }

        $updated = $this->taskRepository->update($id, $data);

        if ($updated && $task->assigned_to) {
            $user = User::find($task->assigned_to);
            if ($user) {
                $user->notify(new TaskAssignedNotification($task->fresh(), 'updated'));
            }
        }

        return $updated;
    }

    public function deleteTask(int $id): bool
    {
        return $this->taskRepository->delete($id);
    }

    public function getTasksByStatus(string $status): Collection
    {
        return $this->taskRepository->getByStatus($status);
    }

    public function getDashboardStats(): array
    {
        return [
            ...$this->taskRepository->getTaskStats(),
            'high_priority' => $this->taskRepository->getHighPriority(5),
        ];
    }

    public function bulkCreateTasks(array $tasks): void
    {
        $processedTasks = [];

        foreach ($tasks as $task) {
            if (isset($task['urgency'], $task['impact'], $task['effort'])) {
                $task['priority_score'] = $this->scoringContext->calculateScore(
                    $task['urgency'],
                    $task['impact'],
                    $task['effort']
                );
            }

            $task['created_at'] = now();
            $task['updated_at'] = now();
            $processedTasks[] = $task;
        }

        // Bulk insert tasks
        $this->taskRepository->bulkCreate($processedTasks);

        // Notify assigned users
        foreach ($processedTasks as $taskData) {
            if (!empty($taskData['assigned_to'])) {
                $user = User::find($taskData['assigned_to']);
                if ($user) {
                    // You can fetch the latest task via title + timestamps (or return IDs from repo)
                    $task = Task::where('title', $taskData['title'])
                        ->where('assigned_to', $taskData['assigned_to'])
                        ->latest('created_at')
                        ->first();

                    if ($task) {
                        $user->notify(new TaskAssignedNotification($task, 'created'));
                    }
                }
            }
        }
    }
}
