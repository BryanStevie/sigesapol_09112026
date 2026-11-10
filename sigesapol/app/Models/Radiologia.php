<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class Radiologia extends Model {

    //
    public function ShowAll() {
        $data = DB::table('radiologias')
                ->select('radiologias.*')
                ->orderBy('id', 'desc')
                ->get();
        return $data;
    }
    
    public function ExisteRadiologia($ruc, $id) {
        $data = Radiologia::where('ruc', '=', $ruc)->first();
        return isset($data) ? true : false;
    }

    public function GetProcedimientosByIdRadiologia($id_prestacion,$id_lab_rad) {
        
        $datos = DB::table('radiologias')
                ->join('prestacion_procedimientos', 'prestacion_procedimientos.id_prestacion', '=', 'radiologias.id_prestacion')
                ->join('procedimientos','prestacion_procedimientos.id_procedimiento','=','procedimientos.id')
                ->select('prestacion_procedimientos.*','procedimientos.descripcion as nombre','procedimientos.codigo')
                ->where('radiologias.id_prestacion', '=', $id_prestacion)                
                ->where('prestacion_procedimientos.id_lab_rad', '=', $id_lab_rad)                
                ->where('procedimientos.codigo_seccion',  '=', '1.4')
                ->orderBy('prestacion_procedimientos.id', 'asc')
                ->get();
        return $datos;
    }

    public function GetRadiologiaByIdPrestacion($id_prestacion) {
        $datos = Radiologia::where('id_prestacion', '=', $id_prestacion)->where('estado', '<>', 0)->where('id_evaluacion', '=', 0)->orderBy('id','asc')->get();

        return $datos;
    }

    /**
     * Obtner las ordenes de Imagenes por Id Interconsulta
     */
    public function GetRadiologiaByIdPrestacionInterconsulta($id_prestacion, $id_interconsulta) {
        $datos = Radiologia::where('id_interconsulta', '=', $id_interconsulta)->where('estado', '<>', 0)->orderBy('id','asc')->get();

        return $datos;
    }

    public function GetRadiologiaByIdPrestacionEvaluacion($id_prestacion,$id_evaluacion) {
        $datos = Radiologia::where('id_prestacion', '=', $id_prestacion)->where('id_evaluacion', '=', $id_evaluacion)->where('estado', '<>', 0)->get();

        return $datos;
    }
    public function GetRadiologiaPrestacion($id_prestacion) {
        $datos = Radiologia::where('id_prestacion', '=', $id_prestacion)->where('estado', '<>', 0)->first();
        return $datos;
    }

    public function GetRadiologiaById($id_rad) {

        $cad = "select * from radiologias
                where id = ".$id_rad; 

        $data = DB::select($cad);
        return $data[0];
    }


    public function GetRadiologiaById_ev($id_rad) {

        $cad = "select r.*,e.fecha_atencion fecha_evolucion from radiologias r inner join evaluaciones e on e.id= r.id_evaluacion where r.id = ".$id_rad;

        $data = DB::select($cad);
        return $data[0];
    }

    

    public function GetCountExamenRadiologiaByFechaDesdeHasta($id_establecimiento, $fechaoDesde = "", $fechaoHasta = "", $dni_beneficiario = "", $nro_prestacion = "", $nro_examen_rad = "",$id_tipo_atencion="") {

            $last_day = date('Y-m-d', strtotime("{$fechaoHasta} + 1 day"));

            $cad = "select count(1) cantidad
                    from radiologias 
                    inner join prestaciones on prestaciones.id=radiologias.id_prestacion
                    inner join asegurados on prestaciones.id_asegurado=asegurados.id
                    where radiologias.estado = 1 and radiologias.fecha_atencion is null and  radiologias.id_establecimiento=" . $id_establecimiento;
            
            if ($dni_beneficiario!=''):
                    $cad .= " and asegurados.nro_doc_ident = '" . $dni_beneficiario . "'";
            endif;

            if ($id_tipo_atencion!=''):
                    $cad .= " and prestaciones.id_tipo_atencion = '" . $id_tipo_atencion . "'";
            endif;

            if ($nro_prestacion!=''):
                $cad .= " and prestaciones.id = " . $nro_prestacion;
            endif;

            if ($nro_examen_rad!=''):
                $cad .= " and radiologias.id = " . $nro_examen_rad;
            endif;

            if ($fechaoDesde != "")
                $cad .= " and radiologias.created_at >= '" . $fechaoDesde . "'";
            
            if ($fechaoHasta != "")
                $cad .= " and radiologias.created_at < '" . $last_day . "'";

        //dd($cad);
            $data = DB::select($cad);
        
        return $data[0]->cantidad;
    }

    public function GetExamenRadiologiaByDni($dni_beneficiario) {
        $cad = "select t2.* , t3.nombre nombre_beneficiario, t3.paterno as paterno_beneficiario,t3.materno as materno_beneficiario, t3.nro_doc_ident, t3.tipo_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t7.nombre establecimiento,t8.nombre tipo_atencion, t6.nombre consultorio,t4.descripcion servicio,t5.nombre as nombre_medico, t5.paterno as paterno_medico, t5.materno as materno_medico, t1.id_establecimiento, t1.id_tipo_atencion
                from prestaciones t1
                inner join radiologias t2 on t2.id_prestacion=t1.id
                inner join asegurados t3 on t1.id_asegurado=t3.id
                inner join medicos t5 on t1.id_medico=t5.id 
                inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id 
                inner join consultorios t6 on t4.id_consultorio=t6.id
                inner join establecimientos t7 on t1.id_establecimiento=t7.id 
                inner join tipo_atenciones t8 on t1.id_tipo_atencion=t8.id
                where t2.estado = 1 and t3.nro_doc_ident = '" . $dni_beneficiario . "'";
        $cad .= " order By t2.id desc";
        $data = DB::select($cad);
        return $data;
    }

    /*public function GetProcedimientosByIdRadiologia($id_prestacion) {
        
        $datos = DB::table('radiologias')
                ->join('prestacion_procedimientos', 'prestacion_procedimientos.id_prestacion', '=', 'radiologias.id_prestacion')
                ->join('procedimientos','prestacion_procedimientos.id_procedimiento','=','procedimientos.id')
                ->select('prestacion_procedimientos.*','procedimientos.descripcion as nombre','procedimientos.codigo')
                ->where('radiologias.id_prestacion', '=', $id_prestacion)                
                ->where('procedimientos.codigo_seccion',  '=', $subseccion)
                ->orderBy('prestacion_procedimientos.id', 'asc')
                ->get();
        return $datos;
    }*/

    public function AllExamenRadiologiaAtendidaByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_prestacion = "", $nro_examen_rad = "") {

        $cad = "select prestaciones.*,asegurados.nombre nombre_beneficiario, asegurados.paterno as paterno_beneficiario,asegurados.materno as materno_beneficiario,asegurados.tipo_doc_ident, asegurados.nro_doc_ident, prestaciones.created_at as fec_creacion, consultorios.nombre consultorio, ruta_radiologia,medicos.nombre as nombre_medico, medicos.paterno as paterno_medico, medicos.materno as materno_medico, radiologias.id as id_lab, medicos.nombre servicio, radiologias.fecha_firma  firma_radiologia, radiologias.*, prestaciones.id as id_prestacion
        from prestaciones
        inner join radiologias on radiologias.id_prestacion=prestaciones.id
        inner join asegurados on prestaciones.id_asegurado=asegurados.id
        inner join sub_consultorios on prestaciones.id_sub_consultorio=sub_consultorios.id
        inner join consultorios on sub_consultorios.id_consultorio=consultorios.id
        inner join medicos on prestaciones.id_medico=medicos.id
        where radiologias.estado = 1 and radiologias.fecha_atencion is not null and radiologias.id_establecimiento=" . $id_establecimiento;
            
        if ($dni_beneficiario!=''):
            $cad .= " and asegurados.nro_doc_ident = '" . $dni_beneficiario . "'";
        endif;

        if ($nro_prestacion!=''):
            $cad .= " and prestaciones.id = " . $nro_prestacion;
        endif;

        if ($nro_examen_rad!=''):
            $cad .= " and radiologias.id = " . $nro_examen_rad;
        endif;

        
        if ($fechaDesde != "") {
            $cad .= " and citas.fecha_hora::date >= '" . $fechaDesde . "'";
        }
        
        if ($fechaHasta != "") {
            $cad .= " and citas.fecha_hora::date < '" . $last_day . "'";
        }

        $cad .= " order By radiologias.id desc";
        
        $data = DB::select($cad);
        return $data;
    }

    public function GetCountExamenRadiologiaAtendidaByFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_prestacion = "", $nro_examen_rad = "") {

            $cad = "select count(1) cantidad
                    from radiologias 
                    inner join prestaciones on prestaciones.id=radiologias.id_prestacion
                    inner join asegurados on prestaciones.id_asegurado=asegurados.id
                    where radiologias.estado = 1 and radiologias.fecha_atencion is not null and  radiologias.id_establecimiento=" . $id_establecimiento;
            
            if ($dni_beneficiario!=''):
                    $cad .= " and asegurados.nro_doc_ident = '" . $dni_beneficiario . "'";
            endif;

            if ($nro_prestacion!=''):
                $cad .= " and prestaciones.id = " . $nro_prestacion;
            endif;

            if ($nro_examen_rad!=''):
                $cad .= " and radiologias.id = " . $nro_examen_rad;
            endif;

            if ($fechaDesde != "")
                $cad .= " and radiologias.created_at >= '" . $fechaDesde . "'";
            
            if ($fechaHasta != "")
                $cad .= " and radiologias.created_at <= '" . $fechaHasta . "'";

        $data = DB::select($cad);
        return $data[0]->cantidad;
    }

    public function GetRadiologiaByUser($id_rad) {

        $cad = "select * from radiologias
                inner join users on users.id = radiologias.id_responsable
                where radiologias.id = ".$id_rad; 

        $data = DB::select($cad);
        return $data[0];
    }

    public function ActualizaIdEvaluacion($id, $id_evaluacion) {

        $datos = Radiologia::where('id', $id)->first();
        $datos->id_evaluacion = $id_evaluacion;
        return $datos->save();
        
        // return true;
    
    }



    public function GetProcedimientosByIdRadiologiaAndIdServicioRadiologia(/*$id_prestacion,$id_lab_rad, $id_servicio_radiologia,*/ $id_prestacion_procedimiento) {
          $cad= "SELECT 
                procedimientos.codigo_seccion,
                procedimientos.tipo_procedimiento,
                prestacion_procedimientos.*,
                radnivelatenciones.descripcion_corta as nombre,
                procedimientos.descripcion as descripcion_larga,
                procedimientos.codigo,
                prestacion_procedimientos.informe_parrafo,
                u1.paterno,
                u1.materno,
                u1.name,
                u1.dni As dni_transcriptor,
                u2.dni AS dni_medico_validacion,
                consultorios.nombre AS servicio_radiologia
                FROM radiologias
                INNER JOIN prestacion_procedimientos ON prestacion_procedimientos.id_prestacion = radiologias.id_prestacion
                AND prestacion_procedimientos.id_lab_rad = radiologias.id
                INNER JOIN radnivelatenciones on radnivelatenciones.id = prestacion_procedimientos.id_rua 
                INNER JOIN procedimientos ON procedimientos.id = radnivelatenciones.id_procedimiento
                inner join categoria_radiologias on categoria_radiologias.id = radnivelatenciones.id_categoria 
                INNER JOIN consultorios ON categoria_radiologias.id_servicio = consultorios.id
                LEFT JOIN users u1 ON u1.id = prestacion_procedimientos.confirmar_toma_created_us
                LEFT JOIN users u2 ON u2.id = prestacion_procedimientos.validated_us
                WHERE 
                prestacion_procedimientos.id = $id_prestacion_procedimiento
                --AND procedimientos.codigo_subseccion     in ('2.1.1','1.4.1','1.4.2','1.4.3','1.4.4','1.4.5','1.4.6','1.4.7','1.4.8','1.6.10', '1.6.11','1.6.12','1.6.13','1.6.20')
                ORDER BY prestacion_procedimientos.id ASC";
                $data = DB::select($cad);
                return $data;
    }

    public function GetActualizaProcedimientosByIdRadiologiaAndIdServicioRadiologia($id_prestacion,$id_lab_rad, $id_servicio_radiologia, $id_prestacion_procedimiento) {
        $cad = "UPDATE 
                prestacion_procedimientos
                set
                =
                u1.name,
                u2.dni AS dni_medico_validacion,
                consultorios.nombre AS servicio_radiologia
                FROM radiologias
                INNER JOIN prestacion_procedimientos ON prestacion_procedimientos.id_prestacion = radiologias.id_prestacion
                    AND prestacion_procedimientos.id_lab_rad = radiologias.id
                INNER JOIN procedimientos ON prestacion_procedimientos.id_procedimiento = procedimientos.id
                INNER JOIN consultorios ON procedimientos.id_servicio_radiologia = consultorios.id
                LEFT JOIN users u1 ON u1.id = prestacion_procedimientos.transcribed_us
                LEFT JOIN users u2 ON u2.id = prestacion_procedimientos.validated_us
                WHERE radiologias.id_prestacion = $id_prestacion
                AND prestacion_procedimientos.id_lab_rad = $id_lab_rad
                AND procedimientos.id_servicio_radiologia = $id_servicio_radiologia
                AND prestacion_procedimientos.id = $id_prestacion_procedimiento
                AND procedimientos.codigo_seccion = '1.4'
                ORDER BY prestacion_procedimientos.id asc";
        DB::update("UPDATE prestacion_procedimientos SET validated_us = ?, validated_at = ?, flag_validated = ? WHERE id = ? ",
                                    [Auth::user()->id, Carbon::now(), '1', $row]);
        $data = DB::select($cad);
        return $data;
    }

    public function ActualizaIdInterconsulta($id, $id_interconsulta) {

        $datos = Radiologia::where('id', $id)->first();
        $datos->id_interconsulta = $id_interconsulta;
        $datos->save();
        
        return true;
    
    }

      public function busca_Siteds_Radiologia($dni_beneficiario,$fecha_atencion)
    {
      $query_string = "SELECT codigo_siteds from radiologias where dni_beneficiario = '{$dni_beneficiario}' and fecha_atencion = '{$fecha_atencion}'";
      
      $data = DB::SELECT($query_string);
      if ($data != null || count($data) > 0){
        return $data[0];
      }else{
        return $data[0]='';
      }; 
    }

    public function validar_siteds_radiologia($cod_siteds)
      {
        $query_string = "SELECT count(id) as cantidad_siteds from radiologias where codigo_siteds = '{$cod_siteds}'";
        $data = DB::SELECT($query_string);
        if ($data != null || count($data) > 0){
          return $data[0];
        }else{
          return $data;
        }; 
      }
    
    public function getTodasCategoriasRadiologias($nivel)
    {

        $cad = "SELECT categoria_radiologias.*, consultorios.nombre AS nombre_consultorio FROM categoria_radiologias INNER JOIN consultorios on consultorios.id = categoria_radiologias.id_servicio WHERE categoria_radiologias.estado = 1 and nivel = ".$nivel." ORDER BY categoria_radiologias.id ASC;";
        
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

    public function getCategoriaNivelRadiologia($p)
    {
        return $this->readFunctionPostgres('sp_obtener_categorias_nivel_radiologia',$p);
    }

    public function ExisteRadCategoriaAtenconNivel($id_categoria,$tipo_atencion,$nivel, $id_servicio) {
        $cad = "select *
              from categoria_radiologia_nivels l
              inner join categoria_radiologias p on l.id_categoria_radiologia = p.id
              where l.id_categoria_radiologia=".$id_categoria." and l.id_atencion=".$tipo_atencion." and l.estado=1 and l.id_nivel=".$nivel;
        if ($tipo_atencion == "1") {
            $cad .= " and l.id_servicio =".$id_servicio;
        }

        $data = DB::select($cad);

        return $data;
    }

    public static function RegistrarCategoriaRadNivelAtencion($id_categoria, $tipo_atencion, $nivel, $orden, $id_user, $id_consultorio) {
        $cad = "Select sp_insert_radiologia_categoria_nivel_atencion(?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_categoria, $tipo_atencion, $nivel, $orden, $id_user, $id_consultorio));
        return $data[0]->sp_insert_radiologia_categoria_nivel_atencion;
    }

    public function getRad($nivel, $tipo_atencion, $categoria=0, $id_consultorio=0){
        $cad = "select l.id, p.descripcion, l.descripcion_corta, p.codigo,l.estado 
              from radnivelatenciones l
              inner join procedimientos p on l.id_procedimiento = p.id
              where l.id_tipo_atencion=".$tipo_atencion." and l.id_nivel=".$nivel;
        $cad .="and l.estado in (0,1)";
        $cad .= " and l.id_consultorio = ".$id_consultorio;
        if($categoria!=0)
            $cad .= " and l.id_categoria=".$categoria;

        $cad .= " Order by l.descripcion_corta asc";
        //dd($cad);
        $data = DB::select($cad);
        return $data;
    }

    public function exam_relacionados_Categ_RAD($id)
    {
        return DB::table('prestacion_procedimientos as pp')
            ->join('radnivelatenciones as r', 'pp.id_rua', '=', 'r.id')
            ->where('r.id', $id)
            ->count();
    }



    public function getRadNivelAtencion($id){
        $cad = "select l.*
              from radnivelatenciones l
              where l.id=".$id;
        $cad .= " Order by l.id desc";
        $data = DB::select($cad);
        //dd($data);
        return $data[0];
    }

    public function getNombreCategoriasRad($id){
        $cad = "select id, nombre
                from categoria_radiologias
                where estado = 1 and id=".$id;
        $data = DB::select($cad);
        return $data[0];
    }

    public function getCategoriaNivelAtencionRad($id){
        $cad = "select a.*
              from categoria_radiologia_nivels a
              where a.id=".$id;
        $cad .= " Order by a.orden asc";
        $data = DB::select($cad);
        return $data[0];
    }

    public function ExisteRadAtencionNivel($id_procedimiento,$tipo_atencion,$nivel,$descripcion,$id_consultorio=0,$id_categoria) {
        $cad = "select *
              from radnivelatenciones l
              inner join procedimientos p on l.id_procedimiento = p.id
              where l.id_procedimiento=".$id_procedimiento." and l.id_tipo_atencion=".$tipo_atencion." and l.estado=1 and l.id_nivel=".$nivel;
        if ($tipo_atencion == 1) {
        $cad .= " and l.id_consultorio =" . $id_consultorio;
        }
        $cad .= " and l.descripcion_corta='".$descripcion."'";
        $cad .= " Order by l.descripcion_corta asc";
        $data = DB::select($cad);
        return $data;
    }

    public static function RegistrarRadNivelAtencion($id_procedimiento, $tipo_atencion, $nivel,$categoria,$descripcion, $id_user, $id_consultorio) {
        $cad = "Select sp_insert_rad_nivel_atencion(?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_procedimiento, $tipo_atencion, $nivel,$categoria,$descripcion, $id_user, $id_consultorio));
        return $data[0]->sp_insert_rad_nivel_atencion;
    }

    public function ActualizaEstado($id, $id_user){

        $db_update = DB::table('radnivelatenciones')
          ->where('id', $id)
          ->update(['estado' => 3, 'id_user_update' => $id_user, 'updated_at' => Carbon::now()]);
        return 1;
    }

    public function getVerRad($nivel, $tipo_atencion,$id_consultorio = 0){
        $cad = "select l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo --, crn.orden
                from radnivelatenciones l
                inner join categoria_radiologias cr on cr.id = l.id_categoria
                inner join procedimientos p on l.id_procedimiento = p.id
                where l.estado= 1 and l.id_tipo_atencion =".$tipo_atencion." and l.id_nivel = ".$nivel;
        if ($tipo_atencion == 1) {
            $cad .= " and l.id_consultorio = ".$id_consultorio;
        }        
        $cad .= " group by l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo --crn.orden,
                  order by l.id_categoria, l.descripcion_corta";

        $data = DB::select($cad);
        return $data;
    }

    public function getNumCategoria($nivel, $tipo_atencion, $id_servicio = 0){
        if ($tipo_atencion == 3) {
            $tipo_atencion = 1;
        }
        $cad = "select count(*) total
              from categoria_radiologia_nivels a
              where a.estado= 1 and a.id_atencion =".$tipo_atencion." and a.id_nivel = ".$nivel;
        if ($tipo_atencion == 1 || $tipo_atencion == 3) {
            $cad .= " and a.id_servicio = ".$id_servicio;
        }  
        $data = DB::select($cad);
        return $data[0];
    }

    public function getRadiologiaRuaConvenio($buscar, $nivel, $genero, $tipo, $id_servicio)
    {

        
        $cad = "select l.id, l.descripcion_corta descripcion, l.codigo_rad codigo, l.id_categoria
                from radnivelatenciones l
                inner join procedimientos p on l.id_procedimiento = p.id
                where l.codigo_rad||l.descripcion_corta ilike '%" . $buscar . "%' and l.id_nivel =" . $nivel . "  and l.estado=1 ";
        if ($id_servicio != 0) {
            $cad .= " and l.id_consultorio = " . $id_servicio;
        }

        $cad .= " Order by l.codigo_rad asc
                    limit 50 ";
                    
        $data = DB::select($cad);
        return $data;
        
    }
    // public function getRadiologiaRuaConvenio_hsp($buscar, $nivel, $genero, $tipo=3, $id_servicio)
    // {

    //     // $cad ="select distinct p.id, p.descripcion , p.codigo , p.id_categoria
    //     //         from procedimientos p 
    //     //         where p.codigo||p.descripcion ilike '%" . $buscar . "%' and p.nivel in(1,2,3)  and p.codigo_seccion ='1.4' and p.estado =1";
    //     // // if ($id_servicio != 0) {
    //     // //     $cad .= " and l.id_consultorio = " . $id_servicio;
    //     // // }
        
    //     // $cad.= "Order by p.codigo asc limit 50 ";
    //     // // dd($cad);
    //     // $data = DB::select($cad);
    //     // return $data;
    //     $cad = "select l.id, l.descripcion_corta descripcion, l.codigo_rad codigo, l.id_categoria
    //     from radnivelatenciones l
    //     inner join procedimientos p on l.id_procedimiento = p.id
    //     where l.codigo_rad||l.descripcion_corta ilike '%" . $buscar . "%' and l.id_nivel =" . $nivel . "  and l.estado=1 ";
    //     // if ($id_servicio != 0) {
    //     //     $cad .= " and l.id_consultorio = " . $id_servicio;
    //     // }

    //     $cad .= " Order by l.codigo_rad asc
    //                 limit 50 ";
                    
    //     $data = DB::select($cad);
    //     return $data;
    // }

    public function getVerRadCheckListPrestacionProcedimiento($nivel, $tipo_atencion, $id_prestacion, $id_radiologia, $id_consultorio = 0){
        Log::info("ID Consultorio", ["id_consultorio" => $id_consultorio]);
        if ($tipo_atencion == 1 ){
            $tipo_atencion = 1;
        }
        $cad = "select l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo, pp.id_rua, pp.confirmar_toma,pp.observacion, pp.lado_radio ,d.codigo as codigo_diag
                from radnivelatenciones l
                inner join categoria_radiologias cr on cr.id = l.id_categoria
                inner join procedimientos p on l.id_procedimiento = p.id
                left join prestacion_procedimientos pp on l.id = pp.id_rua and pp.id_prestacion = ".$id_prestacion." and pp.id_lab_rad = ".$id_radiologia;
        $cad .= "left join diagnosticos d on d.id = pp.id_diagnostico";
        // $cad .= " where l.estado= 1 and l.id_tipo_atencion =".$tipo_atencion." and l.id_nivel = ".$nivel;
        $cad .= " where l.id_tipo_atencion =".$tipo_atencion." and l.id_nivel = ".$nivel . " and l.estado = 1  ";
        // if ($tipo_atencion == 2  ) {
        //     $cad .= " and l.id_consultorio = ".$id_consultorio;
        // }elseif($tipo_atencion == 1 || $tipo_atencion == 3) {
            $cad .= " and pp.id_rua is not null";
        //}       
        $cad .= " group by l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo,pp.lado_radio,d.codigo, pp.id_rua, pp.confirmar_toma,pp.observacion  order by l.id_categoria, l.descripcion_corta";
        Log::info("query getVerRadCheckListPrestacionProcedimiento", ["cad" => $cad]);
        $data = DB::select($cad);
        return $data;
    }

    public function getVerRadCheckListPrestacionProcedimiento_new($nivel, $tipo_atencion, $id_prestacion, $id_radiologia, $id_consultorio = 0,$id_evaluacion = 0, $id_interconsulta = 0){
        Log::info("ID Consultorio", ["id_consultorio" => $id_consultorio]);
        if ($tipo_atencion == 1 ){
            $tipo_atencion = 1;
        }
        $cad = "select l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo, pp.id_rua, pp.confirmar_toma,pp.observacion, pp.lado_radio ,d.codigo as codigo_diag,d.codigo as codigo_diag, d.nombre as nombre_diag,left(td.descripcion, 1) as tipo_diag
                from radnivelatenciones l
                inner join categoria_radiologias cr on cr.id = l.id_categoria
                inner join procedimientos p on l.id_procedimiento = p.id
                left join prestacion_procedimientos pp on l.id = pp.id_rua and pp.id_prestacion = ".$id_prestacion." and pp.id_lab_rad = ".$id_radiologia;
        $cad .= "left join diagnosticos d on d.id = pp.id_diagnostico left join receta_diagnosticos rd on rd.id_diagnostico =d.id and rd.id_prestacion = pp.id_prestacion and rd.estado = 1";
        if($id_evaluacion!= 0){
            $cad.="and rd.id_evaluacion = ".$id_evaluacion ;
        }else{
            $cad.="and rd.id_evaluacion = 0 ";
        }
        if($id_interconsulta!= 0){
            $cad.="and rd.id_interconsulta = ".$id_interconsulta ;
        }else{
            $cad.="and rd.id_interconsulta = 0 ";
        }
        $cad .= "left join tipo_diagnosticos td on rd.id_tipo_diagnostico = td.id where l.id_tipo_atencion = ".$tipo_atencion." and l.id_nivel = ".$nivel . " and l.estado = 1  ";

            $cad .= " and pp.id_rua is not null";    
        $cad .= " group by l.id,l.id_procedimiento, l.descripcion_corta, cr.nombre,  p.codigo,pp.lado_radio,d.codigo, pp.id_rua, pp.confirmar_toma,pp.observacion,d.nombre,td.descripcion  order by l.id_categoria, l.descripcion_corta";
        Log::info("query getVerRadCheckListPrestacionProcedimiento", ["cad" => $cad]);
        //dd($cad);
        $data = DB::select($cad);
        return $data;
    }
    

    public function obtenerPrestacionProcedimientoPorIdPrestacionPorRadiologia($id_prestacion,$id_laboratorio)
    {
        $data = DB::table('prestacion_procedimientos')
                ->select('prestacion_procedimientos.*')
                ->where('tipo_procedimiento','=',3)
                ->where('id_prestacion','=',$id_prestacion)
                ->where('id_lab_rad','=',$id_laboratorio)
                ->orderBy('id', 'desc')
                ->get();
        return $data;
    }

    public function listarServiciosDeImagenes($nivel) {
        $data = DB::table('categoria_radiologias')
                ->select('categoria_radiologias.*')
                ->whereNotNull('categoria_radiologias.id_servicio')
                ->where('categoria_radiologias.nivel',$nivel)
                ->orderBy('id', 'desc')
                ->get();
        return $data;
    }

    public function insertarFlagCitaRadiologia($id_prestacion) {
        $cad = "Select * from sp_insertar_flag_cita_radiologia(?)";
        $data = DB::select($cad, array($id_prestacion));
        return $data;
    }

    public function resgitrarCitaNuevaRadiologiaCE($id_cita)
    {
        $cad = "Select sp_insertar_cita_radiologia_ce(?)";
        $data = DB::select($cad, array($id_cita));
        return $data[0]->sp_insertar_cita_radiologia_ce;
    }

    public function resgitrarCitaNuevaRadiologia($id_cita)
    {
        $cad = "Select sp_insertar_cita_radiologia(?)";
        $data = DB::select($cad, array($id_cita));
        return $data[0]->sp_insertar_cita_radiologia;
    }

    public function obtenerOrdenesDeImagenes1($nro_doc, $establecimiento,$id_servicio_estudio = 0) {
        $cad ="SELECT 
            r.id,
            r.id_evaluacion,
            r.ruta_radiologia,
            date(r.created_at) as created_at,
            p2.id_tipo_atencion,
            pp.id as pres_proce,
            pp.observacion_cita_imagenes_por_pp,
            pp.observ_citmul_at,
            tipo_atenciones.nombre as nombre_tipo_atencion,
            co.nombre as servicio_estudio,
            co2.nombre as especialidad_prestacional,
            null as especialidad_emergencia,
            null as especialidad_hospitalizacion,
            r2.descripcion_corta as nombre_examen,
            r2.codigo_rad,
            m.paterno || ' ' || m.materno || ', ' || m.nombre as medico_prestacional,
            a.paterno || ' ' || a.materno || ', ' || a.nombre as paciente_prestacional,
            a.tipo_doc_ident as tipo_doc,
            a.nro_doc_ident as nro_doc,
            TO_CHAR(r.created_at, 'DD-MM-YYYY') as fecha_atencion, 
            TO_CHAR(r.created_at, 'HH:MI AM') AS hora_atencion,
            e.nombre as establecimiento,
            sc.descripcion as consultorio,
            null as medico_evaluacion,
            null as medico_evolucion,
            null as paciente_evaluacion,
            pp.radiologia_portatil,
            pp.comparativo_radio,
            null::timestamp as fecha_alta_medica1,
            null::timestamp as fecha_alta_medica
        FROM radiologias r
        INNER JOIN prestaciones p2 ON r.id_prestacion = p2.id
        INNER JOIN prestacion_procedimientos pp  ON r.id_prestacion = pp.id_prestacion AND pp.id_lab_rad = r.id  AND pp.tipo_procedimiento = '3'
        INNER JOIN asegurados a ON p2.id_asegurado = a.id
        inner join tipo_atenciones on p2.id_tipo_atencion = tipo_atenciones.id
        inner join radnivelatenciones r2 on r2.id = pp.id_rua 
        inner join categoria_radiologias cr on cr.id = r2.id_categoria 
        inner JOIN consultorios co on co.id = cr.id_servicio
        inner join medicos m on m.id = p2.id_medico
        inner join establecimientos e on e.id = r.id_establecimiento 
        left  join sub_consultorios sc on sc.id = p2.id_sub_consultorio
        left  join consultorios co2 on co2.id = sc.id_consultorio
        WHERE r.estado = 1
        AND r.fecha_atencion IS NULL
        AND pp.flag_cita_imagenes IS NULL
        AND pp.confirmar_toma IS NULL";
        $cad.= " AND r.id_establecimiento = ".$establecimiento;
        $cad.= " AND a.nro_doc_ident = '".$nro_doc."'";
        if ($id_servicio_estudio > 0) {
            $cad.= " AND cr.id_servicio = ".$id_servicio_estudio;
        }
        $cad.= " AND p2.id_tipo_atencion = 1

        UNION ALL

        --EMERGENCIA
        select
            r.id,
            r.id_evaluacion,
            r.ruta_radiologia,
            date(r.created_at) as created_at,
            p2.id_tipo_atencion,
            pp.id as pres_proce,
            pp.observacion_cita_imagenes_por_pp,
            pp.observ_citmul_at,
            tipo_atenciones.nombre as nombre_tipo_atencion,
            co.nombre as servicio_estudio,
            co2.nombre as especialidad_prestacional,
            triajes.descripcion as especialidad_emergencia,
            null as especialidad_hospitalizacion,
            r2.descripcion_corta as nombre_examen,
            r2.codigo_rad,
            m.paterno || ' ' || m.materno || ', ' || m.nombre as medico_prestacional,
            a.paterno || ' ' || a.materno || ', ' || a.nombre as paciente_prestacional,
            a.tipo_doc_ident as tipo_doc,
            a.nro_doc_ident as nro_doc,
            TO_CHAR(r.created_at, 'DD-MM-YYYY') as fecha_atencion, 
            TO_CHAR(r.created_at, 'HH:MI AM') AS hora_atencion,
            e.nombre as establecimiento,
            sc.descripcion as consultorio,
            m2.paterno || ' ' || m2.materno || ', ' || m2.nombre as medico_evaluacion,
            null as medico_evolucion,
            a2.paterno || ' ' || a2.materno || ', ' || a2.nombre as paciente_evaluacion,
            pp.radiologia_portatil,
            pp.comparativo_radio,
            emg.fecha_alta_medica as fecha_alta_medica1,
            null::timestamp as fecha_alta_medica
        FROM radiologias r
        INNER JOIN prestaciones p2 ON r.id_prestacion = p2.id
        INNER JOIN prestacion_procedimientos pp 
        ON r.id_prestacion = pp.id_prestacion 
        AND pp.id_lab_rad = r.id 
        AND pp.tipo_procedimiento = '3'
        INNER JOIN asegurados a ON p2.id_asegurado = a.id
        inner join tipo_atenciones on p2.id_tipo_atencion = tipo_atenciones.id
        inner join radnivelatenciones r2 on r2.id = pp.id_rua 
        inner join categoria_radiologias cr on cr.id = r2.id_categoria 
        inner JOIN consultorios co on co.id = cr.id_servicio
        inner join medicos m on m.id = p2.id_medico
        inner join establecimientos e on e.id = r.id_establecimiento
        left  join emergencias emg on emg.id = p2.id_ext
        left  join prioridades on prioridades.id = emg.id_prioridad
        left  join triajes on triajes.id = emg.id_triaje
        left  join evaluaciones on evaluaciones.id = r.id_evaluacion
        left  join medicos m2 on	m2.id = evaluaciones.id_medico
        left  join asegurados a2 on evaluaciones.id_asegurado = a2.id
        left  join sub_consultorios sc on sc.id = p2.id_sub_consultorio
        left  join consultorios co2 on co2.id = sc.id_consultorio
        WHERE r.estado = 1
        AND r.fecha_atencion IS NULL
        AND pp.flag_cita_imagenes IS NULL
        AND pp.confirmar_toma IS NULL";
        $cad.= " AND r.id_establecimiento = ".$establecimiento;
        $cad.= " AND a.nro_doc_ident = '".$nro_doc."'";
        if ($id_servicio_estudio > 0) {
            $cad.= " AND cr.id_servicio = ".$id_servicio_estudio;
        }
        $cad.= " AND p2.id_tipo_atencion = 2
        AND NOW() BETWEEN r.created_at AND COALESCE(emg.fecha_alta_medica, NOW())

        UNION ALL

        --HOSPITALIZACIÓN
        SELECT 
            r.id,
            r.id_evaluacion,
            r.ruta_radiologia,
            date(r.created_at) as created_at,
            p2.id_tipo_atencion,
            pp.id as pres_proce,
            pp.observacion_cita_imagenes_por_pp,
            pp.observ_citmul_at,
            tipo_atenciones.nombre as nombre_tipo_atencion,
            co.nombre as servicio_estudio,
            null as especialidad_prestacional,
            null as especialidad_emergencia,
            c3h.nombre as especialidad_hospitalizacion,
            r2.descripcion_corta as nombre_examen,
            r2.codigo_rad,
            m.paterno || ' ' || m.materno || ', ' || m.nombre as medico_prestacional,
            a.paterno || ' ' || a.materno || ', ' || a.nombre as paciente_prestacional,
            a.tipo_doc_ident as tipo_doc,
            a.nro_doc_ident as nro_doc,
            TO_CHAR(r.created_at, 'DD-MM-YYYY') as fecha_atencion, 
            TO_CHAR(r.created_at, 'HH:MI AM') AS hora_atencion,
            e.nombre as establecimiento,
            sc.descripcion as consultorio,
            null as medico_evaluacion,
            t5.paterno || ' ' || t5.materno || ', ' || t5.nombre as medico_evolucion,
            null as paciente_evaluacion,
            pp.radiologia_portatil,
            pp.comparativo_radio,
            null as fecha_alta_medica1,
            hospitalizaciones.fecha_alta_medica 
        FROM radiologias r
        INNER JOIN prestaciones p2 ON r.id_prestacion = p2.id
        left join hospitalizaciones on hospitalizaciones.id = p2.id_ext
        INNER JOIN prestacion_procedimientos pp ON r.id_prestacion = pp.id_prestacion AND pp.id_lab_rad = r.id AND pp.tipo_procedimiento = '3'
        INNER JOIN asegurados a ON p2.id_asegurado = a.id
        inner join tipo_atenciones on p2.id_tipo_atencion = tipo_atenciones.id
        inner join radnivelatenciones r2 on r2.id = pp.id_rua 
        inner join categoria_radiologias cr on cr.id = r2.id_categoria 
        inner JOIN consultorios co on co.id = cr.id_servicio
        inner join medicos m on m.id = p2.id_medico
        inner join establecimientos e on e.id = r.id_establecimiento
        left  join sub_consultorios sc on sc.id = p2.id_sub_consultorio
        left  join consultorios co2 on co2.id = sc.id_consultorio
        left join cambio_camas cch on p2.id = cch.id_prestacion and cch.id_evolucion = 0
        left join camas cah on cch.id_cama_atencion = cah.id
        left join cuartos c2h on cah.id_cuarto = c2h.id
        left join asignacion_servicios as2h on c2h.id_asignacion_servicio = as2h.id
        left join consultorios c3h on as2h.id_consultorio = c3h.id 
        left join evaluaciones t13 on t13.id = r.id_evaluacion
        left join medicos t5 on t13.id_medico=t5.id
        WHERE r.estado = 1
        AND r.fecha_atencion IS NULL
        AND pp.flag_cita_imagenes IS NULL
        AND pp.confirmar_toma IS NULL";
        $cad.= " AND r.id_establecimiento = ".$establecimiento;
        $cad.= " AND a.nro_doc_ident = '".$nro_doc."'";
        if ($id_servicio_estudio > 0) {
            $cad.= " AND cr.id_servicio = ".$id_servicio_estudio;
        }
        $cad.= " AND p2.id_tipo_atencion = 3
        AND NOW() BETWEEN r.created_at AND COALESCE(hospitalizaciones.fecha_alta_medica, NOW())

        ORDER BY id ASC;";
        $datos = DB::select($cad);
        return $datos;
    }


    public function obtenerOrdenesDeImagenes($nro_doc, $establecimiento,$id_servicio_estudio = 0) {

        $cad = "SELECT 
                r.id,
                r.id_evaluacion,
                r.ruta_radiologia,
                date(r.created_at) as created_at,
                p2.id_tipo_atencion,
                pp.id as pres_proce,
                pp.observacion_cita_imagenes_por_pp,
                pp.observ_citmul_at,
                --c.comentario,
                --c.updated_at,
                tipo_atenciones.nombre as nombre_tipo_atencion,
                co.nombre as servicio_estudio,
                co2.nombre as especialidad_prestacional,
                triajes.descripcion as especialidad_emergencia,
                c3h.nombre as especialidad_hospitalizacion,
                r2.descripcion_corta as nombre_examen,
                r2.codigo_rad,
                m.paterno || ' ' || m.materno || ', ' || m.nombre as medico_prestacional,
                a.paterno || ' ' || a.materno || ', ' || a.nombre as paciente_prestacional,
                a.tipo_doc_ident as tipo_doc,
                a.nro_doc_ident as nro_doc,
                TO_CHAR(r.created_at, 'DD-MM-YYYY') as fecha_atencion, 
                TO_CHAR(r.created_at, 'HH:MI AM') AS hora_atencion,
                e.nombre as establecimiento,
                sc.descripcion as consultorio,
                m2.paterno || ' ' || m2.materno || ', ' || m2.nombre as medico_evaluacion,
                t5.paterno || ' ' || t5.materno || ', ' || t5.nombre as medico_evolucion,
                a2.paterno || ' ' || a2.materno || ', ' || a2.nombre as paciente_evaluacion,
                pp.radiologia_portatil,
                pp.comparativo_radio,
                emg.fecha_alta_medica,
	            hospitalizaciones.fecha_alta_medica 
                FROM radiologias r 
                INNER JOIN prestacion_procedimientos pp ON r.id_prestacion = pp.id_prestacion AND pp.id_lab_rad = r.id AND pp.tipo_procedimiento = '3'
                --left join citas c on c.id= pp.id_citas_multiples_rad
                --left join citas c on c.id_pp= pp.id
                inner join radnivelatenciones r2 on r2.id = pp.id_rua 
                INNER JOIN procedimientos p ON p.id = r2.id_procedimiento
                inner join categoria_radiologias cr on cr.id = r2.id_categoria 
                inner JOIN consultorios co on co.id = cr.id_servicio
                INNER JOIN prestaciones p2 ON r.id_prestacion = p2.id 
                inner join tipo_atenciones on p2.id_tipo_atencion = tipo_atenciones.id
                INNER JOIN asegurados a ON p2.id_asegurado = a.id
                inner join medicos m on m.id = p2.id_medico 
                inner join establecimientos e on e.id = r.id_establecimiento 
                left  join emergencias emg on emg.id = p2.id_ext
                left  join prioridades on prioridades.id = emg.id_prioridad
                left  join triajes on triajes.id = emg.id_triaje
                left  join evaluaciones on evaluaciones.id = r.id_evaluacion
                left  join medicos m2 on	m2.id = evaluaciones.id_medico
                left  join asegurados a2 on evaluaciones.id_asegurado = a2.id
                left  join sub_consultorios sc on sc.id = p2.id_sub_consultorio
                left  join consultorios co2 on co2.id = sc.id_consultorio
                -----------------------HOSPITALIZACIÖN
                left join hospitalizaciones on hospitalizaciones.id = p2.id_ext
                left join cambio_camas cch on p2.id = cch.id_prestacion and cch.id_evolucion = 0
                left join camas cah on cch.id_cama_atencion = cah.id
                left join cuartos c2h on cah.id_cuarto = c2h.id
                left join asignacion_servicios as2h on c2h.id_asignacion_servicio = as2h.id
                left join consultorios c3h on as2h.id_consultorio = c3h.id 
                left join evaluaciones t13 on t13.id = r.id_evaluacion
                left join medicos t5 on t13.id_medico=t5.id

                WHERE r.estado = 1 
                and r.fecha_atencion is null 
                and pp.flag_cita_imagenes is null 
                and pp.confirmar_toma is null ";

        $cad .= " and r.id_establecimiento=".$establecimiento;
        $cad .= " and a.nro_doc_ident='".$nro_doc."'";
        if ($id_servicio_estudio > 0) {
            $cad .= " and cr.id_servicio = ".$id_servicio_estudio;
        }
        // COMENTADO PARA ORDENES DE AMBULATORIO 19-02-26 A SOLICITUD DEL AREA USUARIA Y AUDITORÍA MÉDICA ========
            // $cad .= " AND (
            //             (
            //                 r.created_at <= DATE '2026-01-26'
            //                 AND r.created_at >= NOW() - INTERVAL '6 months'
            //             )
            //             OR
            //             (
            //                 r.created_at > DATE '2026-01-26'
            //                 AND (
            //                     (
            //                         p2.id_tipo_atencion = 1
            //                         AND r.created_at >= NOW() - INTERVAL '4 months'
            //                     )
            //                     OR
            //                     (
            //                         p2.id_tipo_atencion = 2
            //                         AND NOW() >= r.created_at
            //                         AND NOW() <= COALESCE(emg.fecha_alta_medica, NOW())
            //                     )
            //                     OR
            //                     (
            //                         p2.id_tipo_atencion = 3
            //                         AND NOW() >= r.created_at
            //                         AND NOW() <= COALESCE(hospitalizaciones.fecha_alta_medica, NOW())
            //                     )
            //                 )
            //             )
            //         )
            //         ORDER BY r.id ASC";
        //=================================================================================================================
        $cad .= " AND (
                        p2.id_tipo_atencion = 1

                        OR

                        (
                            p2.id_tipo_atencion = 2
                            AND NOW() >= r.created_at
                            AND NOW() <= COALESCE(emg.fecha_alta_medica, NOW())
                        )

                        OR

                        (
                            p2.id_tipo_atencion = 3
                            AND NOW() >= r.created_at
                            AND NOW() <= COALESCE(hospitalizaciones.fecha_alta_medica, NOW())
                        )
                )
                ORDER BY r.id ASC";

        $datos = DB::select($cad);
        return $datos;
    }

    public function resgitrarPresProEnCitaRadiologia($id_cita_radiologia,$id_pp)
    {
        $res = false;

        $db_update = DB::table('citas')
          ->where('id', $id_cita_radiologia)
          ->update(['id_pp' => $id_pp]);

            if ($db_update) {
                Log::info('Actualizaci�n exitosa en la tabla citas.', [
                    'id_cita_radiologia' => $id_cita_radiologia,
                    'id_pp' => $id_pp,
                    'filas_afectadas' => $db_update
                ]);
            } else {
                Log::warning('No se realiz� ninguna actualizaci�n en la tabla citas.', [
                    'id_cita_radiologia' => $id_cita_radiologia,
                    'id_pp' => $id_pp
                ]);
                $res = true;
            }
        
        // $db_update_flg = DB::table('prestacion_procedimientos')
        // ->where('id', $id_pp)
        // ->update([
        //     'flag_cita_imagenes' => 1,
        //     'id_citas_multiples_rad' => $id_cita_radiologia
        // ]);

        //     if ($db_update_flg) {
        //         Log::info('Actualizaci�n exitosa en la tabla prestacion_procedimientos.', [
        //             'id_pp' => $id_pp,
        //             'flag_cita_imagenes' => 1,
        //             'id_citas_multiples_rad' => $id_cita_radiologia,
        //             'filas_afectadas' => $db_update_flg
        //         ]);
        //     } else {
        //         Log::warning('No se realiz� ninguna actualizaci�n en la tabla prestacion_procedimientos.', [
        //             'id_pp' => $id_pp,
        //             'flag_cita_imagenes' => 1,
        //             'id_citas_multiples_rad' => $id_cita_radiologia
        //         ]);
        //         $res = true;
        //     }
        try {
            $registro = DB::table('prestacion_procedimientos')->where('id', $id_pp)->first();

            if (!$registro) {
                return response()->json([
                    'success' => false,
                    'message' => 'El registro con el ID proporcionado no existe.'
                ], 404);
            }

            $db_update_flg = DB::table('prestacion_procedimientos')
                ->where('id', $id_pp)
                ->update([
                    'flag_cita_imagenes' => 1,
                    'id_citas_multiples_rad' => $id_cita_radiologia,
                    //'observacion_cita_imagenes_por_pp' => null,
                    'observ_citmul_us' => null,
                    'observ_citmul_at' => null,
                    'flag_observ_citmul' => 1,
                ]);

            if ($db_update_flg) {
                Log::info('Actualización exitosa en la tabla prestacion_procedimientos.', [
                    'id_pp' => $id_pp,
                    'flag_cita_imagenes' => 1,
                    'id_citas_multiples_rad' => $id_cita_radiologia,
                    //'observacion_cita_imagenes_por_pp' => null,
                    'observ_citmul_us' => null,
                    'observ_citmul_at' => null,
                    'flag_observ_citmul' => null,
                    'filas_afectadas' => $db_update_flg
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Actualización exitosa.',
                    'affected_rows' => $db_update_flg
                ]);
            } else {
                Log::warning('No se realizó ninguna actualización en la tabla prestacion_procedimientos.', [
                    'id_pp' => $id_pp,
                    'flag_cita_imagenes' => 1,
                    'id_citas_multiples_rad' => $id_cita_radiologia,
                    //'observacion_cita_imagenes_por_pp' => null,
                    'observ_citmul_us' => null,
                    'observ_citmul_at' => null,
                    'flag_observ_citmul' => null
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'No se realizó ninguna actualización (posiblemente los datos ya estaban actualizados).'
                ]);
            }
        } catch (Exception $e) {
            Log::error('Error al actualizar la tabla prestacion_procedimientos.', [
                'id_pp' => $id_pp,
                'exception_message' => $e->getMessage(),
                'exception_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado al actualizar los datos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function GetCitasByDni($dni_beneficiario)
    {

        $cad = "select 
        t1.id,r.id id_rad,prestacion_procedimientos.id id_presproc,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,
        t2.nro_doc_ident,to_char(t1.fecha_hora, 'YYYY-MM-DD') dia,to_char(t1.fecha_hora, 'HH:MI AM') hora,t3.id as id_establecimiento,t3.nombre establecimiento, t6.id AS id_servicio, t6.nombre servicio,r.ruta_radiologia,prestacion_procedimientos.ruta_prestacion_procedimiento,
        t4.descripcion consultorio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,cr.id_servicio as id_servicio_radiologia,cr.nombre as nom_servicio,co.nombre as servicio,
        t1.id_estado_cita,t1.id_estado_reg, tc.descripcion as tipo_cita, t1.id_pp, prestacion_procedimientos.confirmar_toma_created_at
        ,
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
        t1.comentario,
        ud1.paterno || ' ' || ud1.materno || ', ' || ud1.name as created_nombre_digitador_radiologia, 
        ud1.dni as created_nrodoc_digitador_radiologia, 
        ud2.paterno || ' ' || ud2.materno || ', ' || ud2.name as updated_nombre_digitador_radiologia, 
        ud2.dni as updated_nrodoc_digitador_radiologia, 
        uv1.paterno || ' ' || uv1.materno || ', ' || uv1.name as created_nombre_registrador_informe, 
        uv1.dni as created_nrodoc_registrador_informe, 
        uv2.paterno || ' ' || uv2.materno || ', ' || uv2.name as updated_nombre_registrador_informe,
        uv2.dni as updated_nrodoc_registrador_informe,
        radnivelatenciones.descripcion_corta,
        radnivelatenciones.codigo_rad

        FROM citas t1
        inner join asegurados t2 on t1.id_asegurado = t2.id 
        inner join establecimientos t3 on t1.id_establecimiento=t3.id
        inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
        inner join consultorios t6 on t4.id_consultorio=t6.id
        left join medicos t5 on t1.id_medico=t5.id
        left join tipo_cita tc on tc.id = t1.id_tipo_cita 
        --left join prestacion_procedimientos on prestacion_procedimientos.id = t1.id_pp
        left join prestacion_procedimientos on prestacion_procedimientos.id_citas_multiples_rad = t1.id 
        left join radiologias r  on prestacion_procedimientos.id_lab_rad  = r.id
        left join users ud1 on ud1.id = prestacion_procedimientos.created_us_digitador_radiologia
        left join users ud2 on ud2.id = prestacion_procedimientos.updated_us_digitador_radiologia 
        left join users uv1 on uv1.id = prestacion_procedimientos.created_us_registro_informe
        left join users uv2 on uv2.id = prestacion_procedimientos.updated_us_registro_informe 
        left join radnivelatenciones on radnivelatenciones.id = prestacion_procedimientos.id_rua
        left join categoria_radiologias cr ON  cr.id = radnivelatenciones.id_categoria
        left join consultorios co ON  co.id = cr.id_servicio
        WHERE t2.nro_doc_ident='" . $dni_beneficiario . "' 
        AND t6.id in (114,76) 
        ORDER BY t1.fecha_hora DESC";
        //dd($cad);
        $data = DB::select($cad);
        return $data;
    }


    public function validacionBeneficiarioDiasPorExamenRad($dni_beneficiario, $id_examen_rad)
    {
        $cad = "select pp.id, pp.id_prestacion, pp.id_rua,
        radnivelatenciones.codigo_rad,
        a.nombre as nombre_beneficiario,
        a.paterno as paterno_beneficiario,
        a.materno as materno_beneficiario,
        a.nro_doc_ident as dni,radnivelatenciones.descripcion_corta,
        p2.descripcion,
        to_char(radiologias.created_at, 'DD/MM/YYYY') as fecha_expedicion,
        DATE_PART('"."day"."', NOW() - radiologias.created_at) AS dias_transcurridos,
        COUNT(*) OVER() as total_registros,
        m.nombre as medico_nombre,
        m.paterno as medico_paterno,
        m.materno as medico_materno,
        c2.nombre as nombre_servicio,
        t.descripcion as nombre_servicio_emergencia,
        pp.id_evaluacion,
        ta.nombre as tipo_atencion_nombre,
        p.id_tipo_atencion,
        pp.confirmar_toma, 
        pp.flag_cita_imagenes, 
        pp.flag_informe, 
        pp.flag_validated,
        radiologias.flag
        from prestacion_procedimientos pp
        inner join prestaciones p on p.id = pp.id_prestacion
        inner join asegurados a on a.id = p.id_asegurado
        inner join radnivelatenciones on radnivelatenciones.id = pp.id_rua
        inner join radiologias on radiologias.id = pp.id_lab_rad
        inner join procedimientos p2 on p2.id = radnivelatenciones.id_procedimiento
        inner join citas c on p.id_cita = c.id and c.id_estado_cita = 4
        left join medicos m on m.id = p.id_medico
        left join sub_consultorios sc on sc.id = p.id_sub_consultorio
        left join consultorios c2 on c2.id = sc.id_consultorio
        left join emergencias e on e.id = p.id_ext
        left join triajes t on t.id = e.id_triaje
        inner join tipo_atenciones ta on ta.id = p.id_tipo_atencion
        WHERE pp.created_at >= (NOW() - INTERVAL '3 months') AND pp.tipo_procedimiento = 3 AND pp.created_at <= NOW()
        ";
        $cad .= " and a.nro_doc_ident = '".$dni_beneficiario."' ";
        $cad .= " and radnivelatenciones.codigo_rad = '".$id_examen_rad."' and pp.confirmar_toma is null";

        try {
            $data = DB::select($cad);
            return $data;
        } catch (\Exception $e) {
            // Manejar el error, por ejemplo, registr�ndolo o devolviendo un mensaje de error.
            return $e->getMessage();
        }
    }
    
    public static function GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRadInformeAdicional($id_radiologia, $id_servicio_radiologia, $id_pp, $id_informe_adicional)
    {
        $cad = "select 
        ira.id as id_informe_adicional,
        t1.id,
        rad.codigo_rad codigo,
        t2.descripcion nombre_completo, 
        rad.descripcion_corta as nombre, 
        t1.cantidad, 
        ira.informe_parrafo, 
        ira.flag_informe, 
        ira.total_informe, 
        t1.lado_radio, 
        t1.observacion, 
        t1.comparativo_radio, 
        ira.tipo_plantilla, 
        t3.codigo diagnostico, 
        t3.nombre nombre_diagnostico, 
        ira.observacion_registro_informe, 
        ira.confirmar_toma, 
        ira.ruta_informe_radiologia_adicional,
        ud1.paterno || ' ' || ud1.materno || ', ' || ud1.name as created_nombre_digitador_radiologia, 
        ud1.dni as created_nrodoc_digitador_radiologia, 
        m1.colegiatura as colegiatura_digitador, 
        ud2.paterno || ' ' || ud2.materno || ', ' || ud2.name as updated_nombre_digitador_radiologia, 
        ud2.dni as updated_nrodoc_digitador_radiologia, 
        uv1.paterno || ' ' || uv1.materno || ', ' || uv1.name as created_nombre_registrador_informe, 
        uv1.dni as created_nrodoc_registrador_informe, 
        uv2.paterno || ' ' || uv2.materno || ', ' || uv2.name as updated_nombre_registrador_informe,
        uv2.dni as updated_nrodoc_registrador_informe,
        uval.paterno || ' ' || uval.materno || ', ' || uval.name as nombre_validador,
        uval.dni as dni_nombre_validador,
        uval1.paterno || ' ' || uval1.materno || ', ' || uval1.name as nombre_validador_adicional,
        uval1.dni as dni_nombre_validador_adicional,
        m2.colegiatura as colegiatura_validador_adicional,
        ira.flag,
        t1.radiologia_portatil
            from prestacion_procedimientos t1
        inner join informes_radiologias_adicionales ira on ira.id_prestacion_procedimiento = t1.id 
        inner join radnivelatenciones rad on rad.id = t1.id_rua
        inner join categoria_radiologias on categoria_radiologias.id = rad.id_categoria
            inner join procedimientos t2 on t1.id_procedimiento=t2.id
        left join diagnosticos t3 on t1.id_diagnostico = t3.id
        left join users ud1 on ud1.id = t1.confirmar_toma_created_us
        left join medicos m1 on ud1.dni= m1.dni and m1.estado =1
        left join users ud2 on ud2.id = t1.confirmar_toma_updated_us 
        left join users uv1 on uv1.id = t1.created_us_registro_informe
        left join users uv2 on uv2.id = t1.updated_us_registro_informe
        left join users uval on uval.id = t1.validated_us
        left join users uval1 on uval1.id = ira.validated_us
        left join medicos m2 on uval1.dni=m2.dni and m2.estado =1
            where t1.id_lab_rad={$id_radiologia} 
            and t1.tipo_procedimiento=3
        and t1.id = {$id_pp} ";
        if ($id_informe_adicional !== 0) {
            # code...
            $cad .= " and ira.id = {$id_informe_adicional} ";
        }
        $cad .= " AND categoria_radiologias.id_servicio={$id_servicio_radiologia}
            and t1.id_estado_reg=1 order by t1.id desc";
        //dd($cad);
        $data = DB::select($cad);
        return $data;
    }

    public function GetProcedimientosByIdRadiologiaAndIdServicioRadiologiaInformeAdicional($id_prestacion,$id_lab_rad, $id_servicio_radiologia, $id_prestacion_procedimiento, $id_informe_adicional) {
        $cad = "SELECT 
                prestacion_procedimientos.*,
                radnivelatenciones.descripcion_corta as nombre,
                procedimientos.descripcion as descripcion_larga,
                procedimientos.codigo,
                ira.informe_parrafo,
                ira.ruta_informe_radiologia_adicional, 
                --u1.paterno,
                --u1.materno,
                --u1.name,
                --u1.dni As dni_transcriptor,
                --u2.dni AS dni_medico_validacion,
                u3.dni As dni_transcriptor_act,
                u4.dni AS dni_medico_validacion_act,
                ira.created_at as fecha_adenda,
                ira.transcribed_us as transcribed_us_adenda,
                ira.fecha_firma as fecha_firma_adenda,
                u3.paterno as paterno_adenda,
                u3.materno as materno_adenda,
                u3.name as name_adenda,
                consultorios.nombre AS servicio_radiologia
                FROM radiologias
                INNER JOIN prestacion_procedimientos ON prestacion_procedimientos.id_prestacion = radiologias.id_prestacion
                    AND prestacion_procedimientos.id_lab_rad = radiologias.id
                INNER JOIN informes_radiologias_adicionales ira on ira.id_prestacion_procedimiento = prestacion_procedimientos.id 
                INNER JOIN radnivelatenciones on radnivelatenciones.id = prestacion_procedimientos.id_rua 
                INNER JOIN procedimientos ON procedimientos.id = radnivelatenciones.id_procedimiento
                inner join categoria_radiologias on categoria_radiologias.id = radnivelatenciones.id_categoria 
                INNER JOIN consultorios ON categoria_radiologias.id_servicio = consultorios.id
                --LEFT JOIN users u1 ON u1.id = prestacion_procedimientos.transcribed_us
                --LEFT JOIN users u2 ON u2.id = prestacion_procedimientos.validated_us
                LEFT JOIN users u3 ON u3.id = ira.transcribed_us
                LEFT JOIN users u4 ON u4.id = ira.validated_us
                WHERE radiologias.id_prestacion = $id_prestacion
                AND prestacion_procedimientos.id_lab_rad = $id_lab_rad
                AND categoria_radiologias.id_servicio = $id_servicio_radiologia
                AND prestacion_procedimientos.id = $id_prestacion_procedimiento
                AND ira.id = $id_informe_adicional
                --AND procedimientos.codigo_seccion = '1.4'
                --AND procedimientos.codigo_subseccion  in ('2.1.1','1.4.1','1.4.2','1.4.3','1.4.4','1.4.5','1.4.6','1.4.7','1.4.8','1.6.10', '1.6.11','1.6.12','1.6.13')
                ORDER BY prestacion_procedimientos.id asc";
                //dd($cad);
        $data = DB::select($cad);
        return $data;
    }

    public function obtenerPresProcePorIdServicioRadiologiaAdicional($id_radiologia, $tipo, $id_servicio_radiologia, $id_pp, $id_informe_adicional)
    {
        $res = DB::table('prestacion_procedimientos')
            ->select(
                'informes_radiologias_adicionales.id as id_informe_adicional',
                'prestacion_procedimientos.id',
                'radnivelatenciones.codigo_rad as codigo',
                'procedimientos.descripcion as nombre_completo',
                'radnivelatenciones.descripcion_corta as nombre',
                'prestacion_procedimientos.cantidad', 
                'informes_radiologias_adicionales.informe_parrafo', 
                'informes_radiologias_adicionales.flag_informe', 
                'informes_radiologias_adicionales.total_informe', 
                'prestacion_procedimientos.lado_radio', 
                'prestacion_procedimientos.observacion', 
                'prestacion_procedimientos.comparativo_radio', 
                'informes_radiologias_adicionales.tipo_plantilla', 
                'prestacion_procedimientos.id_lab_rad as id_radiologia', 
                'diagnosticos.codigo as diagnostico', 
                'diagnosticos.nombre as nombre_diagnostico', 
                'prestacion_procedimientos.observacion_registro_informe',
                'categoria_radiologias.id_servicio as id_servicio_categoria_radiologia'
            )
            ->join('informes_radiologias_adicionales','informes_radiologias_adicionales.id_prestacion_procedimiento','=','prestacion_procedimientos.id')
            ->join('radnivelatenciones','radnivelatenciones.id','=','prestacion_procedimientos.id_rua')
            ->join('categoria_radiologias','categoria_radiologias.id','=','radnivelatenciones.id_categoria')
            ->join('procedimientos','procedimientos.id','=','prestacion_procedimientos.id_procedimiento')
            ->leftJoin('diagnosticos','diagnosticos.id','=','prestacion_procedimientos.id_diagnostico')
            ->where('prestacion_procedimientos.id_lab_rad','=',$id_radiologia)
            ->where('prestacion_procedimientos.tipo_procedimiento','=',$tipo)
            ->where('categoria_radiologias.id_servicio','=',$id_servicio_radiologia)
            ->where('prestacion_procedimientos.id_estado_reg','=',1)
            ->where('prestacion_procedimientos.id','=',$id_pp)
            ->where('informes_radiologias_adicionales.id','=',$id_informe_adicional)
            ->whereIn('informes_radiologias_adicionales.confirmar_toma', ['0', '3'])
            ->orderBy('prestacion_procedimientos.id', 'desc')
            ->get();
        return $res;
    }
    public function obtenerInformesAdicionales($id_pp= "",$dni_beneficiario = null) {
        $cad = "Select * from fn_obtener_informes_adicionales(?,?)";
        $id_pp = ($id_pp === "") ? null : $id_pp;
        $dni_beneficiario = ($dni_beneficiario === "") ? null : $dni_beneficiario;
        $data = DB::select($cad, [$id_pp, $dni_beneficiario]);
        return $data;
    }
    public function AllServicioExamenRadiologiaByEstablecimientoAndFechaDesdeHasta($id_pp = "", $dni_beneficiario = null){
        $cad = "SELECT * FROM fn_servicio_examen_radiologia_por_pp_v3(?, ?)";
        $id_pp = ($id_pp === "") ? null : $id_pp;
        $dni_beneficiario = ($dni_beneficiario === "") ? null : $dni_beneficiario;
        $data = DB::select($cad, [$id_pp, $dni_beneficiario]);
        return $data;
    }

    // public function AllExamenRadiologiaHospitalizacionSinCitaByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaoDesde, $fechaoHasta, $dni_beneficiario,$estado) {
        
    //      $results = DB::select('SELECT * FROM sp_get_radiologias_hospitalizacion_sin_citas(?,?,?,?,?)', [
    //         $id_establecimiento, 
    //         $fechaoDesde, 
    //         $fechaoHasta,
    //         $dni_beneficiario,
    //         $estado]);
    //     return $results;
    // }
    public function AllExamenRadiologiaHospitalizacionSinCitaByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaoDesde, $fechaoHasta, $fechaDesde, $fechaHasta, $fechamDesde, $fechamHasta, $dni_beneficiario, $id_servicio_imagenes, $estado_muestra, $estado_informe) {
        if ($estado_muestra === '0' || $estado_muestra === 0) {
            $estado_muestra = null;
        }
        $params = [$id_establecimiento, $fechaoDesde, $fechaoHasta,$fechaDesde, $fechaHasta, $fechamDesde, $fechamHasta,$dni_beneficiario, $id_servicio_imagenes, $estado_muestra, $estado_informe];
        $params = array_map(function ($value) { return ($value === '' || $value === 'null') ? null : $value; }, $params);
        $results = DB::select('SELECT * FROM sp_get_radiologias_hospitalizacion(?,?,?,?,?,?,?,?,?,?,?)', $params);
        //dd($params);
        return $results;
    }
    // public function AllExamenRadiologiaHospitalizacionByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaoDesde, $fechaoHasta,$dni_beneficiario,$estado,$estado_informe) {
    //     // $cad = "Select sp_get_radiologias_hospitalizacion_citas(?,?,?,?,?,?)";
    //     $results = DB::select('SELECT * FROM sp_get_radiologias_hospitalizacion_citas(?,?,?,?,?,?)', [
    //     $id_establecimiento, 
    //     $fechaoDesde, 
    //     $fechaoHasta,
    //     $dni_beneficiario,
    //     $estado,
    //     $estado_informe]);
    //     //dd($results);
    //     //$data = DB::select($cad, array($id_establecimiento, $fechaoDesde, $fechaoHasta, $dni_beneficiario));
    //     return $results;
    //     //$data[0]->sp_get_radiologias_hospitalizacion_citas;
    // }

    // public function AllExamenRadiologiaAmbulatorioSinCitaByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaoDesde, $fechaoHasta, $dni_beneficiario,$estado) {
        
    //     if ($dni_beneficiario == "") {
    //         $dni_beneficiario = null;
    //     }
    //     $results = DB::select('SELECT * FROM sp_get_radiologias_ambulatorio_sin_citas(?,?,?,?,?)', [
    //         $id_establecimiento, 
    //         $fechaoDesde, 
    //         $fechaoHasta,
    //         $dni_beneficiario,
    //         $estado]);
    //         //dd($results);
    //     return $results;
        
    // }
    public function AllExamenRadiologiaAmbulatorioByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaoDesde, $fechaoHasta, $fechaDesde, $fechaHasta, $fechamDesde, $fechamHasta, $dni_beneficiario, $id_servicio_imagenes, $estado_muestra, $estado_informe) {
        //dd([$id_establecimiento, $fechaoDesde, $fechaoHasta,$dni_beneficiario,$estado,$estado_informe]);
        if ($estado_muestra === '0' || $estado_muestra === 0) {
            $estado_muestra = null;
        }    

        $params = [$id_establecimiento, $fechaoDesde, $fechaoHasta, $fechaDesde, $fechaHasta, $fechamDesde, $fechamHasta, $dni_beneficiario, $id_servicio_imagenes, $estado_muestra, $estado_informe];
        //dd($params);
        $params = array_map(function ($value) {
            return ($value === '' || $value === 'null') ? null : $value;
        }, $params);

        $results = DB::select('SELECT * FROM sp_get_radiologias_ambulatorio_citas(?,?,?,?,?,?,?,?,?,?,?)', $params);

        // dd($params);
        return $results;
        // $results = DB::select('SELECT * FROM sp_get_radiologias_ambulatorio_citas(?,?,?,?,?,?)', [
        // $id_establecimiento, 
        // $fechaoDesde, 
        // $fechaoHasta,
        // $dni_beneficiario,
        // $estado,
        // $estado_informe]);
        // //dd($results);
        // // $cad = "Select sp_get_radiologias_ambulatorio_citas(?,?,?,?,?,?)";
        // //$data = DB::select($cad, array($id_establecimiento, $fechaoDesde, $fechaoHasta, $dni_beneficiario,$estado,$estado_informe));
        //  //dd($results);
        //  return $results;
    }

    public function AllExamenRadiologiaEmergenciaSinCitaByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaoDesde, $fechaoHasta, $fechaDesde, $fechaHasta, $fechamDesde, $fechamHasta, $dni_beneficiario, $id_servicio_imagenes, $estado_muestra, $estado_informe) {

        if ($estado_muestra === '0' || $estado_muestra === 0) {
            $estado_muestra = null;
        }
        $params = [$id_establecimiento, $fechaoDesde, $fechaoHasta,$fechaDesde, $fechaHasta, $fechamDesde, $fechamHasta,$dni_beneficiario, $id_servicio_imagenes, $estado_muestra, $estado_informe];
        $params = array_map(function ($value) { return ($value === '' || $value === 'null') ? null : $value; }, $params);
        $results = DB::select('SELECT * FROM sp_get_radiologias_emergencia(?,?,?,?,?,?,?,?,?,?,?)', $params);
        //dd($params);
        return $results;
    }

    // public function AllExamenRadiologiaByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaoDesde, $fechaoHasta,$dni_beneficiario,$estado,$estado_informe) {
        
    //     $results = DB::select('Select * from  sp_get_radiologias_emergencia_citas(?,?,?,?,?,?)', [
    //     $id_establecimiento, 
    //     $fechaoDesde, 
    //     $fechaoHasta,
    //     $dni_beneficiario,
    //     $estado,
    //     $estado_informe]);
    //     //dd($results);
    //     return $results;
    // }

    

    public function cpmsAnulados($tipo,$id_prestacion)
    {
        $cpms = DB::table('prestacion_procedimientos')
                  ->join('prestaciones', 'prestaciones.id','=', 'prestacion_procedimientos.id_prestacion')
                  ->join('radiologias', 'radiologias.id','=', 'prestacion_procedimientos.id_lab_rad')
                  ->join('users', 'users.id','=', 'radiologias.id_user_examen_invalidado')
                  ->join('radnivelatenciones', 'radnivelatenciones.id','=', 'prestacion_procedimientos.id_rua')
                  ->join('diagnosticos', 'diagnosticos.id','=', 'prestacion_procedimientos.id_diagnostico')
                  ->where('tipo_procedimiento', 3)
                  ->where('radiologias.id_prestacion', $id_prestacion)
                  ->where('radiologias.flag_invalidado', '=',$tipo)
                  ->select('prestacion_procedimientos.id','radnivelatenciones.codigo_rad','radnivelatenciones.descripcion_corta','users.paterno','users.materno','users.name','diagnosticos.codigo as diagnostico','radiologias.fecha_orden_invalidado','radiologias.obs_examen_invalidado','radiologias.id as id_radiologia')
                  ->orderBy('radiologias.id', 'asc')
                  ->get();
        return $cpms;
    }

    public function cpmsAnuladosEvaluaciones($tipo,$id_prestacion,$id_evaluacion)
    {
        $cpms = DB::table('prestacion_procedimientos')
                  ->join('prestaciones', 'prestaciones.id','=', 'prestacion_procedimientos.id_prestacion')
                  ->join('radiologias', 'radiologias.id','=', 'prestacion_procedimientos.id_lab_rad')
                  ->join('users', 'users.id','=', 'radiologias.id_user_examen_invalidado')
                  ->join('radnivelatenciones', 'radnivelatenciones.id','=', 'prestacion_procedimientos.id_rua')
                  ->join('diagnosticos', 'diagnosticos.id','=', 'prestacion_procedimientos.id_diagnostico')
                  ->where('tipo_procedimiento', 3)
                  ->where('radiologias.id_prestacion', $id_prestacion)
                  ->where('radiologias.flag_invalidado', '=',$tipo)
                  ->where('radiologias.id_evaluacion', '=',$id_evaluacion)
                  ->select('prestacion_procedimientos.id','radnivelatenciones.codigo_rad','radnivelatenciones.descripcion_corta','users.paterno','users.materno','users.name','diagnosticos.codigo as diagnostico','radiologias.fecha_orden_invalidado','radiologias.obs_examen_invalidado','radiologias.id as id_radiologia')
                  ->orderBy('radiologias.id', 'asc')
                  ->get();
        return $cpms;
    }

    public function obtenerDataImagenesPorEstudio($p){
        $cad = "select * from sp_obtener_datos_imagenes_por_estudio(?,?,?,?)";
        $data = DB::select($cad, $p);
        return $data;
    }

    public function getTodasCategoriasRadiologiasReportPorEstudio()
    {
        $cad = "SELECT categoria_radiologias.id, consultorios.nombre AS nombre_consultorio FROM categoria_radiologias INNER JOIN consultorios on consultorios.id = categoria_radiologias.id_servicio WHERE categoria_radiologias.estado = 1 ORDER BY categoria_radiologias.id ASC;";
        $data = DB::select($cad);
        return $data;
    }

    public function GetRadiologiaByIdInterconsulta($id_interconsulta) {
        $datos = Radiologia::where('id_interconsulta', '=', $id_interconsulta)->where('estado', '<>', 0)->orderBy('id','asc')->get();

        return $datos;
    }

    public function AllExamenesRadiologiasByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fecha_desde = "", $fecha_hasta = "",$id_tipo_atencion, $dni_beneficiario = "", $id_servicio_imagenes = "",$id_codigo_rad="") {

        $params = [$id_establecimiento, $fecha_desde, $fecha_hasta,$id_tipo_atencion, $dni_beneficiario, $id_servicio_imagenes,$id_codigo_rad];
        $params = array_map(function ($value) { return ($value === '' || $value === 'null') ? null : $value; }, $params);
        //dd($params);
        $results = DB::select('SELECT * FROM fn_listar_radiologias_filtros(?,?,?,?,?,?,?)', $params);
        return $results;
    }

    public function AllExamenRadiologiaAmbulatorioByEstablecimientoAndFechaDesdeHastaTicketCitados($id_establecimiento, $fechaoDesde, $fechaoHasta, $fechaDesde, $fechaHasta, $fechamDesde, $fechamHasta, $dni_beneficiario, $id_servicio_imagenes, $estado_muestra, $estado_informe, $estado_llamado) {
        //dd([$id_establecimiento, $fechaoDesde, $fechaoHasta,$dni_beneficiario,$estado,$estado_informe]);
        if ($estado_muestra === '0' || $estado_muestra === 0) {
            $estado_muestra = null;
        }    

        $params = [$id_establecimiento, $fechaoDesde, $fechaoHasta, $fechaDesde, $fechaHasta, $fechamDesde, $fechamHasta, $dni_beneficiario, $id_servicio_imagenes, $estado_muestra, $estado_informe, $estado_llamado];
        //dd($params);
        $params = array_map(function ($value) {
            return ($value === '' || $value === 'null') ? null : $value;
        }, $params);

        $results = DB::select('SELECT * FROM sp_get_radiologias_ambulatorio_citas_tickets(?,?,?,?,?,?,?,?,?,?,?,?)', $params);

        // dd($params);
        return $results;
        // $results = DB::select('SELECT * FROM sp_get_radiologias_ambulatorio_citas(?,?,?,?,?,?)', [
        // $id_establecimiento, 
        // $fechaoDesde, 
        // $fechaoHasta,
        // $dni_beneficiario,
        // $estado,
        // $estado_informe]);
        // //dd($results);
        // // $cad = "Select sp_get_radiologias_ambulatorio_citas(?,?,?,?,?,?)";
        // //$data = DB::select($cad, array($id_establecimiento, $fechaoDesde, $fechaoHasta, $dni_beneficiario,$estado,$estado_informe));
        //  //dd($results);
        //  return $results;
    }
    
}
