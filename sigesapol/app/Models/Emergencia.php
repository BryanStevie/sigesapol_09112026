<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Emergencia extends Model
{

    protected $table = 'emergencias'; 
    protected $fillable = ['id_gestacion', 'id_puerperio'];



// AGREGANDO HORA Y FECHA ACTUAL TRAER EL ID_EMERGENCIA
function getByidHoraEgreso($id_emergencia)
{

    // Verificar si $id_emergencia no está vacío y es un número válido 
    if (!empty($id_emergencia) && is_numeric($id_emergencia)) {
        $cad = "SELECT * FROM emergencias WHERE id = ?";
        $data = DB::select($cad, [$id_emergencia]);
        return $data;
    } else {
        // Manejar el caso en el que $id_emergencia no es válido en este caso se valida directo la data si es nullo
        // fecha atencion y fecha alta estan nullos cuando ES NUEVO_HOSPITAl
        return null; // O cualquier otra acción adecuada
    }
}


    function getNroEmergencia($id_establecimiento)
    {
      $cad = "SELECT extract(year from now())||LPAD((count(*)::text),5,'0')nro_admision from emergencias where id_establecimiento = " . $id_establecimiento . " ";
      $data = DB::SELECT($cad);
      return $data[0]->nro_admision;
    }
    function getDescripcionPrioridad($id_prioridad)
    {
      $cad = "SELECT * From prioridades where id = " . $id_prioridad . "";
      $data = DB::SELECT($cad);
      return $data[0]->descripcion;
    }

    function getDescripcionTriaje($id_topico)
    {
      $cad = "SELECT * From triajes where id = " . $id_topico . "";
      $data = DB::SELECT($cad);
      return $data[0]->descripcion;
    }

  function getEmergencia($id_establecimiento)
  {
    $cad = "Select * From emergencias where id_establecimiento = " . $id_establecimiento . " and estado = 1 Order By id limit 100";
    $data = DB::select($cad);
    return $data;
  }

  public function AllRegistradorByFarmacia($id_farmacia)
  {
    $cad = "Select rv.id_user, upper(u.name) nombre, upper(u.paterno) paterno, upper(u.materno) materno From hospitalizaciones rv
Inner Join users u On rv.id_user = u.id
Where rv.id_farmacia =" . $id_farmacia . "
Group By rv.id_user, u.name, u.paterno, u.materno";
    $data = DB::select($cad);
    return $data;
  }

  public function AllRegistradorAltaByFarmacia($id_farmacia)
  {
    $cad = "Select rv.id_user_alta as id_user, upper(u.name) nombre, upper(u.paterno) paterno, upper(u.materno) materno From hospitalizaciones rv
Inner Join users u On rv.id_user_alta = u.id
Where rv.id_farmacia =" . $id_farmacia . "
Group By rv.id_user_alta, u.name, u.paterno, u.materno";
    $data = DB::select($cad);
    return $data;
  }

  public function AllHospitalizadosByFarmaAndFechaDesdeHasta($id_ipress, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_hospitalizacion = "", $iddispensador = "")
  {

    $cad = "select hospitalizaciones.*,users.name nombre_usuario, users.paterno as paterno_usuario,users.materno as materno_usuario,hospitalizaciones.created_at as fec_creacion
, c.nombre consultorio , camas.nombre nombre_cama, cuartos.nombre nombre_cuarto, medicos.nombre nombre_medico, medicos.paterno as paterno_medico,medicos.materno as materno_medico,diagnosticos.codigo as codigo_diagnostico, diagnosticos.nombre as nombre_diagnostico
from hospitalizaciones
Inner Join consultorios c On hospitalizaciones.id_consultorio=c.id
inner join users on hospitalizaciones.id_user=users.id
inner join farmacias on hospitalizaciones.id_farmacia=farmacias.id
inner join cuartos on hospitalizaciones.id_cuarto=cuartos.id
inner join camas on hospitalizaciones.id_cama=camas.id
inner join medicos on hospitalizaciones.id_medico=medicos.id
inner join diagnosticos on hospitalizaciones.id_diagnostico_inicial=diagnosticos.id
where farmacias.id_establecimiento=" . $id_ipress;
    if ($dni_beneficiario) :
      $cad .= " and hospitalizaciones.dni_beneficiario = '" . $dni_beneficiario . "'";
    //$fechaDesde="";$fechaHasta="";
    endif;

    if ($nro_hospitalizacion) :
      $cad .= " and hospitalizaciones.nro_hospitalizacion like '%" . $nro_hospitalizacion . "%'";
    //$fechaDesde="";$fechaHasta="";
    endif;

    if ($iddispensador != "")
      $cad .= " and hospitalizaciones.id_user = " . $iddispensador . "";

    if ($fechaDesde != "")
      $cad .= " and hospitalizaciones.fecha_ingreso >= '" . $fechaDesde . "'";
    if ($fechaHasta != "")
      $cad .= " and hospitalizaciones.fecha_ingreso <= '" . $fechaHasta . "'";

    $cad .= " and hospitalizaciones.estado=1
order By hospitalizaciones.fecha_ingreso desc";
    //echo $cad;
    $data = DB::select($cad);
    return $data;
  }

  public function AllHospitalizadosAltaByFarmaAndFechaDesdeHasta($id_ipress, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_hospitalizacion = "", $iddispensador_alta = "")
  {

    $cad = "select hospitalizaciones.*,users.name nombre_usuario, users.paterno as paterno_usuario,users.materno as materno_usuario,hospitalizaciones.created_at as fec_creacion
, c.nombre consultorio , camas.nombre nombre_cama, cuartos.nombre nombre_cuarto, medicos.nombre nombre_medico, medicos.paterno as paterno_medico,medicos.materno as materno_medico, diagnosticos.codigo as codigo_diagnostico, diagnosticos.nombre as nombre_diagnostico
from hospitalizaciones
Inner Join consultorios c On hospitalizaciones.id_consultorio=c.id
inner join users on hospitalizaciones.id_user_alta=users.id
inner join farmacias on hospitalizaciones.id_farmacia=farmacias.id
inner join cuartos on hospitalizaciones.id_cuarto=cuartos.id
inner join camas on hospitalizaciones.id_cama=camas.id
inner join medicos on hospitalizaciones.id_medico_alta=medicos.id
inner join diagnosticos on hospitalizaciones.id_diagnostico_final=diagnosticos.id
where farmacias.id_establecimiento=" . $id_ipress;
    if ($dni_beneficiario) :
      $cad .= " and hospitalizaciones.dni_beneficiario = '" . $dni_beneficiario . "'";
    //$fechaDesde="";$fechaHasta="";
    endif;

    if ($nro_hospitalizacion) :
      $cad .= " and hospitalizaciones.nro_hospitalizacion like '%" . $nro_hospitalizacion . "%'";
    //$fechaDesde="";$fechaHasta="";
    endif;

    if ($iddispensador_alta != "")
      $cad .= " and hospitalizaciones.id_user_alta = " . $iddispensador_alta . "";

    if ($fechaDesde != "")
      $cad .= " and hospitalizaciones.fecha_salida >= '" . $fechaDesde . "'";
    if ($fechaHasta != "")
      $cad .= " and hospitalizaciones.fecha_salida <= '" . $fechaHasta . "'";

    $cad .= " and hospitalizaciones.estado=2
order By hospitalizaciones.fecha_salida desc";
    //echo $cad;
    $data = DB::select($cad);
    return $data;
  }

  public function GetCountRegistroByUserAndFechaDesdeHasta($id_ipress, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_hospitalizacion = "", $iddispensador = "")
  {

    $cad = "select count(1) cantidad
from hospitalizaciones
inner join farmacias on hospitalizaciones.id_farmacia = farmacias.id
where farmacias.id_establecimiento=" . $id_ipress;

    if ($dni_beneficiario) :
      $cad .= " and hospitalizaciones.dni_beneficiario = '" . $dni_beneficiario . "'";
    //$fechaDesde="";$fechaHasta="";
    endif;

    if ($nro_hospitalizacion) :
      $cad .= " and hospitalizaciones.nro_hospitalizacion = '" . $nro_hospitalizacion . "'";
    //$fechaDesde="";$fechaHasta="";
    endif;

    if ($iddispensador != "")
      $cad .= " and hospitalizaciones.id_user = " . $iddispensador . "";

    if ($fechaDesde != "")
      $cad .= " and hospitalizaciones.fecha_ingreso >= '" . $fechaDesde . "'";
    if ($fechaHasta != "")
      $cad .= " and hospitalizaciones.fecha_ingreso <= '" . $fechaHasta . "'";
    $cad .= " and hospitalizaciones.estado=1";

    $data = DB::select($cad);
    return $data[0]->cantidad;
  }

  public function GetCountRegistroAltaByUserAndFechaDesdeHasta($id_ipress, $fechaDesdeSalida = "", $fechaHastaSalida = "", $dni_beneficiario = "", $nro_hospitalizacion = "", $iddispensador_alta = "")
  {

    $cad = "select count(1) cantidad
from hospitalizaciones
inner join farmacias on hospitalizaciones.id_farmacia = farmacias.id
where farmacias.id_establecimiento=" . $id_ipress;

    if ($dni_beneficiario) :
      $cad .= " and hospitalizaciones.dni_beneficiario = '" . $dni_beneficiario . "'";
    //$fechaDesde="";$fechaHasta="";
    endif;

    if ($nro_hospitalizacion) :
      $cad .= " and hospitalizaciones.nro_hospitalizacion = '" . $nro_hospitalizacion . "'";
    //$fechaDesde="";$fechaHasta="";
    endif;

    if ($iddispensador_alta != "")
      $cad .= " and hospitalizaciones.id_user_alta = " . $iddispensador_alta . "";

    if ($fechaDesdeSalida != "")
      $cad .= " and hospitalizaciones.fecha_salida >= '" . $fechaDesdeSalida . "'";
    if ($fechaHastaSalida != "")
      $cad .= " and hospitalizaciones.fecha_salida <= '" . $fechaHastaSalida . "'";

    $cad .= " and hospitalizaciones.estado=2";

    $data = DB::select($cad);
    return $data[0]->cantidad;
  }


  public function GetAdmisionByNroAdmision($nro_admision, $id_establecimiento)
  {

    $cad = "SELECT T1.*,T2.nombre consultorio,(case when turno='M' then 'MA&Ntilde;ANA' when turno='T' then 'TARDE' when turno='N' then 'NOCHE' end)turnos
,T3.nombre,T3.paterno,T3.materno,T3.dni,T3.colegiatura
,coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)actual_receta
FROM admisiones T1
inner join consultorios T2 on T1.id_consultorio = T2.id
inner join medicos T3 on T1.id_medico = T3.id
inner join farmacias T4 on T1.id_farmacia = T4.id 
where T1.nro_admision = '" . $nro_admision . "'";

    $cad .= " AND T4.id_establecimiento=" . $id_establecimiento;

    $data = DB::select($cad);
    return $data[0];
  }

  public function ShowByIdHospitalizacionId_farmacia($nro_hospitalizacion, $id_ipress)
  {
    $datos = DB::table('hospitalizaciones')
      ->join('farmacias', 'hospitalizaciones.id_farmacia', '=', 'farmacias.id')
      ->join('consultorios', 'hospitalizaciones.id_consultorio', '=', 'consultorios.id')
      ->join('medicos', 'hospitalizaciones.id_medico', '=', 'medicos.id')
      ->join('establecimientos', 'farmacias.id_establecimiento', '=', 'establecimientos.id')
      ->join('pabellones', 'hospitalizaciones.id_pabellon', '=', 'pabellones.id')
      ->join('pisos', 'hospitalizaciones.id_piso', '=', 'pisos.id')
      ->join('diagnosticos', 'hospitalizaciones.id_diagnostico_inicial', '=', 'diagnosticos.id')
      ->join('cuartos', 'hospitalizaciones.id_cuarto', '=', 'cuartos.id')
      ->join('camas', 'hospitalizaciones.id_cama', '=', 'camas.id')
      ->join('users', 'hospitalizaciones.id_user', '=', 'users.id')
      ->where('hospitalizaciones.id', '=', $nro_hospitalizacion)
      ->where('farmacias.id_establecimiento', '=', $id_ipress)
      ->where('hospitalizaciones.estado', '=', 1)
      ->select('hospitalizaciones.*', 'diagnosticos.nombre as nombre_diagnostico', 'diagnosticos.codigo as codigo_diagnostico', 'pabellones.nombre as nombre_pabellon', 'pisos.nombre as nombre_piso', 'cuartos.nombre as nombre_cuarto', 'camas.nombre as nombre_cama', 'establecimientos.nivel', 'establecimientos.nombre as ipress', 'establecimientos.codigo as codigoipress', 'consultorios.nombre as consultorio', 'medicos.nombre as nombre_medico', 'medicos.paterno as paterno_medico', 'medicos.materno as materno_medico', 'users.name as nombre_usuario', 'users.paterno as paterno_usuario', 'users.materno as materno_usuario', 'hospitalizaciones.created_at as fecha_creacion', 'nro_hospitalizacion', 'fecha_ingreso')
      ->get();
    return $datos;
  }

  public function ShowByIdAltaPacienteId_farmacia($id_hospitalizacion, $id_ipress)
  {
    $datos = DB::table('hospitalizaciones')
      ->join('farmacias', 'hospitalizaciones.id_farmacia', '=', 'farmacias.id')
      ->join('consultorios', 'hospitalizaciones.id_consultorio', '=', 'consultorios.id')
      ->join('medicos', 'hospitalizaciones.id_medico_alta', '=', 'medicos.id')
      ->join('establecimientos', 'farmacias.id_establecimiento', '=', 'establecimientos.id')
      ->join('pabellones', 'hospitalizaciones.id_pabellon', '=', 'pabellones.id')
      ->join('pisos', 'hospitalizaciones.id_piso', '=', 'pisos.id')
      ->join('diagnosticos', 'hospitalizaciones.id_diagnostico_final', '=', 'diagnosticos.id')
      ->join('cuartos', 'hospitalizaciones.id_cuarto', '=', 'cuartos.id')
      ->join('camas', 'hospitalizaciones.id_cama', '=', 'camas.id')
      ->join('users', 'hospitalizaciones.id_user_alta', '=', 'users.id')
      ->where('hospitalizaciones.id', '=', $id_hospitalizacion)
      ->where('farmacias.id_establecimiento', '=', $id_ipress)
      ->where('hospitalizaciones.estado', '=', 2)
      ->select('hospitalizaciones.*', 'diagnosticos.nombre as nombre_diagnostico', 'diagnosticos.codigo as codigo_diagnostico', 'pabellones.nombre as nombre_pabellon', 'pisos.nombre as nombre_piso', 'cuartos.nombre as nombre_cuarto', 'camas.nombre as nombre_cama', 'establecimientos.nivel', 'establecimientos.nombre as ipress', 'establecimientos.codigo as codigoipress', 'consultorios.nombre as consultorio', 'medicos.nombre as nombre_medico', 'medicos.paterno as paterno_medico', 'medicos.materno as materno_medico', 'users.name as nombre_usuario', 'hospitalizaciones.condicion as condicion', 'users.paterno as paterno_usuario', 'users.materno as materno_usuario', 'hospitalizaciones.created_at as fecha_creacion', 'nro_hospitalizacion', 'fecha_ingreso')
      ->get();
    return $datos;
  }

  public function GetAdmisionByIdFarmaciaAndDni($id_establecimiento, $dni)
  {

    $cad = "SELECT T1.*
,coalesce(T1.cantidad_receta,1)cantidad_receta
,coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)actual_receta
,T2.nombre consultorio,(case when turno='M' then 'MA&Ntilde;ANA' when turno='T' then 'TARDE' when turno='N' then 'NOCHE' when turno='MD' then 'MADRUGADA' end)turnos
,T3.nombre,T3.paterno,T3.materno,T3.dni,T3.colegiatura
FROM admisiones T1
inner join consultorios T2 on T1.id_consultorio = T2.id
inner join medicos T3 on T1.id_medico = T3.id 
inner join farmacias T4 on T1.id_farmacia = T4.id 
where T1.dni_beneficiario = '" . $dni . "'
and T4.id_establecimiento = " . $id_establecimiento;

    $cad .= " AND coalesce(T1.cantidad_receta,1) > coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)";
    $cad .= " ORDER BY T1.id desc ";
    $data = DB::select($cad);
    return $data;
  }

  public function GetCamaById($id_establecimiento, $id_hospitalizacion)
  {

    $cad = "select H.* FROM Hospitalizaciones H inner join Farmacias F on F.id = H.id_farmacia where H.id = '" . $id_hospitalizacion . "' and F.id_establecimiento = '" . $id_establecimiento . "'";
    $data = DB::select($cad);

    return $data[0];
  }

  public function GetBuscaPaciente($id_establecimiento, $id_hospitalizacion)
  {

    $cad = "select H.* FROM Hospitalizaciones H inner join Farmacias F on F.id = H.id_farmacia where H.id = '" . $id_hospitalizacion . "' and F.id_establecimiento = '" . $id_establecimiento . "'";
    $data = DB::select($cad);

    return $data[0];
  }
 
  public function listar_triaje_emergencia_ajax($p)
  { 
    //$p[0] id_establecimiento
    //$p[1] fecha_ini
    //$p[2] fecha_fin
    //$p[3] dni_beneficiario
    //$p[4] nombre_beneficiario
    //$p[5] estado
    //$p[6] NumeroPagina
    //$p[7] NumeroRegistros
    //$p[8] opcion 

    $estado= $p[5];
    $fechaInicio = $p[1];
    $fechaFinal = $p[2];
    $dniPaciente = $p[3];
    $nombrePaciente = $p[4];
    $NumeroPagina = $p[6];
    $NumeroRegistros = $p[7];

    if($p[5] ==-1||$p[5]==-2){
      $estado_TICKET=1;
      if($estado==-2){
        $estado_TICKET=0;
      }
     
       
      if(Auth::user()->flagservicios==1){
 
        // ESTA CONSULTA SIEMPRE LLEGA NULL
        $datos = DB::table('totem_opciones')->where('id_consultorio', '=', Auth::user()->id_consultorio_ticket)->first();
         
        $where = " WHERE ";
        if ($fechaInicio != "" && $fechaFinal != "")  $where .= " CAST(tickets.created_at AS DATE) BETWEEN '".$fechaInicio."' AND '".$fechaFinal."'  ";
        if ($dniPaciente != "")                       $where .= " AND tickets.dni_reniec = '".$dniPaciente."' ";
        if ($nombrePaciente != "")                    $where .= " AND tickets.nombres_reniec ILIKE '%".$nombrePaciente."%' ";
  
        $cad = "
        SELECT 
        ROW_NUMBER() OVER(ORDER BY tickets.created_at DESC) AS num,
        tickets.motivo_anulacion,tickets.id_estado_tv,
        tickets.id as cod_emergencia, 
        tickets.cantidad_llamada,
        to_char(tickets.created_at::date,'DD/MM/YYYY') AS fecha_triaje, 
        to_char(tickets.created_at, 'HH:MI:SS AM')  AS hora_triaje,  tickets.id_emergencia_a as id_emergencia, 
        case 
          when tickets.id_asegurado = 0 then tickets.dni_reniec
          else a.nro_doc_ident
          end as nro_doc_ident,
        case 
          when tickets.id_asegurado = 0 then tickets.nombres_reniec
          else concat(a.nombre,'',a.paterno,'',a.materno) 
        end as asegurado ,  

        CONCAT (tickets.letra ,'',tickets.numero) as cod_totem,
        CONCAT (tickets.letra ,'',tickets.numero) as cod_totem,

        to_char(tickets.anulado_at::date,'DD/MM/YYYY') AS fecha_triaje_anulado, 
        to_char(tickets.anulado_at, 'HH:MI:SS AM')  AS hora_triaje_anulado,
        CONCAT(user_adm.paterno,' ',user_adm.materno,' ',user_adm.name) AS anulado_us, 

        case 
        	when seleccionado_us is null then CONCAT(user_llamado.paterno,' ',user_llamado.materno,' ',user_llamado.name)
        	when llamado_us is null then CONCAT(user_selec.paterno,' ',user_selec.materno,' ',user_selec.name)
        end seleccionado_us,

        totem_opciones.nombre as totem_nombre
        FROM tickets as tickets 
        INNER JOIN emergencias em ON  em.id_ticket_admision = tickets.id 
        LEFT JOIN asegurados a on a.id= tickets.id_asegurado  
        LEFT JOIN users user_adm ON tickets.anulado_us = user_adm.id 
        LEFT JOIN users user_selec ON tickets.seleccionado_us = user_selec.id 
        LEFT JOIN users user_llamado ON tickets.llamado_us= user_llamado.id
        LEFT JOIN totem_opciones as totem_opciones on tickets.id_totem_opcion =totem_opciones.id
        
        ";
        $cad .= $where ." AND tickets.flag_triaje='1' AND tickets.flag_admision='0' AND tickets.atendido_at is null AND tickets.id_totem='6'  AND tickets.estado='$estado_TICKET' 
                          AND tickets.id_totem_opcion='$datos->id' AND tickets.id_emergencia_a IS NOT NULL "; 
        
        $cad_total =  $cad ; 
        if($NumeroPagina == 0 && $NumeroRegistros==0 ){
          $cad .= " ORDER BY tickets.id desc ";
        }else{
          $cad .= " ORDER BY tickets.id desc LIMIT " . $NumeroRegistros . " OFFSET " . ($NumeroPagina - 1) * $NumeroRegistros . "; ";
        }  
        
        $data = DB::SELECT($cad);
        $totalrows = count(DB::SELECT($cad_total));  
        return array('data' => $data, 'totalrows' => $totalrows);   
  
      }else{
          
        $id_establecimiento = Auth::user()->id_establecimiento;
        $id_consultorio = auth()->user()->id_consultorio_ticket;
        $id_totem_array = DB::select('select t.id from totems t 
        LEFT JOIN corredores c on t.id_corredor = c.id 
        LEFT JOIN corredores_detalle cd on cd.id_corredor = c.id 
        LEFT JOIN users u on u.id_corredores_detalle = cd.id 
        where u.id ='.auth()->user()->id); 
        $id_totem = $id_totem_array[0]->id; 

        
        $where = " WHERE ";
        if ($fechaInicio != "" && $fechaFinal != "")  $where .= " CAST(t.created_at AS DATE) BETWEEN '".$fechaInicio."' AND '".$fechaFinal."'  ";
        if ($dniPaciente != "")                       $where .= " AND t.dni_reniec = '".$dniPaciente."' ";
        if ($nombrePaciente != "")                    $where .= " AND t.nombres_reniec ILIKE '%".$nombrePaciente."%' ";

        $estado = "0,1,2,4";

        $cad = "select 
                ROW_NUMBER() OVER(ORDER BY t.created_at DESC) AS num, 
                t.id as cod_emergencia, t.id_estado_tv,
                to_char(t.created_at::date,'DD/MM/YYYY') AS fecha_triaje, 
                to_char(t.created_at, 'HH:MI:SS AM')  AS hora_triaje,
                CONCAT (t.letra ,'',t.numero) as cod_totem,
                to2.nombre as totem_nombre,

                to_char(t.anulado_at::date,'DD/MM/YYYY') AS fecha_triaje_anulado, 
                to_char(t.anulado_at, 'HH:MI:SS AM')  AS hora_triaje_anulado,
                
                CONCAT(user_adm.paterno,' ',user_adm.materno,' ',user_adm.name) AS anulado_us,
                
                case 
                  when seleccionado_us is null then CONCAT(user_llamado.paterno,' ',user_llamado.materno,' ',user_llamado.name)
                  when llamado_us is null then CONCAT(user_selec.paterno,' ',user_selec.materno,' ',user_selec.name)
                end seleccionado_us,

                t.id_emergencia_a as id_emergencia, 
                case 
                  when t.id_asegurado = 0 then t.dni_reniec
                  else a.nro_doc_ident
                  end as nro_doc_ident,
                case 
                  when t.id_asegurado = 0 then t.nombres_reniec
                  else concat(a.nombre,' ',a.paterno,' ',a.materno) 
                end as asegurado ,  
                case 
                  when t.id_asegurado = 0 then t.dni_reniec
                  else a.nro_doc_ident
                  end as nro_doc_ident, 
 
                  case when t.id_estado_tv = 0 then 'Pendiente' when t.id_estado_tv = 1 then 'Llamando' when t.id_estado_tv = 2 then 'Pospuesto' when t.id_estado_tv = 3 then 'Atendido' end AS estado,
                  t.id_estado_tv,
                  t.letra,
                  t.numero,t.cantidad_llamada,t.motivo_anulacion,
                  to2.nombre totem_opcion,
                  t.id_asegurado,
                  case when t.id_asegurado = 0 or t.id_asegurado isnull then 
                  split_part(t.nombres_reniec,',',2) ||''|| split_part(t.nombres_reniec,',',3) ||', '|| split_part(t.nombres_reniec,',',1)
                  else coalesce(a.paterno||''||a.materno||', '||a.nombre, '')end::varchar as paciente,
                  to_char(t.created_at, 'HH24:MI PM')::varchar hora_ticket,
                  coalesce(c.id, 0) id_cita,
                  to_char(t.created_at, 'dd/mm/yyyy')::varchar AS fecha_ticket,
                  t.id_estado_tv,
                  coalesce(t.cantidad_llamada, 0) AS cantidad_llamada,
                  CASE WHEN to2.id = 2 THEN 'NO' ELSE 'SI' END::varchar AS citado, 
                  t.ventanilla as id_ventanilla, v.nombre as nombre_ventanilla, to2.id_consultorio, t.llamado_us, t.llamado_at 

                FROM tickets t   
                
                LEFT JOIN asegurados a ON t.id_asegurado = a.id 
                LEFT JOIN citas c on t.id_cita = c.id
                LEFT JOIN users user_adm ON t.anulado_us = user_adm.id
                LEFT JOIN users user_selec ON t.seleccionado_us = user_selec.id 
                LEFT JOIN users user_llamado ON t.llamado_us= user_llamado.id 
                LEFT JOIN ventanillas v on t.ventanilla = v.id
                LEFT JOIN totem_opciones to2 on t.id_totem_opcion = to2.id 
                LEFT JOIN totems t2 on t.id_totem = t2.id
              "; 
              
              $cad .= $where ."  AND t.flag_triaje='1'  AND t.flag_admision='0' AND t.atendido_at is null AND t.estado=".$estado_TICKET."  AND t.id_emergencia_a IS NOT NULL  
                AND t.id_estado_tv in(".$estado.")  AND t2.id_establecimiento = ".$id_establecimiento." AND to2.id_consultorio = ".$id_consultorio." 
                AND t.id_totem = ".$id_totem." ";
 
                $cad_total =  $cad ; 
                if($NumeroPagina == 0 && $NumeroRegistros==0 ){
                  $cad .= " ORDER BY t.created_at, t.numero desc ";
                }else{
                  $cad .= " ORDER BY t.created_at, t.numero desc LIMIT " . $NumeroRegistros . " OFFSET " . ($NumeroPagina - 1) * $NumeroRegistros . "; ";
                }  
        $data = DB::select($cad);
        $totalrows = count(DB::SELECT($cad_total)); 
        return array('data' => $data, 'totalrows' => $totalrows); 

      } 
    }else{

      return $this->readFunctionPostgres('sp_listar_triaje_emergencia', $p);

    }
 
      
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

  public function GetBuscaEmergenciaTriaje($id, $estado)
  {
    $p[] = $id;
    $p[] = $estado;
    $data = $this->readFunctionPostgres('sp_consulta_emergencia', $p);
    return $data[0];
    
    /*
    $cad = "select e.dni_beneficiario , e.fecha_ingreso, e.hora_ingreso, p.descripcion prioridad , t.descripcion, 
          a.nombre, a.paterno, a.materno, e.tipo_beneficiario, e.estado, e.id, a.sexo, a.fecha_nac, a.domicilio, 
          a.telefono, a.id id_asegurado, a.fecha_nac, a.foto, e.tipo_beneficiario, 
          e.id_forma_ingreso, e.id_estado_ingreso, e.id_motivo_ingreso, e.id_departamento, e.id_provincia, e.id_distrito,
          e.dni_conducido, e.nombre_conducido, e.id_tipo_beneficiario_conducido as tipo_conducido , e.telf_conducido, observacion_admision 
          from emergencias as e
          left join asegurados a on e.id_asegurado=a.id
          left join triajes t on e.id_triaje=t.id
          left join prioridades p on e.id_prioridad=p.id
          where e.estado=1 and e.id = " . $id;
    $data = DB::select($cad);

    return $data[0];
    */
  }

  public function generar_cups_pnp($id_forma_ingreso){
    
    $cad = "SELECT 
    'E'||(((((
     substr(e.fecha_registro_ingreso::text, 3, 2) || ''::text) || 
     substr(e.fecha_registro_ingreso::text, 6, 2)) || ''::text) || 
     substr(e.fecha_registro_ingreso::text, 9, 2)) || ''::text) ||
        CASE
            WHEN LENGTH(e.secuencia::text) = 1 THEN '00000'::text || e.secuencia::text
            WHEN LENGTH(e.secuencia::text) = 2 THEN '0000'::text || e.secuencia::text
            WHEN LENGTH(e.secuencia::text) = 3 THEN '000'::text || e.secuencia::text
            WHEN LENGTH(e.secuencia::text) = 4 THEN '00'::text || e.secuencia::text
            WHEN LENGTH(e.secuencia::text) = 5 THEN '0'::text || e.secuencia::text
            ELSE e.secuencia::text
         END AS cups_pnp
      FROM emergencias as e where e.id=".$id_forma_ingreso." 
    ";
    $data = DB::select($cad);

    return $data;
  }
  public static function RegistrarAdmisionEmergencia($id_forma_ingreso, $id_estado_ingreso, $id_motivo_ingreso, $id_departamento,
   $id_provincia, $id_distrito, $dni_conducido, $conducido, $id_tipo_beneficiario_conducido, $telfconducido, $observacion_admision, 
   $oficial_guardia, $direccion_referencial, $telefono, $id, $id_asegurado, $id_departamento_evac, $numero_oficio, 
   $unidad_policial, $id_situacion_evac, $diagnostico, $observacion, $id_violencia,  $id_tipo_violencia, $id_comisaria, 
   $id_situacion, $fecha_accidente, $hora_accidente, $DepartamentoAc, $ProvinciaAc, $DistritoAc, $lugar_accidente, $id_unidad,
   $user_id,$id_establecimiento,$id_tipo_beneficiario,$tipo_doc_cond)
  {
    $cad = "Select sp_insert_admision_emergencia2(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_forma_ingreso, $id_estado_ingreso, $id_motivo_ingreso, $id_departamento, $id_provincia, $id_distrito, 
    $dni_conducido, $conducido, $id_tipo_beneficiario_conducido, $telfconducido, $observacion_admision, $oficial_guardia, $direccion_referencial,
     $telefono, $id, $id_asegurado, $id_departamento_evac, $numero_oficio, $unidad_policial, $id_situacion_evac, $diagnostico, $observacion, 
     $id_violencia,  $id_tipo_violencia, $id_comisaria, $id_situacion, $fecha_accidente, $hora_accidente, $DepartamentoAc, $ProvinciaAc, $DistritoAc, 
     $lugar_accidente, $id_unidad,$user_id,$id_establecimiento, $id_tipo_beneficiario, $tipo_doc_cond));

    return $data[0]->sp_insert_admision_emergencia2;
  }



  public function InsertTriaje(
    $dni_beneficiario, $id_prioridad, $id_asegurado, $id_triaje, $id_medico_triaje, 
    $temperatura, $sa02_porcentaje, $presion_arterial_x, $presion_arterial_y, $talla_cm, 
    $tamano_cintura_cm, $frecuencia_respiratoria, $peso_kg, $frecuencia_cardiaca_fetal, $frecuencia_cardiaca, 
    $fi02, $id_establecimiento, $observacion_triaje, $id_padre_paciente, $id_madre_paciente, 
    $estado,$id_forma_ingreso ,$id_estado_ingreso ,$id_motivo_ingreso, $fecha_registro_ingreso,$hora_registro_ingreso, 
    $registro_manual, $es_gestante) {

    $cad = "Select sp_emergencias_triaje_insert3(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $new = DB::select($cad, array($dni_beneficiario, $id_prioridad, $id_asegurado, $id_triaje, $id_medico_triaje, 
    $temperatura, $sa02_porcentaje, $presion_arterial_x,$presion_arterial_y,  $talla_cm, $tamano_cintura_cm, 
    $frecuencia_respiratoria, $peso_kg, $frecuencia_cardiaca_fetal, $frecuencia_cardiaca, $fi02, $id_establecimiento, 
    $observacion_triaje, $id_padre_paciente, $id_madre_paciente, $estado, $id_forma_ingreso, $id_estado_ingreso, 
    $id_motivo_ingreso, $fecha_registro_ingreso,$hora_registro_ingreso, $registro_manual, $es_gestante)); 
    return $new;
  }

