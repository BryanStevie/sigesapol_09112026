<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Log;

class Cama extends Model
{
    protected $table = 'camas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'id_establecimiento',
        'id_cuarto',
        'estado',
        'estado_fisico',
        'tipo',
        'situacion',
        'nombre_atencion',
        'id_clase_cama',
        'created_us',
        'updated_us',
    ];
    public $timestamps = false;
 	public function GetCuartoByIdAsignacionServicio($id_asignacion_servicio) {
        
        $cad = "select * from cuartos T1 where id_asignacion_servicio=".$id_asignacion_servicio;
        $data = DB::select($cad);
        return $data;
    }
	
	public function GetCamaByIdConsultorio($id_consultorio) {
        
        $cad = "select T6.id,T6.nombre as nombre_cama,T1.nombre as nombre_cuarto
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio = T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id 
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                where T6.estado=0 and T2.id_consultorio=".$id_consultorio."
                order by T6.id";
        $data = DB::select($cad);
        return $data;
        //181.65.158.197

    }

    public function GetConsultorioByIpress($id_ipress) {
        
        $cad = "select T3.id id_consultorio, T3.nombre
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                where T1.id_establecimiento= ".$id_ipress." And T6.estado=0
                group by T3.id,T3.nombre";
        $data = DB::select($cad);
        return $data;
    }

    /*public function GetConsultorioByIpressCama($id_ipress, $id_cama) {
        
        
        $cad = "select T2.id id_consultorio, T2.nombre
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                where T6.estado=0 And T1.id_establecimiento= ".$id_ipress." 
                UNION 
                select T2.id id_consultorio, T2.nombre
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                where T6.estado=1 And T1.id_establecimiento= ".$id_ipress." and T6.id=".$id_cama."
                group by T2.id,T2.nombre";
        $data = DB::select($cad);
        return $data;
    }
	*/
	public function GetConsultorioByIpressCama($id_ipress, $id_cama) {
        
        // EL T6.2 SE VA MANTENER DE MANERA TEMPORAL PORQUE AUN NO SE SABE LA DESCRIPCION 
        // DE LOS ESTADOS COMO 0 1 , 2 Y 3
        $cad = "select distinct   T2.nombre,T2.id_consultorio
        from camas T6
        inner join cuartos T1 on T6.id_cuarto=T1.id
        inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
        inner join consultorios T3 on T2.id_consultorio=T3.id
        inner join pisos T4 on T2.id_piso=T4.id
        inner join pabellones T5 on T4.id_pabellon=T5.id
                where  T1.id_establecimiento= ".$id_ipress." 
                UNION 
				select distinct   T2.nombre,T2.id_consultorio
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                where T6.estado=1 And T1.id_establecimiento= ".$id_ipress." and T6.id=".$id_cama."
                group by T2.id,T2.nombre";
        $data = DB::select($cad);
        return $data;
    }
	public function ShowallById_Ipress($id_ipress){
		/*
		$cad = "select T6.*,T1.nombre cuarto,T3.nombre consultorio,T3.nombre consultorio,T4.nombre piso,T5.nombre pabellon
from camas T6
inner join cuartos T1 on T6.id_cuarto=T1.id
inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
inner join consultorios T3 on T2.id_consultorio=T3.id
inner join pisos T4 on T2.id_piso=T4.id
inner join pabellones T5 on T4.id_pabellon=T5.id
where T1.id_establecimiento=".$id_ipress;
		$cad .= " And COALESCE(T6.estado,1)!=3  ";
		*/
		$cad = "select T6.*,T1.nombre cuarto,T3.nombre consultorio,T3.nombre consultorio,T4.nombre piso,T5.nombre pabellon, T7.descripcion as desc_estado_fisico, T8.descripcion as desc_tipo, T9.descripcion as desc_situacion
        from camas T6
        inner join cuartos T1 on T6.id_cuarto=T1.id
        inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
        inner join consultorios T3 on T2.id_consultorio=T3.id
        inner join pisos T4 on T2.id_piso=T4.id
        inner join pabellones T5 on T4.id_pabellon=T5.id
        left join estados_sistema T7 on CAST(T6.estado_fisico AS VARCHAR) = T7.estado_tabla and T7.tabla = 'camas->estado_fisico'
        left join tipo_sistemas T8 on T6.tipo = T8.estado_tabla and T8.tabla = 'camas->tipo'
        left join situacion_camas T9 on T6.situacion = cast(T9.id as varchar)
        where T1.id_establecimiento=".$id_ipress." And T6.estado!=3";
		
        $data = DB::select($cad);
        return $data;
		
    }
    public function ShowAllCamasIpress($id_ipress,$id_atencion =0){
        $cad = "select T6.*,T1.nombre cuarto,T3.nombre consultorio,T3.nombre consultorio,T4.nombre piso,T5.nombre pabellon, T7.descripcion as desc_estado_fisico, T8.descripcion as desc_tipo, T9.descripcion as desc_situacion, T10.descripcion prioridad_cama, T8.descripcion tipo_cama
        from camas T6
        inner join cuartos T1 on T6.id_cuarto=T1.id
        inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
        inner join consultorios T3 on T2.id_consultorio=T3.id
        inner join pisos T4 on T2.id_piso=T4.id
        inner join prioridades T10 on T2.id_prioridad=T10.id
        inner join pabellones T5 on T4.id_pabellon=T5.id
        left join estados_sistema T7 on CAST(T6.estado_fisico AS VARCHAR) = T7.estado_tabla and T7.tabla = 'camas->estado_fisico'
        --left join tipo_sistemas T8 on T6.tipo = T8.estado_tabla and T8.tabla = 'camas->tipo'
        left join tipo_camas T8 on T6.tipo = cast(T8.id as varchar)
        left join situacion_camas T9 on T6.situacion = cast(T9.id as varchar)
        where T1.id_establecimiento=".$id_ipress." ";
        if($id_atencion == 1)
            $cad .=" and T1.nombre_atencion='EMERGENCIA'";
        if($id_atencion == 2)
            $cad .=" and T1.nombre_atencion='HOSPITALIZACION'";
        // Log::info($cad);
        $data = DB::select($cad);
        return $data;
        
    }

    public function ShowAllCamasXIpress($id_ipress,$id_atencion =0){
        $cad = "select T6.*,T1.nombre cuarto,T3.nombre consultorio,T3.nombre consultorio,T4.nombre piso,T5.nombre pabellon, T7.descripcion as desc_estado_fisico, T8.descripcion as desc_tipo, T9.descripcion as desc_situacion, T8.descripcion tipo_cama, cc.descripcion clase_cama
        from camas T6
        inner join cuartos T1 on T6.id_cuarto=T1.id
        inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
        inner join consultorios T3 on T2.id_consultorio=T3.id
        inner join pisos T4 on T2.id_piso=T4.id
        
        inner join pabellones T5 on T4.id_pabellon=T5.id
        left join estados_sistema T7 on CAST(T6.estado_fisico AS VARCHAR) = T7.estado_tabla and T7.tabla = 'camas->estado_fisico'
        --left join tipo_sistemas T8 on T6.tipo = T8.estado_tabla and T8.tabla = 'camas->tipo'
        left join tipo_camas T8 on T6.tipo = cast(T8.id as varchar)
        left join situacion_camas T9 on T6.situacion = cast(T9.id as varchar)
        left join clase_camas cc on T6.id_clase_cama = cc.id
        where T1.id_establecimiento=".$id_ipress." and T6.estado in(0,1,2)";
        if($id_atencion == 1)
            $cad .=" and T1.nombre_atencion='EMERGENCIA'";
        if($id_atencion == 2)
            $cad .=" and T1.nombre_atencion='HOSPITALIZACION'";
        $data = DB::select($cad);
        return $data;
        
    }
	
	public function GetCamaById($id) {
        
        $cad = "select T6.*,T1.id_asignacion_servicio,T2.id_piso,T4.id_pabellon
				from camas T6
				inner join cuartos T1 on T6.id_cuarto=T1.id
				inner join asignacion_servicios T2 on T1.id_asignacion_servicio = T2.id
				inner join consultorios T3 on T2.id_consultorio=T3.id 
				inner join pisos T4 on T2.id_piso=T4.id
				inner join pabellones T5 on T4.id_pabellon=T5.id
				where T6.id=".$id." 
				limit 1 ";
        $data = DB::select($cad);
        return $data[0];
    }

    public function ExisteCama($nombre, $id_cuarto, $id_establecimiento, $id=0) {
        $data = Cama::where(DB::raw('upper(nombre)'), '=', trim(strtoupper($nombre)))->where('id_cuarto', '=', $id_cuarto)->where('id_establecimiento', '=', $id_establecimiento)->whereNull('estado')->where('id', '!=', $id)->first();
        return isset($data) ? true : false;
    }

    
    public function actualizaEstado($id,$estado,$id_establecimiento=0,$id_situacion=""){
        $update=Cama::where('id','=',$id)->first();
        $update->estado=$estado;

        // Solo al Hospital LNS se le modifica la situación, hasta nuevo aviso.
        if ($id_establecimiento==76):
            $update->situacion=$id_situacion;
        endif;
        return $update->save();
    }

    public function ShowEstadosFisico() {
        $datos=DB::table('estados_sistema') 
                ->select('estados_sistema.estado_tabla','estados_sistema.descripcion')
                ->where('estados_sistema.tabla','=','camas->estado_fisico')
                ->where('estados_sistema.estado','=','1')
                ->get();
        return $datos;
    }

    public function ShowTipos() {
        $datos=DB::table('tipo_sistemas') 
                ->select('tipo_sistemas.estado_tabla','tipo_sistemas.descripcion')
                ->where('tipo_sistemas.tabla','=','camas->tipo')
                ->where('tipo_sistemas.estado','=','1')
                ->get();
        return $datos;
    }

    public function ShowTiposCamas() {
        $datos=DB::table('tipo_camas') 
                ->select('tipo_camas.descripcion','tipo_camas.id')
                ->where('tipo_camas.estado','=','1')
                ->get();
        return $datos;
    }

        public function ShowSituacionesCamas() {
        $datos=DB::table('situacion_camas') 
                ->select('situacion_camas.id','situacion_camas.descripcion')
                ->where('situacion_camas.estado','=','1')
                ->get();
        return $datos;
    }

	public function ShowSituaciones() {
        $datos=DB::table('situacion_camas') 
                ->select('situacion_camas.id','situacion_camas.descripcion')
                ->where('situacion_camas.estado','=','1')
                ->get();
        return $datos;
    }

    public function ShowClasesCamas() {
        $datos=DB::table('clase_camas') 
                ->select('clase_camas.id','clase_camas.descripcion')
                ->where('clase_camas.estado','=','1')
                ->get();
        return $datos;
    }

    public function ShowBuscaCama($id_cama, $nivel, $id_consultorio=0,$id_consultorio_hosp=0) {
    
        $cad_cama = "select T6.id,T6.nombre as nombre_cama,T1.nombre as nombre_cuarto
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio = T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id 
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                where T6.estado=0";

        if ($nivel == 2)
          $cad_cama .= "AND T2.id_consultorio=" . $id_consultorio;
        else
          $cad_cama .= " AND T2.id_consultorio=" . $id_consultorio_hosp;

        $cad_cama .= "UNION
                select T6.id,T6.nombre as nombre_cama,T1.nombre as nombre_cuarto
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio = T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id 
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                where T6.estado=1 and T6.id=" . $id_cama . "
                ";
        
        $data = DB::select($cad_cama);
        return $data;
    }

    public function RegistrarRotacion($id_hospitalizado,$dni_beneficiario , $id_opcion , $fecha_cambio , $id_medico, $id_pabellon, $id_piso , $id_consultorio_hosp , $nombre_consultorio , $id_cuarto ,$id_cama , $id_consultorio , $id_user , $id_establecimiento, $documento) {
                                
        $cad = "Select sp_insert_rotacion_cama(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_hospitalizado,$dni_beneficiario , $id_opcion , $fecha_cambio , $id_medico, $id_pabellon, $id_piso , $id_consultorio_hosp , $nombre_consultorio , $id_cuarto ,$id_cama , $id_consultorio , $id_user , $id_establecimiento, $documento));

        return $data[0]->sp_insert_rotacion_cama;

    }   
    public function RegistrarRotacion_emergencia($dni_beneficiario , $id_opcion , $fecha_cambio , $id_medico, $id_pabellon, $id_piso , $id_consultorio_hosp , $nombre_consultorio , $id_cuarto ,$id_cama , $id_consultorio , $id_user , $id_establecimiento, $documento,$id_emergencia) {
                                
        $cad = "Select sp_insert_rotacion_cama_emergencia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($dni_beneficiario , $id_opcion , $fecha_cambio , $id_medico, $id_pabellon, $id_piso , $id_consultorio_hosp , $nombre_consultorio , $id_cuarto ,$id_cama , $id_consultorio , $id_user , $id_establecimiento, $documento,$id_emergencia));

        return $data[0]->sp_insert_rotacion_cama_emergencia;

    }   
    public function GetCamaByIdConsultorio_todos($id_consultorio) {
        
        $cad = "select T6.id,T6.nombre as nombre_cama,T1.nombre as nombre_cuarto,T6.estado
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio = T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id 
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                where T2.id_consultorio=".$id_consultorio."
                order by T6.id";
        $data = DB::select($cad);
        return $data;
    }
    public function GetCamaActualyVacias($id_cama, $nombre_atencion, $id_ipress) {
    
        $cad = "select T6.id,T6.nombre as nombre_cama,T1.nombre as nombre_cuarto
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                left join estados_sistema T7 on CAST(T6.estado_fisico AS VARCHAR) = T7.estado_tabla 
                left join tipo_camas T8 on T6.tipo = cast(T8.id as varchar)
                left join situacion_camas T9 on T6.situacion = cast(T9.id as varchar)
                where T1.id_establecimiento=".$id_ipress." And T6.estado=0 And
                T6.nombre_atencion = '" . $nombre_atencion . "'
                UNION
                select T6.id,T6.nombre as nombre_cama,T1.nombre as nombre_cuarto
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio = T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id 
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                where T6.id=" . $id_cama . "
                ";

        $data = DB::select($cad);
        return $data;
    }

    public function GetCamaByIdConsultorioEmergencia($id_consultorio) {
        
        $cad = "select T6.id,T6.nombre as nombre_cama,T1.nombre as nombre_cuarto,T6.estado
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio = T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id 
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                where T2.id_consultorio=".$id_consultorio."
                order by T6.id";
        $data = DB::select($cad);
        return $data;
    }

    public function GetTotalCamas($id_establecimiento, $tipo_atencion) {
        $sql = "select count(c.id) count 
        from camas c 
        LEFT JOIN cuartos cu ON c.id_cuarto = cu.id
        where 
        c.id_establecimiento = $id_establecimiento 
        and c.estado = 0
        and cu.estado = 1
        and c.nombre_atencion = '$tipo_atencion';";
        $data = DB::select($sql);
        return $data[0];
    }

    public function GetCamasDisponibleCant($id_establecimiento, $tipo_atencion) {
        $sql = "select count(c.id) count 
        from camas c 
        LEFT JOIN cuartos cu ON c.id_cuarto = cu.id
        where c.id_establecimiento = $id_establecimiento 
        and c.estado = 0
        and cu.estado = 1 and c.nombre_atencion = '$tipo_atencion';;";
        $data = DB::select($sql);
        return $data[0];
    }

    public function GetCamasOcupadasCant($id_establecimiento, $tipo_atencion) {
        $sql = "select count(c.id) count from camas  c
                LEFT JOIN cuartos cu ON c.id_cuarto = cu.id
                where c.id_establecimiento = $id_establecimiento and c.situacion = '4' and   
                c.estado = 0 and
                cu.estado = 1 and 
                c.nombre_atencion = '$tipo_atencion';";
        $data = DB::select($sql);
        return $data[0];
    }

    public function GetCamasLibresCant($id_establecimiento, $tipo_atencion) {
        $sql = "select count(c.id) count from camas c
              LEFT JOIN cuartos cu ON c.id_cuarto = cu.id
              where c.id_establecimiento = $id_establecimiento 
              and c.situacion = '1' 
              and c.estado = 0 
              and cu.estado = 1  
              and c.nombre_atencion = '$tipo_atencion';";
        $data = DB::select($sql);
        return $data[0];
    }

    public function GetCamasBloqueadasCant($id_establecimiento, $tipo_atencion) {
        //$sql = " select count(id) count from camas where id_establecimiento = $id_establecimiento and estado = '1';";
        $sql = "select count(id) count from camas where id_establecimiento = $id_establecimiento and situacion in ('2','3') and nombre_atencion = '$tipo_atencion';";
        $data = DB::select($sql);
        return $data[0];
    }

    public function GetTodosServiciosConEstadisticaCamas($id_establecimiento, $tipo_atencion,$servicio) {
      
        $sql = "SELECT
                      T5.nombre AS pabellon, -- Agrupar por pabellón
                      T4.nombre AS piso,
                      T4.id AS idPiso,
                      T5.id AS idPabellon,
                      (
                          SELECT STRING_AGG(T2_sub.nombre::TEXT, ',')
                          FROM asignacion_servicios T2_sub
                          INNER JOIN consultorios T3_sub ON T2_sub.id_consultorio = T3_sub.id
                          INNER JOIN pisos T4_sub ON T2_sub.id_piso = T4_sub.id
                          INNER JOIN pabellones T5_sub ON T4_sub.id_pabellon = T5_sub.id
                          WHERE T4_sub.id = T4.id
                            AND T5_sub.id = T5.id
                      ) AS nombre_atencion,
                      (
                          SELECT STRING_AGG(T2_sub.id::TEXT, ',')
                          FROM asignacion_servicios T2_sub
                          INNER JOIN consultorios T3_sub ON T2_sub.id_consultorio = T3_sub.id
                          INNER JOIN pisos T4_sub ON T2_sub.id_piso = T4_sub.id
                          INNER JOIN pabellones T5_sub ON T4_sub.id_pabellon = T5_sub.id
                          WHERE T4_sub.id = T4.id
                            AND T5_sub.id = T5.id
                      ) AS idServicios,
                      T6.id_establecimiento,
                      T3.id,
                      T4.nombre consultorio,
                ";
        
        if ($tipo_atencion == "EMERGENCIA") {
            // Si es emergencia, eliminamos la condición T1.estado = 1
            $sql .= "
                    SUM(CASE WHEN T6.estado IS NULL THEN 1 ELSE 0 END) sin_situacion,
                    SUM(CASE WHEN T6.estado IN ('0', '1','2') OR T6.estado IS NULL THEN 1 ELSE 0 END) AS total,
                    SUM(CASE WHEN T6.estado = '0' THEN 1 ELSE 0 END) c_disponibles,
                    SUM(CASE WHEN T6.situacion = '4' AND  T6.estado = 0 THEN 1 ELSE 0 END) c_ocupadas,
                    SUM(CASE WHEN T6.situacion = '1' AND  T6.estado = 0 THEN 1 ELSE 0 END) c_libres,
                    SUM(CASE WHEN T6.situacion IN ('2', '3')  THEN 1 ELSE 0 END) c_bloqueadas ";
        } else {
            // Para otras atenciones, mantenemos la condición T1.estado = 1
            
            $sql .= "
                    SUM(CASE WHEN T6.estado IS NULL THEN 1 ELSE 0 END) sin_situacion,
                    SUM(CASE WHEN T6.estado IN ('0', '1','2') OR T6.estado IS NULL THEN 1 ELSE 0 END) AS total,
                    SUM(CASE WHEN T6.estado = '0' THEN 1 ELSE 0 END) c_disponibles,
                    SUM(CASE WHEN T6.situacion = '4' THEN 1 ELSE 0 END) c_ocupadas,
                    SUM(CASE WHEN T6.situacion = '1' THEN 1 ELSE 0 END) c_libres,
                    SUM(CASE WHEN T6.situacion IN ('2', '3') THEN 1 ELSE 0 END) c_bloqueadas ";
        }
        if ($id_establecimiento==76 && $tipo_atencion=="EMERGENCIA"):
            $sql .= ",T1.id id_cuarto,T1.nombre cuarto ";
        endif;
        $sql .= "from camas T6
                    inner join cuartos T1 on T6.id_cuarto=T1.id
                    inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
                    inner join consultorios T3 on T2.id_consultorio=T3.id
                    inner join pisos T4 on T2.id_piso=T4.id 
                    inner join pabellones T5 on T4.id_pabellon=T5.id
                    where  T1.id_establecimiento=$id_establecimiento and T2.nombre_atencion = '$tipo_atencion' "; 
        if ($servicio != ""){
         
        $sql .= "and T2.id = '$servicio'";
        }
        if ($id_establecimiento==76 && $tipo_atencion=="EMERGENCIA"){
        $sql .=  "GROUP BY
                  T3.nombre,
                  T3.id,                  
                  T6.id_establecimiento,
                  T2.nombre_atencion,
                  T5.nombre,
                  T4.nombre,
                  T1.nombre,
                  T4.id ,
                  T5.id ,
                  T2.id 
                  ";
        }else{
        $sql .=  "GROUP BY
                  T5.nombre, -- Agrupado por pabellón
                  T4.nombre,
                  T4.id,
                  T6.id_establecimiento,
                  T3.id,
                  T5.id
                  ";
        }
                 

        if ($id_establecimiento==76 && $tipo_atencion=="EMERGENCIA"):
            $sql .= ",T1.id";
        endif;
        $sql .= " ORDER BY T5.nombre;";
        
        $data = DB::select($sql);
        // dd($data );
        return $data;
    }

    public function GetEstablecimientos($id_establecimiento) {
        $sql = "select distinct e.id, e.nombre establecimiento from asignacion_servicios as2 join establecimientos e on as2.id_establecimiento = e.id where e.id = $id_establecimiento;";
        $data = DB::select($sql);
        return $data;
    }

    public function GetAtencionPorIdEstablecimiento($id_establecimiento) {
        $sql = "select distinct as2.nombre_atencion from asignacion_servicios as2 where as2.id_establecimiento = $id_establecimiento  ;";
        $data = DB::select($sql);
        return $data;
    }

     public function GetServicioPorIdEstablecimientoYTipoAtencion($id_establecimiento, $tipo_atencion) {
        $sql = "SELECT as2.nombre,
                    MIN(as2.id) AS id
                FROM asignacion_servicios as2 
                JOIN consultorios c 
                ON as2.id_consultorio = c.id 
                WHERE as2.estado = 0 
                  AND as2.id_establecimiento = $id_establecimiento 
                  AND as2.nombre_atencion = '$tipo_atencion'
                GROUP BY as2.nombre;";
        $data = DB::select($sql);
        return $data;
    }


    public function GetServicioPorIdEstablecimientoYTipoAtencion2($id_establecimiento, $tipo_atencion) {
        $sql = "SELECT 
                    MIN(c.id) AS id,
                    c.nombre AS nombre
                FROM asignacion_servicios AS as2
                INNER JOIN consultorios AS c 
                    ON as2.id_consultorio = c.id
                WHERE as2.estado = 0
                AND as2.id_establecimiento = $id_establecimiento
                AND as2.nombre_atencion = '$tipo_atencion'
                GROUP BY c.nombre
                ORDER BY c.nombre;";
        $data = DB::select($sql);
        return $data;
    }

    public function GetPabellonIdEstablecimientoYTipoAtencionYIdServicio($id_establecimiento, $tipo_atencion, $id_servicio) {
        $sql = "select
                    p2.id id_pabellon,
                    p2.nombre pabellon
                from asignacion_servicios as2 
                join pisos p on as2.id_piso = p.id 
                join pabellones p2 on p.id_pabellon = p2.id 
                WHERE as2.estado = 0 and as2.id_establecimiento = $id_establecimiento AND as2.nombre_atencion = '$tipo_atencion' and as2.id_consultorio = $id_servicio
                ORDER BY p2.nombre;";
        $data = DB::select($sql);
        return $data;
    }

    public function GetEstadisticaCamasFiltrado($id_establecimiento, $tipo_atencion, $id_servicio=0, $id_pabellon='') {
        $sql = "select
                    T3.id,
                    T3.nombre consultorio,
                    T6.id_establecimiento,
                    T6.nombre_atencion,
                    sum(case when T6.estado in('0','1') THEN 1 ELSE 0 end) total,
                    sum(case when T6.estado = '0' then 1 else 0 end) c_disponibles,
                    sum(case when T6.situacion = '4' then 1 else 0 end) c_ocupadas,
                    sum(case when T6.situacion = '1' then 1 else 0 end) c_libres,
                    sum(case when T6.situacion in ('2','3') then 1 else 0 end) c_bloqueadas";
        
        if ($id_establecimiento==76 && $tipo_atencion=="EMERGENCIA"):
            $sql .= ",T1.id id_cuarto,T1.nombre cuarto";
        endif;

        $sql .= " from camas T6
                    inner join cuartos T1 on T6.id_cuarto=T1.id
                    inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
                    inner join consultorios T3 on T2.id_consultorio=T3.id
                    join pisos p on T2.id_piso = p.id 
                    join pabellones p2 on p.id_pabellon = p2.id 
                where T1.id_establecimiento=$id_establecimiento and T2.nombre_atencion = '$tipo_atencion'";

        if ($id_establecimiento==76 && $tipo_atencion=="EMERGENCIA"):
            $sql .= " and T3.id = 76 
                    and p2.id = 28";
        else:
            if ($id_servicio != "0"):
                $sql .= " and T3.id = $id_servicio";
            endif;
            if ($id_pabellon != ""):
                $sql .= " and p2.id = $id_pabellon";
            endif;
        endif;
        
        $sql .= " group by T3.id, T6.id_establecimiento, T6.nombre_atencion ";

        if ($id_establecimiento==76 && $tipo_atencion=="EMERGENCIA"):
            $sql .= ",T1.id";
        endif;

        $sql .= " order by consultorio;";
        $data = DB::select($sql);
        return $data;
    }

    public function GetTotalCamasPorIdEstablecimientoYIdServicio($id_establecimiento, $id_servicio, $tipo_atencion) {
        if ($id_establecimiento==76 and $tipo_atencion=="EMERGENCIA"):
            $sql = "select sum(case when c.estado in('0','1') THEN 1 ELSE 0 end) count
                from camas c
                inner join cuartos cu on c.id_cuarto  = cu.id
                where c.id_establecimiento = $id_establecimiento and c.estado = 0 and cu.estado = 1  and c.id_cuarto = $id_servicio;";
        else:
            $sql = "select sum(case when c.estado in('0','1') THEN 1 ELSE 0 end) count
                from camas c
                    inner join cuartos c2 on c.id_cuarto = c2.id
                    inner join asignacion_servicios as2 on c2.id_asignacion_servicio=as2.id
                where c.id_establecimiento = $id_establecimiento and c.estado = 0 and c2.estado = 1 and as2.id_consultorio = $id_servicio;";
        endif;
        $data = DB::select($sql);
        return $data[0];
    }

    public function GetCamasDisponibleCantPorIdEstablecimientoYIdServicio($id_establecimiento, $id_servicio, $tipo_atencion) {
        if ($id_establecimiento==76 and $tipo_atencion=="EMERGENCIA"):
            $sql = "select count(c.id) count
                from camas c
                inner join cuartos c2 on c.id_cuarto = c2.id
                where c.id_establecimiento = $id_establecimiento and c.estado = 1 and c2.estado = 1 and c.id_cuarto = $id_servicio;";
        else:
            $sql = "select count(c.id) count
                from camas c
                    inner join cuartos c2 on c.id_cuarto = c2.id
                    inner join asignacion_servicios as2 on c2.id_asignacion_servicio=as2.id
                where c.id_establecimiento = $id_establecimiento  and c.estado = 1 and c2.estado = 1 and as2.id_consultorio = $id_servicio;";
        endif;
        $data = DB::select($sql);
        return $data[0];
    }

    public function GetCamasOcupadasCantPorIdEstablecimientoYIdServicio($id_establecimiento, $id_servicio, $tipo_atencion) {
        if ($id_establecimiento==76 and $tipo_atencion=="EMERGENCIA"):
            $sql = "select count(c.id) count
                from camas c
                inner join cuartos c2 on c.id_cuarto = c2.id
                where c.id_establecimiento = $id_establecimiento and c.situacion = '4' and c.estado = 0 and c2.estado = 1 and c.id_cuarto = $id_servicio;";
        else:
            $sql = "select count(c.id) count
                from camas c
                    inner join cuartos c2 on c.id_cuarto = c2.id
                    inner join asignacion_servicios as2 on c2.id_asignacion_servicio=as2.id
                where c.id_establecimiento = $id_establecimiento and c.situacion = '4' and c.estado = 0 and c2.estado = 1 and as2.id_consultorio = $id_servicio;";
        endif;
        $data = DB::select($sql);
        return $data[0];
    }

    public function GetCamasLibresCantPorIdEstablecimientoYIdServicio($id_establecimiento, $id_servicio, $tipo_atencion) {
        if ($id_establecimiento==76 and $tipo_atencion=="EMERGENCIA"):
            $sql = "select count(c.id) count
                from camas c
                inner join cuartos c2 on c.id_cuarto = c2.id
                where c.id_establecimiento = $id_establecimiento and c.situacion = '1' and c.estado = 0 and c2.estado = 1 and c.id_cuarto = $id_servicio;";
        else:
            $sql = "select count(c.id) count
                from camas c
                    inner join cuartos c2 on c.id_cuarto = c2.id
                    inner join asignacion_servicios as2 on c2.id_asignacion_servicio=as2.id
                where c.id_establecimiento = $id_establecimiento and c.situacion = '1' and c.estado = 0 and c2.estado = 1 and as2.id_consultorio = $id_servicio;";
        endif;
        $data = DB::select($sql);
        return $data[0];
    }

    public function GetCamasBloqueadasCantPorIdEstablecimientoYIdServicio($id_establecimiento, $id_servicio, $tipo_atencion) {
        if ($id_establecimiento==76 and $tipo_atencion=="EMERGENCIA"):
            $sql = "select count(c.id) count
                from camas c
                where c.id_establecimiento = $id_establecimiento and c.situacion in ('2','3') and c.id_cuarto = $id_servicio;";
        else:
            $sql = "select count(c.id) count
                from camas c
                    inner join cuartos c2 on c.id_cuarto = c2.id
                    inner join asignacion_servicios as2 on c2.id_asignacion_servicio=as2.id
                where c.id_establecimiento = $id_establecimiento and c.situacion in ('2','3') and as2.id_consultorio = $id_servicio;";
        endif;
        $data = DB::select($sql);
        return $data[0];
    }

    public function GetReporteCamasPorIdEstablecimientoYTipoAtencionYIdServicio($id_establecimiento, $tipo_atencion, $id_servicio) {
        // bakup por el where :  c.nombre_atencion = '$tipo_atencion' por as2.nombre_atencion
        // $sql = "select
        //             c.id,
        //             c.nombre nombre_cama,
        //             c.situacion id_situacion,
        //             sc.descripcion situacion_cama,
        //             p2.id id_pabellon,
        //             p2.nombre pabellon
        //         from camas c
        //             inner join cuartos c2 on c.id_cuarto = c2.id
        //             inner join asignacion_servicios as2 on c2.id_asignacion_servicio=as2.id
        //             left join situacion_camas sc on cast(c.situacion as integer) = sc. id 
        //             inner join pisos p on as2.id_piso = p.id
        //             inner join pabellones p2 on p.id_pabellon = p2.id
        //         where c.id_establecimiento = $id_establecimiento and c.nombre_atencion = '$tipo_atencion' and as2.id_consultorio = $id_servicio
        //         order by pabellon, nombre_cama;";
        $sql = "select
                c.id,
                c.nombre nombre_cama,
                c.situacion id_situacion,
                sc.descripcion situacion_cama,
                p2.id id_pabellon,
                p2.nombre pabellon,
                cc.id id_clase_cama,
                cc.descripcion clase_cama,
                c.nombre_atencion tipo_atencion,
                c.id_cuarto,
                c2.nombre cuarto,
                c.tipo id_tipo_cama,
                tc.descripcion tipo_cama
            from camas c
                inner join cuartos c2 on c.id_cuarto = c2.id
                inner join asignacion_servicios as2 on c2.id_asignacion_servicio=as2.id
                left join situacion_camas sc on cast(c.situacion as integer) = sc. id 
                inner join pisos p on as2.id_piso = p.id
                inner join pabellones p2 on p.id_pabellon = p2.id
                left join clase_camas cc on c.id_clase_cama = cc.id
                left join tipo_camas tc on c.tipo = cast(tc.id as varchar)";
        if ($id_establecimiento == 76 && $tipo_atencion == "EMERGENCIA"):
            // En la siguiente instruccion el $id_servicio se toma como id_cuarto
            $sql .= " where c.id_establecimiento = $id_establecimiento and as2.nombre_atencion = 'EMERGENCIA' and as2.id_consultorio = 76 and c.id_cuarto = $id_servicio";
        else:
            $sql .= " where c.id_establecimiento = $id_establecimiento and as2.nombre_atencion = '$tipo_atencion' and as2.id_consultorio = $id_servicio";
        endif;
        $sql .= " order by pabellon, cuarto, tipo_cama, nombre_cama;";
        $data = DB::select($sql);
        return $data;
    }

    public function GetSoloPabellonesPorIdEstablecimientoYTipoAtencionYIdServicio($id_establecimiento, $tipo_atencion, $id_servicio) {
        // Se modifico where : c.nombre_atencion = '$tipo_atencion' por as2.nombre_atencion = '$tipo_atencion'
        $sql = "select distinct
                    p2.nombre pabellon
                from camas c
                    inner join cuartos c2 on c.id_cuarto = c2.id
                    inner join asignacion_servicios as2 on c2.id_asignacion_servicio=as2.id
                    left join situacion_camas sc on cast(c.situacion as integer) = sc. id 
                    inner join pisos p on as2.id_piso = p.id
                    inner join pabellones p2 on p.id_pabellon = p2.id";
        if ($id_establecimiento == 76 && $tipo_atencion == "EMERGENCIA"):
            // En la siguiente instruccion el $id_servicio se toma como id_cuarto
            $sql .= " where c.id_establecimiento = $id_establecimiento and as2.nombre_atencion = '$tipo_atencion' and as2.id_consultorio = 76 and c.id_cuarto = $id_servicio order by pabellon;";
        else:
            $sql .= " where c.id_establecimiento = $id_establecimiento and as2.nombre_atencion = '$tipo_atencion' and as2.id_consultorio = $id_servicio order by pabellon;";
        endif;
        
        $data = DB::select($sql);
        return $data;
    }

    public function getTitularForIdAsegurado($idAsegurado){
      $sql = "
        select a.id, tg.descripcion grado,s.descripcion situacion,pda.cip ,a.id_asegurado_titular FROM asegurados a
        left join titular_grados tg on tg.id  = a.id_asegurado_titular
        left join persona_datos_adicionales pda on pda.id_asegurado = a.id
        left join situaciones s on s.id = pda.id_situacion
        where a.id = $idAsegurado 
      ";
      $asegurado = DB::select($sql);
      $asegurado = $asegurado[0];
      if($asegurado->id_asegurado_titular == $asegurado->id_asegurado_titular){
        return $asegurado;
      }
      else if($asegurado->id_asegurado_titular == null){
          return null;
      }
      else{
        $sql2 = "
        select a.id, tg.descripcion grado,s.descripcion situacion,pda.cip ,a.id_asegurado_titular FROM asegurados a
        left join titular_grados tg on tg.id  = a.id_asegurado_titular
        left join persona_datos_adicionales pda on pda.id_asegurado = a.id
        left join situaciones s on s.id = pda.id_situacion
        where a.id = $asegurado->id_asegurado_titular 
      ";
        $asegurado2 = DB::select($sql2);
        return $asegurado2[0];
      }
    } 
    public function GetInfoPacientePorIdCama($id_cama,$tipo_atencion) {
        $sql1 = "
        select h.id id_hospitalizaciones ,
              h.paterno_beneficiario ||' '|| h.materno_beneficiario  apellidos_paciente,
              h.nombre_beneficiario nombre_paciente,
              h.dni_beneficiario  nro_doc_ident,
              h.tipo_doc_beneficiario  tipo_doc,
              h.fecha_nacimiento_beneficiario fecha_nac,
              h.hora_ingreso,
              h.fecha_ingreso,
              h.nro_historia,
              h.sexo,
              h.tipo_beneficiario,
              h.dni_titular,
              h.nombre_titular,
              h.paterno_titular,
              h.materno_titular,
              h.id_asegurado,
              di.nombre diagnostico_inicial,
              aten.nombre lugar_pide,
              tp.descripcion  parentesco,
            DATE_PART('YEAR',AGE(CAST(h.fecha_nacimiento_beneficiario AS date))) edad_anios,
            DATE_PART('MONTH',AGE(CAST(h.fecha_nacimiento_beneficiario AS date))) edad_meses,
            DATE_PART('DAY',AGE(CAST(h.fecha_nacimiento_beneficiario AS date))) edad_dias,
            h.fecha_ingreso fecha_ingreso_emergencia,
            h.id_cama_estado id_cama,
            c.nombre nombre_cama,
            h.id_tipo_acompaniante,
            h.nombre_acompaniante,
            h.telefono_acompaniante,
            h.tipo_doc_acompaniante,
            tg.descripcion_corta grado,
            a2.cip,
            tp.descripcion  tipo_parentesco,
            h.nro_doc_acompaniante dni_conducido,
            h.nombre_acompaniante nombre_conducido,
            h.telefono_acompaniante telf_conducido,
            up.descripcion_upss
          from hospitalizaciones h
          left join camas  c on c.id = h.id_cama_estado 
          left join tipo_parentescos tp  on tp.id = h.id_tipo_acompaniante
          left join diagnosticos di on  di.id=  h.id_diagnostico_inicial 
          left join tipo_atenciones aten on aten.id =  h.id_tipo_origen
          LEFT JOIN asegurados a2 ON h.id_asegurado = a2.id 
          LEFT JOIN upsses up on up.id=h.id_upss_encargado
          LEFT JOIN persona_datos_adicionales pda ON a2.id = pda.id_asegurado 
          LEFT JOIN titular_grados tg ON pda.id_grado = tg.id 
          where c.id =$id_cama and (h.estado = 5 or h.estado = 4)
                    ";
        $sql ="
            SELECT
                e.id id_emergencia,
                e.id_asegurado,
                a.paterno||' '||a.materno apellidos_paciente,
                a.nombre nombre_paciente,
                a.nro_doc_ident,
                tdi.descripcion tipo_doc,
                tb.descripcion parentesco,
                a.fecha_nac,
                DATE_PART('YEAR',AGE(CAST(a.fecha_nac AS date))) edad_anios,
                DATE_PART('MONTH',AGE(CAST(a.fecha_nac AS date))) edad_meses,
                DATE_PART('DAY',AGE(CAST(a.fecha_nac AS date))) edad_dias,
                pda.cip,
                tg.descripcion_corta grado,
                e.fecha_registro_ingreso fecha_ingreso_emergencia,
                e.cama_estado id_cama,
                c.nombre nombre_cama,
                h.id_tipo_acompaniante,
                h.tipo_doc_acompaniante,
                h.nombre_acompaniante,
                h.telefono_acompaniante,
                e.conducido,
                e.dni_conducido,
                e.id_tipo_beneficiario_conducido,
                e.nombre_conducido,
                e.telf_conducido,
                e.personal_acompañante,
                a2.dni_titular,
                a2.paterno_titular,
                a2.materno_titular,
                a2.nombre_titular,
                '' nro_historia,
                '' sexo,
                e.tipo_beneficiario,
                aten.nombre lugar_pide,
                up.descripcion_upss ,
                di.nombre diagnostico_inicial,
                tp.descripcion tipo_parentesco                
            from emergencias e 
            inner JOIN camas c ON e.cama_estado = c.id
            INNER JOIN asegurados a ON e.id_asegurado = a.id
            INNER JOIN tipo_documento_identidad tdi ON a.id_tipo_doc_ident = tdi.id 
            INNER JOIN tipo_beneficiarios tb ON a.id_tipo_beneficiario = tb.id
            LEFT JOIN asegurados a2 ON a.id_asegurado_titular = a2.id 
            LEFT join tipo_parentescos tp on tp.id =  e.id_tipo_parentesco 
            LEFT JOIN persona_datos_adicionales pda ON a2.id = pda.id_asegurado 
            LEFT JOIN titular_grados tg ON pda.id_grado = tg.id 
            LEFT JOIN hospitalizaciones h ON h.id_cama_estado = e.cama_estado
            left join tipo_atenciones aten on aten.id =  e.id_tipo_egreso
            LEFT JOIN upsses up on up.id=e.id_upss_alta
            LEFT JOIN diagnosticos di on  di.id=  e.id_diag_cab 
            WHERE e.cama_estado = $id_cama AND e.fecha_alta_medica IS NULL 
            and (e.estado = 3 or e.estado = 4)
            ORDER BY e.fecha_registro_ingreso DESC ;
        ";
        $data = [];
        if($tipo_atencion == "HOSPITALIZACION"){
          $data = DB::select($sql1);
        }else{
          $data = DB::select($sql);
        }
       
        return $data;
    }

    public function GetSoloCuartosPorIdEstablecimientoYTipoAtencionYIdServicio($id_establecimiento, $tipo_atencion, $id_servicio) {
        // Se modifico where : c.nombre_atencion = '$tipo_atencion' por as2.nombre_atencion = '$tipo_atencion'
        $sql = "select distinct
                    c2.id id,
                    c2.nombre cuarto
                from camas c
                    inner join cuartos c2 on c.id_cuarto = c2.id
                    inner join asignacion_servicios as2 on c2.id_asignacion_servicio=as2.id
                    left join situacion_camas sc on cast(c.situacion as integer) = sc. id 
                    inner join pisos p on as2.id_piso = p.id
                    inner join pabellones p2 on p.id_pabellon = p2.id";
        if ($id_establecimiento == 76 && $tipo_atencion == "EMERGENCIA"):
            // En la siguiente instruccion el $id_servicio se toma como id_cuarto
            $sql .= " where c.id_establecimiento = $id_establecimiento and as2.nombre_atencion = '$tipo_atencion' and as2.id_consultorio = 76 and c.id_cuarto = $id_servicio order by cuarto;";
        else:
            $sql .= " where c.id_establecimiento = $id_establecimiento and as2.nombre_atencion = '$tipo_atencion' and as2.id_consultorio = $id_servicio order by cuarto;";
        endif;
        $data = DB::select($sql);
        return $data;
    }

    public function ShowAllCamasXIpressYCuartoYTipoatencion($id_ipress,$id_cuarto,$id_atencion=0){
        $cad = "select T6.*,T1.nombre cuarto,T3.nombre consultorio,T3.nombre consultorio,T4.nombre piso,T5.nombre pabellon, T7.descripcion as desc_estado_fisico, T8.descripcion as desc_tipo, T9.descripcion as desc_situacion, T8.descripcion tipo_cama, cc.descripcion clase_cama
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id
                inner join pisos T4 on T2.id_piso=T4.id
                
                inner join pabellones T5 on T4.id_pabellon=T5.id
                left join estados_sistema T7 on CAST(T6.estado_fisico AS VARCHAR) = T7.estado_tabla and T7.tabla = 'camas->estado_fisico'
                --left join tipo_sistemas T8 on T6.tipo = T8.estado_tabla and T8.tabla = 'camas->tipo'
                left join tipo_camas T8 on T6.tipo = cast(T8.id as varchar)
                left join situacion_camas T9 on T6.situacion = cast(T9.id as varchar)
                left join clase_camas cc on T6.id_clase_cama = cc.id
                where T1.id_establecimiento=".$id_ipress." 
                and T6.id_cuarto=".$id_cuarto;
        if($id_atencion == 1)
            $cad .=" and T1.nombre_atencion='EMERGENCIA'";
        if($id_atencion == 2)
            $cad .=" and T1.nombre_atencion='HOSPITALIZACION'";
        $cad .= " order by T6.nombre;";
        $data = DB::select($cad);
        return $data;
        
    }
    
    public function ShowAllCuartosXIpress($id_ipress,$id_atencion =0){
        $cad = "select distinct c.id, c.nombre 
                from cuartos c
                inner join camas c2 on c.id = c2.id_cuarto 
                where c.id_establecimiento = $id_ipress
                /*and c2.estado = 0*/";
        if($id_atencion == 1)
            $cad .= " and c.nombre_atencion = 'EMERGENCIA'";
        if($id_atencion == 2)
            $cad .= " and c.nombre_atencion = 'HOSPITALIZACION'";
        $cad .= " order by c.nombre;";
        $data = DB::select($cad);
        return $data;
        
    }

    public function GetConsultorioByIpress_by_id_consultorio($id_ipress, $id_consultorio) {
        
        $cad = "select T3.id id_consultorio, T3.nombre
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                where T1.id_establecimiento= '".$id_ipress."' And T6.estado=0  and T2.id_consultorio='".$id_consultorio."'
                group by T3.id,T3.nombre  order by T3.nombre"; 
        $data = DB::select($cad);
        return $data;
    }

    public function ShowAllCuartosXIpress2($id_ipress,$id_atencion =0){
        $cad = "select distinct c.id, p2.nombre pabellon, p.nombre piso, c3.nombre servicio, c3.id as id_servicio , c.nombre 
            from cuartos c
            inner join camas c2 on c.id = c2.id_cuarto 
            inner join asignacion_servicios as2 on c.id_asignacion_servicio = as2.id 
            inner join consultorios c3 on as2.id_consultorio = c3.id 
            inner join pisos p on as2.id_piso = p.id
            inner join pabellones p2 on p.id_pabellon = p2.id 
            where c.id_establecimiento = $id_ipress
            and c2.estado = 0";
        if($id_atencion == 1)
            $cad .= " and c.nombre_atencion = 'EMERGENCIA'";
        if($id_atencion == 2)
            $cad .= " and c.nombre_atencion = 'HOSPITALIZACION'";
        $cad .= " order by c.nombre;";
        $data = DB::select($cad);
        return $data;
        
    }
    
    public function GetAllPabellonesYServicioOSalaYCuartos($id_establecimiento, $nombre_tipo_atencion) {
        /*$datos = Cama::select('p2.nombre as pabellon', 'c3.nombre as servicio_sala', 'c2.nombre as cuarto', 'c2.id as id_cuarto')
            ->join('cuartos as c2', 'camas.id_cuarto', '=', 'c2.id')
            ->join('asignacion_servicios as as2', 'c2.id_asignacion_servicio', '=', 'as2.id')
            ->join('consultorios as c3', 'as2.id_consultorio', '=', 'c3.id')
            ->join('pisos as p', 'as2.id_piso', '=', 'p.id')
            ->join('pabellones as p2', 'p.id_pabellon', '=', 'p2.id')
            ->where('camas.id_establecimiento', '=', $id_establecimiento)
            ->where('camas.nombre_atencion', '=', $nombre_tipo_atencion)
            ->distinct()
            ->orderBy('pabellon')
            ->orderBy('servicio_sala')
            ->orderBy('cuarto')
            ->get();*/
        $datos = DB::table('asignacion_servicios as as2')
            ->join('consultorios as c3', 'as2.id_consultorio', '=', 'c3.id')
            ->select('c3.id as id_cuarto', 'c3.nombre as servicio_sala')
            ->where('as2.id_establecimiento', $id_establecimiento)
            ->where('as2.nombre_atencion', $nombre_tipo_atencion)
            ->where('as2.estado', 0)
            ->distinct()
            ->orderBy('servicio_sala', 'asc')
            ->get();
        return $datos;
    }

    public function GetCamasByIdEstablecimientoYNombreTipoAtencionYIdCuarto($id_establecimiento, $nombre_tipo_atencion, $id_cuarto) {
        /*$camas = Cama::where('id_cuarto', $id_cuarto)
             ->where('id_establecimiento', $id_establecimiento)
             ->where('nombre_atencion', $nombre_tipo_atencion)
             ->where('estado', 0)
             ->orderBy('nombre')
             ->get();*/
        $camas = DB::table('camas as c')
            ->leftjoin('cuartos as c2', 'c.id_cuarto', '=', 'c2.id')
            ->leftjoin('asignacion_servicios as as2', 'c2.id_asignacion_servicio', '=', 'as2.id')
            ->leftjoin('consultorios as c3', 'as2.id_consultorio', '=', 'c3.id')
            ->select('c.id', 'c.nombre', 'c2.id as id_cuarto', 'c2.nombre as cuarto_nombre')
            ->where('c3.id', $id_cuarto)
            ->where('c.id_establecimiento', $id_establecimiento)
            /* ->where('c.nombre_atencion', $nombre_tipo_atencion) */
            ->whereIn('c.estado', [0,1])
            ->orderBy('c.nombre')
            ->get();
        return $camas;
    }

    // Listar Servicios Activos
    public function GetAllPabellonesYServicioOSalaYCuartosActivos($id_establecimiento, $nombre_tipo_atencion) {
        $datos = DB::table('camas')
            ->join('cuartos as c2', 'camas.id_cuarto', '=', 'c2.id')
            ->join('asignacion_servicios as as2', 'c2.id_asignacion_servicio', '=', 'as2.id')
            ->join('consultorios as c3', 'as2.id_consultorio', '=', 'c3.id')
            ->select('c3.id as id_cuarto', 'c3.nombre as servicio_sala')
            ->where('camas.id_establecimiento', $id_establecimiento)
            ->where('camas.nombre_atencion', $nombre_tipo_atencion)
            ->where('camas.estado', 0)
            ->distinct()
            ->orderBy('servicio_sala', 'asc')
            ->get();
        return $datos;
    }

    // Get Pabellones Admision
    public function GetAllPabellones($id_establecimiento, $nombre_tipo_atencion) {
        /*$datos = DB::table('camas')
            ->join('cuartos as c2', 'camas.id_cuarto', '=', 'c2.id')
            ->join('asignacion_servicios as as2', 'c2.id_asignacion_servicio', '=', 'as2.id')
            ->join('consultorios as c3', 'as2.id_consultorio', '=', 'c3.id')
            ->join('pisos as p1', 'as2.id_piso', '=', 'p1.id')
            ->join('pabellones as p2', 'p1.id_pabellon', '=', 'p2.id')
            ->select('p2.id', 'p2.nombre as pabellon')
            ->where('camas.id_establecimiento', $id_establecimiento)
            ->where('camas.nombre_atencion', $nombre_tipo_atencion)
            ->whereIn('camas.estado', [0, 1])
            ->distinct()
            ->orderBy('pabellon', 'asc')
            ->get();*/
        $datos = DB::table('pabellones')
            ->select('pabellones.id', 'pabellones.nombre as pabellon')
            ->where('pabellones.id_establecimiento', $id_establecimiento)
            ->where('pabellones.nombre_atencion', $nombre_tipo_atencion)
            ->where('pabellones.estado', 0)
            ->orderBy('pabellon', 'asc')
            ->get();
    
        return $datos;
    }

    // Get Servicios por Pabellon - Admision
    public function GetAllServiciosByIdEstablecimientoYNombreTipoAtencionYIdPabellon($id_establecimiento, $nombre_tipo_atencion, $id_pabellon) {
        /*$datos = DB::table('camas')
            ->join('cuartos as c2', 'camas.id_cuarto', '=', 'c2.id')
            ->join('asignacion_servicios as as2', 'c2.id_asignacion_servicio', '=', 'as2.id')
            ->join('consultorios as c3', 'as2.id_consultorio', '=', 'c3.id')
            ->join('pisos as p1', 'as2.id_piso', '=', 'p1.id')
            ->join('pabellones as p2', 'p1.id_pabellon', '=', 'p2.id')
            ->select('c3.id', 'c3.nombre')
            ->where('camas.id_establecimiento', $id_establecimiento)
            ->where('camas.nombre_atencion', $nombre_tipo_atencion)
            ->where('p2.id', $id_pabellon)
            ->whereIn('camas.estado', [0, 1])
            ->distinct()
            ->orderBy('c3.nombre', 'asc')
            ->get();*/
        $datos = DB::table('consultorios as c3')
            ->join('asignacion_servicios as as2', 'as2.id_consultorio', '=', 'c3.id')
            ->join('pisos as p1', 'as2.id_piso', '=', 'p1.id')
            ->join('pabellones as p2', 'p1.id_pabellon', '=', 'p2.id')
            ->select('c3.id', 'c3.nombre')
            ->where('as2.id_establecimiento', $id_establecimiento)
            ->where('as2.nombre_atencion', $nombre_tipo_atencion)
            ->where('p2.id', $id_pabellon)
            ->where('as2.estado', 0)
            ->distinct()
            ->orderBy('c3.nombre', 'asc')
            ->get();

        return $datos;
    }

    public function GetReporteCamasPorIdEstablecimientoYTipoAtencionYIdServicio2($id_establecimiento, $tipo_atencion, $id_servicio,$nombreCuarto ="",$idPiso = 0,$idPabellon=0,$idServicios=""){
        //dd($tipo_atencion);
      if($tipo_atencion != "EMERGENCIA"){//$tipo_atencion == "HOSPITALIZACION"){        
      // Construcción de la consulta con condición específica
      // Entrada en forma de cadena
      $idServiciosString = $idServicios; // Entrada como "6,12,44"

      // Convertir la cadena en un array
      $idServiciosArray = explode(',', $idServiciosString);

      // Validar que los valores sean numéricos (opcional, pero recomendado)
      $idServiciosArray = array_filter($idServiciosArray, 'is_numeric');

      // Convertir el array en una lista separada por comas
      $idServiciosList = implode(',', $idServiciosArray);
      $query = "
                  select T6.id, T2.nombre nombreservicio,T4.nombre Piso,t1.nombre nombrecuarto,
                                  T6.nombre nombrecama,tc.descripcion tipocama, sc.descripcion situacioncamadesc,T6.nombre_atencion tipo_atencion,
                                  t6.situacion situacioncama from camas T6
                                            inner join tipo_camas tc   on tc.id=T6.id_clase_cama 
                                            inner join cuartos T1 on T6.id_cuarto=T1.id
                                            left  join situacion_camas sc on sc.id = cast(T6.situacion as integer)
                                            left join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
                                            left join consultorios T3 on T2.id_consultorio=T3.id
                                            inner join pisos T4 on T2.id_piso=T4.id 
                                            inner join pabellones T5 on T4.id_pabellon=T5.id
                  where (T6.estado IN (0, 1, 2) OR T6.estado IS NULL) and T5.estado = 0 AND T2.estado = 0 and  T2.id_establecimiento = 76
                  and T4.id = $idPiso --idPiso
                  and T5.id = $idPabellon --idPabellon
                  AND T2.id IN ($idServiciosList) --idServicios múltiples
                ";
                
                  $data = DB::select($query);
                  return $data;
      }else{
        $cad = "
        select 
          T6.id,
          T6.nombre as nombrecama,
          T6.situacion as situacioncama,
          T1.nombre as nombrecuarto,
          T6.nombre_atencion as tipo_atencion,
          T9.descripcion as situacioncamadesc,
          T2.nombre nombreservicio,
          T8.descripcion as tipocama
        from camas T6 
        inner join cuartos T1 on T6.id_cuarto=T1.id
        inner join asignacion_servicios T2 on T1.id_asignacion_servicio=T2.id
        inner join consultorios T3 on T2.id_consultorio=T3.id
        inner join pisos T4 on T2.id_piso=T4.id
        
        inner join pabellones T5 on T4.id_pabellon=T5.id
        left join estados_sistema T7 on CAST(T6.estado_fisico AS VARCHAR) = T7.estado_tabla and T7.tabla = 'camas->estado_fisico'
        --left join tipo_sistemas T8 on T6.tipo = T8.estado_tabla and T8.tabla = 'camas->tipo'
        left join tipo_camas T8 on T6.tipo = cast(T8.id as varchar)
        left join situacion_camas T9 on T6.situacion = cast(T9.id as varchar)
        left join clase_camas cc on T6.id_clase_cama = cc.id
        where 
          T1.id_establecimiento=".$id_establecimiento." 
          and T6.estado in(0,1,2) ";
          //dd($id_establecimiento,$tipo_atencion,$id_servicio);
           // Condiciones adicionales según el establecimiento y tipo de atención
           if ($id_establecimiento == 76 && $tipo_atencion == "EMERGENCIA") {
            $cad .=" and T1.nombre_atencion='EMERGENCIA'";
            $cad .=" and T2.id_consultorio=76";
            $cad .=" and T1.nombre='$nombreCuarto'";
            } else {
                $cad .=" and T1.nombre_atencion='HOSPITALIZACION'";
                $cad .=" and T2.id_consultorio=$id_servicio";
            }
        
        $data = DB::select($cad);
        
        return $data;
      }
     
    }
    // Listas Camas activas segun servicio
    public function GetCamasByIdEstablecimientoYNombreTipoAtencionYIdCuartoActivos($id_establecimiento, $nombre_tipo_atencion, $id_cuarto) {
        $camas = DB::table('camas as c')
            ->join('cuartos as c2', 'c.id_cuarto', '=', 'c2.id')
            ->join('asignacion_servicios as as2', 'c2.id_asignacion_servicio', '=', 'as2.id')
            ->join('consultorios as c3', 'as2.id_consultorio', '=', 'c3.id')
            ->leftJoin('clase_camas as cc', 'c.id_clase_cama', '=', 'cc.id')
            ->select('c.id', 'c.nombre', 'c2.id as id_cuarto','c2.nombre as cuarto_nombre', 'c.id_clase_cama', 'cc.descripcion as clase_cama')
            ->where('c3.id', $id_cuarto)
            ->where('c.id_establecimiento', $id_establecimiento)
            ->where('c.nombre_atencion', $nombre_tipo_atencion)
            ->where('c.estado', 0)
            ->orderBy('c.nombre')
            ->get();
        return $camas;
    }

    public function getCamasByEstablecimientoIdYNombreTipoAtencionYPabellonId($id_establecimiento, $nombre_tipo_atencion, $id_pabellon) {
        $camas = DB::table('camas as c')
            ->join('cuartos as c2', 'c.id_cuarto', '=', 'c2.id')
            ->join('asignacion_servicios as as2', 'c2.id_asignacion_servicio', '=', 'as2.id')
            ->join('pisos as p', 'as2.id_piso', '=', 'p.id')
            ->join('pabellones as p2', 'p.id_pabellon', '=', 'p2.id')
            ->select('c.id', 'c.nombre', 'c.estado')
            ->where('c.id_establecimiento', $id_establecimiento)
            ->where('c.nombre_atencion', $nombre_tipo_atencion)
            ->where('p2.id', $id_pabellon)
            ->where('c.estado', 0)
            ->get();
        return $camas;
    }

    /**
     * Obtener informacion de la Cama Actual por Id Hospitalizacion
     */
    public function GetHospitalizacionCamaActualByHospitalizacionId($hospitalizacion_id) {
        
        $data = DB::table('hospitalizaciones as ho')
            ->join('camas as ca', 'ca.id', '=', 'ho.id_cama_estado')
            ->join('cuartos as cu', 'cu.id', '=', 'ca.id_cuarto')
            ->join('asignacion_servicios as as2', 'as2.id', '=', 'cu.id_asignacion_servicio')
            ->join('consultorios as co', 'co.id', '=', 'as2.id_consultorio')
            ->join('pisos as pi', 'pi.id', '=', 'as2.id_piso')
            ->join('pabellones as pa', 'pa.id', '=', 'pi.id_pabellon')
            ->join('consultorios as co2', 'co2.id', '=', 'ho.id_servicio_encargado')
            ->where('ho.id', $hospitalizacion_id)
            ->select([
                'co.nombre as servicio',
                'co.nombre as consultorio',
                'pa.nombre as pabellon',
                'pi.nombre as piso',
                'cu.nombre as cuarto',
                'ca.nombre as cama',
                'co2.nombre as servicio_encargado'
            ])
            ->first();

        return $data;
    }

    public function listar_censo_camas($p){
      $sql = "SELECT * FROM sp_listar_censos_camas(?, ?, ?, ?, ?, ?)";
      return DB::select($sql, $p);
    }

    // public function CamasClaseTransferir()
    // {
    //     return DB::table('camas as c')
    //         ->select(
    //             'c.id as id_cama',
    //             'c.nombre as cama',
    //             'as2.id_consultorio',
    //             'c2.id as id_cuarto',
    //             'c2.nombre as cuarto_nombre',
    //             'c.id_clase_cama',
    //             'cc.descripcion as clase_cama'
    //         )
    //         ->join('cuartos as c2', 'c.id_cuarto', '=', 'c2.id')
    //         ->join('asignacion_servicios as as2', 'c2.id_asignacion_servicio', '=', 'as2.id')
    //         ->join('consultorios as c3', 'as2.id_consultorio', '=', 'c3.id')
    //         ->leftJoin('clase_camas as cc', 'c.id_clase_cama', '=', 'cc.id')
    //         ->orderBy('c.nombre', 'asc')   
    //         ->get();
    // }

    public function listarCamasPorConsultorio($id_consultorio)
    {
        $camas = DB::table('camas as T6')
            ->join('cuartos as T1', 'T6.id_cuarto', '=', 'T1.id')
            ->join('asignacion_servicios as T2', 'T1.id_asignacion_servicio', '=', 'T2.id')
            ->join('pisos as T4', 'T2.id_piso', '=', 'T4.id')
            ->join('pabellones as T5', 'T4.id_pabellon', '=', 'T5.id')
            ->join('consultorios as T8', 'T2.id_consultorio', '=', 'T8.id')
            ->select(
                'T6.id as id_cama',
                'T8.id as id_consultorio',
                'T8.nombre as consultorio',
                'T6.nombre as cama',
                'T6.estado'
            )
            ->where('T8.id', $id_consultorio)
            ->where('T6.estado', 0) 
            ->orderBy('T6.nombre')
            ->get();

        return $camas;
    }

    public function listarCamaActual($id_consultorio)
    {
        $cama = DB::table('camas as T6')
            ->join('cuartos as T1', 'T6.id_cuarto', '=', 'T1.id')
            ->join('asignacion_servicios as T2', 'T1.id_asignacion_servicio', '=', 'T2.id')
            ->join('pisos as T4', 'T2.id_piso', '=', 'T4.id')
            ->join('pabellones as T5', 'T4.id_pabellon', '=', 'T5.id')
            ->join('consultorios as T8', 'T2.id_consultorio', '=', 'T8.id')
            ->select(
                'T6.id as id_cama',
                'T8.id as id_consultorio',
                'T8.nombre as consultorio',
                'T6.nombre as cama',
                'T6.estado'
            )
            ->where('T8.id', $id_consultorio)
            ->where('T6.estado', 1)
            ->first();

        return $cama;
    }

    public function listarUpsses($idConsultorioActual)
    {
        return DB::table('upsses as up')
            ->select(
                'up.id as id_upss',
                'up.id_consultorio',
                'up.codigo',
                'up.descripcion_upss as nombre'
            )
            ->join('consultorios as co', 'co.id', '=', 'up.id_consultorio')
            ->where('up.estado', 1)
            ->where('up.id_tipo_atencion', 3)
            ->where('up.id_consultorio', '!=', $idConsultorioActual)
            ->orderBy('up.descripcion_upss')
            ->get();
    }

    public function listarEspecialidadesPorUpss($id_upss)
    {
        return DB::table('upsses as up')
            ->join('consultorios as co', 'co.id', '=', 'up.id_consultorio')
            ->where('up.estado', 1)
            ->where('up.id_tipo_atencion', 3)
            ->where('up.id', $id_upss)
            ->select(DB::raw('DISTINCT co.id'), 'co.nombre as servicio', 'co.estado')
            ->orderBy('servicio')
            ->get();
    }

    public function medicosPorConsultorio($id)
    {
        return DB::table('medicos')
            ->where('id_consultorio', $id)
            ->select(
                'id',
                DB::raw('(nombre || \' \' || paterno || \' \' || materno) AS doctor')
            )
            ->orderBy('paterno', 'ASC')
            ->get();
    }

    public function getSituacionCamas()
    {
        return DB::table('situacion_camas')
            ->select('id', 'descripcion')
            ->where('estado', 1) 
            ->get();
    }
 
}
