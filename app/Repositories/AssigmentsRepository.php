<?php

namespace App\Repositories;
use App\Models\AssignmentsModel;
use Illuminate\Support\Facades\DB;

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


}