public function GetBuscaEmergenciaAdmision($id, $id_medico) {
        
        if($id > 0){

        $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,nro_historia,t2.sexo,t1.tipo_beneficiario parentesco,fecha_nac,tipo_doc_ident,
        t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.hora_ingreso hora,t1.id_establecimiento,t3.nombre establecimiento,t4.id_servicio,t4.id_sub_consultorio id_sub_consultorio,t10.descripcion servicio,t4.descripcion consultorio,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno
        ,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion, t1.secuencia, t2.telefono, t2.domicilio, t1.presion_arterial_x, t1.presion_arterial_y, t1.frecuencia_cardiaca, t1.frecuencia_respiratoria, t1.fi02, t1.sa02_porcentaje, t1.temperatura, t1.talla_cm, t1.peso_kg, t1.frecuencia_cardiaca_fetal, t1.fecha_firma_admision
        FROM emergencias t1
        inner join asegurados t2 on t1.id_asegurado = t2.id 
        inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
        inner join establecimientos t3 on t1.id_establecimiento=t3.id
        inner join triajes t4 on t1.id_triaje=t4.id
        inner join prioridades t10 on t1.id_prioridad=t10.id
        left join medicos t5 on t5.id=".$id_medico." 
        left join especializaciones t7 on t5.id_especializacion=t7.id
        left join profesiones t8 on t5.id_profesion=t8.id
        Where t1.estado=2 and t1.id=".$id; 

            $data = DB::select($cad);
            return $data[0];
        }
    }

    public function GetCargarEmergenciaTriaje($id)
    {

      $cad = "select e.temperatura , e.sa02_porcentaje, e.presion_arterial_x, e.presion_arterial_y , e.talla_cm, e.tamano_cintura_cm, e.frecuencia_respiratoria, e.peso_kg, e.frecuencia_cardiaca_fetal, e.frecuencia_cardiaca, fi02
            from emergencias as e
            where e.estado=2 and e.id = " . $id;
      $data = DB::select($cad);

    return $data[0];
    }

    public function GetBuscaEmergencia($id, $id_medico, $estado) {        
      if($id > 0){
        $cad = "select t1.id,t1.id_asegurado,t1.estado,t1.id_gestacion,t1.id_puerperio,as_t.nombre nombre_titular,as_t.paterno paterno_titular,as_t.materno materno_titular,as_t.tipo_doc_ident tipo_doc_titular,as_t.nro_doc_ident nro_doc_ident_titular,as_t.sexo sexo_titular,as_t.cip cip_titular,as_t.grado grado_titular,as_t.fecha_nac fecha_nac_titular,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.nombre asegurado_nombre,t2.fecha_nac,t2.tipo_doc_ident,t2.nro_doc_ident,t9.nro_historia,t2.sexo,tb.descripcion parentesco,t1.fecha_ingreso dia,t1.hora_ingreso hora,t1.id_establecimiento, t2.id_asegurado_titular,t3.nombre establecimiento,t4.id_servicio,t4.id_sub_consultorio id_sub_consultorio,t10.descripcion nom_prioridad,t4.descripcion consultorio,t5.id id_medico,td.descripcion tipo_doc_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno, date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, t2.grado, t2.cip
        ,t5.dni nro_dni_medico,t5.colegiatura colegiatura_medico,t5.rne rne_medico,t7.nombre especializacion_medico,t8.nombre profesion_medico, t1.temperatura, t1.sa02_porcentaje,t1.presion_arterial_x,t1.presion_arterial_y,t1.talla_cm,t1.tamano_cintura_cm,t1.frecuencia_respiratoria,t1.peso_kg,t1.frecuencia_cardiaca,t1.id_prioridad,t1.id_triaje,t1.created_at,t1.fi02,t1.secuencia, t1.observacion_triaje, t1.dni_conducido, t1.nombre_conducido, t1.telf_conducido, t1.id_tipo_parentesco, t2.situacion_paciente
        ,t1.cod_siteds_susalud, t1.updated_at_cod_siteds, t1.updated_us_cod_siteds, CASE WHEN t1.updated_at_cod_siteds::DATE < CURRENT_DATE  THEN 0 ELSE 1 END AS is_siteds_generate_today,t8.id as id_profesion
        FROM emergencias t1
        inner join asegurados t2 on t1.id_asegurado = t2.id 
        /*left join asegurados as_t on t1.dni_titular = as_t.nro_doc_ident*/
        left join asegurados as_t on t2.id_asegurado_titular = as_t.id
        inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
        inner join establecimientos t3 on t1.id_establecimiento = t3.id
        inner join triajes t4 on t1.id_triaje = t4.id
        inner join prioridades t10 on t1.id_prioridad = t10.id
        left join medicos t5 on t5.id=".$id_medico." 
        left join tipo_documento_identidad td on td.id = t5.tipo_documento
        left join especializaciones t7 on t5.id_especializacion = t7.id
        left join profesiones t8 on t5.id_profesion = t8.id
        left join tipo_beneficiarios tb on tb.id = t1.id_tipo_beneficiario
        Where t9.id_estado_reg = 1 and t1.estado=". $estado ." and t1.id=".$id;
            $data = DB::select($cad);
            return $data[0];
        }
    }
    
    public function GetBuscaEmergencia2($id, $id_medico) {        
      if($id > 0){
        $cad = "select t1.id,t1.id_asegurado,t1.id_gestacion,t1.id_puerperio,t2.nombre nombre_titular, t2.nombre asegurado_nombre,t2.paterno paterno_titular,t2.paterno asegurado_paterno,t2.materno materno_titular,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,tb.descripcion parentesco,fecha_nac,tipo_doc_ident,t2.tipo_doc_ident tipo_doc_ident_titular, t2.nro_doc_ident,t2.nro_doc_ident nro_doc_ident_titular,t1.fecha_ingreso dia,t1.hora_ingreso hora,t1.id_establecimiento, t2.id_asegurado_titular,t3.nombre establecimiento,t4.id_servicio,t4.id_sub_consultorio id_sub_consultorio,t10.descripcion nom_prioridad,t4.descripcion consultorio,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno, date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, t2.grado, t2.cip
        ,t5.dni nro_dni_medico,t5.colegiatura colegiatura_medico,t5.rne rne_medico,t7.nombre especializacion_medico,t8.nombre profesion_medico, t1.temperatura, t1.sa02_porcentaje,t1.presion_arterial_x,t1.presion_arterial_y,t1.talla_cm,t1.tamano_cintura_cm,t1.frecuencia_respiratoria,t1.peso_kg,t1.frecuencia_cardiaca,t1.id_prioridad,t1.id_triaje,t1.created_at,t1.fi02,t1.secuencia, t1.observacion_triaje, t1.dni_conducido, t1.nombre_conducido, t1.telf_conducido, t1.id_tipo_parentesco, t2.situacion_paciente
        ,t1.cod_siteds_susalud, t1.updated_at_cod_siteds, t1.updated_us_cod_siteds, CASE WHEN t1.updated_at_cod_siteds::DATE < CURRENT_DATE  THEN 0 ELSE 1 END AS is_siteds_generate_today
        FROM emergencias t1
        inner join asegurados t2 on t1.id_asegurado = t2.id 
        inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
        inner join establecimientos t3 on t1.id_establecimiento=t3.id
        inner join triajes t4 on t1.id_triaje=t4.id
        inner join prioridades t10 on t1.id_prioridad=t10.id
        left join medicos t5 on t5.id=".$id_medico." 
        left join especializaciones t7 on t5.id_especializacion=t7.id
        left join profesiones t8 on t5.id_profesion=t8.id
        left join tipo_beneficiarios tb on tb.id=t1.id_tipo_beneficiario
        Where t1.id=".$id;
            $data = DB::select($cad);
            return $data[0];
        }
    }

    public function GetBuscaEmergenciaEvaluacion($id, $id_medico, $estado) {
        
      if($id > 0){

        $cad = "select t1.id_asegurado,t1.id_tipo_beneficiario,at.nombre nombre_titular,at.paterno paterno_titular,at.materno materno_titular,at.fecha_nac fecha_nac_titular,at.tipo_doc_ident tipo_doc_ident_titular,at.nro_doc_ident nro_doc_ident_titular,at.sexo sexo_titular,at.grado grado_titular,at.cip cip_titular,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,tb.descripcion parentesco,t2.fecha_nac,t2.tipo_doc_ident,t2.nro_doc_ident,t1.fecha_ingreso dia,t1.hora_ingreso hora,t1.id_establecimiento,t3.nombre establecimiento,t4.id_servicio,t4.id_sub_consultorio id_sub_consultorio,t10.descripcion nom_prioridad,t4.descripcion consultorio,t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno, date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, t2.grado, t2.id_asegurado_titular, t2.cip, t2.grado
        ,t5.dni nro_dni_medico,t5.colegiatura colegiatura_medico,t5.rne rne_medico,t7.nombre especializacion_medico,t8.nombre profesion_medico, t1.temperatura, t1.sa02_porcentaje,t1.presion_arterial_x,t1.presion_arterial_y,t1.talla_cm,t1.tamano_cintura_cm,t1.frecuencia_respiratoria,t1.peso_kg,t1.frecuencia_cardiaca,t1.id_prioridad,t1.id_triaje,t1.created_at,t1.fi02,t1.secuencia, t1.ubica_topico, t1.ubica_prioridad, t3.codigo as codigo_establecimiento, t2.id_tipo_doc_ident as id_tipo_doc_ident,t1.dni_conducido,t1.nombre_conducido,t1.id_tipo_parentesco,t2.situacion_paciente
        FROM emergencias t1
        left join asegurados t2 on t1.id_asegurado = t2.id 
        left join asegurados at on t2.id_asegurado_titular = at.id
        left join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
        left join establecimientos t3 on t1.id_establecimiento=t3.id
        left join triajes t4 on t1.ubica_topico=t4.id
        left join prioridades t10 on t1.ubica_prioridad=t10.id
        left join medicos t5 on t5.id=".$id_medico." 
        left join especializaciones t7 on t5.id_especializacion=t7.id
        left join profesiones t8 on t5.id_profesion=t8.id
        left join tipo_beneficiarios tb on tb.id=t1.id_tipo_beneficiario
        Where t1.estado=". $estado ." and t1.id=".$id;

            $data = DB::select($cad);

        if(count($data)>0):
          return $data[0];
        else:
          return $data;
        endif;
          }
    }
        public function GetCargarEmergencia($id,$estado)
    {

      $cad = "select e.temperatura , e.sa02_porcentaje, e.presion_arterial_x, e.presion_arterial_y , e.talla_cm, e.tamano_cintura_cm, e.frecuencia_respiratoria, e.peso_kg, e.frecuencia_cardiaca_fetal, e.frecuencia_cardiaca, fi02
            from emergencias as e
            where e.estado=". $estado ." and e.id = " . $id;
      $data = DB::select($cad);
    return $data[0];
    }

    public function GetBuscaPacienteEmergencia($id, $estado)
  {
    $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,tb.descripcion parentesco,t2.fecha_nac,t2.tipo_doc_ident,t1.cama_estado cama,t2.grado,
        t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.hora_ingreso hora,t1.id_establecimiento,t3.nombre establecimiento,t4.id_servicio,t4.id_sub_consultorio id_sub_consultorio,t10.descripcion servicio,t4.descripcion consultorio,
         t1.dni_conducido, t1.nombre_conducido, t1.telf_conducido, t1.id_tipo_parentesco
        FROM emergencias t1
        inner join asegurados t2 on t1.id_asegurado = t2.id 
        inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
        inner join establecimientos t3 on t1.id_establecimiento=t3.id
        inner join triajes t4 on t1.id_triaje=t4.id
        inner join prioridades t10 on t1.id_prioridad=t10.id
        inner join tipo_beneficiarios tb on t1.id_tipo_beneficiario = tb.id
        Where 1 = 1 and
        t1.estado=". $estado ." 
        and t1.id=".$id; 

        $data = DB::select($cad);
    if(count($data)>0):    
      return $data[0];
    else:
      return $data;
    endif;
    
  }

  public function GetBuscaPacienteEmergenciaEvaluacion($id, $estado)
  {
    $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,tb.descripcion parentesco,t2.fecha_nac,t2.tipo_doc_ident,t1.cama_estado cama,t2.grado,
        t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.hora_ingreso hora,t1.id_establecimiento,t3.nombre establecimiento,t4.id_servicio,t4.id_sub_consultorio id_sub_consultorio,t10.descripcion servicio,t4.descripcion consultorio 
        FROM emergencias t1
        inner join asegurados t2 on t1.id_asegurado = t2.id 
        inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
        inner join establecimientos t3 on t1.id_establecimiento=t3.id
        inner join triajes t4 on t1.ubica_topico=t4.id
        inner join prioridades t10 on t1.ubica_prioridad=t10.id
        inner join tipo_beneficiarios tb on t1.id_tipo_beneficiario = tb.id
        Where 1 = 1 and
        t1.estado=". $estado ." 
        and t1.id=".$id; 

        $data = DB::select($cad);
    return $data[0];
    
  }
  
  public function GetBuscaPacienteEmergenciaEvaluacionX($id)
  {
    $cad = "select t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,tb.descripcion parentesco,t2.fecha_nac,t2.tipo_doc_ident,t1.cama_estado cama,t2.grado,
        t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.hora_ingreso hora,t1.id_establecimiento,t3.nombre establecimiento,t4.id_servicio,t4.id_sub_consultorio id_sub_consultorio,t10.descripcion servicio,t4.descripcion consultorio 
        FROM emergencias t1
        inner join asegurados t2 on t1.id_asegurado = t2.id 
        inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
        inner join establecimientos t3 on t1.id_establecimiento=t3.id
        inner join triajes t4 on t1.ubica_topico=t4.id
        inner join prioridades t10 on t1.ubica_prioridad=t10.id
        inner join tipo_beneficiarios tb on t1.id_tipo_beneficiario = tb.id
        Where 1 = 1
        and t1.id=".$id; 

        $data = DB::select($cad);
    return $data[0];
    
  }

  public function getIdPrestacion($id_ext){
        $cad = "Select * From prestaciones where id_ext = ".$id_ext;
        $data = DB::select($cad);
        return $data[0];
    }

  public function getIdPrestacionid_Ext($id){
    $datos = DB::table('prestaciones')->where('id_tipo_atencion', 2)->where('id_estado_reg', 1)->where('id_ext', $id)->first();
    return $datos;
  }
  
  public function getIdPrestacionHospitalizacion($id_ext){
        $cad = "Select * From prestaciones where id_tipo_atencion = 3 and id_ext = ".$id_ext;
        $data = DB::select($cad);
        return $data[0];
    }

  public function ActualizaEstadoEmergencia($id_emergencia, $estado) {

    $date = Carbon::now();
    $emergencia = Emergencia::where('id', $id_emergencia)->first(); 
    if($estado!=0){
      $emergencia->estado = $estado;  
    }
    $emergencia->fecha_atencion = $date;
    $emergencia->save();
    
    return true;
    
  }

  public function ActualizaEstadoEmergenciaAtencion($id_emergencia, $estado, $id_user, $fecha_hora_atencion = null) {

    $date = Carbon::now();
    $emergencia = Emergencia::where('id', $id_emergencia)->first(); 
    if($estado!=0){
      $emergencia->estado = $estado;  
    }
    if ($fecha_hora_atencion):
      $emergencia->fecha_atencion = $fecha_hora_atencion;
    else:
    $emergencia->fecha_atencion = $date;
    endif;
    $emergencia->id_user_atencion = $id_user;
    return $emergencia->save();
    
    // return true;
    
  }
  public function ActualizaUbicacionEstado($id_emergencia,$prioridadesopciones,$topicosprioridad,$fecha_inicio_estado,$hora,$id_cama, $id_establecimiento=0) {

    $date = Carbon::now();
    $emergencia = Emergencia::where('id', $id_emergencia)->first(); 
    // Cama actual la desocupamos
    if ($id_establecimiento == 76):

      /**
       * Validamos que el campo de cama_estado (id_cama) esté registrado (Ya cuente con atencion)
       * por lo tanto, solo las evoluciones ingresarian a este condición
       */
      if($emergencia->cama_estado != null) {
        $cama = Cama::where('id', $emergencia->cama_estado)->first();
        $cama->estado = 0; // Disponible
        $cama->situacion = '1'; // Libre
        $cama->save();
      }
    endif;
    
    // Cama nueva se registra
    $emergencia->ubica_prioridad = $prioridadesopciones;
    $emergencia->ubica_topico = $topicosprioridad;
    $emergencia->fecha_inicio_estado = $fecha_inicio_estado;
    $emergencia->hora_estado = $hora;
    $emergencia->cama_estado = $id_cama;
    
    return $emergencia->save();
    
    // return true;
    
  }

  

  public function GetDocumentosPendientesFirmarAdmisionId($id)
  {
    $query_string = "with tbl as (
        select id, id id_admision,fecha_firma_admision, flag_admision, 'admision' tipo 
        from emergencias e where id = {$id}
        ) select * from tbl
        where flag_admision = 0";
    return DB::select($query_string);
  }

  public function GetEmergenciaById($id_emergencia)
  {
    $datos = Emergencia::where('id', '=', $id_emergencia)->first();
    return $datos;
  }


