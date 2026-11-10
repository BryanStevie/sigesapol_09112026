<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CqxAnestesia extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'anestesia_quirurgicas';

    public function agregarProductoRecetas($id_receta_vale,$medicamentos) 
    {
        $cad = "Select sp_insert_productorecetas(?,?)";
    
        $data = DB::select($cad, array($id_receta_vale, $medicamentos));

        return $data[0]->sp_insert_productorecetas;
  
    }

    public function addDiagnosticoAdicional($id_prestacion, $diagnosticos, $user) {
        $cad = "Select sp_agregar_diagnostico_adicional(?,?,?)";
        $data = DB::select($cad, array($id_prestacion, $diagnosticos, $user));
        return $data[0]->sp_agregar_diagnostico_adicional;
    }
    public function addProcedimientoAdicional($id_prestacion, $diagnosticos, $user) {
        $cad = "Select sp_agregar_procedimiento_adicional(?,?,?)";
        $data = DB::select($cad, array($id_prestacion, $diagnosticos, $user));
        return $data[0]->sp_agregar_procedimiento_adicional;
    }
    public function registrarPrestacionAnestesiologia($id_cita, $id_asegurado,
      $id_tipo_atencion, $id_establecimiento, $id_servicio,
      $fecha_atencion, $fecha_alta, $id_medico, $id_motivo_alta,
      $id_estado_reg, $observacion, $id_receta_vale,
       $id_farmacia, $id_user, $nro_receta, $digitos_adicionales,
       $id_anestesiologo, $obs_registro, $id_receta_vale_bus,
      $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia,
      $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales,
      $p_estado,$condicion, $cantidadReceta, $p_procedimiento,     
      $p_producto, $p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita, $id_modalidad, $sexo, $fecha_nacimiento, $codigo_upss, $adicional,$registrar_cita, $id_prestacion,$flag_ref_cref)
    {
        $cad = "Select sp_insert_prestacion_anestesiologia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; //49 parametros
        $data = DB::select($cad, array($id_cita, $id_asegurado, $id_tipo_atencion, $id_establecimiento, $id_servicio, $fecha_atencion, $fecha_alta, $id_medico, $id_motivo_alta, $id_estado_reg, $observacion, $id_receta_vale, $id_farmacia, $id_user, $nro_receta, $digitos_adicionales, $id_anestesiologo, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales, $p_estado,$condicion, $cantidadReceta, $p_procedimiento, $p_producto, $p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita, $id_modalidad, $sexo, $fecha_nacimiento, $codigo_upss, $adicional,$registrar_cita, $id_prestacion,$flag_ref_cref));

        return $data[0]->sp_insert_prestacion_anestesiologia;
    }

}
