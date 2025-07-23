<?php

namespace App\Services;
use App\Models\Users;


class UserService
{
    /**
     * Get all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers()
    {
        return Users::all();
    }

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return \App\Models\User|null
     */
    public function findUserById(int $id)
    {
        return Users::find($id);
    }

    /**
     * Check if the user is an admin.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function isAdmin(User $user): bool
    {
        return $user->isAdmin();
    }
}