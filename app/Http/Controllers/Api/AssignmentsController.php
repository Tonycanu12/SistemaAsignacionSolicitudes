<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AssigmentsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AssignmentsController extends Controller
{
    protected $assignmentsServise;

    public function __construct(AssigmentsService $assignmentsServise)
    {
        $this->assignmentsServise = $assignmentsServise;
    }

    public function createAssignments(Request $assignments):JsonResponse
    {
        try{
            $newAssignment = $this->assignmentsServise->createAssignment($assignments->all());
            return response()->json($newAssignment,200);

        }catch(ValidationException $e){
            return response()->json([
                'errors' => $e->errors(),
                'message' => 'Validacion de datos Fallida'
            ]);
        }catch(\Exception $e){
            return response()->json([
                'error' => 'A ocurrido un error',
                'error_message' => $e->getMessage()
            ],500);
        }catch(\InvalidArgumentException $e){
            return response()->json([
                'error' => 'A ocurrido un error en los argumentos',
                'error_message' => $e->getMessage()
            ],500);
        }catch(\RuntimeException $e){
            return response()->json([
                'error' => 'A ocurrido un error ',
                'error_message' => $e->getMessage()
            ],500);
        }
    }
}
