<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class request extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('request')->insert([
            [
                'titulo_request' => 'Problema con el servidor',
                'descripcion_request' => 'El servidor principal no responde desde ayer.',
                'estado_request' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo_request' => 'Fugas en la oficina 2',
                'descripcion_request' => 'Se detectaron fugas de agua en la oficina 2 durante la inspección.',
                'estado_request' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo_request' => 'Computadora no arranca',
                'descripcion_request' => 'La computadora del empleado en el escritorio 5 no arranca.',
                'estado_request' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo_request' => 'Problemas con la impresora',
                'descripcion_request' => 'La impresora de la sala de reuniones está atascada con papel.',
                'estado_request' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo_request' => 'Falta de suministros de oficina',
                'descripcion_request' => 'Se necesita reponer los suministros de oficina en el área de recepción.',
                'estado_request' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo_request' => 'Actualización del software',
                'descripcion_request' => 'Se requiere actualizar el software en todas las computadoras del departamento de ventas.',
                'estado_request' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo_request' => 'Cámara de seguridad rota',
                'descripcion_request' => 'La cámara de seguridad en la entrada principal no está funcionando correctamente.',
                'estado_request' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo_request' => 'Falta de luz en la oficina 7',
                'descripcion_request' => 'La luz en la oficina 7 no enciende, se necesita revisar el sistema eléctrico.',
                'estado_request' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo_request' => 'Problema con el aire acondicionado',
                'descripcion_request' => 'El aire acondicionado en el área de la cocina no está enfriando adecuadamente.',
                'estado_request' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo_request' => 'Ropa de cama para sala de descanso',
                'descripcion_request' => 'Se necesita ropa de cama nueva para la sala de descanso del personal.',
                'estado_request' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