public function GetAdmisionEmergenciaById($id_emergencia){

  $cad = "
          SELECT e.*, p.descripcion prioridad , t.descripcion,e.dni_conducido,  
          a.nombre, a.paterno, a.materno, a.sexo, a.fecha_nac, a.domicilio, a.tipo_doc_ident, a.id_tipo_doc_ident,t.descripcion servicio,
          a.telefono, a.id id_asegurado, a.fecha_nac, a.foto,  e.created_at, a.tipo_doc_ident, et.nombre establecimiento,
          e.id_tipo_beneficiario_conducido as tipo_conducido , ah.nro_historia,med.name medico_nombre, med.paterno medico_paterno, med.materno medico_materno,
          u.name admision_nombre, u.paterno admision_paterno, u.materno admision_materno, tb.descripcion tipo_beneficiario,
          date_part('year',age(to_date(to_char(a.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, a.situacion_paciente,
          (SELECT array_to_json(array_agg(row_to_json(row)))
          from (
          SELECT ee.id id_evacuado, ee.id_emergencia, ee.numero_oficio,
          ee.unidad_policial, ee.id_situacion, ee.id_departamento, ee.observacion
          from emergencia_evacuados ee
          where ee.id_emergencia = e.id and ee.estado='1'
          ) row) evacuado,
          (SELECT array_to_json(array_agg(row_to_json(row)))
          from (
          SELECT ev.id id_emergencia_violencia, ev.id_violencia, ev.id_modalidad, ev.id_comisaria, ev.id_situacion, ev.fecha_accidente, ev.hora_accidente,
          ev.id_dpto, ev.id_prov, ev.id_dist, ev.lugar_accidente, ev.id_unidad, ev.id_emergencia
          from emergencia_violencias ev
          where ev.id_emergencia = e.id and ev.estado='1'
          ) row) violencia,
          asegurados_titular.tipo_doc_ident  AS nomtipdoctitular, 
              asegurados_titular.nro_doc_ident  AS nrodoctitular, 
              asegurados_titular.paterno  AS apepattitular, 
              asegurados_titular.materno  AS apemattitular, 
              asegurados_titular.nombre  AS nomtitular, 
              asegurados_titular.cip  AS ciptitular,  
              asegurados_titular.grado  AS gradotitular,  
              asegurados_titular.situacion_paciente  AS situaciontitular,  
              asegurados_titular.unidad_pnptitular  AS unidad_pnptitular

          from emergencias as e

          left join asegurados a on e.id_asegurado=a.id
          left join asegurado_historias ah on ah.id_asegurado=a.id
          LEFT JOIN asegurados as asegurados_titular ON  a.id_asegurado_titular = asegurados_titular.id
          left join triajes t on e.id_triaje=t.id
          left join prioridades p on e.id_prioridad=p.id
          left join establecimientos et on e.id_establecimiento=et.id
          left join users u on u.id=e.id_usuario_admision 
          left join users med on med.id=e.id_medico_triaje
          left join tipo_beneficiarios tb on tb.id=e.id_tipo_beneficiario 

          where e.id = ".$id_emergencia." and e.estado!=0";

      $data = DB::SELECT($cad);
      return $data[0];

  }

  public function GetObtenerServicio($id) {
        
        if($id > 0){

                $cad = "select t10.descripcion servicio,t4.descripcion consultorio, t1.ubica_prioridad, t1.ubica_topico, t1.cama_estado cama, t1.fecha_inicio_estado, t1.hora_estado, t1.id_prioridad 
                        FROM emergencias t1
                        inner join triajes t4 on t1.id_triaje=t4.id
                        inner join prioridades t10 on t1.id_prioridad=t10.id
                        Where t1.id=".$id; 

            $data = DB::select($cad);
            return $data[0];
        }
  }

  public function GetObtenerServicioReceta($id_prestacion) {
        
        if($id_prestacion > 0){

                $cad = "select t10.descripcion servicio,t4.descripcion consultorio, t1.ubica_prioridad, t1.ubica_topico, t1.cama_estado cama, t1.fecha_inicio_estado, t1.hora_estado, t1.id_prioridad, t1.id_triaje 
                        FROM emergencias t1
                        inner join prestaciones t2 on t1.id = t2.id_ext
                        left join triajes t4 on t1.ubica_topico=t4.id
                        left join prioridades t10 on t1.ubica_prioridad=t10.id
                        Where t2.id_estado_reg = 1 and t2.id=".$id_prestacion;

            $data = DB::select($cad);
            return $data[0];
        }
    }
    public function GetObtenerServicioActual($id) {
        
        if($id > 0){

                $cad = "select t10.descripcion servicio,t4.descripcion consultorio, t4.id_ups, t4.id_servicio, t1.ubica_prioridad, t1.ubica_topico, t1.cama_estado cama, t1.fecha_inicio_estado, t1.hora_estado, t1.id_prioridad, t1.id_triaje 
                        FROM emergencias t1
                        left join triajes t4 on t1.ubica_topico=t4.id
                        left join prioridades t10 on t1.ubica_prioridad=t10.id
                        Where t1.id=".$id; 

            $data = DB::select($cad);
            return $data[0];
        }
    }

    public function GetObtenerCama($id) {
      if (empty($id)) {
        return [];
      }
        $cad = "select T6.*,T1.id_asignacion_servicio,T2.id_piso,T4.id_pabellon,T1.nombre cuarto, T5.nombre pabellon, T4.nombre piso,  T3.nombre consultorio , T6.nombre cama, T2.id_consultorio
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio = T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id 
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                where T6.id=".$id."
                limit 1 ";
        
        $data = DB::select($cad);
        if(count($data)== 0){
          return [];
        }
        return $data[0];
    }

  public function GetEmergenciaByIdAsegurado($id_asegurado)
  {
   // $datos = Emergencia::where('id_asegurado', '=', $id_asegurado)->first();
   // return $datos;
     $cad = "select t1.dni_beneficiario ,to_char(t1.created_at::date,'DD/MM/YYYY') fecha_ingreso,
                  to_char(t1.created_at::time,'HH24:MI') hora_ingreso,
                  DATE_PART('day', now()::timestamp -  t1.created_at::timestamp) * 24 + 
                  DATE_PART('hour', now()::timestamp -  t1.created_at::timestamp) as dif_horas,
                  DATE_PART('day', now()::timestamp -  t1.created_at::timestamp) as dia_estancia,
                  t4.descripcion consultorio       
                  FROM emergencias t1
                  inner join triajes t4 on t1.id_triaje=t4.id
                  Where t1.id_asegurado=".$id_asegurado. " and t1.estado <> 0 order by t1.created_at desc LIMIT 1"; //-- 0 : REBOTA 
      $data = DB::select($cad);
      //dd($data);
      if($data==null){
        return null;
      }else{
        return $data[0];
      }
  }
  
  public function GetEmergenciaByIdAseguradoEmergencia($id_asegurado, $id_emergencia)
  {
   // $datos = Emergencia::where('id_asegurado', '=', $id_asegurado)->first();
   // return $datos;
     $cad = "select t1.dni_beneficiario ,to_char(t1.created_at::date,'DD/MM/YYYY') fecha_ingreso,
                  to_char(t1.created_at::time,'HH24:MI') hora_ingreso,
                  DATE_PART('day', now()::timestamp -  t1.created_at::timestamp) * 24 + 
                  DATE_PART('hour', now()::timestamp -  t1.created_at::timestamp) as dif_horas,
                  DATE_PART('day', now()::timestamp -  t1.created_at::timestamp) as dia_estancia,
                  t4.descripcion consultorio       
                  FROM emergencias t1
                  inner join triajes t4 on t1.id_triaje=t4.id
                  Where t1.id=".$id_emergencia. " and t1.id_asegurado=".$id_asegurado. " and t1.estado <> 0 order by t1.created_at desc LIMIT 1"; //-- 0 : REBOTA 
      $data = DB::select($cad);
      //dd($data);
      if($data==null){
        return null;
      }else{
        return $data[0];
      }
  }

  public function GetEmergenciaDiaEstancia($id_asegurado,$fecha_egreso,$hora_egreso)
  {   
     $cad = "select t1.dni_beneficiario ,to_char(t1.created_at::date,'DD/MM/YYYY') fecha_ingreso,
                  to_char(t1.created_at::time,'HH24:MI') hora_ingreso,
                  DATE_PART('day', '".$fecha_egreso." ".$hora_egreso."' -  t1.created_at::timestamp) as dia_estancia,
                  t4.descripcion consultorio
                  FROM emergencias t1
                  inner join triajes t4 on t1.id_triaje=t4.id
                  Where t1.id_asegurado=".$id_asegurado. " and t1.estado <> 0 order by t1.created_at desc LIMIT 1"; //-- 0 : REBOTA 
      
      $data = DB::select($cad);
      
      if($data==null){
        return null;
      }else{
        return $data[0];
      }
  }
  public function ActualizaEstadoEmergenciaByIdAsegurado($id_asegurado, $estado) {

    $emergencia = Emergencia::where('id_asegurado', $id_asegurado)->where('estado', '1')->first(); 
    if($emergencia!=NULL){
      $emergencia->estado = $estado;
      $emergencia->save();
    }else{
      
    }
  }
  public function verEmergenciaTriajeById($id) {
    $cad = "SELECT
              em.id, ase.nro_doc_ident as nro_dni,  em.created_at as fecha_registro_emergencia_triaje, ase.tipo_doc_ident as tipo_documento, ase.sexo as sexo,
              ase.paterno as apellido_pat_paciente, ase.materno as apellido_mat_paciente, ase.nombre as nombres_paciente,
              date_part('year',age(to_date(to_char(ase.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente,
              ase.fecha_nac as fecha_nac_paciente,  pri.descripcion as prioridad_nombre, tr.descripcion as topico_nombre, em.observacion_triaje,
              em.id_asegurado as id_paciente, em.id_triaje as id_triaje, em.secuencia as secuencia, em.registro_manual as registro_manual,
              em.temperatura, em.talla_cm, em.peso_kg, em.sa02_porcentaje,  em.tamano_cintura_cm,  em.frecuencia_cardiaca_fetal, 
              em.presion_arterial_x, em.presion_arterial_y, TO_CHAR(em.fecha_registro_ingreso, 'DD/MM/YYYY') AS fecha_registro_ingreso, 
              em.frecuencia_respiratoria,   em.frecuencia_cardiaca, em.fi02, em.es_gestante,
              tdi.descripcion as tipo_doc_ident, em.id_padre_paciente, em.id_madre_paciente,
              em.id_forma_ingreso, f_ing.descripcion as descripcion_forma_ingreso,
              em.id_estado_ingreso, e_ing.descripcion as descripcion_estado_ingreso,
              em.id_motivo_ingreso, mot_ing.descripcion as descripcion_motivo_ingreso,
              em.flag_triaje,

              asegurados_titular.tipo_doc_ident  AS nomtipdoctitular, 
              asegurados_titular.nro_doc_ident  AS nrodoctitular, 
              asegurados_titular.paterno  AS apepattitular, 
              asegurados_titular.materno  AS apemattitular, 
              asegurados_titular.nombre  AS nomtitular, 
              asegurados_titular.cip  AS ciptitular,  
              asegurados_titular.grado  AS gradotitular,  
              asegurados_titular.situacion_paciente  AS situaciontitular,  
              asegurados_titular.unidad_pnptitular  AS unidad_pnptitular,

              em.id_gestacion, em.id_puerperio, -- YMC
              p.fecha_parto, p.id_tipo_parto, tp.nombre as tipo_parto_nombre, p.complicaciones, p.descripcion, -- YMC
              g.duda, g.fech_ult_regla, g.fech_prob_parto, g.semanas_gestacion, g.num_controles -- YMC
              
            FROM emergencias em
            LEFT JOIN puerperios p on em.id_puerperio = p.id -- YMC
            LEFT JOIN gestaciones g on em.id_gestacion = g.id -- YMC
            LEFT JOIN tipo_partos tp on p.id_tipo_parto = tp.id -- YMC
            LEFT JOIN asegurados ase ON em.id_asegurado = ase.id
            LEFT JOIN asegurados as asegurados_titular ON  ase.id_asegurado_titular    =asegurados_titular.id
            LEFT JOIN tipo_documento_identidad tdi ON ase.id_tipo_doc_ident = tdi.id
            LEFT JOIN triajes tr ON em.id_triaje = tr.id
            LEFT JOIN prioridades pri ON tr.prioridad = pri.id
            LEFT JOIN forma_ingresos f_ing ON em.id_forma_ingreso = f_ing.id
            LEFT JOIN estado_ingresos e_ing ON em.id_estado_ingreso = e_ing.id
            LEFT JOIN categorias mot_ing ON em.id_motivo_ingreso = mot_ing.id
            WHERE em.id = ?;";
    $data = DB::SELECT($cad, [$id]);
    return $data;
  }
  
  public function GetEstadoFirmaEmergenciaTriaje($id)
  {
    $query_string = "select id, fecha_firma_triaje, ruta_triaje from emergencias where id = '{$id}'";
    return DB::select($query_string);
  }
    public function validar_dato_SitedsEmergencia($cod_siteds_susalud)
  {
    $query_string = "SELECT count(id) as cantidad_siteds from emergencias where cod_siteds_susalud = '{$cod_siteds_susalud}'";
    $data = DB::SELECT($query_string);
    if ($data != null || count($data) > 0){
      return $data[0];
    }else{
      return $data;
    }; 
  }



  public function GetDocPendinteFirmarEmergenciaTriajeId($id)
  {
    $query_string = "with tbl as (
        select id, id id_emergencia,fecha_firma_triaje, flag_triaje, 'emergencias_triaje' tipo 
        from emergencias p where id = {$id}
        union all
        select id, id id_emergencia,fecha_firma_admision, flag_admision, 'emergencias_admision' tipo 
        from emergencias p where id = {$id}
        ) select * from tbl
    where flag_triaje = 0";
  return DB::select($query_string);
  }

  public function ListarTriajeById($id) {
    $cad = "SELECT
              em.id as id_emergencia, ase.*, em.id_prioridad, em.id_asegurado, em.id_triaje as id_topico, 
              em.temperatura, em.sa02_porcentaje, em.presion_arterial_x, em.presion_arterial_y, em.talla_cm, em.tamano_cintura_cm, 
              em.frecuencia_respiratoria, em.peso_kg, em.frecuencia_cardiaca_fetal, em.frecuencia_cardiaca, em.fi02, 
              em.observacion_triaje,  em.estado, tr.id as triaje_id,TO_CHAR(em.fecha_registro_ingreso, 'DD/MM/YYYY') AS fecha_registro_ingreso, 
              date_part('year',age(to_date(to_char(ase.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente,  em.registro_manual,
              ase.fecha_nac, tdi.descripcion as tipo_doc_ident ,em.id_padre_paciente as id_padre_paciente, em.id_madre_paciente as id_madre_paciente,
              em.id_forma_ingreso, f_ing.descripcion as descripcion_forma_ingreso,em.hora_registro_ingreso as hora_registro_ingreso,
              em.id_estado_ingreso, e_ing.descripcion as descripcion_estado_ingreso,em.fecha_registro_ingreso as fecha_registro_ingreso_orig,
              em.id_motivo_ingreso, mot_ing.descripcion as descripcion_motivo_ingreso, em.es_gestante

            FROM emergencias em 
              INNER JOIN asegurados ase ON em.id_asegurado = ase.id
              LEFT JOIN tipo_documento_identidad tdi ON ase.id_tipo_doc_ident = tdi.id
              INNER JOIN triajes tr ON em.id_triaje = tr.id
              INNER JOIN prioridades pri ON tr.prioridad = pri.id

              INNER JOIN forma_ingresos f_ing ON em.id_forma_ingreso = f_ing.id
              INNER JOIN estado_ingresos e_ing ON em.id_estado_ingreso = e_ing.id
              INNER JOIN categorias mot_ing ON em.id_motivo_ingreso = mot_ing.id
            WHERE em.id = ?;";
    $data = DB::select($cad, [$id]);
    return $data;
}
public function obtener_alerta_atenciones($dni_beneficiario) {
    $date = Carbon::now(); 
    $fecha30DiasAntes = $date->subDays(30)->format('Y-m-d');

    $cad = "SELECT establecimientos.nombre as establecimiento_descripcion,
    prioridades.descripcion as prioridad_descripcion,
    triajes.descripcion as servicio_descripcion,
    TO_CHAR(emergencias.fecha_registro_ingreso, 'DD/MM/YYYY') as fecha_registro_ingreso,
    emergencias.hora_registro_ingreso as hora_registro_ingreso,
    TO_CHAR(emergencias.fecha_alta_medica, 'DD/MM/YYYY') as fecha_egreso_medico,
    emergencias.hora_egreso_medico as hora_egreso_medico,
    EXTRACT(DAY FROM age(date(emergencias.fecha_alta_medica) ,date(emergencias.fecha_registro_ingreso) ) ) as dias_transcurridos,
    emergencias.flag_egreso as flag_egreso
    FROM emergencias 
    INNER JOIN establecimientos ON establecimientos.id=emergencias.id_establecimiento
    INNER JOIN prioridades ON prioridades.id=emergencias.id_prioridad
    INNER JOIN triajes ON triajes.id=emergencias.id_triaje
    WHERE 
    emergencias.dni_beneficiario ='".$dni_beneficiario."' 
    and emergencias.fecha_registro_ingreso >='".$fecha30DiasAntes."'
    and emergencias.fecha_alta_medica isnull 
    and emergencias.estado!='0' 
    and emergencias.flag_egreso ='0'
    and emergencias.id_prioridad != '4'
    order by emergencias.fecha_registro_ingreso desc"; 
    $data = DB::SELECT($cad);
    return $data;
  } 
  public function evaluar_pendientes_alta($dni_beneficiario) { 
      $date = Carbon::now(); 
      $fecha30DiasAntes = $date->subDays(30)->format('Y-m-d');

      $cad = "SELECT count(id) FROM emergencias WHERE 
      dni_beneficiario ='".$dni_beneficiario."' and 
      fecha_alta_medica isnull
      and fecha_registro_ingreso >='".$fecha30DiasAntes."' 
      and estado!='0'
      and id_prioridad != '4'
      and flag_egreso = '0'"; 
      $data = DB::SELECT($cad);
      return $data;
    }  
//---------SALUD MATERNA---------////



public function obtenerReporteSaludMaterna($id){
  $cad = "SELECT e.*,p.*, nat.*,
          concat(a.nro_doc_ident, ' - ', a.paterno, ' ', a.materno, ' ', a.nombre) as nombre_asegurado, a.tipo_doc_ident as tipo_doc_ident,
          ah.nro_historia as nro_historia_paciente, substring(a.fecha_nac, 7, 10) as fecha_nacimiento, a.domicilio as domicilio_aseg,
         
          (SELECT concat(a2.paterno, ' ', a2.materno, ' ', a2.nombre) as nombre_asegurado_titular from asegurados a2 where a2.id = a.id_asegurado_titular),
          to_char(age(TO_DATE(fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"dias\"') as fec_nacim_descrip,

          tb.descripcion as parentesco, a.sexo, a.telefono, 
          (SELECT a3.nro_doc_ident from asegurados a3 where a3.id = a.id_asegurado_titular) as nro_doc_asegurado_titular,
          (SELECT a4.cip from asegurados a4 where a4.id = a.id_asegurado_titular) as cip_asegurado_titular, 
          (SELECT a5.grado from asegurados a5 where a5.id = a.id_asegurado_titular) as grado_asegurado_titular,
          (SELECT a5.situacion_paciente from asegurados a5 where a5.id = a.id_asegurado_titular) as situacion_paciente_titular,
          (SELECT a5.unidad_pnptitular from asegurados a5 where a5.id = a.id_asegurado_titular) as unidad_pnptitular,
          t.descripcion as descripcion_topico , a.id as id_asegurado, e.es_gestante, e.frecuencia_cardiaca_fetal, e.tamano_cintura_cm,
          date_part('year',age(to_date(to_char(a.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, tip_paren.descripcion as descripcion_parentesco,
          e.id_forma_ingreso, f_ing.descripcion as descripcion_forma_ingreso, e.cups_pnp as cups_pnp, e.cod_siteds_susalud, 
          e.id_estado_ingreso, e_ing.descripcion as descripcion_estado_ingreso,
          e.id_motivo_ingreso, mot_ing.descripcion as descripcion_motivo_ingreso,
          TO_CHAR(e.fecha_registro_ingreso, 'DD/MM/YYYY') AS fecha_registro_ingreso, 
          TO_CHAR(e.hora_registro_ingreso::time,'HH:MI:SS AM') AS  hora_registro_ingreso,
          TO_CHAR(e.fecha_registro_admision, 'DD/MM/YYYY') AS fecha_registro_admision,
          TO_CHAR(e.hora_registro_admision::time,'HH:MI:SS AM') AS  hora_registro_admision,
          dep.descripcion as departamento_descripcion, prov.descripcion as provincia_descripcion, dist.descripcion as distrito_descripcion,

          e.id_gestacion, e.id_puerperio, -- YMC
          pu.fecha_parto, pu.id_tipo_parto, tp.nombre as tipo_parto_nombre, pu.complicaciones, -- YMC
          g.duda, g.fech_ult_regla, g.fech_prob_parto, g.semanas_gestacion, g.num_controles -- YMC
          
          from emergencias e

          inner join prioridades p on e.id_prioridad = p.id
          inner join asegurados a on a.id = e.id_asegurado 
          inner join asegurado_historias ah  on e.id_asegurado = ah.id_asegurado 
          inner join triajes t on t.id = e.id_triaje  
          INNER JOIN forma_ingresos f_ing ON e.id_forma_ingreso = f_ing.id
          INNER JOIN estado_ingresos e_ing ON e.id_estado_ingreso = e_ing.id
          left JOIN tipo_parentescos tip_paren ON e.id_tipo_parentesco = tip_paren.id 
          left JOIN categorias mot_ing ON e.id_motivo_ingreso = mot_ing.id 
          left join tipo_beneficiarios tb on tb.id = a.id_tipo_beneficiario
          
          left join departamentos dep on a.id_departamento = dep.id_departamento
          left join provincias prov on a.id_provincia = prov.id_provincia
          left join distritos dist on a.id_distrito = dist.id_distrito

          left join puerperios pu on e.id_puerperio = pu.id -- YMC
          left join gestaciones g on e.id_gestacion = g.id -- YMC
          left join tipo_partos tp on pu.id_tipo_parto = tp.id -- YMC
          left join natis nat on pu.id =nat.id_puerperio 

          where e.id = ".$id; 
  $data = DB::SELECT($cad);
  //var_dump($data);
  return $data[0];
}

public function obtenerRegistroEmergencia($id){
    $cad = "SELECT e.*,p.*, nat.*,
            concat(a.nro_doc_ident, ' - ', a.paterno, ' ', a.materno, ' ', a.nombre) as nombre_asegurado, a.id_tipo_doc_ident, a.nro_doc_ident, a.tipo_doc_ident as tipo_doc_ident,
            ah.nro_historia as nro_historia_paciente, substring(a.fecha_nac, 7, 10) as fecha_nacimiento, a.domicilio as domicilio_aseg,
           
            (SELECT concat(a2.paterno, ' ', a2.materno, ' ', a2.nombre) as nombre_asegurado_titular from asegurados a2 where a2.id = a.id_asegurado_titular),
            to_char(age(TO_DATE(fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"dias\"') as fec_nacim_descrip,

            tb.descripcion as parentesco, a.sexo, a.telefono, 
            (SELECT a3.nro_doc_ident from asegurados a3 where a3.id = a.id_asegurado_titular) as nro_doc_asegurado_titular,
            (SELECT a4.cip from asegurados a4 where a4.id = a.id_asegurado_titular) as cip_asegurado_titular, 
            (SELECT a5.grado from asegurados a5 where a5.id = a.id_asegurado_titular) as grado_asegurado_titular,
            (SELECT a5.situacion_paciente from asegurados a5 where a5.id = a.id_asegurado_titular) as situacion_paciente_titular,
            (SELECT a5.unidad_pnptitular from asegurados a5 where a5.id = a.id_asegurado_titular) as unidad_pnptitular,
            t.descripcion as descripcion_topico , a.id as id_asegurado, e.es_gestante, e.frecuencia_cardiaca_fetal, e.tamano_cintura_cm,
            date_part('year',age(to_date(to_char(a.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, tip_paren.descripcion as descripcion_parentesco,
            e.id_forma_ingreso, f_ing.descripcion as descripcion_forma_ingreso, e.cups_pnp as cups_pnp, e.cod_siteds_susalud, 
            e.id_estado_ingreso, e_ing.descripcion as descripcion_estado_ingreso,
            e.id_motivo_ingreso, mot_ing.descripcion as descripcion_motivo_ingreso,
            TO_CHAR(e.fecha_registro_ingreso, 'DD/MM/YYYY') AS fecha_registro_ingreso, 
            TO_CHAR(e.hora_registro_ingreso::time,'HH:MI:SS AM') AS  hora_registro_ingreso,
            TO_CHAR(e.fecha_registro_admision, 'DD/MM/YYYY') AS fecha_registro_admision,
            TO_CHAR(e.hora_registro_admision::time,'HH:MI:SS AM') AS  hora_registro_admision,
            dep.descripcion as departamento_descripcion, prov.descripcion as provincia_descripcion, dist.descripcion as distrito_descripcion,
            m.dni medico_ndoc, m.paterno medico_paterno, m.materno medico_materno, m.nombre medico_nombre, m.colegiatura medico_colegiatura, m.rne medico_rne,
            prof.nombre medico_profesion, esp.nombre medico_especializacion, m.id id_medico,
            e.id_gestacion, e.id_puerperio, -- YMC
            pu.fecha_parto, pu.id_tipo_parto, tp.nombre as tipo_parto_nombre, pu.complicaciones, -- YMC
            g.duda, g.fech_ult_regla, g.fech_prob_parto, g.semanas_gestacion, g.num_controles, -- YMC
            u2.id as id_upss_destino, u2.descripcion_upss as descripcion_upss_destino, c.id as id_servicio_destino, c.nombre as nombre_servicio_destino,coalesce(ec.nombre,'') as estado_civil
            from emergencias e

            inner join prioridades p on e.id_prioridad = p.id
            inner join asegurados a on a.id = e.id_asegurado 
            inner join asegurado_historias ah  on e.id_asegurado = ah.id_asegurado and ah.id_establecimiento = e.id_establecimiento
            inner join triajes t on t.id = e.id_triaje  
            INNER JOIN forma_ingresos f_ing ON e.id_forma_ingreso = f_ing.id
            INNER JOIN estado_ingresos e_ing ON e.id_estado_ingreso = e_ing.id
            left JOIN tipo_parentescos tip_paren ON e.id_tipo_parentesco = tip_paren.id 
            left JOIN categorias mot_ing ON e.id_motivo_ingreso = mot_ing.id 
            left join tipo_beneficiarios tb on tb.id = a.id_tipo_beneficiario
            left join users u on e.id_medico_egreso = u.id
            left join medicos m on m.dni = u.dni and m.id_establecimiento = u.id_establecimiento
            left join profesiones prof on m.id_profesion = prof.id 
            left join especializaciones esp on m.id_especializacion = esp.id
            left join departamentos dep on a.id_departamento = dep.id_departamento
            left join provincias prov on a.id_provincia = prov.id_provincia
            left join distritos dist on a.id_distrito = dist.id_distrito
            left join puerperios pu on e.id_puerperio = pu.id -- YMC
            left join gestaciones g on e.id_gestacion = g.id -- YMC
            left join tipo_partos tp on pu.id_tipo_parto = tp.id -- YMC
            left join natis nat on pu.id =nat.id_puerperio 
            left join upsses u2 on e.id_upss_alta = u2.id
            left join consultorios c on u2.id_consultorio = c.id 
            left join asignacion_servicios as2 on c.id = as2.id_consultorio 
            left join estado_civiles ec on a.id_estado_civil = ec.id
            where e.id = ".$id; 
    $data = DB::SELECT($cad);
    return $data[0];
  }  
  public function listarPrioridades()
  {
      $prioridades = DB::table('prioridades')
                      ->select('id', 'codigo', 'descripcion', 'estado')
                      ->get();
  
      return $prioridades;
  }

  public function listarTriajes()
{
    $triajes = DB::table('triajes')
                ->select('id', 'codigo', 'descripcion', 'estado', 'prioridad', 'id_servicio', 'id_sub_consultorio', 'id_establecimiento', 'created_at', 'updated_at')->where('estado','N')
                ->get();

    return $triajes;
}
public function serviciosxmedicos()
{
  $serviciosxMedicos = DB::table('medico_turnos')
  ->where('fecha_atencion', date('Y-m-d'))
  ->where('estado', 1)
  ->get();

return $serviciosxMedicos;
}

public function medicosxservicios($idconsultorio){
  $serviciosxMedicos = DB::table('medico_turnos')
  ->select('id_tipo_atencion', '*')
  ->where('fecha_atencion', date('Y-m-d'))
  ->where('estado', 1)
  ->where('id_consultorio', $idconsultorio)
  ->get();

return $serviciosxMedicos;
}

public function turnomedico($idvalor){
  $turnos = DB::table('medico_turnos')
  ->select('id_turno')
  ->where('fecha_atencion', date('Y-m-d'))
  ->where('estado', 1)
  ->where('id_medico', $idvalor)
  ->get();
return $turnos;

}

public function ShowallById_IpressEmergencia($id_ipress){
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
      where T1.id_establecimiento=".$id_ipress." And T6.estado!=3 and t3.nombre ='EMERGENCIA'" ;
  
      $data = DB::select($cad);
      return $data;
  

}
  
  /*
  Registrar datos de egreso de emergencia y el telefono en caso de ser modificado
  */
  public function RegistrarEgreso($id_emergencia, $id_usuario_egreso, $fecha_egreso, $hora_egreso, $tipo_egreso, $obs_egreso, 
  $id_servicio_hospitalizacion, $id_condicion_alta, $derivar_consulta, $id_servicio_deriva) {
    
    $emergencia = Emergencia::where('id', $id_emergencia)->first(); 
    $emergencia->fecha_egreso = $fecha_egreso;
    $emergencia->hora_egreso = $hora_egreso;
    $emergencia->id_tipo_egreso = $tipo_egreso;
    $emergencia->observacion = $obs_egreso;
    $emergencia->id_sala_egreso = $id_servicio_hospitalizacion;
    $emergencia->id_condicion_alta = $id_condicion_alta;
    $emergencia->derivar = $derivar_consulta;
    $emergencia->id_servicio_destino = $id_servicio_deriva;
    $emergencia->flag_egreso = 2;
    $emergencia->codigo_usuario_egreso = $id_usuario_egreso;
    $emergencia->fecha_registro_egreso = Carbon::now();
    $emergencia->hora_registro_egreso = Carbon::now();
    $emergencia->estado = 4;
    $emergencia->save();
    
    return true;
    
  }

  public function GetEgresoEmergencia($id_emergencia) {
     $cad = "SELECT 
            e.id, e.fecha_firma_admision, e.fecha_egreso, e.hora_egreso, e.id_tipo_egreso, d.descripcion tipo_egreso,
            c.nombre servicio_hospitalizacion, c2.descripcion condicion_egreso,
            e.observacion, e.derivar, c3.nombre servicio_derivacion, e.fecha_firma_egreso
            FROM emergencias e 
            INNER JOIN destinos d ON e.id_tipo_egreso = d.id
            LEFT JOIN consultorios c ON e.id_sala_egreso = c.id 
            LEFT JOIN condiciones c2 ON CAST(e.id_condicion_alta AS integer) = c2.id
            LEFT JOIN consultorios c3 ON e.id_servicio_destino = c3.id 
            WHERE e.id = {$id_emergencia}"; 

      $data = DB::select($cad);
      if ($data != null || count($data) > 0):
        return $data[0];
      else:
        return $data;
      endif;
      
  }

 public function update_ticket_by_emergencia_codigo_ticket($cod_totem) { 
  
  //$date = Carbon::now();
  //$fechaActual = $date->format('Y-m-d');
  DB::table('tickets')
  ->whereRaw("CONCAT(letra, '', numero) = ?", [$cod_totem])
  //->whereRaw("to_char(created_at::date, 'YYYY-MM-DD') = ?", [$fechaActual])
  ->update([
      'flag_triaje' => '1'
  ]);
   
 }
   
   
  public function ListarTriajesAtendidos($id_establecimiento, $fechaInicio, $fechaFinal, $dniPaciente, $nombrePaciente, $estado,
   $NumeroPagina, $NumeroRegistros) { 
    $fechaInicio = $fechaInicio != "" ? $fechaInicio : "";
    $fechaFinal = $fechaFinal != "" ? $fechaFinal : "";
    $dniPaciente = $dniPaciente != "" ? strtoupper($dniPaciente) : "";
    $nombrePaciente = $nombrePaciente != "" ? strtoupper($nombrePaciente) : "";
 
    $datos = DB::table('totem_opciones')->where('id_consultorio', '=', Auth::user()->id_consultorio_ticket)->first();  
  
    if($estado=='' || $estado==null || $estado==-1|| $estado==-2){
      $estado_TICKET=1;
      if($estado==-2){
        $estado_TICKET=0;
      }
     
      
      if(Auth::user()->flagservicios==1){

        $where = " WHERE ";
        if ($fechaInicio != "" && $fechaFinal != "")  $where .= " CAST(tickets.created_at AS DATE) BETWEEN '".$fechaInicio."' AND '".$fechaFinal."'  ";
        if ($dniPaciente != "")                       $where .= " AND tickets.dni_reniec = '".$dniPaciente."' ";
        if ($nombrePaciente != "")                    $where .= " AND tickets.nombres_reniec ILIKE '%".$nombrePaciente."%' ";
  
        $cad = "
        SELECT 
        ROW_NUMBER() OVER(ORDER BY tickets.created_at DESC) AS num,
         tickets.motivo_anulacion,
        tickets.id as cod_emergencia, 
        tickets.cantidad_llamada,
        to_char(tickets.created_at::date,'DD/MM/YYYY') AS fecha_triaje, 
        to_char(tickets.created_at, 'HH:MI:SS AM')  AS hora_triaje,
        case 
          when tickets.id_asegurado = 0 then tickets.dni_reniec
          else a.nro_doc_ident
          end as nro_doc_ident,
        case 
          when tickets.id_asegurado = 0 then tickets.nombres_reniec
          else concat(a.nombre,' ',a.paterno,' ',a.materno) 
        end as asegurado ,  
        CONCAT (tickets.letra ,'',tickets.numero) as cod_totem,

        to_char(tickets.anulado_at::date,'DD/MM/YYYY') AS fecha_triaje_anulado, 
        to_char(tickets.anulado_at, 'HH:MI:SS AM')  AS hora_triaje_anulado,
        CONCAT(user_adm.paterno,' ',user_adm.materno,' ',user_adm.name) AS anulado_us,

        totem_opciones.nombre as totem_nombre
        FROM tickets as tickets 
        LEFT join asegurados a on a.id= tickets.id_asegurado  
        LEFT JOIN users user_adm ON tickets.anulado_us = user_adm.id 
        LEFT JOIN totem_opciones as totem_opciones on tickets.id_totem_opcion =totem_opciones.id
        
        ";
        $cad .= $where ." AND tickets.flag_triaje='0' AND tickets.id_totem='6'  AND tickets.estado='$estado_TICKET' 
                          AND tickets.id_totem_opcion='$datos->id' "; 
        
        $cad_total =  $cad ; 
        if($NumeroPagina == 0 && $NumeroRegistros==0 ){
          $cad .= " ORDER BY tickets.id desc ";
        }else{
          $cad .= " ORDER BY tickets.id desc LIMIT " . $NumeroRegistros . " OFFSET " . ($NumeroPagina - 1) * $NumeroRegistros . "; ";
        }  
         
        $data = DB::SELECT($cad);
        $totalrows = count(DB::SELECT($cad_total));  
        return array('data' => $data, 'totalrows' => $totalrows);   
  
      }else{
          
        $id_establecimiento = Auth::user()->id_establecimiento;
        $id_consultorio = auth()->user()->id_consultorio_ticket;
        $id_totem = null;
        $id_totem_array = DB::select('select t.id from totems t 
        LEFT join corredores c on t.id_corredor = c.id 
        LEFT join corredores_detalle cd on cd.id_corredor = c.id 
        LEFT join users u on u.id_corredores_detalle = cd.id 
        where u.id ='.auth()->user()->id);
        if(count($id_totem_array)>0):
          $id_totem = $id_totem_array[0]->id; 
        endif;
        
        $where = " WHERE ";
        if ($fechaInicio != "" && $fechaFinal != "")  $where .= " CAST(t.created_at AS DATE) BETWEEN '".$fechaInicio."' AND '".$fechaFinal."'  ";
        if ($dniPaciente != "")                       $where .= " AND t.dni_reniec = '".$dniPaciente."' ";
        if ($nombrePaciente != "")                    $where .= " AND t.nombres_reniec ILIKE '%".$nombrePaciente."%' ";

        $estado = "0,1,2,4";

        $cad = "select 
                ROW_NUMBER() OVER(ORDER BY t.created_at DESC) AS num, 
                t.id as cod_emergencia, 
                to_char(t.created_at::date,'DD/MM/YYYY') AS fecha_triaje, 
                to_char(t.created_at, 'HH:MI:SS AM')  AS hora_triaje,
                CONCAT (t.letra ,'',t.numero) as cod_totem,
                to2.nombre as totem_nombre,

                to_char(t.anulado_at::date,'DD/MM/YYYY') AS fecha_triaje_anulado, 
                to_char(t.anulado_at, 'HH:MI:SS AM')  AS hora_triaje_anulado,
                
                CONCAT(user_adm.paterno,' ',user_adm.materno,' ',user_adm.name) AS anulado_us,
      
                case 
                  when t.id_asegurado = 0 then t.dni_reniec
                  else a.nro_doc_ident
                  end as nro_doc_ident,
                case 
                  when t.id_asegurado = 0 then t.nombres_reniec
                  else concat(a.nombre,' ',a.paterno,' ',a.materno) 
                end as asegurado ,  
                case 
                  when t.id_asegurado = 0 then t.dni_reniec
                  else a.nro_doc_ident
                  end as nro_doc_ident, 
 
                  case when t.id_estado_tv = 0 then 'Pendiente' when t.id_estado_tv = 1 then 'Llamando' when t.id_estado_tv = 2 then 'Pospuesto' when t.id_estado_tv = 3 then 'Atendido' end AS estado,
                  t.id_estado_tv,
                  t.letra,
                  t.numero,t.cantidad_llamada,t.motivo_anulacion,
                  to2.nombre totem_opcion,
                  t.id_asegurado,
                  case when t.id_asegurado = 0 or t.id_asegurado isnull then 
                  split_part(t.nombres_reniec,',',2) ||' '|| split_part(t.nombres_reniec,',',3) ||', '|| split_part(t.nombres_reniec,',',1)
                  else coalesce(a.paterno||' '||a.materno||', '||a.nombre, '')end::varchar as paciente,
                  to_char(t.created_at, 'HH24:MI PM')::varchar hora_ticket,
                  coalesce(c.id, 0) id_cita,
                  to_char(t.created_at, 'dd/mm/yyyy')::varchar AS fecha_ticket,
                  t.id_estado_tv,
                  coalesce(t.cantidad_llamada, 0) AS cantidad_llamada,
                  CASE WHEN to2.id = 2 THEN 'NO' ELSE 'SI' END::varchar AS citado, 
                  t.ventanilla as id_ventanilla, v.nombre as nombre_ventanilla, to2.id_consultorio, t.llamado_us, t.llamado_at, t.seleccionado_us
                  from tickets t 
                LEFT join asegurados a ON t.id_asegurado = a.id 
                LEFT join citas c on t.id_cita = c.id
                LEFT JOIN users user_adm ON t.anulado_us = user_adm.id
                LEFT join ventanillas v on t.ventanilla = v.id
                LEFT join totem_opciones to2 on t.id_totem_opcion = to2.id 
                LEFT join totems t2 on t.id_totem = t2.id
              "; 
              
              $cad .= $where ."  AND t.flag_triaje='0' AND t.estado=".$estado_TICKET."  AND t.id_estado_tv in(".$estado.")
                AND t2.id_establecimiento = ".$id_establecimiento." AND to2.id_consultorio = ".$id_consultorio."  ";
              
              if($id_totem != null){
                  $where ." AND t.id_totem = ".$id_totem." ";
              } 
                $cad_total =  $cad ; 
                if($NumeroPagina == 0 && $NumeroRegistros==0 ){
                  $cad .= " ORDER BY t.created_at, t.numero desc ";
                }else{
                  $cad .= " ORDER BY t.created_at, t.numero desc LIMIT " . $NumeroRegistros . " OFFSET " . ($NumeroPagina - 1) * $NumeroRegistros . "; ";
                }  
        $data = DB::select($cad);
        $totalrows = count(DB::SELECT($cad_total)); 
        return array('data' => $data, 'totalrows' => $totalrows); 

      } 

    }else{
      
      $where = " WHERE ";
      if ($fechaInicio != "" && $fechaFinal != "")  $where .= " CAST(em.created_at AS DATE) BETWEEN '".$fechaInicio."' AND '".$fechaFinal."' AND ";
      if ($dniPaciente != "")                       $where .= " ase.nro_doc_ident = '".$dniPaciente."' AND ";
      if ($nombrePaciente != "")                    $where .= " ase.paterno||' '||ase.materno||' '||ase.nombre ILIKE '%".$nombrePaciente."%' AND  "; 
 
      
      if($estado==9){
        //$where .= " em.estado!=2 and em.id_establecimiento = ".$id_establecimiento; 
        $where .= "  em.id_establecimiento = ".$id_establecimiento; 
      }else{ 
        
        if($estado == 0){
          $where .= " em.id_establecimiento = ".$id_establecimiento." AND em.estado = ".$estado." AND id_usuario_anulacion IS NOT NULL " ; 
        }else{ 
          $where .= " em.id_establecimiento = ".$id_establecimiento." AND em.estado = ".$estado ; 
        }
        if($estado==3){
          $where .= " AND em.id_prioridad = '4' ";
        }
      } 


      $cad = "SELECT ROW_NUMBER() OVER(ORDER BY em.created_at DESC) AS num,
                em.id as cod_emergencia, pri.descripcion as prioridad,  tr.descripcion as topico, 
                to_char(em.created_at::date,'DD/MM/YYYY') AS fecha_triaje, 
                to_char(em.created_at, 'HH:MI:SS AM')  AS hora_triaje, 
                tb.descripcion as parentesco, ase.sexo as sexo_asegurado, em.cod_siteds_susalud,
                DATE_PART('year', CURRENT_DATE) - DATE_PART('year', TO_DATE(ase.fecha_nac, 'DD-MM-YY')) as edad_asegurado, 
                em.dni_conducido,
                ase.nro_doc_ident, ase.paterno, ase.materno, ase.nombre, ase.tipo_doc_ident, ase.fecha_nac, em.observacion_triaje, em.nombre_conducido,
                med.paterno as apepaterno_profesional, med.materno as apematerno_profesional, med.name as nombre_profesional, em.estado, em.secuencia, 
                em.flag_triaje, em.ruta_triaje, em.fecha_firma_triaje, em.secuencia, ase.telefono, ase.telefono2, ase.domicilio,
                to_char(em.totem_at::date,'DD/MM/YYYY') AS fecha_totem, 
                to_char(em.totem_at::time, 'HH:MI:SS AM')  AS hora_totem,
                est.nombre as establecimiento, for_ing.descripcion as forma_ingreso, mot_ing.descripcion as motivo_ingreso,
                to_char(em.fecha_registro_admision::date,'DD/MM/YYYY') AS fecha_admision , 
                to_char(em.fecha_registro_admision::time,'HH:MI:SS AM') AS hora_admision,
                user_adm.paterno as apepaterno_user_adm, user_adm.materno as apematerno_user_adm, user_adm.name as nombre_user_adm ,
                to_char(em.fecha_atencion::date,'DD/MM/YYYY') AS fecha_atencion , em.id_medico_triaje,
                to_char(em.hora_atencion::time,'HH:MI:SS AM') AS hora_atencion,
                to_char(em.a_llamado_at::date,'DD/MM/YYYY') AS fecha_llamado, 
                to_char(em.a_llamado_at::time, 'HH:MI:SS AM')  AS hora_llamado ,
                user_llama.paterno as apematerno_llamado, user_llama.materno as apepaterno_llamado, user_llama.name as nombre_llamado,
                em.motivo_anulacion,
                concat(user_anula.paterno,' ',user_anula.materno,' ',user_anula.name) as usuario_anula,
                to_char(em.fecha_anulacion::date,'DD/MM/YYYY') AS fecha_anulacion , 
                to_char(em.fecha_anulacion::time, 'HH:MI:SS AM')  AS hora_anulacion

                
              FROM emergencias em 
                INNER JOIN asegurados ase ON em.id_asegurado = ase.id
                LEFT JOIN tipo_beneficiarios tb ON ase.id_tipo_beneficiario = tb.id
                LEFT JOIN users med ON em.id_medico_triaje = med.id
                LEFT JOIN triajes tr ON em.id_triaje = tr.id
                LEFT JOIN prioridades pri ON tr.prioridad = pri.id 
                LEFT JOIN establecimientos est ON em.id_establecimiento = est.id 
                LEFT JOIN forma_ingresos for_ing ON em.id_forma_ingreso = for_ing.id   
                LEFT JOIN estado_ingresos e_ing ON em.id_estado_ingreso = e_ing.id
                LEFT JOIN categorias mot_ing ON em.id_motivo_ingreso = mot_ing.id
                LEFT JOIN users user_adm ON em.id_usuario_admision = user_adm.id
                LEFT JOIN users user_llama ON em.a_llamado_us = user_llama.id
                LEFT JOIN users user_anula ON em.id_usuario_anulacion = user_anula.id 
              ";   

      $cad .= $where; 
      //dd($cad);
      $cad_total =  $cad ; 
      if($NumeroPagina == 0 && $NumeroRegistros==0 ){
        $cad .= " ORDER BY em.id desc";
      }else{
        $cad .= " ORDER BY em.id desc LIMIT " . $NumeroRegistros . " OFFSET " . ($NumeroPagina - 1) * $NumeroRegistros . ";";
      } 
      //dd($cad);
      $data = DB::SELECT($cad);
      $totalrows = count(DB::SELECT($cad_total));   
      return array('data' => $data, 'totalrows' => $totalrows); 
    }

  }

   
  public function ListarTriajesAtendidosRepo1($id_establecimiento, $fechaInicio, $fechaFinal, $dniPaciente, $nombrePaciente, 
  $estado, $ubicacion, $id_prioridad, $id_topico, $edad_desde, $edad_hasta, $tipo_consulta, $NumeroPagina, $NumeroRegistros){

    
    if($id_prioridad=="")
      $id_prioridad=0;
    
    $p = array();
    $p[0]=$fechaInicio;
    $p[1]=$fechaFinal;
    $p[2]=$nombrePaciente;
    $p[3]=$dniPaciente;
    $p[4]=$id_prioridad;
    $p[5]=1000000000;
    $p[6]=1000000000;
    $p[7]=1000000000;
  
    return $this->readFunctionPostgres('filtrar_emergencias2',$p);
  }

  
  public function ListarTriajesAtendidosRepo($id_establecimiento, $fechaInicio, $fechaFinal, $dniPaciente, $nombrePaciente, 
  $estado, $ubicacion, $id_prioridad, $id_topico, $id_motivo_ingreso, $id_tipo_violencia, $edad_desde, $edad_hasta, $tipo_consulta, $NumeroPagina, $NumeroRegistros) { //?

    //dd($estado);

    $fechaInicio = $fechaInicio != "" ? $fechaInicio : "";
    $fechaFinal = $fechaFinal != "" ? $fechaFinal : "";
    $dniPaciente = $dniPaciente != "" ? strtoupper($dniPaciente) : "";
    $nombrePaciente = $nombrePaciente != "" ? strtoupper($nombrePaciente) : "";
  
    $where = " WHERE ";
    if ($fechaInicio != "" && $fechaFinal != "") $where .= " CAST(em.created_at AS DATE) BETWEEN '".$fechaInicio."' AND '".$fechaFinal."' AND ";  
    if ($nombrePaciente != "")                   $where .= " CONCAT(ase.nro_doc_ident,' ',ase.paterno,' ',ase.materno,' ',ase.nombre) LIKE '%".$nombrePaciente."%' AND ";
    if ($edad_desde != "" && $edad_hasta != "")  $where .= " 
      DATE_PART('year', CURRENT_DATE) - DATE_PART('year', TO_DATE(ase.fecha_nac, 'DD-MM-YY')) >= '".$edad_desde."' AND 
      DATE_PART('year', CURRENT_DATE) - DATE_PART('year', TO_DATE(ase.fecha_nac, 'DD-MM-YY')) <= '".$edad_hasta."' AND ";  

      $where .= " em.id_establecimiento = ".$id_establecimiento; 

      if($estado=='0'){
        $where .= " AND em.estado =  ".$estado  ;  
      } else if($estado=='1'){
        $where .= " AND em.estado >=1  " ;  
      }

      if($ubicacion==1){ //! TRIAJE = 1
        $where .= " AND em.fecha_registro_ingreso IS NOT NULL 
                    AND em.fecha_registro_admision IS NULL 
                    AND em.fecha_atencion IS NULL 
                    AND em.fecha_alta_medica IS NULL
                    AND em.fecha_egreso_administrativa IS NULL ";  
      }else if($ubicacion==2){ //! ADMISIÓN = 2
        $where .= " AND em.fecha_registro_ingreso IS NOT NULL 
                    AND em.fecha_registro_admision IS NOT NULL 
                    AND em.fecha_atencion IS NULL 
                    AND em.fecha_alta_medica IS NULL
                    AND em.fecha_egreso_administrativa IS NULL "; 
      }else if($ubicacion==3){ //!  ATENSIÓN MÉDICA = 3
        $where .= " AND em.fecha_registro_ingreso IS NOT NULL 
                    AND em.fecha_registro_admision IS NOT NULL 
                    AND em.fecha_atencion IS NOT NULL 
                    AND em.fecha_alta_medica IS NULL
                    AND em.fecha_egreso_administrativa IS NULL ";   
      }else if($ubicacion==4){ //!EVALUACIÓN= 4
        $where .= " AND em.fecha_registro_ingreso IS NOT NULL 
                    AND em.fecha_registro_admision IS NOT NULL 
                    AND em.fecha_atencion IS NOT NULL 
                    AND em.fecha_alta_medica IS NULL
                    AND em.fecha_egreso_administrativa IS NULL "; 
      }else if($ubicacion==5){ //!   ALTA MÉDICA = 5
        $where .= " AND em.fecha_registro_ingreso IS NOT NULL 
                    AND em.fecha_registro_admision IS NOT NULL 
                    AND em.fecha_atencion IS NOT NULL 
                    AND em.fecha_alta_medica IS NOT NULL
                    AND em.fecha_egreso_administrativa IS NULL "; 
      }else if($ubicacion==6){ //!  ALTA ADMINISTRATIVA = 6
        $where .= " AND em.fecha_registro_ingreso IS NOT NULL 
                    AND em.fecha_registro_admision IS NOT NULL 
                    AND em.fecha_atencion IS NOT NULL 
                    AND em.fecha_alta_medica IS NOT NULL
                    AND em.fecha_egreso_administrativa IS NOT NULL "; 
      }

      if($id_prioridad!=''){
        $where .= " AND em.id_prioridad = ".$id_prioridad;  
      } 

      if($id_topico!=''){
        $where .= " AND em.id_triaje = ".$id_topico;  
      } 

      if($id_motivo_ingreso!=''){
        $where .= " AND em.id_motivo_ingreso = ".$id_motivo_ingreso;  
        
      }

      if($id_tipo_violencia!=''){
        //if($tipo_consulta=='exportar'){ 
          $where .= " AND tv.id_violencia = ".$id_tipo_violencia;  
        //}  
      }



      $cad = "WITH ultimas_evaluaciones AS (
                    SELECT DISTINCT ON (ev.id_emergencia)
                        ev.id_emergencia,
                        ev.id_medico,
                        ev.fecha_atencion
                    FROM evaluaciones ev
                        JOIN prestaciones pr ON ev.id_prestacion = pr.id
                    WHERE ev.id_tipo_atencion = 2
                        and pr.id_establecimiento = ".$id_establecimiento."
                    ORDER BY ev.id_emergencia, ev.id DESC
                )
              SELECT ROW_NUMBER() OVER(ORDER BY em.created_at DESC) AS num,
                em.id as cod_emergencia, pri.descripcion as prioridad,  tr.descripcion as topico, to_char(em.created_at::date,'DD/MM/YYYY') AS fecha_triaje, 
                to_char(em.created_at, 'HH:MI:SS AM')  AS hora_triaje, tb.descripcion as parentesco, ase.sexo as sexo_asegurado, em.cod_siteds_susalud,
                date_part('year',age(to_date(to_char(ase.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) as edad_asegurado, em.dni_conducido, em.id_condi_egres_med,
                ase.nro_doc_ident, ase.paterno, ase.materno, ase.nombre, ase.tipo_doc_ident, ase.fecha_nac, em.observacion_triaje, em.nombre_conducido,
                med.paterno as apepaterno_profesional, med.materno as apematerno_profesional, med.name as nombre_profesional, em.estado, em.secuencia, 
                em.flag_triaje, em.ruta_triaje, em.fecha_firma_triaje, em.secuencia, ase.telefono, ase.telefono2, ase.domicilio, em.estado,
                to_char(em.totem_at::date,'DD/MM/YYYY') AS fecha_totem,  ase.tipo_doc_ident as tipo_doc_ident,
                to_char(em.totem_at::time, 'HH:MI:SS AM')  AS hora_totem, em.observacion_admision,
                est.nombre as establecimiento, for_ing.descripcion as forma_ingreso, mot_ing.descripcion as motivo_ingreso, e_ing.descripcion as estado_ingreso,v.descripcion as violencia,
                em.id_tipo_egres_med, em.cod_totem ,
                to_char(em.fecha_registro_admision::date,'DD/MM/YYYY') AS fecha_admision ,
                to_char(em.fecha_registro_admision::time,'HH:MI:SS AM') AS hora_admision,
                user_adm.paterno as apepaterno_user_adm, user_adm.materno as apematerno_user_adm, user_adm.name as nombre_user_adm , 
                to_char(em.a_llamado_at::date,'DD/MM/YYYY') AS fecha_llamado, 
                to_char(em.a_llamado_at, 'HH:MI:SS AM')  AS hora_llamado ,
                user_llama.paterno as apematerno_llamado, user_llama.materno as apepaterno_llamado, user_llama.name as nombre_llamado, 

                to_char(em.fecha_alta_medica::date,'DD/MM/YYYY') AS fecha_alta_medica , 
                TO_CHAR(em.hora_alta_medica::time,'HH:MI:SS AM') AS hora_alta_medica_alterna,
                TO_CHAR(em.fecha_alta_medica::time,'HH:MI:SS AM') AS hora_alta_medica,
                consul.nombre as servicio_destino,

                user_alta.paterno as apepaterno_user_alta, 
                user_alta.materno as apematerno_user_alta, 
                user_alta.name as nombre_user_alta, 
                
                user_atencion.paterno as apepaterno_user_atencion, 
                user_atencion.materno as apematerno_user_atencion, 
                user_atencion.name as nombre_user_atencion, 
                
                case 
                	when em.id_tipo_egres_med = '1' then 'ALTA'
                	when em.id_tipo_egres_med = '2' then 'HOSPITALIZACIÓN'
                	when em.id_tipo_egres_med = '3' then 'TRASLADO'
                	when em.id_tipo_egres_med = '4' then 'FALLECIDO'
                  when em.id_tipo_egres_med = '6' then 'FUGA'
                	when em.id_tipo_egres_med = '7' then 'RETIRO VOLUNTARIO'
                end as tipo_egreso_medico,
                
                to_char(em.fecha_egreso_administrativa::date,'DD/MM/YYYY') AS fecha_egreso_administrativa , 
                TO_CHAR(em.fecha_egreso_administrativa::time,'HH:MI:SS AM') AS hora_egreso_administrativa,
                TO_CHAR(em.hora_egreso_administrativa::time,'HH:MI:SS AM') AS hora_egreso_administrativa_alterna,

                user_adminis.paterno as apepaterno_user_admins, 
                user_adminis.materno as apematerno_user_admins, 
                user_adminis.name as nombre_user_admins,

                to_char(em.fecha_registro_ingreso::date,'DD/MM/YYYY') AS fecha_registro_ingreso_triaje , 
                to_char(em.hora_registro_ingreso::time,'HH:MI:SS AM') AS hora_registro_ingreso_triaje,   
                to_char(em.fecha_registro_admision::date,'DD/MM/YYYY') AS fecha_registro_admision ,   
                diag_cab.nombre as diagnostico_cab, diag_cab1.nombre as diagnostico_cab1, diag_cab2.nombre as diagnostico_cab2, 
                to_char(em.fecha_atencion::date,'DD/MM/YYYY') AS fecha_atencion , 
                TO_CHAR(em.fecha_atencion::time,'HH:MI:SS AM') AS hora_atencion,

                case
               		when em.fecha_registro_ingreso is null then ''
               		when totem_at is null then ''
               		when TO_CHAR(em.fecha_registro_ingreso::time,'HH24:MI:SS') != '00:00:00'  then 
               		CONCAT(DATE_PART('day',AGE(em.fecha_registro_ingreso,totem_at)),' DÍAS  ',
	                DATE_PART('hour',AGE(em.fecha_registro_ingreso,totem_at)),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_registro_ingreso,totem_at)),' MINUTOS')
               		else
               		CONCAT(DATE_PART('day',AGE((em.fecha_registro_ingreso + em.hora_registro_ingreso ::time),totem_at)),' DÍAS  ',
	                DATE_PART('hour',AGE((em.fecha_registro_ingreso + em.hora_registro_ingreso ::time),totem_at)),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_registro_ingreso + em.hora_registro_ingreso ::time),totem_at)),' MINUTOS')
               	end as trama_triaje

,

				case
               		when em.fecha_registro_admision is null then ''
               		when em.fecha_registro_ingreso is null  then ''
               		
	                when TO_CHAR(em.fecha_registro_admision::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_registro_ingreso::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_registro_admision,em.fecha_registro_ingreso)),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_registro_admision,em.fecha_registro_ingreso)),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_registro_admision,em.fecha_registro_ingreso)),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_registro_admision::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_registro_ingreso::time,'HH24:MI:SS') = '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_registro_admision,(em.fecha_registro_ingreso + em.hora_registro_ingreso ::time))),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_registro_admision,(em.fecha_registro_ingreso + em.hora_registro_ingreso ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_registro_admision,(em.fecha_registro_ingreso + em.hora_registro_ingreso ::time))),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_registro_admision::time,'HH24:MI:SS') = '00:00:00' and TO_CHAR(em.fecha_registro_ingreso::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE((em.fecha_registro_admision + em.hora_registro_admision ::time),em.fecha_registro_ingreso)),' DÍAS ', 
	                DATE_PART('hour',AGE((em.fecha_registro_admision + em.hora_registro_admision ::time),em.fecha_registro_ingreso)),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_registro_admision + em.hora_registro_admision ::time),em.fecha_registro_ingreso)),' MINUTOS')
               		else
               		CONCAT(DATE_PART('day',AGE((em.fecha_registro_admision + em.hora_registro_admision ::time),(em.fecha_registro_ingreso + em.hora_registro_ingreso ::time))),' DÍAS  ',
	                DATE_PART('hour',AGE((em.fecha_registro_admision + em.hora_registro_admision ::time),(em.fecha_registro_ingreso + em.hora_registro_ingreso ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_registro_admision + em.hora_registro_admision ::time),(em.fecha_registro_ingreso + em.hora_registro_ingreso ::time))),' MINUTOS')
	                
               	end as trama_admision
,

				case
               		when em.fecha_atencion is null then ''
               		when em.fecha_registro_admision is null then ''
               		
	                
	                when TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_registro_admision::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_atencion,em.fecha_registro_admision)),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_atencion,em.fecha_registro_admision)),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_atencion,em.fecha_registro_admision)),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_registro_admision::time,'HH24:MI:SS') = '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_atencion,(em.fecha_registro_admision + em.hora_registro_admision ::time))),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_atencion,(em.fecha_registro_admision + em.hora_registro_admision ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_atencion,(em.fecha_registro_admision + em.hora_registro_admision ::time))),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') = '00:00:00' and TO_CHAR(em.fecha_registro_admision::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE((em.fecha_atencion + em.hora_atencion ::time),em.fecha_registro_admision)),' DÍAS ', 
	                DATE_PART('hour',AGE((em.fecha_atencion + em.hora_atencion ::time),em.fecha_registro_admision)),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_atencion + em.hora_atencion ::time),em.fecha_registro_admision)),' MINUTOS')
               		else
               		CONCAT(DATE_PART('day',AGE((em.fecha_atencion + em.hora_atencion ::time),(em.fecha_registro_admision + em.hora_registro_admision ::time))),' DÍAS  ',
	                DATE_PART('hour',AGE((em.fecha_atencion + em.hora_atencion ::time),(em.fecha_registro_admision + em.hora_registro_admision ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_atencion + em.hora_atencion ::time),(em.fecha_registro_admision + em.hora_registro_admision ::time))),' MINUTOS')
	           end as trama_atencion

,
        COALESCE(m_ev.paterno || ' ' || m_ev.materno || ', ' || m_ev.nombre,'')::VARCHAR AS ultimo_personal_evaluacion,
        COALESCE(to_char(eva.fecha_atencion,'DD/MM/YYYY'), '') AS ultima_fecha_atencion_evaluacion, 
        COALESCE(TO_CHAR(eva.fecha_atencion,'HH:MI:SS AM'), '') AS ultima_hora_atencion_evaluacion,

				case
               		when em.fecha_alta_medica is null then ''
               		when em.fecha_atencion is null  then ''
               		
	                when TO_CHAR(em.fecha_alta_medica::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_alta_medica,em.fecha_atencion)),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_alta_medica,em.fecha_atencion)),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_alta_medica,em.fecha_atencion)),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_alta_medica::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') = '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_alta_medica,(em.fecha_atencion + em.hora_atencion ::time))),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_alta_medica,(em.fecha_atencion + em.hora_atencion ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_alta_medica,(em.fecha_atencion + em.hora_atencion ::time))),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_alta_medica::time,'HH24:MI:SS') = '00:00:00' and TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE((em.fecha_alta_medica + em.hora_alta_medica ::time),em.fecha_atencion)),' DÍAS ', 
	                DATE_PART('hour',AGE((em.fecha_alta_medica + em.hora_alta_medica ::time),em.fecha_atencion)),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_alta_medica + em.hora_alta_medica ::time),em.fecha_atencion)),' MINUTOS')
               		else
               		CONCAT(DATE_PART('day',AGE((em.fecha_alta_medica + em.hora_alta_medica ::time),(em.fecha_atencion + em.hora_atencion ::time))),' DÍAS  ',
	                DATE_PART('hour',AGE((em.fecha_alta_medica + em.hora_alta_medica ::time),(em.fecha_atencion + em.hora_atencion ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_alta_medica + em.hora_alta_medica ::time),(em.fecha_atencion + em.hora_atencion ::time))),' MINUTOS')
	                
	                
	                
               	end as trama_alta_medica
