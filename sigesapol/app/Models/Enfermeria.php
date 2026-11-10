<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

//use Illuminate\Support\Facades\DB;

class Enfermeria extends Model
{
    protected $table = 'enfermerias';

    public $timestamps = false;
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'id_prestacion',
        'id_tipo_atencion',
        'id_ext_enfermeria',
        'fecha_firma',
        'ruta_firma',
        'flag_firma',
        'img_base',
        'created_us',
        'created_at',
        'updated_us',
        'updated_at',
        'observacion_enfermeria',
        'id_estado_reg',
        'id_establecimiento_atencion',
    ];

    public function detalles()
    {
        // se define una relacion de uno a muchos mediante el id_enfermeria que contiene la tabla enfermeriadetalle
        return $this->hasMany(EnfermeriaDetalle::class, 'id_enfermeria');
    }
   // Relación uno a muchos con SignosVitales
   public function signosVitales()
   {
       return $this->hasMany(SignosVitales::class, 'id_enfermeria');
   }

   public function obtenerNotaEnfermeria() {

    }
    public function emergencia()
    {
        return $this->belongsTo(Emergencia::class, 'id_ext_enfermeria ');
    }

    public function readFunctionPostgres($function, $parameters = null){
	
		$_parameters = '';
		if (count($parameters) > 0) {
			$_parameters = implode("','", $parameters);
			$_parameters = "'" . $_parameters . "',";
		}
	  $data = DB::select("BEGIN;");
	  $cad = "select " . $function . "(" . $_parameters . "'ref_cursor3');";
	  $data = DB::select($cad);
	  $cad = "FETCH ALL IN ref_cursor3;";
	  $data = DB::select($cad);
      $closeCursor = DB::select("CLOSE ref_cursor3;");
		return $data;
	}
       
    public function lista_pcientes_enfermeria($p){
		return $this->readFunctionPostgres('lista_pcientes_enfermeria',$p);
	}
   
    public function ObtenerCabeceraPdfById($enfermeriaId)
    {
        $cad = "SELECT * FROM sp_get_cabecera_enfermeria(?);";
        $data = DB::select($cad, [$enfermeriaId]);
        if (isset($data) && is_array($data) && count($data) > 0) {
            return $data[0];
        }
        return [];
    }

    public function obtenerCitasEnfermeria($p)
    {
        // return $this->readFunctionPostgres('sp_obtener_citas_enfermeria', $p);
        $result = DB::select('SELECT * FROM sp_obtener_citas_enfermeria(?, ?, ?, ?, ?, ?, ?, ?)', $p);
        return $result;
    }

    public function obtenerEnfermeriaConsultaExternaPorId($enfermeriaId)
    {   
        DB::statement("BEGIN;");
        DB::statement("SELECT sp_get_enfermeria_consulta_externa_by_id(:enfermeria_id, 'ref_cursor')", ['enfermeria_id' => $enfermeriaId]);
        $result = DB::select("FETCH ALL IN ref_cursor");
        DB::statement("COMMIT;");
        
        if (isset($result) && is_array($result) && count($result) > 0) {
            return $result[0];
        }
        return null;
    }

    public function ObtenerCabeceraPdfById_sa($enfermeriaId)
    {
        $cad = "SELECT * FROM sp_get_cabecera_enfermeria_sa(?);";
        $data = DB::select($cad, [$enfermeriaId]);
        if (isset($data) && is_array($data) && count($data) > 0) {
            return $data[0];
        }
        return [];
    }
    public function ObtenerCabeceraPdfByIdHospi($enfermeriaId)
    {
        $cad = "SELECT * FROM sp_get_cabecera_hospitalizacion_enfermeria(?);";
        $data = DB::select($cad, [$enfermeriaId]);
        if (isset($data) && is_array($data) && count($data) > 0) {
            return $data[0];
        }
        return [];
    }

    public function getHistorialEnfermeriaByAseguradoId($aseguradoId)
    {
        $cad = "SELECT * FROM sp_get_historial_enfermeria_by_asegurado_id(?);";
        $data = DB::select($cad, [$aseguradoId]);
        if (isset($data) && is_array($data) && count($data) > 0) {
            return $data;
        }
        return [];
    }

    public function getHistorialEnfermeriaByAseguradoId_v2($dni_beneficiario)
    {
        $asegurado = Asegurado::where("nro_doc_ident", $dni_beneficiario)->first();
        $cad = "SELECT * FROM sp_get_historial_enfermeria_by_asegurado_id(?);";
        $data = DB::select($cad, [$asegurado->id]);
        if (isset($data) && is_array($data) && count($data) > 0) {
            return $data;
        }
        return [];
    }

    function getLatestTerapeuticaByIdAsegurado($id_asegurado) {
        $sql = "select
                p.id as prestacion_id,
                p.fecha_atencion::date as prestacion_fecha_atencion,
                p.fecha_atencion::time as prestacion_hora_atencion,
                m.id as medico_id,
                m.dni as medico_dni,
                m.nombre as medico_nombre,
                p.indicacion_terapeutica,
                p.created_at 
            from prestaciones p 
            left join medicos m on p.id_medico = m.id 
            where p.id_asegurado = :id_asegurado and p.fecha_atencion is not null
            UNION ALL
              select
                p.id as prestacion_id,
                p.fecha_atencion::date as prestacion_fecha_atencion,
                p.fecha_atencion::time as prestacion_hora_atencion,
                m.id as medico_id,
                m.dni as medico_dni,
                m.nombre as medico_nombre,
                p.tratamiento_auxiliar as indicacion_terapeutica,
                p.created_at
            from evaluaciones p 
            left join medicos m on p.id_medico = m.id 
            where p.id_asegurado = :id_asegurado and p.fecha_atencion is not null
            order by created_at desc
            LIMIT 1;";
     $result = DB::select($sql, ['id_asegurado' => $id_asegurado]);
     return reset($result) ?: null;
    }
}
