<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    
    public function findById(int $id): ?Task;
    
    public function create(array $data): Task;
    
    public function update(int $id, array $data): bool;
    
    public function delete(int $id): bool;
    
    public function getByStatus(string $status): Collection;
    
    public function getByCategory(int $categoryId): Collection;
    
    public function getDueThisWeek(): Collection;
    
    public function getHighPriority(int $limit = 5): Collection;
    
    public function bulkCreate(array $tasks): void;
    
    public function getTaskStats(): array;
}