,

				case
               		when em.fecha_egreso_administrativa is null then ''
               		when em.fecha_alta_medica is null  then ''
               		when TO_CHAR(em.fecha_egreso_administrativa::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_alta_medica::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_egreso_administrativa,em.fecha_alta_medica)),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_egreso_administrativa,em.fecha_alta_medica)),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_egreso_administrativa,em.fecha_alta_medica)),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_egreso_administrativa::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_alta_medica::time,'HH24:MI:SS') = '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_egreso_administrativa,(em.fecha_alta_medica + em.hora_alta_medica ::time))),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_egreso_administrativa,(em.fecha_alta_medica + em.hora_alta_medica ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_egreso_administrativa,(em.fecha_alta_medica + em.hora_alta_medica ::time))),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_egreso_administrativa::time,'HH24:MI:SS') = '00:00:00' and TO_CHAR(em.fecha_alta_medica::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),em.fecha_alta_medica)),' DÍAS ', 
	                DATE_PART('hour',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),em.fecha_alta_medica)),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),em.fecha_alta_medica)),' MINUTOS')
               		else
               		CONCAT(DATE_PART('day',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),(em.fecha_alta_medica + em.hora_alta_medica ::time))),' DÍAS  ',
	                DATE_PART('hour',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),(em.fecha_alta_medica + em.hora_alta_medica ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),(em.fecha_alta_medica + em.hora_alta_medica ::time))),' MINUTOS')
               	end as trama_alta_administrativa
