<?php
namespace App\Repositories;
use App\Models\User;


class UserRepository
{
    public function getAllUsers()
    {
        return User::select('name','role')->get();
    }

    public function getUserById($id)
    {
        return User::find($id);
    }
}
