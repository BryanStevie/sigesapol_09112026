<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HospitalizacionOrden extends Model
{
    protected $table = 'hospitalizacion_ordenes';

    public function prueba()
    {
        $id_upss_responsable = DB::table('upsses')
            ->where('id_consultorio', 14)
            ->where('id_tipo_atencion', 3)
            ->where('estado', 1)
            ->value('id');
            return $id_upss_responsable;
    }
    
    public function RegistrarOrdenDeHospitalizacionSinValidar($id_establecimiento, $id_tipo_atencion, $id_atencion, $id_asegurado, $fecha_derivacion, $id_medico, $id_sala, $id_usuario, $id_upss_responsable, $id_upss_traslado, $id_lugar_operacion,$id_servicio_especial) 
    {
        $orden = new HospitalizacionOrden();
        $orden->id_establecimiento = $id_establecimiento;
        $orden->id_tipo_atencion = $id_tipo_atencion;
        $orden->id_ext = $id_atencion;
        $orden->id_asegurado = $id_asegurado;
        $orden->fecha_derivacion = $fecha_derivacion;
        $orden->id_medico = $id_medico;
        $orden->id_sala = $id_sala;
        //$orden->flag = 0; //Indica Sin validar
        $orden->flag = 1;// se habilitara para que puedan admisionar y ya no necesitara ser validado por el anestesiologo quirurgico
        $orden->created_us = $id_usuario;
        $orden->id_upss_responsable = $id_upss_responsable;
        $orden->id_upss_traslado = $id_upss_traslado;
        $orden->flag_clinica_dia = $id_lugar_operacion?$id_lugar_operacion:0;
        $orden->id_servicio_especial = $id_servicio_especial;
        $orden->save();
    }

    public function ValidarOrdenDeCentroQuirurgico($id_tipo_atencion, $id_atencion) 
    {
        $orden = HospitalizacionOrden::where('id_ext', $id_atencion)
            ->where('id_tipo_atencion', $id_tipo_atencion)
            ->first();
        $orden->flag = 1;
        return $orden->save();
    }

    public function GetOrdenById($id)
    {

        $orden = DB::table('hospitalizacion_ordenes as ho')
            ->select(
                'ho.id',
                'ho.id_establecimiento',
                'ho.id_ext',
                'ho.id_tipo_atencion',
                'a.tipo_doc_ident',
                'a.nro_doc_ident',
                DB::raw("to_char(ho.fecha_derivacion,'DD/MM/YYYY') as fecha_derivacion"),
                DB::raw("to_char(ho.fecha_derivacion,'HH:MI:SS AM') as hora_derivacion"),
                'a.nombre',
                'a.paterno',
                'a.materno',
                'tb.descripcion as tipo_beneficiario',
                'a.sexo',
                'a.cip',
                'ah.nro_historia',
                'e.nombre as establecimiento',
                'c.nombre as sala',
                'm.dni as medico_dni',
                'm.nombre as medico_nombre',
                'm.paterno as medico_paterno',
                'm.materno as medico_materno',
                'u.codigo as codigo_upss_responsable',
                'u.descripcion_upss as descripcion_upss_responsable',
                'u2.codigo as codigo_upss_traslado',
                'u2.descripcion_upss as descripcion_upss_traslado'
            )
            ->join('establecimientos as e', 'ho.id_establecimiento', '=', 'e.id')
            ->join('consultorios as c', 'ho.id_sala', '=', 'c.id')
            ->join('asegurados as a', 'ho.id_asegurado', '=', 'a.id')
            ->leftJoin('tipo_beneficiarios as tb', 'a.id_tipo_beneficiario', '=', 'tb.id')
            ->leftJoin('asegurado_historias as ah', function ($join) {
                $join->on('ho.id_asegurado', '=', 'ah.id_asegurado')
                    ->on('ho.id_establecimiento', '=', 'ah.id_establecimiento');
            })
            ->join('medicos as m', 'ho.id_medico', '=', 'm.id')
            ->join('upsses as u', 'u.id', '=', 'ho.id_upss_responsable')
            ->join('upsses as u2', 'u2.id', '=', 'ho.id_upss_traslado')
            ->where('ho.id', $id)
            ->first();
        return $orden;
    }

    public function GetDiagnosticosByIdExtYTipoAtencion($id_ext, $id_tipo_atencion)
    {
        $diagnosticos = null;
        switch ($id_tipo_atencion) {
            case 2:
                $diagnostico1 = DB::table('emergencias as e')
                    ->join('diagnosticos as d1', 'e.id_diag_cab', '=', 'd1.id')
                    ->where('e.id', $id_ext)
                    ->select(DB::raw("1 as orden"), 'd1.codigo', 'd1.nombre');

                $diagnostico2 = DB::table('emergencias as e')
                    ->join('diagnosticos as d2', 'e.id_diag_cuer1', '=', 'd2.id')
                    ->where('e.id', $id_ext)
                    ->select(DB::raw("2 as orden"), 'd2.codigo', 'd2.nombre');

                $diagnostico3 = DB::table('emergencias as e')
                    ->join('diagnosticos as d3', 'e.id_diag_cuer2', '=', 'd3.id')
                    ->where('e.id', $id_ext)
                    ->select(DB::raw("3 as orden"), 'd3.codigo', 'd3.nombre');

                $diagnosticos = $diagnostico1
                    ->union($diagnostico2)
                    ->union($diagnostico3)
                    ->orderBy('orden', 'asc')
                    ->get();
            break;
            case 4: 
            $diagnosticos = DB::table('cqx_intervenciones as i')
                ->join('receta_diagnosticos as rd', 'i.id_prestacion', '=', 'rd.id_prestacion')
                ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
                ->where('i.id', $id_ext)
                ->where('i.id_estado_reg', 1)
                ->where('rd.estado', 1)
                ->select('i.id', 'i.id_prestacion', 'd.codigo', 'd.nombre')
                ->orderBy('rd.id', 'asc')
                ->get();
            break;
        }
        return $diagnosticos;
    }

    public function GetOrdenById2($id)
    {
        $orden = DB::table('hospitalizacion_ordenes as ho')
            ->join('tipo_atenciones as ta', 'ho.id_tipo_atencion', '=', 'ta.id')
            ->where('ho.id_estado_reg', 1)
            ->where('ho.id', $id)
            ->select(
                'ho.*',
                DB::raw("cast(ho.fecha_derivacion as date) as fecha_derivacion_date"),
                DB::raw("cast(ho.fecha_derivacion as time) as hora_derivacion"),
                'ta.nombre as tipo_atencion'
            )
            ->first();
        return $orden;
    }

    public function GetDatosById($id)
    {
        $orden = HospitalizacionOrden::find($id);
        $data = null;
        if ($orden->id_tipo_atencion == 2) {
            $data = DB::table('emergencias as e')
                ->select(
                    'e.id','e.fecha_alta_medica','e.hora_alta_medica','tdi.descripcion as tipo_doc_ident','a.id_tipo_doc_ident',
                    'a.nro_doc_ident','a.nombre','a.paterno','a.materno','a.fecha_nac','a.sexo','tb.descripcion as parentesco',
                    'ah.nro_historia as nro_historia_paciente','a.id_raza','a.id_tipo_sangre','a.id_estado_civil','a.id_religion',
                    'a.id_grado_instruccion','a.id_ocupacion','a.domicilio','a.id_distrito as id_distrito_domicilio',
                    'd.id_provincia as id_provincia_domicilio','p2.id_departamento as id_departamento_domicilio','m.id as id_medico',
                    'm.dni as medico_ndocumento','m.paterno as medico_paterno','m.materno as medico_materno','m.nombre as medico_nombre',
                    'm.colegiatura as medico_colegiatura','m.rne as medico_rne','p.nombre as medico_profesion',
                    'e2.nombre as medico_especializacion','u2.id as id_upss_destino','u2.descripcion_upss as descripcion_upss_destino',
                    'c.id as id_servicio_destino','c.nombre as nombre_servicio_destino','e.id_diag_cab','e.id_diag_cuer1',
                    'e.id_diag_cuer2'
                )
                ->join('asegurados as a', 'e.id_asegurado', '=', 'a.id')
                ->join('users as u', 'e.id_medico_egreso', '=', 'u.id')
                ->join('medicos as m', function($join) {
                    $join->on('u.dni', '=', 'm.dni')
                        ->on('u.id_establecimiento', '=', 'm.id_establecimiento');
                })
                ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
                ->leftJoin('especializaciones as e2', 'm.id_especializacion', '=', 'e2.id')
                ->leftJoin('tipo_documento_identidad as tdi', 'a.id_tipo_doc_ident', '=', 'tdi.id')
                ->leftJoin('tipo_beneficiarios as tb', 'tb.id', '=', 'a.id_tipo_beneficiario')
                ->leftJoin('asegurado_historias as ah', function($join) {
                    $join->on('e.id_asegurado', '=', 'ah.id_asegurado')
                        ->on('ah.id_establecimiento', '=', 'e.id_establecimiento');
                })
                ->leftJoin('distritos as d', 'a.id_distrito', '=', 'd.id_distrito')
                ->leftJoin('provincias as p2', 'd.id_provincia', '=', 'p2.id_provincia')
                ->leftJoin('upsses as u2', 'e.id_upss_alta', '=', 'u2.id')
                ->leftJoin('consultorios as c', 'u2.id_consultorio', '=', 'c.id')
                ->where('e.id', $orden->id_ext)
                ->first();

        } else if ($orden->id_tipo_atencion == 1) {

            $data = DB::table('hospitalizacion_ordenes as ho')
            ->select([
                'ho.id',
                DB::raw('CAST(ho.fecha_derivacion AS DATE) as fecha_alta_medica'),
                DB::raw('CAST(ho.fecha_derivacion AS TIME) as hora_alta_medica'),
                'a.id_tipo_doc_ident',
                'a.tipo_doc_ident',
                'a.nro_doc_ident',
                'a.nombre',
                'a.paterno',
                'a.materno',
                'a.fecha_nac',
                'a.sexo',
                'tb.descripcion as parentesco',
                'ah.nro_historia as nro_historia_paciente',
                'a.id_raza',
                'a.id_tipo_sangre',
                'a.id_estado_civil',
                'a.id_religion',
                'a.id_grado_instruccion',
                'a.id_ocupacion',
                'a.domicilio',
                'a.id_distrito as id_distrito_domicilio',
                'd.id_provincia as id_provincia_domicilio',
                'p2.id_departamento as id_departamento_domicilio',
                'm.id as id_medico',
                'm.dni as medico_ndocumento',
                'm.paterno as medico_paterno',
                'm.materno as medico_materno',
                'm.nombre as medico_nombre',
                'm.colegiatura as medico_colegiatura',
                'm.rne as medico_rne',
                'p.nombre as medico_profesion',
                'e2.nombre as medico_especializacion',
                'u2.id as id_upss_destino',
                'u2.descripcion_upss as descripcion_upss_destino',
                'c.id as id_servicio_destino',
                'c.nombre as nombre_servicio_destino',
                DB::raw("(SELECT diag.id_diagnostico FROM (SELECT ROW_NUMBER() OVER(ORDER BY rd.id_diagnostico) AS orden, rd.id_diagnostico 
                          FROM receta_diagnosticos rd WHERE ci.id_prestacion = rd.id_prestacion) diag WHERE diag.orden = 1) as id_diag_cab"),
                DB::raw("(SELECT diag.id_diagnostico FROM (SELECT ROW_NUMBER() OVER(ORDER BY rd.id_diagnostico) AS orden, rd.id_diagnostico 
                          FROM receta_diagnosticos rd WHERE ci.id_prestacion = rd.id_prestacion) diag WHERE diag.orden = 2) as id_diag_cuer1"),
                DB::raw("(SELECT diag.id_diagnostico FROM (SELECT ROW_NUMBER() OVER(ORDER BY rd.id_diagnostico) AS orden, rd.id_diagnostico 
                          FROM receta_diagnosticos rd WHERE ci.id_prestacion = rd.id_prestacion) diag WHERE diag.orden = 3) as id_diag_cuer2")
            ])
            ->join('asegurados as a', 'ho.id_asegurado', '=', 'a.id')
            ->leftJoin('tipo_beneficiarios as tb', 'a.id_tipo_beneficiario', '=', 'tb.id')
            ->leftJoin('asegurado_historias as ah', function($join) {
                $join->on('ho.id_asegurado', '=', 'ah.id_asegurado')
                     ->on('ho.id_establecimiento', '=', 'ah.id_establecimiento');
            })
            ->leftJoin('distritos as d', 'a.id_distrito', '=', 'd.id_distrito')
            ->leftJoin('provincias as p2', 'd.id_provincia', '=', 'p2.id_provincia')
            ->join('medicos as m', 'ho.id_medico', '=', 'm.id')
            ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
            ->leftJoin('especializaciones as e2', 'm.id_especializacion', '=', 'e2.id')
            ->leftJoin('upsses as u2', 'ho.id_upss_responsable', '=', 'u2.id')
            ->leftJoin('consultorios as c', 'u2.id_consultorio', '=', 'c.id')
            ->join('cqx_intervenciones as ci', 'ho.id_ext', '=', 'ci.id')
            ->where('ho.id', '=', $orden->id)
            ->first();
        }
        return $data;
    }
    
    public function AdmisionarOrden($id)
    {
        $orden = HospitalizacionOrden::find($id);
        $orden->flag = 2;
        return $orden->save();
    }
    
    public function RegistrarOrdenDeHospitalizacionDesdeEmergencia($id_emergencia,$requiere_intervencion_quirurgica)
    {
        $emergencia = DB::table('emergencias as e')
            ->join('users as u', 'e.id_medico_egreso', '=', 'u.id')
            ->join('medicos as m', function($join) {
                $join->on('u.dni', '=', 'm.dni')
                    ->on('u.id_establecimiento', '=', 'm.id_establecimiento');
            })
            ->where('e.id', '=', $id_emergencia)
            ->select(
                'e.id', 
                'e.id_establecimiento', 
                'e.id_asegurado', 
                DB::raw("CAST((CAST(e.fecha_alta_medica AS date) || ' ' || e.hora_alta_medica) AS timestamp) as fecha_alta_medica"), 
                'm.id as id_medico', 
                'e.id_servicio_destino', 
                'e.id_medico_egreso as id_usuario_egreso', 
                'e.id_upss_alta',
                /*'e.id_servicio_destino_ho',
                'e.id_pabellon_destino',
                'e.id_cuarto_destino'*/
                'e.id_edificio_ubicacion',
                'e.id_pabellon_ubicacion'
            )
            ->first();

        if ($emergencia != null) {
            $orden = new HospitalizacionOrden();
            $orden->id_establecimiento = $emergencia->id_establecimiento;
            $orden->id_tipo_atencion = 2;
            $orden->id_ext = $emergencia->id;
            $orden->id_asegurado = $emergencia->id_asegurado;
            $orden->fecha_derivacion = $emergencia->fecha_alta_medica;
            $orden->id_medico = $emergencia->id_medico;
            $orden->id_sala = $emergencia->id_servicio_destino;
            $orden->flag = 1;
            $orden->created_us = $emergencia->id_usuario_egreso;
            $orden->id_upss_responsable = $emergencia->id_upss_alta;
            $orden->id_upss_traslado = $emergencia->id_upss_alta;
            $orden->flag_quirurgico= $requiere_intervencion_quirurgica;
            // $orden->id_servicio_destino_hospi = $emergencia->id_servicio_destino_ho;
            // $orden->id_pabellon_destino = $emergencia->id_pabellon_destino;
            // $orden->id_cuarto_destino = $emergencia->id_cuarto_destino;
            $orden->id_edificio_ubicacion = $emergencia->id_edificio_ubicacion;
            $orden->id_pabellon_ubicacion = $emergencia->id_pabellon_ubicacion;
            $orden->save();
        }
    }
    public function flagQuirurgico($id_atencion,$id_tipo_atencion) 
    {
        $flag = HospitalizacionOrden::where('id_ext', $id_atencion)
            ->where('id_tipo_atencion', $id_tipo_atencion)
            ->where('flag_quirurgico',1)
            ->exists();
        return $flag;
    }

    public function GetVerUbicacionTraslado($id)
    {

        $orden = DB::table('hospitalizacion_ordenes as ho')
            ->select(
                'u.codigo as codigo_upss_responsable',
                'u.descripcion_upss as descripcion_upss_responsable',
                'c.nombre as descripcion_servicio_responsable'
              //  'c2.nombre as descripcion_servicio_destino',
              //  'pa.nombre as pabellon_traslado',
              //  'ca.nombre as cama_traslado'
            )
            ->join('establecimientos as e', 'ho.id_establecimiento', '=', 'e.id')
            ->join('consultorios as c', 'ho.id_sala', '=', 'c.id')
            //->leftjoin('consultorios as c2', 'ho.id_servicio_destino_hospi', '=', 'c2.id')
            ->join('upsses as u', 'u.id', '=', 'ho.id_upss_responsable')
            //->leftjoin('pabellones as pa', 'pa.id', '=', 'ho.id_pabellon_destino')
            //->leftjoin('camas as ca', 'ca.id', '=', 'ho.id_cuarto_destino')
            ->where('ho.id', $id)
            ->first();
        return $orden;
    }

}