,

				case
               		when em.fecha_egreso_administrativa is null then ''
               		when em.fecha_atencion is null  then ''
               		
	                when TO_CHAR(em.fecha_egreso_administrativa::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_egreso_administrativa,em.fecha_atencion)),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_egreso_administrativa,em.fecha_atencion)),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_egreso_administrativa,em.fecha_atencion)),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_egreso_administrativa::time,'HH24:MI:SS') != '00:00:00' and TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') = '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE(em.fecha_egreso_administrativa,(em.fecha_atencion + em.hora_atencion ::time))),' DÍAS ', 
	                DATE_PART('hour',AGE(em.fecha_egreso_administrativa,(em.fecha_atencion + em.hora_atencion ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE(em.fecha_egreso_administrativa,(em.fecha_atencion + em.hora_atencion ::time))),' MINUTOS')
	                --
	                when TO_CHAR(em.fecha_egreso_administrativa::time,'HH24:MI:SS') = '00:00:00' and TO_CHAR(em.fecha_atencion::time,'HH24:MI:SS') != '00:00:00' then 
               		CONCAT( DATE_PART('day',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),em.fecha_atencion)),' DÍAS ', 
	                DATE_PART('hour',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),em.fecha_atencion)),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),em.fecha_atencion)),' MINUTOS')
               		else
               		CONCAT(DATE_PART('day',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),(em.fecha_atencion + em.hora_atencion ::time))),' DÍAS  ',
	                DATE_PART('hour',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),(em.fecha_atencion + em.hora_atencion ::time))),' HORAS ', 
	                DATE_PART('MINUTE',AGE((em.fecha_egreso_administrativa + em.hora_egreso_administrativa ::time),(em.fecha_atencion + em.hora_atencion ::time))),' MINUTOS')
	            end as trama_alta_administrativa_sin_medica,

                u_triaje_anu.id as id_user_anula_triaje,
	              concat(u_triaje_anu.paterno,' ',u_triaje_anu.materno,' ',u_triaje_anu.name) as user_anula_triaje,
                concat(u_admision_anu.paterno,' ',u_admision_anu.materno,' ',u_admision_anu.name) as user_anula_admision,
                concat(u_atencion_anu.paterno,' ',u_atencion_anu.materno,' ',u_atencion_anu.name) as user_anula_atencion,
                concat(u_alta_anul.paterno,' ',u_alta_anul.materno,' ',u_alta_anul.name) as user_anula_alta,
                case 
                  when em.id_usuario_anulacion is not null and (em.motivo_anulacion = '' or em.motivo_anulacion is null) then 'EL USUARIO NO HA CONSIGADO MOTIVO DE ANULACION EN TRIAJE'
                  else em.motivo_anulacion
                end as motivo_anulacion_triaje,
                case 
                  when em.id_usuario_anulacion_admision is not null and (em.motivo_anulacion_admision = '' or em.motivo_anulacion_admision is null) then 'EL USUARIO NO HA CONSIGADO MOTIVO DE ANULACION EN ADMISION'
                  else em.motivo_anulacion_admision
                end as motivo_anulacion_admisión,
                case 
                  when em.id_delete_atencion is not null and (em.observacion_elimina_paciente = '' or em.observacion_elimina_paciente is null) then 'EL USUARIO NO HA CONSIGADO MOTIVO DE ANULACION EN ATENCIÓN'
                  else em.observacion_elimina_paciente
                end as motivo_anulacion_atencion,
                em.observacion_elimina_paciente,
                case 
                  when em.id_anula_alta is not null and (em.motivo_anula_alta = '' or em.motivo_anula_alta is null) then 'EL USUARIO NO HA CONSIGADO MOTIVO DE ANULACION EN ALTA'
                  else em.motivo_anula_alta
                end as motivo_anulacion_alta


                ";

            if($tipo_consulta=='exportar'){ 
              $cad .= "
                  ,p.id_ext as id_emergencia,
                  p.id as id_prestacion,
                  to_char(p.created_at::date,'DD/MM/YYYY') as fecha_procedimiento , 
                  to_char(p.created_at, 'HH:MI:SS AM')  AS hora_procedimiento,
                  concat(pr.codigo,' - ',pr.descripcion)  as procedimiento, 
                  concat(d.codigo,' - ',d.nombre)  as diagnostico, 
                  concat(m.paterno,' ',m.materno,' ',m.nombre) as medico_responsable,
                  c2.comisaria  ,
                  ev.lugar_accidente ,
                  v.descripcion as violencia,
                  tv.descripcion as tipo_violencia
                   ";
            }
                 
            $cad .= "  
                FROM emergencias em 
                INNER JOIN asegurados ase ON em.id_asegurado = ase.id
                LEFT JOIN tipo_beneficiarios tb ON ase.id_tipo_beneficiario = tb.id
                LEFT JOIN users med ON em.id_medico_triaje = med.id
                LEFT JOIN triajes tr ON em.id_triaje = tr.id
                LEFT JOIN prioridades pri ON tr.prioridad = pri.id 
                LEFT JOIN establecimientos est ON em.id_establecimiento = est.id 
                LEFT JOIN forma_ingresos for_ing ON em.id_forma_ingreso = for_ing.id   
                LEFT JOIN estado_ingresos e_ing ON em.id_estado_ingreso = e_ing.id
                LEFT JOIN categorias mot_ing ON em.id_motivo_ingreso = mot_ing.id
                LEFT JOIN users user_adm ON em.id_usuario_admision = user_adm.id
                LEFT JOIN users user_llama ON em.a_llamado_us = user_llama.id
                LEFT JOIN consultorios consul ON em.id_medico_egreso = consul.id
                LEFT JOIN users user_alta ON em.id_medico_egreso = user_alta.id
                LEFT JOIN users user_adminis ON em.id_user_egreso_administrativo = user_adminis.id
                
                LEFT JOIN diagnosticos diag_cab ON em.id_diag_cab = diag_cab.id  
                LEFT JOIN diagnosticos diag_cab1 ON em.id_diag_cuer1 = diag_cab1.id  
                LEFT JOIN diagnosticos diag_cab2 ON em.id_diag_cuer2 = diag_cab2.id 
                 
                LEFT JOIN users user_atencion ON em.id_user_atencion = user_atencion.id 
                
                ---nuevo 24/08
                LEFT join users u_triaje_anu on u_triaje_anu.id = em.id_usuario_anulacion
                LEFT join users u_admision_anu on u_admision_anu.id = em.id_usuario_anulacion_admision
                LEFT join users u_atencion_anu on u_atencion_anu.id = em.id_delete_atencion
                LEFT join users u_alta_anul on u_alta_anul.id = cast(em.id_anula_alta as integer)
                ---fin nuevo 24/08
                LEFT JOIN emergencia_violencias ev on em.id = ev.id_emergencia
                LEFT JOIN violencia v on ev.id_violencia = v.id
                LEFT JOIN tipo_violencia tv on ev.id_modalidad=tv.id_tipo_violencia
                LEFT JOIN ultimas_evaluaciones eva ON eva.id_emergencia = em.id
				        LEFT JOIN medicos m_ev ON eva.id_medico = m_ev.id AND eva.id_emergencia IS NOT NULL           
                ";
                
            if($tipo_consulta=='exportar'){ 
              $cad .= "  
                  
                  LEFT JOIN comisarias c2 on c2.id = ev.id_comisaria 
                  LEFT JOIN prestaciones p on em.id = p.id_ext
                  LEFT JOIN medicos m on p.id_medico = m.id
                  LEFT JOIN prestacion_procedimientos pp on p.id = pp.id_prestacion 
                  LEFT JOIN procedimientos pr on pp.id_procedimiento = pr.id 
                  LEFT JOIN (select distinct id_prestacion,id_diagnostico from receta_diagnosticos) rd on p.id = rd.id_prestacion
                  LEFT JOIN diagnosticos d on rd.id_diagnostico = d.id  
                ";   
            }

      //$tipo_consulta : exportar & consultar
      
      $cad .= $where;
      if($tipo_consulta=='exportar'){
        $cad .= " ORDER BY em.created_at, pp.id"; 
      } else if($tipo_consulta=='consultar'){
        $cad_total =  $cad ;
        $cad .= " ORDER BY em.created_at ASC LIMIT " . $NumeroRegistros . " OFFSET " . ($NumeroPagina - 1) * $NumeroRegistros . ";"; 
      } 
         
      $data = DB::SELECT($cad);
      if($tipo_consulta=='consultar'){
        $totalrows = count(DB::SELECT($cad_total));  
        return array('data' => $data, 'totalrows' => $totalrows); 
      } else{
        return array('data' => $data); 
      }
 
  }  
  

  public function listado_de_asgurados_by_busqueda_atenciones($campo_de_busqueda, $valor_de_busqueda) { 
    
    $where = '';
    $id_establecimiento = Auth::user()->id_establecimiento;
    if($campo_de_busqueda == 3){ 
 
      $cad="SELECT 
 
      DISTINCT asegurados.nro_doc_ident ,
      asegurados.cip, 
      asegurados.grado,  
      asegurados.paterno, 
      asegurados.materno, 
      asegurados.nombre, 
      asegurado_historias.nro_historia,
      asegurados.id_estado_reg, 
      tipo_beneficiarios.descripcion as tipo_beneficiario   

      FROM emergencias  
      LEFT JOIN asegurados ON emergencias.id_asegurado =   asegurados.id 
      LEFT JOIN tipo_beneficiarios ON asegurados.id_tipo_beneficiario =   tipo_beneficiarios.id 
      LEFT JOIN asegurado_historias ON asegurado_historias.id_asegurado = asegurados.id  
      WHERE asegurados.nombre != ''
      AND asegurados.paterno != ''
      AND asegurados.materno != '' 
      AND asegurado_historias.id_establecimiento = $id_establecimiento 
      AND CAST(emergencias.created_at AS DATE) BETWEEN '".$valor_de_busqueda."' AND '".$valor_de_busqueda."'  
       
      " . $where ; 
  
      $data = DB::select($cad);   
      return $data;  

    }else{
        if($campo_de_busqueda == 1){ //NÚMERO DE DNI
          if ($campo_de_busqueda != "")  $where .= " AND nro_doc_ident = '".$valor_de_busqueda."' "; 

        }else if($campo_de_busqueda == 2){ //APELLIDOS
          if ($campo_de_busqueda != "")  $where .= " AND CONCAT(paterno,' ',materno) ILIKE '%".$valor_de_busqueda."%'"; 

        } else if($campo_de_busqueda == 4){ //NÚMERO DE CARNET
          if ($campo_de_busqueda != "")  $where .= " AND cip = '".$valor_de_busqueda."' "; 

        }else if($campo_de_busqueda == 5){ //HC GENERAL
          if ($campo_de_busqueda != "")  $where .= " AND nro_historia = '".$valor_de_busqueda."' "; 
        } 

        $cad="SELECT asegurados.*, asegurado_historias.nro_historia, tipo_beneficiarios.descripcion as tipo_beneficiario   FROM asegurados 
          LEFT JOIN tipo_beneficiarios ON asegurados.id_tipo_beneficiario =   tipo_beneficiarios.id 
          LEFT JOIN asegurado_historias ON asegurado_historias.id_asegurado = asegurados.id  
          WHERE asegurados.nombre != ''
          AND asegurados.paterno != ''
          AND asegurados.materno != '' 
          AND asegurado_historias.id_establecimiento = $id_establecimiento 
          " . $where ; 
      
        $data = DB::select($cad);   
        return $data;  
    }
  }

  
  public function listado_de_atenciones_by_asegurado($dni_beneficiario) {
     
    $id_establecimiento = Auth::user()->id_establecimiento;
    $cad="SELECT emergencias.*,triajes.descripcion AS triaje FROM emergencias 
    LEFT JOIN triajes on emergencias.id_triaje = triajes.id 
    WHERE 
    emergencias.dni_beneficiario='$dni_beneficiario' AND
    emergencias.id_establecimiento='$id_establecimiento'
    ORDER BY id DESC";
    
    $data = DB::select($cad);   
    return $data;  
  }

  public function listar_prestacion_ajax_emergencia($p){
		return $this->readFunctionPostgres('sp_prestacion_emergencia',$p);
	}
	
  public function listar_atencion_emergencia_ajax($p)
  {
    return $this->readFunctionPostgres('listar_atencion_emergencia_ajax', $p);
  }
  
  public function consultar_ticket($id_ticket){
    
    $cad = "SELECT 
      tickets.motivo_anulacion,tickets.id_estado_tv,
      tickets.id as cod_emergencia, 
      tickets.cantidad_llamada,
      to_char(tickets.created_at::date,'DD/MM/YYYY') AS fecha_triaje, 
      to_char(tickets.created_at, 'HH:MI:SS AM')  AS hora_triaje,  tickets.id_emergencia_a as id_emergencia, 
      case 
        when tickets.id_asegurado = 0 then tickets.dni_reniec
        else a.nro_doc_ident
        end as nro_doc_ident,
      case 
        when tickets.id_asegurado = 0 then tickets.nombres_reniec
        else concat(a.nombre,' ',a.paterno,' ',a.materno) 
      end as asegurado ,  

      CONCAT (tickets.letra ,'',tickets.numero) as cod_totem,
      CONCAT (tickets.letra ,'',tickets.numero) as cod_totem,

      to_char(tickets.anulado_at::date,'DD/MM/YYYY') AS fecha_triaje_anulado, 
      to_char(tickets.anulado_at, 'HH:MI:SS AM')  AS hora_triaje_anulado,
      CONCAT(user_adm.paterno,' ',user_adm.materno,' ',user_adm.name) AS anulado_us,
      totem_opciones.nombre as totem_nombre

      FROM tickets as tickets 

      INNER JOIN emergencias em ON  em.id_ticket_admision = tickets.id 
      LEFT JOIN asegurados a on a.id= tickets.id_asegurado  
      LEFT JOIN users user_adm ON tickets.anulado_us = user_adm.id 
      LEFT JOIN totem_opciones as totem_opciones on tickets.id_totem_opcion =totem_opciones.id
      
      WHERE tickets.id='$id_ticket'";

      $data = DB::select($cad);   
      return $data; 
  }

  public function GetReporteEmergenciaalta($fechaDesde="", $fechaHasta="", $id_establecimiento=0,$estado,$id_tipo_egreso,$dni="",$paciente="", $id_prioridad, $id_topico) {
    $cad = "SELECT 
            tb.codigo,
            e.dni_beneficiario,
            a.fecha_nac,
            a.sexo as sexo,
            a.nombre,
            a.paterno,
            a.materno,
            a.grado,
            a.telefono as telefono_paciente,
            p.descripcion as prioridad ,
            a2.grado as grado2,
            tb.descripcion as parentesco,
            DATE_PART('year', CURRENT_DATE) - DATE_PART('year', TO_DATE(a.fecha_nac, 'DD-MM-YY')) as edadpaciente, 
            to_char(e.fecha_registro_ingreso::date,'YYYY/MM/DD') as fecha_registro_ingreso,
            to_char(e.fecha_alta_medica::date,'YYYY/MM/DD') as fecha_alta_medica,
            t.descripcion as servicio,
            CASE 
            WHEN e.id_tipo_egres_med  = '1' THEN 'DOCIMICILIARIO'
            WHEN e.id_tipo_egres_med  = '2' then 'HOSPITALIZACION'
            WHEN e.id_tipo_egres_med  = '3' THEN 'TRANSFERIDO'
            WHEN e.id_tipo_egres_med  = '4' then 'FALLECIDO' 
            WHEN e.id_tipo_egres_med  = '6' THEN 'FUGADO'
            WHEN e.id_tipo_egres_med  = '7' THEN 'RETIRO VOLUNTARIO'
            else ' ' end  AS condicon_egreso,
            a.sexo as sexo,
            d.codigo as cie101,
            d.nombre  as diagnostico1,
            d2.codigo as cie102,
            d2.nombre  as diagnostico2,
            d3.codigo as cie103,
            d3.nombre  as diagnostico3
            FROM  emergencias e 
            inner join asegurados a on e.dni_beneficiario = a.nro_doc_ident
            left join asegurados a2 on a.id_asegurado_titular=a2.id
            inner join triajes t on e.id_triaje = t.id 
            left join prioridades p on e.id_prioridad=p.id
            left join tipo_beneficiarios tb on tb.id=e.id_tipo_beneficiario
            left join diagnosticos d on e.id_diag_cab = d.id 
            left join diagnosticos d2 on e.id_diag_cuer1 = d2.id
            left join diagnosticos d3 on e.id_diag_cuer2 = d3.id ";
            
            $cad .= " Where 1=1";
            
            if($estado == "5"){
              $cad .= "  AND e.estado = 5";
            }else{
              $cad .= " AND   e.estado = 3 and e.id_delete_atencion is NULL";
            }

            if($dni != 0){
              $cad .= "  AND e.dni_beneficiario ='$dni'";
            }

            if($id_prioridad != 0){
              $cad .= "  AND e.id_prioridad =".$id_prioridad;
            }

            if($id_topico != 0){
              $cad .= "  AND e.id_triaje =".$id_topico;
            }

            if($id_tipo_egreso != 0){
              $cad .= "  AND e.id_tipo_egres_med =".$id_tipo_egreso;
            }

                        if($id_establecimiento != 0){
              $cad .= "  AND e.id_establecimiento =".$id_establecimiento;
            }

    if ($fechaDesde != ""):
        $cad .= " AND CAST(e.fecha_registro_ingreso AS date) >= '$fechaDesde'";
    endif;
    if ($fechaHasta != ""):
        $cad .= " AND CAST(e.fecha_registro_ingreso AS date) <= '$fechaHasta'";
    endif;
   
      
    $cad .= " ORDER BY e.fecha_alta_medica ";
    
    $data = DB::select($cad);

    return $data;
  }

  public function listar_alta_ajax_emergencia($p){
		return $this->readFunctionPostgres('sp_alta_emergencia',$p);
	}

  public function listar_reporte_2_ajax($p)
  {
    return $this->readFunctionPostgres('listar_reporte_2_ajax', $p);
  }
  public function listar_reporte_1_ajax($p)
  {
    return $this->readFunctionPostgres('listar_reporte_1_ajax', $p);
  }
  public function listar_reporte_5_ajax($p)
  {
    return $this->readFunctionPostgres('listar_reporte_5_ajax', $p);
  }
  public function listar_reporte_16_ajax($p)
  {
    return $this->readFunctionPostgres('listar_reporte_16_ajax', $p);
  }
  public function listar_reporte_17_ajax($p)
  {
    return $this->readFunctionPostgres('listar_reporte_17_ajax', $p);
  }

  public function GetReporteEmergencia1($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia1',$p);
	}
  public function GetReporteEmergencia2($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia2',$p);
	}

  
  public function GetReporteEmergencia3g($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia3g',$p);
	}

  public function GetReporteEmergencia3t($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia3t',$p);
	}

  public function GetReporteEmergencia3c($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia3c',$p);
	}

  public function GetReporteEmergencia3a($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia3a',$p);
	}

  public function GetReporteEmergencia3o($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia3o',$p);
	}
  public function GetReporteEmergencia3m($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia3m',$p);
	}
  public function GetReporteEmergencia3p($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia3p',$p);
	}
  public function GetReporteEmergencia3ha($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia3ha',$p);
	}
  public function GetReporteEmergencia3ho($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia3ho',$p);
	}

   public function GetReporteEmergencia4g($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia4g',$p);
	}

  public function GetReporteEmergencia4t($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia4t',$p);
	}

  public function GetReporteEmergencia4c($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia4c',$p);
	}

  public function GetReporteEmergencia4a($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia4a',$p);
	}

  public function GetReporteEmergencia4o($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia4o',$p);
	}
  public function GetReporteEmergencia4m($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia4m',$p);
	}
  public function GetReporteEmergencia4p($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia4p',$p);
	}
  public function GetReporteEmergencia4ha($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia4ha',$p);
	}
  public function GetReporteEmergencia4ho($fechaDesde, $fechaHasta, $id_establecimiento){
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;

    return $this->readFunctionPostgres('GetReporteEmergencia4ho',$p);
	}

