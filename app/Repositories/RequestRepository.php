<?php
namespace App\Repositories;
use App\Models\Request;


class RequestRepository
{
    public function createRequest(array $request)
    {
        return Request::create($request);
    }
}
