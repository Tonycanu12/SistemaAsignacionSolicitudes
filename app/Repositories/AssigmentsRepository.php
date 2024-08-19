<?php

namespace App\Repositories;
use App\Models\AssignmentsModel;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

class AssigmentsRepository
{
    public function createAssignment(array $assignments) {
        return AssignmentsModel::create($assignments);
    }

    public function getAllAssignmentByStatus($usersId){
        //return AssignmentsModel::whereIn('user_id',$usersId)->get();
        return AssignmentsModel::whereIn('user_id', $usersId)
                                ->select('user_id', DB::raw('count(*) as total'))
                                ->groupBy('user_id')
                                ->get();
    }


    public function getAllAssignment(){
        return AssignmentsModel::join('users', 'assigments.user_id','=', 'users.id')
                                ->join('request','assigments.request_id','=', 'request.id' )
                                ->select('users.name as name','request.descripcion_request as description','assigments.metodo_assignments as metod')->get();
    }


}