public function GetReporteEmergencia5($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia5',$p);
}

public function GetReporteEmergencia6($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia6',$p);
}

public function GetReporteEmergencia7($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia7',$p);
}
public function GetReporteEmergencia8($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia8',$p);
}
public function GetReporteEmergencia9($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia9',$p);
}
public function GetReporteEmergencia10($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia10',$p);
}
public function GetReporteEmergencia11($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia11',$p);
}

public function GetReporteEmergencia12($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia12',$p);
}
public function GetReporteEmergencia13($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia13',$p);
}
public function GetReporteEmergencia14($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia14',$p);
}
public function GetReporteEmergencia15($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia15',$p);
}
public function GetReporteEmergencia16($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia16',$p);
}
public function GetReporteEmergencia17($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia17',$p);
}
public function GetReporteEmergencia18($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia18',$p);
}

public function enviartrama($id_emergencia){
  $data = DB::select("SELECT sp_insertar_tramas_emergencia_2 ($id_emergencia)");
}

public function GetReporteEmergencia19($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia19',$p);
}
public function GetReporteEmergencia20($fechaDesde, $fechaHasta, $id_establecimiento){
  $p = array();
  $p[0]=$fechaDesde;
  $p[1]=$fechaHasta;
  $p[2]=$id_establecimiento;
  $p[3]=1000000000;
  $p[4]=1000000000;
  $p[5]=1000000000;

  return $this->readFunctionPostgres('GetReporteEmergencia20',$p);
}

  public function GetReporteEmergencia21($fechaDesde, $fechaHasta, $id_establecimiento)
  {
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;
    return $this->readFunctionPostgres('GetReporteEmergencia21',$p);
  }

  public function GetReporteEmergencia22($fechaDesde, $fechaHasta, $id_establecimiento)
  {
    $p = array();
    $p[0]=$fechaDesde;
    $p[1]=$fechaHasta;
    $p[2]=$id_establecimiento;
    $p[3]=1000000000;
    $p[4]=1000000000;
    $p[5]=1000000000;
    return $this->readFunctionPostgres('GetReporteEmergencia22',$p);
  }

  public static function RegistrarAnulacionAlta($id,$id_tipo_atencion,$id_user,$fechaActual,$motivo,$id_establecimiento) {
    $cad = "Select sp_insert_alta_anulada(?,?,?,?,?,?)";
    $data = DB::select($cad, array($id,$id_tipo_atencion,$id_user,$fechaActual,$motivo,$id_establecimiento));
    
        return $data[0]->sp_insert_alta_anulada;
    }

  public function GetBuscaPacienteTriajeEmergencia($dni_beneficiario){


    $cad = "SELECT ROW_NUMBER() OVER(ORDER BY em.created_at DESC) AS num,
                em.id as cod_emergencia, pri.descripcion as prioridad,  tr.descripcion as topico, 
                to_char(em.created_at::date,'DD/MM/YYYY') AS fecha_triaje, em.estado,
                to_char(em.created_at, 'HH:MI:SS AM')  AS hora_triaje, 
                ase.nro_doc_ident, ase.paterno, ase.materno, ase.nombre, ase.tipo_doc_ident, em.observacion_triaje, 
                med.paterno as apepaterno_profesional, med.materno as apematerno_profesional, med.name as nombre_profesional, 
                est.nombre as establecimiento
              FROM emergencias em 
                INNER JOIN asegurados ase ON em.id_asegurado = ase.id
                LEFT JOIN tipo_beneficiarios tb ON ase.id_tipo_beneficiario = tb.id
                LEFT JOIN users med ON em.id_medico_triaje = med.id
                LEFT JOIN triajes tr ON em.id_triaje = tr.id
                LEFT JOIN prioridades pri ON tr.prioridad = pri.id 
                LEFT JOIN establecimientos est ON em.id_establecimiento = est.id 
                where ase.nro_doc_ident = '".$dni_beneficiario."' and em.estado!=0 ";
              
    $data = DB::SELECT($cad);
    return $data;
  }

  public function InsertaEstancia($id_emergencia, $id_prestacion,$id_evaluacion, $tipo_atencion, $cpm_alta, $fecha_atencion, $id_user , $dia_estancia) {
    $cad = "Select sp_inserta_cpm_estancia(?, ?, ?, ?, ?, ?, ?, ?)";
    
    $new = DB::select($cad, array($id_emergencia, $id_prestacion,$id_evaluacion,$tipo_atencion, $cpm_alta, $fecha_atencion, $id_user , $dia_estancia)); 
    return $new;
  }

  public function obtenerRegistroEmergencia_($id){
    $cad = "
      select
      e.id,
      e.fecha_alta_medica,
      e.hora_alta_medica,
      tdi.descripcion tipo_doc_ident,
      a.id_tipo_doc_ident,
      a.nro_doc_ident,
      a.nombre,
      a.paterno,
      a.materno,
      a.fecha_nac,
      a.sexo,
      --to_char(age(TO_DATE(a.fecha_nac , 'DD/MM/YYYY')), 'YY \"años con\" MM \"meses y\" DD \"dias\"') as fec_nacim_descrip,
      tb.descripcion as parentesco,
      ah.nro_historia as nro_historia_paciente,
      a.id_raza,
      a.id_tipo_sangre,
      a.id_estado_civil,
      a.id_religion,
      a.id_grado_instruccion,
      a.id_ocupacion,
      --Procedencia
      a.domicilio,
      a.id_distrito id_distrito_domicilio,
      d.id_provincia id_provincia_domicilio,
      p2.id_departamento id_departamento_domicilio,
      m.id id_medico,
      m.dni medico_ndocumento, 
      m.paterno medico_paterno, 
      m.materno medico_materno, 
      m.nombre medico_nombre, 
      m.colegiatura medico_colegiatura, 
      m.rne medico_rne,
      p.nombre medico_profesion, 
      e2.nombre medico_especializacion,
      u2.id as id_upss_destino, 
      u2.descripcion_upss as descripcion_upss_destino,
      c.id as id_servicio_destino, 
      c.nombre as nombre_servicio_destino,
      e.id_diag_cab,
      e.id_diag_cuer1,
      e.id_diag_cuer2
      from emergencias e 
      inner join asegurados a on e.id_asegurado = a.id
      inner join users u on e.id_medico_egreso = u.id
      inner join medicos m on u.dni = m.dni and u.id_establecimiento = m.id_establecimiento
      left join profesiones p on m.id_profesion = p.id 
      left join especializaciones e2 on m.id_especializacion = e2.id
      left join tipo_documento_identidad tdi on a.id_tipo_doc_ident = tdi.id
      left join tipo_beneficiarios tb on tb.id = a.id_tipo_beneficiario
      left join asegurado_historias ah  on e.id_asegurado = ah.id_asegurado and ah.id_establecimiento = e.id_establecimiento
      left join distritos d on a.id_distrito = d.id_distrito 
      left join provincias p2 on d.id_provincia = p2.id_provincia 
      left join upsses u2 on e.id_upss_alta = u2.id
      left join consultorios c on u2.id_consultorio = c.id 
      --left join asignacion_servicios as2 on c.id = as2.id_consultorio 
      where e.id =".$id; 
    $data = DB::SELECT($cad);
    return $data[0];
  }

  public function listar_cuadro_ajax_emergencia($p){
    return $this->readFunctionPostgres('sp_cuadro_emergencia',$p);
  }

  public function GetBuscaSitedEmergencia($id_emergencia, $id_prestacion, $fecha_actual){
    $result = DB::table('emergencias as e')
                  ->join('prestaciones as p', 'e.id', '=', 'p.id_ext')
                  ->select(
                      'e.id as emergencia_id',
                      'e.cod_siteds_susalud',
                      'e.updated_at_cod_siteds',
                      'e.updated_us_cod_siteds',
                      'p.fecha_atencion',
                      'p.id as prestacion_id'
                  )
                  ->where('e.id', $id_emergencia)
                  ->where('p.id', $id_prestacion)
                  // ->whereRaw('DATE(e.updated_at_cod_siteds) = DATE(p.fecha_atencion)')
                  ->whereRaw('DATE(p.fecha_atencion) = DATE(CURRENT_DATE)')
                  ->first();
    if (!isset($result) || !isset($result->cod_siteds_susalud)) {
      return null;
    }
    return $result->cod_siteds_susalud;
        /* //ultima atencion 
        $ultimo_sited_emergencia = DB::table('emergencias')
              ->join('prestaciones','prestaciones.id_ext','=', 'emergencias.id')
              ->where('emergencias.id', $id_emergencia)
              ->where('prestaciones.fecha_atencion', $fecha_actual)
              ->orderby('emergencias.id', 'desc')
              ->first();
        
        $ultimo_sited_evaluacion = DB::table('evaluaciones')
              ->where('id_emergencia', $id_emergencia)
              ->where('id_prestacion', $id_prestacion)
              ->where('fecha_atencion', $fecha_actual)
              ->orderby('id', 'desc')
              ->first();
        
        //primera evaluacion en el dia
        if(count($ultimo_sited_emergencia)>0 and count($ultimo_sited_evaluacion)==0):
          $codigo_sited = $ultimo_sited_emergencia->cod_siteds_susalud;
        endif;
        //ultima evaluaciones en el dia 
        if(count($ultimo_sited_emergencia)>0 and count($ultimo_sited_evaluacion)>0):
          $codigo_sited = $ultimo_sited_evaluacion->cod_siteds_susalud;
        endif;
        //primera evaluacion de otro dia
        if(count($ultimo_sited_emergencia)==0 and count($ultimo_sited_evaluacion)==0):
          $codigo_sited = '';
        endif;
        //ultima evaluaciones de otro dia 
        if(count($ultimo_sited_emergencia)==0 and count($ultimo_sited_evaluacion)>0):
          $codigo_sited = $ultimo_sited_evaluacion->cod_siteds_susalud;;
        endif;

        return $codigo_sited; */

  }

  public function obstenerIdEstadoCama($id_emergencia)
  {
    $res = DB::table('emergencias')
            ->where('emergencias.id',$id_emergencia)
            ->first();
    return $res;
  }

  public function GetDiaMayorEstancia($fechaDesde){

    $cad = "WITH ranked_emergencias AS (
    SELECT 
            EXTRACT(DAY FROM NOW() - e.created_at::date) + 1 AS dias_de_estancia,
            e.created_at,
            ROW_NUMBER() OVER (ORDER BY EXTRACT(DAY FROM NOW() - e.created_at::date) + 1 DESC) AS rn
        FROM emergencias e
        WHERE e.estado >= 3 and e.estado <5 AND e.created_at >= '$fechaDesde'::date  
    )
    SELECT 
        dias_de_estancia,
        created_at,
        CASE WHEN rn = 1 THEN dias_de_estancia ELSE NULL END AS max_dias
    FROM ranked_emergencias
    WHERE rn = 1";
   
    $data = DB::SELECT($cad);
   
    if (!empty($data)) {
      $data = $data[0];
      $data = $data->max_dias;
    } else {
      $data = 1;
    }

    return $data;
  }

  public function GetListaTodosEmergenciaNotaEnfermeria($p){
    $NumeroPagina = $p[0]; //0
    $NumeroRegistros= $p[1];  //1
    $fecha_ini = $p[2]; //2
    $fecha_fin = $p[3]; //3  
    $id_establecimiento = $p[4]; //4
    $busqueda_datos = $p[5]; //5
    $seleccionar_estado = $p[6]; //6
    $id_consultorio = $p[7]; //7  
    $id_prioridad = $p[8]; //8 
    $id_topico = $p[9]; //9 

    $where = "  ";
    if ($fecha_ini != "" && $fecha_fin != "") {
      $where .= " AND CAST( e.created_at AS DATE) BETWEEN '".$fecha_ini."' AND '".$fecha_fin."'  ";
    } 
    if ($busqueda_datos != "") {
        $where .= " AND CONCAT(e.dni_beneficiario, a.nombre, a.paterno, a.materno, c3.nombre) ILIKE '%" . $busqueda_datos . "%' ";
    }
    
    if($seleccionar_estado >= 0){
        if($seleccionar_estado == 0){
            $where .= " AND e.estado = 0";   
        }else if($seleccionar_estado == 1){
            $where .= " AND e.estado = 1";   
        } 
    }

    if($id_consultorio != 0){
        $where .= " AND sc.id_consultorio =" . $id_consultorio;    
    }

    if (isset($id_prioridad) && $id_prioridad != 0) {
      $where .= " AND e.id_prioridad =" . $id_prioridad;
    }

    if (isset($id_topico) && $id_topico != 0) {
      $where .= " AND e.id_triaje =" . $id_topico;
    }

    $cad = "select e.peso_kg,p2.descripcion as prioridad,ta.nombre as nombre_tipo_atencion,p.id as id_prestacion,
    to_char(e.fecha_alta_medica ::date,'DD/MM/YYYY')  as fecha_alta ,to_char(e.hora_alta_medica ::time,'HH:MI:SS AM')  AS hora_alta, u.name ,u.paterno ,u.materno ,tp.descripcion as tipo_des ,e.dni_conducido ,e.nombre_conducido ,e.telf_conducido ,a.situacion_paciente , e.id_motivo_ingreso, a.grado as grado_as,a.cip as cip, EXTRACT(YEAR FROM age(current_date, fecha_nac::date)) as edad, a.sexo as sexo_as,tb2.descripcion AS des_parentesco, a.tipo_doc_ident as tipo_doc,p2.descripcion as des_prioridades ,t.id as id_area,t.descripcion , e.id , tb2.descripcion as tipo_beneficiario ,c3.nombre as nombre_consultorio,e.fecha_registro_ingreso as fecha_ingreso ,e.estado , e.dni_beneficiario
    ,a.nombre as nombre_beneficiario, e.id_asegurado, a.paterno as paterno_beneficiario ,a.materno as materno_beneficiario,
    e.cama_estado  ,
    e.id_triaje,
    
    p2.id as id_prioridad,
    c3.id as id_consultorio,
    CASE
      WHEN p.id is not null THEN true
      ELSE false
    end AS tiene_prestacion,
    COALESCE(c2.nombre, 'SIN ASIGNAR') AS nombre_cuarto,
    COALESCE(c.nombre, 'SIN ASIGNAR') AS nombre_cama
    from emergencias e 
    left join camas  c on c.id =e.cama_estado 
    left join cuartos c2 on c2.id =c.id_cuarto 
    left join asegurados a on a.id =e.id_asegurado 
    left join prestaciones p on p.id_ext =e.id
    left join tipo_atenciones as ta on p.id_tipo_atencion=ta.id
    left join triajes t on t.id =e.id_triaje
    left join sub_consultorios sc on p.id_sub_consultorio =sc.id 
    left join consultorios c3 on c3.id =sc.id_consultorio 
    left join prioridades p2 on p2.id =e.id_prioridad 
    left join tipo_beneficiarios tb2 ON tb2.id =a.id_tipo_beneficiario 
    left join tipo_parentescos tp on tp.id =e.id_tipo_parentesco 
    left join users u on u.id =e.id_medico_triaje
    left join enfermerias e2  on e2.id_ext_enfermeria =e.id  
    where  e.estado  in ('2','3','4','5')  and e.id_tipo_beneficiario !=13  and e.id_establecimiento =" . $id_establecimiento .' '. $where; 

    $totalrows = count(DB::SELECT($cad));  

    $cad .= " order by e.fecha_registro_ingreso  DESC LIMIT " . $NumeroRegistros . " OFFSET " . ($NumeroPagina - 1) * $NumeroRegistros . "; ";
    $data = DB::select($cad);
    //var_dump($cad);
    return array('data' => $data, 'totalrows' => $totalrows); 
}


