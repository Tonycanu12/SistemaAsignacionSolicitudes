<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\RequestService;

class RequestController extends Controller
{
    protected $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    public function createRequest(Request $request):JsonResponse
    {
        try{
            $this->requestService->createRequest($request->all());
            return response()->json([$request->all()],200);

        }catch(ValidationException $e){
            return response()->json([
                'errors' => $e->errors(),
                'message' => 'validacion de datos fallida'
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'A ocurrido un error',
                'error_message' => $e->getMessage()
            ],500);
        }
    }
}
