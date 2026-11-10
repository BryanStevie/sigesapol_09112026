<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Asegúrate de importar Log

class CqDestino extends Model
{
    // Define the table name
    protected $table = 'cqx_destino';

    protected $fillable = [
        'id_cuarto', 'id_cama', 'descripcion', 'pabellon', 'nombre', 'flag', 'estado', 'created_us', 'created_at'
    ];

    public function guardarDestinos($data, $iduser) {
        try {
            // Insertar un nuevo registro en la tabla cqx_destino
            CqDestino::create([
                'id_cuarto' => $data['id_cuarto'],
                'id_cama' => (int)$data['id_cama'],
                'descripcion' => $data['descripcion'],
                'pabellon' => $data['pabellon'],
                'nombre' => $data['nombre'],
                'flag' => (int)$data['flag'],
                'estado' => 1, // O el valor que corresponda
                'created_us' => $iduser,
                'created_at' => Carbon::now(), 
            ]);

            return ['success' => true, 'message' => 'Datos guardados correctamente.'];
        } catch (\Exception $e) {
            // Registrar el error
            Log::error('Error al guardar datos en cqx_destino', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // Retornar mensaje de error
            return ['success' => false, 'message' => 'Error al guardar los datos.'];
        }
    }
}
