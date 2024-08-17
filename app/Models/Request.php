<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $table = 'request';

    protected $fillable = [
        'titulo_request',
        'descripcion_request',
        'estado_request'
    ];
}
