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
                    return $newAssignment;
                } catch (\RuntimeException $e) {
                    return response()->json(['Error' => $e->getMessage()], 500);
                }
                break;
            case 'sequential':
                try {
                    $newAssignment = $this->algorithSequential($assignments['role']);
                    return $newAssignment;
                } catch (\RuntimeException $e) {
                    return response()->json(['Error' => $e->getMessage()], 500);
                }
                break;
            case 'equity':
                try {
                    $newAssignment = $this->algorithEquity($assignments['role']);
                    return $newAssignment;
                } catch (\RuntimeException $e) {
                    return response()->json(['Error' => $e->getMessage()], 500);
                }
                break;
            case 'direct':
                try {
                    $newAssignment = $this->algorithDirect($assignments);
                    return $newAssignment;
                } catch (\RuntimeException $e) {
                    return response()->json(['Error' => $e->getMessage()], 500);
                }
                break;
            default:
                throw new \Exception("Error el metodo de asignacion incorrecto");
            break;
        }

    }

    public function getAllAsignments(){
        try{
            return $this->assignmetsRespository->getAllAssignment();
        }catch(\Exception $e){
            throw new \Exception("Error al obtener los asignamientos");
        }

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

        //verificamos si existe el request
        $request = $this->requestService->getRequestById($assignments['request_id']);

        if(!$request){
            throw new \Exception("Error al obtener la solicitud verifique que exista");
        }

        if($request->estado_request != 'pendiente'){
            throw new \InvalidArgumentException("El estado de la solicitud es: '$request->estado_request' verifique que el estado sea: pendiente");
        }

        //id usuario random dentro del rango de usuarios obtenidos
        $userIds = $users->pluck('id')->toArray();
        $minId = min($userIds);
        $maxId = max($userIds);

        $randomId = rand($minId, $maxId);



        $data = [
            'user_id' => $randomId,
            'request_id' => $assignments['request_id'],
            'metodo_assignments' => $assignments['algorithm']
        ];


        //asignamos user a solicitudes
        try {
            $this->assignmetsRespository->createAssignment($data);
            //actualizar estados de request asignados
            $this->requestService->updateRequestStatus($request['id'], 'asignado');
        } catch (\Exception $e) {
            throw new \Exception("Error en la asignacion de usuarios a request '{$e}'");
        }


        return  $this->dataRequest($data);

        //print_r("es el nuevo dato '$newUser'");

    }


    private function algorithSequential($rol)
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
                //$assigmentsHistory[] = $data;
                $assigmentsHistory[] = $this->dataRequest($data);
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


    private function algorithEquity($rol)
    {
         $assigmentsHistory =[];
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

                $assigmentsHistory[] = $this->dataRequest($data);

            } catch (\Exception $e) {
                throw new \Exception("Error en la asignacion de usuarios a request '{$e}'");
            }

            $assignmentsCount[$userId]++;
        }

        return $assigmentsHistory;
    }

    private function algorithDirect($assignments){
        //verificar datos correspondietes id request para asignar
        if (!isset($assignments['role']) || !isset($assignments['request_id']) || !isset($assignments['user_id'])) {
            throw new \InvalidArgumentException('Verifique que esta enviando los datos rol, user_id, request_id para la asignacion Directa');
        }

        //validamos el usuario con el rol y solicitud con estado pendiente
        $userRol = $this->userService->getUserById($assignments['user_id']);
        $stateRequest = $this->requestService->getRequestById($assignments['request_id']);

        if(!$userRol){
            throw new \InvalidArgumentException('El usuario no existe');
        }

        if($userRol['role'] !=$assignments['role'] ){
            throw new \InvalidArgumentException('El usuario a asignar no concide con el rol especificado');
        }

        if(!$userRol){
            throw new \InvalidArgumentException('Usuario no encontrado verifique que el usuario exista');
        }

        if($stateRequest->estado_request != 'pendiente'){
            throw new \InvalidArgumentException("El estado de la solicitud es: '$stateRequest->estado_request' verifique que el estado sea: pendiente");
        }

        if (!$stateRequest) {
            throw new \InvalidArgumentException('Solicitud no encontrada');
        }


        //asignamos
        $data = [
            'user_id' => $assignments['user_id'],
            'request_id' => $assignments['request_id'],
            'metodo_assignments' => 'direct'
        ];

        try{
            $this->assignmetsRespository->createAssignment($data);
            //actualizar estados de request asignados
            $this->requestService->updateRequestStatus($assignments['request_id'], 'asignado');
        }catch(\Exception $e){
            throw new \InvalidArgumentException('Error al asignar la solicitud al usuario');
        }


        return $this->dataRequest($data);

    }

    private function dataRequest($data){
        try{
            $user = $this->userService->getUserById($data['user_id']);
            $requestTitle = $this->requestService->getRequestById($data['request_id']);
        }catch(\Exception $e){
            throw new \RuntimeException('Error en la obtencion de datos');
        }
        $dataRequest = [
            'user' => $user->name,
            'request_title' => $requestTitle->titulo_request,
            'metodo_assignments' => $data['metodo_assignments']
        ];

        return $dataRequest;

    }
}
