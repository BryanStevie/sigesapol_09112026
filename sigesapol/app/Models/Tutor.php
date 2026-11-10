<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class Tutor extends Model
{
    protected $table = 'tutores';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombres',
        'apellidos',
        'telefono_tutor',
        'observacion_tutor',
        'id_parentesco',
        'direccion',
        'estado',
        'created_at',
        'created_us',
        'updated_at',
        'updated_us'
    ];

  public function actualizarTutor($obj)
    {
        $tutor = Tutor::find($obj->id_tutor);
        $tutor->tipo_documento    = $obj->tipo_documento;
        $tutor->numero_documento  = $obj->numero_documento;
        $tutor->nombres           = $obj->nombres;
        $tutor->apellidos         = $obj->apellidos;
        $tutor->telefono_tutor    = $obj->telefono_tutor;
        $tutor->observacion_tutor = $obj->observacion_tutor;
        $tutor->id_parentesco     = $obj->id_parentesco;
        $tutor->direccion         = $obj->direccion;
        $tutor->updated_at        = Carbon::now();
        $tutor->updated_us        = Auth::user()->id;
        $tutor->save();
        return true;
    }
    
    public function registrarTutor($arrayData)
    {
        $tutor = new Tutor();
        $tutor->tipo_documento = $arrayData->tipo_documento;
        $tutor->numero_documento = $arrayData->numero_documento;
        $tutor->nombres = $arrayData->nombres;
        $tutor->apellidos = $arrayData->apellidos;
        $tutor->telefono_tutor = $arrayData->telefono_tutor;
        $tutor->observacion_tutor = $arrayData->observacion_tutor;
        $tutor->id_parentesco = $arrayData->id_parentesco;
        $tutor->direccion = $arrayData->direccion;
        $tutor->estado = 1;
        $tutor->flag = 1; 
        $tutor->created_at = Carbon::now();
        $tutor->created_us = Auth::user()->id;
        $tutor->save();
        
        return $tutor->id; 
    }

     public function tipo_tutor()
    {
        $cad = "SELECT * FROM tutores WHERE estado = '1' Order by id asc";
        $data = DB::select($cad);
        return $data;
    }

    public function obtener_tutor($id)
    {
        if (is_null($id)) {
            return null;
        }

        $cad = "SELECT * FROM tutores WHERE estado = '1' AND id = $id";
        $data = DB::select($cad);
        return $data;
    }
}
