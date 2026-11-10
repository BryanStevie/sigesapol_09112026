<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class Hospitalizacione extends Model
{
    protected $table = 'hospitalizaciones';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_farmacia',
        'nro_hospitalizacion',
        'dni_beneficiario',
        'nombre_beneficiario',
        'paterno_beneficiario',
        'materno_beneficiario',
        'dni_titular',
        'nombre_titular',
        'paterno_titular',
        'materno_titular',
        'fecha_nacimiento_beneficiario',
        'edad',
        'sexo',
        'tipo_beneficiario',
        'nro_historia',
        'turno',
        'turno_salida',
        'tarifa',
        'origen',
        'grado',
        'id_diagnostico_inicial',
        'id_diagnostico_final',
        'condicion',
        'fecha_ingreso',
        'fecha_salida',
        'id_medico',
        'id_medico_alta',
        'id_pabellon',
        'id_piso',
        'id_consultorio_hosp',
        'nombre_consultorio',
        'id_cuarto',
        'id_cama',
        'estado',
        'id_consultorio',
        'id_user',
        'nombre_registrador',
        'id_user_alta',
        'nombre_registrador_alta',
        'created_at',
        'updated_at',
        'id_diagnostico_hospitalizacion',
        'motivo',
        'tipo_hospitalizacion',
        'id_establecimiento',
        'secuencia',
        'id_asegurado',
        'id_servicio_origen',
        'hora_ingreso',
        'hora_egreso',
        'id_ingreso',
        'tipo_doc_beneficiario',
        'id_tipo_doc_ident',
        'id_tipo_origen',
        'id_ext_origen',
        'id_ext_prestacion_origen',
        'cod_siteds_susalud',
        'id_tipo_acompaniante',
        'tipo_doc_acompaniante',
        'nro_doc_acompaniante',
        'nombre_acompaniante',
        'telefono_acompaniante',
        'id_tipo_apoderado',
        'tipo_doc_apoderado',
        'nro_doc_apoderado',
        'nombre_apoderado',
        'telefono_apoderado',
        'id_dx_2',
        'id_dx_3',
        'cups_pnp',
        'id_user_atencion',
        'fecha_atencion',
        'id_cama_estado',
        'fecha_ini_cama_estado',
        'hora_ini_cama_estado',
        'flag_egreso',
        'flag_alta_medica',
        'egreso_resumen',
        'egreso_complicacion',
        'id_diag_cab',
        'id_diag_cuer1',
        'id_diag_cuer2',
        'id_tipo_egreso',
        'id_condicion_alta',
        'id_cama_egreso',
        'fecha_alta_medica',
        'hora_alta_medica',
        'derivar',
        'observacion_egreso',
        'fecha_defuncion',
        'hora_defuncion',
        'necropsia',
        'causas_muerte',
        'cpms_alta',
        'id_establecimiento_traslado',
        'obs_traslado',
        'especialida_destino',
        'especialidad_destino_otro',
        'condicion_traslado',
        'fecha_firma_alta_medica',
        'ruta_alta_medica',
        'flag_alta_administrativa',
        'id_user_alta_administrativa',
        'fecha_alta_administrativa',
        'hora_alta_administrativa',
        'fecha_firma_alta_administrativa',
        'ruta_alta_administrativa',
        'egreso_examen_clinico',
        'egreso_examen_auxiliar',
        'egreso_evolucion',
        'egreso_tratamiento',
        'egreso_procedimiento',
        'egreso_medidas_generales',
        'egreso_medicamentos_prescritos',
        'egreso_pronostico',
        'id_tipo_traslado',
        'updated_at_cod_siteds',
        'updated_us_cod_siteds',
        'codigo_ubig_responsable',
        'codigo_ubig_apoderado',
        'ruta_epicrisis',
        'dias_estancia',
        'estado',
        'suspension_user',
        'id_discapacidad',
        "ruta_papeleta_egreso",
        'fecha_alta_cq_n_2',
        'hora_alta_cq_n_2'
    ];

    public function esFlujoDosVistas() {
		return $this->id_establecimiento == 76 && $this->id_servicio_encargado == 80;
	}

	// Hospitalizaciones cuyo flujo CU (vista 1) inició antes de esta fecha no tienen esta validación
	const FECHA_INICIO_VALIDACION_HC = '2026-09-24';
	public function requiereValidacionHc() {
		if (!$this->esFlujoDosVistas()) {
			return false;
		}
		$fecha_referencia = !empty($this->fecha_hora_cu) ? $this->fecha_hora_cu : $this->created_at;
		return !empty($fecha_referencia)
			&& Carbon::parse($fecha_referencia)->gte(Carbon::parse(self::FECHA_INICIO_VALIDACION_HC));
	}

	public function historiaClinicaCompleta() {
		return !$this->requiereValidacionHc() || $this->flag_hc_cu == 1;
	}

	public function nombreMedicoRegistroHc() {
		$medico = \App\Models\User::where('id', $this->id_user_exa_cu)->first(['name', 'paterno', 'materno']);
		return $medico ? strtoupper(trim($medico->paterno . ' ' . $medico->materno . ' ' . $medico->name)) : 'NO IDENTIFICADO';
	}

	function getNroHospitalizacion($id_establecimiento){
		$cad = "select extract(year from now())||LPAD((count(*)::text),5,'0')nro_admision 
from hospitalizaciones T1
inner join farmacias T2 on T1.id_farmacia = T2.id 
where T2.id_establecimiento = ".$id_establecimiento." ";
		$data = DB::select($cad);
        return $data[0]->nro_admision;
	}
    //

    public function AllRegistradorByFarmacia($id_farmacia) {
        $cad = "Select rv.id_user, upper(u.name) nombre, upper(u.paterno) paterno, upper(u.materno) materno From hospitalizaciones rv
Inner Join users u On rv.id_user = u.id
Where rv.id_farmacia =" . $id_farmacia . "
Group By rv.id_user, u.name, u.paterno, u.materno";
        $data = DB::select($cad);
        return $data;
    }

        public function AllRegistradorAltaByFarmacia($id_farmacia) {
        $cad = "Select rv.id_user_alta as id_user, upper(u.name) nombre, upper(u.paterno) paterno, upper(u.materno) materno From hospitalizaciones rv
Inner Join users u On rv.id_user_alta = u.id
Where rv.id_farmacia =" . $id_farmacia . "
Group By rv.id_user_alta, u.name, u.paterno, u.materno";
        $data = DB::select($cad);
        return $data;
    }

    /*public function AllHospitalizadosByFarmaAndFechaDesdeHasta($id_ipress, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_hospitalizacion = "", $iddispensador = "") {

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
        if ($dni_beneficiario):
            $cad .= " and hospitalizaciones.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_hospitalizacion):
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
    }*/
    public function AllHospitalizadosByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_hospitalizacion = "", $iddispensador = "") {

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
where hospitalizaciones.id_farmacia=" . $id_farmacia;
        if ($dni_beneficiario):
            $cad .= " and hospitalizaciones.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_hospitalizacion):
            $cad .= " and hospitalizaciones.nro_hospitalizacion like '%" . $nro_hospitalizacion . "%'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($iddispensador != "")
            $cad .= " and hospitalizaciones.id_user = " . $iddispensador . "";

        if ($fechaDesde != "")
            $cad .= " and hospitalizaciones.fecha_ingreso >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and hospitalizaciones.fecha_ingreso <= '" . $fechaHasta . "'";

        $cad .= " and hospitalizaciones.estado in (1,4)
order By hospitalizaciones.fecha_ingreso desc";
        
        $data = DB::select($cad);
        return $data;
    }

