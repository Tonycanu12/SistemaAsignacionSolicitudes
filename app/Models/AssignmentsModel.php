<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentsModel extends Model
{
    use HasFactory;

    protected  $table = 'assigments';

    protected $fillable = [
        'user_id',
        'request_id',
        'metodo_assignments'
    ];
}