public function GetBuscaPacienteEmergencia_cama($id, $estado)
    {
    
        $cad = "select e.id_medico_triaje as id_medico , t2.id id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,e.tipo_beneficiario parentesco,fecha_nac,tipo_doc_ident
        ,p4.nombre as pabellon,p3.nombre as piso,c2.nombre as cuarto,c.nombre as cama,e.cama_estado as id_cama,c3.nombre, t.descripcion  ,e.fecha_registro_ingreso as fecha_ingreso,e.id_medico_triaje 
        ,e.id_medico_triaje ,t2.grado,t2.nro_doc_ident,e.id_establecimiento ,t3.nombre establecimiento,t.id_servicio  as id_consultorio,t.id_servicio  as id_consultorio_emer
        ,c2.id as id_cuarto
        from emergencias e 
        inner join asegurados t2 on e.dni_beneficiario = t2.nro_doc_ident 
        inner join asegurado_historias t9 on t2.id = t9.id_asegurado And e.id_establecimiento = t9.id_establecimiento 
        left join camas  c on c.id =e.cama_estado 
        left join cuartos c2 on c2.id =c.id_cuarto 
        left join asignacion_servicios as2 on as2.id =c2.id_asignacion_servicio 
        left join pisos p3  on p3.id =as2.id_piso 
        left join pabellones p4 on p4.id =p3.id_pabellon 
        left join prestaciones p on p.id_ext =e.id 
        left join sub_consultorios sc on p.id_sub_consultorio =sc.id  
        left join triajes t on t.id =e.id_triaje  
        left join consultorios c3 on c3.id =t.id_servicio  
        inner join establecimientos t3 on e.id_establecimiento =t3.id
        Where e.estado=". $estado ." and e.id=".$id; 

        $data = DB::select($cad);
        return $data[0];
  }

  public function GetBuscaPacienteEmergencia_cama2($id, $estado)
    {
    
        $cad = "select e.id_medico_triaje as id_medico , t2.id id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,tb.descripcion as parentesco,fecha_nac,tipo_doc_ident
        ,p4.nombre as pabellon,p3.nombre as piso,c2.nombre as cuarto,c.nombre as cama,e.cama_estado as id_cama,c3.nombre, t.descripcion,e.fecha_registro_ingreso as fecha_ingreso,e.id_medico_triaje 
        ,e.id_medico_triaje ,t2.grado,t2.nro_doc_ident,e.id_establecimiento ,t3.nombre establecimiento,t.id_servicio as id_consultorio,t.id_servicio  as id_consultorio_emer
        ,c2.id as id_cuarto
        from emergencias e 
        inner join asegurados t2 on e.dni_beneficiario = t2.nro_doc_ident 
        inner join tipo_beneficiarios tb on e.id_tipo_beneficiario = tb.id
        inner join asegurado_historias t9 on t2.id = t9.id_asegurado And e.id_establecimiento = t9.id_establecimiento 
        left join camas  c on c.id =e.cama_estado 
        left join cuartos c2 on c2.id =c.id_cuarto 
        left join asignacion_servicios as2 on as2.id =c2.id_asignacion_servicio 
        left join pisos p3  on p3.id =as2.id_piso 
        left join pabellones p4 on p4.id =p3.id_pabellon 
        left join prestaciones p on p.id_ext =e.id 
        left join sub_consultorios sc on p.id_sub_consultorio =sc.id  
        left join triajes t on t.id =e.id_triaje  
        left join consultorios c3 on c3.id =t.id_servicio  
        inner join establecimientos t3 on e.id_establecimiento =t3.id
        Where e.estado=". $estado ." and e.id=".$id; 

        $data = DB::select($cad);
        return $data[0];
  }


  public function GetObtenerDatosRotacion($id) {
        
    if($id > 0){

            $cad = "select t1.*, t1.nombre_consultorio servicio, t6.nombre consultorio, pa.nombre as pabellon,p.nombre as piso,c.nombre as cuarto,ca.nombre as cama, u.name nombre_usuario, u.paterno paterno_usuario, u.materno materno_usuario, m.nombre nombre_medico, m.paterno paterno_medico, m.materno materno_medico
                    FROM rotacion_camas t1
                    inner join  pabellones pa on t1.id_pabellon=pa.id
                    inner join pisos p on t1.id_piso=p.id
                    inner join cuartos c on t1.id_cuarto = c.id
                    inner join camas ca on t1.id_cama=ca.id
                    inner join users u on t1.id_user=u.id
                    left join consultorios t6 on t1.id_consultorio=t6.id
                    left join medicos m on t1.id_medico_cambio=m.id
                    Where t1.id_emergencia=".$id.
                    "order by t1.id DESC";

        $data = DB::select($cad);
        return $data;
    }
}

