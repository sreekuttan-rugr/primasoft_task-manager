<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAll(): Collection
    {
        return Category::orderBy('name')->get();
    }

    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Category::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Category::destroy($id) > 0;
    }

    public function findByName(string $name): ?Category
    {
        return Category::where('name', $name)->first();
    }
}