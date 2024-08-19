<?php
namespace App\Services;
use App\Repositories\RequestRepository;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class RequestService
{
    protected $requestRepository;

    public function __construct(RequestRepository $requestRepository){
         $this-> requestRepository = $requestRepository;
    }

    public function createRequest(array $request)
    {
        //reglas de validacion
        $validator = Validator::make($request, [
            'titulo_request' => 'required|string|max:255',
            'descripcion_request' => 'required|string|max:500',
            'estado_request' => 'required|string'
        ]);

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        return $this->requestRepository->createRequest($request);
    }

    public function getAllRequest()
    {
        return $this->requestRepository->getAllRequest();
    }

    public function getRequestById($id){
        return $this->requestRepository->getRequestById($id);
    }

    public function updateRequestStatus($requestId, $newStatus)
    {
        return $this->requestRepository->updateRequestStatus($requestId, $newStatus);
    }
}