public function GetBusca_Emergencia($id, $id_medico, $estado)
{

    if ($id > 0) {
    $cad = "select distinct e.telf_conducido as telefono_acompaniante,e.observacion_admision as observacion_acompaniante,e.tipo_doc_cond as tipo_documento_acompaniante, e.nombre_conducido as nombre_acompaniante, e.dni_conducido as numero_documento_acompaniante, tp.descripcion as tipo_acompaniante,c.nombre as cama,c2.nombre as cuarto,p3.nombre piso, p4.nombre pabellon,ta.id as origen,to_char(e.fecha_registro_ingreso ::date,'DD/MM/YYYY') AS fecha_ingreso, 
    to_char(e.hora_registro_ingreso ::time,'HH:MI:SS AM') AS hora_ingreso, 
     to_char(e.created_at ::date,'DD/MM/YYYY') AS fecha_creacion, 
    to_char(e.created_at, 'HH:MI:SS AM')  AS hora_creacion, 
    p2.descripcion as pronvincia_actual, p2.descripcion as pronvincia_actual,d2.descripcion as distrito_actual, d.descripcion as departamento_actual, a.domicilio as domicilio_actual,a.codigo_ubig as codigo_ubig,a.id_departamento , r.nombre as religion, a.id_religion , ts.descripcion_corta as descripcion_tipo_sangres, a.sexo ,a.fecha_nac as fecha_nacimiento_beneficiario, u.name as nombre_usuario,u.paterno paterno_usuario,u.materno materno_usuario, e.id_usuario_admision , a.tipo_doc_ident as tipo_doc_ident_beneficiario, a.nombre ,  ah.nro_historia , a.id, e.*, m.nombre, tb.descripcion as tipo_beneficiario ,c3.nombre as nombre_consultorio,e.fecha_registro_ingreso as fecha_ingreso ,e.estado , e.dni_beneficiario 
    ,a.nombre as nombre_beneficiario ,a.paterno as paterno_beneficiario ,a.materno as materno_beneficiario, c2.id ,
        c2.nombre AS nombre_cuarto,
    c.id ,c.nombre AS nombre_cama
            from emergencias e
            left join camas  c on c.id =e.cama_estado 
            left join cuartos c2 on c2.id =c.id_cuarto 
            left join asignacion_servicios as2 on as2.id =c2.id_asignacion_servicio 
            left join pisos p3  on p3.id =as2.id_piso 
            left join pabellones p4 on p4.id =p3.id_pabellon 
            left join asegurados a on a.id =e.id_asegurado 
            left join prestaciones p on p.id_ext =e.id 
            left join sub_consultorios sc on p.id_sub_consultorio =sc.id 
            left join consultorios c3 on c3.id =sc.id_consultorio
            left join tipo_beneficiarios tb on tb.id =a.id_tipo_beneficiario 
            left join medicos m on m.id_consultorio =p.id_medico 
            left join asegurado_historias ah on ah.id =a.id 
            left join users u on u.id =e.id_medico_triaje  
            left join tipo_sangres ts on ts.id =a.id_tipo_sangre 
            left join religiones r on r.id =a.id_religion 
            left join departamentos d on a.id_departamento = d.id_departamento 
            left join distritos d2 on d2.id_distrito  =a.id_distrito  
            left join provincias p2 on p2.id_provincia =a.id_provincia 
            left join tipo_atenciones ta on ta.id =p.id_tipo_atencion 
            --esto es el tipo_acompaniante
            left join tipo_parentescos tp on tp.id =e.id_tipo_parentesco 
            Where  e.id=" . $id . " ";

    $data = DB::SELECT($cad);
    return $data[0];
    }
}

  public function ObtenerEmergenciaParaRotacionCamaById($id) {
    $sql =  "select 
              e.id as id_emergencia,
              p.id as id_prestacion,
              e.estado as estado,
              a.id as id_asegurado,
              a2.id as id_asegurado_titular,
              tb.id as id_tipo_beneficiario,
              m.id as id_medico,
              es.id as id_especializacion,
              pr.id as id_profesion,
              a.nro_doc_ident,
              a.nombre as asegurado_nombre,
              a.paterno as asegurado_paterno,
              a.materno as asegurado_materno,
              tb.descripcion as tipo_beneficiario_descripcion,
              a.fecha_nac as asegurado_fecha_nac,
              a.sexo as asegurado_sexo,
              DATE_PART('year', AGE(a.fecha_nac::DATE)) as asegurado_edad,
              a.direccion_procedencia asegurado_direccion_procedencia,
              a.direccion_nacimiento asegurado_direccion_nacimiento,
              ts.descripcion_corta asegurado_tipo_sangre_descripcion_corta,
              ts.descripcion asegurado_tipo_sangre_descripcion,
              a.situacion_paciente as asegurado_situacion_paciente,
              a.grado as asegurado_grado,
              a.cip as asegurado_cip,
              a2.dni_titular as dni_titular,
              a2.nombre as nombre_titular,
              a2.paterno as paterno_titular,
              a2.materno as materno_titular,
              a2.grado as grado_titular,
              a2.situacion_paciente as situacion_paciente_titular,
	            a2.telefono as telefono_titular, 
              a2.cip as cip_titular,
              e.dni_conducido, 
              e.nombre_conducido, 
              e.telf_conducido,
              m.nombre as medico_nombre, 
              m.paterno as medico_paterno, 
              m.materno as medico_materno, 
              m.dni as medico_dni, 
              m.id_especializacion as medico_id_especializacion,
              es.nombre as especializacion_nombre,
              m.id_profesion as medico_id_profesion,
              pr.nombre as profesion_nombre,
              m.colegiatura as medico_colegiatura,
              m.rne as medico_rne,
              p2.descripcion as prioridad_nombre,
              c.id as consultorio_id,
              c.nombre as consultorio_nombre,
              c2.id as cama_id,
              c2.nombre as cama,
              to_char(e.fecha_registro_ingreso, 'DD/MM/YYYY') as fecha_registro_ingreso,
              e.hora_registro_ingreso,
              e.id_establecimiento,
              e.id_tipo_parentesco,
	            tp.descripcion as tipo_parentesco,
              c3.nombre as cuartos_nombre
            from emergencias e 
            left join camas c2 on e.cama_estado = c2.id
            left join cuartos c3 on c2.id_cuarto = c3.id
            left join tipo_parentescos tp on e.id_tipo_parentesco = tp.id 
            left join prioridades p2 on e.id_prioridad = p2.id 
            left join triajes t on e.id_triaje = t.id 
            left join sub_consultorios sc on t.id_sub_consultorio = sc.id 
            left join consultorios c on sc.id_consultorio = c.id 
            left join prestaciones p on e.id = p.id_ext 
            left join asegurados a on e.id_asegurado = a.id
            left join asegurados a2 on a2.id = a.id_asegurado_titular
            left join tipo_beneficiarios tb on e.id_tipo_beneficiario = tb.id
            left join tipo_sangres ts on a.id_tipo_sangre = ts.id 
            left join medicos m on m.id = p.id_medico 
            left join especializaciones es on m.id_especializacion = es.id
            left join profesiones pr on m.id_profesion = pr.id 
            where e.id = " . $id . " limit 1";
    $result = DB::select($sql);
    if (!isset($result) || !is_array($result) || count($result) === 0) {
      return null;
    }
    return $result[0];
  }

  public function getSqlWithBindings($query)
  {
      $sql = $query->toSql();
      $bindings = $query->getBindings();

      foreach ($bindings as $binding) {
          $value = is_numeric($binding) ? $binding : "'".str_replace("'", "''", $binding)."'";
          $sql = preg_replace('/\?/', $value, $sql, 1);
      }

      return $sql;
  }

  public function GetAllEmergenciasForEnfermerias($p) {

    $NumeroPagina = $p[0]; //0
    $NumeroRegistros = $p[1];  //1
    $fecha_ini = $p[2]; //2
    $fecha_fin = $p[3]; //3  
    $id_establecimiento = $p[4]; //4
    $busqueda_datos = $p[5]; //5
    $seleccionar_estado = $p[6]; //6
    $id_consultorio = $p[7]; //7  
    $id_prioridad = $p[8]; //8 
    $id_topico = $p[9]; //9

    $query = Emergencia::from('emergencias as e1')
    ->leftJoin('camas as c1', 'c1.id', '=','e1.cama_estado')
    ->leftJoin('cuartos as c2', 'c2.id', '=','c1.id_cuarto')
    ->leftJoin('asegurados as a', 'a.id', '=','e1.id_asegurado')
    ->leftJoin('prestaciones as p', 'p.id_ext', '=','e1.id')
    ->leftJoin('tipo_atenciones as ta', 'ta.id', '=','p.id_tipo_atencion')
    ->leftJoin('triajes as t', 't.id', '=','e1.id_triaje')
    ->leftJoin('sub_consultorios as sc', 'sc.id', '=','p.id_sub_consultorio')
    ->leftJoin('consultorios as c3', 'c3.id', '=','sc.id_consultorio')
    ->leftJoin('prioridades as p2', 'p2.id', '=','e1.id_prioridad')
    ->leftJoin('tipo_beneficiarios as tb', 'tb.id', '=','a.id_tipo_beneficiario')
    ->leftJoin('tipo_parentescos as tp', 'tp.id', '=','e1.id_tipo_parentesco')
    ->leftJoin('users as u', 'u.id', '=','e1.id_medico_triaje')
    ->leftJoin('enfermerias as e2', 'e2.id_ext_enfermeria', '=','e1.id')
    ->select('e1.peso_kg','p2.descripcion as prioridad', 'ta.nombre as nombre_tipo_atencion', 'p.id as id_prestacion', 
    DB::raw("to_char(e1.fecha_alta_medica ::date,'DD/MM/YYYY') as fecha_alta"), 
    DB::raw("to_char(e1.hora_alta_medica ::time,'HH:MI:SS AM') as hora_alta"), 
    'u.name', 'u.paterno', 'u.materno', 'tp.descripcion as tipo_des', 
    'e1.dni_conducido', 'e1.nombre_conducido', 'e1.telf_conducido', 'a.situacion_paciente', 'e1.id_motivo_ingreso', 
    'a.grado as grado_as', "a.cip as cip", 
    DB::raw('EXTRACT(YEAR FROM age(current_date, a.fecha_nac::date)) as edad'), 
    'a.sexo as sexo_as', 'tb.descripcion AS des_parentesco', 'a.tipo_doc_ident as tipo_doc', 'p2.descripcion as des_prioridades', 
    't.id as id_area', 't.descripcion', 'e2.id as id_enfermeria', 'e1.id', 'tb.descripcion as tipo_beneficiario', 
    'c3.nombre as nombre_consultorio', 'e1.fecha_registro_ingreso as fecha_ingreso', 
    'e1.estado', 'e1.dni_beneficiario', 'a.nombre as nombre_beneficiario', 'e1.id_asegurado', 'a.paterno as paterno_beneficiario', 
    'a.materno as materno_beneficiario', 'e1.cama_estado', 'e1.id_triaje', 
    'p2.id as id_prioridad', 'c3.id as id_consultorio', 'e1.id_establecimiento',
    DB::raw('CASE WHEN p.id is not null THEN true ELSE false end AS tiene_prestacion'),
    DB::raw('CASE WHEN e2.id is not null THEN true ELSE false end AS tiene_enfermeria'),
    DB::raw("COALESCE(c2.nombre, 'SIN ASIGNAR') AS nombre_cuarto"),
    DB::raw("COALESCE(c1.nombre, 'SIN ASIGNAR') AS nombre_cama"));

    if ($fecha_ini && $fecha_fin) {
      $query->whereRaw('CAST(e1.created_at AS DATE) BETWEEN ? AND ?', [$fecha_ini, $fecha_fin]);
    }

    if ($busqueda_datos) {
      $query->whereRaw("CONCAT(e1.dni_beneficiario, a.nombre, a.paterno, a.materno, c3.nombre) LIKE ?", ["%{$busqueda_datos}%"]);
    }

    if ($seleccionar_estado == 0) {
      $query->where('e1.estado', 0);
    } else if ($seleccionar_estado == 1) {
      $query->where('e1.estado', 1);
    }

    if ($id_consultorio != 0) {
      $query->where('sc.id_consultorio', $id_consultorio);
    }

    if (isset($id_prioridad) && $id_prioridad != 0) {
      $query->where('e1.id_prioridad', $id_prioridad);
    }

    if (isset($id_topico) && $id_topico != 0) {
      $query->where('e1.id_triaje', $id_topico);
    }

    $query->where('e1.estado', '!=', '1');

    $query->orderBy('e1.fecha_registro_ingreso', 'desc');

    //$query->limit($NumeroRegistros);

    $query->offset(($NumeroPagina - 1) * $NumeroRegistros);

    $data = $query->get();

    return array('data' => $data, 'totalrows' => count($data));

  }
  public function getSitedsValidForEvaluacion($id_emergencia)
  {
    $sitedsEmergencia = DB::table('emergencias')
                        ->join('prestaciones', 'emergencias.id', '=', 'prestaciones.id_ext')
                        ->where('prestaciones.id_ext', $id_emergencia)
                        ->whereRaw('DATE(prestaciones.fecha_atencion) = CURRENT_DATE')
                        ->select(
                            'emergencias.id as id_emergencia',
                            'prestaciones.id as id_prestacion',
                            'emergencias.cod_siteds_susalud',
                            'emergencias.updated_at_cod_siteds',
                            'emergencias.updated_us_cod_siteds',
                            'prestaciones.fecha_atencion'
                        )
                        ->limit(1)
                        ->first();

    if (isset($sitedsEmergencia) && isset($sitedsEmergencia->cod_siteds_susalud)) {
      return $sitedsEmergencia->cod_siteds_susalud;
    }
    
    $sitedsEvaluacion = DB::table('evaluaciones')
                          ->where('evaluaciones.id_emergencia', $id_emergencia)
                          ->whereRaw('DATE(fecha_atencion) = CURRENT_DATE')
                          ->select('id', 'cod_siteds_susalud', 'updated_at_cod_siteds', 'fecha_atencion')
                          ->limit(1)
                          ->first();
                          
    return isset($sitedsEvaluacion) && isset($sitedsEvaluacion->cod_siteds_susalud) ? $sitedsEvaluacion->cod_siteds_susalud : null;
  }

  function obtenetUltimoSitedsGenerado($id_emergencia, $fecha_atencion)
  {
    $emergencias = DB::table('emergencias')
        ->select('cod_siteds_susalud as codigo_siteds', 'updated_at_cod_siteds as fecha_siteds', 'fecha_atencion', DB::raw("'emergencias' as origen_siteds"))
        ->where('id', $id_emergencia)
        ->whereDate('fecha_atencion', '<=', $fecha_atencion);

    $evaluaciones = DB::table('evaluaciones')
        ->select('cod_siteds_susalud as codigo_siteds', 'updated_at_cod_siteds as fecha_siteds', 'fecha_atencion', DB::raw("'evaluaciones' as origen_siteds")) 
        ->where('id_emergencia', $id_emergencia)
        ->whereDate('fecha_atencion', '<=', $fecha_atencion)
        ->unionAll($emergencias)

        ->orderBy('fecha_atencion', 'DESC')

        ->first();
    
    return $evaluaciones;
  }

  public function GetBuscaEmergenciaConsejeria($id,$estado,$id_consejeria)
  {
    if ($id > 0) {
      $cad = "SELECT 
          t2.id AS id_asegurado,
          t2.nombre AS asegurado_nombre,
          t2.paterno AS asegurado_paterno,
          t2.materno AS asegurado_materno,
          t9.nro_historia,
          t2.sexo,
          tb.descripcion AS parentesco,
          t2.fecha_nac,
          t2.tipo_doc_ident,
          t2.id_asegurado_titular, 
          t2.nro_doc_ident,
          t1.fecha_ingreso AS dia,
          t1.id_establecimiento,
          t3.nombre AS establecimiento,
          t4.id_servicio AS id_servicio,
          t4.descripcion AS servicio,
          t4.id_sub_consultorio AS id_sub_consultorio,
          t4.id AS idconsultorio,
          t5.id AS id_medico,
          t5.nombre AS medico_nombre,
          t5.paterno AS medico_paterno,
          t5.materno AS medico_materno,
          t1.secuencia AS secuencia,
          ts.descripcion_corta AS tipo_sangre,
          to_char(age(TO_DATE(t2.fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\" \"y\" DD \"dias\"')  edadpaciente,
          pa.nombre AS pabellon,
          p.nombre AS piso,
          cu.nombre AS cuarto,
          c.nombre AS cama,
          t5.dni AS nro_dni_medico,
          t5.colegiatura AS colegiatura_medico,
          t5.rne AS rne_medico,
          t7.nombre AS especializacion_medico,
          t8.nombre AS profesion_medico,
          t1.created_at,
          t2.situacion_paciente,
          asegurados_titular.tipo_doc_ident AS nomtipdoctitular,
          asegurados_titular.nro_doc_ident AS nrodoctitular,
          asegurados_titular.paterno AS paterno_titular,
          asegurados_titular.materno AS materno_titular,
          asegurados_titular.nombre AS nombre_titular,
          asegurados_titular.cip AS cip_titular,
          asegurados_titular.grado AS grado_titular,
          asegurados_titular.situacion_paciente AS situaciontitular,
          asegurados_titular.unidad_pnptitular AS unidad_pnptitular,
          t1.cod_siteds_susalud,
          t1.updated_at_cod_siteds,
          t1.updated_us_cod_siteds,
          CASE 
              WHEN t1.updated_at_cod_siteds::DATE < CURRENT_DATE THEN 0 
              ELSE 1 
          END AS is_siteds_generate_today
      FROM emergencias t1
      LEFT JOIN consejerias c7 ON t1.id = c7.id_emergencia 
      LEFT JOIN asegurados t2 ON t1.dni_beneficiario = t2.nro_doc_ident 
      LEFT JOIN asegurados asegurados_titular ON t2.id_asegurado_titular = asegurados_titular.id
      LEFT JOIN asegurado_historias t9 ON t2.id = t9.id_asegurado AND t1.id_establecimiento = t9.id_establecimiento 
      LEFT JOIN establecimientos t3 ON t1.id_establecimiento = t3.id
      LEFT JOIN triajes t4 ON t1.ubica_topico = t4.id
      LEFT JOIN consultorios c3 ON c3.id = t4.id_servicio
      LEFT JOIN camas c ON c.id = t1.cama_estado 
      LEFT JOIN cuartos cu ON c.id_cuarto = cu.id
      LEFT JOIN asignacion_servicios ass ON cu.id_asignacion_servicio = ass.id
      LEFT JOIN pisos p ON ass.id_piso = p.id 
      LEFT JOIN pabellones pa ON p.id_pabellon = pa.id 
      LEFT JOIN medicos t5 ON c7.id_medico = t5.id
      LEFT JOIN especializaciones t7 ON t5.id_especializacion = t7.id
      LEFT JOIN profesiones t8 ON t5.id_profesion = t8.id
      LEFT JOIN tipo_sangres ts ON t2.id_tipo_sangre = ts.id
      LEFT JOIN tipo_beneficiarios tb ON t1.id_tipo_beneficiario = tb.id
      Where t1.estado=" . $estado . " AND t1.id=" . $id . " AND C7.id=" .$id_consejeria." and t2.id_estado_reg = 1";
      $data = DB::SELECT($cad);
      return $data[0];
      }
  }

  public function GetListaEmergenciasAtencionPendiente($p) {
		return $this->readFunctionPostgres('sp_emergencias_atencion_pendiente', $p);
	}

  public function GetListaTodosEmergencias2($p) {
		return $this->readFunctionPostgres('sp_prestacion_emergencias', $p);
	}

  public function GetBuscaEmergenciaById($id)
  {
      if ($id > 0) {
        $cad = "select distinct e.telf_conducido as telefono_acompaniante,e.observacion_admision as observacion_acompaniante,e.tipo_doc_cond as tipo_documento_acompaniante, e.nombre_conducido as nombre_acompaniante, e.dni_conducido as numero_documento_acompaniante, tp.descripcion as tipo_acompaniante,c.nombre as cama,c2.nombre as cuarto,p3.nombre piso, p4.nombre pabellon,ta.id as origen,to_char(e.fecha_registro_ingreso ::date,'DD/MM/YYYY') AS fecha_ingreso, 
        to_char(e.hora_registro_ingreso ::time,'HH:MI:SS AM') AS hora_ingreso, 
        to_char(e.created_at ::date,'DD/MM/YYYY') AS fecha_creacion, 
        to_char(e.created_at, 'HH:MI:SS AM')  AS hora_creacion, 
        p2.descripcion as pronvincia_actual, p2.descripcion as pronvincia_actual,d2.descripcion as distrito_actual, d.descripcion as departamento_actual, a.domicilio as domicilio_actual,a.codigo_ubig as codigo_ubig,a.id_departamento , r.nombre as religion, a.id_religion , ts.descripcion_corta as descripcion_tipo_sangres, a.sexo ,a.fecha_nac as fecha_nacimiento_beneficiario, u.name as nombre_usuario,u.paterno paterno_usuario,u.materno materno_usuario, e.id_usuario_admision , a.tipo_doc_ident as tipo_doc_ident_beneficiario, a.nombre ,  ah.nro_historia , a.id, e.*, tb.descripcion as tipo_beneficiario ,c3.nombre as nombre_consultorio,e.fecha_registro_ingreso as fecha_ingreso ,e.estado , e.dni_beneficiario 
        ,a.nombre as nombre_beneficiario ,a.paterno as paterno_beneficiario ,a.materno as materno_beneficiario, c2.id ,
            c2.nombre AS nombre_cuarto,
        c.id,c.nombre AS nombre_cama,as2.id as id_servicio_sala_egreso,establecimientos.nombre as establecimiento_traslado,m.paterno as profesional_egreso_paterno,
        m.materno as profesional_egreso_materno,m.nombre as profesional_egreso_nombre,m.colegiatura as profesional_egreso_colegiatura,
        e.temperatura,e.sa02_porcentaje,e.presion_arterial_x,e.presion_arterial_y,e.talla_cm,e.tamano_cintura_cm,
        e.frecuencia_respiratoria,e.peso_kg,e.frecuencia_cardiaca,e.observacion_triaje,e.id_tipo_egres_med,date_part('year',age(to_date(to_char(a.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente
              from emergencias e
              left join camas  c on c.id =e.cama_estado 
              left join cuartos c2 on c2.id =c.id_cuarto 
              left join asignacion_servicios as2 on as2.id =c2.id_asignacion_servicio 
              left join pisos p3  on p3.id =as2.id_piso 
              left join pabellones p4 on p4.id =p3.id_pabellon 
              left join asegurados a on a.id =e.id_asegurado 
              left join prestaciones p on p.id_ext =e.id 
              left join sub_consultorios sc on p.id_sub_consultorio =sc.id 
              left join consultorios c3 on c3.id =sc.id_consultorio
              left join tipo_beneficiarios tb on tb.id =a.id_tipo_beneficiario 
              left join medicos m on m.id_consultorio =p.id_medico 
              left join asegurado_historias ah on ah.id_asegurado =a.id and e.id_establecimiento = ah.id_establecimiento
              left join users u on u.id =e.id_usuario_admision  
              left join tipo_sangres ts on ts.id =a.id_tipo_sangre 
              left join religiones r on r.id =a.id_religion 
              left join departamentos d on a.id_departamento = d.id_departamento 
              left join distritos d2 on d2.id_distrito  =a.id_distrito  
              left join provincias p2 on p2.id_provincia =a.id_provincia 
              left join tipo_atenciones ta on ta.id =p.id_tipo_atencion
              left join establecimientos on e.id_establecimiento_traslado = establecimientos.id 
              --esto es el tipo_acompaniante
              left join tipo_parentescos tp on tp.id =e.id_tipo_parentesco 
              Where  e.id=" . $id . " ";
        $data = DB::SELECT($cad);
        return $data[0];
      }
  }

  public function GetVerUbicacionTraslado($id)
    {

        $orden = DB::table('emergencias as em')
            ->select(
                // 'u.codigo as codigo_upss_origen',
                // 'u.descripcion_upss as descripcion_upss_origen',
                'u2.codigo as codigo_upss_responsable',
                'u2.descripcion_upss as descripcion_upss_responsable',
                'c.nombre as descripcion_servicio_responsable'
                // 'c2.nombre as descripcion_servicio_destino',
                // 'pa.nombre as pabellon_traslado',
                // 'ca.nombre as cama_traslado'
            )
            ->join('establecimientos as e', 'em.id_establecimiento', '=', 'e.id')
            ->join('consultorios as c', 'em.id_servicio_destino', '=', 'c.id')
            // ->leftjoin('consultorios as c2', 'em.id_servicio_destino_ho', '=', 'c2.id')
            // ->join('upsses as u', 'u.id', '=', 'em.id_upss_origen')
            ->join('upsses as u2', 'u2.id', '=', 'em.id_upss_alta')
            // ->leftjoin('pabellones as pa', 'pa.id', '=', 'em.id_pabellon_destino')
            // ->leftjoin('camas as ca', 'ca.id', '=', 'em.id_cuarto_destino')
            ->where('em.id', $id)
            ->first();
        return $orden;
    }
  
  public function GetVerUbicacionTraslado2($id)
  {
    $registro = DB::table('emergencias as e')
      // ->leftJoin('upsses as u', 'e.id_upss_ubicacion', '=', 'u.id')
      // ->leftJoin('consultorios as c', 'e.id_servicio_ubicacion', '=', 'c.id')
      // ->leftJoin('camas as ca', 'e.id_cama_ubicacion', '=', 'ca.id')
      ->select(
          DB::raw("CASE 
                    WHEN e.id_edificio_ubicacion = 1 THEN 'HISTORICO'
                    WHEN e.id_edificio_ubicacion = 2 THEN 'PIP'
                    ELSE '' 
                  END as edificio_ubicacion")
          // 'u.codigo as upss_codigo',
          // 'u.descripcion_corta_upss as upss_descripcion',
          // 'c.nombre as servicio_ubicacion',
          // 'ca.nombre as cama_ubicacion',
          // 'e.personal_coordinacion'
      )
      ->where('e.id', $id)
      ->first();
    return $registro;
  }
}
