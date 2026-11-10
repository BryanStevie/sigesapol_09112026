<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Log;
use App\Models\Prestacion_procedimiento;
use App\Models\PrestacionProcedimientoProveedor;
use App\Models\Labnivelatencion;
use App\Models\LaboratorioTomaMuestraHistorial;
use Auth;

class Laboratorio extends Model {

    protected $table = 'laboratorios';
    public $timestamps = false;
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'id_prestacion',
        'id_establecimiento',
        'fecha_atencion',
        'fecha_firma',
        'ruta_laboratorio',
        'tipo_bioquimica',
        'tipo_hematologia',
        'tipo_inmunologia',
        'tipo_microbiologia',
        'tipo_anatomia',
        'tipo_citopatologia',
        'tipo_citogenetico',
        'tipo_quirurgica',
        'tipo_otro',
        'tipo_reproductiva',
        'tipo_covid',
        'rpta_bioquimica',
        'rpta_hematologia',
        'rpta_inmunologia',
        'rpta_microbiologia',
        'rpta_anatomia',
        'rpta_citopatologia',
        'rpta_citogenetico',
        'rpta_quirugica',
        'rpta_otro',
        'rpta_reproductiva',
        'rpta_covid',
        'estado',
        'created_us',
        'updated_at',
        'updated_us',
        'deleted_us',
        'flag',
        'secuencial',
        'observacion_toma_muestra',
        'fecha_registro_toma_muestra',
        'id_usuario_tomamuestra',
        'obs_lastupdate_tomamuestra',
        'fecha_lastupdate_tomamuestra',
        'id_usuario_lastupdate_tomamuestra',
        'id_evaluacion',
        'cod_siteds_susalud',
        'obs_examen_invalidado',
        'id_user_examen_invalidado',
        'cod_orden_lab',
        'estado_labcore',
        'id_interconsulta',
        'estado_asistencia_paciente',
        'obs_asistencia_paciente',
        'id_farmacia',
        'updated_us_cod_siteds',
        'updated_at_cod_siteds',
        'rua',
        'id_asegurado',
        'estado_n_1_2',
        'cantidad_toma_muestra'
    ];
    //
    public function ShowAll() {
        $data = DB::table('laboratorios')
                ->select('laboratorios.*')
                ->orderBy('id', 'desc')
                ->get();
        return $data;
    }

    public function ExisteLaboratorio($ruc, $id) {
        $data = Laboratorio::where('ruc', '=', $ruc)->first();
        return isset($data) ? true : false;
    }

    public function GetProcedimientosByIdLaboratorio($id_prestacion, $id_examen, $id_lab_rad) {

        switch($id_examen){
            case 1: $subseccion='1.5.1';break;
            case 7: $subseccion='1.5.2';break;
            case 4: $subseccion='1.5.3';break;
            case 9: $subseccion='1.5.4';break;
            case 12: $subseccion='1.5.9';break;

            /*case 5: $subseccion='1.5.5';break;
            case 6: $subseccion='1.5.6';break;
            case 7: $subseccion='1.5.7';break;
            case 8: $subseccion='1.5.8';break;
            case 10: $subseccion='1.5.10';break;
            case 11: $subseccion='1.5.11';break;
            */
        }


        $datos = DB::table('laboratorios')
                ->join('prestacion_procedimientos', 'prestacion_procedimientos.id_prestacion', '=', 'laboratorios.id_prestacion')
                ->join('procedimientos','prestacion_procedimientos.id_procedimiento','=','procedimientos.id')
                ->select('prestacion_procedimientos.*','procedimientos.descripcion as nombre','procedimientos.codigo')
                ->where('laboratorios.id_prestacion', '=', $id_prestacion)
                ->where('prestacion_procedimientos.id_lab_rad', '=', $id_lab_rad)
                ->where('procedimientos.codigo_subseccion',  '=', $subseccion)
                ->orderBy('prestacion_procedimientos.id', 'asc')
                ->get();
        return $datos;
    }

    public function GetLaboratorioByIdPrestacion($id_prestacion) {
        $cad = "Select * from sp_obtener_laboratorio_por_id_prestacion(?)";
        $data = DB::select($cad, array($id_prestacion));
        return $data;
    }

    public function GetLaboratorioByIdPrestacionInterconsultaRpta($id_prestacion, $id_interconsulta) {
        $cad = "Select * from sp_obtener_laboratorio_por_id_prestacion_interconsulta(?,?)";
        $data = DB::select($cad, array($id_prestacion, $id_interconsulta));
        return $data;
    }

    public function GetLaboratorioByIdPrestacionEvaluacion($id_prestacion,$id_evaluacion) {
        //$datos = Laboratorio::where('id_prestacion', '=', $id_prestacion)->where('id_evaluacion', '=', $id_evaluacion)->where('estado', '<>', 0)->get();
        $datos = DB::table('laboratorios')->where('id_prestacion', '=', $id_prestacion)->where('id_evaluacion', '=', $id_evaluacion)->where('estado', '<>', 0)->orderBy('id', 'asc')->get();
        return $datos;
    }
    public function GetLaboratorioPrestacion($id_prestacion) {
        $datos = Laboratorio::where('id_prestacion', '=', $id_prestacion)->where('estado', '<>', 0)->first();
        return $datos;
    }

    public function GetLaboratorioById($id_lab) {
        $data = DB::table("laboratorios")->where("id", $id_lab)->first();
        return $data;
    }

    public function AllExamenLaboratorioByEstablecimientoAndFechaDesdeHasta(
        $id_establecimiento, 
        $fechaDesde = "", 
        $fechaHasta = "", 
        $dni_beneficiario = "", 
        /*$nro_prestacion = "", */
        $nro_examen_lab = "", 
        $tipo_atencion = "", 
        $id_servicio = "", 
        $referido = "", 
        $prioridad_emergencia = "", 
        $estado, 
        $id_farmacia
    ) {
    // dd($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen_lab, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia);
        $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

        $cad = "select prestaciones.*,asegurados.nombre nombre_beneficiario, asegurados.paterno as paterno_beneficiario,asegurados.materno as materno_beneficiario,asegurados.tipo_doc_ident, ta.id id_tipo_atencion,ta.nombre nom_tipo_atencion, u.name as digitador, asegurados.nro_doc_ident, prestaciones.created_at as fec_creacion, consultorios.nombre consultorio, ruta_laboratorio,medicos.nombre as nombre_medico, medicos.paterno as paterno_medico, medicos.materno as materno_medico, laboratorios.id as id_lab, medicos.nombre servicio, laboratorios.fecha_firma  firma_laboratorio, laboratorios.*, prestaciones.id as id_prestacion
        , DATE_PART('day', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) * 24 + DATE_PART('hour', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) as diff_horas_desde_toma_muestra, laboratorios.id_evaluacion, farmacias.nombre as nombre_farmacia,
        CASE
        WHEN EXTRACT(EPOCH FROM (NOW() - prestaciones.created_at)) / 3600 >= 2 THEN 0
        WHEN EXTRACT(EPOCH FROM (NOW() - prestaciones.created_at)) / 3600 >= 1 THEN 1
        WHEN EXTRACT(EPOCH FROM (NOW() - prestaciones.created_at)) / 3600 < 1 THEN 2
        END AS estado_emergencia";
        if ($tipo_atencion == "0") {
            $cad .= ", c.referido";
            $cad .= ", p.descripcion nom_prioridad";
            $cad .= ", t.descripcion nom_especialidad_emergencia";
            $cad .= ", eeva.unidad_policial";
            $cad .= ", cuartos.nombre cuarto, pabellones.nombre pabellon, pisos.nombre piso, camas.nombre cama";
        }
        if ($tipo_atencion == "1") {
            if ($estado == "6") {
                $cad .= ", c2.referido, c3.fecha_hora, c2.id_cita_lab, citas.fecha_hora as fecha_hora_cita_laboratorio, citas.id as id_cita_laboratorio_ce";
            } else {
                $cad .= ", c.referido, c2.fecha_hora, c.id_cita_lab, c3.fecha_hora as fecha_hora_cita_laboratorio, c3.id as id_cita_laboratorio_ce";
            }
        }
        if ($tipo_atencion == "2") {
            $cad .= ", p.descripcion nom_prioridad";
            $cad .= ", t.descripcion nom_especialidad_emergencia";
            $cad .= ", eeva.unidad_policial";
            $cad .= ", cuartos.nombre cuarto, pabellones.nombre pabellon, pisos.nombre piso, camas.nombre cama";
        }

        if ($tipo_atencion == "1") {
            if ($estado == "6"){ // VALIDAR
                $cad .= " from citas
                    inner join citas c2 on c2.id = citas.id_cita_orden_procedencia
                    inner join citas c3 on c3.id = citas.id_cita_result_procedencia
                    inner join prestaciones on prestaciones.id_cita = c2.id
                    inner join laboratorios on laboratorios.id_prestacion=prestaciones.id
                    inner join farmacias on farmacias.id = laboratorios.id_farmacia
                    inner join asegurados on prestaciones.id_asegurado=asegurados.id
                    inner join sub_consultorios on prestaciones.id_sub_consultorio=sub_consultorios.id
                    inner join consultorios on sub_consultorios.id_consultorio=consultorios.id
                    inner join medicos on prestaciones.id_medico=medicos.id
                    inner join tipo_atenciones ta on prestaciones.id_tipo_atencion =ta.id
                    inner join users u on prestaciones.created_us =u.id";
            }else {
                $cad .= " from prestaciones
                    inner join laboratorios on laboratorios.id_prestacion=prestaciones.id
                    inner join farmacias on farmacias.id = laboratorios.id_farmacia
                    inner join asegurados on prestaciones.id_asegurado=asegurados.id
                    inner join sub_consultorios on prestaciones.id_sub_consultorio=sub_consultorios.id
                    inner join consultorios on sub_consultorios.id_consultorio=consultorios.id
                    inner join medicos on prestaciones.id_medico=medicos.id
                    inner join tipo_atenciones ta on prestaciones.id_tipo_atencion =ta.id
                    inner join users u on prestaciones.created_us =u.id";
            }
        } else {
            $cad .= " from prestaciones
                    inner join laboratorios on laboratorios.id_prestacion=prestaciones.id
                    inner join farmacias on farmacias.id = laboratorios.id_farmacia
                    inner join asegurados on prestaciones.id_asegurado=asegurados.id
                    inner join sub_consultorios on prestaciones.id_sub_consultorio=sub_consultorios.id
                    inner join consultorios on sub_consultorios.id_consultorio=consultorios.id
                    inner join medicos on prestaciones.id_medico=medicos.id
                    inner join tipo_atenciones ta on prestaciones.id_tipo_atencion =ta.id
                    inner join users u on prestaciones.created_us =u.id";
        }

        if ($tipo_atencion == "0") {
            $cad .= " inner join citas c on c.id = prestaciones.id_cita  and c.id_estado_reg = 1";
            $cad .= " left join emergencias e on e.id = prestaciones.id_ext";
            $cad .= " left join prioridades p on p.id = e.id_prioridad";
            $cad .= " left join triajes t on t.id = e.id_triaje";
            $cad .= " left join emergencia_evacuados eeva on eeva.id_emergencia = e.id";
            $cad .= " left join camas on camas.id = e.cama_estado";
            $cad .= " left join cuartos on camas.id_cuarto=cuartos.id";
            $cad .= " left join asignacion_servicios on asignacion_servicios.id = cuartos.id_asignacion_servicio";
            $cad .= " left join pisos on asignacion_servicios.id_piso=pisos.id";
            $cad .= " left join pabellones on pisos.id_pabellon=pabellones.id";
            $cad .= " left join consultorios con on asignacion_servicios.id_consultorio = con.id";
        }
        if ($tipo_atencion == "1") {
            if ($estado !== "6"){ // VALIDAR
                $cad .= " inner join citas c on c.id = prestaciones.id_cita and c.id_estado_reg = 1";
                $cad .= " left join citas c2 on c2.id = c.id_cita_lab";
                $cad .= " left join citas c3 on c3.id = c.id_cita_laboratorio_ce";
            }
        }
        if ($tipo_atencion == "2") {
            $cad .= " inner join emergencias e on e.id = prestaciones.id_ext";
            $cad .= " inner join prioridades p on p.id = e.id_prioridad";
            $cad .= " inner join triajes t on t.id = e.id_triaje";
            $cad .= " left join emergencia_evacuados eeva on eeva.id_emergencia = e.id";
            $cad .= " left join camas on camas.id = e.cama_estado";
            $cad .= " inner join cuartos on camas.id_cuarto=cuartos.id";
            $cad .= " inner join asignacion_servicios on asignacion_servicios.id = cuartos.id_asignacion_servicio";
            $cad .= " inner join pisos on asignacion_servicios.id_piso=pisos.id";
            $cad .= " inner join pabellones on pisos.id_pabellon=pabellones.id";
            $cad .= " inner join consultorios con on asignacion_servicios.id_consultorio = con.id";
        }
        $cad .= " where laboratorios.id_establecimiento=" . $id_establecimiento;

        if ($dni_beneficiario):
            $cad .= " and asegurados.nro_doc_ident = '" . $dni_beneficiario . "'";
        endif;

        // if ($nro_prestacion):
        //     $cad .= " and prestaciones.id = " . $nro_prestacion;
        // endif;

        if ($nro_examen_lab):
            $cad .= " and laboratorios.id = " . $nro_examen_lab;
        endif;

        if ($tipo_atencion):
            $cad .= " and prestaciones.id_tipo_atencion = " . $tipo_atencion;
        endif;

        if ($id_servicio):
            if ($tipo_atencion !== "2") {
                $cad .= " and consultorios.id = " . $id_servicio;
            } else {
                $cad .= " and e.id_triaje = " . $id_servicio;
            }
        endif;

        if ($referido):
            if ($tipo_atencion == "1") {
                if ($estado == "6") {
                    $cad .= " and c2.referido = '" . $referido . "' ";
                } else {
                    $cad .= " and c.referido = '" . $referido . "' ";
                }
            }
            if ($tipo_atencion == "2") {
                if ($referido == "SI") {
                    $condicion = " is not null ";
                }
                if ($referido == "NO") {
                    $condicion = " is null ";
                }
                $cad .= " and eeva.unidad_policial " . $condicion;
            }
        endif;

        if ($prioridad_emergencia):
            $cad .= " and e.id_prioridad = " . $prioridad_emergencia;
        endif;
        if ($estado == "1"):
            //Pendientes
            $cad .= " and laboratorios.fecha_registro_toma_muestra is null and laboratorios.estado_asistencia_paciente is null";
        endif;
        if ($estado == "2"):
            //Atendidos sigesapol
            $cad .= " and laboratorios.fecha_registro_toma_muestra is not null";
        endif;
        if ($estado == "6"):
            //No Atendidos
            if ($tipo_atencion == "1") {
                $cad .= " and citas.estado_asistencia_paciente = 1 ";
            } else {
                $cad .= " and laboratorios.fecha_registro_toma_muestra is null and laboratorios.estado_asistencia_paciente = 1 ";
            }
        endif;

        if ($fechaDesde != "") {
            if ($tipo_atencion == "1") {
                //CE debe filtra por la cita programada
                if ($estado == "6") {
                    $cad .= " and citas.fecha_hora >= '" . $fechaDesde . "'";
                } else {
                    $cad .= " and c3.fecha_hora >= '" . $fechaDesde . "'";
                }
            } else {
                //Emergencia y hospitalización debe filtrar por orden creada
                $cad .= " and laboratorios.created_at >= '" . $fechaDesde . "'";
            }
        }
        if ($fechaHasta != "") {
            if ($tipo_atencion == "1") {
                if ($estado == "6") {
                    $cad .= " and citas.fecha_hora < '" . $last_day . "'";
                } else {
                    $cad .= " and c3.fecha_hora < '" . $last_day . "'";
                }
            } else {
                $cad .= " and laboratorios.created_at < '" . $last_day . "'";
            }
        }
        
        if ($id_farmacia > 0) {
            $cad .= " and laboratorios.id_farmacia = ". $id_farmacia ." and laboratorios.estado = 1";
        }

        if ($tipo_atencion == "1") {
            $cad .= " order By laboratorios.id desc";
        } else {
            //Priorizar EMERGENCIA ( UVI y TRAUMATOLOGÏA )
            $cad .= " order By  laboratorios.id desc
                    --case
                       --when t.descripcion = 'UNIDAD VIGILANCIA INTENSIVA' then 1
                       --when t.descripcion = 'SHOCK TRAUMA' then 2
                       --else 3
                    --end ";
        }
        // Log::info(["cad" => $cad]);
    
        $data = DB::select($cad);
        return $data;
    }

    public function GetCountExamenLaboratorioByFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */$nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia) {

        $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

            $cad = "select count(*) cantidad";
            if ($tipo_atencion == "1") {
                if ($estado == "6"){ // VALIDAR
                    $cad .= " from citas
                        inner join citas c2 on c2.id = citas.id_cita_orden_procedencia
                        inner join citas c3 on c3.id = citas.id_cita_result_procedencia
                        inner join prestaciones on prestaciones.id_cita = c2.id
                        inner join laboratorios on laboratorios.id_prestacion=prestaciones.id
                        inner join asegurados on prestaciones.id_asegurado=asegurados.id
                        inner join sub_consultorios on prestaciones.id_sub_consultorio=sub_consultorios.id
                        inner join consultorios on sub_consultorios.id_consultorio=consultorios.id
                        inner join medicos on prestaciones.id_medico=medicos.id
                        inner join tipo_atenciones ta on prestaciones.id_tipo_atencion =ta.id
                        inner join users u on prestaciones.created_us =u.id";
                }else {
                    $cad .= " from prestaciones
                        inner join laboratorios on laboratorios.id_prestacion=prestaciones.id
                        inner join asegurados on prestaciones.id_asegurado=asegurados.id
                        inner join sub_consultorios on prestaciones.id_sub_consultorio=sub_consultorios.id
                        inner join consultorios on sub_consultorios.id_consultorio=consultorios.id
                        inner join medicos on prestaciones.id_medico=medicos.id
                        inner join tipo_atenciones ta on prestaciones.id_tipo_atencion =ta.id
                        inner join users u on prestaciones.created_us =u.id";
                }
            } else {
                $cad .= " from prestaciones
                        inner join laboratorios on laboratorios.id_prestacion=prestaciones.id
                        inner join asegurados on prestaciones.id_asegurado=asegurados.id
                        inner join sub_consultorios on prestaciones.id_sub_consultorio=sub_consultorios.id
                        inner join consultorios on sub_consultorios.id_consultorio=consultorios.id
                        inner join medicos on prestaciones.id_medico=medicos.id
                        inner join tipo_atenciones ta on prestaciones.id_tipo_atencion =ta.id
                        inner join users u on prestaciones.created_us =u.id";
            }

            if ($tipo_atencion == "0") {
                $cad .= " inner join citas c on c.id = prestaciones.id_cita  and c.id_estado_reg = 1";
                $cad .= " left join emergencias e on e.id = prestaciones.id_ext";
                $cad .= " left join prioridades p on p.id = e.id_prioridad";
                $cad .= " left join triajes t on t.id = e.id_triaje";
                $cad .= " left join emergencia_evacuados eeva on eeva.id_emergencia = e.id";
                $cad .= " left join camas on camas.id = e.cama_estado";
                $cad .= " left join cuartos on camas.id_cuarto=cuartos.id";
                $cad .= " left join asignacion_servicios on asignacion_servicios.id = cuartos.id_asignacion_servicio";
                $cad .= " left join pisos on asignacion_servicios.id_piso=pisos.id";
                $cad .= " left join pabellones on pisos.id_pabellon=pabellones.id";
                $cad .= " left join consultorios con on asignacion_servicios.id_consultorio = con.id";
            }
            if ($tipo_atencion == "1") {
                if ($estado !== "6"){ // VALIDAR
                    $cad .= " inner join citas c on c.id = prestaciones.id_cita and c.id_estado_reg = 1";
                    $cad .= " left join citas c2 on c2.id = c.id_cita_lab";
                    $cad .= " left join citas c3 on c3.id = c.id_cita_laboratorio_ce";
                }
            }
            if ($tipo_atencion == "2") {
                $cad .= " inner join emergencias e on e.id = prestaciones.id_ext";
                $cad .= " inner join prioridades p on p.id = e.id_prioridad";
                $cad .= " inner join triajes t on t.id = e.id_triaje";
                $cad .= " left join emergencia_evacuados eeva on eeva.id_emergencia = e.id";
                $cad .= " left join camas on camas.id = e.cama_estado";
                $cad .= " inner join cuartos on camas.id_cuarto=cuartos.id";
                $cad .= " inner join asignacion_servicios on asignacion_servicios.id = cuartos.id_asignacion_servicio";
                $cad .= " inner join pisos on asignacion_servicios.id_piso=pisos.id";
                $cad .= " inner join pabellones on pisos.id_pabellon=pabellones.id";
                $cad .= " inner join consultorios con on asignacion_servicios.id_consultorio = con.id";
            }
            $cad .= " where laboratorios.id_establecimiento=" . $id_establecimiento;

            if ($dni_beneficiario):
                $cad .= " and asegurados.nro_doc_ident = '" . $dni_beneficiario . "'";
            endif;

            // if ($nro_prestacion):
            //     $cad .= " and prestaciones.id = " . $nro_prestacion;
            // endif;

            if ($nro_examen_lab):
                $cad .= " and laboratorios.id = " . $nro_examen_lab;
            endif;

            if ($tipo_atencion):
                $cad .= " and prestaciones.id_tipo_atencion = " . $tipo_atencion;
            endif;

            if ($id_servicio):
                if ($tipo_atencion !== "2") {
                    $cad .= " and consultorios.id = " . $id_servicio;
                } else {
                    $cad .= " and e.id_triaje = " . $id_servicio;
                }
            endif;

            if ($referido):
                if ($tipo_atencion == "1") {
                    if ($estado == "6") {
                        $cad .= " and c2.referido = '" . $referido . "' ";
                    } else {
                        $cad .= " and c.referido = '" . $referido . "' ";
                    }
                }
                if ($tipo_atencion == "2") {
                    if ($referido == "SI") {
                        $condicion = " is not null ";
                    }
                    if ($referido == "NO") {
                        $condicion = " is null ";
                    }
                    $cad .= " and eeva.unidad_policial " . $condicion;
                }
            endif;

            if ($prioridad_emergencia):
                $cad .= " and e.id_prioridad = " . $prioridad_emergencia;
            endif;
            if ($estado == "1"):
                //Pendientes
                $cad .= " and laboratorios.fecha_registro_toma_muestra is null and laboratorios.estado_asistencia_paciente is null";
            endif;
            if ($estado == "2"):
                //Atendidos sigesapol
                $cad .= " and laboratorios.fecha_registro_toma_muestra is not null";
            endif;
            if ($estado == "6"):
                //No Atendidos
                if ($tipo_atencion == "1") {
                    $cad .= " and citas.estado_asistencia_paciente = 1 ";
                } else {
                    $cad .= " and laboratorios.fecha_registro_toma_muestra is null and laboratorios.estado_asistencia_paciente = 1 ";
                }
            endif;

            if ($fechaDesde != "") {
                if ($tipo_atencion == "1") {
                    //CE debe filtra por la cita programada
                    if ($estado == "6") {
                        $cad .= " and citas.fecha_hora >= '" . $fechaDesde . "'";
                    } else {
                        $cad .= " and c3.fecha_hora >= '" . $fechaDesde . "'";
                    }
                } else {
                    //Emergencia y hospitalización debe filtrar por orden creada
                    $cad .= " and laboratorios.created_at >= '" . $fechaDesde . "'";
                }
            }
            if ($fechaHasta != "") {
                if ($tipo_atencion == "1") {
                    if ($estado == "6") {
                        $cad .= " and citas.fecha_hora < '" . $last_day . "'";
                    } else {
                        $cad .= " and c3.fecha_hora < '" . $last_day . "'";
                    }
                } else {
                    $cad .= " and laboratorios.created_at < '" . $last_day . "'";
                }
            }

            if ($id_farmacia > 0) {
                $cad .= " and laboratorios.id_farmacia = ". $id_farmacia ." and laboratorios.estado = 1";
            }

        $data = DB::select($cad);
        return $data[0]->cantidad;
    }

    public function AllMedicosByIpress($id_establecimiento) {
        $cad = "Select m.id, upper(m.nombre) nombre, upper(m.paterno) paterno, upper(m.materno) materno
                From medicos m
                Inner Join medico_consultorios mc On m.id = mc.id_medico
                Where mc.id_consultorio = 113 and mc.id_establecimiento =" . $id_establecimiento . "
                Group By m.id, m.nombre, m.paterno, m.materno";
        $data = DB::select($cad);
        return $data;
    }

    public function AllResponsableByIpress($id_establecimiento) {
        $cad = "Select u.id, upper(u.name) as name, upper(u.paterno) paterno, upper(u.materno) materno
                From users u
                Where u.tipo_registro = 5 and u.id_establecimiento =" . $id_establecimiento . "
                Group By u.id, u.name, u.paterno, u.materno";
        $data = DB::select($cad);
        return $data;
    }

    public static function RegistrarExamenBioquimica($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $glucosa1, $urea, $creatinina, $colesterol, $trigleceridos, $hdl_colesterol, $ldl_colesterol, $amilasa, $acido_urico, $proteinas_totales,$albumina1,$globulinas,$tgo,$tgp,$bilirrubina_total,$bilirrubina_directa,$bilirrubina_indirecta,$fosfatasa_alcalina) {
        $cad = "Select sp_insert_examen_bioquimica(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $glucosa1, $urea, $creatinina, $colesterol, $trigleceridos, $hdl_colesterol, $ldl_colesterol, $amilasa, $acido_urico, $proteinas_totales,$albumina1,$globulinas,$tgo,$tgp,$bilirrubina_total,$bilirrubina_directa,$bilirrubina_indirecta,$fosfatasa_alcalina));
        return $data[0]->sp_insert_examen_bioquimica;
    }

    public static function RegistrarExamenHematologia($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $hematies1, $hemoglobina, $procedimientohematocrito, $procedimientovsg, $procedimientot_coagulacion, $procedimientot_sangria, $procedimientot_protombina, $procedimientoleucocitos1, $procedimientoneutrofilos, $procedimientobasofilos,$procedimientomonocitos,$procedimientolinfocitos,$procedimientoplaquetas,$procedimientomielcitos,$procedimientometamielcitos,$procedimientoabastonados,$procedimientosegmentados) {
        $cad = "Select sp_insert_examen_hematologia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $hematies1, $hemoglobina, $procedimientohematocrito, $procedimientovsg, $procedimientot_coagulacion, $procedimientot_sangria, $procedimientot_protombina, $procedimientoleucocitos1, $procedimientoneutrofilos, $procedimientobasofilos,$procedimientomonocitos,$procedimientolinfocitos,$procedimientoplaquetas,$procedimientomielcitos,$procedimientometamielcitos,$procedimientoabastonados,$procedimientosegmentados));
        return $data[0]->sp_insert_examen_hematologia;
    }

    public static function RegistrarExamenMicrobiologia($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $muestra1, $aspecto1, $consistencia, $color1, $lecucitos, $hematies2, $levaduras,$parasitos,$polimorfonucleares,$mononucleares,$observaciones1,$color2,$aspecto2,$densidad,$muestra2,$hematies3,$lecucitos2,$celulas_epiteliales,$cristales,$cilindros,$germenes,$piocitos,$albumina2,$glucosa2,$urobilina,$pigmentos_biliares,$cuerpos_cetonicos,$ac_ascorbico,$nitritos,$thevenon,$observaciones2) {
        $cad = "Select sp_insert_examen_microbiologia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $muestra1, $aspecto1, $consistencia, $color1, $lecucitos, $hematies2, $levaduras,$parasitos,$polimorfonucleares,$mononucleares,$observaciones1,$color2,$aspecto2,$densidad,$muestra2,$hematies3,$lecucitos2,$celulas_epiteliales,$cristales,$cilindros,$germenes,$piocitos,$albumina2,$glucosa2,$urobilina,$pigmentos_biliares,$cuerpos_cetonicos,$ac_ascorbico,$nitritos,$thevenon,$observaciones2));
        return $data[0]->sp_insert_examen_microbiologia;
    }

    public static function RegistrarExamenInmunoserologia($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $aglutinaciones_febriles, $tifico_h, $tifico_o, $paratifico_a, $paratifico_b, $brucellas_placa, $vdrl_rpr, $proteina_c_reactiva, $latex_reumatoideo, $antiestreptolisina,$rosa_bangals,$prueba_tubo,$mercaptoetanol,$hbctotal ,$havgm ,$hbcigm ,$aghbs ,$hcv ,$hiv ) {
        $cad = "Select sp_insert_examen_inmunoserologia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $aglutinaciones_febriles, $tifico_h, $tifico_o, $paratifico_a, $paratifico_b, $brucellas_placa, $vdrl_rpr, $proteina_c_reactiva, $latex_reumatoideo, $antiestreptolisina,$rosa_bangals,$prueba_tubo,$mercaptoetanol,$hbctotal ,$havgm ,$hbcigm ,$aghbs ,$hcv ,$hiv));
        return $data[0]->sp_insert_examen_inmunoserologia;
    }

    public static function MostrarResultadoExamen($id_lab, $tipo_examen ) {

        switch ($tipo_examen) {
            case '1': $tabla='bioquimicas';break;
            case '2': $tabla='marcadores_cardiacos';break;
            case '3': $tabla='pruebas_inmunidad';break;
            case '4': $tabla='inmunoserologias';break;
            case '5': $tabla='marcadores_tumorales';break;
            case '6': $tabla='marcadores_virales';break;
            case '7': $tabla='hematologias';break;
            case '8': $tabla='prueba_coagulacion';break;
            case '9': $tabla='microbiologias';break;
            case '10': $tabla='banco_sangre';break;

        }

        $cad = "select * from ".$tabla."
                where id_laboratorio = ".$id_lab;

        $data = DB::select($cad);

        return $data[0];

    }

    public function GetExamenLaboratorioByDni($p)
    {
        return $this->readFunctionPostgres('sp_obtener_examen_laboratorio_por_dni', $p);
    }


    public function ActualizaIdEvaluacion($id, $id_evaluacion) {

        $datos = Laboratorio::where('id', $id)->first();
        $datos->id_evaluacion = $id_evaluacion;
        $datos->save();

        return true;

    }

    /*public function AllExamenLaboratorioByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_prestacion = "", $nro_examen_lab = "") {

        $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

        $cad = "select prestaciones.*,asegurados.nombre nombre_beneficiario, asegurados.paterno as paterno_beneficiario,asegurados.materno as materno_beneficiario,asegurados.tipo_doc_ident, asegurados.nro_doc_ident, prestaciones.created_at as fec_creacion, consultorios.nombre consultorio, ruta_laboratorio,medicos.nombre as nombre_medico, medicos.paterno as paterno_medico, medicos.materno as materno_medico, laboratorios.id as id_lab, medicos.nombre servicio, laboratorios.fecha_firma  firma_laboratorio, laboratorios.*, prestaciones.id as id_prestacion
        from prestaciones
        inner join laboratorios on laboratorios.id_prestacion=prestaciones.id
        inner join asegurados on prestaciones.id_asegurado=asegurados.id
        inner join sub_consultorios on prestaciones.id_sub_consultorio=sub_consultorios.id
        inner join consultorios on sub_consultorios.id_consultorio=consultorios.id
        inner join medicos on prestaciones.id_medico=medicos.id
        where laboratorios.id_establecimiento=" . $id_establecimiento;

        if ($dni_beneficiario):
            $cad .= " and asegurados.nro_doc_ident = '" . $dni_beneficiario . "'";
        endif;

        if ($nro_prestacion):
            $cad .= " and prestaciones.id = " . $nro_prestacion;
        endif;

        if ($nro_examen_lab):
            $cad .= " and laboratorios.id = " . $nro_examen_lab;
        endif;


            if ($fechaDesde != "")
                $cad .= " and laboratorios.created_at >= '" . $fechaDesde . "'";
            if ($fechaHasta != "")
                $cad .= " and laboratorios.created_at < '" . $last_day . "'";
        $cad .= " order By laboratorios.id desc";



        $data = DB::select($cad);
        return $data;
    }
    */

    public function GetDataParaLIS($id_prestacion, $id_laboratorio, $id_tipo_atencion, $id_evaluacion) {

      $cad = "SELECT * FROM sp_listar_orden_laboratorio_lns(?,?,?,?) AS FOO (
        id_tipo_atencion integer,
        tipo_atencion varchar,
        fecha_atencion text,
        id_establecimiento integer,
        establecimiento varchar,
        id_consultorio integer,
        servicio varchar,
        ndoc_medico varchar,
        nombre_medico text,
        nro_historia varchar,
        nro_doc_ident varchar,
        nombre_paciente text,
        sexo_paciente varchar,
        fnac_paciente text,
        parentesco_paciente varchar,
        upss varchar,
        codigo_cie varchar,
        descripcion_cie varchar,
        tipo_diagnostico text,
        id_prestacion integer,
        cod_procedimiento varchar,
        desc_procedimiento text,
        estado_lab varchar,
        fecha_registro_orden text,
        observacion varchar,
        secuencial integer,
        nombre_usuario text,
        apepaterno_paciente varchar,
        apematerno_paciente varchar,
        prenombres_paciente varchar,
        codigo_proveedor varchar,
        cpms_personalizado varchar,
        id_servicio integer,
        grado varchar,
        paciente_situacion varchar,
        servicio_area varchar
        );";

        $data = DB::select($cad, [$id_prestacion, $id_laboratorio, $id_tipo_atencion, $id_evaluacion]);
        return $data;
    }

    public function GetOrdenesPorCitaAgrupada($id_cita_laboratorio_agrupa) {
        return DB::table('laboratorios')
            ->join('prestaciones', 'laboratorios.id_prestacion', '=', 'prestaciones.id')
            ->join('citas', 'prestaciones.id_cita', '=', 'citas.id')
            ->where('citas.id_cita_laboratorio_ce', $id_cita_laboratorio_agrupa)
            ->whereNull('laboratorios.estado_asistencia_paciente')
            ->lists('laboratorios.id');
    }

    private function validarSecuencialVentanaMedianoche(Carbon $fecha_actual, $secuencia) {
        // Ventana de riesgo: los primeros minutos del día, cuando aún pueden llegar
        // secuenciales "de ayer" rezagados por el corte de medianoche del WS de Laboratorio.
        // Fuera de esta ventana no se valida nada (comportamiento intacto durante el día).
        if ($fecha_actual->format('H:i') > '00:10') {
            return;
        }

        $secuencia = intval($secuencia);
        // El contador del WS es compartido con otro sistema (RUA desktop) que también genera
        // órdenes contra él, así que el primer secuencial del día para el SIGESAPOL puede no ser
        // exactamente 1 si el otro sistema ya consumió uno o más antes. Se acepta un rango
        // bajo (1 a 10) como señal de reseteo real; fuera de ese rango es indistinguible de
        // un secuencial alto rezagado de ayer.
        $secuencial_min_esperado = 1;
        $secuencial_max_esperado = 15;

        if ($secuencia < $secuencial_min_esperado || $secuencia > $secuencial_max_esperado) {
            Log::warning("Laboratorio: posible desfase de medianoche en secuencial del WS LNS. " .
                "Secuencial recibido: {$secuencia} (se esperaba un valor entre {$secuencial_min_esperado} " .
                "y {$secuencial_max_esperado} dentro de los primeros 10 minutos del día). " .
                "Se rechaza el registro para evitar un Código de orden duplicado.");
            throw new \Exception("No se pudo generar el secuencial de la Orden de Laboratorio: " .
                "el servicio web externo aún no actualiza su fecha (posible desfase de medianoche). " .
                "Por favor, vuelva a intentarlo en un minuto.");
        }
    }

    public function ConfirmarTomaMuestra($id_prestacion, $tipo_procedimiento, $prueba, $observacion, $id_user, $secuencia, $id_laboratorio, $cod_siteds_susalud, $pendiente, $ordenes_id = null) {
        Log::info("Entro en ConfirmarTomaMuestra()");
        $res = "";
        $id_prestacion_procedimiento_error = 0;
        $hay_examen_pendiente = false;
        $cant_examen_pendiente = 0;

        if ($ordenes_id != null) {
            $ordenes_id = array_unique(
                array_map('intval',
                    array_map('trim', is_string($ordenes_id) ? explode(',', $ordenes_id) : $ordenes_id)
                )
            );
        }

        $fecha_actual = Carbon::now();
        $this->validarSecuencialVentanaMedianoche($fecha_actual, $secuencia);
        $numero_orden_lab = $fecha_actual->format('y') . $fecha_actual->format('m') . $fecha_actual->format('d') . str_pad($secuencia, 4, '0', STR_PAD_LEFT);

        // DB::insert("SELECT sp_laboratorio_confirmar_toma_muestra(?,?,?,?,?,?);", [$id_prestacion, $observacion, $id_user, $secuencia, $id_laboratorio, $cod_siteds_susalud]);
        $laboratorio = Laboratorio::find($id_laboratorio);
        $laboratorio->secuencial = $secuencia;
        $laboratorio->observacion_toma_muestra = $observacion;
        $laboratorio->fecha_registro_toma_muestra = $fecha_actual;
        $laboratorio->id_usuario_tomamuestra = $id_user;
        $laboratorio->cod_siteds_susalud = $cod_siteds_susalud;
        $laboratorio->updated_at_cod_siteds = $fecha_actual;
        $laboratorio->updated_us_cod_siteds = $id_user;
        $laboratorio->flag_toma_muestra = 1;
        $laboratorio->cod_orden_lab = $numero_orden_lab;
        $laboratorio->cantidad_toma_muestra = 1;

        if ($laboratorio->save()) {
            Log::info("Save de datos toma muestra para la orden (Registrado): " . $id_laboratorio);

            $historial = new LaboratorioTomaMuestraHistorial();
            $historial->id_laboratorio = $id_laboratorio;
            $historial->observacion = $observacion;
            $historial->created_us = $id_user;
            $historial->created_at = $fecha_actual;

            if (!$historial->save()) {
                Log::error("Laboratorio: No se pudo registrar el historial de toma de muestra, id_laboratorio:" . $id_laboratorio);
                throw new \Exception("No se pudo registrar el historial de la toma de muestra.");
            } else {
                Log::info("Laboratorio: Se registró el historial de toma de muestra, id_laboratorio:" . $id_laboratorio);
            }

            foreach ($prueba as $key => $id_rua) {

                Log::info("Foreach id_rua[" . $key ."]: ". $id_rua);
                $estado_pendiente = 'false';
                if ($pendiente[$key] == "NO") {
                
                    Log::info("Foreach pendiente[" . $key ."]: ". $pendiente[$key]);
                    Log::info("Select de Presttacion_procedimieinto: id_laboratorio: " . $id_laboratorio . " - tipo_procedimiento: " . $tipo_procedimiento . " - id_rua: " . $id_rua);
                    $prestacion_procedimiento = Prestacion_procedimiento::where("id_lab_rad", $id_laboratorio)
                                                ->where("tipo_procedimiento", $tipo_procedimiento)
                                                ->where("id_rua", $id_rua)
                                                ->first();
                    Log::info("Resultado del select: prestacion_procedimiento: " . json_encode($prestacion_procedimiento));
                    $prestacion_procedimiento->confirmar_toma = "0";
                    $prestacion_procedimiento->confirmar_toma_created_at = $fecha_actual;
                    $prestacion_procedimiento->confirmar_toma_created_us = $id_user;

                    if (!$prestacion_procedimiento->save()) {
                        Log::error("Error al registrar la confirmación de la toma de muestra => id_prestacion_procedimiento: " . $prestacion_procedimiento->id);
                        throw new \Exception("Error al registrar la confirmación de la toma de muestra => id_prestacion_procedimiento: " . $prestacion_procedimiento->id);
                    } else {
                        Log::info("Si se logro actualizar la confirmacion de toma del examen con id_rua: " . $id_rua);

                        if ($ordenes_id != null) {
                            $cpms = Labnivelatencion::where('id', $id_rua)->value('cpms_personalizado');
                            
                            if ($cpms != null) {
                                $pp = Prestacion_procedimiento::from('prestacion_procedimientos as pp')
                                    ->join('labnivelatenciones as lna', 'pp.id_rua', '=', 'lna.id')
                                    ->whereIn('pp.id_lab_rad', $ordenes_id)
                                    ->where('lna.cpms_personalizado', $cpms)
                                    ->where('pp.id_estado_reg', 1)
                                    ->whereNull('pp.confirmar_toma')
                                    ->orderBy('pp.id')
                                    ->lists('id_rua');

                                Log::info("Resultado del select: prestacion_procedimiento buscando por cpms: " . json_encode($pp));
                                if (!$pp->isEmpty()) {
                                    
                                    $afectados = Prestacion_procedimiento::whereIn('id_lab_rad', $ordenes_id)
                                        ->where('tipo_procedimiento', $tipo_procedimiento)
                                        ->whereIn('id_rua', $pp->toArray())
                                        ->update([
                                            'confirmar_toma'            => '4',
                                            'confirmar_toma_created_at' => $fecha_actual,
                                            'confirmar_toma_created_us' => $id_user,
                                        ]);

                                    if ($afectados > 0) {
                                        Log::info("Se actualizaron {$afectados} registros correctamente.");
                                    } else {
                                        Log::warning("No se actualizó ningún registro.");
                                        throw new \Exception("Error al registrar la confirmación de la toma de muestra 4 => id_rua: " . $id_rua);
                                    }
                                }
                            }
                        }
                    }

                } else if ($pendiente[$key] == "SI") {
                    $hay_examen_pendiente = true;
                    $cant_examen_pendiente++;
                }
            }
        } else {
            Log::error("Error al registrar los datos de toma de muestra => id_laboratorio: " . $id_laboratorio);
            throw new \Exception("Error al registrar los datos de toma de muestra => id_laboratorio: " . $id_laboratorio);
        }

        // DB::commit();
        if (!$hay_examen_pendiente) {
            $res = "Y";
        } else {
            if (count($prueba) == $cant_examen_pendiente) {
                $res = "P"; // Todos los examenes de la orden son Pendientes.
            } else {
                $res = "Y";
            }
        }

      return $res;
    }


    public function updateEstadoMigrate($tipo_procedimiento, $prueba, $id_laboratorio, $pendiente){

        $realizado = false;
        // DB::beginTransaction();

        // try {
            foreach ($prueba as $index => $id_rua) {

                $estado_pendiente = 'false';

                if ($pendiente[$index] == "NO") {
                //echo $id_rua."<br>";
                // $estado_lab = $pendiente[$index] == "SI" ? 1 : 0;
                /*$affected2 = DB::update("UPDATE prestacion_procedimientos SET migrado_lab = true 
                                        WHERE id_lab_rad = ? AND tipo_procedimiento = ? 
                                        AND id_rua = ? AND ",
                                        [$id_laboratorio, $tipo_procedimiento, $id_rua]);*/
                $prestacion_procedimiento = Prestacion_procedimiento::where("id_lab_rad", $id_laboratorio)
                                        ->where("tipo_procedimiento", $tipo_procedimiento)
                                        ->where("id_rua", $id_rua)
                                        ->first();

                $prestacion_procedimiento->migrado_lab = true;
                
                if (!$prestacion_procedimiento->save()) {
                    Log::error("Error al actualizar flag de 'migrado_lab' de los exámenes de laboratorio => id_prestacion_procedimiento: " . $prestacion_procedimiento->id);
                    throw new \Exception("Error al actualizar flag de 'migrado_lab' de los exámenes de laboratorio => id_prestacion_procedimiento: " . $prestacion_procedimiento->id);
                }

                /*$sql = "select * from  prestacion_procedimientos WHERE id_prestacion = $id_prestacion AND tipo_procedimiento = $tipo_procedimiento
                AND id_rua = $id_rua AND id_lab_rad = $id_laboratorio";

                $pp = DB::select($sql);
                if(count($pp)!= 0){
                DB::update(
                    "UPDATE prestacion_procedimientos_proveedor 
                    SET pendiente_laboratorio = ? 
                    WHERE id_prestacion_procedimientos = ?",
                    [$estado_lab, $pp[0]->id]
                );
                }*/
                } else if ($pendiente[$index] == "SI") {
                    // 
                }
            
            }
            // DB::commit();
            $realizado = true;
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     Log::error("Laboratorio Actualizar flag 'migrado_lab'" . 
        //         "\n\nmensaje: " . $e->getMessage()
        //     );
            
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     Log::critical("Laboratorio Actualizar flag 'migrado_lab'" . 
        //         "\n\nmensaje: " . $th->getMessage()
        //     );
            
        // }
        return $realizado;
    }

    public function RegistrarResultadosLaboratorioLNS($resultados_lab) {
        $res = "";
        DB::beginTransaction();
        try {
            if (false) {
                    $laboratorio_resultado = new Laboratorio_resultado;
                    $laboratorio_resultado->o_numero = $resultados_lab->o_numero;
                    $laboratorio_resultado->o_fecha_toma = $resultados_lab->o_fecha_toma;
                    $laboratorio_resultado->h_numero = $resultados_lab->h_numero;
                    $laboratorio_resultado->l_resultado = $resultados_lab->l_resultado;
                    $laboratorio_resultado->l_estado = $resultados_lab->l_estado;
                    $laboratorio_resultado->l_resultcomp = $resultados_lab->l_resultcomp;
                    $laboratorio_resultado->l_fecha_val = $resultados_lab->l_fecha_val;
                    $laboratorio_resultado->l_hora_val = $resultados_lab->l_hora_val;
                    $laboratorio_resultado->l_ref_inf = $resultados_lab->l_ref_inf;
                    $laboratorio_resultado->l_ref_sup = $resultados_lab->l_ref_sup;
                    $laboratorio_resultado->p_nombre = $resultados_lab->p_nombre;
                    $laboratorio_resultado->l_ralt_id = $resultados_lab->l_ralt_id;
                    $laboratorio_resultado->ra_texto = $resultados_lab->ra_texto;
                    $laboratorio_resultado->e_nombre = $resultados_lab->e_nombre;
                    $laboratorio_resultado->e_cod_alterno = $resultados_lab->e_cod_alterno;
                    $laboratorio_resultado->s_nombre = $resultados_lab->s_nombre;
                    $laboratorio_resultado->c_id = $resultados_lab->c_id;
                    $laboratorio_resultado->c_nombre = $resultados_lab->c_nombre;
                    $laboratorio_resultado->g_nombre = $resultados_lab->g_nombre;
                    $laboratorio_resultado->usr_name = $resultados_lab->usr_name;
                    $laboratorio_resultado->l_unidades = $resultados_lab->l_unidades;
                    $laboratorio_resultado->g_orden_imp = $resultados_lab->g_orden_imp;
                    $laboratorio_resultado->l_orden_imp = $resultados_lab->l_orden_imp;
                    $laboratorio_resultado->usr_image = $resultados_lab->usr_image;
                    $laboratorio_resultado->usr_login = $resultados_lab->usr_login;
                    $laboratorio_resultado->p_comentario = $resultados_lab->p_comentario;
                    $laboratorio_resultado->pr_descripcion = $resultados_lab->pr_descripcion;
                    $laboratorio_resultado->o_parentesco = $resultados_lab->o_parentesco;
                    $laboratorio_resultado->h_nombres = $resultados_lab->h_nombres;
                    $laboratorio_resultado->h_sexo = $resultados_lab->h_sexo;
                    $laboratorio_resultado->h_fecha_nacimiento = $resultados_lab->h_fecha_nacimiento;
                    $laboratorio_resultado->l_fecha = $resultados_lab->l_fecha;
                    $laboratorio_resultado->l_fecha_crea = $resultados_lab->l_fecha_crea;
                    $laboratorio_resultado->l_hora_crea = $resultados_lab->l_hora_crea;
                    $laboratorio_resultado->l_fecha_imp = $resultados_lab->l_fecha_imp;
                    $laboratorio_resultado->l_hora_imp = $resultados_lab->l_hora_imp;
                    $laboratorio_resultado->p_item_comentario = $resultados_lab->l_notavalidacion;
                    $laboratorio_resultado->o_profesional_solicita = $resultados_lab->o_profesional_solicita;
                    $laboratorio_resultado->o_departamento_solicita = $resultados_lab->o_departamento_solicita;
                    $laboratorio_resultado->save();
            } else {
                foreach ($resultados_lab as $obj) {
                    // dd($obj);
                    $laboratorio_resultado = new Laboratorio_resultado;
                    $laboratorio_resultado->o_numero = $obj->o_numero;
                    $laboratorio_resultado->o_fecha_toma = $obj->o_fecha_toma;
                    $laboratorio_resultado->h_numero = $obj->h_numero;
                    $laboratorio_resultado->l_resultado = $obj->l_resultado;
                    $laboratorio_resultado->l_estado = $obj->l_estado;
                    $laboratorio_resultado->l_resultcomp = $obj->l_resultcomp;
                    $laboratorio_resultado->l_fecha_val = $obj->l_fecha_val;
                    $laboratorio_resultado->l_hora_val = $obj->l_hora_val;
                    $laboratorio_resultado->l_ref_inf = $obj->l_ref_inf;
                    $laboratorio_resultado->l_ref_sup = $obj->l_ref_sup;
                    $laboratorio_resultado->p_nombre = $obj->p_nombre;
                    $laboratorio_resultado->l_ralt_id = $obj->l_ralt_id;
                    $laboratorio_resultado->ra_texto = $obj->ra_texto;
                    $laboratorio_resultado->e_nombre = $obj->e_nombre;
                    $laboratorio_resultado->e_cod_alterno = $obj->e_cod_alterno;
                    $laboratorio_resultado->s_nombre = $obj->s_nombre;
                    $laboratorio_resultado->c_id = $obj->c_id;
                    $laboratorio_resultado->c_nombre = $obj->c_nombre;
                    $laboratorio_resultado->g_nombre = $obj->g_nombre;
                    $laboratorio_resultado->usr_name = $obj->usr_name;
                    $laboratorio_resultado->l_unidades = $obj->l_unidades;
                    $laboratorio_resultado->g_orden_imp = $obj->g_orden_imp;
                    $laboratorio_resultado->l_orden_imp = $obj->l_orden_imp;
                    $laboratorio_resultado->usr_image = $obj->usr_image;
                    $laboratorio_resultado->usr_login = $obj->usr_login;
                    $laboratorio_resultado->p_comentario = $obj->p_comentario;
                    $laboratorio_resultado->pr_descripcion = $obj->pr_descripcion;
                    $laboratorio_resultado->o_parentesco = $obj->o_parentesco;
                    $laboratorio_resultado->h_nombres = $obj->h_nombres;
                    $laboratorio_resultado->h_sexo = $obj->h_sexo;
                    $laboratorio_resultado->h_fecha_nacimiento = $obj->h_fecha_nacimiento;
                    $laboratorio_resultado->l_fecha = $obj->l_fecha;
                    $laboratorio_resultado->l_fecha_crea = $obj->l_fecha_crea;
                    $laboratorio_resultado->l_hora_crea = $obj->l_hora_crea;
                    $laboratorio_resultado->l_fecha_imp = $obj->l_fecha_imp;
                    $laboratorio_resultado->l_hora_imp = $obj->l_hora_imp;
                    $laboratorio_resultado->p_item_comentario = $obj->l_notavalidacion;
                    $laboratorio_resultado->o_departamento_solicita = $obj->o_departamento_solicita;
                    $laboratorio_resultado->o_profesional_solicita = $obj->o_profesional_solicita;
                    $laboratorio_resultado->save();
                }
            }

            DB::commit();
            $res = "Y";
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        return $res;
    }



    
    function prestacion_procedimientos_proveedor($id_prestacion,$id_laboratorio){


      $string_id_prestacion_procedimientos = "SELECT * FROM 
          prestacion_procedimientos 
          WHERE id_prestacion = $id_prestacion AND id_lab_rad = $id_laboratorio";

      $id_prestacion_procedimientos_raw = DB::select($string_id_prestacion_procedimientos);

      foreach ($id_prestacion_procedimientos_raw as $key => $value) {
        $existe = DB::table('prestacion_procedimientos_proveedor')
                  ->where('id_prestacion_procedimientos', $value->id)
                  ->exists();
        if ($existe) {
            // Sí existen filas
        } else {
            // No existen filas
            
            $labnivelAtenciones = DB::select(
              "select * from labnivelatenciones where id = $value->id_rua"
            );
            $proveedor = DB::select(
              "select * from proveedores_apoyo_diagnostico where id = ".$labnivelAtenciones[0]->codigo_proveedor
            );
            
            DB::table('prestacion_procedimientos_proveedor')->insert([
              'id_prestacion_procedimientos' => $value->id,
              'id_proveedor' => $proveedor[0]->id,
              'cpms_personalizado' => $labnivelAtenciones[0]->cpms_personalizado,
              
              'created_us' => auth()->id(),
              'pendiente_laboratorio' => false,
          ]);
        }
      }
    }
    /*
    Esta funcion es la copia de "GetProcedimientosByIdLaboratorio" pero sin tomar
    en cuenta el servicio al que pertenece cada prueba, ya sea (Bioquimica, Hematologia, etc)
    */
    public function GetAllProcedimientosByIdLaboratorio($id_prestacion, $id_lab_rad) {


    

    // $this->prestacion_procedimientos_proveedor($id_prestacion, $id_lab_rad);


    

       

      $datos = DB::table('prestacion_procedimientos')
                ->join('labnivelatenciones', 'labnivelatenciones.id', '=', 'prestacion_procedimientos.id_rua')
                ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
                ->leftJoin('prestacion_procedimientos_proveedor', 'prestacion_procedimientos.id', '=', 'prestacion_procedimientos_proveedor.id_prestacion_procedimientos')
                ->leftJoin('tipo_muestra', 'tipo_muestra.id', '=', 'labnivelatenciones.id_tipo_muestra')
                ->leftJoin('diagnosticos', 'diagnosticos.id','=','prestacion_procedimientos.id_diagnostico')
                ->select('procedimientos.id as id_procedimiento',
                'labnivelatenciones.descripcion_corta as nombre',
                'procedimientos.descripcion as nombre_completo',
                'procedimientos.codigo','prestacion_procedimientos.confirmar_toma',
                'prestacion_procedimientos.cantidad', 
                'prestacion_procedimientos.id as id_prestacion_procedimientos', 
                'diagnosticos.codigo as relacion_codigo_diagnostico', 
                'diagnosticos.nombre as relacion_nombre_diagnostico',
                'tipo_muestra.nombre as tipo_muestra_nombre',
                'prestacion_procedimientos_proveedor.pendiente_laboratorio',
                'prestacion_procedimientos.observacion','prestacion_procedimientos.tipo_muestra_microbiologia',
                'prestacion_procedimientos.id_rua',
                'prestacion_procedimientos.migrado_lab')
                ->where('prestacion_procedimientos.id_prestacion', '=', $id_prestacion)
                ->where('prestacion_procedimientos.id_lab_rad', '=', $id_lab_rad)
                ->where('prestacion_procedimientos.tipo_procedimiento', '=', 2)
                ->orderBy('prestacion_procedimientos.id', 'asc')
                ->get();
      return $datos;
  }

    public function ModificarTomaMuestra($id_prestacion, $id_laboratorio, $observacion, $id_user, $array_idrua, $array_estado,$pendiente, $examenes) {
        $res = "";
        DB::beginTransaction();
        try {

            /*$longitud_cpt = count($array_idrua);

            for ($i = 0; $i < $longitud_cpt; $i++) {

                $estado   = $array_estado[$i];
                $id_rua   = $array_idrua[$i];

                $affected = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?
                                        WHERE id_prestacion = ? AND id_lab_rad = ? AND id_rua = ? ",
                                        [$estado, $id_prestacion, $id_laboratorio, $id_rua]);


                $prestaciones_procedimientos = DB::select("SELECT * FROM prestacion_procedimientos 
                                        WHERE id_prestacion = ? AND id_lab_rad = ? AND id_rua = ? ",
                                        [ $id_prestacion, $id_laboratorio, $id_rua]);

                
                foreach ($prestaciones_procedimientos as $key => $value) {
                  # code...
                  $estado_pendiente = 'false';
                  if($pendiente[$i] == "SI")
                    $estado_pendiente = 'true';
                  DB::update("UPDATE prestacion_procedimientos_proveedor SET pendiente_laboratorio = $estado_pendiente
                                        WHERE id_prestacion_procedimientos = ".$value->id);
                }
                
            }

            $affected2 = DB::update("UPDATE laboratorios SET obs_lastupdate_tomamuestra = ?, fecha_lastupdate_tomamuestra = now(), id_usuario_lastupdate_tomamuestra = ? WHERE id = ? ",
                                        [$observacion, $id_user, $id_laboratorio]);*/

            foreach ($examenes as $key => $id_rua) {

                // Verificar que el examen a confirmar ya no esta como pendiente
                $estado_pendiente = 'false';

                if ($pendiente[$key] == "NO") {

                    $prestacion_procedimiento = Prestacion_procedimiento::where("id_lab_rad", $id_laboratorio)
                                                ->where("tipo_procedimiento", 2)
                                                ->where("id_rua", $id_rua)
                                                ->first();
                    
                    $prestacion_procedimiento->confirmar_toma = $array_estado[$key];
                    $prestacion_procedimiento->confirmar_toma_created_at = Carbon::now();
                    $prestacion_procedimiento->confirmar_toma_created_us = $id_user;

                    if ($prestacion_procedimiento->save()) {

                        $estado_pendiente = 'false';
                        if ($pendiente[$key] == "SI") $estado_pendiente = 'true';

                        $actualizado = DB::update("UPDATE prestacion_procedimientos_proveedor SET pendiente_laboratorio = $estado_pendiente WHERE id_prestacion_procedimientos = ". $prestacion_procedimiento->id);

                        if (!$actualizado) {
                            throw new \Exception("Error al actualizar el estado de pendiente del id_prestacion_procedimiento: " . $prestacion_procedimiento->id);
                        }
                    } else {
                        throw new \Exception("Error al registrar la confirmación de la toma de muestra => id_prestacion_procedimiento: " . $prestacion_procedimiento->id);
                    }
                } else if ($pendiente[$key] == "SI") {
                    // 
                }
            }

            $laboratorio = Laboratorio::find($id_laboratorio);
            $laboratorio->obs_lastupdate_tomamuestra = $observacion;
            $laboratorio->fecha_lastupdate_tomamuestra = Carbon::now();
            $laboratorio->id_usuario_lastupdate_tomamuestra = $id_user;

            DB::commit();
            $res = "Y";
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        return $res;
    }


  

    public function GetDataModificadaParaLIS($id_prestacion, $id_laboratorio, $id_tipo_atencion, $id_evaluacion) {

        $cad = "SELECT * FROM sp_listar_orden_laboratorio_lns_modificado(?,?,?,?) AS FOO (
          id_tipo_atencion integer,
          tipo_atencion varchar,
          fecha_atencion text,
          id_establecimiento integer,
          establecimiento varchar,
          id_consultorio integer,
          servicio varchar,
          ndoc_medico varchar,
          nombre_medico text,
          nro_historia varchar,
          nro_doc_ident varchar,
          nombre_paciente text,
          sexo_paciente varchar,
          fnac_paciente text,
          parentesco_paciente varchar,
          upss varchar,
          codigo_cie varchar,
          descripcion_cie varchar,
          tipo_diagnostico text,
          id_prestacion integer,
          cod_procedimiento varchar,
          desc_procedimiento text,
          estado_lab varchar,
          fecha_registro_orden text,
          observacion varchar,
          secuencial integer,
          nombre_usuario text,
          apepaterno_paciente varchar,
          apematerno_paciente varchar,
          prenombres_paciente varchar,
          codigo_proveedor varchar,
          cpms_personalizado varchar,
          id_servicio integer,
          grado varchar,
          paciente_situacion varchar,
          servicio_area varchar
          );";

          $data = DB::select($cad, [$id_prestacion,$id_laboratorio,$id_tipo_atencion, $id_evaluacion]);
          return $data;
    }

    public function getRua($nivel, $tipo_atencion, $categoria=0, $id_consultorio=0, $id_categoria_rua_nivel = 0){
        $cad = "select l.id, p.descripcion, l.descripcion_corta, p.codigo, proveedores_apoyo_diagnostico.nombre as descripcion_proveedor, l.cpms_personalizado
              from labnivelatenciones l
              inner join procedimientos p on l.id_procedimiento = p.id
              left join proveedores_apoyo_diagnostico on proveedores_apoyo_diagnostico.codigo_proveedor = l.codigo_proveedor
              where l.id_tipo_atencion=".$tipo_atencion." and l.estado=1  and l.id_nivel=".$nivel;
        if ($tipo_atencion == 1 && $id_consultorio !== 0) {
            $cad .= " and l.id_consultorio = ".$id_consultorio;
        }
        if($categoria!=0) {
            $cad .= " and l.id_categoria=".$categoria;
        }
        if ($id_categoria_rua_nivel != 0) {
            $cad .= " and l.id_categoria_rua_nivel = " . $id_categoria_rua_nivel;
        }

        $cad .= " Order by l.descripcion_corta asc";
        $data = DB::select($cad);
        return $data;
    }

    public function getRua2($id_categoria_rua_nivel){
        $cad = "select l.id, p.descripcion, l.descripcion_corta, p.codigo, proveedores_apoyo_diagnostico.nombre as descripcion_proveedor, l.cpms_personalizado
              from labnivelatenciones l
              inner join procedimientos p on l.id_procedimiento = p.id
              left join proveedores_apoyo_diagnostico on proveedores_apoyo_diagnostico.codigo_proveedor = l.codigo_proveedor
              where l.id_categoria_rua_nivel = " . $id_categoria_rua_nivel . " and l.estado = 1 
              Order by l.descripcion_corta asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ExisteRuaAtenconNivel($id_procedimiento,$tipo_atencion,$nivel,$codigo_personalizado,$id_consultorio=0,$id_categoria, $id_categoria_rua_nivel = 0) {
        $cad = "select *
              from labnivelatenciones l
              inner join procedimientos p on l.id_procedimiento = p.id
              where l.id_procedimiento=".$id_procedimiento." and l.id_tipo_atencion=".$tipo_atencion." and l.estado=1 and l.id_nivel=".$nivel;
        if ($tipo_atencion == 1) {
            $cad .= " and l.id_consultorio =" . $id_consultorio;

            if ($id_categoria_rua_nivel != 0) {
                $cad .= " and l.id_categoria_rua_nivel = " . $id_categoria_rua_nivel;
            }
        }
        $cad .= " and l.cpms_personalizado ='".$codigo_personalizado."'";
        $cad .= " Order by l.descripcion_corta asc";
        
        // CÓDIGO PARA FILTRAR POR DESCRIPCIÓN Y ID_PROCEDIMIENTO
        // $cad = "select *
        //       from labnivelatenciones l
        //       inner join procedimientos p on l.id_procedimiento = p.id
        //       where l.id_procedimiento=".$id_procedimiento." and l.id_tipo_atencion=".$tipo_atencion." and l.estado=1 and l.id_nivel=".$nivel;
        // if ($tipo_atencion == 1) {
        // $cad .= " and l.id_consultorio =" . $id_consultorio;
        // }
        // $cad .= " and (l.descripcion_corta='".$descripcion."' OR l.codigo_lab IN (select  p.codigo
        //         from labnivelatenciones l
        //         inner join procedimientos p on l.id_procedimiento = p.id
        //         where l.id_tipo_atencion=".$tipo_atencion." and l.estado=1  and l.id_nivel=".$nivel." and l.id_categoria=".$id_categoria." Order by l.descripcion_corta asc))";
        // $cad .= " Order by l.descripcion_corta asc";
        //dd($cad);
        $data = DB::select($cad);
        return $data;
    }

    public static function RegistrarRuaNivelAtencion($id_procedimiento, $tipo_atencion, $nivel,$categoria,$descripcion, $id_user, $id_consultorio,$id_proveedor,$cpms_personalizado, $id_categoria_rua_nivel) {
        $cad = "Select sp_insert_rua_nivel_atencion(?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_procedimiento, $tipo_atencion, $nivel,$categoria,$descripcion, $id_user, $id_consultorio,$id_proveedor,$cpms_personalizado, $id_categoria_rua_nivel));
        return $data[0]->sp_insert_rua_nivel_atencion;
    }

    public function ActualizaEstado($id, $id_user){

        $db_update = DB::table('labnivelatenciones')
          ->where('id', $id)
          ->update(['estado' => 0, 'id_user_update' => $id_user, 'updated_at' => Carbon::now()]);

        return 1;
    }

    public function getTodasCategoriasRua()
    {
        $cad = "SELECT id, nombre FROM categoria_ruas WHERE estado = 1 ORDER BY id ASC;";
        $data = DB::select($cad);
        return $data;
    }

    public function getCategoriaNivelRua($p)
    {
        return $this->readFunctionPostgres('sp_obtener_categorias_nivel_rua',$p);
    }

    public function ExisteRuaCategoriaAtenconNivel($id_categoria,$tipo_atencion,$nivel, $id_servicio, $id_perfil_preoperatorio) {
        $cad = "select *
              from categoria_rua_nivels l
              inner join categoria_ruas p on l.id_categoria_rua = p.id
              where l.id_categoria_rua=".$id_categoria." and l.id_atencion=".$tipo_atencion." and l.estado=1 and l.id_nivel=".$nivel." and l.id_servicio =".$id_servicio . " and l.id_perfil_preoperatorio = " . $id_perfil_preoperatorio;

        $data = DB::select($cad);

        return $data;
    }

    public static function RegistrarCategoriaRuaNivelAtencion($id_categoria, $tipo_atencion, $nivel, $orden, $id_user, $id_consultorio, $id_perfil_preoperatorio) {
        $cad = "Select sp_insert_rua_categoria_nivel_atencion(?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_categoria, $tipo_atencion, $nivel, $orden, $id_user, $id_consultorio, $id_perfil_preoperatorio));
        return $data[0]->sp_insert_rua_categoria_nivel_atencion;
    }

    public function getNombreCategoriasRua($id){
        $cad = "select id, nombre
                from categoria_ruas
                where estado = 1 and id=".$id;
        $data = DB::select($cad);
        return $data[0];
    }

    public function getVerRuaCategoria($nivel, $tipo_atencion, $categoria){
        $cad = "select l.id, l.id_categoria, p.descripcion, l.descripcion_corta, p.codigo, crn.orden
              from labnivelatenciones l
              inner join procedimientos p on l.id_procedimiento = p.id
              inner join categoria_rua_nivels crn on l.id_categoria = crn.id_categoria_rua
              where l.id_tipo_atencion=".$tipo_atencion." and l.estado=1  and l.id_nivel=".$nivel." and l.id_categoria=".$categoria;
        $cad .= " group by l.id, l.id_categoria, p.descripcion, l.descripcion_corta, p.codigo, crn.orden
                  Order by crn.orden,l.id_categoria, l.descripcion_corta asc";

        $data = DB::select($cad);
        return $data;
    }

    public function getVerRuaOrig($nivel, $tipo_atencion){
        $cad = "select l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo, l.cpms_personalizado --, crn.orden
                from labnivelatenciones l
                inner join categoria_ruas cr on cr.id = l.id_categoria
                --inner join categoria_rua_nivels crn on crn.id_categoria_rua  = cr.id
                inner join procedimientos p on l.id_procedimiento = p.id
                where l.estado= 1 and l.id_tipo_atencion =".$tipo_atencion." and l.id_nivel = ".$nivel;
        $cad .= " order by l.id_categoria, l.descripcion_corta --crn.orden, ";

        $data = DB::select($cad);
        return $data;
    }

    public function getVerRua($nivel, $tipo_atencion,$id_consultorio){
        $cad = "select l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo --, crn.orden
                from labnivelatenciones l
                inner join categoria_ruas cr on cr.id = l.id_categoria
                --inner join categoria_rua_nivels crn on crn.id_categoria_rua  = cr.id
                inner join procedimientos p on l.id_procedimiento = p.id
                where l.estado= 1 and l.id_tipo_atencion =".$tipo_atencion." and l.id_nivel = ".$nivel. " and l.id_consultorio = ".$id_consultorio;
        $cad .= " group by l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo --crn.orden,
                  order by l.id_categoria, l.descripcion_corta --crn.orden, ";

        $data = DB::select($cad);
        return $data;
    }

    public function getCategoriaNivelAtencionRua($id){
        $cad = "select a.*
              from categoria_rua_nivels a
              where a.id=".$id;
        $cad .= " Order by a.orden asc";
        $data = DB::select($cad);
        return $data[0];
    }

    public function getNumCategoria($nivel, $tipo_atencion, $id_servicio){
        $cad = "select count(*) total
              from categoria_rua_nivels a
              where a.estado= 1 and a.id_atencion =".$tipo_atencion." and a.id_nivel = ".$nivel." and a.id_servicio=".$id_servicio;
        $data = DB::select($cad);
        return $data[0];
    }

    public function getNumCategoriaOrig($nivel, $tipo_atencion){
        $cad = "select count(*) total
              from categoria_rua_nivels a
              where a.estado= 1 and a.id_atencion =".$tipo_atencion." and a.id_nivel = ".$nivel;
        $data = DB::select($cad);
        return $data[0];
    }

    public function getLaboratorioRuaConvenio($buscar, $nivel, $genero, $tipo, $id_servicio, $id_perfil_preoperatorio)
  {
    $cad = "select
                l.id,
                l.descripcion_corta descripcion,
                l.codigo_lab codigo,
                l.id_categoria,
                l.cpms_personalizado,
                p.id id_procedimiento,
                cr.nombre categoria_nombre
            from labnivelatenciones l
            inner join procedimientos p on l.id_procedimiento = p.id
            inner join categoria_ruas cr on cr.id = l.id_categoria 
            left join categoria_rua_nivels crn on l.id_categoria_rua_nivel = crn.id
            where
                (l.cpms_personalizado ILIKE '%$buscar%' OR l.descripcion_corta ILIKE '%$buscar%' OR l.codigo_lab ILIKE '%$buscar%') AND
                l.id_nivel = $nivel
                and l.estado = 1
                and l.id_tipo_atencion = $tipo";

    // $cad = "select l.id, l.descripcion_corta descripcion, l.codigo_lab codigo, l.id_categoria, l.cpms_personalizado
    //         from labnivelatenciones l
    //         inner join procedimientos p on l.id_procedimiento = p.id
    //         where l.cpms_personalizado||l.descripcion_corta ilike '%" . $buscar . "%' and l.id_nivel =" . $nivel . "  and l.estado=1 and l.id_tipo_atencion = " . $tipo;
    if ($id_servicio && intval($id_servicio) != 0 && $nivel == 3) {
        $cad .= " and l.id_consultorio = " . $id_servicio;
    }
    if (intval($id_perfil_preoperatorio) > 0) {
        $cad .= " and crn.id_perfil_preoperatorio = " . $id_perfil_preoperatorio;
    }

    //$cad .= " Order by l.codigo_lab asc";
    $cad .= " ORDER BY l.id_categoria ASC, l.codigo_lab ASC";
    //            limit 50 ";
    $data = DB::select($cad);
    return $data;
  }

  public function getLaboratorioRuaConvenio_emergencia($buscar, $nivel, $genero, $tipo, $id_servicio)
  {

    $cad = "
      select  
      DISTINCT ON (lab.cpms_personalizado)
        lab.id as id,
        lab.descripcion_corta as descripcion,
        12 AS id_categoria,
        lab.cpms_personalizado codigo
      from labnivelatenciones lab 
      inner join procedimientos p on p.codigo = lab.codigo_lab 
      where (lab.cpms_personalizado || lab.descripcion_corta) ilike '%{$buscar}%'
        and p.tipo_procedimiento = 2 and id_tipo_atencion in (2,3)
      limit 50;
    ";
    $data = DB::select($cad);
    return $data;
  }

public function basico($numero) {
        $valor = array ('uno','dos','tres','cuatro','cinco','seis','siete','ocho',
        'nueve','diez','once','doce','trece','catorce','quince','dieciseis','diecisiete','dieciocho','diecinueve','veinte','veintiuno ','vientidos ','veintitrés ', 'veinticuatro','veinticinco',
        'veintiséis','veintisiete','veintiocho','veintinueve');
        return $valor[$numero - 1];
    }

    public function decenas($n) {
        $decenas = array (30=>'treinta',40=>'cuarenta',50=>'cincuenta',60=>'sesenta',
        70=>'setenta',80=>'ochenta',90=>'noventa');
        if( $n <= 29) return $this->basico($n);
        $x = $n % 10;
        if ( $x == 0 ) {
        return $decenas[$n];
        } else return $decenas[$n - $x].' y '. $this->basico($x);
    }
    public function convertir($n) {
        switch (true) {
        case ( $n >= 1 && $n <= 29) : return $this->basico($n); break;
        case ( $n >= 30 && $n < 100) : return $this->decenas($n); break;
        //case ( $n >= 100 && $n < 1000) : return centenas($n); break;
        //case ($n >= 1000 && $n <= 999999): return miles($n); break;
        //case ($n >= 1000000): return millones($n);
        }
    }

    public function getVerRuaConItemPorIdPrestacion($nivel, $tipo_atencion,$id_consultorio,$id_prestacion,$id_laboratorio){
        $cad = "select l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo, pp.id_rua, pp.confirmar_toma,
                l.descripcion_corta name_examen, p.codigo code
                from labnivelatenciones l
                inner join categoria_ruas cr on cr.id = l.id_categoria
                --inner join categoria_rua_nivels crn on crn.id_categoria_rua  = cr.id
                inner join procedimientos p on l.id_procedimiento = p.id
                left join prestacion_procedimientos pp on l.id = pp.id_rua and pp.id_prestacion =".$id_prestacion." and pp.id_lab_rad  =".$id_laboratorio;
        $cad .= " where l.estado= 1 and l.id_tipo_atencion =".$tipo_atencion." and l.id_nivel = ".$nivel. " ";
              if($tipo_atencion != 2){
                $cad .=     " and l.id_consultorio = ".$id_consultorio;
              }
        
        $cad .= " group by l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo, pp.id_rua, pp.confirmar_toma
                  order by l.id_categoria, l.descripcion_corta --crn.orden, ";
        $data = DB::select($cad);
        return $data;
    }

    public function getVerRuaConItemPorIdPrestacionNivel12($nivel, $tipo_atencion, $id_prestacion, $id_laboratorio)
    {
        $data = "select 
                  l.id,
                  l.descripcion_corta descripcion_corta,
                  cr.nombre as categoria_rua_nombre,
                  p.id as id_procedimiento,
                  p.codigo as procedimiento_codigo,
                  cr.id as id_categoria,
                  pp.id_rua,
                  pp.confirmar_toma,
                  false as is_header,
                  MAX(pp.respuestas_urocultivo::text) AS respuestas_urocultivo
                from labnivelatenciones l 
                inner join categoria_ruas cr on l.id_categoria = cr.id 
                inner join procedimientos p on l.id_procedimiento = p.id
                left join prestacion_procedimientos pp on p.id = pp.id_procedimiento and pp.id_prestacion = $id_prestacion and pp.id_lab_rad = $id_laboratorio
                where l.id_nivel = $nivel and l.id_tipo_atencion = $tipo_atencion and l.estado = 1
                group by l.id, l.descripcion_corta, cr.nombre, p.id, p.codigo, cr.id, pp.id_rua, pp.confirmar_toma
                order by l.id_categoria";
        $data = DB::select($data);
        return $data;
    }

    public function obtenerPrestacionProcedimientoPorIdPrestacion($id_prestacion)
    {
      $data = DB::table('prestacion_procedimientos')
      ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
      ->select(
          'prestacion_procedimientos.*',
          'procedimientos.codigo as code',
          'procedimientos.descripcion as name_examen'
      )
      ->where('prestacion_procedimientos.id_prestacion', '=', $id_prestacion)
      ->where('prestacion_procedimientos.id_rua', '<>', 0)
      ->orderBy('prestacion_procedimientos.id', 'desc')
      ->get();
        return $data;
    }

    public function prioridadesEmergencia(){
        $cad = "select * from prioridades order by id asc";
        $data = DB::select($cad);
        return $data;
    }

    public function readFunctionPostgres($function, $parameters = null)
    {
        $_parameters = '';
        if (count($parameters) > 0) {
            $_parameters = implode("','", $parameters);
            $_parameters = "'" . $_parameters . "',";
        }
        $data = DB::select("BEGIN;");
        $cad = "select " . $function . "(" . $_parameters . "'ref_cursor');";

        $data = DB::select($cad);
        $cad = "FETCH ALL IN ref_cursor;";
        $data = DB::select($cad);
        return $data;
    }

    public function listarDetallePrestacionLaboratorio($p)
    {
        return $this->readFunctionPostgres('sp_obtener_detalle_prestaciones_laboratorio', $p);
    }

    public function listarItemsDetallePrestacionLaboratorio($p)
    {
        return $this->readFunctionPostgres('sp_items_detalle_prestaciones_laboratorio', $p);
    }

    public function resgitrarCitaNuevaLaboratorio($id_cita_origen,$id_cita_nueva)
    {
        $cad = "Select sp_insertar_cita_laboratorio(?,?)";
        $data = DB::select($cad, array($id_cita_origen,$id_cita_nueva));
        return $data[0]->sp_insertar_cita_laboratorio;
    }

    public function GetExamenLaboratorioById($id_laboratorio)
    {
        $cad = "Select * from sp_examen_laboratorio(?)";
        $data = DB::select($cad, array($id_laboratorio));
        return $data;
    }

    public function validacionBeneficiarioDiasPorExamenRua($dni_beneficiario, $id_examen_lab)
    {
        $cad = "select * from sp_obtener_ordenes_laboratorio_por_dni_por_examen(?,?);";
        
        try {
            $data = DB::select($cad,array($dni_beneficiario,$id_examen_lab));
            return $data;
        } catch (\Exception $e) {
            // Manejar el error, por ejemplo, registrándolo o devolviendo un mensaje de error.
            return $e->getMessage();
        }
    }

    public function insertarFlagCitaLaboratorio($id_prestacion) {
        $cad = "Select * from sp_insertar_flag_cita_laboratorio(?)";
        $data = DB::select($cad, array($id_prestacion));
        return $data;
    }

    // public function listarDetallePrestacionLaboratorioEmergencia($p)
    // {
    //     return $this->readFunctionPostgres('sp_obtener_detalle_prestaciones_laboratorio_emergencia', $p);
    // }

    // public function listarItemsDetallePrestacionLaboratorioEmergencia($p)
    // {
    //     return $this->readFunctionPostgres('sp_items_detalle_prestaciones_laboratorio_emergencia', $p);
    // }

    public function obtenerPrestacionProcedimientoPorIdPrestacionEmergencia($id_prestacion,$id_laboratorio)
    {
    
        $cad = "select lab.cpms_personalizado code,lab.descripcion_corta name_examen
                ,lab.descripcion_corta nombre
                ,lab.descripcion_corta 
                ,lab.cpms_personalizado codigo 
                ,pp.* from prestacion_procedimientos pp
                inner join procedimientos p  on p.id = pp.id_procedimiento
                inner join labnivelatenciones lab on lab.id =  pp.id_rua
                where pp.id_prestacion = $id_prestacion and p.tipo_procedimiento =2 and pp.id_lab_rad = $id_laboratorio;";
                $data =  DB::select($cad);
        return $data;
    }

    public function GetLaboratorioByIdCO($id_laboratorio) {
        $cad = "Select * from sp_obtener_laboratorio(?)";
        $data = DB::select($cad, array($id_laboratorio));
        return $data;
    }

    public function guardarNumeroOrdenLaboratorio($id_laboratorio, $cod_orden_lab)
    {
        // Encuentra el registro que coincida con el id del laboratorio
        $laboratorio = Laboratorio::findOrFail($id_laboratorio);
        $laboratorio->cod_orden_lab = $cod_orden_lab;
        $laboratorio->save();
    }

    public function AllOrdenExamenLaboratorioByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */$nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia) {
        // dd($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen_lab, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia);
            $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

            $cad = "Select laboratorios.cod_orden_lab from prestaciones
                    inner join laboratorios on laboratorios.id_prestacion=prestaciones.id
                    inner join farmacias on farmacias.id = laboratorios.id_farmacia
                    inner join asegurados on prestaciones.id_asegurado=asegurados.id
                    inner join sub_consultorios on prestaciones.id_sub_consultorio=sub_consultorios.id
                    inner join consultorios on sub_consultorios.id_consultorio=consultorios.id
                    inner join medicos on prestaciones.id_medico=medicos.id
                    inner join tipo_atenciones ta on prestaciones.id_tipo_atencion =ta.id
                    inner join users u on prestaciones.created_us =u.id";
            if ($tipo_atencion == "0") {
                $cad .= " inner join citas c on c.id = prestaciones.id_cita  and c.id_estado_reg = 1";
                $cad .= " left join emergencias e on e.id = prestaciones.id_ext";
                $cad .= " left join prioridades p on p.id = e.id_prioridad";
                $cad .= " left join triajes t on t.id = e.id_triaje";
                $cad .= " left join emergencia_evacuados eeva on eeva.id_emergencia = e.id";
                $cad .= " left join camas on camas.id = e.cama_estado";
                $cad .= " left join cuartos on camas.id_cuarto=cuartos.id";
                $cad .= " left join asignacion_servicios on asignacion_servicios.id = cuartos.id_asignacion_servicio";
                $cad .= " left join pisos on asignacion_servicios.id_piso=pisos.id";
                $cad .= " left join pabellones on pisos.id_pabellon=pabellones.id";
                $cad .= " left join consultorios con on asignacion_servicios.id_consultorio = con.id";
            }
            if ($tipo_atencion == "1") {
                $cad .= " inner join citas c on c.id = prestaciones.id_cita and c.id_estado_reg = 1";
            }
            if ($tipo_atencion == "2") {
                $cad .= " inner join emergencias e on e.id = prestaciones.id_ext";
                $cad .= " inner join prioridades p on p.id = e.id_prioridad";
                $cad .= " inner join triajes t on t.id = e.id_triaje";
                $cad .= " left join emergencia_evacuados eeva on eeva.id_emergencia = e.id";
                $cad .= " left join camas on camas.id = e.cama_estado";
                $cad .= " inner join cuartos on camas.id_cuarto=cuartos.id";
                $cad .= " inner join asignacion_servicios on asignacion_servicios.id = cuartos.id_asignacion_servicio";
                $cad .= " inner join pisos on asignacion_servicios.id_piso=pisos.id";
                $cad .= " inner join pabellones on pisos.id_pabellon=pabellones.id";
                $cad .= " inner join consultorios con on asignacion_servicios.id_consultorio = con.id";
            }
            $cad .= " where laboratorios.id_establecimiento=" . $id_establecimiento;

            if ($dni_beneficiario):
                $cad .= " and asegurados.nro_doc_ident = '" . $dni_beneficiario . "'";
            endif;

            // if ($nro_prestacion):
            //     $cad .= " and prestaciones.id = " . $nro_prestacion;
            // endif;

            if ($nro_examen_lab):
                $cad .= " and laboratorios.id = " . $nro_examen_lab;
            endif;

            if ($tipo_atencion):
                $cad .= " and prestaciones.id_tipo_atencion = " . $tipo_atencion;
            endif;

            if ($id_servicio):
                if ($tipo_atencion !== "2") {
                    $cad .= " and consultorios.id = " . $id_servicio;
                } else {
                    $cad .= " and e.id_triaje = " . $id_servicio;
                }
            endif;

            if ($referido):
                if ($tipo_atencion == "1") {
                    $cad .= " and c.referido = '" . $referido . "' ";
                }
                if ($tipo_atencion == "2") {
                    if ($referido == "SI") {
                        $condicion = " is not null ";
                    }
                    if ($referido == "NO") {
                        $condicion = " is null ";
                    }
                    $cad .= " and eeva.unidad_policial " . $condicion;
                }
            endif;

            if ($prioridad_emergencia):
                $cad .= " and e.id_prioridad = " . $prioridad_emergencia;
            endif;
            // if ($estado == "1"):
            //     $cad .= " and laboratorios.fecha_registro_toma_muestra is null";
            // endif;
            if ($estado == "4"):
                $cad .= " and laboratorios.fecha_registro_toma_muestra is not null and (laboratorios.estado_labcore IS NULL OR laboratorios.estado_labcore = 1)";
            endif;

                if ($fechaDesde != "")
                    $cad .= " and laboratorios.created_at >= '" . $fechaDesde . "'";
                if ($fechaHasta != "")
                    $cad .= " and laboratorios.created_at < '" . $last_day . "'";

            if ($id_farmacia > 0) {
                $cad .= " and laboratorios.id_farmacia = ". $id_farmacia ." and laboratorios.estado = 1";
            }

            $cad .= " and laboratorios.estado = 1 order By laboratorios.id desc";
            //dd($cad);
            $data = DB::select($cad);
            return $data;
        }

    public function UpdateEstadoValidadoLabByIdCO($cod_orden_lab) {
        $laboratorio = Laboratorio::where('cod_orden_lab', $cod_orden_lab)->firstOrFail();
        $laboratorio->estado_labcore = 1;   /*--1 Validado, 2 firmado--*/
        $laboratorio->save();
    }

    public function AllValidadosExLabByEstaAndFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */$nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia) {
        // dd($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen_lab, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia);
            $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

            $cad = "select prestaciones.*,asegurados.nombre nombre_beneficiario, asegurados.paterno as paterno_beneficiario,asegurados.materno as materno_beneficiario,asegurados.tipo_doc_ident, ta.id id_tipo_atencion,ta.nombre nom_tipo_atencion, u.name as digitador, asegurados.nro_doc_ident, prestaciones.created_at as fec_creacion, consultorios.nombre consultorio, ruta_laboratorio,medicos.dni as dni_medico, medicos.nombre as nombre_medico, medicos.paterno as paterno_medico, medicos.materno as materno_medico, laboratorios.id as id_lab, medicos.nombre servicio, laboratorios.fecha_firma  firma_laboratorio, laboratorios.*, prestaciones.id as id_prestacion
            , DATE_PART('day', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) * 24 + DATE_PART('hour', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) as diff_horas_desde_toma_muestra, laboratorios.id_evaluacion, laboratorios.estado_labcore, laboratorios.cod_orden_lab, farmacias.nombre as nombre_farmacia,
            CASE
            WHEN EXTRACT(EPOCH FROM (NOW() - prestaciones.created_at)) / 3600 >= 2 THEN 0
            WHEN EXTRACT(EPOCH FROM (NOW() - prestaciones.created_at)) / 3600 >= 1 THEN 1
            WHEN EXTRACT(EPOCH FROM (NOW() - prestaciones.created_at)) / 3600 < 1 THEN 2
            END AS estado_emergencia";
            if ($tipo_atencion == "0") {
                $cad .= ", c.referido";
                $cad .= ", p.descripcion nom_prioridad";
                $cad .= ", t.descripcion nom_especialidad_emergencia";
                $cad .= ", eeva.unidad_policial";
                $cad .= ", cuartos.nombre cuarto, pabellones.nombre pabellon, pisos.nombre piso, camas.nombre cama ";
            }
            if ($tipo_atencion == "1") {
                $cad .= ", c.referido";
            }
            if ($tipo_atencion == "2") {
                $cad .= ", p.descripcion nom_prioridad";
                $cad .= ", t.descripcion nom_especialidad_emergencia";
                $cad .= ", eeva.unidad_policial";
                $cad .= ", cuartos.nombre cuarto, pabellones.nombre pabellon, pisos.nombre piso, camas.nombre cama";
            }
            $cad .= " from prestaciones
                    inner join laboratorios on laboratorios.id_prestacion=prestaciones.id
                    inner join farmacias on farmacias.id = laboratorios.id_farmacia
                    inner join asegurados on prestaciones.id_asegurado=asegurados.id
                    inner join sub_consultorios on prestaciones.id_sub_consultorio=sub_consultorios.id
                    inner join consultorios on sub_consultorios.id_consultorio=consultorios.id
                    inner join medicos on prestaciones.id_medico=medicos.id
                    inner join tipo_atenciones ta on prestaciones.id_tipo_atencion =ta.id
                    inner join users u on prestaciones.created_us =u.id";
            if ($tipo_atencion == "0") {
                $cad .= " inner join citas c on c.id = prestaciones.id_cita  and c.id_estado_reg = 1";
                $cad .= " left join emergencias e on e.id = prestaciones.id_ext";
                $cad .= " left join prioridades p on p.id = e.id_prioridad";
                $cad .= " left join triajes t on t.id = e.id_triaje";
                $cad .= " left join emergencia_evacuados eeva on eeva.id_emergencia = e.id";
                $cad .= " left join camas on camas.id = e.cama_estado";
                $cad .= " left join cuartos on camas.id_cuarto=cuartos.id";
                $cad .= " left join asignacion_servicios on asignacion_servicios.id = cuartos.id_asignacion_servicio";
                $cad .= " left join pisos on asignacion_servicios.id_piso=pisos.id";
                $cad .= " left join pabellones on pisos.id_pabellon=pabellones.id";
                $cad .= " left join consultorios con on asignacion_servicios.id_consultorio = con.id";
            }
            if ($tipo_atencion == "1") {
                $cad .= " inner join citas c on c.id = prestaciones.id_cita and c.id_estado_reg = 1";
            }
            if ($tipo_atencion == "2") {
                $cad .= " inner join emergencias e on e.id = prestaciones.id_ext";
                $cad .= " inner join prioridades p on p.id = e.id_prioridad";
                $cad .= " inner join triajes t on t.id = e.id_triaje";
                $cad .= " left join emergencia_evacuados eeva on eeva.id_emergencia = e.id";
                $cad .= " left join camas on camas.id = e.cama_estado";
                $cad .= " inner join cuartos on camas.id_cuarto=cuartos.id";
                $cad .= " inner join asignacion_servicios on asignacion_servicios.id = cuartos.id_asignacion_servicio";
                $cad .= " inner join pisos on asignacion_servicios.id_piso=pisos.id";
                $cad .= " inner join pabellones on pisos.id_pabellon=pabellones.id";
                $cad .= " inner join consultorios con on asignacion_servicios.id_consultorio = con.id";
            }
            $cad .= " where laboratorios.id_establecimiento=" . $id_establecimiento;

            if ($dni_beneficiario):
                $cad .= " and asegurados.nro_doc_ident = '" . $dni_beneficiario . "'";
            endif;

            // if ($nro_prestacion):
            //     $cad .= " and prestaciones.id = " . $nro_prestacion;
            // endif;

            if ($nro_examen_lab):
                $cad .= " and laboratorios.id = " . $nro_examen_lab;
            endif;

            if ($tipo_atencion):
                $cad .= " and prestaciones.id_tipo_atencion = " . $tipo_atencion;
            endif;

            if ($id_servicio):
                if ($tipo_atencion !== "2") {
                    $cad .= " and consultorios.id = " . $id_servicio;
                } else {
                    $cad .= " and e.id_triaje = " . $id_servicio;
                }
            endif;

            if ($referido):
                if ($tipo_atencion == "1") {
                    $cad .= " and c.referido = '" . $referido . "' ";
                }
                if ($tipo_atencion == "2") {
                    if ($referido == "SI") {
                        $condicion = " is not null ";
                    }
                    if ($referido == "NO") {
                        $condicion = " is null ";
                    }
                    $cad .= " and eeva.unidad_policial " . $condicion;
                }
            endif;

            if ($prioridad_emergencia):
                $cad .= " and e.id_prioridad = " . $prioridad_emergencia;
            endif;
            if ($estado == "1"):
                $cad .= " and laboratorios.fecha_registro_toma_muestra is null";
            endif;
            if ($estado == "2"):
                $cad .= " and laboratorios.fecha_registro_toma_muestra is not null";
            endif;
            if ($estado == "4"):
                $cad .= " and laboratorios.estado_labcore = 1";
            endif;
            if ($estado == "5"):
                $cad .= " and laboratorios.estado_labcore = 2";
            endif;

            if ($id_farmacia > 0) {
                $cad .= " and laboratorios.id_farmacia = ". $id_farmacia ." and laboratorios.estado = 1";
            }
    

            if ($fechaDesde != "")
                $cad .= " and laboratorios.created_at >= '" . $fechaDesde . "'";
            if ($fechaHasta != "")
                $cad .= " and laboratorios.created_at < '" . $last_day . "'";
            
            $cad .= " and laboratorios.estado = 1 order By laboratorios.id desc";
            //dd($cad);
            $data = DB::select($cad);
            return $data;
        }

        public function AllValidadosParaFirmaExLabByEstaAndFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */$nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado) {
            // dd($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen_lab, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia);
                $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

                $cad = "Select laboratorios.cod_orden_lab, prestaciones.id as id_prestacion, laboratorios.id as id_laboratorio from prestaciones
                        inner join laboratorios on laboratorios.id_prestacion=prestaciones.id
                        inner join asegurados on prestaciones.id_asegurado=asegurados.id
                        inner join sub_consultorios on prestaciones.id_sub_consultorio=sub_consultorios.id
                        inner join consultorios on sub_consultorios.id_consultorio=consultorios.id
                        inner join medicos on prestaciones.id_medico=medicos.id
                        inner join tipo_atenciones ta on prestaciones.id_tipo_atencion =ta.id
                        inner join users u on prestaciones.created_us =u.id";
                if ($tipo_atencion == "0") {
                    $cad .= " inner join citas c on c.id = prestaciones.id_cita  and c.id_estado_reg = 1";
                    $cad .= " left join emergencias e on e.id = prestaciones.id_ext";
                    $cad .= " left join prioridades p on p.id = e.id_prioridad";
                    $cad .= " left join triajes t on t.id = e.id_triaje";
                    $cad .= " left join emergencia_evacuados eeva on eeva.id_emergencia = e.id";
                    $cad .= " left join camas on camas.id = e.cama_estado";
                    $cad .= " left join cuartos on camas.id_cuarto=cuartos.id";
                    $cad .= " left join asignacion_servicios on asignacion_servicios.id = cuartos.id_asignacion_servicio";
                    $cad .= " left join pisos on asignacion_servicios.id_piso=pisos.id";
                    $cad .= " left join pabellones on pisos.id_pabellon=pabellones.id";
                    $cad .= " left join consultorios con on asignacion_servicios.id_consultorio = con.id";
                }
                if ($tipo_atencion == "1") {
                    $cad .= " inner join citas c on c.id = prestaciones.id_cita and c.id_estado_reg = 1";
                }
                if ($tipo_atencion == "2") {
                    $cad .= " inner join emergencias e on e.id = prestaciones.id_ext";
                    $cad .= " inner join prioridades p on p.id = e.id_prioridad";
                    $cad .= " inner join triajes t on t.id = e.id_triaje";
                    $cad .= " left join emergencia_evacuados eeva on eeva.id_emergencia = e.id";
                    $cad .= " left join camas on camas.id = e.cama_estado";
                    $cad .= " inner join cuartos on camas.id_cuarto=cuartos.id";
                    $cad .= " inner join asignacion_servicios on asignacion_servicios.id = cuartos.id_asignacion_servicio";
                    $cad .= " inner join pisos on asignacion_servicios.id_piso=pisos.id";
                    $cad .= " inner join pabellones on pisos.id_pabellon=pabellones.id";
                    $cad .= " inner join consultorios con on asignacion_servicios.id_consultorio = con.id";
                }
                $cad .= " where laboratorios.id_establecimiento=" . $id_establecimiento;

                if ($dni_beneficiario):
                    $cad .= " and asegurados.nro_doc_ident = '" . $dni_beneficiario . "'";
                endif;

                // if ($nro_prestacion):
                //     $cad .= " and prestaciones.id = " . $nro_prestacion;
                // endif;

                if ($nro_examen_lab):
                    $cad .= " and laboratorios.id = " . $nro_examen_lab;
                endif;

                if ($tipo_atencion):
                    $cad .= " and prestaciones.id_tipo_atencion = " . $tipo_atencion;
                endif;

                if ($id_servicio):
                    if ($tipo_atencion !== "2") {
                        $cad .= " and consultorios.id = " . $id_servicio;
                    } else {
                        $cad .= " and e.id_triaje = " . $id_servicio;
                    }
                endif;

                if ($referido):
                    if ($tipo_atencion == "1") {
                        $cad .= " and c.referido = '" . $referido . "' ";
                    }
                    if ($tipo_atencion == "2") {
                        if ($referido == "SI") {
                            $condicion = " is not null ";
                        }
                        if ($referido == "NO") {
                            $condicion = " is null ";
                        }
                        $cad .= " and eeva.unidad_policial " . $condicion;
                    }
                endif;

                if ($prioridad_emergencia):
                    $cad .= " and e.id_prioridad = " . $prioridad_emergencia;
                endif;
                if ($estado == "1"):
                    $cad .= " and laboratorios.fecha_registro_toma_muestra is null";
                endif;
                if ($estado == "2"):
                    $cad .= " and laboratorios.fecha_registro_toma_muestra is not null";
                endif;
                if ($estado == "4"):
                    $cad .= " and laboratorios.estado_labcore = 1";
                endif;
                if ($estado == "5"):
                    $cad .= " and laboratorios.estado_labcore = 2";
                endif;

                    if ($fechaDesde != "")
                        $cad .= " and laboratorios.created_at >= '" . $fechaDesde . "'";
                    if ($fechaHasta != "")
                        $cad .= " and laboratorios.created_at < '" . $last_day . "'";
                $cad .= " and laboratorios.estado = 1 order By laboratorios.id desc";
                //dd($cad);
                $data = DB::select($cad);
                return $data;
            }

    public function GetLaboratorioByIdPrestacionInterconsulta($id_prestacion,$id_interconsulta) {
        $datos = Laboratorio::where('id_prestacion', '=', $id_prestacion)->where('id_interconsulta', '=', $id_interconsulta)->where('estado', '<>', 0)->get();
        return $datos;
    }

    public function ActualizaIdInterconsulta($id, $id_interconsulta) {

        $datos = Laboratorio::where('id', $id)->first();
        $datos->id_interconsulta = $id_interconsulta;
        $datos->save();

        return true;

    }

    public function obtenerCodigoSitedsSiExisteOrdenLabDelDia($id_asegurado)
    {
        $cad = "Select * from sp_obtener_codigo_siteds_si_existe_orden_lab_del_dia(?)";
        $data = DB::select($cad, array($id_asegurado));
        $data = isset($data[0]) ? $data[0] : null;
        return $data;
    }

    public function resgitrarCitaNuevaLaboratorioCE($id_cita_origen_cita_resultado,$id_cita_nueva)
    {
        $cad = "Select sp_insertar_cita_laboratorio_ce(?,?)";
        $data = DB::select($cad, array($id_cita_origen_cita_resultado,$id_cita_nueva));
        return $data[0]->sp_insertar_cita_laboratorio_ce;
    }

    public function limpiarCitaDeLaboratorioParaReprogramar($id_cita)
    {
        $cad = "Select sp_limpiar_cita_laboratorio(?)";
        $data = DB::select($cad, array($id_cita));
        return $data[0]->sp_limpiar_cita_laboratorio;
    }
  
    public function listarExamenesLaboratorioConDatosPersonalizados() 
    {
        $examenes = DB::table('procedimientos as p')
            ->distinct()
            ->select('p.id', 'l.descripcion_corta', 'p.codigo')
            ->join('labnivelatenciones as l', 'p.id', '=', 'l.id_procedimiento')
            ->where('p.tipo_procedimiento', 2)
            ->where('p.estado', 1)
            ->orderBy('l.descripcion_corta')
            ->orderBy('p.codigo')
            ->get();
        return $examenes;
    }
  
    public function obtenerProveedoresApoyoAlDiagnostico($id_tipo_procedimiento)
    {
        $data = DB::table('proveedores_apoyo_diagnostico')
                ->select('proveedores_apoyo_diagnostico.*')
                ->where('tipo_procedimiento','=',$id_tipo_procedimiento)
                ->where('estado','=',1)
                ->orderBy('id', 'asc')
                ->get();
        return $data;
    }

    public function obtenerPrestacionProcedimientoPorIdPrestacionConIdLaboratorio($id_prestacion,$id_laboratorio)
    {
        $data = DB::table('prestacion_procedimientos')
                ->select('prestacion_procedimientos.*')
                ->where('tipo_procedimiento','=',2)
                ->where('id_prestacion','=',$id_prestacion)
                ->where('id_lab_rad','=',$id_laboratorio)
                ->where('id_rua','<>',0)
                ->orderBy('id', 'desc')
                ->get();
        return $data;
    }

    public function GetLaboratorioByIdLaboratorio($id_laboratorio) {
        $cad = "Select * from sp_obtener_laboratorio_por_id(?)";
        $data = DB::select($cad, array($id_laboratorio));
        return $data;
    }

    public function obtenerSitedsGeneradoHoy($asegurado_dni, $tipo_atencion_id, $id_press)
    {
        $result = DB::table('laboratorios')
                    ->join('prestaciones', 'laboratorios.id_prestacion', '=', 'prestaciones.id')
                    ->join('asegurados', 'prestaciones.id_asegurado', '=', 'asegurados.id')
                    ->where('asegurados.nro_doc_ident', $asegurado_dni)
                    ->where('prestaciones.id_tipo_atencion', $tipo_atencion_id)
                    ->where(DB::raw('DATE(laboratorios.updated_at_cod_siteds)'), '=', DB::raw('CURRENT_DATE'))
                    ->whereNotNull('laboratorios.cod_siteds_susalud')
                    ->where('laboratorios.id_establecimiento', $id_press)
                    ->select('laboratorios.id', 'laboratorios.cod_siteds_susalud', 'laboratorios.updated_at_cod_siteds', 'laboratorios.updated_us_cod_siteds')
                    ->orderBy('laboratorios.id', 'desc')
                    ->limit(1)
                    ->first();
        return $result;
    }

    public function AllExamenLaboratorioConsultaExternaList($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */$nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia, $apellido_nombres = "", $paraExportacion = false) {
        // dd($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen_lab, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia);
        $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));
        $col_id_cita = "c1.id";
        $col_id_cita_laboratorio_agrupa = "c.id_cita_laboratorio_ce";

        $cad = "select
        pres_p.id_cita_dependiente_laboratorio";

        if (!$paraExportacion) {
            $cad .= ",
            (SELECT
                COUNT(pre.id) AS cantidad
            FROM
                prestaciones pre
            WHERE
                pre.id_cita_dependiente_laboratorio = pres_p.id_cita_dependiente_laboratorio
            ) cantidad
            , (select COUNT(pp_c.id)
                from prestacion_procedimientos pp_c
                where
                pp_c.id_lab_rad = laboratorios.id
                and pp_c.tipo_procedimiento = 2
                and pp_c.id_estado_reg = 1) as cantidad_total
            , (SELECT COUNT(pp_c.id)
                FROM prestacion_procedimientos pp_c
                LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
                WHERE pp_c.id_lab_rad = laboratorios.id
                AND pp_c.tipo_procedimiento = 2
                AND pp_c.confirmar_toma is null
                AND ppp.pendiente_laboratorio is null
                and pp_c.id_estado_reg = 1
            ) AS cantidad_sr
            , (SELECT COUNT(pp_c.id)
                FROM prestacion_procedimientos pp_c
                LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
                WHERE pp_c.id_lab_rad = laboratorios.id
                AND pp_c.tipo_procedimiento = 2
                AND (pp_c.confirmar_toma = '0' or pp_c.confirmar_toma = '3')
                AND ppp.pendiente_laboratorio is false
                and pp_c.id_estado_reg = 1) as cantidad_enviado
            , (SELECT COUNT(pp_c.id)
                FROM prestacion_procedimientos pp_c
                LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
                WHERE pp_c.id_lab_rad = laboratorios.id
                AND pp_c.tipo_procedimiento = 2
                AND pp_c.confirmar_toma is null
                AND ppp.pendiente_laboratorio is true
                and pp_c.id_estado_reg = 1) as cantidad_pendiente";
        }

        $cad .= "
        ,asegurados.id id_asegurado --
        ,asegurados.sexo --
        ,asegurados.fecha_nac --
        ,laboratorios.id as id_orden_lab -- 
        ,sub_consultorios.descripcion nombre_sub_consultorio -- fecha_registro_toma_muestra
        ,sub_cit.descripcion consultorio_lab ---
        ,laboratorios.cod_siteds_susalud --
        ,laboratorios.fecha_firma --
        --,pres_p.id as id_prestacion --
        ,{$col_id_cita} id_cita
        ,{$col_id_cita_laboratorio_agrupa} as id_cita_laboratorio_agrupa
        ,pres_p.id_cita_dependiente_laboratorio id_cita_d
        ,pres_p.id_establecimiento ---
        ,laboratorios.estado --- 
        ,asegurados.paterno ||' '|| asegurados.materno ||', '|| asegurados.nombre as paciente
        /*,asegurados.nombre nombre_beneficiario, asegurados.paterno as paterno_beneficiario--
        ,asegurados.materno as materno_beneficiario*/,asegurados.tipo_doc_ident
        , ta.id id_tipo_atencion--
        ,ta.nombre nom_tipo_atencion, asegurados.nro_doc_ident--
        --,pres_p.created_at as fec_creacion
        ,consultorios.nombre servicio --referido --id_cita_dependiente_laboratorio
        ,ruta_laboratorio
        ,medicos.nombre as nombre_medico
        , medicos.paterno as paterno_medico
        , medicos.materno as materno_medico
        , medicos.nombre medico_nombre
        , laboratorios.fecha_firma  firma_laboratorio
        , laboratorios.cod_orden_lab
        , to_char(laboratorios.fecha_lastupdate_tomamuestra, 'DD/MM/YYYY HH24:MI') as fecha_last_toma_muestra
        , laboratorios.cantidad_toma_muestra
        --,laboratorios.*, 
        ,pres_p.id as id_prestacion
        , laboratorios.estado_asistencia_paciente as flag_observado
        , laboratorios.obs_asistencia_paciente as motivo_observado
        , to_char(laboratorios.deleted_at, 'DD/MM/YYYY HH24:MI') as fecha_observado
        ,DATE_PART('day', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) * 24 + DATE_PART('hour', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) as diff_horas_desde_toma_muestra
        , laboratorios.observacion observacion_orden_lab
        , laboratorios.has_observacion
        , laboratorios.observacion_toma_muestra
        , laboratorios.obs_lastupdate_tomamuestra as obs_last_toma_muestra
        --,laboratorios.id_evaluacion
        , u1.dni as usuario_toma_muestra
        , u2.dni as usuario_update_toma_muestra
        ,CASE
            WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 2 THEN 0
            WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 1 THEN 1
            WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 < 1 THEN 2
        END AS estado_emergencia
        , to_char(laboratorios.fecha_registro_toma_muestra, 'DD/MM/YYYY HH24:MI') as fecha_toma_muestra
        , extract(year from age(to_date(asegurados.fecha_nac, 'DD/MM/YYYY'))) || ' A, ' ||
        extract(month from age(to_date(asegurados.fecha_nac, 'DD/MM/YYYY'))) || ' M y ' ||
        extract(day from age(to_date(asegurados.fecha_nac, 'DD/MM/YYYY'))) || ' D' AS edad";
        $cad .= ",es.descripcion estado_cita, c.referido, c2.fecha_hora, c.id_cita_lab, TO_CHAR(c3.fecha_hora, 'DD/MM/YYYY HH:MI AM') as fecha_hora_cita_lab, c3.id as id_cita_laboratorio_ce,  TO_CHAR(c3.created_at, 'DD/MM/YYYY HH:MI AM') fecha_creacion_cita_lab ";

        $cad .= " from prestaciones pres_p
            inner join laboratorios on laboratorios.id_prestacion=pres_p.id
            inner join asegurados on pres_p.id_asegurado=asegurados.id
            inner join sub_consultorios on pres_p.id_sub_consultorio=sub_consultorios.id
            inner join consultorios on sub_consultorios.id_consultorio=consultorios.id
            inner join medicos on pres_p.id_medico=medicos.id
            inner join tipo_atenciones ta on pres_p.id_tipo_atencion =ta.id
            inner join citas c1 on c1.id = pres_p.id_cita
            inner join estados es on es.id = c1.id_estado_cita
            left join users u1 on laboratorios.id_usuario_tomamuestra = u1.id
            left join users u2 on laboratorios.id_usuario_lastupdate_tomamuestra = u2.id
            inner join citas c on c.id = pres_p.id_cita and c.id_estado_reg = 1
            left join citas c2 on c2.id = c.id_cita_lab
            left join citas c3 on c3.id = c.id_cita_laboratorio_ce
            left join sub_consultorios sub_cit on c3.id_sub_consultorio=sub_cit.id
            left join consultorios cun_cit on sub_cit.id_consultorio=cun_cit.id";
        
        $cad .= " where laboratorios.id_establecimiento=" . $id_establecimiento;

        if ($dni_beneficiario):
            $cad .= " and asegurados.nro_doc_ident = '" . $dni_beneficiario . "'";
        endif;

        if ($apellido_nombres):
            $cad .= " and (asegurados.paterno ||' '|| asegurados.materno ||' '|| asegurados.nombre) ilike '%" . $apellido_nombres . "%'";
        endif;

        if ($nro_examen_lab):
            $cad .= " and laboratorios.id = " . $nro_examen_lab;
        endif;

        if ($tipo_atencion):
            $cad .= " and pres_p.id_tipo_atencion = " . $tipo_atencion;
        endif;

        if ($id_servicio):
            $cad .= " and consultorios.id = " . $id_servicio;
        endif;

        if ($referido):
            $cad .= " and c.referido = '" . $referido . "' ";
        endif;

        if ($estado == "1"):
            //Pendientes
            $cad .= " and laboratorios.fecha_registro_toma_muestra is null and laboratorios.estado_asistencia_paciente is null";
        endif;
        if ($estado == "2"):
            //Atendidos sigesapol
            $cad .= " and laboratorios.fecha_registro_toma_muestra is not null";
        endif;
        if ($estado == "6"):
            //Anulados
            $cad .= " and laboratorios.estado_asistencia_paciente = 1 ";
        endif;
        
        if ($estado == "8"):
            //Observados
            $cad .= " and laboratorios.estado_asistencia_paciente = 2 ";
        endif;

        if ($fechaDesde != "") {
            //CE debe filtra por la cita programada
            $cad .= " and c3.fecha_hora >= '" . $fechaDesde . "'";
        }
        if ($fechaHasta != "") {
            $cad .= " and c3.fecha_hora < '" . $last_day . "'";
        }

        if ($id_farmacia > 0) {
            $cad .= " and laboratorios.id_farmacia = ". $id_farmacia ." and laboratorios.estado = 1";
        }

        $cad .= " and laboratorios.estado = 1"; // Tambien deberia ir el flag <> 0

        $cad .= " order By c3.fecha_hora asc, laboratorios.id asc";
        // Log::info(["cad" => $cad]); 
        $data = DB::select($cad);
        return $data;
    }

    public function AllExamenLaboratorioEmergenciaList($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */$nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia, $apellido_nombres = "", $paraExportacion = false) {
        
        $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

        /*$res_rango_min = DB::select("select * from config_options where option_name = 'laboratorio_duracion_semaforo_horas_minimo';");//si no existe el campo crealo
        if(count($res_rango_min)==0){
            $rango_min = "1";
        }else{
            $rango_min = $res_rango_min[0]->option_value;
        }

        $res_rango_max = DB::select("select * from config_options where option_name = 'laboratorio_duracion_semaforo_horas_maximo';");//si no existe el campo crealo
        if(count($res_rango_max)==0){
            $rango_max = "2";
        }else{
            $rango_max = $res_rango_max[0]->option_value;
        }*/
        $cad = "select prestaciones.id_ext,asegurados.nombre nombre_beneficiario,
         asegurados.paterno as paterno_beneficiario,
         asegurados.materno as materno_beneficiario,
         asegurados.tipo_doc_ident";

        if (!$paraExportacion) {
            $cad .= ",
            (select
                COUNT(pp_c.id)
            from
                prestacion_procedimientos pp_c
            where
                pp_c.id_lab_rad = laboratorios.id
                and pp_c.tipo_procedimiento = 2
                and pp_c.id_estado_reg = 1) as cantidad_total
             , (
                SELECT COUNT(pp_c.id)
                FROM prestacion_procedimientos pp_c
                LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
                WHERE pp_c.id_lab_rad = laboratorios.id
                  AND pp_c.tipo_procedimiento = 2
                  AND pp_c.confirmar_toma is null
                  AND ppp.pendiente_laboratorio is null
                  and pp_c.id_estado_reg = 1
              ) AS cantidad_sr,
            (SELECT COUNT(pp_c.id)
            FROM prestacion_procedimientos pp_c
            LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
            WHERE pp_c.id_lab_rad = laboratorios.id
            AND pp_c.tipo_procedimiento = 2
            AND (pp_c.confirmar_toma = '0' or pp_c.confirmar_toma = '3')
            AND ppp.pendiente_laboratorio is false
                and pp_c.id_estado_reg = 1) as cantidad_enviado,
            (SELECT COUNT(pp_c.id)
            FROM prestacion_procedimientos pp_c
            LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
            WHERE pp_c.id_lab_rad = laboratorios.id
            AND pp_c.tipo_procedimiento = 2
            AND pp_c.confirmar_toma is null
            AND ppp.pendiente_laboratorio is true
                and pp_c.id_estado_reg = 1) as cantidad_pendiente";
        }

        $cad .= "
        ,ta.id id_tipo_atencion,
        ta.nombre nom_tipo_atencion,
        asegurados.nro_doc_ident, prestaciones.created_at as fec_creacion, 
        /*consultorios.nombre consultorio,*/ ruta_laboratorio,medicos.nombre as nombre_medico, 
        medicos.paterno as paterno_medico, medicos.materno as materno_medico, 
        medicos.dni as dni_medico,
        laboratorios.id as id_lab, --medicos.nombre servicio, 
        --laboratorios.fecha_firma  firma_laboratorio,
        laboratorios.id_establecimiento,
        to_char(laboratorios.fecha_lastupdate_tomamuestra, 'DD/MM/YYYY HH24:MI') as fecha_lastupdate_tomamuestra,
        prestaciones.id as id_prestacion
        --,laboratorios.fecha_atencion 
        ,laboratorios.cod_orden_lab
        , DATE_PART('day', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) * 24 + DATE_PART('hour', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) as diff_horas_desde_toma_muestra, 
        laboratorios.id_evaluacion--, farmacias.nombre as nombre_farmacia
        ,asegurados.sexo sexo_beneficiario
        ,to_char(laboratorios.fecha_firma::timestamp, 'DD/MM/YYYY HH24:MI') AS fecha_firma--nom_especialidad_emergencia
        ,asegurados.flag_referencia referido
        --,to_char(laboratorios.fecha_registro_toma_muestra::timestamp, 'DD/MM/YYYY HH:MI AM') AS fecha_registro_toma_muestra
        ,to_char(asegurados.fecha_nac::timestamp, 'DD/MM/YYYY') AS fecha_nac_beneficiario
        ,extract(year from age(to_date(fecha_nac, 'DD/MM/YYYY'))) || ' A, ' ||
        extract(month from age(to_date(fecha_nac, 'DD/MM/YYYY'))) || ' M y ' ||
        extract(day from age(to_date(fecha_nac, 'DD/MM/YYYY'))) || ' D' AS edad_texto
        ,CASE
            WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 2 THEN 0
            WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 1 THEN 1
            WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 < 1 THEN 2
        END AS estado_emergencia, to_char(laboratorios.fecha_registro_toma_muestra, 'DD/MM/YYYY HH24:MI') as fecha_registro_toma_muestra
        ,laboratorios.estado_asistencia_paciente, laboratorios.obs_asistencia_paciente as motivo_anulacion,
        --STRING_AGG(pp.observacion, '#|# ' ORDER BY pp.id) as medico_observaciones,
        to_char(laboratorios.deleted_at, 'DD/MM/YYYY HH24:MI') as fecha_anulacion,
        laboratorios.observacion,
        laboratorios.has_observacion,
        laboratorios.observacion_toma_muestra,
        laboratorios.obs_lastupdate_tomamuestra,
        to_char(laboratorios.created_at, 'DD/MM/YYYY HH24:MI') as fecha_creacion,
        u1.dni as usuario_toma_muestra,
        u2.dni as usuario_update_toma_muestra
        , p.descripcion nom_prioridad
        , co.nombre nom_especialidad_emergencia, co.id id_especialidad_emerencia
        /*, eeva.unidad_policial*/
        , cuartos.nombre cuarto, pabellones.nombre pabellon, pisos.nombre piso, camas.nombre cama
        from prestaciones
            inner join laboratorios on laboratorios.id_prestacion=prestaciones.id
            inner join asegurados on prestaciones.id_asegurado=asegurados.id
            /*inner join sub_consultorios on prestaciones.id_sub_consultorio=sub_consultorios.id
            inner join consultorios on sub_consultorios.id_consultorio=consultorios.id*/
            inner join medicos on prestaciones.id_medico=medicos.id
            inner join tipo_atenciones ta on prestaciones.id_tipo_atencion =ta.id
            inner join emergencias e on e.id = prestaciones.id_ext
            inner join prioridades p on p.id = e.id_prioridad
            inner join triajes t on t.id = e.id_triaje
            inner join consultorios co on co.id = t.id_servicio
            left join emergencia_evacuados eeva on eeva.id_emergencia = e.id
            left join camas on camas.id = e.cama_estado
            inner join cuartos on camas.id_cuarto=cuartos.id
            inner join asignacion_servicios on asignacion_servicios.id = cuartos.id_asignacion_servicio
            inner join pisos on asignacion_servicios.id_piso=pisos.id
            inner join pabellones on pisos.id_pabellon=pabellones.id
            inner join consultorios con on asignacion_servicios.id_consultorio = con.id
            --inner join prestacion_procedimientos pp on laboratorios.id = pp.id_lab_rad and pp.tipo_procedimiento = 2
            left join users u1 on laboratorios.id_usuario_tomamuestra = u1.id
            left join users u2 on laboratorios.id_usuario_lastupdate_tomamuestra = u2.id
        where 1 = 1";

        if ($fechaDesde != "") {
            //Emergencia y hospitalización debe filtrar por orden creada
            $cad .= " and laboratorios.created_at >= '" . $fechaDesde . "'";
        }
        if ($fechaHasta != "") {
            $cad .= " and laboratorios.created_at < '" . $last_day . "'";
        }

        if ($id_establecimiento != "") {
            $cad .= " and laboratorios.id_establecimiento=" . $id_establecimiento . " and laboratorios.estado = 1 and laboratorios.flag <> 0";
        }
        
        if ($id_farmacia > 0) {
            $cad .= " and laboratorios.id_farmacia = ". $id_farmacia ." and laboratorios.estado = 1";
        }

        if ($dni_beneficiario):
            $cad .= " and asegurados.nro_doc_ident = '" . $dni_beneficiario . "'";
        endif;

        if ($apellido_nombres):
            $cad .= " and (asegurados.paterno ||' '|| asegurados.materno ||' '|| asegurados.nombre) ilike '%" . $apellido_nombres . "%'";
        endif;

        if ($nro_examen_lab):
            $cad .= " and laboratorios.id = " . $nro_examen_lab;
        endif;

        if ($tipo_atencion):
            $cad .= " and prestaciones.id_tipo_atencion = " . $tipo_atencion;
        endif;

        if ($id_servicio):
            $cad .= " and co.id = " . $id_servicio;
        endif;

        /*if ($referido):
            if ($tipo_atencion == "2") {
                if ($referido == "SI") {
                    $condicion = " is not null ";
                }
                if ($referido == "NO") {
                    $condicion = " is null ";
                }
                $cad .= " and eeva.unidad_policial " . $condicion;
            }
        endif;*/

        if ($prioridad_emergencia):
            $cad .= " and e.id_prioridad = " . $prioridad_emergencia;
        endif;

        if ($estado == "1"): // Ordenes Pendientes
            $cad .= " and (laboratorios.flag_toma_muestra IS NULL 
                        AND laboratorios.estado_asistencia_paciente IS NULL)";
        endif;

        if ($estado == "2"):
            //Atendidos sigesapol
            $cad .= " and laboratorios.flag_toma_muestra = 1";
        endif;

        if ($estado == "6"):
            //Anulados
            $cad .= " and laboratorios.fecha_registro_toma_muestra is null and laboratorios.estado_asistencia_paciente = 1 ";
        endif;

        if ($estado == "7"): // Pendientes de Envío
            $cad .= " AND laboratorios.flag_toma_muestra = 1 
                    AND laboratorios.estado_asistencia_paciente IS NULL
                    AND ((SELECT COUNT(pp_c.id)
                        FROM prestacion_procedimientos pp_c
                        LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
                        WHERE pp_c.id_lab_rad = laboratorios.id
                            AND pp_c.tipo_procedimiento = 2
                            AND pp_c.confirmar_toma is null
                            AND ppp.pendiente_laboratorio is true
                            and pp_c.id_estado_reg = 1) > 0)";
        endif;

        if ($estado == "8"):
            //Observados
            $cad .= " and laboratorios.fecha_registro_toma_muestra is null and laboratorios.estado_asistencia_paciente = 2 ";
        endif;

        //Priorizar EMERGENCIA ( UVI y TRAUMATOLOGÏA )
        $cad .= " /*group by prestaciones.id, laboratorios.id, asegurados.id, medicos.id, ta.id, u1.id, u2.id, p.id, co.id, cuartos.id, pabellones.id, pisos.id, camas.id, t.id*/
                order By 
                case
                    when t.descripcion = 'UNIDAD VIGILANCIA INTENSIVA' then 1
                    when t.descripcion = 'SHOCK TRAUMA' then 2
                    else 3
                end ";
        // dd( $cad);
        // Log::info(["cad" => $cad]);
        // dd($cad);
        $data = DB::select($cad);
        return $data;
    }

    /**
     * @deprecated
     */
    public function AllExamenLaboratorioHospitalizacionList($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */$nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia) {
    // dd($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen_lab, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia);
        $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

        $cad = "select prestaciones.*,asegurados.nombre nombre_beneficiario, 
        asegurados.paterno as paterno_beneficiario,asegurados.materno as materno_beneficiario,asegurados.tipo_doc_ident
        , (
            SELECT COUNT(pp_c.id)
            FROM prestacion_procedimientos pp_c
            WHERE pp_c.id_lab_rad = laboratorios.id
              AND pp_c.tipo_procedimiento = 2
              AND (pp_c.confirmar_toma != '3' AND pp_c.confirmar_toma != '0')
            LIMIT 1
          ) AS cantidad_sr
        ,ta.id id_tipo_atencion,ta.nombre nom_tipo_atencion, u.name as digitador, asegurados.nro_doc_ident, 
        prestaciones.created_at as fec_creacion, consultorios.nombre consultorio, ruta_laboratorio,
        medicos.nombre as nombre_medico, medicos.paterno as paterno_medico, medicos.materno as materno_medico, 
        laboratorios.id as id_lab, medicos.nombre servicio, laboratorios.fecha_firma  firma_laboratorio, 
        laboratorios.*, prestaciones.id as id_prestacion
        , DATE_PART('day', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) * 24 + DATE_PART('hour', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) as diff_horas_desde_toma_muestra, 
        laboratorios.id_evaluacion, farmacias.nombre as nombre_farmacia,cons.nombre servicio_hospitalizacion,
        asegurados.sexo as sexo_beneficiario 
        ,asegurados.fecha_nac as fecha_nac_beneficiario 
        ,asegurados.flag_referencia as referido   
        ,CASE
          WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 2 THEN 0
          WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 1 THEN 1
          WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 < 1 THEN 2
	      END AS estado_emergencia, to_char(laboratorios.fecha_registro_toma_muestra, 'DD/MM/YYYY HH24:MI') as fecha_registro_toma_muestra";
        if ($tipo_atencion == "2") {
            $cad .= ", p.descripcion nom_prioridad";
            $cad .= ", t.descripcion nom_especialidad_emergencia";
            $cad .= ", eeva.unidad_policial";
            $cad .= ", cuartos.nombre cuarto, pabellones.nombre pabellon, pisos.nombre piso, camas.nombre cama";
        }

        
        $cad .= " from prestaciones
                inner join laboratorios on laboratorios.id_prestacion=prestaciones.id
                inner join farmacias on farmacias.id = laboratorios.id_farmacia
                inner join asegurados on prestaciones.id_asegurado=asegurados.id
                inner join sub_consultorios on prestaciones.id_sub_consultorio=sub_consultorios.id
                inner join consultorios on sub_consultorios.id_consultorio=consultorios.id
                inner join medicos on prestaciones.id_medico=medicos.id
                inner join tipo_atenciones ta on prestaciones.id_tipo_atencion =ta.id
                inner join users u on prestaciones.created_us =u.id";
        
        if ($tipo_atencion == "3") {
            $cad .= " inner join hospitalizaciones on hospitalizaciones.id = prestaciones.id_ext";
            $cad .= " inner join consultorios cons on cons.id = hospitalizaciones.id_consultorio ";
            /*$cad .= " inner join prioridades p on p.id = e.id_prioridad";
            $cad .= " inner join triajes t on t.id = e.id_triaje";
            $cad .= " left join emergencia_evacuados eeva on eeva.id_emergencia = e.id";
            $cad .= " left join camas on camas.id = e.cama_estado";
            $cad .= " inner join cuartos on camas.id_cuarto=cuartos.id";
            $cad .= " inner join asignacion_servicios on asignacion_servicios.id = cuartos.id_asignacion_servicio";
            $cad .= " inner join pisos on asignacion_servicios.id_piso=pisos.id";
            $cad .= " inner join pabellones on pisos.id_pabellon=pabellones.id";
            $cad .= " inner join consultorios con on asignacion_servicios.id_consultorio = con.id";*/
        }
        $cad .= " where laboratorios.id_establecimiento=" . $id_establecimiento;
        $cad .= " and laboratorios.flag > 0 and laboratorios.estado = 1 ";

        if ($dni_beneficiario):
            $cad .= " and asegurados.nro_doc_ident = '" . $dni_beneficiario . "'";
        endif;

        if ($nro_examen_lab):
            $cad .= " and laboratorios.id = " . $nro_examen_lab;
        endif;

        if ($tipo_atencion):
            $cad .= " and prestaciones.id_tipo_atencion = " . $tipo_atencion;
        endif;

        if ($id_servicio):
            $cad .= " and cons.id = " . $id_servicio;
        endif;

        if ($referido):
            if ($tipo_atencion == "2") {
                if ($referido == "SI") {
                    $condicion = " is not null ";
                }
                if ($referido == "NO") {
                    $condicion = " is null ";
                }
                $cad .= " and eeva.unidad_policial " . $condicion;
            }
        endif;

        if ($prioridad_emergencia):
            $cad .= " and e.id_prioridad = " . $prioridad_emergencia;
        endif;
        if ($estado == "1"):
            //Pendientes
            $cad .= " and laboratorios.fecha_registro_toma_muestra is null and laboratorios.estado_asistencia_paciente is null";
        endif;
        if ($estado == "2"):
            //Atendidos sigesapol
            $cad .= " and laboratorios.fecha_registro_toma_muestra is not null";
        endif;
        if ($estado == "6"):
            //No Atendidos
            $cad .= " and laboratorios.fecha_registro_toma_muestra is null and laboratorios.estado_asistencia_paciente = 1 ";
        endif;

        if ($fechaDesde != "") {
            //Emergencia y hospitalización debe filtrar por orden creada
            $cad .= " and laboratorios.created_at >= '" . $fechaDesde . "'";
        }
        if ($fechaHasta != "") {
            $cad .= " and laboratorios.created_at < '" . $last_day . "'";
        }
        
        if ($id_farmacia > 0) {
            $cad .= " and laboratorios.id_farmacia = ". $id_farmacia ." and laboratorios.estado = 1";
        }

        //Priorizar EMERGENCIA ( UVI y TRAUMATOLOGÏA )
        /*$cad .= " order By 
                case
                    when t.descripcion = 'UNIDAD VIGILANCIA INTENSIVA' then 1
                    when t.descripcion = 'SHOCK TRAUMA' then 2
                    else 3
                end ";*/
        
        // Log::info(["cad" => $cad]);
        // dd($cad);
        $data = DB::select($cad);
        return $data;
    }

    public function AllExamenLaboratorioHospitalizacionList_v2($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia, $id_hora_toma_muestra = "", $id_ubicacion_hospital = "", $paraExportacion = false) {
        $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

        $query = DB::table('prestaciones')->select([
            //'prestaciones.*',
            'prestaciones.id_ext',
            'asegurados.nombre as nombre_beneficiario',
            'asegurados.paterno as paterno_beneficiario',
            'asegurados.materno as materno_beneficiario',
            'asegurados.tipo_doc_ident',
            'asegurados.nro_doc_ident',
            'asegurados.sexo as sexo_beneficiario',
            'asegurados.fecha_nac as fecha_nac_beneficiario',
            DB::raw("extract(year from age(to_date(fecha_nac, 'DD/MM/YYYY'))) || ' A, ' ||
            extract(month from age(to_date(fecha_nac, 'DD/MM/YYYY'))) || ' M y ' ||
            extract(day from age(to_date(fecha_nac, 'DD/MM/YYYY'))) || ' D' AS edad_texto"),
            'asegurados.flag_referencia as referido',
            'ta.id as id_tipo_atencion',
            'ta.nombre as nom_tipo_atencion',
            'prestaciones.created_at as fec_creacion',
            'consultorios.nombre as consultorio',
            'laboratorios.ruta_laboratorio',
            'medicos.nombre as nombre_medico',
            'medicos.paterno as paterno_medico',
            'medicos.materno as materno_medico',
            'laboratorios.id as id_lab',
            'medicos.nombre as servicio',
            // 'laboratorios.fecha_firma as firma_laboratorio',
            //'laboratorios.*',
            'laboratorios.id_establecimiento',
            'laboratorios.cod_orden_lab',
            DB::raw("to_char(laboratorios.fecha_firma::timestamp, 'DD/MM/YYYY HH24:MI') AS fecha_firma"),
            'prestaciones.id as id_prestacion',
            'laboratorios.id_evaluacion',
            'farmacias.nombre as nombre_farmacia',
            'cons.nombre as servicio_hospitalizacion',
            'laboratorios.estado_asistencia_paciente',
            'laboratorios.obs_asistencia_paciente as motivo_anulacion',
            DB::raw("to_char(laboratorios.deleted_at, 'DD/MM/YYYY HH24:MI') as fecha_anulacion"),
            'laboratorios.observacion',
            'laboratorios.has_observacion',
            'laboratorios.observacion_toma_muestra',
            'laboratorios.obs_lastupdate_tomamuestra',
            'laboratorios.id_cama',
            'u1.dni as usuario_toma_muestra',
            'u2.dni as usuario_update_toma_muestra',
            DB::raw("to_char(laboratorios.created_at, 'DD/MM/YYYY HH24:MI') as fecha_creacion"),
            DB::raw("DATE_PART('day', now()::timestamp - laboratorios.fecha_registro_toma_muestra) * 24 + 
                     DATE_PART('hour', now()::timestamp - laboratorios.fecha_registro_toma_muestra) as diff_horas_desde_toma_muestra"),
            DB::raw("to_char(laboratorios.fecha_registro_toma_muestra, 'DD/MM/YYYY HH24:MI') as fecha_registro_toma_muestra"),
            DB::raw("to_char(laboratorios.fecha_lastupdate_tomamuestra, 'DD/MM/YYYY HH24:MI') as fecha_lastupdate_tomamuestra"),
            DB::raw("CASE
                WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 2 THEN 0
                WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 1 THEN 1
                WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 < 1 THEN 2
            END AS estado_emergencia"),
            DB::raw("CASE
                WHEN laboratorios.id_hora_toma_muestra = 0 THEN 'OTRO'
                WHEN laboratorios.id_hora_toma_muestra = 1 THEN '05:00 AM'
                WHEN laboratorios.id_hora_toma_muestra = 2 THEN '09:00 AM'
                WHEN laboratorios.id_hora_toma_muestra = 3 THEN '16:00 PM'
                WHEN laboratorios.id_hora_toma_muestra = 4 THEN '22:00 PM'
            END AS hora_toma_muestra"),
            DB::raw("CASE
                WHEN pa.id_ubicacion = 1 THEN 'HISTORICO'
                WHEN pa.id_ubicacion = 2 THEN 'PIP'
                ELSE ''
            END AS ubicacion_hospital")
        ])
        ->join('laboratorios', 'laboratorios.id_prestacion', '=', 'prestaciones.id')
        ->join('farmacias', 'farmacias.id', '=', 'laboratorios.id_farmacia')
        ->join('asegurados', 'prestaciones.id_asegurado', '=', 'asegurados.id')
        ->join('sub_consultorios', 'prestaciones.id_sub_consultorio', '=', 'sub_consultorios.id')
        ->join('consultorios', 'sub_consultorios.id_consultorio', '=', 'consultorios.id')
        ->join('medicos', 'prestaciones.id_medico', '=', 'medicos.id')
        ->join('tipo_atenciones as ta', 'prestaciones.id_tipo_atencion', '=', 'ta.id')
        ->leftJoin('users as u1', 'laboratorios.id_usuario_tomamuestra', '=', 'u1.id')
        ->leftJoin('users as u2', 'laboratorios.id_usuario_lastupdate_tomamuestra', '=', 'u2.id')
        // ->join('hospitalizaciones', 'hospitalizaciones.id', '=', 'prestaciones.id_ext')
        ->join('consultorios as cons', 'laboratorios.id_servicio', '=', 'cons.id')
        ->join('camas as ca', 'laboratorios.id_cama', '=', 'ca.id')
        ->join('cuartos as cu', 'ca.id_cuarto', '=', 'cu.id')
        ->join('asignacion_servicios as as2', 'cu.id_asignacion_servicio', '=', 'as2.id')
        ->join('pisos as pi', 'as2.id_piso', '=', 'pi.id')
        ->join('pabellones as pa', 'pi.id_pabellon', '=', 'pa.id');

        if (!$paraExportacion) {
            $query->join('prestacion_procedimientos as pp', function ($join) {
                $join->on('laboratorios.id', '=', 'pp.id_lab_rad')
                    ->where('pp.tipo_procedimiento', '=', 2);
            });

            $query->addSelect([
                DB::raw("(
                    select COUNT(pp_c.id)
                    from prestacion_procedimientos pp_c
                    where pp_c.id_lab_rad = laboratorios.id
                        and pp_c.tipo_procedimiento = 2
                        and pp_c.id_estado_reg = 1
                ) as cantidad_total"),
                DB::raw("(
                    SELECT COUNT(pp_c.id)
                    FROM prestacion_procedimientos pp_c
                    LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
                    WHERE pp_c.id_lab_rad = laboratorios.id
                      AND pp_c.tipo_procedimiento = 2
                      AND pp_c.confirmar_toma is null
                      AND ppp.pendiente_laboratorio is null
                ) AS cantidad_sr"),
                DB::raw("(
                    SELECT COUNT(pp_c.id)
                    FROM prestacion_procedimientos pp_c
                    LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
                    WHERE pp_c.id_lab_rad = laboratorios.id
                    AND pp_c.tipo_procedimiento = 2
                    AND (pp_c.confirmar_toma = '0' or pp_c.confirmar_toma = '3')
                    AND ppp.pendiente_laboratorio is false
                        and pp_c.id_estado_reg = 1
                ) as cantidad_enviado"),
                DB::raw("(
                    SELECT COUNT(pp_c.id)
                    FROM prestacion_procedimientos pp_c
                    LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
                    WHERE pp_c.id_lab_rad = laboratorios.id
                    AND pp_c.tipo_procedimiento = 2
                    AND pp_c.confirmar_toma is null
                    AND ppp.pendiente_laboratorio is true
                    AND pp_c.id_estado_reg = 1
                ) as cantidad_pendiente"),
                DB::raw("STRING_AGG(pp.observacion, '#|#' ORDER BY pp.id) as medico_observaciones"),
            ]);
        }

        // Filtros WHERE
        $query->where('laboratorios.id_establecimiento', $id_establecimiento)
              ->where('laboratorios.flag', '>', 0)
              ->where('laboratorios.estado', 1);

        // Filtro por rango de fechas
        if (!empty($fechaDesde)) {
            $query->where('laboratorios.created_at', '>=', $fechaDesde);
        }

        if (!empty($fechaHasta)) {
            $query->where('laboratorios.created_at', '<', $last_day);
        }

        // Filtro por farmacia
        if (!empty($id_farmacia) && $id_farmacia > 0) {
            $query->where('laboratorios.id_farmacia', $id_farmacia)
                  ->where('laboratorios.estado', 1);
        }

        // Filtro por DNI
        if (!empty($dni_beneficiario)) {
            $query->where('asegurados.nro_doc_ident', $dni_beneficiario);
        }

        // Filtro por número de examen
        if (!empty($nro_examen_lab)) {
            $query->where('laboratorios.id', $nro_examen_lab);
        }

        // Filtro por tipo de atención
        if (!empty($tipo_atencion)) {
            $query->where('prestaciones.id_tipo_atencion', $tipo_atencion);
        }

        // Filtro por servicio
        if (!empty($id_servicio)) {
            $query->where('cons.id', $id_servicio);
        }

        // Filtros por estado
        switch ($estado) {
            case "1": // Pendientes
                $query->whereNull('laboratorios.flag_toma_muestra')
                        ->whereNull('laboratorios.estado_asistencia_paciente');
                break;
            case "2": // Atendidos
                $query->where('laboratorios.flag_toma_muestra', 1);
                break;
            case "6": // Anulados
                $query->whereNull('laboratorios.fecha_registro_toma_muestra')
                        ->where('laboratorios.estado_asistencia_paciente', 1);
                break;
            case "7": // Pendientes de Envío
                $query->where('laboratorios.flag_toma_muestra', 1)
                        ->whereNull('laboratorios.estado_asistencia_paciente')
                        ->whereRaw("(
                            SELECT COUNT(pp_c.id)
                            FROM prestacion_procedimientos pp_c
                            LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
                            WHERE pp_c.id_lab_rad = laboratorios.id
                            AND pp_c.tipo_procedimiento = 2
                            AND pp_c.confirmar_toma is null
                            AND ppp.pendiente_laboratorio is true
                            AND pp_c.id_estado_reg = 1
                        ) > 0");
                break;
            case "8": // Observados
                $query->whereNull('laboratorios.fecha_registro_toma_muestra')
                        ->where('laboratorios.estado_asistencia_paciente', 2);
                break;
        }

        if (!empty($id_hora_toma_muestra) && $id_hora_toma_muestra != "") {
            $query->where('laboratorios.id_hora_toma_muestra', '=', $id_hora_toma_muestra);
        }

        if (!empty($id_ubicacion_hospital) && $id_ubicacion_hospital != "") {
            $query->where('pa.id_ubicacion', '=', $id_ubicacion_hospital);
        }

        if (!$paraExportacion) {
            $query->groupBy(
                'prestaciones.id',
                'laboratorios.id',
                'asegurados.id',
                'farmacias.id',
                'consultorios.id',
                'medicos.id',
                'ta.id',
                'u1.id',
                'u2.id',
                'cons.id',
                'pa.id'
            );
        }

        return $query->get();
    }

    public function obtenerOrdenesDeLaboratorioCitaOrigen($nro_doc, $id_establecimiento, $id_servicio = 0){
        
        $cad = "SELECT * FROM sp_obtener_ordenes_laboratorio_cita_origen(?,?,?)";
        
        $datos = DB::select($cad,array($nro_doc, $id_establecimiento, $id_servicio));
        return $datos;
    }

    public function obtenerOrdenesDeLaboratorioCitaOrigen2($nro_doc, $id_establecimiento, $id_cqx_intervencion){
        
        $cad = "SELECT * FROM sp_obtener_ordenes_laboratorio_cita_origen_v2(?,?,?)";
        
        $datos = DB::select($cad,array($nro_doc, $id_establecimiento, $id_cqx_intervencion));
        return $datos;
    }

    public function obtenerOrdenesDeLaboratorioCitaOrigenCitaResultado($nro_doc, $id_establecimiento){
        
        $cad = "SELECT * FROM sp_obtener_ordenes_laboratorio_cita_origen_cita_resultado_v2(?,?)";
        
        $datos = DB::select($cad,array($nro_doc, $id_establecimiento));
        return $datos;
    }

    public function obtenerCpmsPorIdLaboratorio($id_laboratorio)
    {
        $res = DB::table('prestacion_procedimientos')
                ->join('labnivelatenciones','labnivelatenciones.id','=','prestacion_procedimientos.id_rua') 
                ->where('prestacion_procedimientos.tipo_procedimiento',2)
                ->where('prestacion_procedimientos.id_estado_reg',1)
                ->where('prestacion_procedimientos.id_lab_rad',$id_laboratorio)
                ->select('labnivelatenciones.codigo_lab','labnivelatenciones.descripcion_corta')
                ->get();
        return $res; 
    }


    
    public function obtenerPacienteEmergencia($idPaciente,$idCita){
      $cad = "
            select l.id_prestacion  ,pp.id_procedimiento,pp.comentario_examen_repetida
              ,p2.codigo examen_cod
              , p.id_asegurado
              ,(select lv.descripcion_corta from labnivelatenciones lv where lv.id_procedimiento= pp.id_procedimiento limit 1) examen_descripcion
              ,d.nombre diagnostico
              ,pp.id id_prestacion_procedimiento
              ,d.nombre nombre_diagnostico
              ,(select up.descripcion_upss  from  upsses up where up.codigo = p.codigo_upss limit 1 ) descripcion_upss
              ,m.nombre medico_nombre,m.paterno medico_paterno,m.materno medico_amterno,pp.created_at fecha_atencion
              ,co.nombre servicio
              ,pp.migrado_lab
              ,l.id id_laboratorio
              ,pp.id_evaluacion
              ,pp.id_rua
              ,d.codigo diagnostico_codigo
              , ppp.pendiente_laboratorio
              from laboratorios l  
            inner join prestaciones p on p.id = l.id_prestacion
            inner join prestacion_procedimientos pp on  pp.id_lab_rad  = l.id
            inner join procedimientos p2 on p2.id = pp.id_procedimiento
            inner join receta_diagnosticos rd on rd.id_prestacion = l.id_prestacion 
            inner join diagnosticos d on d.id = rd.id_diagnostico
            inner join medicos m on m.id = p.id_medico 
            inner join sub_consultorios sc   on sc.id = p.id_sub_consultorio 
            inner join consultorios  co   on co.id = sc.id_consultorio
            left join   prestacion_procedimientos_proveedor ppp on ppp.id_prestacion_procedimientos = pp.id
            where  p.id_establecimiento  = 76 
            and p.id_cita_dependiente_laboratorio = $idCita
            --and l.fecha_firma  notnull 
            --and p.flag = 2
            and pp.tipo_procedimiento = 2 
            order by l.id desc limit 100;
            
      ";
      $data = DB::select($cad);
      return $data;
    }

    public function obtenerUserlogeado($idUser){
      $cad = "      
        select u.dni dni, u.name Nombre, u.materno, u.paterno, p.nombre profecion from users  u 
        inner join medicos m on u.dni  = m.dni 
        inner join profesiones p on p.id = m.id_profesion 
        where u.id = $idUser limit 1;          
      ";
      $data = DB::select($cad);
      return $data;
    }

    public function obtenerPaciente($idPaciete){
      $cad = "      
        select a.*,tb.descripcion tipo_beneficioario
        ,pda.cip ,a.sexo,a.fecha_nac
        from asegurados a
        left join tipo_beneficiarios tb on a.id_tipo_beneficiario = tb.id 
        left join persona_datos_adicionales pda ON  pda.id_asegurado = a.id
        where a.id = $idPaciete;        
      ";
      $data = DB::select($cad);
      return $data;
    }

    public function obteneInformacionWs($idPrestacionProcedimiento,$idPrestacion,$IdTipoAtencion,$IdEvaluacion){

      $primerDiagnostico = "0";
      $idPrestacion = intval($idPrestacion);
      if ($IdTipoAtencion == 1) {
          // First query - when id_tipo_atencion = 1
          // dd($idPrestacion);
          $primerDiagnostico = DB::table('receta_diagnosticos')
              ->where('id_prestacion', $idPrestacion)
              ->orderBy('id')
              ->limit(1)
              ->value('id_diagnostico');
              
      } else {
          if ($IdTipoAtencion !== null) {
              // Second query - when id_tipo_atencion != 1 and id_evaluacion is not null
              $primerDiagnostico = DB::table('receta_diagnosticos')
                  ->where('id_prestacion', $idPrestacion)
                  ->where('id_evaluacion', $IdEvaluacion)
                  ->orderBy('id')
                  ->limit(1)
                  ->value('id_diagnostico');
          } else {
              // Third query - when id_tipo_atencion != 1 and id_evaluacion is null
              $primerDiagnostico = DB::table('receta_diagnosticos')
                  ->where('id_prestacion', $idPrestacion)
                  ->where('id_evaluacion', 0)
                  ->orderBy('id')
                  ->limit(1)
                  ->value('id_diagnostico');
          }
      }
      // dd($primerDiagnostico);
      $cad ="
        select 
          m.nombre medico_nombre, m.paterno medico_paterno,m.materno medico_materno,m.dni medico_dni
          ,a.nombre paciente_nombre, a.paterno paciente_paterno,a.materno paciente_materno,a.nro_doc_ident paciente_dni,a.sexo paciente_sexo
          ,a.fecha_nac 
          --,tg.descripcion_corta grado
          ,es.id establecimiento_id
          ,p.fecha_atencion
          ,p.id_tipo_atencion 
          ,p.id_servicio
          ,co.id id_consultorio
          --,ah.nro_historia
          ,tb.descripcion tipo_beneficiario
          ,pp.id_diagnostico
          ,pro.codigo codigo_procedimiento,pro.descripcion procedimiento_descripcion
          ,(select sub_ah.nro_historia from asegurado_historias sub_ah where sub_ah.id_asegurado = p.id_asegurado limit 1) nro_historia
          ,tb.descripcion tipo_beneficiario
          ,di.nombre diagnosticos_nombre, di.codigo diagnosticos_codigo
          ,prov.codigo_proveedor --VALIDAR ESTE CAMBIO
          ,prov.cpms_personalizado
          ,pp.id_procedimiento 
          ,pp.id_prestacion 
          ,p.created_at  fecha_registro_orden
          ,p.upss
          ,a.grado grado
          ,a.situacion_paciente paciente_situacion
          ,pda.id_grado
          ,a.id id_asegurado
          ,la.id id_laboratorio
          ,usu.name||' '||usu.paterno||' '||usu.materno as nombre_usuario
          ,rec_dx.id_tipo_diagnostico
          ,rec_dx.id id_receta_diagnosticos
          ,la.obs_lastupdate_tomamuestra observacion
          ,(CASE WHEN coalesce(rec_dx.id_tipo_diagnostico,0) = 0 THEN ''
            WHEN rec_dx.id_tipo_diagnostico = 1 THEN 'P'
            WHEN rec_dx.id_tipo_diagnostico = 2 THEN 'D'
            WHEN rec_dx.id_tipo_diagnostico = 3 THEN 'R' END) AS tipo_diagnostico
        from prestacion_procedimientos  pp
        inner join prestaciones p on pp.id_prestacion = p.id
        inner join asegurados a on a.id = p.id_asegurado 
        inner join citas ci on ci.id = p.id_cita
        left join persona_datos_adicionales pda on pda.id_asegurado = p.id_asegurado
        left join medicos m on m.id =p.id_medico 
        left join establecimientos es on es.id = p.id_establecimiento
        left join sub_consultorios sc  on sc.id = p.id_sub_consultorio
        left join consultorios co on co.id = sc.id_consultorio 
        left join procedimientos pro on pro.id = pp.id_procedimiento 
        left join laboratorios la on la.id_prestacion = p.id
        left join users usu ON la.id_usuario_tomamuestra = usu.id
        --left join labnivelatenciones prov on prov.id_procedimiento  = pp.id_procedimiento       
        --inner join asegurado_historias ah on ah.id_asegurado = p.id_asegurado
        left join labnivelatenciones prov on prov.id_procedimiento  = pp.id_procedimiento  
        left join tipo_beneficiarios tb on tb.id = a.id_tipo_beneficiario 
        left join diagnosticos di on di.id =  $primerDiagnostico
        left join receta_diagnosticos rec_dx ON rec_dx.id_prestacion = pp.id_prestacion
		    where pp.id =$idPrestacionProcedimiento and pp.tipo_procedimiento = 2 limit 1;
      ";

      $data = DB::select($cad);
      // dd($data);
      return $data;
    }


    public function obtenerCitaLab($idCita){
      $cad = "
        select * from citas where id = $idCita
      ";
      $data = DB::select($cad);
      // dd($data);
      return $data;
    }

    public function ModificarTomaMuestraUnitario(  $id_procedimiento_prestacion,$estado,$migrado) {
      $res = "";
      DB::beginTransaction();
      try {          

              $affected = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ? , migrado_lab = ?
                                      WHERE id = ?",
                                      [$estado,$migrado, $id_procedimiento_prestacion]);

          
          // $affected2 = DB::update("UPDATE laboratorios SET obs_lastupdate_tomamuestra = ?, fecha_lastupdate_tomamuestra = now(), id_usuario_lastupdate_tomamuestra = ? WHERE id = ? ",
          //                             [$observacion, $id_user, $id_laboratorio]);

          DB::commit();
          $res = "Y";
      } catch (\Exception $e) {
          DB::rollback();
          throw $e;
      } catch (\Throwable $th) {
          DB::rollback();
          throw $th;
      }
      return $res;
  }


  public function ActilizardataLab($idLaboratorio,$observacion,$idUser,$codigoSusalud,$secuencia){


  $cad = "UPDATE laboratorios SET secuencial = $secuencia, 
				observacion_toma_muestra = '$observacion', 
				fecha_registro_toma_muestra = now(),
				id_usuario_tomamuestra = $idUser,
				cod_siteds_susalud = '$codigoSusalud'
	WHERE  id = $idLaboratorio;";
  DB::update($cad);

  $cad2 ="select l.id, 
						l.fecha_atencion, 
						l.fecha_firma, 
						l.ruta_laboratorio, 
						l.flag, l.secuencial, 
						l.fecha_registro_toma_muestra, 
						l.estado, 
						to_char(l.fecha_registro_toma_muestra,'DD/MM/YYYY') AS fecha_toma_muestra
					from laboratorios l where l.id = $idLaboratorio and l.estado <> 0 order by l.id asc;";
          $data = DB::select($cad2);
      return $data;
  }


  function updateEstadoPrestacionesProcedimientos($idPrestacionesProcedimientos){
    $cad = "
      SELECT COUNT(pp2.id)
      FROM prestacion_procedimientos pp2
      WHERE pp2.id_prestacion = (
          SELECT pp1.id_prestacion
          FROM prestacion_procedimientos pp1
          WHERE pp1.id = $idPrestacionesProcedimientos
      );
      
    ";
    $data = DB::select($cad);
    return $data;
  }


  public function obtener_orden_prestacion($idsprestaciones){
    $cad = "
      select * from prestacion_procedimientos pp where id in($idsprestaciones)
    order by created_at 
    ";
    $data = DB::select($cad);
    return $data;
  }


  public function GetCitasByDni($dni_beneficiario)
  {

      $cad = "select 
      t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,
      t2.nro_doc_ident,to_char(t1.fecha_hora, 'DD/MM/YYYY') dia,to_char(t1.fecha_hora, 'HH:MI AM') hora,t3.id as id_establecimiento,t3.nombre establecimiento, t6.id AS id_servicio, t6.nombre servicio,
      t4.descripcion consultorio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,
      t1.id_estado_cita,t1.id_estado_reg, tc.descripcion as tipo_cita, t1.id_pp
      ,l.id id_laboratorio,l.cod_orden_lab,pres.id id_prestacion,pres.id_cita,
      to_char(t1.created_at, 'DD/MM/YYYY HH:MI AM') cifa_fecha_creacion ,
      (case 
      when t1.id_estado_cita=1 Then 'Programado'
      when t1.id_estado_cita=2 Then 'Confirmado'
      when t1.id_estado_cita=3 Then 'Derivado'
      when t1.id_estado_cita=4 Then 'Atendido'
      when t1.id_estado_cita=5 Then 'Anulado'
      when t1.id_estado_cita=6 Then 'Reservado'
      when t1.id_estado_cita=7 Then 'Observado'
      when t1.id_estado_cita=8 Then 'Migrado'
      when t1.id_estado_cita=9 Then 'Atendido en Triaje'
      when t1.id_estado_cita=10 Then 'Re-Programado'
      end)estado_cita, t1.observacion,
      (select string_agg(distinct con_o.nombre, ', ')
        from prestaciones pres_o
        inner join citas c_o on c_o.id = pres_o.id_cita
        inner join sub_consultorios sc_o on sc_o.id = c_o.id_sub_consultorio
        inner join consultorios con_o on con_o.id = sc_o.id_consultorio
        where pres_o.id_cita_dependiente_laboratorio = t1.id
      ) servicios_origen,
      (u.paterno||' '||u.materno||', '||u.name) usuario_registro
      FROM citas t1
      inner join asegurados t2 on t1.id_asegurado = t2.id 
      inner join establecimientos t3 on t1.id_establecimiento=t3.id
      inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
      inner join consultorios t6 on t4.id_consultorio=t6.id
      left join prestaciones pres on pres.id_cita_dependiente_laboratorio = t1.id 
      left join laboratorios l on l.id_prestacion  = pres.id 
      left join medicos t5 on t1.id_medico=t5.id
      left join tipo_cita tc on tc.id = t1.id_tipo_cita 
      left join users u on t1.created_us = u.id
      WHERE t2.nro_doc_ident='" . $dni_beneficiario . "' 
      AND t6.id = 113
      ORDER BY t1.fecha_hora DESC";
      $data = DB::select($cad);
      return $data;
  }

  public function obterInfromacionCita($idCita){
    /*$cad_2 ="select c.id from prestaciones p 
            inner join citas c on p.id_cita_dependiente_laboratorio  = c.id
            inner join prestacion_procedimientos pp on pp.id_prestacion = p.id
            inner join procedimientos pro on pp.id_procedimiento = pro.id 
            inner join laboratorios l on l.id_prestacion  = p.id
            where (p.id_cita_dependiente_laboratorio  = $idCita or c.id = $idCita )and pp.tipo_procedimiento = 2";

    */

    $cad_2 ="select
                c.id
            from
                laboratorios l
            inner join prestaciones p on l.id_prestacion = p.id
            inner join citas c on c.id = p.id_cita_dependiente_laboratorio 
            inner join prestacion_procedimientos pp on pp.id_prestacion = p.id
            where p.id_cita_dependiente_laboratorio = $idCita and pp.tipo_procedimiento = 2
            union
            select
                c.id
            from
                laboratorios l
            inner join prestaciones p on l.id_prestacion = p.id
            inner join citas c on c.id = p.id_cita_dependiente_laboratorio 
            inner join prestacion_procedimientos pp on pp.id_prestacion = p.id
            where c.id = $idCita and pp.tipo_procedimiento = 2";
          $data2 = DB::select($cad_2);
          $array_data = [];
          foreach ($data2 as $key => $value) {
            # code...
           array_push($array_data,$value->id);
          }
          array_push($array_data,$idCita);
          // dd($array_data);
          $cadena = implode(',', $array_data);

    $cad ="select c.id,p.id_tipo_atencion,pp.tipo_procedimiento,pro.codigo ,pro.descripcion,c2.nombre  consultorio_nombre   from prestaciones p 
          left join citas c  on p.id_cita = c.id
          inner join prestacion_procedimientos pp on pp.id_prestacion = p.id
          inner join procedimientos pro on pp.id_procedimiento = pro.id 
          inner join laboratorios l on l.id_prestacion  = p.id
          inner join sub_consultorios sc   on sc.id = p.id_sub_consultorio 
          inner join consultorios c2     on c2.id = sc.id_consultorio  
          where c.id_cita_laboratorio_ce in ($cadena) and pp.tipo_procedimiento = 2";
          $data = DB::select($cad);
          return $data;
  }


  public function reprogramarCita($idCita,$id_sub_consultorio,$fecha){

    
      DB::update('UPDATE citas SET fecha_hora = ?, id_sub_consultorio = ? WHERE id = ?', [
      $fecha, $id_sub_consultorio, $idCita
      ]);

      DB::update('UPDATE citas_llamados SET fecha_hora = ?, id_sub_consultorio = ? WHERE id_cita = ?', [
          $fecha, $id_sub_consultorio, $idCita
      ]);
     
      DB::commit();
  }

  public function getAllOptions()
  {
      $options = DB::table('config_options')->get();
      return $options;
  }

  public function getAllOptionsKey($key)
  {
      $options = DB::table('config_options')->where('option_name',$key)->first();
      return $options;
  }

  public function obtener_consultorio_id_lab($id_laboratorio){
      $cad ="select c.nombre  from laboratorios l 
            inner join establecimientos e on l.id_establecimiento = e.id
            inner join prestaciones  p on l.id_prestacion = p.id
            inner join sub_consultorios sc  on sc.id = p.id_sub_consultorio 
            inner join consultorios c on c.id = sc.id_consultorio 
            where l.id = $id_laboratorio;";
      $data = DB::select($cad);
      return $data[0];
  }

  function getHistorialAtencionesByIdAsegurado($id_asegurado) {
    $result = DB::select("SELECT * FROM sp_get_history_laboratorio_nivel_1_2_by_asegurado(?)", [$id_asegurado]);
    return $result;
  }
  
    public function getPrestacionProcedimiento($id_laboratorio){
        $cad = "select p.codigo ,p.descripcion 
                ,pp.confirmar_toma 
                ,lab.descripcion_corta name_examen 
                ,lab.descripcion_corta
                ,pp.id_rua
                ,lab.cpms_personalizado  
                from prestacion_procedimientos pp 
                inner join procedimientos p on p.id  = pp.id_procedimiento 
                inner join labnivelatenciones lab on lab.id = pp.id_rua 
                where pp.tipo_procedimiento = 2 and (pp.confirmar_toma is null or pp.confirmar_toma = '1')
                and pp.id_lab_rad =  $id_laboratorio";
        $data = DB::select($cad);
        return $data;
    }

       public function getPrestacionProcedimiento_paso1($id_laboratorio){
        $cad = "select p.codigo ,p.descripcion 
                ,pp.confirmar_toma 
                ,lab.descripcion_corta name_examen 
                ,lab.descripcion_corta
                ,pp.id_rua
                ,lab.cpms_personalizado  
                ,ppp.pendiente_laboratorio
                ,CASE WHEN pp.confirmar_toma IS NULL
                    AND (ppp.pendiente_laboratorio IS NULL OR ppp.pendiente_laboratorio IS FALSE)
                    AND NOT EXISTS (
                        SELECT 1
                        FROM prestacion_procedimientos pp_sr2
                        inner join labnivelatenciones lna_sr2 on pp_sr2.id_rua = lna_sr2.id
                        inner join laboratorios la_sr2 on pp_sr2.id_lab_rad = la_sr2.id
                        inner join prestaciones pre_sr2 on la_sr2.id_prestacion = pre_sr2.id
                        inner join citas ci_sr2 on pre_sr2.id_cita = ci_sr2.id
                        left join prestacion_procedimientos_proveedor ppp_sr2
                            on pp_sr2.id = ppp_sr2.id_prestacion_procedimientos
                        inner join laboratorios la_this on la_this.id = $id_laboratorio
                        inner join prestaciones pre_this on la_this.id_prestacion = pre_this.id
                        inner join citas ci_this on pre_this.id_cita = ci_this.id
                        WHERE pp_sr2.tipo_procedimiento = 2
                        AND pp_sr2.id_estado_reg = 1
                        AND ci_sr2.id_cita_laboratorio_ce = ci_this.id_cita_laboratorio_ce
                        AND lna_sr2.cpms_personalizado = lab.cpms_personalizado
                        AND (pp_sr2.confirmar_toma IN ('0','3') OR ppp_sr2.pendiente_laboratorio IS TRUE)
                        AND pp_sr2.id <> pp.id
                    )
                    THEN 1 ELSE 0 END as es_sr_real
                from prestacion_procedimientos pp 
                inner join procedimientos p on p.id  = pp.id_procedimiento 
                inner join labnivelatenciones lab on lab.id = pp.id_rua 
                left join (
                    select distinct on (id_prestacion_procedimientos)
                        id_prestacion_procedimientos,
                        pendiente_laboratorio
                    from prestacion_procedimientos_proveedor
                    order by id_prestacion_procedimientos
                ) ppp on pp.id = ppp.id_prestacion_procedimientos
                where pp.tipo_procedimiento = 2 
                and pp.id_lab_rad = $id_laboratorio
                and pp.id_estado_reg = 1
                order by pp.id;";
        $data = DB::select($cad);
        return $data;
    }

    public function GetLaboratorioByIdInterconsulta($id_interconsulta) {
        $datos = Laboratorio::where('id_interconsulta', '=', $id_interconsulta)->where('estado', '<>', 0)->get();
        return $datos;
    }
  public function getOrdenLaboratorioByFiltersNivel12($params) {
    $id_tipo_atencion   = $params['id_tipo_atencion'] ? $params['id_tipo_atencion'] : null;
    $id_establecimiento = $params['id_establecimiento'] ? $params['id_establecimiento'] : null;
    $fecha_inicio       = $params['fecha_inicio'] ? $params['fecha_inicio'] : null;
    $fecha_fin          = $params['fecha_fin'] ? $params['fecha_fin'] : null;
    $nro_doc_ident      = $params['nro_doc_ident'] ? $params['nro_doc_ident'] : null;
    $id_consultorio     = $params['id_consultorio'] ? $params['id_consultorio'] : null;
    $id_prioridad       = $params['id_prioridad'] ? $params['id_prioridad'] : null;
    $referido           = $params['referido'] ? $params['referido'] : null;
    $cod_orden_lab      = $params['cod_orden_lab'] ? $params['cod_orden_lab'] : null;
    $estado_n_1_2       = $params['estado_n_1_2'] !== "" ? $params['estado_n_1_2'] : null;

    $result = DB::select("SELECT * FROM sp_get_orden_laboratorio_by_filters_nivel_1_2(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
        $id_tipo_atencion,
        $id_establecimiento,
        $fecha_inicio,
        $fecha_fin,
        $nro_doc_ident,
        $id_consultorio,
        $id_prioridad,
        $referido,
        $cod_orden_lab,
        $estado_n_1_2
    ]);

    return $result;
  }

  

    public function actializarDatosLboratorio($id_laboratorio,$obs_segunda, $id_user) {
      
    DB::update("UPDATE laboratorios
        SET 
            fecha_lastupdate_tomamuestra = NOW(), -- o la fecha que necesites
            obs_lastupdate_tomamuestra = '$obs_segunda',
            id_usuario_lastupdate_tomamuestra = $id_user -- reemplaza con el ID real del usuario
        WHERE id  =  $id_laboratorio ;");
     
      DB::commit();
    }

    public function actializarDatosLboratorio_al_insert($id_laboratorio,$observacion) {
      
    DB::update("UPDATE laboratorios
        SET -- o la fecha que necesites
            observacion_toma_muestra = '$observacion'
        WHERE id  =  $id_laboratorio ;");
     
      DB::commit();
    }

    public function actulizarPendienteAgrupado($pendiende_lab,$id_prestacion_procedimientos)
    {
      $cad = "UPDATE prestacion_procedimientos_proveedor
            SET pendiente_laboratorio = $pendiende_lab
            where id_prestacion_procedimientos = $id_prestacion_procedimientos
            ";

      DB::update($cad);
      DB::commit();


    }

    /**
     * Obtener Examenes del tipo de atencion indicado segun busqueda
     * 
     * (lab.cpms_personalizado || lab.descripcion_corta) ilike '%{$buscar}%
     * ->leftJoin('proveedores_apoyo_diagnostico as pad', 'pad.codigo_proveedor', '=', 'l.codigo_proveedor')
     */
    public function getLabExamenes($buscar, $nivel, $genero, $tipo, $id_servicio)
    {
        $data = DB::table('labnivelatenciones as l')
                ->join('procedimientos as p', 'l.id_procedimiento', '=', 'p.id')
                ->whereRaw('(l.cpms_personalizado || l.descripcion_corta) ilike ?', ['%' . $buscar. '%'])
                ->where('l.id_tipo_atencion', $tipo)
                ->where('l.estado', 1)
                ->where('l.id_nivel', $nivel)
                ->select([
                    'l.id',
                    'p.descripcion as descripcion_original',
                    'l.descripcion_corta as descripcion',
                    'p.codigo as codigo_original',
                    'l.codigo_proveedor',
                    'l.cpms_personalizado as codigo',
                    'l.id_categoria',
                    'p.id as id_procedimiento'
                ])
                ->orderBy('l.id_categoria')
                ->orderBy('l.descripcion_corta', 'asc')
                ->get();
        return $data;
    }

    function RegistrarPrestacionProcedimientosProveedor($id_prestacion, $id_laboratorio, $pendiente, $pruebas){

        $realizado = false;
        // $id_prestacion_procedimiento_error = 0;
        // DB::beginTransaction();

        // try {
            $prestacion_procedimientos_lab = DB::table("prestacion_procedimientos")
                                            ->where("id_lab_rad", $id_laboratorio)
                                            ->where("tipo_procedimiento", 2)
                                            ->where("id_estado_reg", 1)
                                            ->orderBy("id", "asc")
                                            ->get();

            Log::info("Laboratorio RegistrarPrestacionProcedimientosProveedor 1 - cantidad de prestacion_procedimientos_lab : " . count($prestacion_procedimientos_lab));
            foreach ($prestacion_procedimientos_lab as $pp) {
                Log::info("Laboratorio RegistrarPrestacionProcedimientosProveedor 2 - pp->id : " . $pp->id);
                $existe = DB::table('prestacion_procedimientos_proveedor')
                            ->where('id_prestacion_procedimientos', $pp->id)
                            ->exists();

                Log::info("Laboratorio RegistrarPrestacionProcedimientosProveedor 3 - ¿existe pp->id? : " . $existe);
                if (!$existe) {

                    Log::info("Laboratorio RegistrarPrestacionProcedimientosProveedor 4 - cantidad de pruebas checkeadas : " . count($pruebas));
                    foreach ($pruebas as $index => $id_nivel_atencion) {

                        Log::info("Laboratorio RegistrarPrestacionProcedimientosProveedor 5 - comparar id_rua de prestacion_procedimientos con id_rua checkeada : pp->id_rua: " . $pp->id_rua . " = id_nivel_atencion: " . $id_nivel_atencion);
                        if ($pp->id_rua == $id_nivel_atencion) {

                            Log::info("Laboratorio RegistrarPrestacionProcedimientosProveedor 5 - id ruas de prestacion_procedimientos es igual a id_rua checkeada : pp->id_rua: " . $pp->id_rua . " = id_nivel_atencion: " . $id_nivel_atencion);
                            $estado_pendiente = $pendiente[$index] == "SI" ? true : false;

                            $datos = DB::table("labnivelatenciones as lna")
                                    ->join("proveedores_apoyo_diagnostico as pad2", "lna.codigo_proveedor", "=", "pad2.codigo_proveedor")
                                    ->where("lna.id", $pp->id_rua)
                                    ->select("pad2.id as id_proveedor", "lna.codigo_lab as codigo_original", "lna.cpms_personalizado")
                                    ->first();
                            
                            Log::info("Laboratorio RegistrarPrestacionProcedimientosProveedor 6 - datos de proveedor segun el id_rua: " . $pp->id_rua . " - cantidad de datos: " . count($datos));

                            $insert_realizado = DB::table('prestacion_procedimientos_proveedor')->insert([
                                'id_prestacion_procedimientos' => $pp->id,
                                'id_proveedor' => $datos->id_proveedor,
                                'cpms_personalizado' => $datos->cpms_personalizado,
                                'created_us' => Auth::user()->id,
                                'pendiente_laboratorio' => $estado_pendiente
                            ]);

                            Log::info("Laboratorio RegistrarPrestacionProcedimientosProveedor 7 - ¿se inserto en prestacion_procedimientos_proveedor? : " . $insert_realizado);

                            if (!$insert_realizado) {
                                $id_prestacion_procedimiento_error = $pp->id;
                                throw new \Exception("Error al registrar los exámenes de laboratorio en 'prestacion_procedimientos_proveedor'.");
                                Log::info("Laboratorio RegistrarPrestacionProcedimientosProveedor 8 - No se inserto");
                            }

                            break; 
                        } else {
                            Log::info("Laboratorio RegistrarPrestacionProcedimientosProveedor 5 - id_rua de prestacion_procedimientos y id_rua checkeada NO SON IGUALES");
                        }
                    }
                }
            }
            // DB::commit();
            $realizado = true;
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     Log::error("Laboratorio Registro Examenes => id_prestacion_procedimientos: " . $id_prestacion_procedimiento_error . 
        //         "\n\nmensaje: " . $e->getMessage()
        //     );
            
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     Log::critical("Laboratorio Registro Examenes => id_prestacion_procedimientos: " . $id_prestacion_procedimiento_error . 
        //         "\n\nmensaje: " . $th->getMessage()
        //     );
            
        // }
        return $realizado;
    }

    public function getOrdenTomaMuestraUsuario($id_usuario, $fecha_ini, $fecha_fin)
    {
        $data = DB::table('laboratorios as l')
                ->select(
                    'l.id',
                    'l.cod_orden_lab',
                    't.nombre as tipo_atencion',
                    'c.nombre as servicio',
                    'l.fecha_hora_atencion',
                    'm.paterno as profesional_paterno',
                    'm.materno as profesional_materno',
                    'm.nombre as profesional_nombre',
                    'a.nro_doc_ident',
                    'a.paterno as paciente_paterno',
                    'a.materno as paciente_materno',
                    'a.nombre as paciente_nombre',
                    'a.sexo',
                    'u.paterno as usuario_paterno',
                    'u.materno as usuario_materno',
                    'u.name as usuario_nombre',
                    'l.fecha_registro_toma_muestra'
                )
                ->join('tipo_atenciones as t', 'l.id_tipo_atencion', '=', 't.id')
                ->join('consultorios as c', 'l.id_servicio', '=', 'c.id')
                ->join('medicos as m', 'l.id_medico', '=', 'm.id')
                ->join('asegurados as a', 'l.id_asegurado', '=', 'a.id')
                ->join('users as u', 'l.id_usuario_tomamuestra', '=', 'u.id')
                ->where('l.id_usuario_tomamuestra', $id_usuario)
                // ->where(DB::raw('cast(l.fecha_registro_toma_muestra as date)'), '>=', $fecha_ini)
                // ->where(DB::raw('cast(l.fecha_registro_toma_muestra as date)'), '<=', $fecha_ini)
                ->orderBy('l.fecha_registro_toma_muestra', 'desc')
                ->get();

        return $data;
    }

    public function ConfirmarTomaMuestraOrdenSinReactivo($observacion, $id_user, $id_laboratorio, $cod_siteds_susalud) {
        $res = "";
        $id_prestacion_procedimiento_error = 0;
        // DB::beginTransaction();
  
        // try {
          $fecha_actual = Carbon::now();
        //   $numero_orden_lab = $fecha_actual->format('y') . $fecha_actual->format('m') . $fecha_actual->format('d') . str_pad($secuencia, 4, '0', STR_PAD_LEFT);
  
          // DB::insert("SELECT sp_laboratorio_confirmar_toma_muestra(?,?,?,?,?,?);", [$id_prestacion, $observacion, $id_user, $secuencia, $id_laboratorio, $cod_siteds_susalud]);
          $laboratorio = Laboratorio::find($id_laboratorio);
        //   $laboratorio->secuencial = $secuencia;
          $laboratorio->observacion_toma_muestra = $observacion;
          $laboratorio->fecha_registro_toma_muestra = $fecha_actual;
          $laboratorio->id_usuario_tomamuestra = $id_user;
          $laboratorio->cod_siteds_susalud = $cod_siteds_susalud;
          $laboratorio->flag_toma_muestra = 1;
        //   $laboratorio->cod_orden_lab = $numero_orden_lab;
            $laboratorio->updated_at_cod_siteds = $fecha_actual;
            $laboratorio->updated_us_cod_siteds = $id_user;
  
          if (!$laboratorio->save()) {
              Log::error("Error al registrar los datos de toma de muestra => id_laboratorio: " . $id_laboratorio);
              throw new \Exception("Error al registrar los datos de toma de muestra => id_laboratorio: " . $id_laboratorio);
          }
  
        //   DB::commit();
          $res = "Y";
        // } catch (\Exception $e) {
        //   DB::rollback();
        //   Log::error("Laboratorio Registro Toma Muestra" . 
        //       "\n\nmensaje: " . $e->getMessage()
        //   );
  
        // } catch (\Throwable $th) {
        //   DB::rollback();
        //   Log::critical("Laboratorio Registro Toma Muestra" . 
        //       "\n\nmensaje: " . $th->getMessage()
        //   );
  
        // }
  
        return $res;
      }
    
    function RegistrarPrestacionProcedimientosProveedor2($array_id_prestacion_procedimiento, $pendiente, $array_id_lab_nivel_atencion, $id_laboratorio, $observacion, $ordenes_id = null){

        $realizado = false;

        $laboratorio_actual = Laboratorio::find($id_laboratorio);
        if ($laboratorio_actual->cantidad_toma_muestra >= 3) {
            Log::warning("Laboratorio - Se alcanzó el número máximo de modificaciones de toma de muestra permitidas, id_laboratorio:" . $id_laboratorio);
            throw new \Exception("Ya se alcanzó el número máximo de modificaciones permitidas para esta Orden de Laboratorio.");
        }

        if ($ordenes_id != null) {
            $ordenes_id = array_unique(
                array_map('intval',
                    array_map('trim', is_string($ordenes_id) ? explode(',', $ordenes_id) : $ordenes_id)
                )
            );
        }

        foreach ($array_id_prestacion_procedimiento as $index => $id_prestacion_procedimiento) {
            
            $existe = DB::table('prestacion_procedimientos_proveedor')
                        ->where('id_prestacion_procedimientos', $id_prestacion_procedimiento)
                        ->exists();

            Log::info("**********");
            Log::info("Laboratorio - id: " . $id_laboratorio);
            Log::info("Laboratorio - Validando id_prestacion_procedimiento :" . $id_prestacion_procedimiento);

            if (!$existe) {
                Log::info("Laboratorio - No existe en prestacion_procedimientos_proveedor");
                $estado_pendiente = $pendiente[$index] == "SI" ? true : false;

                if (!$estado_pendiente) {

                    Log::info("Laboratorio - El examen NO esta pendiente (Ya tienen su muestra).");
                    $prestacion_procedimiento = Prestacion_procedimiento::find($id_prestacion_procedimiento);
                    $prestacion_procedimiento->confirmar_toma = "3";
                    $prestacion_procedimiento->confirmar_toma_created_at = Carbon::now();
                    $prestacion_procedimiento->confirmar_toma_created_us = Auth::user()->id;
                    // $prestacion_procedimiento->migrado_lab = true;

                    if (!$prestacion_procedimiento->save()) {
                        Log::error("Laboratorio - No registró los datos de confirmación de la toma de muestra");
                        throw new \Exception("Error al registrar los datos de confirmación de la toma de muestra.");
                    } else {
                        Log::info("Laboratorio - Se registró los datos de confirmación de la toma de muestra");

                        if ($ordenes_id != null) {
                            $cpms = DB::table('prestacion_procedimientos as pp')
                                        ->join('labnivelatenciones as lna', 'pp.id_rua', '=', 'lna.id')
                                        ->where('pp.id', '=', $id_prestacion_procedimiento)
                                        ->value('lna.cpms_personalizado');
                            
                            Log::info("Se busco id_prestacion_procedimiento {$id_prestacion_procedimiento} / Valor de variable cpms: " . json_encode($cpms));
                            Log::info("Data de variable ordenes_id: " . json_encode($ordenes_id));

                            if ($cpms != null) {
                                $pp = Prestacion_procedimiento::from('prestacion_procedimientos as pp')
                                    ->join('labnivelatenciones as lna', 'pp.id_rua', '=', 'lna.id')
                                    ->whereIn('pp.id_lab_rad', $ordenes_id)
                                    ->where('lna.cpms_personalizado', $cpms)
                                    ->where('pp.id_estado_reg', 1)
                                    ->whereNull('pp.confirmar_toma')
                                    ->orderBy('pp.id')
                                    ->lists('id_rua');

                                Log::info("Resultado del select: prestacion_procedimiento buscando por cpms: " . json_encode($pp));
                                if (!$pp->isEmpty()) {
                                    
                                    $afectados = Prestacion_procedimiento::whereIn('id_lab_rad', $ordenes_id)
                                        ->where('tipo_procedimiento', 2)
                                        ->whereIn('id_rua', $pp->toArray())
                                        ->update([
                                            'confirmar_toma'            => '4',
                                            'confirmar_toma_created_at' => Carbon::now(),
                                            'confirmar_toma_created_us' => Auth::user()->id,
                                        ]);

                                    if ($afectados > 0) {
                                        Log::info("Se actualizaron {$afectados} registros correctamente.");
                                    } else {
                                        Log::warning("No se actualizó ningún registro.");
                                        throw new \Exception("Error al registrar la confirmación de la toma de muestra 4 => id_prestacion_procedimiento: " . $id_prestacion_procedimiento);
                                    }
                                }
                            }
                        }
                    }
                }

                $datos = DB::table("labnivelatenciones as lna")
                        ->join("proveedores_apoyo_diagnostico as pad2", "lna.codigo_proveedor", "=", "pad2.codigo_proveedor")
                        ->where("lna.id", $array_id_lab_nivel_atencion[$index])
                        ->select("pad2.id as id_proveedor", "lna.codigo_lab as codigo_original", "lna.cpms_personalizado")
                        ->first();

                $insert_realizado = DB::table('prestacion_procedimientos_proveedor')->insert([
                    'id_prestacion_procedimientos' => $id_prestacion_procedimiento,
                    'id_proveedor' => $datos->id_proveedor,
                    'cpms_personalizado' => $datos->cpms_personalizado,
                    'created_us' => Auth::user()->id,
                    'pendiente_laboratorio' => $estado_pendiente
                ]);

                if (!$insert_realizado) {
                    Log::error("Laboratorio - Error al registrar los detalles del exámen de laboratorio");
                    throw new \Exception("Error al registrar los detalles del exámen de laboratorio.");
                } else {
                    Log::info("Laboratorio - Se registró los detalles del exámen de laboratorio");
                }

            } else {
                Log::info("Laboratorio - Si existe en prestacion_procedimientos_proveedor");
                $estado_pendiente = $pendiente[$index] == "SI" ? true : false;
                
                if (!$estado_pendiente) {
                    Log::info("Laboratorio - El examen NO esta pendiente (Ya tienen su muestra).");
                    $prestacion_procedimiento = Prestacion_procedimiento::find($id_prestacion_procedimiento);
                    $prestacion_procedimiento->confirmar_toma = "3";
                    $prestacion_procedimiento->confirmar_toma_created_at = Carbon::now();
                    $prestacion_procedimiento->confirmar_toma_created_us = Auth::user()->id;
                    // $prestacion_procedimiento->migrado_lab = true;

                    if (!$prestacion_procedimiento->save()) {
                        throw new \Exception("Error al registrar los datos de confirmación de la toma de muestra.");
                        Log::error("Laboratorio - No registró los datos de confirmación de la toma de muestra");
                    } else {
                        Log::info("Laboratorio - Se registró los datos de confirmación de la toma de muestra");

                        if ($ordenes_id != null) {
                            $cpms = DB::table('prestacion_procedimientos as pp')
                                        ->join('labnivelatenciones as lna', 'pp.id_rua', '=', 'lna.id')
                                        ->where('pp.id', '=', $id_prestacion_procedimiento)
                                        ->value('lna.cpms_personalizado');
                            
                            if ($cpms != null) {
                                $pp = Prestacion_procedimiento::from('prestacion_procedimientos as pp')
                                    ->join('labnivelatenciones as lna', 'pp.id_rua', '=', 'lna.id')
                                    ->whereIn('pp.id_lab_rad', $ordenes_id)
                                    ->where('lna.cpms_personalizado', $cpms)
                                    ->where('pp.id_estado_reg', 1)
                                    ->whereNull('pp.confirmar_toma')
                                    ->orderBy('pp.id')
                                    ->lists('id_rua');

                                Log::info("Resultado del select: prestacion_procedimiento buscando por cpms: " . json_encode($pp));
                                if (!$pp->isEmpty()) {
                                    
                                    $afectados = Prestacion_procedimiento::whereIn('id_lab_rad', $ordenes_id)
                                        ->where('tipo_procedimiento', 2)
                                        ->whereIn('id_rua', $pp->toArray())
                                        ->update([
                                            'confirmar_toma'            => '4',
                                            'confirmar_toma_created_at' => Carbon::now(),
                                            'confirmar_toma_created_us' => Auth::user()->id,
                                        ]);

                                    if ($afectados > 0) {
                                        Log::info("Se actualizaron {$afectados} registros correctamente.");
                                    } else {
                                        Log::warning("No se actualizó ningún registro.");
                                        throw new \Exception("Error al registrar la confirmación de la toma de muestra 4 => id_prestacion_procedimiento: " . $id_prestacion_procedimiento);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    Log::info("Laboratorio - El examen SI esta pendiente (No tienen su muestra).");
                }

                $datos = DB::table("labnivelatenciones as lna")
                        ->join("proveedores_apoyo_diagnostico as pad2", "lna.codigo_proveedor", "=", "pad2.codigo_proveedor")
                        ->where("lna.id", $array_id_lab_nivel_atencion[$index])
                        ->select("pad2.id as id_proveedor", "lna.codigo_lab as codigo_original", "lna.cpms_personalizado")
                        ->first();

                $prestacion_procedimiento_proveedor = PrestacionProcedimientoProveedor::where('id_prestacion_procedimientos', $id_prestacion_procedimiento)->first();

                $prestacion_procedimiento_proveedor->pendiente_laboratorio = $estado_pendiente;
                $prestacion_procedimiento_proveedor->id_proveedor = $datos->id_proveedor;
                $prestacion_procedimiento_proveedor->cpms_personalizado = $datos->cpms_personalizado;
                $prestacion_procedimiento_proveedor->updated_at = Carbon::now();
                $prestacion_procedimiento_proveedor->updated_us = Auth::user()->id;

                if (!$prestacion_procedimiento_proveedor->save()) {
                    Log::error("Laboratorio - Error al actualizar los detalles del exámen de laboratorio");
                    throw new \Exception("Error al actualizar los detalles del exámen de laboratorio.");
                } else {
                    Log::info("Laboratorio - Se actualizo los detalles del exámen de laboratorio");
                }
            }
        }

        $fecha_actual = Carbon::now();
        $id_user = Auth::user()->id;

        $laboratorio = Laboratorio::find($id_laboratorio);
        $laboratorio->obs_lastupdate_tomamuestra = $observacion;
        $laboratorio->fecha_lastupdate_tomamuestra = $fecha_actual;
        $laboratorio->id_usuario_lastupdate_tomamuestra = $id_user;
        $laboratorio->cantidad_toma_muestra = $laboratorio->cantidad_toma_muestra + 1;

        if (!$laboratorio->save()) {
            Log::error("Laboratorio: No se pudo actualizar las fechas de la ultima actualizacion de la Orden, id_laboratorio:" . $id_laboratorio);
            throw new \Exception("No se pudo actualizar la Orden con los detalles de la Modificación.");
        } else {
            Log::info("Laboratorio: Se actualizó las fechas de la ultima actualizacion de la Orden, id_laboratorio:" . $id_laboratorio);

            $historial = new LaboratorioTomaMuestraHistorial();
            $historial->id_laboratorio = $id_laboratorio;
            $historial->observacion = $observacion;
            $historial->created_us = $id_user;
            $historial->created_at = $fecha_actual;

            if (!$historial->save()) {
                Log::error("Laboratorio: No se pudo registrar el historial de modificación de toma de muestra, id_laboratorio:" . $id_laboratorio);
                throw new \Exception("No se pudo registrar el historial de la modificación de toma de muestra.");
            } else {
                Log::info("Laboratorio: Se registró el historial de modificación de toma de muestra, id_laboratorio:" . $id_laboratorio);
            }
        }

        $realizado = true;
        return $realizado;
    }

    public function updateEstadoMigrate2($array_id_prestacion_procedimiento, $pendiente){

        $realizado = false;

        foreach ($array_id_prestacion_procedimiento as $index => $id_prestacion_procedimiento) {
            Log::info("Validando para Actualizar examenes a Estado Migrado. id_prestacion_procedimiento :" . $id_prestacion_procedimiento);

            if ($pendiente[$index] == "NO") {

                $prestacion_procedimiento = Prestacion_procedimiento::find($id_prestacion_procedimiento);
                $prestacion_procedimiento->migrado_lab = true;
                
                if (!$prestacion_procedimiento->save()) {
                    throw new \Exception("Error al actualizar cambiar de estado a Exámen Migrado");
                    Log::error("Error al actualizar cambiar de estado a Exámen Migrado");
                } else {
                    Log::info("Se actualizó estado a Exámen Migrado");
                }

            }
        
        }
        
        $realizado = true;
        return $realizado;
    }

    public function GetDetallePorCodigoOrden($array_codigo_orden)
    {
        $data = DB::table('laboratorios')
            ->whereIn('laboratorios.cod_orden_lab', $array_codigo_orden)
            ->select(
                'laboratorios.cod_orden_lab',
                'laboratorios.observacion_toma_muestra',
                'laboratorios.obs_lastupdate_tomamuestra',
                DB::raw("(
                    select COUNT(pp_c.id)
                    from prestacion_procedimientos pp_c
                    where pp_c.id_lab_rad = laboratorios.id
                        and pp_c.tipo_procedimiento = 2
                        and pp_c.id_estado_reg = 1
                ) as cantidad_total"),
                DB::raw("(
                    SELECT COUNT(pp_c.id)
                    FROM prestacion_procedimientos pp_c
                    LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
                    WHERE pp_c.id_lab_rad = laboratorios.id
                    AND pp_c.tipo_procedimiento = 2
                    AND pp_c.confirmar_toma is null
                    AND ppp.pendiente_laboratorio is null
                ) AS cantidad_sr"),
                DB::raw("(
                    SELECT COUNT(pp_c.id)
                    FROM prestacion_procedimientos pp_c
                    LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
                    WHERE pp_c.id_lab_rad = laboratorios.id
                    AND pp_c.tipo_procedimiento = 2
                    AND (pp_c.confirmar_toma = '0' or pp_c.confirmar_toma = '3')
                    AND ppp.pendiente_laboratorio is false
                        and pp_c.id_estado_reg = 1
                ) as cantidad_enviado"),
                DB::raw("(
                    SELECT COUNT(pp_c.id)
                    FROM prestacion_procedimientos pp_c
                    LEFT JOIN prestacion_procedimientos_proveedor ppp on pp_c.id = ppp.id_prestacion_procedimientos
                    WHERE pp_c.id_lab_rad = laboratorios.id
                    AND pp_c.tipo_procedimiento = 2
                    AND pp_c.confirmar_toma is null
                    AND ppp.pendiente_laboratorio is true
                    AND pp_c.id_estado_reg = 1
                ) as cantidad_pendiente")
            )->get();

        return $data;
    }

    public function GetExamenesClasificadosPorOrden($codigo_orden)
    {
        $data = DB::table('laboratorios as l')
            ->join('prestacion_procedimientos as pp', function($join) {
                $join->on('l.id', '=', 'pp.id_lab_rad')
                    ->where('pp.tipo_procedimiento', '=', 2);
            })
            ->join('labnivelatenciones as lna', 'pp.id_rua', '=', 'lna.id')
            ->leftJoin('prestacion_procedimientos_proveedor as ppp', 'pp.id', '=', 'ppp.id_prestacion_procedimientos')
            ->where('l.cod_orden_lab', $codigo_orden)
            ->where('pp.id_estado_reg', 1)
            ->select(
                'lna.descripcion_corta as descripcion',
                DB::raw("
                    CASE
                        WHEN pp.confirmar_toma IS NULL AND ppp.pendiente_laboratorio IS NULL THEN 'SR'
                        WHEN (pp.confirmar_toma = '0' OR pp.confirmar_toma = '3') AND ppp.pendiente_laboratorio IS FALSE THEN 'E'
                        WHEN pp.confirmar_toma IS NULL AND ppp.pendiente_laboratorio IS TRUE THEN 'P'
                    END AS clasificacion
                ")
            )
            ->get();

        return $data;
    }
}
