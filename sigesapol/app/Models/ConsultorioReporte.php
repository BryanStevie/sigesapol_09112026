<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ConsultorioReporte extends Model
{

    protected $connection = 'pgsql_reportes';
    protected $table = 'consultorios';

    public function ShowConsultorios($id_farmacia)
    {
        $cad = "SELECT c.* 
            FROM consultorios c 
            INNER JOIN consultorio_farmacias cf ON c.id = cf.id_consultorio 
            WHERE COALESCE(c.estado, 1) != 3 
            AND cf.estado = 1 
            AND c.tipo = 1 
            AND cf.id_farmacia = ? 
            AND c.abreviatura IS NOT NULL 
            ORDER BY c.nombre ASC";

        // Ejecutar la consulta en la conexión alterna
        return DB::connection('pgsql_reportes')->select($cad, [$id_farmacia]);
    }


    public function ShowConsultoriosForMesa()
    {
        $cad = "select c.* from consultorios c 
        Where COALESCE(c.estado,1)!=3 and
        c.abreviatura Is Not Null 
        Order By c.nombre Asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowConsultoriosAnestesiologia($id_farmacia)
    {
        $cad = "select c.* from consultorios c Inner Join consultorio_farmacias cf On c.id = cf.id_consultorio Where COALESCE(c.estado,1)!=3 And cf.estado=1 And cf.id_farmacia=" . $id_farmacia . " And c.abreviatura Is Not Null Order By c.nombre Asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowConsultorios2($id_farmacia)
    {

        $cad = "select c.* from consultorios c Inner Join consultorio_farmacias cf On c.id = cf.id_consultorio Where COALESCE(c.estado,1)!=3 And cf.estado=1 And cf.id_farmacia=" . $id_farmacia . " And ( c.abreviatura='AN' OR c.abreviatura='CD') Order By c.nombre Asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowAllConsultorios($id_ipress)
    {

        $cad = "select T4.id,T4.nombre
                from establecimientos T1
                inner JOIN farmacias T2 on T1.id=T2.id_establecimiento 
                inner join consultorio_farmacias T3 on T3.id_farmacia=T2.id 
                inner JOIN consultorios T4 on T4.id=T3.id_consultorio
                Where T3.estado=1 and T1.id=" . $id_ipress . " and COALESCE(T4.estado,1)!=3 group by T4.id Order By T4.nombre Asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowAllConsultoriosSubConsultorio_old($id_ipress)
    {

        $cad = "select distinct t1.id,t1.nombre 
		from consultorios t1
		inner join sub_consultorios t2 on t1.id=t2.id_consultorio
		where t2.id_establecimiento=" . $id_ipress . " and COALESCE(t1.estado,1)!=3 and tipo=1 order by t1.nombre asc";


        $data = DB::select($cad);
        return $data;
    }

    public function ShowAllConsultoriosSubConsultorio($id_ipress)
    {
        $cad = "select distinct t1.id,t1.nombre
            from consultorios t1
            inner join consultorio_farmacias cf on cf.id_consultorio = t1.id
            inner join farmacias f on f.id  =   cf.id_farmacia 
            inner join establecimientos e on e.id = f.id_establecimiento 
            inner join sub_consultorios t2 on t1.id=t2.id_consultorio
            where e.id=" . $id_ipress . " and COALESCE(t1.estado,1)!=3 and tipo=1 order by t1.nombre asc";


        $data = DB::select($cad);
        return $data;
    }

    public function ShowAllConsultoriosSubConsultorioQuirurgico($id_ipress)
    {
        $cad = "select distinct t1.id,t1.nombre
          from consultorios t1
          inner join consultorio_farmacias cf on cf.id_consultorio = t1.id
          inner join farmacias f on f.id  =   cf.id_farmacia 
          inner join establecimientos e on e.id = f.id_establecimiento 
          inner join sub_consultorios t2 on t1.id=t2.id_consultorio
          where e.id=" . $id_ipress . " and COALESCE(t1.estado,1)!=3 and tipo=1 and t1.flag_quirurgico=1 order by t1.nombre asc";
        $data = DB::select($cad);
        return $data;
    }


    public function ShowAllConsultoriosSubConsultorio_x_servicio($id_ipress, $id_consultorio)
    {
        $cad = "select distinct t1.id,t1.nombre
                from consultorios t1
                inner join consultorio_farmacias cf on cf.id_consultorio = t1.id
                inner join farmacias f on f.id  =   cf.id_farmacia 
                inner join establecimientos e on e.id = f.id_establecimiento 
                inner join sub_consultorios t2 on t1.id=t2.id_consultorio
                where e.id=" . $id_ipress . " and t1.id=" . $id_consultorio . " and COALESCE(t1.estado,1)!=3 and tipo=1 order by t1.nombre asc";


        $data = DB::select($cad);
        return $data;
    }

    public function ShowAllConsultoriosSubConsultorioAll($id_ipress)
    {
        $cad = "select distinct t1.id,t1.nombre
            from consultorios t1
            inner join consultorio_farmacias cf on cf.id_consultorio = t1.id
            inner join farmacias f on f.id  =   cf.id_farmacia 
            inner join establecimientos e on e.id = f.id_establecimiento 
            --inner join sub_consultorios t2 on t1.id=t2.id_consultorio
            where e.id=" . $id_ipress . " and COALESCE(t1.estado,1)!=3  order by t1.nombre asc";


        $data = DB::select($cad);
        return $data;
    }


    public function ExisteConsultorioAll($id, $nombre)
    {
        $cad = "Select count(*) cant From consultorios Where UPPER(nombre)=UPPER(trim('" . $nombre . "')) And estado isNull";
        if ($id <> "")
            $cad .= " And id<>" . $id . "";
        $data = DB::select($cad);
        return $data[0];
    }

    public function getConsultorioPorEstablecimiento($id_ipress, $id_consultorio)
    {
        $query = '
        select	distinct t1.id,t1.nombre 
        from consultorios t1 
        inner join sub_consultorios t2 on t1.id=t2.id_consultorio 
        where t2.id_establecimiento = ' . (int)$id_ipress . ' and t1.id = ' . (int)$id_consultorio . ' and COALESCE(t1.estado,1)!=3 and tipo=1 
        order by t1.nombre asc
      ';
        $data = DB::select($query);
        return $data;
    }

    public function getConsultoriosProgramados($id_ipress = 0)
    {

        $cad = "select distinct T3.id,T3.nombre,T3.abreviatura
from medico_turnos T1
inner join medicos T2 on T1.id_medico=T2.id
inner join consultorios T3 on T2.id_consultorio=T3.id
where 1=1
and T2.id_establecimiento=" . $id_ipress . "
and fecha_atencion = now()::date 
and T1.estado = 1
and COALESCE(T3.estado,1)!=3 ";
        $data = DB::select($cad);
        return $data;
    }

    public function getConsultoriosProgramadosByEstablecimientoAndFecha($id_ipress = 0, $fecha = "")
    {

        $cad = "select distinct T3.id,T3.nombre,T3.abreviatura
from medico_turnos T1
inner join medicos T2 on T1.id_medico=T2.id
inner join consultorios T3 on T2.id_consultorio=T3.id
where 1=1
and id_establecimiento=" . $id_ipress . "
and fecha_atencion = '" . $fecha . "'::date 
and T1.estado = 1
and COALESCE(T3.estado,1)!=3
";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowConsultoriosAll()
    {
        /*
          $datos=DB::table('consultorios')
          ->select('consultorios.*')
          ->orderby('nombre','asc')
          //->where('consultorios.estado','!=','3')
          ->get();
          return $datos;
         */
        $cad = "select * from consultorios T1 where COALESCE(estado,1)!=3 order by nombre asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowConsultoriosAllX($id_consultorio, $id_establecimiento)
    {
        $cad = "select T1.* from consultorios T1
                inner join consultorio_farmacias T2 on T1.id = T2.id_consultorio
                inner join farmacias T3 on T3.id = T2.id_farmacia and T3.id_establecimiento = " . $id_establecimiento . " 
        where COALESCE(T1.estado,1)!=3 and T1.id != " . $id_consultorio . " and tipo = 1  group by T1.id order by T1.nombre asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowConsultoriosByParent($parent)
    {

        $cad = "select * from consultorios T1 where COALESCE(estado,1)!=3 and parent=" . $parent . " order by nombre asc";
        $data = DB::select($cad);
        return $data;
    }

    public function GetConsultorioByNombre($nombre)
    {
        $datos = DB::table('consultorios')
            ->select('consultorios.*')
            ->where('consultorios.nombre', $nombre)
            ->get();
        return $datos;
    }

    public function GetNombreById2($id_consultorio)
    {
        $datos = DB::table('consultorios')
            ->select('consultorios.*')
            ->where('consultorios.id', $id_consultorio)
            ->first();
        return $datos;
    }

    public function GetNombreById($id_consultorio)
    {
        $datos = DB::connection('pgsql_reportes')
            ->table('consultorios')
            ->select('consultorios.*')
            ->where('consultorios.id', $id_consultorio)
            ->first();
        return $datos;
    }


    public function GetNombreFarmaciaById($id_farmacia)
    {
        $datos = DB::table('farmacias')
            ->select('farmacias.*')
            ->where('farmacias.id', $id_farmacia)
            ->first();
        return $datos;
    }

    public function ShowServiciosByFarmacia($id_farmacia)
    {
        $datos = DB::table('farmacia_servicios')
            ->join('farmacias', 'farmacia_servicios.id_farmacia', '=', 'farmacias.id')
            ->join('consultorios', 'farmacia_servicios.id_servicio', '=', 'consultorios.id')
            ->where('farmacia_servicios.id_farmacia', '=', $id_farmacia)
            ->select('farmacias.nombre as farmacia', 'consultorios.nombre as servicio', 'consultorios.abreviatura')
            ->get();
        return $datos;
    }

    public function ShowAConsultorio($id_ipress, $id_servicio)
    {

        $cad = "select T4.id,T4.nombre
                from establecimientos T1
                inner JOIN farmacias T2 on T1.id=T2.id_establecimiento 
                inner join consultorio_farmacias T3 on T3.id_farmacia=T2.id 
                inner JOIN consultorios T4 on T4.id=T3.id_consultorio
                Where T3.estado=1 and T1.id=" . $id_ipress . " and T4.tipo=1 and  T4.id =" . $id_servicio . " and COALESCE(T4.estado,1)!=3 group by T4.id Order By T4.nombre Asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowConsultoriosUpss($id_servicio)
    {
        $data = DB::select("Select * From sp_listar_upss_consultorio($id_servicio);");
        return $data;
    }

    public function ExisteCodigoUpss($id_servicio, $codigo)
    {
        $cad = "select u.id 
                FROM upss  as u
                WHERE u.id_consultorio = " . $id_servicio . " AND codigo=" . $codigo . " AND COALESCE(u.estado,1)=1";
        $data = DB::select($cad);
        return isset($data) ? true : false;
    }

    public function getBuscarConsultorios($buscar)
    {

        $cad = "select *
            from consultorios
            where nombre ilike '%" . $buscar . "%' AND COALESCE(estado,1)!=3 AND tipo=1 ";
        //and tipo =

        $cad .= " Order by nombre asc
            limit 50 ";

        $data = DB::select($cad);
        return $data;
    }

    public function ShowConsultorioTipoAtencion($id_ipress, $id_tipo_atencion)
    {

        $cad = "select T4.id,T4.nombre
                from establecimientos T1
                inner JOIN farmacias T2 on T1.id=T2.id_establecimiento 
                inner join consultorio_farmacias T3 on T3.id_farmacia=T2.id 
                inner JOIN consultorios T4 on T4.id=T3.id_consultorio
                inner join upsses T5 on T5.id_consultorio=T4.id
                Where T3.estado=1 and T1.id=" . $id_ipress . " and T4.tipo=1 and  T5.id_tipo_atencion =" . $id_tipo_atencion . " group by T4.id Order By T4.nombre Asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowConsultorioTipoAtencion_x_consultorio($id_ipress, $id_consulto, $id_tipo_atencion)
    {

        $cad = "select T4.id,T4.nombre
                from establecimientos T1
                inner JOIN farmacias T2 on T1.id=T2.id_establecimiento 
                inner join consultorio_farmacias T3 on T3.id_farmacia=T2.id 
                inner JOIN consultorios T4 on T4.id=T3.id_consultorio
                inner join upsses T5 on T5.id_consultorio=T4.id
                Where T3.estado=1 and T1.id=" . $id_ipress . " and T4.tipo=1 and t4.id = " . $id_consulto . " and T5.id_tipo_atencion =" . $id_tipo_atencion . " group by T4.id Order By T4.nombre Asc";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowConsultorioHospitalizacionDesocupadosById_Ipress($id_ipress)
    {

        $cad = "SELECT DISTINCT
                T7.id,
                T6.nombre
                FROM camas T1 
                INNER JOIN cuartos T2 ON T1.id_cuarto = T2.id 
                INNER JOIN asignacion_servicios T3 ON T2.id_asignacion_servicio = T3.id
                    INNER JOIN consultorios T6 ON T3.id_consultorio = T6.id
                    INNER JOIN upsses T7 ON T6.id = T7.id_consultorio
                WHERE T6.tipo=1 AND  T1.id_establecimiento = ? AND T1.estado = 0 AND T7.id_tipo_Atencion  = 3
                GROUP BY T7.id, T6.nombre
                ORDER BY nombre;";
        $data = DB::select($cad, [$id_ipress]);
        return $data;
    }

    public function ShowConsultorioByIdIpressAndIdTipoAtencion($id_ipress, $id_tipo_atencion)
    {
        $cad = "SELECT T1.id, T2.id id_consultorio, T2.nombre, T1.codigo, T2.estado 
                FROM upsses as T1
                INNER JOIN consultorios as T2 ON T1.id_consultorio = T2.id 
                WHERE T2.tipo=1 AND T1.id_tipo_atencion = ? 
                ORDER BY T2.nombre;";
        $data = DB::select($cad, [$id_tipo_atencion]);
        return $data;
    }

    public function ShowCodigoUpssIdTipoAtencion($consultorio, $id_tipo_atencion)
    {
        $cad = "Select T1.id, T2.nombre, T1.codigo, T2.estado , T1.descripcion_upss
                FROM upsses as T1
                INNER JOIN consultorios as T2 ON T1.id_consultorio = T2.id 
                WHERE T1.id_tipo_atencion = " . $id_tipo_atencion . "
                AND T1.id_consultorio = " . $consultorio . "
                AND T2.tipo=1
                AND T1.estado=1
                ORDER BY T2.nombre;";
        $data = DB::select($cad);
        return $data;
    }

    public function getDivisiones()
    {
        $query = '
        select id, codigo_division, nombre 
        from tipo_consultorio_divisiones 
        where estado = 1 
        order by nombre asc
      ';
        $data = DB::select($query);
        return $data;
    }

    public function getDepartamentosPorDivision($id_tipo_division)
    {
        $query = '
        select id, codigo_departamento, nombre 
        from tipo_consultorio_departamentos 
        where estado = 1 and id_consultorio_division = ' . (int)$id_tipo_division . ' 
        order by nombre asc
      ';
        $data = DB::select($query);
        return $data;
    }

    public function ShowCodigoUpssId($id_upsses)
    {

        $datos = DB::table('upsses')
            ->join('consultorios', 'upsses.id_consultorio', '=', 'consultorios.id')
            ->where('upsses.id', '=', $id_upsses)
            ->select('upsses.id', 'consultorios.nombre', 'upsses.codigo')
            ->first();
        return $datos;
    }

    /* Obtener todos los consultorios por ipress sin condicionar el estado */
    public function GetAllPorIpress($id_ipress)
    {
        $cad = "SELECT DISTINCT c.id, c.nombre, c.tipo
                FROM consultorios c 
                INNER JOIN sub_consultorios sc ON c.id = sc.id_consultorio 
                WHERE sc.id_establecimiento = " . $id_ipress . " ORDER BY c.nombre ASC;";
        $data = DB::select($cad);
        return $data;
    }

    public function ShowConsultoriosTicketsAll()
    {

        $cad = DB::table('consultorios')->where('estado_servicio_ticket', 1)->orderBy('nombre', 'asc')->get();
        return $cad;
    }

    public function getServiciosAdministrativosByEstablecimiento($id_establecimiento)
    {
        $cad = "SELECT DISTINCT c.id, c.nombre
                FROM consultorios c 
                INNER JOIN sub_consultorios sc ON c.id = sc.id_consultorio 
                WHERE sc.id_establecimiento = " . $id_establecimiento . " AND c.estado = 4 AND c.tipo = 1
                ORDER BY c.nombre ASC";
        $data = DB::select($cad);
        return $data;
    }

    public function ListarServicioByIdUpss($id_upss)
    {
        $servicio = "SELECT distinct co.id, co.nombre servicio, co.estado 
          FROM upsses as up
          INNER JOIN consultorios as co ON co.id = up.id_consultorio
          inner join asignacion_servicios as2 on co.id = as2.id_consultorio
          WHERE  up.estado = 1 and as2.id_establecimiento = 76 and as2.estado = 0 
          And up.id_tipo_atencion = 3 and up.id = $id_upss
          ORDER BY servicio;";
        $servicio = DB::select($servicio);
        return $servicio;
    }

    public function solicitudes()
    {
        return $this->hasMany('App\Models\MesaRequerimiento');
    }

    public function getServiciosQuirurgicos()
    {
        $servicios = "select distinct c.id as id_upss, c.nombre from consultorios c 
            where c.id in(74, 14, 72, 61, 75, 25, 13, 16, 68, 43, 77, 119) 
            order by c.nombre";
        $servicios = DB::select($servicios);
        return $servicios;
    }

    public function ListarServicioByIdUpss2($id_upss)
    {
        $servicio = "SELECT distinct co.id, co.nombre servicio, co.estado 
          FROM upsses as up
          INNER JOIN consultorios as co ON co.id = up.id_consultorio
          WHERE  up.estado = 1 
          And up.id_tipo_atencion = 3 and up.id = $id_upss
          ORDER BY servicio;";
        $servicio = DB::select($servicio);
        return $servicio;
    }
}
