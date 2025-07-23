<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view tasks
    }

    public function view(User $user, Task $task): bool
    {
        // Users can view tasks assigned to them or admins can view all
        return $user->isAdmin() || $task->assigned_to === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin(); // Only admins can create tasks
    }

    public function update(User $user, Task $task): bool
    {
        // Admins can update any task, users can update their assigned tasks
        return $user->isAdmin() || $task->assigned_to === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->isAdmin(); // Only admins can delete tasks
    }

    public function bulkImport(User $user): bool
    {
        return $user->isAdmin(); // Only admins can bulk import
    }
}