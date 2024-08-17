<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    protected $userService;

    public  function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUsers(): JsonResponse
    {
        $users = $this -> userService ->getAllUsers();
        return response()->json($users);
    }
}