public function AllHospitalizadosAltaByFarmaAndFechaDesdeHasta($id_ipress, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_hospitalizacion = "", $iddispensador_alta = "") {

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
        if ($dni_beneficiario):
            $cad .= " and hospitalizaciones.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_hospitalizacion):
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


    /*public function GetCountRegistroByUserAndFechaDesdeHasta($id_ipress, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_hospitalizacion = "", $iddispensador = "") {

        $cad = "select count(1) cantidad
from hospitalizaciones
inner join farmacias on hospitalizaciones.id_farmacia = farmacias.id
where farmacias.id_establecimiento=" . $id_ipress;

        if ($dni_beneficiario):
            $cad .= " and hospitalizaciones.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_hospitalizacion):
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
    }*/

    public function GetCountRegistroByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_hospitalizacion = "", $iddispensador = "") {

        $cad = "select count(1) cantidad
from hospitalizaciones
where hospitalizaciones.id_farmacia=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and hospitalizaciones.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_hospitalizacion):
            $cad .= " and hospitalizaciones.nro_hospitalizacion = '" . $nro_hospitalizacion . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($iddispensador != "")
            $cad .= " and hospitalizaciones.id_user = " . $iddispensador . "";

        if ($fechaDesde != "")
            $cad .= " and hospitalizaciones.fecha_ingreso >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and hospitalizaciones.fecha_ingreso <= '" . $fechaHasta . "'";
        $cad .= " and hospitalizaciones.estado in (1,4)";

        $data = DB::select($cad);
        return $data[0]->cantidad;
    }


    public function GetCountRegistroAltaByUserAndFechaDesdeHasta($id_ipress, $fechaDesdeSalida = "", $fechaHastaSalida = "", $dni_beneficiario = "", $nro_hospitalizacion = "", $iddispensador_alta = "") {

        $cad = "select count(1) cantidad
from hospitalizaciones
inner join farmacias on hospitalizaciones.id_farmacia = farmacias.id
where farmacias.id_establecimiento=" . $id_ipress;

        if ($dni_beneficiario):
            $cad .= " and hospitalizaciones.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_hospitalizacion):
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

	
   	public function GetAdmisionByNroAdmision($nro_admision,$id_establecimiento) {
        
        $cad = "SELECT T1.*,T2.nombre consultorio,(case when turno='M' then 'MA&Ntilde;ANA' when turno='T' then 'TARDE' when turno='N' then 'NOCHE' end)turnos
,T3.nombre,T3.paterno,T3.materno,T3.dni,T3.colegiatura
,coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)actual_receta
FROM admisiones T1
inner join consultorios T2 on T1.id_consultorio = T2.id
inner join medicos T3 on T1.id_medico = T3.id
inner join farmacias T4 on T1.id_farmacia = T4.id 
where T1.nro_admision = '".$nro_admision."'";
		
		$cad .= " AND T4.id_establecimiento=".$id_establecimiento;
		
        $data = DB::select($cad);
        return $data[0];
    }

    public function ShowByIdHospitalizacionId_farmacia($nro_hospitalizacion, $id_ipress) {
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
                ->select('hospitalizaciones.*','diagnosticos.nombre as nombre_diagnostico','diagnosticos.codigo as codigo_diagnostico','pabellones.nombre as nombre_pabellon','pisos.nombre as nombre_piso','cuartos.nombre as nombre_cuarto','camas.nombre as nombre_cama','establecimientos.nivel', 'establecimientos.nombre as ipress', 'establecimientos.codigo as codigoipress', 'consultorios.nombre as consultorio', 'medicos.nombre as nombre_medico', 'medicos.paterno as paterno_medico', 'medicos.materno as materno_medico', 'users.name as nombre_usuario', 'users.paterno as paterno_usuario', 'users.materno as materno_usuario', 'hospitalizaciones.created_at as fecha_creacion', 'nro_hospitalizacion', 'fecha_ingreso')
                ->get();
        return $datos;
    }

    public function ShowByIdAltaPacienteId_farmacia($id_hospitalizacion, $id_ipress) {
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
                ->select('hospitalizaciones.*','diagnosticos.nombre as nombre_diagnostico','diagnosticos.codigo as codigo_diagnostico','pabellones.nombre as nombre_pabellon','pisos.nombre as nombre_piso','cuartos.nombre as nombre_cuarto','camas.nombre as nombre_cama','establecimientos.nivel', 'establecimientos.nombre as ipress', 'establecimientos.codigo as codigoipress', 'consultorios.nombre as consultorio', 'medicos.nombre as nombre_medico', 'medicos.paterno as paterno_medico', 'medicos.materno as materno_medico', 'users.name as nombre_usuario','hospitalizaciones.condicion as condicion', 'users.paterno as paterno_usuario', 'users.materno as materno_usuario', 'hospitalizaciones.created_at as fecha_creacion', 'nro_hospitalizacion', 'fecha_ingreso')
                ->get();
        return $datos;
    }
    
	public function GetAdmisionByIdFarmaciaAndDni($id_establecimiento,$dni) {
        
         $cad = "SELECT T1.*
,coalesce(T1.cantidad_receta,1)cantidad_receta
,coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)actual_receta
,T2.nombre consultorio,(case when turno='M' then 'MA&Ntilde;ANA' when turno='T' then 'TARDE' when turno='N' then 'NOCHE' when turno='MD' then 'MADRUGADA' end)turnos
,T3.nombre,T3.paterno,T3.materno,T3.dni,T3.colegiatura
FROM admisiones T1
inner join consultorios T2 on T1.id_consultorio = T2.id
inner join medicos T3 on T1.id_medico = T3.id 
inner join farmacias T4 on T1.id_farmacia = T4.id 
where T1.dni_beneficiario = '".$dni."'
and T4.id_establecimiento = ".$id_establecimiento;
		
		$cad .= " AND coalesce(T1.cantidad_receta,1) > coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)";
		$cad .= " ORDER BY T1.id desc ";		
        $data = DB::select($cad);
        return $data;
    }
	
    public function GetCamaById($id_establecimiento,$id_hospitalizacion) {
        
        $cad = "select H.* FROM Hospitalizaciones H inner join Farmacias F on F.id = H.id_farmacia where H.id = '".$id_hospitalizacion."' and F.id_establecimiento = '".$id_establecimiento."'";         
        $data = DB::select($cad);
        
        return $data[0];
    }

    public function GetBuscaPaciente($id_establecimiento,$id_hospitalizacion) {
        
        $cad = "select H.* FROM Hospitalizaciones H inner join Farmacias F on F.id = H.id_farmacia where H.id = '".$id_hospitalizacion."' and F.id_establecimiento = '".$id_establecimiento."'";         
        $data = DB::select($cad);
        
        return $data[0];
    }

    public function listar_prestacion_hospitalizacion($p){
        return $this->readFunctionPostgres('sp_prestacion_paginado',$p);
    }

    public function readFunctionPostgres($function, $parameters = null){
    
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

   //nuevo modulo hospitalizacion
   public function listar_emergencia_a_hospitalizar_ajax($p)
    {
        return $this->readFunctionPostgres('sp_listar_emergencia_a_hospitalizar_2', $p);
    }   
	
    public function GetBuscaHospitalizado_anterior($id, $id_medico, $estado) {
        
        if($id > 0){

                $cad = "select t2.id id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,t1.tipo_beneficiario parentesco,fecha_nac,tipo_doc_ident,
        t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.id_establecimiento,t3.nombre establecimiento, t1.id_consultorio id_servicio, t1.nombre_consultorio servicio, t1.id_consultorio_hosp id_sub_consultorio, t6.nombre consultorio, t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno, date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, pa.nombre as pabellon,p.nombre as piso,c.nombre as cuarto,ca.nombre as cama
        ,dni,colegiatura,rne,t7.nombre especializacion,t8.nombre profesion
        FROM hospitalizaciones t1
        inner join asegurados t2 on t1.dni_beneficiario = t2.nro_doc_ident 
        inner join asegurado_historias t9 on t2.id = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
        inner join establecimientos t3 on t1.id_establecimiento=t3.id
        inner join  pabellones pa on t1.id_pabellon=pa.id
        inner join pisos p on t1.id_piso=p.id
        inner join cuartos c on t1.id_cuarto = c.id
        inner join camas ca on t1.id_cama=ca.id
        left join medicos t5 on t5.id=".$id_medico." 
        left join especializaciones t7 on t5.id_especializacion=t7.id
        left join consultorios t6 on t1.id_consultorio=t6.id
        left join profesiones t8 on t5.id_profesion=t8.id
        Where t1.estado=". $estado ." and t1.id=".$id; 

            $data = DB::select($cad);
            return $data[0];
        }
    }

    public function ActualizaEstadoHospitalizacion($id_hospitalizacion, $estado) {

        $hospitalizacion = Hospitalizacione::where('id', $id_hospitalizacion)->first(); 
        $hospitalizacion->estado = $estado;
        $hospitalizacion->save();
        
        return true;
        
    }

    public function GetListaHospitalizado($id_establecimiento, $estado) {
    
        $cad = "    select hospitalizaciones.*, coalesce(camas.nombre, '') as nombre_cama ,cuartos.nombre as nombre_cuarto
                    from hospitalizaciones
                    inner join farmacias on farmacias.id = hospitalizaciones.id_farmacia
                    inner join cuartos on cuartos.id = hospitalizaciones.id_cuarto
                    left join camas on camas.id = hospitalizaciones.id_cama
                    where hospitalizaciones.estado=".$estado." and tipo_hospitalizacion='HO' and farmacias.id_establecimiento =" . $id_establecimiento .
                  "order by hospitalizaciones.fecha_ingreso DESC";
        $data = DB::select($cad);
        return $data;
    }

    public function GetCamaById2($hospitalizacion_id)
    {
        $id_cama = Hospitalizacione::where('id', $hospitalizacion_id)
            ->value('id_cama_estado');

        return Cama::find($id_cama);
    }

    public function GetCuartoById2($hospitalizacion_id)
    {
        $id_cuarto = Hospitalizacione::where('id', $hospitalizacion_id)
            ->value('id_cuarto');

        return Cuarto::find($id_cuarto);
    }

    public function GetListaHospitalizadoDial($id_establecimiento, $estado) {
    
        $cad = "    select hospitalizaciones.*
                from hospitalizaciones
                inner join farmacias on farmacias.id = hospitalizaciones.id_farmacia|-
                where hospitalizaciones.estado=".$estado." and tipo_hospitalizacion='HE' and farmacias.id_establecimiento =" . $id_establecimiento .
              "order by hospitalizaciones.fecha_ingreso DESC";
        $data = DB::select($cad);
        return $data;
    }

    /**
     * Obtener informacion de la Cama de Admision por Id Hospitalizacion
     */
    public function GetObtenerDatos($id) {
        
        if($id > 0){

                $cad = "select t1.nombre_consultorio servicio, t6.nombre consultorio, pa.nombre as pabellon,p.nombre as piso,c.nombre as cuarto,ca.nombre as cama
                        FROM hospitalizaciones t1
                        left join  pabellones pa on t1.id_pabellon=pa.id
                        left join pisos p on t1.id_piso=p.id
                        left join cuartos c on t1.id_cuarto = c.id
                        left join camas ca on t1.id_cama_estado=ca.id
                        left join consultorios t6 on t1.id_consultorio=t6.id
                        Where t1.id=".$id; 

            $data = DB::select($cad);
            return $data[0];
        }
    }
     

    public function GetBuscaPacienteHospitalizacion($id, $estado)
    {
    
        $cad = "select t2.id id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,t1.tipo_beneficiario parentesco,fecha_nac,tipo_doc_ident,pa.nombre as pabellon,p.nombre as piso,c.nombre as cuarto,ca.nombre as cama,ca.id id_cama,t1.id_consultorio, t1.id_consultorio_hosp,t1.id_cuarto,t1.fecha_ingreso,t1.id_medico,t2.grado,
        t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.id_establecimiento,t3.nombre establecimiento, t1.id_consultorio id_servicio, t1.nombre_consultorio servicio, t1.id_consultorio_hosp id_sub_consultorio, t6.nombre consultorio,/* t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,*/ date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente
        FROM hospitalizaciones t1
        inner join asegurados t2 on t1.dni_beneficiario = t2.nro_doc_ident 
        inner join asegurado_historias t9 on t2.id = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
        inner join establecimientos t3 on t1.id_establecimiento=t3.id
        left join  pabellones pa on t1.id_pabellon=pa.id
        left join pisos p on t1.id_piso=p.id
        left join cuartos c on t1.id_cuarto = c.id
        left join camas ca on t1.id_cama=ca.id
        left join consultorios t6 on t1.id_consultorio=t6.id
        
        Where t2.id_estado_reg = 1 and t1.estado=". $estado ." and t1.id=".$id; 

        $data = DB::select($cad);
    return $data[0];
    
  }

  public function GetBuscaPacienteHospitalizacionFarmacia($id)
    {
    
        $cad = "select t2.id id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,t2.sexo,t1.tipo_beneficiario parentesco,fecha_nac,tipo_doc_ident,pa.nombre as pabellon,p.nombre as piso,c.nombre as cuarto,ca.nombre as cama,ca.id id_cama,t1.id_consultorio, t1.id_consultorio_hosp,t1.id_cuarto,t1.fecha_ingreso,t1.id_medico,t2.grado,
        t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.id_establecimiento,t3.nombre establecimiento, t1.id_consultorio id_servicio, t1.nombre_consultorio servicio, t1.id_consultorio_hosp id_sub_consultorio, t6.nombre consultorio,/* t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,*/ date_part('year',age(to_date(to_char(t2.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente
        FROM hospitalizaciones t1
        inner join asegurados t2 on t1.dni_beneficiario = t2.nro_doc_ident 
        inner join asegurado_historias t9 on t2.id = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
        inner join establecimientos t3 on t1.id_establecimiento=t3.id
        inner join  pabellones pa on t1.id_pabellon=pa.id
        inner join pisos p on t1.id_piso=p.id
        inner join cuartos c on t1.id_cuarto = c.id
        inner join camas ca on t1.id_cama=ca.id
        left join consultorios t6 on t1.id_consultorio=t6.id
        
        Where  t1.id=".$id; 

        $data = DB::select($cad);
    return $data[0];
    
  }

  public function ActualizaSecuenciaHospitalizacion($id_hospitalizacion, $secuencia) {

        $hospitalizacion = Hospitalizacione::where('id', $id_hospitalizacion)->first(); 
        $hospitalizacion->secuencia = $secuencia;
        $hospitalizacion->save();
        
        return true;
        
    }


    public function GetListaHospitalizadoSecuencia($id_establecimiento, $secuencia) {
    
        $cad = "    select hospitalizaciones.*, coalesce(camas.nombre, '') as nombre_cama ,cuartos.nombre as nombre_cuarto
                    from hospitalizaciones
                    inner join farmacias on farmacias.id = hospitalizaciones.id_farmacia
                    inner join cuartos on cuartos.id = hospitalizaciones.id_cuarto
                    left join camas on camas.id = hospitalizaciones.id_cama
                    where hospitalizaciones.estado=1  and hospitalizaciones.secuencia>=".$secuencia." and tipo_hospitalizacion='HO' and farmacias.id_establecimiento =" . $id_establecimiento .
                  " order by hospitalizaciones.fecha_ingreso DESC";
        $data = DB::select($cad);
        return $data;
    }

    public function GetListaHospitalizadoDialSecuencia($id_establecimiento, $secuencia) {
    
        $cad = "    select hospitalizaciones.*
                from hospitalizaciones
                inner join farmacias on farmacias.id = hospitalizaciones.id_farmacia|-
                where hospitalizaciones.estado=1 and secuencia>=".$secuencia." and tipo_hospitalizacion='HE' and farmacias.id_establecimiento =" . $id_establecimiento .
              "order by hospitalizaciones.fecha_ingreso DESC";
        $data = DB::select($cad);
        return $data;
    }

    public function GetListaTodosHospitalizado_anterior($id_establecimiento) {
    
        $cad = "    select hospitalizaciones.*, coalesce(camas.nombre, '') as nombre_cama ,cuartos.nombre as nombre_cuarto, ae,ruta_nota, ae.flag
                    from hospitalizaciones
                    inner join farmacias on farmacias.id = hospitalizaciones.id_farmacia
                    inner join cuartos on cuartos.id = hospitalizaciones.id_cuarto
                    left join camas on camas.id = hospitalizaciones.id_cama
                    left join admision_enfermerias ae on ae.id_paciente =  hospitalizaciones.id
                    where (hospitalizaciones.estado!=0 or hospitalizaciones.estado!=2)  and tipo_hospitalizacion='HO' and farmacias.id_establecimiento =" . $id_establecimiento .
                  "order by hospitalizaciones.fecha_ingreso DESC";
        $data = DB::select($cad);
        return $data;
    }

    public function GetListaTodosHospitalizadoDial($id_establecimiento) {
    
        $cad = "    select hospitalizaciones.*
                from hospitalizaciones
                inner join farmacias on farmacias.id = hospitalizaciones.id_farmacia|-
                where (hospitalizaciones.estado!=0 or hospitalizaciones.estado!=2) and tipo_hospitalizacion='HE' and farmacias.id_establecimiento =" . $id_establecimiento .
              "order by hospitalizaciones.fecha_ingreso DESC";
        $data = DB::select($cad);
        return $data;
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
                        Where t1.id_hospitalizado=".$id.
                        "order by t1.id DESC";

            $data = DB::select($cad);
            return $data;
        }
    }

     public function GetListaHospitalizado_id($id_establecimiento, $estado, $id) {
    
        $cad = "    select hospitalizaciones.*, coalesce(camas.nombre, '') as nombre_cama ,cuartos.nombre as nombre_cuarto, consultorios.nombre as servicio_origen,medicos.nombre nomb_medico, medicos.paterno pat_medico, medicos.materno  mat_medico, medicos.dni medico_dni,esp.nombre as especializacion, pr.nombre as profesion, consultorios.id id_sala
                    from hospitalizaciones
                    inner join asegurados on asegurados.id=hospitalizaciones.id_asegurado
                    inner join consultorios on consultorios.id= hospitalizaciones.id_servicio_origen
                    inner join farmacias on farmacias.id = hospitalizaciones.id_farmacia
                    inner join cuartos on cuartos.id = hospitalizaciones.id_cuarto
                    left join camas on camas.id = hospitalizaciones.id_cama
                    left join medicos on medicos.id =hospitalizaciones.id_medico
                    left join especializaciones esp On medicos.id_especializacion=esp.id
                    left join profesiones pr On medicos.id_profesion=pr.id
                    where hospitalizaciones.estado=".$estado." and tipo_hospitalizacion='HO' and farmacias.id_establecimiento =" . $id_establecimiento . "  and hospitalizaciones.id =".$id.
                  " order by hospitalizaciones.fecha_ingreso DESC";
        $data = DB::select($cad);
        return $data;
    }

    public function generar_secuencia_hospitalizacion()
    {
        $cad = "SELECT COUNT(id) FROM hospitalizaciones where to_char(created_at::date,'YYYY-MM-DD') = to_char( now()::date,'YYYY-MM-DD')";
        $data = DB::SELECT($cad);
        if (empty($data)) {
        return null;
        }
        return $data[0];
    }

    public function GetListaTodosHospitalizado($p)
    {
        //estado 2 son los ya admisionados
        //estado 3 son los que ya tienen una atencion

        $id_establecimiento = $p[0]; //0
        $fechaInicio = $p[1]; //1
        $fechaFinal = $p[2]; //2
        $busqueda_datos = $p[3]; //3
        $id_consultorio = $p[4]; //4
        $seleccionar_estado = $p[5]; //5  

        $NumeroPagina = $p[6]; //6
        $NumeroRegistros = $p[7];  //7 
        $viene_modulo = $p[8];  //8 modulo de donde viene 
        $where = " WHERE ";
        if ($fechaInicio != "" && $fechaFinal != "")
        $where .= " CAST(hospitalizaciones.created_at AS DATE) BETWEEN '" . $fechaInicio . "' AND '" . $fechaFinal . "' AND  ";
        if ($id_establecimiento != "")
        $where .= " hospitalizaciones.id_establecimiento = '" . $id_establecimiento . "'  AND ";

        if ($busqueda_datos != "")
        $where .= " CONCAT(a.nro_doc_ident,'', a.paterno,'' ,a.materno,'',a.nombre,'',hospitalizaciones.nro_historia,'',
        hospitalizaciones.nro_hospitalizacion) ilike '%" . $busqueda_datos . "%'  AND ";
        //if ($id_consultorio != "")
        //$where .= " farmacias.id_upss_alta '" . $id_consultorio . "' ";

        $cad = "SELECT
        hospitalizaciones.*,  a.sexo, to_char(a.fecha_nac::date,'DD/MM/YYYY') fecha_nac,
        date_part('year',age(to_date(to_char(a.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente,
        
        to_char(hospitalizaciones.created_at::date,'DD/MM/YYYY') AS fecha_registro, 
        to_char(hospitalizaciones.created_at, 'HH:MI:SS AM')  AS hora_registro,  
        concat(user_admisiona.paterno,' ',user_admisiona.materno,' ',user_admisiona.name) as usuario_admisiona, 
        coalesce(camas.nombre, '') as nombre_cama ,cuartos.nombre as nombre_cuarto  ,

        --hospitalizaciones.motivo_anulacion, hospitalizaciones.id_usuario_anulacion,
        --concat(user_anula.paterno,' ',user_anula.materno,' ',user_anula.name) as usuario_anula_admision,
        --to_char(hospitalizaciones.fecha_anulacion::date,'DD/MM/YYYY') AS fecha_anulacion_admision , 
        --to_char(hospitalizaciones.fecha_anulacion::time, 'HH:MI:SS AM')  AS hora_anulacion_admision,



        --concat(user_atencion.paterno,' ',user_atencion.materno,' ',user_atencion.name) as usuario_atencion,
        --to_char(hospitalizaciones.fecha_atencion::date,'DD/MM/YYYY') AS fecha_atencion , 
        --to_char(hospitalizaciones.fecha_atencion::time, 'HH:MI:SS AM')  AS hora_atencion,
        
        --hospitalizaciones.motivo_anulacion_atencion, hospitalizaciones.id_usuario_anulacion_atencion,
        --concat(user_anula_atencion.paterno,' ',user_anula_atencion.materno,' ',user_anula_atencion.name) as usuario_anula_atencion,
        --to_char(hospitalizaciones.fecha_anulacion_atencion::date,'DD/MM/YYYY') AS fecha_anulacion_atencion , 
        --to_char(hospitalizaciones.fecha_anulacion_atencion::time, 'HH:MI:SS AM')  AS hora_anulacion_atencion,
        


        concat(user_alta.paterno,' ',user_alta.materno,' ',user_alta.name) as usuario_alta,
        to_char(hospitalizaciones.fecha_salida::date,'DD/MM/YYYY') AS fecha_alta , 
        to_char(hospitalizaciones.fecha_salida::time, 'HH:MI:SS AM')  AS hora_alta 

        --hospitalizaciones.motivo_anulacion_alta, hospitalizaciones.id_usuario_anulacion_alta,
        --concat(user_anula_alta.paterno,' ',user_anula_alta.materno,' ',user_anula_alta.name) as usuario_anula_alta,
        --to_char(hospitalizaciones.fecha_anulacion_alta::date,'DD/MM/YYYY') AS fecha_anulacion_alta , 
        --to_char(hospitalizaciones.fecha_anulacion_alta::time, 'HH:MI:SS AM')  AS hora_anulacion_alta

        from hospitalizaciones 

        LEFT JOIN asegurados a on hospitalizaciones.id_asegurado=a.id
        LEFT JOIN farmacias on farmacias.id = hospitalizaciones.id_farmacia
        LEFT join cuartos on cuartos.id = hospitalizaciones.id_cuarto
        LEFT JOIN camas on camas.id = hospitalizaciones.id_cama 
        LEFT JOIN users user_admisiona ON hospitalizaciones.id_user = user_admisiona.id 
        --LEFT JOIN users user_anula ON hospitalizaciones.id_usuario_anulacion = user_anula.id  
        --LEFT JOIN users user_atencion ON hospitalizaciones.id_user_atencion = user_atencion.id
        --LEFT JOIN users user_anula_atencion ON hospitalizaciones.id_usuario_anulacion_atencion = user_anula_atencion.id 
        LEFT JOIN users user_alta ON hospitalizaciones.id_user_alta = user_alta.id  ";
        // LEFT JOIN users user_anula_alta ON hospitalizaciones.id_usuario_anulacion_alta = user_anula_alta.id ";

        if ($viene_modulo == 'atencion') {
        if ($seleccionar_estado == 0) { //ANULACION DE ATENCIÓN
            $cad .= $where . " hospitalizaciones.estado=0 AND tipo_hospitalizacion='HO' /*AND hospitalizaciones.id_usuario_anulacion_atencion IS NOT NULL*/  ";
        } else {
            $cad .= $where . " hospitalizaciones.estado in (" . $seleccionar_estado . ") AND tipo_hospitalizacion='HO' ";
        }
        } else if ($viene_modulo == 'admision') {
        if ($seleccionar_estado == -1) { //ANULACION DE ADMISIÓN
            $cad .= $where . " hospitalizaciones.estado=0 AND tipo_hospitalizacion='HO' /*AND hospitalizaciones.id_usuario_anulacion IS NOT NULL*/  ";
        } else {
            $cad .= $where . " hospitalizaciones.estado in (" . $seleccionar_estado . ") AND tipo_hospitalizacion='HO' ";
        }
        }else if ($viene_modulo == 'alta') {
        if ($seleccionar_estado == 0) { //ANULACION DE ALTA
            $cad .= $where . " hospitalizaciones.estado=0 AND tipo_hospitalizacion='HO' /*AND hospitalizaciones.id_usuario_anulacion_alta IS NOT NULL*/  ";
        } else {
            $cad .= $where . " hospitalizaciones.estado in (" . $seleccionar_estado . ") AND tipo_hospitalizacion='HO' ";
        }
        }
        // dd($cad);
        $totalrows = count(DB::SELECT($cad));

        if ($NumeroPagina == 0 && $NumeroRegistros == 0) {
        $cad .= " order by hospitalizaciones.created_at DESC; ";
        } else {
        $cad .= " order by hospitalizaciones.created_at DESC LIMIT " . $NumeroRegistros . " OFFSET " . ($NumeroPagina - 1) * $NumeroRegistros . "; ";
        }
        

        $data = DB::SELECT($cad);
        return array('data' => $data, 'totalrows' => $totalrows);
    }

    public function GetBuscaHospitalizado($id, $id_medico, $estado)
    {

        if ($id > 0) {
        $cad = "SELECT t2.id id_asegurado,t1.estado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,
                    t2.sexo,t1.tipo_beneficiario parentesco,t2.fecha_nac,t2.tipo_doc_ident, t2.id_asegurado_titular, /*t1.id_emergencia ,*/ 
            t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.id_establecimiento,t3.nombre establecimiento, t1.id_consultorio id_servicio, t1.nombre_consultorio servicio, 
            t1.id_consultorio_hosp id_sub_consultorio, t6.nombre consultorio,t6.id idconsultorio, t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,
            t5.materno medico_materno, t1.secuencia as secuencia, ts.descripcion_corta as tipo_sangre,
            to_char(age(TO_DATE(t2.fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\" \"y\" DD \"dias\"')  edadpaciente,
            pa.nombre as pabellon,p.nombre as piso,c.nombre as cuarto,ca.nombre as cama
            ,t5.dni nro_dni_medico,t5.colegiatura colegiatura_medico,t5.rne rne_medico,t7.nombre especializacion_medico,t8.nombre profesion_medico, t1.created_at, t2.situacion_paciente,/*t1.tipo_acompaniante as tipo_acompaniante,
            t1.numero_documento_acompaniante as dni_conducido, t1.nombre_acompaniante as nombre_conducido, t1.telefono_acompaniante as telf_conducido,*/
            asegurados_titular.tipo_doc_ident  AS nomtipdoctitular, 
                asegurados_titular.nro_doc_ident  AS nrodoctitular, 
                asegurados_titular.paterno  AS paterno_titular, 
                asegurados_titular.materno  AS materno_titular, 
                asegurados_titular.nombre  AS nombre_titular, 
                asegurados_titular.cip  AS cip_titular,  
                asegurados_titular.grado  AS grado_titular,  
                asegurados_titular.situacion_paciente  AS situaciontitular,  
                asegurados_titular.unidad_pnptitular  AS unidad_pnptitular
            ,t1.cod_siteds_susalud, 
            t1.updated_at_cod_siteds, 
            t1.updated_us_cod_siteds,
            CASE WHEN t1.updated_at_cod_siteds::DATE < CURRENT_DATE  THEN 0 ELSE 1 END AS is_siteds_generate_today,
            t8.id as id_profesion
            
            FROM hospitalizaciones t1

            LEFT join asegurados t2 on t1.dni_beneficiario = t2.nro_doc_ident 
            LEFT JOIN asegurados as asegurados_titular ON  t2.id_asegurado_titular = asegurados_titular.id
            LEFT join asegurado_historias t9 on t2.id = t9.id_asegurado AND t1.id_establecimiento = t9.id_establecimiento 
            LEFT join establecimientos t3 on t1.id_establecimiento=t3.id
            LEFT join pabellones pa on t1.id_pabellon=pa.id
            LEFT join pisos p on t1.id_piso=p.id
            LEFT join cuartos c on t1.id_cuarto = c.id
            LEFT join camas ca on t1.id_cama=ca.id
            left join medicos t5 on t5.id=" . $id_medico . " 
            left join especializaciones t7 on t5.id_especializacion=t7.id
            left join consultorios t6 on t1.id_consultorio=t6.id
            left join profesiones t8 on t5.id_profesion=t8.id
            left join tipo_sangres ts on t2.id_tipo_sangre = ts.id
            Where t1.estado=" . $estado . " AND t1.id=" . $id . " and t2.id_estado_reg = 1 and t9.id_estado_reg=1";
        $data = DB::SELECT($cad);
        return $data[0];
        }
    }

    public function GetBuscaHospitalizadoConsejeria($id,$estado,$id_consejeria)
    {

        if ($id > 0) {
        $cad = "SELECT t2.id id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,
                    t2.sexo,t1.tipo_beneficiario parentesco,t2.fecha_nac,t2.tipo_doc_ident, t2.id_asegurado_titular, /*t1.id_emergencia ,*/ 
            t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.id_establecimiento,t3.nombre establecimiento, t1.id_consultorio id_servicio, t1.nombre_consultorio servicio, 
            t1.id_consultorio_hosp id_sub_consultorio, t6.nombre consultorio,t6.id idconsultorio, t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,
            t5.materno medico_materno, t1.secuencia as secuencia, ts.descripcion_corta as tipo_sangre,
            to_char(age(TO_DATE(t2.fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\" \"y\" DD \"dias\"')  edadpaciente,
            pa.nombre as pabellon,p.nombre as piso,c.nombre as cuarto,ca.nombre as cama
            ,t5.dni nro_dni_medico,t5.colegiatura colegiatura_medico,t5.rne rne_medico,t7.nombre especializacion_medico,t8.nombre profesion_medico, t1.created_at, t2.situacion_paciente,/*t1.tipo_acompaniante as tipo_acompaniante,
            t1.numero_documento_acompaniante as dni_conducido, t1.nombre_acompaniante as nombre_conducido, t1.telefono_acompaniante as telf_conducido,*/
            asegurados_titular.tipo_doc_ident  AS nomtipdoctitular, 
                asegurados_titular.nro_doc_ident  AS nrodoctitular, 
                asegurados_titular.paterno  AS paterno_titular, 
                asegurados_titular.materno  AS materno_titular, 
                asegurados_titular.nombre  AS nombre_titular, 
                asegurados_titular.cip  AS cip_titular,  
                asegurados_titular.grado  AS grado_titular,  
                asegurados_titular.situacion_paciente  AS situaciontitular,  
                asegurados_titular.unidad_pnptitular  AS unidad_pnptitular
            ,t1.cod_siteds_susalud, 
            t1.updated_at_cod_siteds, 
            t1.updated_us_cod_siteds,
            CASE WHEN t1.updated_at_cod_siteds::DATE < CURRENT_DATE  THEN 0 ELSE 1 END AS is_siteds_generate_today
            
            FROM hospitalizaciones t1

            LEFT join consejerias c7 ON t1.id = c7.id_hospitalizacion 
            LEFT join asegurados t2 on t1.dni_beneficiario = t2.nro_doc_ident 
            LEFT JOIN asegurados as asegurados_titular ON  t2.id_asegurado_titular = asegurados_titular.id
            LEFT join asegurado_historias t9 on t2.id = t9.id_asegurado AND t1.id_establecimiento = t9.id_establecimiento 
            LEFT join establecimientos t3 on t1.id_establecimiento=t3.id
            LEFT join pabellones pa on t1.id_pabellon=pa.id
            LEFT join pisos p on t1.id_piso=p.id
            LEFT join cuartos c on t1.id_cuarto = c.id
            LEFT join camas ca on t1.id_cama=ca.id
            left join medicos t5 ON c7.id_medico = t5.id 
            left join especializaciones t7 on t5.id_especializacion=t7.id
            left join consultorios t6 on t1.id_consultorio=t6.id
            left join profesiones t8 on t5.id_profesion=t8.id
            left join tipo_sangres ts on t2.id_tipo_sangre = ts.id
            Where t1.estado=" . $estado . " AND t1.id=" . $id . " AND C7.id=" .$id_consejeria." and t2.id_estado_reg = 1";
        $data = DB::SELECT($cad);
        return $data[0];
        }
    }
    public function GetBuscaHospitalizado2($id)
    {

        if ($id > 0) {
        $cad = "SELECT t2.id id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t9.nro_historia,
                    t2.sexo,t1.tipo_beneficiario parentesco,t2.fecha_nac,t2.tipo_doc_ident, t2.id_asegurado_titular, /*t1.id_emergencia ,*/ 
            t2.nro_doc_ident,t1.fecha_ingreso  dia,t1.id_establecimiento,t3.nombre establecimiento, t1.id_consultorio id_servicio, t1.nombre_consultorio servicio, 
            t1.id_consultorio_hosp id_sub_consultorio, t6.nombre consultorio,t6.id idconsultorio, t5.id id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,
            t5.materno medico_materno, t1.secuencia as secuencia,
            to_char(age(TO_DATE(t2.fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"dias\"')  edadpaciente,
            pa.nombre as pabellon,p.nombre as piso,c.nombre as cuarto,ca.nombre as cama, ca.id as cama_id
            ,t5.dni nro_dni_medico,t5.colegiatura colegiatura_medico,t5.rne rne_medico,t7.nombre especializacion_medico,t8.nombre profesion_medico, t1.created_at, t2.situacion_paciente,/*t1.tipo_acompaniante as tipo_acompaniante,
            t1.numero_documento_acompaniante as dni_conducido, t1.nombre_acompaniante as nombre_conducido, t1.telefono_acompaniante as telf_conducido,*/
            asegurados_titular.tipo_doc_ident  AS nomtipdoctitular, 
                asegurados_titular.nro_doc_ident  AS nrodoctitular, 
                asegurados_titular.paterno  AS paterno_titular, 
                asegurados_titular.materno  AS materno_titular, 
                asegurados_titular.fecha_nac as asegurado_fecha_nac,
                asegurados_titular.sexo as asegurado_sexo,
                DATE_PART('year', AGE(asegurados_titular.fecha_nac::DATE)) as asegurado_edad,
                ts.descripcion_corta asegurado_tipo_sangre_descripcion_corta,
                asegurados_titular.direccion_nacimiento as asegurado_direccion_nacimiento,
                tp.descripcion as tipo_parentesco,
                t5.dni as medico_dni, 
                t7.nombre as especializacion_nombre,
                t8.nombre as profesion_nombre,
                t5.colegiatura as medico_colegiatura,
                t5.rne as medico_rne,
                c.nombre as cuartos_nombre,
                asegurados_titular.nombre  AS nombre_titular, 
                asegurados_titular.cip  AS cip_titular,  
                asegurados_titular.grado  AS grado_titular,  
                asegurados_titular.situacion_paciente  AS situaciontitular,  
                asegurados_titular.unidad_pnptitular  AS unidad_pnptitular,
                t1.tipo_beneficiario as tipo_beneficiario_descripcion,
                ups1.descripcion_upss as upss_encargado,
            t1.cod_siteds_susalud, 
            t1.updated_at_cod_siteds, 
            to_char(t1.fecha_ingreso, 'DD/MM/YYYY') as fecha_registro_ingreso,
            to_char(t1.hora_ingreso ::time,'HH:MI:SS AM') AS hora_registro_ingreso, 
            t1.updated_us_cod_siteds,
            t1.estado as estado,
            p2.id as id_prestacion,
            t1.id as id_ext_hosp,
            CASE WHEN t1.updated_at_cod_siteds::DATE < CURRENT_DATE  THEN 0 ELSE 1 END AS is_siteds_generate_today,
            u.id as upss_id,
            u.codigo as upss_codigo,
            u.descripcion_upss as descripcion_upss,
            cu.nombre as cuarto_origen,
            t1.id_pabellon
            
            FROM hospitalizaciones t1

            LEFT join asegurados t2 on t1.dni_beneficiario = t2.nro_doc_ident 
            LEFT JOIN asegurados as asegurados_titular ON  t2.id_asegurado_titular = asegurados_titular.id
            left join upsses as ups1 on t1.id_upss_encargado = ups1.id
            left join tipo_sangres ts on asegurados_titular.id_tipo_sangre = ts.id 
            left join tipo_parentescos tp on t1.id_tipo_acompaniante = tp.id 
            LEFT join asegurado_historias t9 on t2.id = t9.id_asegurado AND t1.id_establecimiento = t9.id_establecimiento 
            LEFT join establecimientos t3 on t1.id_establecimiento=t3.id
            LEFT join pabellones pa on t1.id_pabellon=pa.id
            LEFT join pisos p on t1.id_piso=p.id
            LEFT join cuartos c on t1.id_cuarto = c.id
            LEFT join cuartos cu on t1.id_cuarto_origen = cu.id
            LEFT join camas ca on t1.id_cama=ca.id
            left join medicos t5 on t5.id=t1.id_medico 
            left join especializaciones t7 on t5.id_especializacion=t7.id
            left join consultorios t6 on t1.id_consultorio=t6.id
            left join upsses u on u.id_consultorio = t6.id
            left join profesiones t8 on t5.id_profesion=t8.id
            left join (
                SELECT id, id_ext, id_tipo_atencion
                FROM prestaciones
                GROUP BY id, id_ext, id_tipo_atencion
            ) p2 ON t1.id = p2.id_ext AND p2.id_tipo_atencion = 3
            Where t1.id=" . $id . " and t2.id_estado_reg = 1";

        $data = DB::SELECT($cad);
        return $data[0];
        }
    }

   

    public function ActualizaUbicacionEstado($id_hopitalizacion, $fecha_inicio_estado, $hora, $id_cama)
    {

        // $date = Carbon::now();
        $hospitalizacione = Hospitalizacione::where('id', $id_hopitalizacion)->first();

        // $hospitalizacione->ubica_prioridad = $prioridadesopciones;
        // $hospitalizacione->ubica_topico = $topicosprioridad;
        $hospitalizacione->fecha_ini_cama_estado = $fecha_inicio_estado;
        $hospitalizacione->hora_ini_cama_estado = $hora;
        $hospitalizacione->id_cama_estado = $id_cama;
        
        $hospitalizacione->save();

        return true;

    }

    public function ActualizaEstadoHospitalizacionAtencion($id_hospitalizacion, $estado, $id_user) {

        $date = Carbon::now();
        $hospitalizacione = Hospitalizacione::where('id', $id_hospitalizacion)->first(); 
        if($estado!=0){
          $hospitalizacione->estado = $estado;  
        }
        $hospitalizacione->fecha_atencion = $date;
        $hospitalizacione->id_user_atencion = $id_user;
        $hospitalizacione->save();
        
        return true;
        
      }

    public function listar_hospitalizaciones_asegurados($p){
        $sql = "SELECT * FROM fn_listar_hospitalizaciones_rango(?, ?, ?, ?, ?)";
        return DB::select($sql, $p);
    }

    public function listar_hospitalizaciones_admisionadas($p) {
        return $this->readFunctionPostgres('sp_listar_hospitalizaciones_admisionadas_enfermeria', $p);
        //return $this->readFunctionPostgres('sp_listar_hospitalizaciones_admisionadas', $p);
    }

    public function listar_hospitalizaciones_admisionadas_geriatrico($p) {
        return $this->readFunctionPostgres('sp_listar_hospitalizaciones_admisionadas_geriatrico', $p);
    }

    public function GetListaTodosHospitalizado2($p) {
		return $this->readFunctionPostgres('sp_prestacion_hospitalizacion', $p);
	}

    public function GetListaHospitalizacionAtencionPendiente($p) {
		return $this->readFunctionPostgres('sp_hospitalizacion_atencion_pendiente', $p);
	}

    public function listar_atencion_hospitalizacion_ajax($p) {
        return $this->readFunctionPostgres('listar_atencion_hospitalizacion_ajax', $p);
    }

    public function listar_hospitalizacion_alta_pendiente($p) {
        return $this->readFunctionPostgres('sp_hospitalizacion_alta_pendiente_listar', $p);
    }

    public function listar_atencion_hospitalizacion_ajax_geriatrico($p) {
        return $this->readFunctionPostgres('listar_atencion_hospitalizacion_ajax_geriatrico', $p);
    }
  
    public function listar_alta_administrativa_ajax($p) {
        return $this->readFunctionPostgres('sp_hospitalizacion_listar_alta_administrativa', $p);
    }
  
    public function GetDiasEstanciaByIdHospitalizacion($id_hospitalizacion) {
        // $datos = Emergencia::where('id_asegurado', '=', $id_asegurado)->first();
        // return $datos;
            $cad = "select /*use.name,*/t1.id_user_alta_administrativa, t1.fecha_alta_administrativa,t1.dni_beneficiario ,to_char(t1.fecha_ingreso::date,'DD/MM/YYYY') fecha_ingreso,
            to_char(t1.hora_ingreso::time,'HH24:MI') hora_ingreso,
            DATE_PART('day', now()::timestamp -  t1.fecha_ingreso::timestamp) * 24 + 
            DATE_PART('hour', now()::timestamp -  t1.fecha_ingreso::timestamp) as dif_horas,
            DATE_PART('day', now()::timestamp -  t1.fecha_ingreso::timestamp) as dia_estancia
            FROM hospitalizaciones t1
            --inner join users use on t1.id_user_alta_administrativa =use.id
            Where t1.id=".$id_hospitalizacion." and t1.estado <> 0 order by t1.fecha_ingreso desc LIMIT 1"; //-- 0 : REBOTA 
            $data = DB::select($cad);
            //dd($data);
            if($data==null){
                return null;
            }else{
                return $data[0];
            }
        }
    public function GetObtenerCama($id) {

        if (empty($id) || !is_numeric($id)) {
        return null;
        }

        $cad = "select T6.*,T1.id_asignacion_servicio,T2.id_piso,T4.id_pabellon,T1.nombre cuarto, T5.nombre pabellon, T4.nombre piso,  T8.nombre consultorio , T6.nombre cama, T2.id_consultorio
                from camas T6
                inner join cuartos T1 on T6.id_cuarto=T1.id
                inner join asignacion_servicios T2 on T1.id_asignacion_servicio = T2.id
                inner join consultorios T3 on T2.id_consultorio=T3.id 
                inner join pisos T4 on T2.id_piso=T4.id
                inner join pabellones T5 on T4.id_pabellon=T5.id
                inner join consultorios T8 on T2.id_consultorio = T8.id
                where T6.id=".$id."
                limit 1 ";
        
        $data = DB::select($cad);
        return !empty($data) ? $data[0] : null;
    }

    public function GetDiasEstancia($id_asegurado,$fecha_egreso,$hora_egreso)
    {   
        $cad = "select t1.dni_beneficiario ,to_char(t1.fecha_ingreso::date,'DD/MM/YYYY') fecha_ingreso,
                    to_char(t1.hora_ingreso::time,'HH24:MI') hora_ingreso,
                    DATE_PART('day', '".$fecha_egreso." ".$hora_egreso."' -  t1.fecha_ingreso::timestamp) as dia_estancia
                    FROM hospitalizaciones t1
                    Where t1.id_asegurado=".$id_asegurado. " and t1.estado <> 0 order by t1.created_at desc LIMIT 1"; //-- 0 : REBOTA 
        
        $data = DB::select($cad);
        
        if($data==null){
            return null;
        }else{
            return $data[0];
        }
    }


    public function ExisteCodigoSiteds($codigo) {
        
        $codigo_unico = false;
        $count = 0;
        $cad = "select count(id) from emergencias where cod_siteds_susalud = '$codigo';";
        $data = DB::select($cad);
        $count = (int) $data[0]->count;
        if ($count == 0) :
            $cad = "select count(id) from hospitalizaciones where cod_siteds_susalud = '$codigo';";
            $data = DB::select($cad);
            $count = (int) $data[0]->count;
            if ($count == 0) :
                $cad = "select count(id) from evaluaciones where cod_siteds_susalud = '$codigo';";
                $data = DB::select($cad);
                $count = (int) $data[0]->count;
                if ($count == 0) :
                    $codigo_unico = true;
                endif;
            endif;
        endif;
        return $codigo_unico;
        
    }
    
    public function getById($id) {
        $query = "select 
        id, id_farmacia, nro_hospitalizacion, dni_beneficiario, nombre_beneficiario, paterno_beneficiario, materno_beneficiario, dni_titular, nombre_titular, paterno_titular, 
        materno_titular, fecha_nacimiento_beneficiario, edad, sexo, tipo_beneficiario, nro_historia, turno, turno_salida, tarifa, origen, grado, id_diagnostico_inicial, 
        id_diagnostico_final, condicion, fecha_salida, id_medico, id_medico_alta, id_pabellon, id_piso, id_consultorio_hosp, nombre_consultorio, id_cuarto, 
        id_cama, estado, id_consultorio, id_user, nombre_registrador, id_user_alta, nombre_registrador_alta, id_diagnostico_hospitalizacion, motivo, 
        tipo_hospitalizacion, id_establecimiento, secuencia, id_asegurado, id_servicio_origen, hora_egreso, id_ingreso/*, id_emergencia*/, cod_siteds_susalud, 
        cups_pnp, fecha_ini_cama_estado, hora_ini_cama_estado, id_cama_estado, fecha_atencion, id_user_atencion/*, fecha_firma_egreso*/, fecha_alta_medica, hora_alta_medica, 
        id_tipo_egreso, id_condicion_alta, derivar, /*id_servicio_destino,*/ observacion_egreso/*, id_tipo_beneficiario*/, flag_egreso, id_diag_cab, id_diag_cuer1, id_diag_cuer2, id_cama_estado,
        necropsia, causas_muerte, cpms_alta, id_establecimiento_traslado, obs_traslado, especialida_destino, especialidad_destino_otro, condicion_traslado, id_user_alta_administrativa, 
        id_tipo_doc_ident, id_tipo_origen, id_ext_origen, id_dx_2, id_dx_3, hora_alta_administrativa, fecha_alta_administrativa,
        to_char(created_at::date,'DD/MM/YYYY') as fecha_creacion, 
        to_char(created_at,'HH:MI:SS AM') as hora_creacion,
        to_char(fecha_ingreso::date,'DD/MM/YYYY') as fecha_ingreso, 
        to_char(hora_ingreso::time,'HH:MI:SS AM') as hora_ingreso
        from hospitalizaciones h 
        where h.id = " . $id;
        $data = DB::select($query);
        return $data[0];
    }

    public function getDatosEgresoAltaAdministrativaById($id) {
        $data = DB::table('hospitalizaciones as h')
            ->select(
                'h.id as id_hospitalizacion',
                'e.nombre as establecimiento',
                'h.cups_pnp',
                'h.cod_siteds_susalud',
                'h.id_asegurado',
                'h.paterno_beneficiario as paciente_paterno',
                'h.materno_beneficiario as paciente_materno',
                'h.nombre_beneficiario as paciente_nombre',
                'h.dni_beneficiario as paciente_ndocumento',
                'h.sexo as paciente_sexo',
                'h.edad as paciente_edad_ingreso',
                'h.nro_historia as paciente_nhistoria',
                'h.tipo_beneficiario as paciente_parentesco',
                'h.grado as titular_grado',
                'h.created_at as created_at',
                'a.nro_doc_ident as asegurado_ndocumento',
                'a2.nro_doc_ident as titular_ndocumento',
                'a2.paterno as titular_paterno',
                'a2.materno as titular_materno',
                'a2.nombre as titular_nombre',
                'a2.cip as titular_cip',
                'h.secuencia',
                DB::raw('CAST(h.fecha_ingreso AS DATE) as fecha_ingreso'),
                'h.hora_ingreso',
                'me.paterno as medico_egreso_paterno',
                'me.materno as medico_egreso_materno',
                'me.nombre as medico_egreso_nombre',
                'ue.dni as medico_egreso_ndocumento',
                'pe.nombre as medico_egreso_profesion',
                'ee.nombre as medico_egreso_especialidad',
                'me.colegiatura as medico_egreso_colegiatura',
                'me.rne as medico_egreso_rne',
                'de1.codigo as cie_egreso_codigo1',
                'de1.nombre as cie_egreso_nombre1',
                DB::raw("'PRINCIPAL' as cie_egreso_categoria1"),
                'de2.codigo as cie_egreso_codigo2',
                'de2.nombre as cie_egreso_nombre2',
                DB::raw("'SECUNDARIO' as cie_egreso_categoria2"),
                'de3.codigo as cie_egreso_codigo3',
                'de3.nombre as cie_egreso_nombre3',
                DB::raw("'SECUNDARIO' as cie_egreso_categoria3"),
                'h.id_cama_estado',
                'ce.nombre as cama',
                'ce2.nombre as cuarto',
                'ce3.nombre as servicio',
                'pe2.nombre as piso',
                'pe3.nombre as pabellon',
                DB::raw('CAST(h.fecha_alta_medica AS DATE) as fecha_alta_medica'),
                'h.hora_alta_medica',
                DB::raw("CASE 
                            WHEN h.id_tipo_egreso = 1 THEN 'ALTA'
                            WHEN h.id_tipo_egreso = 3 THEN 'TRANSFERIDO'
                            WHEN h.id_tipo_egreso = 4 THEN 'FALLECIDO'
                            WHEN h.id_tipo_egreso = 6 THEN 'FUGA'
                            WHEN h.id_tipo_egreso = 7 THEN 'RETIRO VOLUNTARIO'
                            ELSE '-' 
                        END as tipo_egreso"),
                DB::raw("CASE 
                            WHEN h.id_condicion_alta = 1 THEN 'MEJORADO'
                            WHEN h.id_condicion_alta = 2 THEN 'CURADO'
                            WHEN h.id_condicion_alta = 3 THEN 'TRASLADO'
                            WHEN h.id_condicion_alta = 4 THEN 'RETIRO VOLUNTARIO'
                            WHEN h.id_condicion_alta = 5 THEN 'FUGADO'
                            WHEN h.id_condicion_alta = 6 THEN 'FALLECIDO'
                            ELSE '-' 
                        END as condicion_egreso"),
                DB::raw('CAST(h.fecha_alta_administrativa AS DATE) as fecha_alta_administrativa'),
                'h.hora_alta_administrativa',
                'uea.name as user_nombre',
                'uea.paterno as user_paterno',
                'uea.materno as user_materno'
            )
            ->join('users as ue', 'h.id_medico_alta', '=', 'ue.id')
            ->leftJoin('medicos as me', function($join) {
                $join->on('ue.dni', '=', 'me.dni')
                     ->on('ue.id_establecimiento', '=', 'me.id_establecimiento');
            })
            ->leftJoin('profesiones as pe', 'me.id_profesion', '=', 'pe.id')
            ->leftJoin('especializaciones as ee', 'me.id_especializacion', '=', 'ee.id')
            ->leftJoin('diagnosticos as de1', 'h.id_diag_cab', '=', 'de1.id')
            ->leftJoin('diagnosticos as de2', 'h.id_diag_cuer1', '=', 'de2.id')
            ->leftJoin('diagnosticos as de3', 'h.id_diag_cuer2', '=', 'de3.id')
            ->leftJoin('camas as ce', 'h.id_cama_estado', '=', 'ce.id')
            ->leftJoin('cuartos as ce2', 'ce.id_cuarto', '=', 'ce2.id')
            ->leftJoin('asignacion_servicios as ase2', 'ce2.id_asignacion_servicio', '=', 'ase2.id')
            ->leftJoin('consultorios as ce3', 'ase2.id_consultorio', '=', 'ce3.id')
            ->leftJoin('pisos as pe2', 'ase2.id_piso', '=', 'pe2.id')
            ->leftJoin('pabellones as pe3', 'pe2.id_pabellon', '=', 'pe3.id')
            ->leftJoin('users as uea', 'h.id_user_alta_administrativa', '=', 'uea.id')
            ->leftJoin('establecimientos as e', 'h.id_establecimiento', '=', 'e.id')
            ->leftJoin('asegurados as a', 'h.id_asegurado', '=', 'a.id')
            ->leftJoin('asegurados as a2', 'a.id_asegurado_titular', '=', 'a2.id')
            ->where('h.id', $id)
            ->where('h.estado', 7)
            ->first();
        return $data;
    }
    public function getDatosHospitalizacionFichaEstadistica($id){
        $data = DB::table('hospitalizaciones as h')
            ->select(
                'h.cod_siteds_susalud',
                'h.causas_muerte',
                'h.created_at as created_at',
                'h.cups_pnp',
                'h.dni_beneficiario as paciente_ndocumento',
                'h.edad as paciente_edad_ingreso',
                'h.egreso_complicacion as egreso_complicacion',
                'h.egreso_examen_auxiliar as egreso_examen_auxiliar',
                'h.egreso_examen_clinico as egreso_examen_clinico',
                'h.egreso_procedimiento as egreso_procedimiento',
                'h.egreso_pronostico as egreso_pronostico',
                DB::raw('to_char(h.fecha_alta_medica, \'DD/MM/YYYY\') as fecha_alta_medica'),
                DB::raw('to_char(h.fecha_alta_administrativa, \'DD/MM/YYYY\') as fecha_alta_administrativa'),
                DB::raw('to_char(h.fecha_atencion, \'DD/MM/YYYY\') as fecha_atencion'),
                DB::raw('to_char(h.fecha_defuncion, \'DD/MM/YYYY\') as fecha_defuncion'),
                DB::raw('to_char(h.hora_defuncion::time, \'hh:mi:ss\') as hora_defuncion'),
                DB::raw('to_char(h.fecha_ingreso, \'DD/MM/YYYY\') as fecha_ingreso'),
                DB::raw('to_char(h.fecha_nacimiento_beneficiario, \'DD/MM/YYYY\') as fecha_nacimiento_beneficiario'),
                'h.grado as titular_grado',
                'h.hora_ingreso',
                'h.id as id_hospitalizacion',
                'h.id_asegurado',
                'h.id_condicion_alta',
                'h.id_diagnostico_inicial',
                'h.id_establecimiento',
                'h.paterno_beneficiario as paciente_paterno',
                'h.materno_beneficiario as paciente_materno',
                'h.nombre_beneficiario as paciente_nombre',
                'h.nombre_registrador as nombre_registrador',
                'h.nro_hospitalizacion as nro_hospitalizacion',
                'h.nro_historia as paciente_nhistoria',
                'h.sexo as paciente_sexo',
                'h.tarifa as tarifa',
                'h.tipo_beneficiario as paciente_parentesco',
                'e.nombre as establecimiento',
                'a.cip as asegurado_cip',
                'a.id_raza as id_raza',
                'a.nro_doc_ident as asegurado_ndocumento',
                'a.unidad_pnptitular as unidad_pnptitular',
                'a2.cip as titular_cip',
                'a2.materno as titular_materno',
                'a2.nombre as titular_nombre',
                'a2.nro_doc_ident as titular_ndocumento',
                'a2.paterno as titular_paterno',
                'h.secuencia',
                'mea.paterno as medico_admision_paterno',
                'mea.materno as medico_admision_materno',
                'mea.nombre as medico_admision_nombre',
                'ue.dni as medico_egreso_ndocumento',
                'pe.nombre as medico_egreso_profesion',
                'ee.nombre as medico_egreso_especialidad',
                'me.colegiatura as medico_egreso_colegiatura',
                'me.rne as medico_egreso_rne',
                'de1.codigo as cie_egreso_codigo1',
                'de1.nombre as cie_egreso_nombre1',
                DB::raw("'PRINCIPAL' as cie_egreso_categoria1"),
                'de2.codigo as cie_egreso_codigo2',
                'de2.nombre as cie_egreso_nombre2',
                DB::raw("'SECUNDARIO' as cie_egreso_categoria2"),
                'de3.codigo as cie_egreso_codigo3',
                'de3.nombre as cie_egreso_nombre3',
                DB::raw("'SECUNDARIO' as cie_egreso_categoria3"),
                'h.id_cama_estado',
                'ce.nombre as cama',
                'ce2.nombre as cuarto',
                'ce3.nombre as servicio',
                'pe2.nombre as piso',
                'pe3.nombre as pabellon',
                'h.hora_alta_medica',
                DB::raw("CASE 
                            WHEN h.id_tipo_egreso = 1 THEN 'ALTA'
                            WHEN h.id_tipo_egreso = 3 THEN 'TRANSFERIDO'
                            WHEN h.id_tipo_egreso = 4 THEN 'FALLECIDO'
                            WHEN h.id_tipo_egreso = 6 THEN 'FUGA'
                            WHEN h.id_tipo_egreso = 7 THEN 'RETIRO VOLUNTARIO'
                            ELSE '-' 
                        END as tipo_egreso"),
                DB::raw("CASE 
                            WHEN h.id_condicion_alta = 1 THEN 'MEJORADO'
                            WHEN h.id_condicion_alta = 2 THEN 'CURADO'
                            WHEN h.id_condicion_alta = 3 THEN 'TRASLADO'
                            WHEN h.id_condicion_alta = 4 THEN 'RETIRO VOLUNTARIO'
                            WHEN h.id_condicion_alta = 5 THEN 'FUGADO'
                            WHEN h.id_condicion_alta = 6 THEN 'FALLECIDO'
                            ELSE '-' 
                        END as condicion_egreso"),
                'h.hora_alta_administrativa',
                'uea.name as user_nombre',
                'uea.paterno as user_paterno',
                'uea.materno as user_materno'
            )
            ->join('users as ue', 'h.id_medico_alta', '=', 'ue.id')
            ->leftJoin('medicos as me', function($join) {
                $join->on('ue.dni', '=', 'me.dni')
                    ->on('ue.id_establecimiento', '=', 'me.id_establecimiento');
            })
            ->leftJoin('medicos as mea', 'h.id_medico', '=', 'mea.id')
            ->leftJoin('profesiones as pe', 'me.id_profesion', '=', 'pe.id')
            ->leftJoin('especializaciones as ee', 'me.id_especializacion', '=', 'ee.id')
            ->leftJoin('diagnosticos as de1', 'h.id_diag_cab', '=', 'de1.id')
            ->leftJoin('diagnosticos as de2', 'h.id_diag_cuer1', '=', 'de2.id')
            ->leftJoin('diagnosticos as de3', 'h.id_diag_cuer2', '=', 'de3.id')
            ->leftJoin('camas as ce', 'h.id_cama_estado', '=', 'ce.id')
            ->leftJoin('cuartos as ce2', 'ce.id_cuarto', '=', 'ce2.id')
            ->leftJoin('asignacion_servicios as ase2', 'ce2.id_asignacion_servicio', '=', 'ase2.id')
            ->leftJoin('consultorios as ce3', 'ase2.id_consultorio', '=', 'ce3.id')
            ->leftJoin('pisos as pe2', 'ase2.id_piso', '=', 'pe2.id')
            ->leftJoin('pabellones as pe3', 'pe2.id_pabellon', '=', 'pe3.id')
            ->leftJoin('users as uea', 'h.id_user_alta_administrativa', '=', 'uea.id')
            ->leftJoin('establecimientos as e', 'h.id_establecimiento', '=', 'e.id')
            ->leftJoin('asegurados as a', 'h.id_asegurado', '=', 'a.id')
            ->leftJoin('asegurados as a2', 'a.id_asegurado_titular', '=', 'a2.id')
            ->where('h.id', $id)
            ->where('h.estado', 7)
            ->first();
        return $data;
    }
    public function getDatosPapeletaEgreso($id_hospitalizacion) {
        $data = DB::table('hospitalizaciones as h')
            ->select(
                'h.cod_siteds_susalud',
                'h.cups_pnp',
                'h.dni_beneficiario as paciente_ndocumento',
                'h.edad as paciente_edad_ingreso',
                'h.estado as estado',
                DB::raw('to_char(h.fecha_alta_medica, \'DD/MM/YYYY\') as fecha_alta_medica'),
                DB::raw('to_char(h.fecha_alta_administrativa, \'DD/MM/YYYY\') as fecha_alta_administrativa'),
                DB::raw('to_char(h.fecha_atencion, \'DD/MM/YYYY\') as fecha_atencion'),
                DB::raw('to_char(h.fecha_ingreso, \'DD/MM/YYYY\') as fecha_ingreso'),
                'h.grado as titular_grado',
                'h.nro_historia as paciente_nhistoria',
                'h.nro_hospitalizacion as nro_hospitalizacion',
                'h.id_asegurado',
                'h.id_condicion_alta',
                'h.id as id_hospitalizacion',
                'h.materno_beneficiario as paciente_materno',
                'h.nombre_beneficiario as paciente_nombre',
                'h.paterno_beneficiario as paciente_paterno',
                'h.sexo as paciente_sexo',
                'h.tipo_beneficiario as paciente_parentesco',
                'e.nombre as establecimiento',
                DB::raw('to_char(h.created_at, \'DD/MM/YYYY hh:mi:ss\') as created_at'),
                'a.nro_doc_ident as asegurado_ndocumento',
                'a2.cip as titular_cip',
                'a2.nro_doc_ident as titular_ndocumento',
                'a2.materno as titular_materno',
                'a2.nombre as titular_nombre',
                'a2.paterno as titular_paterno',
                'h.secuencia',
                'h.hora_ingreso',
                'me.paterno as medico_egreso_paterno',
                'me.materno as medico_egreso_materno',
                'me.nombre as medico_egreso_nombre',
                'ue.dni as medico_egreso_ndocumento',
                'pe.nombre as medico_egreso_profesion',
                'ee.nombre as medico_egreso_especialidad',
                'me.colegiatura as medico_egreso_colegiatura',
                'me.rne as medico_egreso_rne',
                'de1.codigo as cie_egreso_codigo1',
                'de1.nombre as cie_egreso_nombre1',
                DB::raw("'PRINCIPAL' as cie_egreso_categoria1"),
                'de2.codigo as cie_egreso_codigo2',
                'de2.nombre as cie_egreso_nombre2',
                DB::raw("'SECUNDARIO' as cie_egreso_categoria2"),
                'de3.codigo as cie_egreso_codigo3',
                'de3.nombre as cie_egreso_nombre3',
                DB::raw("'SECUNDARIO' as cie_egreso_categoria3"),
                'h.id_cama_estado',
                'ce.nombre as cama',
                'ce2.nombre as cuarto',
                'ce3.nombre as servicio',
                'pe2.nombre as piso',
                'pe3.nombre as pabellon',
                'h.hora_alta_medica',
                DB::raw("CASE 
                            WHEN h.id_tipo_egreso = 1 THEN 'ALTA'
                            WHEN h.id_tipo_egreso = 3 THEN 'TRANSFERIDO'
                            WHEN h.id_tipo_egreso = 4 THEN 'FALLECIDO'
                            WHEN h.id_tipo_egreso = 6 THEN 'FUGA'
                            WHEN h.id_tipo_egreso = 7 THEN 'RETIRO VOLUNTARIO'
                            ELSE '-' 
                        END as tipo_egreso"),
                DB::raw("CASE 
                            WHEN h.id_condicion_alta = 1 THEN 'MEJORADO'
                            WHEN h.id_condicion_alta = 2 THEN 'CURADO'
                            WHEN h.id_condicion_alta = 3 THEN 'TRASLADO'
                            WHEN h.id_condicion_alta = 4 THEN 'RETIRO VOLUNTARIO'
                            WHEN h.id_condicion_alta = 5 THEN 'FUGADO'
                            WHEN h.id_condicion_alta = 6 THEN 'FALLECIDO'
                            ELSE '-' 
                        END as condicion_egreso"),
                'uea.name as user_nombre',
                'uea.paterno as user_paterno',
                'uea.materno as user_materno',
                'ue.name as user_nombre_medico_alta',
                'ue.paterno as user_paterno_medico_alta',
                'ue.materno as user_materno_medico_alta',
                 DB::raw("regexp_replace(ups.descripcion_upss, '-', ' ', 'g') as upss_acargo"),
                'co.nombre as servicio_acargo' 
            )
            ->join('users as ue', 'h.id_medico_alta', '=', 'ue.id')
            ->leftJoin('medicos as me', function($join) {
                $join->on('ue.dni', '=', 'me.dni')
                    ->on('ue.id_establecimiento', '=', 'me.id_establecimiento');
            })
            ->leftJoin('profesiones as pe', 'me.id_profesion', '=', 'pe.id')
            ->leftJoin('especializaciones as ee', 'me.id_especializacion', '=', 'ee.id')
            ->leftJoin('diagnosticos as de1', 'h.id_diag_cab', '=', 'de1.id')
            ->leftJoin('diagnosticos as de2', 'h.id_diag_cuer1', '=', 'de2.id')
            ->leftJoin('diagnosticos as de3', 'h.id_diag_cuer2', '=', 'de3.id')
            ->leftJoin('camas as ce', 'h.id_cama_estado', '=', 'ce.id')
            ->leftJoin('cuartos as ce2', 'ce.id_cuarto', '=', 'ce2.id')
            ->leftJoin('asignacion_servicios as ase2', 'ce2.id_asignacion_servicio', '=', 'ase2.id')
            ->leftJoin('consultorios as ce3', 'ase2.id_consultorio', '=', 'ce3.id')
            ->leftJoin('pisos as pe2', 'ase2.id_piso', '=', 'pe2.id')
            ->leftJoin('pabellones as pe3', 'pe2.id_pabellon', '=', 'pe3.id')
            ->leftJoin('users as uea', 'h.id_user_alta_administrativa', '=', 'uea.id')
            ->leftJoin('establecimientos as e', 'h.id_establecimiento', '=', 'e.id')
            ->leftJoin('asegurados as a', 'h.id_asegurado', '=', 'a.id')
            ->leftJoin('asegurados as a2', 'a.id_asegurado_titular', '=', 'a2.id')
            ->leftJoin('upsses as ups', 'h.id_upss_encargado', '=', 'ups.id')
            ->leftJoin('consultorios as co', 'h.id_servicio_encargado', '=', 'co.id')
            ->where('h.id', $id_hospitalizacion)
            ->first();
        return $data;
    }
    public function getHospitalizacionById($id_hospitalizacion) {
        $sql = "select
                    hospitalizaciones.*,
                    diagnosticos.codigo as codigo_cie,
                    diagnosticos.nombre as nombre_cie,
                    d5.codigo as codigo_cie_ingreso_2,
                    d5.nombre as nombre_cie_ingreso_2,
                    d6.codigo as codigo_cie_ingreso_3,
                    d6.nombre as nombre_cie_ingreso_3,
                    d2.codigo as codigo_cie_egreso_1,
                    d2.nombre as nombre_cie_egreso_1,
                    d3.codigo as codigo_cie_egreso_2,
                    d3.nombre as nombre_cie_egreso_2,
                    d4.codigo as codigo_cie_egreso_3,
                    d4.nombre as nombre_cie_egreso_3,
                    m.paterno as profesional_egreso_paterno,
                    m.materno as profesional_egreso_materno,
                    m.nombre as profesional_egreso_nombre,
                    m.colegiatura as profesional_egreso_colegiatura,
                    pm.nombre as profesional_egreso_profesion,
                    camas.nombre as cama,
                    consultorios.nombre as servicio_sala,
                    establecimientos.nombre as establecimiento_traslado,
                    camas_2.nombre as cama_egreso,
                    consultorios_2.id as id_servicio_sala_egreso,
                    consultorios_2.nombre as servicio_sala_egreso,
                    COALESCE(servicio_encargado.nombre, '') as servicio_encargado_nombre
                from
                    hospitalizaciones
                inner join diagnosticos on
                    hospitalizaciones.id_diagnostico_inicial = diagnosticos.id
                left join diagnosticos as d5 on
                    hospitalizaciones.id_dx_2 = d5.id
                left join diagnosticos as d6 on
                    hospitalizaciones.id_dx_3 = d6.id
                left join diagnosticos as d2 on
                    hospitalizaciones.id_diag_cab = d2.id
                left join diagnosticos as d3 on
                    hospitalizaciones.id_diag_cuer1 = d3.id
                left join diagnosticos as d4 on
                    hospitalizaciones.id_diag_cuer2 = d4.id
                inner join users as u on
                    hospitalizaciones.id_medico_alta = u.id
                inner join medicos as m on
                    u.dni = m.dni
                    and u.id_establecimiento = m.id_establecimiento
                left join profesiones as pm on m.id_profesion = pm.id
                inner join camas on hospitalizaciones.id_cama_estado = camas.id
                inner join cuartos on camas.id_cuarto = cuartos.id
                inner join asignacion_servicios on cuartos.id_asignacion_servicio = asignacion_servicios.id
                inner join consultorios on asignacion_servicios.id_consultorio = consultorios.id
                left join establecimientos on hospitalizaciones.id_establecimiento_traslado = establecimientos.id
                left join camas as camas_2 on hospitalizaciones.id_cama_egreso = camas_2.id
                left join cuartos as cuartos_2 on camas_2.id_cuarto = cuartos_2.id
                left join asignacion_servicios as as3 on cuartos_2.id_asignacion_servicio = as3.id
                left join consultorios as consultorios_2 on as3.id_consultorio = consultorios_2.id
                left join consultorios as servicio_encargado on hospitalizaciones.id_servicio_encargado = servicio_encargado.id
                where
                    hospitalizaciones.id = $id_hospitalizacion
                limit 1";
        $data = DB::select($sql);
        return $data[0];
    }

    public function registrar_alta_servicio($id_hospitalizacion, $id_prestacion, $id_evolucion = 0, $id_user, $fecha, $hora, $id_cama, $observacion, $diagnosticos)
    {
        $cad = "Select sp_insert_hospitalizacion_alta_servicio(?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_hospitalizacion, $id_prestacion, $id_evolucion, $id_user, $fecha, $hora, $id_cama, $observacion, $diagnosticos));
        return $data[0]->sp_insert_hospitalizacion_alta_servicio;
    }

    public function registrar_evolucion_alta_servicio($id_hospitalizacion, $id_prestacion, $id_evolucion = 0, $id_user, $fecha, $hora, $id_cama, $observacion, $diagnosticos)
    {
        $cad = "Select sp_insert_hospitalizacion_evolucion_alta_servicio(?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_hospitalizacion, $id_prestacion, $id_evolucion, $id_user, $fecha, $hora, $id_cama, $observacion, $diagnosticos));
        return $data[0]->sp_insert_hospitalizacion_evolucion_alta_servicio;
    }

    public function getServiciosConcurrenciaByIdHospitalizacion($id_hospitalizacion) {
        $data = DB::table('hospitalizacion_concurrencias')
        ->join('consultorios', 'hospitalizacion_concurrencias.id_servicio', '=', 'consultorios.id')
        ->where('id_hospitalizacion', $id_hospitalizacion)
        ->select('hospitalizacion_concurrencias.*', 'consultorios.nombre as servicio')
        ->where('hospitalizacion_concurrencias.id_estado_reg', 1)
        ->orderBy('id', 'asc')
        ->get();
        
        return $data != null ? $data : null;
    }

    /**
     * Uso: Historial de las primeras atenciones en Sala de una Hospitalizacion.
     */
    public function getAtencionesById($id) {
        
        $prestaciones = DB::table('hospitalizaciones as h')
            ->join('prestaciones as t1', function($join) {
                $join->on('t1.id_ext', '=', 'h.id')
                    ->where('t1.id_tipo_atencion', '=', 3)
                    ->where('t1.id_estado_reg', '=', 1);
            })
            ->join('medicos as t5', 't1.id_medico', '=', 't5.id')
            ->join('cambio_camas as cc', function($join) {
                $join->on('t1.id', '=', 'cc.id_prestacion')
                    ->where('cc.id_evolucion', '=', 0);
            })
            ->join('camas as ca', 'cc.id_cama_atencion', '=', 'ca.id')
            ->join('cuartos as c2', 'ca.id_cuarto', '=', 'c2.id')
            ->join('asignacion_servicios as as2', 'c2.id_asignacion_servicio', '=', 'as2.id')
            ->join('consultorios as c', 'as2.id_consultorio', '=', 'c.id')
            ->leftJoin('consultorios as servicio_encargado', 'cc.id_servicio_encargado_atencion', '=', 'servicio_encargado.id')
            ->leftJoin('hospitalizacion_atenciones as ha', 't1.id', '=', 'ha.id_prestacion')
            ->join('establecimientos as est', 't1.id_establecimiento', '=', 'est.id')
            ->where('h.id', $id)
            ->select(
                't1.id as id_prestacion',
                't1.id_medico',
                't5.nombre as medico_nombre',
                't5.paterno as medico_paterno',
                't5.materno as medico_materno',
                't5.dni',
                DB::raw("to_char(t1.fecha_atencion, 'HH24:MI AM') as hora_atencion"),
                DB::raw("to_char(t1.fecha_atencion, 'DD/MM/YYYY') as fecha_atencion"),
                't1.ruta_prestacion',
                DB::raw("(select array_agg(rv.ruta_receta order by rv.id) from receta_vales rv where rv.id_prestacion=t1.id and rv.estado<>0 and t1.id_ext=h.id and rv.id_evaluacion=0) as ruta_receta"),
                DB::raw("(select array_agg(rva.flag order by rva.id) from receta_vales rva where rva.id_prestacion=t1.id and rva.estado<>0 and t1.id_ext=h.id and rva.id_evaluacion=0) as flag_receta"),
                DB::raw("(select array_agg(la.ruta_laboratorio order by la.id) from laboratorios la where la.id_prestacion=t1.id and la.estado=1 and t1.id_ext=h.id and la.id_evaluacion=0) as ruta_laboratorio"),
                DB::raw("(select array_agg(lab.flag order by lab.id) from laboratorios lab where lab.id_prestacion=t1.id and lab.estado<>0 and t1.id_ext=h.id and lab.id_evaluacion=0) as flag_laboratorio"),
                DB::raw("(select array_agg(l.id) from laboratorios l where l.id_prestacion=t1.id and l.estado<>0 and t1.id_ext=h.id and l.id_evaluacion=0) as lid"),
                DB::raw("(select array_agg(ra.ruta_radiologia order by ra.id) from radiologias ra where ra.id_prestacion=t1.id and ra.estado=1 and t1.id_ext=h.id and ra.id_evaluacion=0) as ruta_radiologia"),
                DB::raw("(select array_agg(rad.flag order by rad.id) from radiologias rad where rad.id_prestacion=t1.id and rad.estado<>0 and t1.id_ext=h.id and rad.id_evaluacion=0) as flag_radiologia"),
                DB::raw("(select array_agg(r.id) from radiologias r where r.id_prestacion=t1.id and r.estado<>0 and t1.id_ext=h.id and r.id_evaluacion=0) as rid"),
                DB::raw("(select array_agg(ref.ruta_referencia order by ref.id) from referencias ref where ref.id_prestacion=t1.id and ref.id_estado_reg=1 and t1.id_ext=h.id) as ruta_referencia"),
                DB::raw("(select array_agg(refe.flag order by refe.id) from referencias refe where refe.id_prestacion=t1.id and refe.id_estado_reg<>0 and t1.id_ext=h.id) as flag_referencia"),
                DB::raw("(select array_agg(i.ruta_interconsulta order by i.id) from interconsultas i where i.id_prestacion=t1.id and i.id_estado_reg=1 and t1.id_ext=h.id and i.id_evaluacion=0) as ruta_interconsulta"),
                DB::raw("(select array_agg(rpt.ruta_rpta_interconsulta order by rpt.id) from interconsultas rpt where rpt.id_prestacion=t1.id and rpt.id_estado_reg=1 and t1.id_ext=h.id and rpt.id_evaluacion=0) as ruta_rpta_interconsulta"),
                DB::raw("(select array_agg(inter.flag order by inter.id) from interconsultas inter where inter.id_prestacion=t1.id and inter.id_estado_reg<>0 and t1.id_ext=h.id and inter.id_evaluacion=0) as flag_interconsulta"),
                DB::raw("(select array_agg(rp.flag_rpta order by rp.id) from interconsultas rp where rp.id_prestacion=t1.id and rp.id_estado_reg<>0 and t1.id_ext=h.id and rp.id_evaluacion=0) as flag_rpta_interconsulta"),
                't1.id_estado_reg',
                't1.flag',
                'c2.nombre as nombre_cuarto',
                'ca.nombre as nombre_cama',
                'c.nombre as servicio',
                'h.estado as estado',
                'h.fecha_firma_terapeutica',
                'h.ruta_terapeutica',
                'h.flag_terapeutica',
                'h.flag_terapeutica',
                'ha.hoja_terapeutica',
                'servicio_encargado.nombre as servicio_encargado_atencion',
                DB::raw("CASE WHEN DATE(t1.created_at) = CURRENT_DATE THEN '1' ELSE '0' END AS is_editable"),
                'est.nivel as nivel_establecimiento'
            )
            ->orderBy('t1.fecha_atencion', 'desc')
            ->get();

        return $prestaciones;
    }

    public function getAtencionesResumenById($id) {
        $atenciones = DB::table('hospitalizaciones as h')
            ->join('prestaciones as t1', function($join) {
            $join->on('t1.id_ext', '=', 'h.id')
                ->where('t1.id_tipo_atencion', '=', 3)
                ->where('t1.id_estado_reg', '=', 1)
                ->where(DB::raw('coalesce(t1.flag_intervencion_quirurgica, false)'), '=', DB::raw('false'));
            })
            ->join('medicos as t5', 't1.id_medico', '=', 't5.id')
            ->join('cambio_camas as cc', function($join) {
            $join->on('t1.id', '=', 'cc.id_prestacion')
                ->where('cc.id_evolucion', '=', 0);
            })
            ->join('camas as ca', 'cc.id_cama_atencion', '=', 'ca.id')
            ->join('cuartos as c2', 'ca.id_cuarto', '=', 'c2.id')
            ->join('asignacion_servicios as as2', 'c2.id_asignacion_servicio', '=', 'as2.id')
            ->join('consultorios as c', 'as2.id_consultorio', '=', 'c.id')
            ->join('establecimientos as e', 'h.id_establecimiento', '=', 'e.id')
            ->join('tipo_atenciones as t', 't1.id_tipo_atencion', '=', 't.id')
            ->leftJoin('consultorios as servicio_encargado', 'cc.id_servicio_encargado_atencion', '=', 'servicio_encargado.id')
            ->where('h.id', $id)
            ->select(
                't1.id',
                't5.nombre as medico_nombre',
                't5.paterno as medico_paterno',
                't5.materno as medico_materno',
                DB::raw("to_char(t1.fecha_atencion, 'HH24:MI AM') as hora"),
                DB::raw("to_char(t1.fecha_atencion, 'DD/MM/YYYY') as dia"),
                't1.id_estado_reg',
                'c2.nombre as nombre_cuarto',
                'ca.nombre as cama',
                'c.nombre as servicio',
                'e.nombre as establecimiento',
                't.nombre as tipo_atencion',
                't1.id_estado_reg',
                'servicio_encargado.nombre as servicio_encargado_atencion'
                )
            ->orderBy('t1.fecha_atencion','desc')
            ->get();

        return $atenciones;
    }

    public function enviartrama($id_hospitalizacion){
        $data = DB::select("SELECT sp_insertar_tramas_hospitalizaciones_2 ($id_hospitalizacion)");
      }
    public function GetOrdenById($id){

        $idExtOrigen = DB::table('hospitalizaciones')
                ->where('id', $id)
                ->value('id_ext_origen');

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
            ->where('ho.id_ext', $idExtOrigen)
            ->first();
        return $orden;
    }

    public function GetDiagnosticosAdmisionById($id_hospitalizacion)
    {
        $result = DB::table('hospitalizaciones')
            ->join('diagnosticos as d1', 'hospitalizaciones.id_diagnostico_inicial', '=', 'd1.id')
            ->leftJoin('diagnosticos as d2', 'hospitalizaciones.id_dx_2', '=', 'd2.id')
            ->leftJoin('diagnosticos as d3', 'hospitalizaciones.id_dx_3', '=', 'd3.id')
            ->select(
                'd1.codigo as codigo_cie_ingreso_1',
                'd1.nombre as nombre_cie_ingreso_1',
                'd2.codigo as codigo_cie_ingreso_2',
                'd2.nombre as nombre_cie_ingreso_2',
                'd3.codigo as codigo_cie_ingreso_3',
                'd3.nombre as nombre_cie_ingreso_3'
            )
            ->where('hospitalizaciones.id', $id_hospitalizacion)
            ->first();
        return $result;
    }

    public function GetDiagnosticosTraslado($id_prestacion, $id_evolucion)
    {
        $result = DB::table('receta_diagnosticos as rd')
            ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
            ->join('tipo_diagnosticos as td', 'rd.id_tipo_diagnostico', '=', 'td.id')
            ->select(
                'd.codigo',
                'd.nombre',
                'td.descripcion as tipo_diagnostico',
                'rd.id_clasificacion_diagnosticos',
                'rd.id_categoria as id_prioridad',
                'rd.id_cambio_cama'
            )
            ->where('rd.id_prestacion', $id_prestacion)
            ->where('rd.id_evaluacion', $id_evolucion)
            ->whereNotNull('rd.id_cambio_cama')
            ->orderBy('rd.created_at', 'asc')
            ->get();
        return $result;
    }

    public function hospitalazacionValidarAtendida($idOrdenHospitalizacion){
        $orden = DB::table('hospitalizacion_ordenes')
        ->select('id_asegurado', 'id_establecimiento')
        ->where('id',$idOrdenHospitalizacion)
        ->first(); // Obtener solo un registro
    
        // Verificar si se encontró la orden
        if ($orden) {
        // Construir la consulta de hospitalizaciones
        $hospitalizaciones = DB::table('hospitalizaciones')
            ->where('id_asegurado', $orden->id_asegurado);
    
        // Aplicar filtros según id_establecimiento
        if ($orden->id_establecimiento == 76) {
            $hospitalizaciones->whereIn('estado', [1, 3, 4, 5, 6]);
        } else {
            $hospitalizaciones->whereIn('estado', [1, 2, 3, 4, 5, 6]);
        }
    
        // Verificar si existe al menos un registro que cumpla con la condición
        $existe = $hospitalizaciones->get();
    
        if(count($existe) == 0){
          $dataExiste = false;
        }else{
          $dataExiste = true;
        }
        return $dataExiste;
      }else{
        return false;
      }
    }


    public function hospitalazacionValidarAtendidaForDni($dni,$id_establecimiento){
        // Construir la consulta de hospitalizaciones
        $hospitalizaciones = DB::table('hospitalizaciones')
        ->where('dni_beneficiario', $dni);

    // Aplicar filtros según id_establecimiento
    if ($id_establecimiento == 76) {
        $hospitalizaciones->whereIn('estado', [1, 3, 4, 5, 6]);
    } else {
        $hospitalizaciones->whereIn('estado', [1, 2, 3, 4, 5, 6]);
    }

    // Verificar si existe al menos un registro que cumpla con la condición
    $existe = $hospitalizaciones->get();

    if(count($existe) == 0){
      $dataExiste = false;
    }else{
      $dataExiste = true;
    }
    return $dataExiste;
    }
   
    public function getAtencionesHCById($id) {
        $atenciones = DB::table('hospitalizaciones as h')
        ->join('prestaciones as t1', function($join) {
            $join->on('t1.id_ext', '=', 'h.id')
                ->where('t1.id_tipo_atencion', '=', 3)
                ->where('t1.id_estado_reg', '=', 1);
        })
        ->join('medicos as t5', 't1.id_medico', '=', 't5.id')
        ->join('cambio_camas as cc', function($join) {
            $join->on('t1.id', '=', 'cc.id_prestacion')
                ->where('cc.id_evolucion', '=', 0);
        })
        ->join('camas as ca', 'cc.id_cama_atencion', '=', 'ca.id')
        ->join('cuartos as c2', 'ca.id_cuarto', '=', 'c2.id')
        ->join('asignacion_servicios as as2', 'c2.id_asignacion_servicio', '=', 'as2.id')
        ->join('consultorios as c', 'as2.id_consultorio', '=', 'c.id')
        ->join('establecimientos as e', 'h.id_establecimiento', '=', 'e.id')
        ->join('tipo_atenciones as t', 't1.id_tipo_atencion', '=', 't.id')
        ->leftJoin('consultorios as servicio_encargado', 'cc.id_servicio_encargado_atencion', '=', 'servicio_encargado.id')
        ->join('diagnosticos as d1', 'h.id_diagnostico_inicial', '=', 'd1.id')
        ->leftJoin('diagnosticos as d5', 'h.id_dx_2', '=', 'd5.id')
        ->leftJoin('diagnosticos as d6', 'h.id_dx_3', '=', 'd6.id')
        ->leftJoin('diagnosticos as d2', 'h.id_diag_cab', '=', 'd2.id')
        ->leftJoin('diagnosticos as d3', 'h.id_diag_cuer1', '=', 'd3.id')
        ->leftJoin('diagnosticos as d4', 'h.id_diag_cuer2', '=', 'd4.id')
        // ->join('users as u', 'h.id_medico_alta', '=', 'u.id')
        ->leftJoin('users as u', 'h.id_medico_alta', '=', 'u.id')
        ->leftJoin('medicos as m', function($join) {
            $join->on('u.dni', '=', 'm.dni')
                 ->on('u.id_establecimiento', '=', 'm.id_establecimiento');
        })
        ->join('camas as camas_1', 'h.id_cama_estado', '=', 'camas_1.id')
        ->join('cuartos as cuartos_1', 'camas_1.id_cuarto', '=', 'cuartos_1.id')
        ->join('asignacion_servicios as as1', 'cuartos_1.id_asignacion_servicio', '=', 'as1.id')
        ->join('consultorios as consultorios_1', 'as1.id_consultorio', '=', 'consultorios_1.id')
        ->leftJoin('establecimientos as establecimientos_traslado', 'h.id_establecimiento_traslado', '=', 'establecimientos_traslado.id')
        ->leftJoin('camas as camas_2', 'h.id_cama_egreso', '=', 'camas_2.id')
        ->leftJoin('cuartos as cuartos_2', 'camas_2.id_cuarto', '=', 'cuartos_2.id')
        ->leftJoin('asignacion_servicios as as3', 'cuartos_2.id_asignacion_servicio', '=', 'as3.id')
        ->leftJoin('consultorios as consultorios_2', 'as3.id_consultorio', '=', 'consultorios_2.id')
        ->where('h.id', $id)
        ->whereRaw("COALESCE(flag_intervencion_quirurgica, false) IS FALSE")
        ->select(
            'h.*',
            't1.*',
            'd1.codigo as codigo_cie',
            'd1.nombre as nombre_cie',
            'd5.codigo as codigo_cie_ingreso_2',
            'd5.nombre as nombre_cie_ingreso_2',
            'd6.codigo as codigo_cie_ingreso_3',
            'd6.nombre as nombre_cie_ingreso_3',
            'd2.codigo as codigo_cie_egreso_1',
            'd2.nombre as nombre_cie_egreso_1',
            'd3.codigo as codigo_cie_egreso_2',
            'd3.nombre as nombre_cie_egreso_2',
            'd4.codigo as codigo_cie_egreso_3',
            'd4.nombre as nombre_cie_egreso_3',
            'm.paterno as profesional_egreso_paterno',
            'm.materno as profesional_egreso_materno',
            'm.nombre as profesional_egreso_nombre',
            'm.colegiatura as profesional_egreso_colegiatura',
            'camas_1.nombre as cama',
            'consultorios_1.nombre as servicio_sala',
            'establecimientos_traslado.nombre as establecimiento_traslado',
            'camas_2.nombre as cama_egreso',
            'consultorios_2.id as id_servicio_sala_egreso',
            'consultorios_2.nombre as servicio_sala_egreso',
            DB::raw("COALESCE(servicio_encargado.nombre, '') as servicio_encargado_nombre"),
            't1.id as id_prestacion',
            'h.id as id_hospitalizacion',
            't5.nombre as medico_nombre',
            't5.paterno as medico_paterno',
            't5.materno as medico_materno',
            DB::raw("to_char(t1.fecha_atencion, 'HH24:MI AM') as hora"),
            DB::raw("to_char(t1.fecha_atencion, 'DD/MM/YYYY') as dia"),
            't1.id_estado_reg',
            'c2.nombre as nombre_cuarto',
            'ca.nombre as cama',
            'c.nombre as servicio',
            'e.nombre as establecimiento',
            't.nombre as tipo_atencion',
            't1.id_estado_reg',
            'servicio_encargado.nombre as servicio_encargado_atencion'
        )
        ->orderBy('t1.fecha_atencion','desc')
        ->get();
        return $atenciones;
    }
	public function actualizarDatosDomiciliaria($id,$tipo,$condicion,$fecha_alta,$hora_alta,$id_user){
        DB::table('hospitalizaciones')
        ->where('id', $id)
        ->update([
            'id_tipo_egreso' => $tipo,
            'id_condicion_alta' => $condicion,
            'fecha_alta_medica' => $fecha_alta,
            'hora_alta_medica' => $hora_alta,
            'id_medico_alta' =>$id_user
        ]);
    }

    /**
     * Obtener datos de Ingreso de una Hospitalizacion (1 solo registro)
     * Para los diagnosticos obtenerlo de otra funcion
     * 
     * - Adicionar solo datos de ingreso de Hospitalizacion
     */
    public function GetDatosIngreso_ByIdHospitalizacion($id_hospitalizacion)
    {
        $data = DB::table('hospitalizaciones AS h')
                ->select(
                    'h.id',
                    DB::raw("CAST(h.fecha_ingreso AS DATE) AS fecha_ingreso"),
                    'h.hora_ingreso',
                    DB::raw("TO_CHAR(h.fecha_ingreso, 'DD-MM-YYYY') AS fecha_ingreso_string"),
                    DB::raw("TO_CHAR(CAST(h.hora_ingreso AS TIME), 'HH24:MI:SS') AS hora_ingreso_string")
                )
                ->where('h.id', $id_hospitalizacion)
                ->first();
        return $data;
    }
    public function getDatosPapeletaEgresoAtencionQuirurgica($id_hospitalizacion) {

        $data = DB::table('hospitalizaciones as h')
            ->select(
                'h.id_asegurado',
                'h.id_condicion_alta',
                'h.nro_hospitalizacion as nro_hospitalizacion',
                'e.nombre as establecimiento',
                'h.cups_pnp',
                'h.cod_siteds_susalud',
                'h.dni_beneficiario as paciente_ndocumento',
                'h.nro_historia as paciente_nhistoria',
                'h.secuencia',
                DB::raw('to_char(h.fecha_ingreso, \'DD/MM/YYYY\') as fecha_ingreso'),
                'h.hora_ingreso',
                'h.paterno_beneficiario as paciente_paterno',
                'h.materno_beneficiario as paciente_materno',
                'h.nombre_beneficiario as paciente_nombre',
                'h.tipo_beneficiario as paciente_parentesco',
                'h.edad as paciente_edad_ingreso',
                'h.sexo as paciente_sexo',
                DB::raw('to_char(h.created_at, \'DD/MM/YYYY hh:mi:ss\') as created_at'),
                'ue.name as user_nombre_medico_alta',
                'ue.paterno as user_paterno_medico_alta',
                'ue.materno as user_materno_medico_alta',
                'ce.nombre as cama',
                'ce2.nombre as cuarto',
                'pe2.nombre as piso',
                'pe3.nombre as pabellon',
                DB::raw('to_char(h.fecha_alta_medica, \'DD/MM/YYYY\') as fecha_alta_medica'),
                'h.hora_alta_medica',
                DB::raw("CASE 
                            WHEN h.id_tipo_egreso = 1 THEN 'ALTA'
                            WHEN h.id_tipo_egreso = 3 THEN 'TRANSFERIDO'
                            WHEN h.id_tipo_egreso = 4 THEN 'FALLECIDO'
                            WHEN h.id_tipo_egreso = 6 THEN 'FUGA'
                            WHEN h.id_tipo_egreso = 7 THEN 'RETIRO VOLUNTARIO'
                            ELSE '-' 
                        END as tipo_egreso"),
                DB::raw("CASE 
                        WHEN h.id_condicion_alta = 1 THEN 'MEJORADO'
                        WHEN h.id_condicion_alta = 2 THEN 'CURADO'
                        WHEN h.id_condicion_alta = 3 THEN 'TRASLADO'
                        WHEN h.id_condicion_alta = 4 THEN 'RETIRO VOLUNTARIO'
                        WHEN h.id_condicion_alta = 5 THEN 'FUGADO'
                        WHEN h.id_condicion_alta = 6 THEN 'FALLECIDO'
                        ELSE '-' 
                    END as condicion_egreso"),
                'uea.name as user_nombre',
                'uea.paterno as user_paterno',
                'uea.materno as user_materno',
                 DB::raw("regexp_replace(ups.descripcion_upss, '-', ' ', 'g') as upss_acargo"),
                'co.nombre as servicio_acargo' 
            )
            ->join('users as ue', 'h.id_medico_alta', '=', 'ue.id')
            ->leftJoin('upsses as ups', 'h.id_upss_encargado', '=', 'ups.id')
            ->leftJoin('consultorios as co', 'h.id_servicio_encargado', '=', 'co.id')
            ->leftJoin('camas as ce', 'h.id_cama_estado', '=', 'ce.id')
            ->leftJoin('cuartos as ce2', 'ce.id_cuarto', '=', 'ce2.id')
            ->leftJoin('asignacion_servicios as ase2', 'ce2.id_asignacion_servicio', '=', 'ase2.id')
            ->leftJoin('pisos as pe2', 'ase2.id_piso', '=', 'pe2.id')
            ->leftJoin('pabellones as pe3', 'pe2.id_pabellon', '=', 'pe3.id')
            ->leftJoin('users as uea', 'h.id_medico_alta', '=', 'uea.id')
            ->leftJoin('establecimientos as e', 'h.id_establecimiento', '=', 'e.id')
            ->where('h.id', $id_hospitalizacion)
            ->where('h.id_tipo_egreso', 1)//SOLO CUANDO EL DESTINO DEL PACIENTE ES DOMICILIARIA
            ->where(function($query) {
                $query->where('h.estado', 6)
                    ->orWhere('h.estado', 7);
            })
            ->first();
        return $data;
    }
    public function get_reporte_operatorio_cqx($id_hospitalizacion)
	{
		$result = DB::select('SELECT * FROM sp_reporte_operatorio_cqx(?) AS resultado', [$id_hospitalizacion]);
        if (!empty($result) && isset($result[0]->resultado) && !is_null($result[0]->resultado)) {
            $jsonString = $result[0]->resultado;
            $data = json_decode($jsonString, true);
            return $data[0];
        } else {
            return [];
        }
	}

    public function get_reporte_mopri_cqx($id_intervencion)
	{
		$result = DB::select('SELECT * FROM sp_reporte_mopri_cqx(?) AS resultado', [$id_intervencion]);
        if (!empty($result) && isset($result[0]->resultado) && !is_null($result[0]->resultado)) {
            $jsonString = $result[0]->resultado;
            $data = json_decode($jsonString, true);
            return $data[0];
        } else {
            return [];
        }
	}

    public function getHospitalizacionByIdCqx($id_hospitalizacion) {
        $sql = "select
                    hospitalizaciones.id_tipo_egreso,
                    hospitalizaciones.fecha_ingreso,
                    hospitalizaciones.fecha_alta_medica,
                    hospitalizaciones.paterno_beneficiario,
                    hospitalizaciones.materno_beneficiario,
                    hospitalizaciones.nombre_beneficiario,
                    hospitalizaciones.nro_historia,
                    hospitalizaciones.id_servicio_encargado,
                    hospitalizaciones.hora_alta_medica,
                    hospitalizaciones.hora_ingreso,
                    hospitalizaciones.id_condicion_alta,
                    hospitalizaciones.necropsia,
                    hospitalizaciones.egreso_medidas_generales,
                    m.paterno as profesional_egreso_paterno,
                    m.materno as profesional_egreso_materno,
                    m.nombre as profesional_egreso_nombre,
                    m.colegiatura as profesional_egreso_colegiatura,
                    pm.nombre as profesional_egreso_profesion,
                    establecimientos.nombre as establecimiento_traslado,
                    
                    COALESCE(servicio_encargado.nombre, '') as servicio_encargado_nombre
                from
                    hospitalizaciones
                inner join users as u on  hospitalizaciones.id_medico_alta = u.id
                inner join medicos as m on  u.dni = m.dni  and u.id_establecimiento = m.id_establecimiento
                left join profesiones as pm on m.id_profesion = pm.id
                left join establecimientos on hospitalizaciones.id_establecimiento_traslado = establecimientos.id
                left join consultorios as servicio_encargado on hospitalizaciones.id_servicio_encargado = servicio_encargado.id
                where
                    hospitalizaciones.id = $id_hospitalizacion
                limit 1";
        $data = DB::select($sql);
        return $data[0];
    }

    public function getDatosPapeletaEgresoAtencionQuirurgicaNoTratado($id_hospitalizacion) {

        $data = DB::table('hospitalizaciones as h')
            ->select(
                'h.id_asegurado',
                'h.id_condicion_alta',
                'h.nro_hospitalizacion as nro_hospitalizacion',
                'e.nombre as establecimiento',
                'h.cups_pnp',
                'h.cod_siteds_susalud',
                'h.dni_beneficiario as paciente_ndocumento',
                'h.nro_historia as paciente_nhistoria',
                'h.secuencia',
                DB::raw('to_char(h.fecha_ingreso, \'DD/MM/YYYY\') as fecha_ingreso'),
                'h.hora_ingreso',
                'h.paterno_beneficiario as paciente_paterno',
                'h.materno_beneficiario as paciente_materno',
                'h.nombre_beneficiario as paciente_nombre',
                'h.tipo_beneficiario as paciente_parentesco',
                'h.edad as paciente_edad_ingreso',
                'h.sexo as paciente_sexo',
                DB::raw('to_char(h.created_at, \'DD/MM/YYYY hh:mi:ss\') as created_at'),
                'ue.name as user_nombre_medico_alta',
                'ue.paterno as user_paterno_medico_alta',
                'ue.materno as user_materno_medico_alta',
                'ce.nombre as cama',
                'ce2.nombre as cuarto',
                'pe2.nombre as piso',
                'pe3.nombre as pabellon',
                //DB::raw('to_char(h.fecha_alta_medica, \'DD/MM/YYYY\') as fecha_alta_medica'),
                'h.hora_alta_medica',
                DB::raw("CASE 
                            WHEN h.id_tipo_egreso = 1 THEN 'ALTA'
                            WHEN h.id_tipo_egreso = 3 THEN 'TRANSFERIDO'
                            WHEN h.id_tipo_egreso = 4 THEN 'FALLECIDO'
                            WHEN h.id_tipo_egreso = 6 THEN 'FUGA'
                            WHEN h.id_tipo_egreso = 7 THEN 'RETIRO VOLUNTARIO'
                            ELSE '-' 
                        END as tipo_egreso"),
                DB::raw("CASE 
                        WHEN h.id_condicion_alta = 1 THEN 'MEJORADO'
                        WHEN h.id_condicion_alta = 2 THEN 'CURADO'
                        WHEN h.id_condicion_alta = 3 THEN 'TRASLADO'
                        WHEN h.id_condicion_alta = 4 THEN 'RETIRO VOLUNTARIO'
                        WHEN h.id_condicion_alta = 5 THEN 'FUGADO'
                        WHEN h.id_condicion_alta = 6 THEN 'FALLECIDO'
                        ELSE '-' 
                    END as condicion_egreso"),
                'uea.name as user_nombre',
                'uea.paterno as user_paterno',
                'uea.materno as user_materno',
                 DB::raw("regexp_replace(ups.descripcion_upss, '-', ' ', 'g') as upss_acargo"),
                'co.nombre as servicio_acargo'
            )
            ->leftJoin('users as ue', 'h.suspension_user', '=', 'ue.id')
            ->leftJoin('upsses as ups', 'h.id_upss_encargado', '=', 'ups.id')
            ->leftJoin('consultorios as co', 'h.id_servicio_encargado', '=', 'co.id')
            ->leftJoin('camas as ce', 'h.id_cama_estado', '=', 'ce.id')
            ->leftJoin('cuartos as ce2', 'ce.id_cuarto', '=', 'ce2.id')
            ->leftJoin('asignacion_servicios as ase2', 'ce2.id_asignacion_servicio', '=', 'ase2.id')
            ->leftJoin('pisos as pe2', 'ase2.id_piso', '=', 'pe2.id')
            ->leftJoin('pabellones as pe3', 'pe2.id_pabellon', '=', 'pe3.id')
            ->leftJoin('users as uea', 'h.id_medico_alta', '=', 'uea.id')
            ->leftJoin('establecimientos as e', 'h.id_establecimiento', '=', 'e.id')
            ->where('h.id', $id_hospitalizacion)
           // ->where('h.id_tipo_egreso', 1)//SOLO CUANDO EL DESTINO DEL PACIENTE ES DOMICILIARIA
            ->where('h.estado', 0)
            ->first();
        return $data;
    }
}
