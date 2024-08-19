<?php

namespace App\Services;

use App\Models\AssignmentsModel;
use App\Repositories\AssigmentsRepository;
use App\Services\UserService;
use App\Services\RequestService;


use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class AssigmentsService
{
    protected $assignmetsRespository;
    protected $userService;
    protected $requestService;
    protected $newAssignment;

    public function __construct(
        AssigmentsRepository $assignmetsRespository,
        UserService $userService,
        RequestService $requestService
    ) {
        $this->assignmetsRespository = $assignmetsRespository;
        $this->userService = $userService;
        $this->requestService = $requestService;
    }

    public function createAssignment(array $assignments)
    {

        //reglasd de validacion
        $validator = Validator::make($assignments, [
            'user_id' => 'nullable|int',
            'request_id' => 'nullable|int',
            'algorithm' => 'required|string',
            'role' => 'required|string',

        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }


        switch ($assignments['algorithm']) {
            case 'random':
                try {
                    $newAssignment = $this->algorithmRamdom($assignments);
                } catch (\RuntimeException $e) {
                    return response()->json(['Error' => $e->getMessage()], 500);
                }
                break;
            case 'sequential':
                try {
                    $newAssignment = $this->algorithSecuencial($assignments['role']);
                    return $newAssignment;
                } catch (\RuntimeException $e) {
                    return response()->json(['Error' => $e->getMessage()], 500);
                }
                break;
            case 'equity':
                try {
                    $newAssignment = $this->algorithEquidad($assignments['role']);
                    return $newAssignment;
                } catch (\RuntimeException $e) {
                    return response()->json(['Error' => $e->getMessage()], 500);
                }
                break;
        }
        //este es del random
        $this->assignmetsRespository->createAssignment($newAssignment);
        return $newAssignment;
    }

    //asigna a un usuario a alzar una solicutud
    private function algorithmRamdom($assignments)
    {

        //verificar datos correspondietes id request para asignar
        if (!isset($assignments['role']) || !isset($assignments['request_id'])) {
            throw new \InvalidArgumentException('El dato request id es requerido para el metodo de asignacion Random');
        }

        //verificamos usuarios con el rol
        $rol = $assignments['role'];
        $users = $this->userService->getUsersRol($rol);
        if ($users->isEmpty()) {
            throw new \Exception("Error al obtener usuarios con el rol:'$rol', verifique que el rol exista");
        }

        //id usuario random dentro del rango de usuarios obtenidos
        $userIds = $users->pluck('id')->toArray();
        $minId = min($userIds);
        $maxId = max($userIds);

        $randomId = rand($minId, $maxId);


        return [
            'user_id' => $randomId,
            'request_id' => $assignments['request_id'],
            'metodo_assignments' => $assignments['algorithm']
        ];
        //print_r("es el nuevo dato '$newUser'");

    }


    private function algorithSecuencial($rol)
    {
        //verificamos usuarios con el rol
        $users = $this->userService->getUsersRol($rol);
        if ($users->isEmpty()) {
            throw new \Exception("Error al obtener usuarios con el rol:'$rol', verifique que el rol exista");
        }

        //obtener solicitudes con estado pendiente
        $requestPending = $this->requestService->getAllRequest();
        if ($requestPending->isEmpty()) {
            throw new \Exception("Sin solicitudes verifique que tenga solicitudes con estado pendiente");
        }

        $countUsers = $users->count();
        $userIds = $users->pluck('id')->toArray();
        $indexUser = 0;
        $assigmentsHistory = [];

        foreach ($requestPending as $request) {
            $data = [
                'user_id' => $userIds[$indexUser],
                'request_id' => $request['id'],
                'metodo_assignments' => 'Secuencial'
            ];

            //asignamos user a solicitudes
            try {
                $this->assignmetsRespository->createAssignment($data);
                //actualizar estados de request asignados
                $this->requestService->updateRequestStatus($request['id'], 'asignado');
                $assigmentsHistory[] = $data;
            } catch (\Exception $e) {
                throw new \Exception("Error en la asignacion de usuarios a request '{$e}'");
            }

            $indexUser++;
            if ($indexUser >= $countUsers) {
                $indexUser = 0;
            }
        }
        return $assigmentsHistory;
    }


    private function algorithEquidad($rol)
    {
        //verificamos usuarios con el rol
        $users = $this->userService->getUsersRol($rol);
        if ($users->isEmpty()) {
            throw new \Exception("Error al obtener usuarios con el rol:'$rol', verifique que el rol exista");
        }

        $userIds = $users->pluck('id')->toArray();

        //obtener solicitudes con estado pendiente
        $requestPending = $this->requestService->getAllRequest();
        if ($requestPending->isEmpty()) {
            throw new \Exception("Sin solicitudes verifique que tenga solicitudes  pendientes");
        }

        $countrequestPending = $requestPending->count();

        //obtener solicitudes asignadas anteriormente filtrados por usuarios segun su rol
        try {
            $assignmentsUsers = $this->assignmetsRespository->getAllAssignmentByStatus($userIds);
        } catch (\Exception $e) {
            throw new \Exception("A ocurrido un error en la caraga de solicitudes ya asignadas '$e'");
        }

        //asignacion de solicitudes
        $assignmentsCount = [];

        if (!$assignmentsUsers->isEmpty()) {
            foreach ($assignmentsUsers->toArray() as $assignment) {
                $assignmentsCount[$assignment['user_id']] = $assignment['total'];
            }
        }

        //validamos que todos los usuarios obtenidos esten dentro de assignmentsCount
        foreach ($users as $user) {
            if (!isset($assignmentsCount[$user->id])) {
                $assignmentsCount[$user->id] = 0;
            }
        }

        foreach($requestPending as $request){
            //buscamos el usuario con el numero menor de asiganaciones previas
            $userId = array_search(min($assignmentsCount),$assignmentsCount);

            $data = [
                'user_id' => $userId,
                'request_id' => $request->id,
                'metodo_assignments' => 'equity'
            ];

             //asignamos user a solicitudes
             try {
                $this->assignmetsRespository->createAssignment($data);
                //actualizar estados de request asignados
                $this->requestService->updateRequestStatus($request['id'], 'asignado');
                //$assigmentsHistory[] = $data;

            } catch (\Exception $e) {
                throw new \Exception("Error en la asignacion de usuarios a request '{$e}'");
            }

            $assignmentsCount[$userId]++;
            //print_r($data);

        }
        return "asignados con equity '$countrequestPending'";
    }
}
