<?php

namespace App\Services;

use App\Repositories\AssigmentsRepository;
use App\Services\UserService;


use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class AssigmentsService
{
    protected $assignmetsRespository;
    protected $userService;

    public function __construct(AssigmentsRepository $assignmetsRespository, UserService $userService )
    {
        $this->assignmetsRespository = $assignmetsRespository;
        $this->userService = $userService;
    }

    public function createAssignment(array $assignments)
    {

        //reglasd de validacion
        $validator = Validator::make($assignments, [
            'user_id' => 'nullable|int',
            'request_id' => 'nullable|int',
            'metodo_assignments' => 'required|string',
            'algorithm' => 'required|string|in:random, sequential, equity, direct',
            'role' => 'required|string',

        ]);

        if($validator->fails()){
            throw new ValidationException($validator);
        }


        switch($assignments['algorithm'])
        {
            case 'random':
                try{
                    $this->algorithmRamdom($assignments);
                }catch(\RuntimeException $e){
                    return response()->json(['Error' => $e->getMessage()],500);
                }

                break;
        }

        //return $this->assignmetsRespository->createAssignment($assignments);
    }

    //asigna a un usuario a alzar una solicutud
    private function algorithmRamdom($assignments)
    {

        //verificar datos correspondietes id request para asignar
        if(!isset($assignments['role']) || !isset($assignments['request_id'])){
            throw new \InvalidArgumentException('El dato request id es requerido para el metodo de asignacion Random');
        }

        $rol = $assignments['role'];
        $users = $this->userService->getUsersRol($rol);

        if($users->isEmpty()){
            throw new \Exception("Error al obtener usuarios con el rol:'$rol', verifique que el rol exista");
        }

        print_r($users->toArray());

    }
}
