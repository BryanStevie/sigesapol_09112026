<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Log;

class LaboratorioReporte extends Model
{

    //
    public function ShowAll()
    {
        $data = DB::table('laboratorios')
            ->select('laboratorios.*')
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }

    public function ExisteLaboratorio($ruc, $id)
    {
        $data = Laboratorio::where('ruc', '=', $ruc)->first();
        return isset($data) ? true : false;
    }

    public function GetProcedimientosByIdLaboratorio($id_prestacion, $id_examen, $id_lab_rad)
    {

        switch ($id_examen) {
            case 1:
                $subseccion = '1.5.1';
                break;
            case 7:
                $subseccion = '1.5.2';
                break;
            case 4:
                $subseccion = '1.5.3';
                break;
            case 9:
                $subseccion = '1.5.4';
                break;
            case 12:
                $subseccion = '1.5.9';
                break;

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
            ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
            ->select('prestacion_procedimientos.*', 'procedimientos.descripcion as nombre', 'procedimientos.codigo')
            ->where('laboratorios.id_prestacion', '=', $id_prestacion)
            ->where('prestacion_procedimientos.id_lab_rad', '=', $id_lab_rad)
            ->where('procedimientos.codigo_subseccion',  '=', $subseccion)
            ->orderBy('prestacion_procedimientos.id', 'asc')
            ->get();
        return $datos;
    }

    public function GetLaboratorioByIdPrestacion($id_prestacion)
    {
        $cad = "Select * from sp_obtener_laboratorio_por_id_prestacion(?)";
        $data = DB::select($cad, array($id_prestacion));
        return $data;
    }

    public function GetLaboratorioByIdPrestacionEvaluacion($id_prestacion, $id_evaluacion)
    {
        //$datos = Laboratorio::where('id_prestacion', '=', $id_prestacion)->where('id_evaluacion', '=', $id_evaluacion)->where('estado', '<>', 0)->get();
        $datos = DB::table('laboratorios')->where('id_prestacion', '=', $id_prestacion)->where('id_evaluacion', '=', $id_evaluacion)->where('estado', '<>', 0)->get();
        return $datos;
    }
    public function GetLaboratorioPrestacion($id_prestacion)
    {
        $datos = Laboratorio::where('id_prestacion', '=', $id_prestacion)->where('estado', '<>', 0)->first();
        return $datos;
    }

    public function GetLaboratorioById($id_lab)
    {

        $cad = "select * from laboratorios
                where id = " . $id_lab;

        $data = DB::select($cad);
        return $data[0];
    }

    public function AllExamenLaboratorioByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */ $nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia)
    {
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
            if ($estado == "6") { // VALIDAR
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
            if ($estado !== "6") { // VALIDAR
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
            $cad .= " and laboratorios.id_farmacia = " . $id_farmacia . " and laboratorios.estado = 1";
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
        Log::info(["cad" => $cad]);
        $data = DB::select($cad);
        return $data;
    }

    public function GetCountExamenLaboratorioByFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */ $nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia)
    {

        $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

        $cad = "select count(*) cantidad";
        if ($tipo_atencion == "1") {
            if ($estado == "6") { // VALIDAR
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
            if ($estado !== "6") { // VALIDAR
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
            $cad .= " and laboratorios.id_farmacia = " . $id_farmacia . " and laboratorios.estado = 1";
        }

        $data = DB::select($cad);
        return $data[0]->cantidad;
    }

    public function AllMedicosByIpress($id_establecimiento)
    {
        $cad = "Select m.id, upper(m.nombre) nombre, upper(m.paterno) paterno, upper(m.materno) materno
                From medicos m
                Inner Join medico_consultorios mc On m.id = mc.id_medico
                Where mc.id_consultorio = 113 and mc.id_establecimiento =" . $id_establecimiento . "
                Group By m.id, m.nombre, m.paterno, m.materno";
        $data = DB::select($cad);
        return $data;
    }

    public function AllResponsableByIpress($id_establecimiento)
    {
        $cad = "Select u.id, upper(u.name) as name, upper(u.paterno) paterno, upper(u.materno) materno
                From users u
                Where u.tipo_registro = 5 and u.id_establecimiento =" . $id_establecimiento . "
                Group By u.id, u.name, u.paterno, u.materno";
        $data = DB::select($cad);
        return $data;
    }

    public static function RegistrarExamenBioquimica($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $glucosa1, $urea, $creatinina, $colesterol, $trigleceridos, $hdl_colesterol, $ldl_colesterol, $amilasa, $acido_urico, $proteinas_totales, $albumina1, $globulinas, $tgo, $tgp, $bilirrubina_total, $bilirrubina_directa, $bilirrubina_indirecta, $fosfatasa_alcalina)
    {
        $cad = "Select sp_insert_examen_bioquimica(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $glucosa1, $urea, $creatinina, $colesterol, $trigleceridos, $hdl_colesterol, $ldl_colesterol, $amilasa, $acido_urico, $proteinas_totales, $albumina1, $globulinas, $tgo, $tgp, $bilirrubina_total, $bilirrubina_directa, $bilirrubina_indirecta, $fosfatasa_alcalina));
        return $data[0]->sp_insert_examen_bioquimica;
    }

    public static function RegistrarExamenHematologia($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $hematies1, $hemoglobina, $procedimientohematocrito, $procedimientovsg, $procedimientot_coagulacion, $procedimientot_sangria, $procedimientot_protombina, $procedimientoleucocitos1, $procedimientoneutrofilos, $procedimientobasofilos, $procedimientomonocitos, $procedimientolinfocitos, $procedimientoplaquetas, $procedimientomielcitos, $procedimientometamielcitos, $procedimientoabastonados, $procedimientosegmentados)
    {
        $cad = "Select sp_insert_examen_hematologia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $hematies1, $hemoglobina, $procedimientohematocrito, $procedimientovsg, $procedimientot_coagulacion, $procedimientot_sangria, $procedimientot_protombina, $procedimientoleucocitos1, $procedimientoneutrofilos, $procedimientobasofilos, $procedimientomonocitos, $procedimientolinfocitos, $procedimientoplaquetas, $procedimientomielcitos, $procedimientometamielcitos, $procedimientoabastonados, $procedimientosegmentados));
        return $data[0]->sp_insert_examen_hematologia;
    }

    public static function RegistrarExamenMicrobiologia($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $muestra1, $aspecto1, $consistencia, $color1, $lecucitos, $hematies2, $levaduras, $parasitos, $polimorfonucleares, $mononucleares, $observaciones1, $color2, $aspecto2, $densidad, $muestra2, $hematies3, $lecucitos2, $celulas_epiteliales, $cristales, $cilindros, $germenes, $piocitos, $albumina2, $glucosa2, $urobilina, $pigmentos_biliares, $cuerpos_cetonicos, $ac_ascorbico, $nitritos, $thevenon, $observaciones2)
    {
        $cad = "Select sp_insert_examen_microbiologia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $muestra1, $aspecto1, $consistencia, $color1, $lecucitos, $hematies2, $levaduras, $parasitos, $polimorfonucleares, $mononucleares, $observaciones1, $color2, $aspecto2, $densidad, $muestra2, $hematies3, $lecucitos2, $celulas_epiteliales, $cristales, $cilindros, $germenes, $piocitos, $albumina2, $glucosa2, $urobilina, $pigmentos_biliares, $cuerpos_cetonicos, $ac_ascorbico, $nitritos, $thevenon, $observaciones2));
        return $data[0]->sp_insert_examen_microbiologia;
    }

    public static function RegistrarExamenInmunoserologia($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $aglutinaciones_febriles, $tifico_h, $tifico_o, $paratifico_a, $paratifico_b, $brucellas_placa, $vdrl_rpr, $proteina_c_reactiva, $latex_reumatoideo, $antiestreptolisina, $rosa_bangals, $prueba_tubo, $mercaptoetanol, $hbctotal, $havgm, $hbcigm, $aghbs, $hcv, $hiv)
    {
        $cad = "Select sp_insert_examen_inmunoserologia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_laboratorio, $id_ipress, $idresponsable, $idjefelaboratorio, $aglutinaciones_febriles, $tifico_h, $tifico_o, $paratifico_a, $paratifico_b, $brucellas_placa, $vdrl_rpr, $proteina_c_reactiva, $latex_reumatoideo, $antiestreptolisina, $rosa_bangals, $prueba_tubo, $mercaptoetanol, $hbctotal, $havgm, $hbcigm, $aghbs, $hcv, $hiv));
        return $data[0]->sp_insert_examen_inmunoserologia;
    }

    public static function MostrarResultadoExamen($id_lab, $tipo_examen)
    {

        switch ($tipo_examen) {
            case '1':
                $tabla = 'bioquimicas';
                break;
            case '2':
                $tabla = 'marcadores_cardiacos';
                break;
            case '3':
                $tabla = 'pruebas_inmunidad';
                break;
            case '4':
                $tabla = 'inmunoserologias';
                break;
            case '5':
                $tabla = 'marcadores_tumorales';
                break;
            case '6':
                $tabla = 'marcadores_virales';
                break;
            case '7':
                $tabla = 'hematologias';
                break;
            case '8':
                $tabla = 'prueba_coagulacion';
                break;
            case '9':
                $tabla = 'microbiologias';
                break;
            case '10':
                $tabla = 'banco_sangre';
                break;
        }

        $cad = "select * from " . $tabla . "
                where id_laboratorio = " . $id_lab;

        $data = DB::select($cad);

        return $data[0];
    }

    public function GetExamenLaboratorioByDni($p)
    {
        return $this->readFunctionPostgres('sp_obtener_examen_laboratorio_por_dni', $p);
    }


    public function ActualizaIdEvaluacion($id, $id_evaluacion)
    {

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

    public function GetDataParaLIS($id_prestacion, $id_laboratorio, $id_tipo_atencion, $id_evaluacion)
    {

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
        cpms_personalizado varchar
        );";

        $data = DB::select($cad, [$id_prestacion, $id_laboratorio, $id_tipo_atencion, $id_evaluacion]);
        return $data;
    }


    public function ConfirmarTomaMuestra($id_prestacion, $tipo_procedimiento, $prueba, $observacion, $id_user, $secuencia, $id_laboratorio, $cod_siteds_susalud)
    {
        $res = "";
        DB::beginTransaction();
        try {
            DB::insert("SELECT sp_laboratorio_confirmar_toma_muestra(?,?,?,?,?,?);", [$id_prestacion, $observacion, $id_user, $secuencia, $id_laboratorio, $cod_siteds_susalud]);

            foreach ($prueba as $id_rua) {
                //echo $id_rua."<br>";
                $affected2 = DB::update(
                    "UPDATE prestacion_procedimientos SET confirmar_toma = '0' WHERE id_prestacion = ? AND tipo_procedimiento = ? AND id_rua = ? AND id_lab_rad = ?",
                    [$id_prestacion, $tipo_procedimiento, $id_rua, $id_laboratorio]
                );
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

    public function RegistrarResultadosLaboratorioLNS($resultados_lab)
    {
        $res = "";
        DB::beginTransaction();
        try {
            if (count($resultados_lab) == 1) {
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

    /*
    Esta funcion es la copia de "GetProcedimientosByIdLaboratorio" pero sin tomar
    en cuenta el servicio al que pertenece cada prueba, ya sea (Bioquimica, Hematologia, etc)
    */
    public function GetAllProcedimientosByIdLaboratorio($id_prestacion, $id_lab_rad)
    {

        $datos = DB::table('prestacion_procedimientos')
            ->join('labnivelatenciones', 'labnivelatenciones.id', '=', 'prestacion_procedimientos.id_rua')
            ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
            ->leftJoin('diagnosticos', 'diagnosticos.id', '=', 'prestacion_procedimientos.id_diagnostico')
            ->select('procedimientos.id as id_procedimiento', 'labnivelatenciones.descripcion_corta as nombre', 'procedimientos.descripcion as nombre_completo', 'procedimientos.codigo', 'prestacion_procedimientos.confirmar_toma', 'prestacion_procedimientos.cantidad', 'diagnosticos.codigo as relacion_codigo_diagnostico', 'diagnosticos.nombre as relacion_nombre_diagnostico', 'prestacion_procedimientos.observacion', 'prestacion_procedimientos.tipo_muestra_microbiologia', 'prestacion_procedimientos.id_rua')
            ->where('prestacion_procedimientos.id_prestacion', '=', $id_prestacion)
            ->where('prestacion_procedimientos.id_lab_rad', '=', $id_lab_rad)
            ->orderBy('prestacion_procedimientos.id', 'desc')
            ->get();
        return $datos;
    }

    public function ModificarTomaMuestra($id_prestacion, $id_laboratorio, $observacion, $id_user, $array_idrua, $array_estado)
    {
        $res = "";
        DB::beginTransaction();
        try {

            $longitud_cpt = count($array_idrua);

            for ($i = 0; $i < $longitud_cpt; $i++) {

                $estado             = $array_estado[$i];
                $id_rua   = $array_idrua[$i];

                $affected = DB::update(
                    "UPDATE prestacion_procedimientos SET confirmar_toma = ?
                                        WHERE id_prestacion = ? AND id_lab_rad = ? AND id_rua = ? ",
                    [$estado, $id_prestacion, $id_laboratorio, $id_rua]
                );
            }

            $affected2 = DB::update(
                "UPDATE laboratorios SET obs_lastupdate_tomamuestra = ?, fecha_lastupdate_tomamuestra = now(), id_usuario_lastupdate_tomamuestra = ? WHERE id = ? ",
                [$observacion, $id_user, $id_laboratorio]
            );

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

    public function GetDataModificadaParaLIS($id_prestacion, $id_laboratorio, $id_tipo_atencion, $id_evaluacion)
    {

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
          cpms_personalizado varchar
          );";

        $data = DB::select($cad, [$id_prestacion, $id_laboratorio, $id_tipo_atencion, $id_evaluacion]);
        return $data;
    }

    public function getRua($nivel, $tipo_atencion, $categoria = 0, $id_consultorio = 0)
    {
        $cad = "select l.id, p.descripcion, l.descripcion_corta, p.codigo, proveedores_apoyo_diagnostico.nombre as descripcion_proveedor, l.cpms_personalizado
              from labnivelatenciones l
              inner join procedimientos p on l.id_procedimiento = p.id
              left join proveedores_apoyo_diagnostico on proveedores_apoyo_diagnostico.codigo_proveedor = l.codigo_proveedor
              where l.id_tipo_atencion=" . $tipo_atencion . " and l.estado=1  and l.id_nivel=" . $nivel;
        if ($tipo_atencion == 1) {
            $cad .= " and l.id_consultorio = " . $id_consultorio;
        }
        if ($categoria != 0)
            $cad .= " and l.id_categoria=" . $categoria;

        $cad .= " Order by l.descripcion_corta asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ExisteRuaAtenconNivel($id_procedimiento, $tipo_atencion, $nivel, $codigo_personalizado, $id_consultorio = 0, $id_categoria)
    {
        $cad = "select *
              from labnivelatenciones l
              inner join procedimientos p on l.id_procedimiento = p.id
              where l.id_procedimiento=" . $id_procedimiento . " and l.id_tipo_atencion=" . $tipo_atencion . " and l.estado=1 and l.id_nivel=" . $nivel;
        if ($tipo_atencion == 1) {
            $cad .= " and l.id_consultorio =" . $id_consultorio;
        }
        $cad .= " and l.cpms_personalizado ='" . $codigo_personalizado . "'";
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

    public static function RegistrarRuaNivelAtencion($id_procedimiento, $tipo_atencion, $nivel, $categoria, $descripcion, $id_user, $id_consultorio, $id_proveedor, $cpms_personalizado)
    {
        $cad = "Select sp_insert_rua_nivel_atencion(?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_procedimiento, $tipo_atencion, $nivel, $categoria, $descripcion, $id_user, $id_consultorio, $id_proveedor, $cpms_personalizado));
        return $data[0]->sp_insert_rua_nivel_atencion;
    }

    public function ActualizaEstado($id, $id_user)
    {

        $db_update = DB::table('labnivelatenciones')
            ->where('id', $id)
            ->update(['estado' => 0, 'id_user_update' => $id_user]);

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
        return $this->readFunctionPostgres('sp_obtener_categorias_nivel_rua', $p);
    }

    public function ExisteRuaCategoriaAtenconNivel($id_categoria, $tipo_atencion, $nivel, $id_servicio)
    {
        $cad = "select *
              from categoria_rua_nivels l
              inner join categoria_ruas p on l.id_categoria_rua = p.id
              where l.id_categoria_rua=" . $id_categoria . " and l.id_atencion=" . $tipo_atencion . " and l.estado=1 and l.id_nivel=" . $nivel . " and l.id_servicio =" . $id_servicio;

        $data = DB::select($cad);

        return $data;
    }

    public static function RegistrarCategoriaRuaNivelAtencion($id_categoria, $tipo_atencion, $nivel, $orden, $id_user, $id_consultorio)
    {
        $cad = "Select sp_insert_rua_categoria_nivel_atencion(?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_categoria, $tipo_atencion, $nivel, $orden, $id_user, $id_consultorio));
        return $data[0]->sp_insert_rua_categoria_nivel_atencion;
    }

    public function getNombreCategoriasRua($id)
    {
        $cad = "select id, nombre
                from categoria_ruas
                where estado = 1 and id=" . $id;
        $data = DB::select($cad);
        return $data[0];
    }

    public function getVerRuaCategoria($nivel, $tipo_atencion, $categoria)
    {
        $cad = "select l.id, l.id_categoria, p.descripcion, l.descripcion_corta, p.codigo, crn.orden
              from labnivelatenciones l
              inner join procedimientos p on l.id_procedimiento = p.id
              inner join categoria_rua_nivels crn on l.id_categoria = crn.id_categoria_rua
              where l.id_tipo_atencion=" . $tipo_atencion . " and l.estado=1  and l.id_nivel=" . $nivel . " and l.id_categoria=" . $categoria;
        $cad .= " group by l.id, l.id_categoria, p.descripcion, l.descripcion_corta, p.codigo, crn.orden
                  Order by crn.orden,l.id_categoria, l.descripcion_corta asc";

        $data = DB::select($cad);
        return $data;
    }

    public function getVerRuaOrig($nivel, $tipo_atencion)
    {
        $cad = "select l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo --, crn.orden
                from labnivelatenciones l
                inner join categoria_ruas cr on cr.id = l.id_categoria
                --inner join categoria_rua_nivels crn on crn.id_categoria_rua  = cr.id
                inner join procedimientos p on l.id_procedimiento = p.id
                where l.estado= 1 and l.id_tipo_atencion =" . $tipo_atencion . " and l.id_nivel = " . $nivel;
        $cad .= " group by l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo --crn.orden,
                  order by l.id_categoria, l.descripcion_corta --crn.orden, ";

        $data = DB::select($cad);
        return $data;
    }

    public function getVerRua($nivel, $tipo_atencion, $id_consultorio)
    {
        $cad = "select l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo --, crn.orden
                from labnivelatenciones l
                inner join categoria_ruas cr on cr.id = l.id_categoria
                --inner join categoria_rua_nivels crn on crn.id_categoria_rua  = cr.id
                inner join procedimientos p on l.id_procedimiento = p.id
                where l.estado= 1 and l.id_tipo_atencion =" . $tipo_atencion . " and l.id_nivel = " . $nivel . " and l.id_consultorio = " . $id_consultorio;
        $cad .= " group by l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo --crn.orden,
                  order by l.id_categoria, l.descripcion_corta --crn.orden, ";

        $data = DB::select($cad);
        return $data;
    }

    public function getCategoriaNivelAtencionRua($id)
    {
        $cad = "select a.*
              from categoria_rua_nivels a
              where a.id=" . $id;
        $cad .= " Order by a.orden asc";
        $data = DB::select($cad);
        return $data[0];
    }

    public function getNumCategoria($nivel, $tipo_atencion, $id_servicio)
    {
        $cad = "select count(*) total
              from categoria_rua_nivels a
              where a.estado= 1 and a.id_atencion =" . $tipo_atencion . " and a.id_nivel = " . $nivel . " and a.id_servicio=" . $id_servicio;
        $data = DB::select($cad);
        return $data[0];
    }

    public function getNumCategoriaOrig($nivel, $tipo_atencion)
    {
        $cad = "select count(*) total
              from categoria_rua_nivels a
              where a.estado= 1 and a.id_atencion =" . $tipo_atencion . " and a.id_nivel = " . $nivel;
        $data = DB::select($cad);
        return $data[0];
    }

    public function getLaboratorioRuaConvenio($buscar, $nivel, $genero, $tipo, $id_servicio)
    {

        $cad = "select l.id, l.descripcion_corta descripcion, l.codigo_lab codigo, l.id_categoria, l.cpms_personalizado
            from labnivelatenciones l
            inner join procedimientos p on l.id_procedimiento = p.id
            where l.cpms_personalizado||l.descripcion_corta ilike '%" . $buscar . "%' and l.id_tipo_atencion =" . $tipo . " and l.id_nivel =" . $nivel . "  and l.estado=1 ";
        if ($id_servicio != 0) {
            $cad .= " and l.id_consultorio = " . $id_servicio;
        }

        $cad .= " Order by l.codigo_lab asc
                  limit 50 ";

        $data = DB::select($cad);
        return $data;
    }

    public function basico($numero)
    {
        $valor = array(
            'uno',
            'dos',
            'tres',
            'cuatro',
            'cinco',
            'seis',
            'siete',
            'ocho',
            'nueve',
            'diez',
            'once',
            'doce',
            'trece',
            'catorce',
            'quince',
            'dieciseis',
            'diecisiete',
            'dieciocho',
            'diecinueve',
            'veinte',
            'veintiuno ',
            'vientidos ',
            'veintitrés ',
            'veinticuatro',
            'veinticinco',
            'veintiséis',
            'veintisiete',
            'veintiocho',
            'veintinueve'
        );
        return $valor[$numero - 1];
    }

    public function decenas($n)
    {
        $decenas = array(
            30 => 'treinta',
            40 => 'cuarenta',
            50 => 'cincuenta',
            60 => 'sesenta',
            70 => 'setenta',
            80 => 'ochenta',
            90 => 'noventa'
        );
        if ($n <= 29) return $this->basico($n);
        $x = $n % 10;
        if ($x == 0) {
            return $decenas[$n];
        } else return $decenas[$n - $x] . ' y ' . $this->basico($x);
    }
    public function convertir($n)
    {
        switch (true) {
            case ($n >= 1 && $n <= 29):
                return $this->basico($n);
                break;
            case ($n >= 30 && $n < 100):
                return $this->decenas($n);
                break;
                //case ( $n >= 100 && $n < 1000) : return centenas($n); break;
                //case ($n >= 1000 && $n <= 999999): return miles($n); break;
                //case ($n >= 1000000): return millones($n);
        }
    }

    public function getVerRuaConItemPorIdPrestacion($nivel, $tipo_atencion, $id_consultorio, $id_prestacion, $id_laboratorio)
    {
        $cad = "select l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo, pp.id_rua, pp.confirmar_toma
                from labnivelatenciones l
                inner join categoria_ruas cr on cr.id = l.id_categoria
                --inner join categoria_rua_nivels crn on crn.id_categoria_rua  = cr.id
                inner join procedimientos p on l.id_procedimiento = p.id
                left join prestacion_procedimientos pp on l.id = pp.id_rua and pp.id_prestacion =" . $id_prestacion . " and pp.id_lab_rad  =" . $id_laboratorio;
        $cad .= " where l.estado= 1 and l.id_tipo_atencion =" . $tipo_atencion . " and l.id_nivel = " . $nivel . " ";
        if ($tipo_atencion != 2) {
            $cad .=     " and l.id_consultorio = " . $id_consultorio;
        }

        $cad .= " group by l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo, pp.id_rua, pp.confirmar_toma
                  order by l.id_categoria, l.descripcion_corta --crn.orden, ";
        $data = DB::select($cad);
        return $data;
    }

    public function obtenerPrestacionProcedimientoPorIdPrestacion($id_prestacion)
    {
        $data = DB::table('prestacion_procedimientos')
            ->select('prestacion_procedimientos.*')
            ->where('id_prestacion', '=', $id_prestacion)
            ->where('id_rua', '<>', 0)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }

    public function prioridadesEmergencia()
    {
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

    public function resgitrarCitaNuevaLaboratorio($id_cita_origen, $id_cita_nueva)
    {
        $cad = "Select sp_insertar_cita_laboratorio(?,?)";
        $data = DB::select($cad, array($id_cita_origen, $id_cita_nueva));
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
            $data = DB::select($cad, array($dni_beneficiario, $id_examen_lab));
            return $data;
        } catch (\Exception $e) {
            // Manejar el error, por ejemplo, registrándolo o devolviendo un mensaje de error.
            return $e->getMessage();
        }
    }

    public function insertarFlagCitaLaboratorio($id_prestacion)
    {
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

    public function obtenerPrestacionProcedimientoPorIdPrestacionEmergencia($id_prestacion, $id_laboratorio)
    {
        $data = DB::table('prestacion_procedimientos')
            ->select('prestacion_procedimientos.*')
            ->where('id_prestacion', '=', $id_prestacion)
            ->where('id_lab_rad', '=', $id_laboratorio)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }

    public function GetLaboratorioByIdCO($id_laboratorio)
    {
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

    public function AllOrdenExamenLaboratorioByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */ $nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia)
    {
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
            $cad .= " and laboratorios.id_farmacia = " . $id_farmacia . " and laboratorios.estado = 1";
        }

        $cad .= " and laboratorios.estado = 1 order By laboratorios.id desc";
        //dd($cad);
        $data = DB::select($cad);
        return $data;
    }

    public function UpdateEstadoValidadoLabByIdCO($cod_orden_lab)
    {
        $laboratorio = Laboratorio::where('cod_orden_lab', $cod_orden_lab)->firstOrFail();
        $laboratorio->estado_labcore = 1;   /*--1 Validado, 2 firmado--*/
        $laboratorio->save();
    }

    public function AllValidadosExLabByEstaAndFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */ $nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia)
    {
        // dd($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen_lab, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia);
        $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

        $cad = "select prestaciones.*,asegurados.nombre nombre_beneficiario, asegurados.paterno as paterno_beneficiario,asegurados.materno as materno_beneficiario,asegurados.tipo_doc_ident, ta.id id_tipo_atencion,ta.nombre nom_tipo_atencion, u.name as digitador, asegurados.nro_doc_ident, prestaciones.created_at as fec_creacion, consultorios.nombre consultorio, ruta_laboratorio,medicos.nombre as nombre_medico, medicos.paterno as paterno_medico, medicos.materno as materno_medico, laboratorios.id as id_lab, medicos.nombre servicio, laboratorios.fecha_firma  firma_laboratorio, laboratorios.*, prestaciones.id as id_prestacion
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
            $cad .= " and laboratorios.id_farmacia = " . $id_farmacia . " and laboratorios.estado = 1";
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

    public function AllValidadosParaFirmaExLabByEstaAndFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */ $nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado)
    {
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

    public function GetLaboratorioByIdPrestacionInterconsulta($id_prestacion, $id_interconsulta)
    {
        $datos = Laboratorio::where('id_prestacion', '=', $id_prestacion)->where('id_interconsulta', '=', $id_interconsulta)->where('estado', '<>', 0)->get();
        return $datos;
    }

    public function ActualizaIdInterconsulta($id, $id_interconsulta)
    {

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

    public function resgitrarCitaNuevaLaboratorioCE($id_cita_origen_cita_resultado, $id_cita_nueva)
    {
        $cad = "Select sp_insertar_cita_laboratorio_ce(?,?)";
        $data = DB::select($cad, array($id_cita_origen_cita_resultado, $id_cita_nueva));
        return $data[0]->sp_insertar_cita_laboratorio_ce;
    }

    public function limpiarCitaDeLaboratorioParaReprogramar($id_cita)
    {
        $cad = "Select sp_limpiar_cita_laboratorio(?)";
        $data = DB::select($cad, array($id_cita));
        return $data[0]->sp_limpiar_cita_laboratorio;
    }

    public function listarExamenesLaboratorioConDatosPersonalizados2()
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

    public function listarExamenesLaboratorioConDatosPersonalizados()
    {
        return DB::connection('pgsql_reportes')->table('procedimientos as p')
            ->distinct()
            ->select('p.id', 'l.descripcion_corta', 'p.codigo')
            ->join('labnivelatenciones as l', 'p.id', '=', 'l.id_procedimiento')
            ->where('p.tipo_procedimiento', 2)
            ->where('p.estado', 1)
            ->orderBy('l.descripcion_corta')
            ->orderBy('p.codigo')
            ->get();
    }


    public function obtenerProveedoresApoyoAlDiagnostico($id_tipo_procedimiento)
    {
        $data = DB::table('proveedores_apoyo_diagnostico')
            ->select('proveedores_apoyo_diagnostico.*')
            ->where('tipo_procedimiento', '=', $id_tipo_procedimiento)
            ->where('estado', '=', 1)
            ->orderBy('id', 'asc')
            ->get();
        return $data;
    }

    public function obtenerPrestacionProcedimientoPorIdPrestacionConIdLaboratorio($id_prestacion, $id_laboratorio)
    {
        $data = DB::table('prestacion_procedimientos')
            ->select('prestacion_procedimientos.*')
            ->where('tipo_procedimiento', '=', 2)
            ->where('id_prestacion', '=', $id_prestacion)
            ->where('id_lab_rad', '=', $id_laboratorio)
            ->where('id_rua', '<>', 0)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }

    public function GetLaboratorioByIdLaboratorio($id_laboratorio)
    {
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

    public function AllExamenLaboratorioConsultaExternaList($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */ $nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia)
    {
        // dd($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen_lab, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia);
        $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

        $cad = "select prestaciones.*,asegurados.nombre nombre_beneficiario, asegurados.paterno as paterno_beneficiario,asegurados.materno as materno_beneficiario,asegurados.tipo_doc_ident, ta.id id_tipo_atencion,ta.nombre nom_tipo_atencion, u.name as digitador, asegurados.nro_doc_ident, prestaciones.created_at as fec_creacion, consultorios.nombre consultorio, ruta_laboratorio,medicos.nombre as nombre_medico, medicos.paterno as paterno_medico, medicos.materno as materno_medico, laboratorios.id as id_lab, medicos.nombre servicio, laboratorios.fecha_firma  firma_laboratorio, laboratorios.*, prestaciones.id as id_prestacion
        , DATE_PART('day', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) * 24 + DATE_PART('hour', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) as diff_horas_desde_toma_muestra, laboratorios.id_evaluacion, farmacias.nombre as nombre_farmacia,
         CASE
            WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 2 THEN 0
            WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 1 THEN 1
            WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 < 1 THEN 2
        END AS estado_emergencia, to_char(laboratorios.fecha_registro_toma_muestra, 'DD-MM-YYYY HH24:MI') as fecha_registro_toma_muestra";
        if ($estado == "6") {
            $cad .= ", c2.referido, c3.fecha_hora, c2.id_cita_lab, citas.fecha_hora as fecha_hora_cita_laboratorio, citas.id as id_cita_laboratorio_ce";
        } else {
            $cad .= ", c.referido, c2.fecha_hora, c.id_cita_lab, c3.fecha_hora as fecha_hora_cita_laboratorio, c3.id as id_cita_laboratorio_ce";
        }

        if ($estado == "6") { // VALIDAR
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

        if ($estado !== "6") { // VALIDAR
            $cad .= " inner join citas c on c.id = prestaciones.id_cita and c.id_estado_reg = 1";
            $cad .= " left join citas c2 on c2.id = c.id_cita_lab";
            $cad .= " left join citas c3 on c3.id = c.id_cita_laboratorio_ce";
        }

        $cad .= " where laboratorios.id_establecimiento=" . $id_establecimiento;

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
            $cad .= " and consultorios.id = " . $id_servicio;
        endif;

        if ($referido):
            if ($estado == "6") {
                $cad .= " and c2.referido = '" . $referido . "' ";
            } else {
                $cad .= " and c.referido = '" . $referido . "' ";
            }
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
            $cad .= " and citas.estado_asistencia_paciente = 1 ";
        endif;

        if ($fechaDesde != "") {
            //CE debe filtra por la cita programada
            if ($estado == "6") {
                $cad .= " and citas.fecha_hora >= '" . $fechaDesde . "'";
            } else {
                $cad .= " and c3.fecha_hora >= '" . $fechaDesde . "'";
            }
        }
        if ($fechaHasta != "") {
            if ($estado == "6") {
                $cad .= " and citas.fecha_hora < '" . $last_day . "'";
            } else {
                $cad .= " and c3.fecha_hora < '" . $last_day . "'";
            }
        }

        if ($id_farmacia > 0) {
            $cad .= " and laboratorios.id_farmacia = " . $id_farmacia . " and laboratorios.estado = 1";
        }

        $cad .= " order By laboratorios.id desc";
        Log::info(["cad" => $cad]);
        $data = DB::select($cad);
        return $data;
    }

    public function AllExamenLaboratorioEmergenciaList($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */ $nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia)
    {
        // dd($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen_lab, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia);
        $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

        $cad = "select prestaciones.*,asegurados.nombre nombre_beneficiario, asegurados.paterno as paterno_beneficiario,asegurados.materno as materno_beneficiario,asegurados.tipo_doc_ident, ta.id id_tipo_atencion,ta.nombre nom_tipo_atencion, u.name as digitador, asegurados.nro_doc_ident, prestaciones.created_at as fec_creacion, consultorios.nombre consultorio, ruta_laboratorio,medicos.nombre as nombre_medico, medicos.paterno as paterno_medico, medicos.materno as materno_medico, laboratorios.id as id_lab, medicos.nombre servicio, laboratorios.fecha_firma  firma_laboratorio, laboratorios.*, prestaciones.id as id_prestacion
        , DATE_PART('day', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) * 24 + DATE_PART('hour', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) as diff_horas_desde_toma_muestra, laboratorios.id_evaluacion, farmacias.nombre as nombre_farmacia,
         CASE
            WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 2 THEN 0
            WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 1 THEN 1
            WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 < 1 THEN 2
        END AS estado_emergencia, to_char(laboratorios.fecha_registro_toma_muestra, 'DD-MM-YYYY HH24:MI') as fecha_registro_toma_muestra";
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
        $cad .= " and laboratorios.ruta_laboratorio is not null and laboratorios.ruta_laboratorio <> '' ";

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
            $cad .= " and e.id_triaje = " . $id_servicio;
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
            $cad .= " and laboratorios.id_farmacia = " . $id_farmacia . " and laboratorios.estado = 1";
        }

        //Priorizar EMERGENCIA ( UVI y TRAUMATOLOGÏA )
        $cad .= " order By 
                case
                    when t.descripcion = 'UNIDAD VIGILANCIA INTENSIVA' then 1
                    when t.descripcion = 'SHOCK TRAUMA' then 2
                    else 3
                end ";

        Log::info(["cad" => $cad]);
        $data = DB::select($cad);
        return $data;
    }

    public function AllExamenLaboratorioHospitalizacionList($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", /*$nro_prestacion = "", */ $nro_examen_lab = "", $tipo_atencion = "", $id_servicio = "", $referido = "", $prioridad_emergencia = "", $estado, $id_farmacia)
    {
        // dd($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen_lab, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia);
        $last_day = date('Y-m-d', strtotime("{$fechaHasta} + 1 day"));

        $cad = "select prestaciones.*,asegurados.nombre nombre_beneficiario, asegurados.paterno as paterno_beneficiario,asegurados.materno as materno_beneficiario,asegurados.tipo_doc_ident, ta.id id_tipo_atencion,ta.nombre nom_tipo_atencion, u.name as digitador, asegurados.nro_doc_ident, prestaciones.created_at as fec_creacion, consultorios.nombre consultorio, ruta_laboratorio,medicos.nombre as nombre_medico, medicos.paterno as paterno_medico, medicos.materno as materno_medico, laboratorios.id as id_lab, medicos.nombre servicio, laboratorios.fecha_firma  firma_laboratorio, laboratorios.*, prestaciones.id as id_prestacion
        , DATE_PART('day', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) * 24 + DATE_PART('hour', now()::timestamp -  laboratorios.fecha_registro_toma_muestra) as diff_horas_desde_toma_muestra, laboratorios.id_evaluacion, farmacias.nombre as nombre_farmacia,
         CASE
          WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 2 THEN 0
          WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 >= 1 THEN 1
          WHEN EXTRACT(EPOCH FROM (NOW() - TO_TIMESTAMP(laboratorios.fecha_firma, 'DD-MM-YYYY HH24:MI:SS'))) / 3600 < 1 THEN 2
	      END AS estado_emergencia, to_char(laboratorios.fecha_registro_toma_muestra, 'DD-MM-YYYY HH24:MI') as fecha_registro_toma_muestra";
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
            $cad .= " and e.id_triaje = " . $id_servicio;
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
            $cad .= " and laboratorios.id_farmacia = " . $id_farmacia . " and laboratorios.estado = 1";
        }

        //Priorizar EMERGENCIA ( UVI y TRAUMATOLOGÏA )
        /*$cad .= " order By 
                case
                    when t.descripcion = 'UNIDAD VIGILANCIA INTENSIVA' then 1
                    when t.descripcion = 'SHOCK TRAUMA' then 2
                    else 3
                end ";*/

        Log::info(["cad" => $cad]);
        $data = DB::select($cad);
        return $data;
    }

    public function obtenerOrdenesDeLaboratorioCitaOrigen($nro_doc, $id_establecimiento, $id_servicio = 0)
    {

        $cad = "SELECT * FROM sp_obtener_ordenes_laboratorio_cita_origen(?,?,?)";

        $datos = DB::select($cad, array($nro_doc, $id_establecimiento, $id_servicio));
        return $datos;
    }
    public function obtenerOrdenesDeLaboratorioCitaOrigenCitaResultado($nro_doc, $id_establecimiento)
    {

        $cad = "SELECT * FROM sp_obtener_ordenes_laboratorio_cita_origen_cita_resultado(?,?)";

        $datos = DB::select($cad, array($nro_doc, $id_establecimiento));
        return $datos;
    }

    public function obtenerCpmsPorIdLaboratorio($id_laboratorio)
    {
        $res = DB::table('prestacion_procedimientos')
            ->join('labnivelatenciones', 'labnivelatenciones.id', '=', 'prestacion_procedimientos.id_rua')
            ->where('prestacion_procedimientos.tipo_procedimiento', 2)
            ->where('prestacion_procedimientos.id_estado_reg', 1)
            ->where('prestacion_procedimientos.id_lab_rad', $id_laboratorio)
            ->select('labnivelatenciones.codigo_lab', 'labnivelatenciones.descripcion_corta')
            ->get();
        return $res;
    }
}
