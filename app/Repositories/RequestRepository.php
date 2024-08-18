<?php
namespace App\Repositories;
use App\Models\Request;


class RequestRepository
{
    public function createRequest(array $request)
    {
        return Request::create($request);
    }

    public function getAllRequest()
    {
        return Request::where('estado_request','pendiente')->get();

    }

    public function updateRequestStatus($requestId, $newStatus){

        $request = Request::find($requestId);

        if(!$request){
            throw new \Exception('Request no encontrado');
        }

        $request->estado_request = $newStatus;

        $request->save();

        return $request;
    }
}
