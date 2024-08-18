<?php

namespace App\Repositories;
use App\Models\AssignmentsModel;


class AssigmentsRepository
{
    public function createAssignment(array $assignments) {
        return AssignmentsModel::create($assignments);
    }
}
