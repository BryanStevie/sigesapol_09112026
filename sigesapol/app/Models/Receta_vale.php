<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Receta_vale extends Model {

    protected $table = 'receta_vales';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id_farmacia',
        'id_user',
        'id_consultorio',
        'nro_receta',
        'dni_beneficiario',
        'nombre_beneficiario',
        'paterno_beneficiario',
        'materno_beneficiario',
        'cip_beneficiario',
        'tps_beneficiario',
        'tipo_beneficiario',
        'fecha_expedicion',
        'fecha_expiracion',
        'fecha_registro',
        'created_at',
        'updated_at',
        'id_diagnostico',
        'nro_historia',
        'grado',
        'id_medico',
        'tipo_receta',
        'turno',
        'dni_titular',
        'estado',
        'nro_rua',
        'fecha_intervencion',
        'total',
        'id_admision',
        'obs_registro',
        'tipodoc_beneficiario',
        'tipodoc_titular',
        'id_anestesiologo',
        'nro_guia',
        'interconsulta',
        'interconsulta_dias',
        'fecha_final_interconsulta',
        'tipo_via',
        'sub_via',
        'final_via',
        'id_asegurado',
        'id_prestacion',
        'id_tipo_num',
        'fecha_firma',
        'ruta_receta',
        'updated_us',
        'codigo_upss',
        'fecha_nacimiento_beneficiario',
        'sexo_beneficiario',
        'flag',
        'id_evaluacion',
        'cod_cpt',
        'codigo_siteds',
        'fecha_firma_rv',
        'ruta_receta_rv',
        'flag_rv',
        'id_interconsulta',
        'fecha_firma_dispensador',
        'ruta_receta_dispensador',
        'flag_dispensador',
        'updated_at_cod_siteds',
        'flag_docsinfirmar',
        'flg_post_quirurgico',
    ];

    public function DatosRecetaById($id) {
        $data = DB::table('receta_vales')
                ->join('medicos', 'receta_vales.id_medico', '=', 'medicos.id')
                ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->leftJoin('diagnosticos', 'receta_vales.id_diagnostico', '=', 'diagnosticos.id')
                ->join('consultorios', 'receta_vales.id_consultorio', '=', 'consultorios.id')
                ->where('receta_vales.id', '=', $id)
                ->select('receta_vales.*', 'diagnosticos.codigo as codigo_diagnostico_principal', 'diagnosticos.nombre as diagnostico_principal', 'consultorios.nombre as consultorio', 'medicos.dni as dni_cirujano_principal', 'especializaciones.nombre as especializacion', 'medicos.nombre as nombre_cirujano_principal', 'medicos.paterno as paterno_cirujano_principal', 'medicos.materno as materno_cirujano_principal', 'farmacias.nombre as nombre_farmacia')
                ->first();
        return $data;
    }

    public function ProductosRecetaById($id) {
        $data = DB::table('receta_vales')
                ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
                ->where('receta_vales.id', '=', $id)
                ->where('producto_recetas.estado','<>',0)
                ->select('productos.id as id_producto','productos.codigo', 'productos.nombre', 'unidades.abreviatura', 'producto_recetas.cantidad_prescrita', 'producto_recetas.cantidad_dispensada', 'producto_recetas.tiempo_periodo', 'producto_recetas.posologia','productos.presentacion','productos.presentacion_comercial','via_administracion.descripcion as via_administracion')
                ->orderBy('producto_recetas.id','asc')
                ->get();
        return $data;
    }

    public function ProductosRecetaByIdV2($id) {
        $datos=DB::table('producto_recetas') 
                ->join('productos','producto_recetas.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->leftJoin('diagnosticos', 'producto_recetas.id_diagnosticos', '=', 'diagnosticos.id')
				->leftJoin('farmacias','producto_recetas.id_farmacia','=','farmacias.id')
                ->leftJoin('via_administracion','producto_recetas.id_via_administracion','=','via_administracion.id')
                ->where('producto_recetas.id_receta_vale','=',$id)
                ->where('producto_recetas.estado','<>',0)
                ->select('producto_recetas.id as id_producto_receta','productos.codigo','productos.nombre','productos.id as id_producto','productos.presentacion_comercial','productos.presentacion_comercial as presentacion', 'producto_recetas.*','unidades.abreviatura','farmacias.nombre as farmacia','via_administracion.descripcion as via', 'diagnosticos.codigo as cod_diagnostico')                
                ->orderBy('productos.nombre','asc')
                ->get();
        return $datos;
    }
    public function ProductosRecetaByIdV3($id) {
        $datos=DB::table('producto_recetas') 
                ->join('productos','producto_recetas.id_producto','=','productos.id')
                ->join('unidades','productos.id_unidad','=','unidades.id')
                ->leftJoin('diagnosticos', 'producto_recetas.id_diagnosticos', '=', 'diagnosticos.id')
				->leftJoin('farmacias','producto_recetas.id_farmacia','=','farmacias.id')
                ->leftJoin('via_administracion','producto_recetas.id_via_administracion','=','via_administracion.id')
                ->where('producto_recetas.id_receta_vale','=',$id)
                ->where('producto_recetas.estado','<>',0)
                ->select('producto_recetas.id as id_producto_receta','productos.codigo','productos.nombre','productos.id as id_producto','productos.presentacion_comercial as presentacion', 'producto_recetas.*','unidades.abreviatura','farmacias.nombre as farmacia','via_administracion.descripcion as via', 'diagnosticos.codigo as cod_diagnostico')                
                ->orderBy('productos.nombre','asc')
                ->get();
        return $datos;
    }

    public function PUdeProductosSegunKardex($nombre_farmacia, $fecha_movimiento) {
        $data = DB::table('kardexes')
                ->where('kardexes.fecha_movimiento', '<=', $fecha_movimiento)
                ->where('kardexes.nombre_farmacia', $nombre_farmacia)
                ->Where(function ($query) {
                    $query->orwhere('kardexes.id_tipo_movimiento', 1)->orwhere('kardexes.id_tipo_movimiento', 2);
                })
                ->select('kardexes.codigo_producto', 'kardexes.nombre_producto', DB::raw('avg(kardexes.precio_unitario) as pu'))
                ->groupBy('kardexes.id_producto', 'kardexes.nombre_producto')
                ->get();
        return $data;
    }

    public function EnfermerPrincipalRecetaById($id) {
        $data = DB::table('medicos')
                ->join('receta_medicos', 'medicos.id', '=', 'receta_medicos.id_medico')
                ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
                ->join('receta_vales', 'receta_medicos.id_receta_vale', '=', 'receta_vales.id')
                ->where('especializaciones.nombre', 'ENFERMERIA')
                ->where('receta_medicos.tipo', '=', 1)
                ->where('receta_vales.id', '=', $id)
                ->select('medicos.*', 'especializaciones.nombre as especializacion')
                ->first();
        //var_dump($data); exit;
        return $data;
    }

    public function CirujanosRecetaById($id) {
        $data = DB::table('medicos')
                ->join('receta_medicos', 'medicos.id', '=', 'receta_medicos.id_medico')
                ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
                ->join('receta_vales', 'receta_medicos.id_receta_vale', '=', 'receta_vales.id')
                ->where('receta_vales.id', '=', $id)
                ->select('medicos.*', 'especializaciones.nombre as especializacion')
                ->get();
        return $data;
    }

    public function ProcedimientosRecetaById($id) {
        $data = DB::table('procedimientos')
                ->join('receta_procedimientos', 'procedimientos.id', '=', 'receta_procedimientos.id_procedimiento')
                ->join('receta_vales', 'receta_procedimientos.id_receta_vale', '=', 'receta_vales.id')
                ->where('receta_vales.id', '=', $id)
                ->select('procedimientos.*')
                ->get();
        return $data;
    }

    public function DiagnosticosRecetaById($id) {
        $data = DB::table('diagnosticos')
                ->join('receta_diagnosticos', 'diagnosticos.id', '=', 'receta_diagnosticos.id_diagnostico')
                ->join('receta_vales', 'receta_diagnosticos.id_receta_vale', '=', 'receta_vales.id')
                ->where('receta_vales.id', '=', $id)
                ->select('diagnosticos.*')
                ->get();
        return $data;
    }

    public function genera_receta_duplicado($nro_receta, $id_farmacia) {
        $cad = "Select nro_receta From receta_vales Where nro_receta like '" . $nro_receta . "%' And id_farmacia=" . $id_farmacia . " Order By id desc limit 1";
        $data = DB::select($cad);
        if (isset($data[0])) {
            return $data[0]->nro_receta;
        } else {
            return "";
        }
    }

    public function exite_historiaclinica($id_farmacia, $nro_documento, $nro_historia) {
        $datos = Receta_vale::where('id_farmacia', '=', $id_farmacia)
                ->where('nro_historia', '=', $nro_historia)
                ->where('dni_beneficiario', '<>', $nro_documento)
                ->where('estado', '=', 1)
                ->count();
        return $datos;
    }

    public function exite_receta($nro_receta, $id_farmacia) {
        $datos = Receta_vale::where('nro_receta', '=', $nro_receta)
                ->where('id_farmacia', '=', $id_farmacia)
                //->where('id_consultorio', '=', $id_consultorio)
                ->where('estado', '=', 1)
                ->count();
        return $datos;
    }

    public function exite_receta_anulada_by_fiscalizador($nro_receta, $id_farmacia, $id_user) {
        $datos = DB::table('receta_vales')
                ->join('receta_devueltas', 'receta_vales.id', '=', 'receta_devueltas.id_receta_vale')
                ->where('receta_vales.nro_receta', '=', $nro_receta)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                //->where('receta_vales.id_consultorio', '=', $id_consultorio) 
                ->where('receta_devueltas.id_user', '=', $id_user)
                ->where('receta_vales.estado', '=', 0)
                ->count();
        return $datos;
    }

    public function GetDatosBasicoReceta($id_farmacia, $nro_receta) {
        $datos = DB::table('receta_vales')
                ->where('id_farmacia', '=', $id_farmacia)
                ->where('nro_receta', '=', $nro_receta)
                ->get();
        //var_dump($datos); exit;
        return $datos;
    }

    public function GetRecetaByIdReceta($id_receta) {
        $datos = Receta_vale::where('id', '=', $id_receta)->first();
        return $datos;
    }

    public function GetRecetaByIdWithJoinUser($id_receta) {
        $productoConCategoria = DB::table('receta_vales')
                                ->join('asegurados', 'receta_vales.dni_beneficiario', '=', 'asegurados.nro_doc_ident')
                                ->where('receta_vales.id', $id_receta)
                                ->select('receta_vales.*', 'asegurados.sexo as asegurado_sexo')
                                ->first();
        return $productoConCategoria;
    }
	
	public function GetRecetaByIdPrestacion($id_prestacion) {
        $datos = Receta_vale::where('id_prestacion', '=', $id_prestacion)->first();
        return $datos;
    }
	
	public function GetRecetasByIdPrestacion($id_prestacion) {
        $datos = Receta_vale::where('id_prestacion', '=', $id_prestacion)->where('estado', '<>', 0)->get();
        return $datos;
    }

    public function GetRecetasByIdEvaluacion($id_prestacion, $id_evaluacion) {
        $datos = Receta_vale::where('id_prestacion', '=', $id_prestacion)->where('id_evaluacion', '=', $id_evaluacion)->where('estado', '<>', 0)->get();
        return $datos;
    }

    public function GetRecetasByDni($dni_beneficiario)
    {
        $data = DB::table('producto_recetas as pr')
            ->join('productos as p', 'p.id', '=', 'pr.id_producto')
            ->join('receta_vales as rv', 'rv.id', '=', 'pr.id_receta_vale')
            ->join('tipo_productos as tp', 'p.id_tipo', '=', 'tp.id')
            ->select(
                'p.codigo as cod_producto',
                'p.nombre as descripcion',
                'rv.fecha_registro as fecha_prescripcion',
                'rv.fecha_expedicion as fecha_dispensacion'
            )
            ->where('rv.estado', 1)
            ->where('rv.dni_beneficiario', $dni_beneficiario)
            ->where('p.id_tipo', 10)
            ->get();
        return $data;
    }
	
	public function GetDatosRecetasByIdPrestacion($id_prestacion) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->select('receta_vales.*', 'farmacias.id as id_farmacia','farmacias.nombre as farmacia')
                ->where('receta_vales.id_prestacion', '=', $id_prestacion)
                ->where('receta_vales.estado', '<>', 0)
                ->where('receta_vales.id_evaluacion', '=', 0)
                ->where('receta_vales.id_interconsulta', '=', 0)
                ->orderBy('receta_vales.id', 'asc')
                ->get();
        return $datos;
    }

    public function GetDatosRecetasAltasByIdPrestacion($id_prestacion){
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->select('receta_vales.*', 'farmacias.id as id_farmacia','farmacias.nombre as farmacia')
                ->where('receta_vales.id_prestacion', '=', $id_prestacion)
                ->where('receta_vales.estado', '<>', 0)
                ->where('receta_vales.id_evaluacion', '=', 0)
                ->where('receta_vales.id_interconsulta', '=', 0)
                ->where('receta_vales.flag_origen', '=', 1)
                ->orderBy('receta_vales.id', 'asc')
                ->get();
        return $datos;
    }

    public function ShowDiagnosticosByFarma_old($fecha_inicio, $fecha_fin, $id_farmacia) {
        $datos = DB::table('receta_vales')
                ->leftJoin('diagnosticos', 'receta_vales.id_diagnostico', '=', 'diagnosticos.id')
                ->select('diagnosticos.codigo', 'diagnosticos.nombre', DB::raw('count(*) as contador'))
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '>=', $fecha_inicio)
                ->where('receta_vales.fecha_registro', '<=', $fecha_fin)
                ->where('receta_vales.estado', '=', 1)
                ->groupBy('diagnosticos.codigo', 'diagnosticos.nombre')
                ->orderBy('contador', 'desc')
                ->get();
        //var_dump($datos); exit;
        return $datos;
    }

    public function ShowDiagnosticosByFarma($fecha_inicio, $fecha_fin, $id_farmacia) {
        
        $cad= "select diagnosticos.codigo,diagnosticos.nombre, count(*) as contador
from receta_vales
inner join receta_diagnosticos  on receta_vales.id=receta_diagnosticos.id_receta_vale 
inner join diagnosticos on receta_diagnosticos.id_diagnostico=diagnosticos.id
where receta_vales.id_farmacia =". $id_farmacia ." and receta_vales.fecha_registro >= '" . $fecha_inicio . "' and receta_vales.fecha_registro <= '" . $fecha_fin . "' and receta_vales.estado = 1
group by diagnosticos.codigo,diagnosticos.nombre
order by contador desc";
        //var_dump($datos); exit;
        $data = DB::select($cad);
        //var_dump($data); exit;
        return $data;        
        //return $datos;
    }

    public function obternerDiagnosticosPorMeses($fecha_inicio, $fecha_fin, $id_farmacia) {
        $data = DB::table('receta_vales')
                ->Join('receta_diagnosticos', 'receta_vales.id', '=', 'receta_diagnosticos.id_receta_vale')
                ->Join('diagnosticos', 'receta_vales.id_diagnostico', '=', 'diagnosticos.id')
                ->select('diagnosticos.codigo', 'diagnosticos.nombre', DB::raw('count(*) as total'), DB::raw("extract(year from receta_vales.fecha_registro) as ano"), DB::raw("TO_CHAR(receta_vales.fecha_registro,'MM')as mes"), DB::raw("TO_CHAR(receta_vales.fecha_registro,'MM') ||'_'|| TO_CHAR(receta_vales.fecha_registro,'YYYY') as fecha_anio"))
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '>=', $fecha_inicio)
                ->where('receta_vales.fecha_registro', '<=', $fecha_fin)
                ->where('receta_vales.estado', '=', 1)
                ->groupBy('diagnosticos.codigo', 'diagnosticos.nombre', DB::raw("extract(year from receta_vales.fecha_registro), TO_CHAR(receta_vales.fecha_registro,'MM'),TO_CHAR(receta_vales.fecha_registro,'MM') ||'_'|| TO_CHAR(receta_vales.fecha_registro,'YYYY')"))
                ->orderBy(DB::raw("extract(year from receta_vales.fecha_registro), TO_CHAR(receta_vales.fecha_registro,'MM')"))
                ->get();
        //var_dump($data);
        $mes_anio = [];
        return $data;
    }
    public function obternerDiagnosticosPorMeses_old($fecha_inicio, $fecha_fin, $id_farmacia) {
        $data = DB::table('receta_vales')
                ->leftJoin('diagnosticos', 'receta_vales.id_diagnostico', '=', 'diagnosticos.id')
                ->select('diagnosticos.codigo', 'diagnosticos.nombre', DB::raw('count(*) as total'), DB::raw("extract(year from receta_vales.fecha_registro) as ano"), DB::raw("TO_CHAR(receta_vales.fecha_registro,'MM')as mes"), DB::raw("TO_CHAR(receta_vales.fecha_registro,'MM') ||'_'|| TO_CHAR(receta_vales.fecha_registro,'YYYY') as fecha_anio"))
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '>=', $fecha_inicio)
                ->where('receta_vales.fecha_registro', '<=', $fecha_fin)
                ->where('receta_vales.estado', '=', 1)
                ->groupBy('diagnosticos.codigo', 'diagnosticos.nombre', DB::raw("extract(year from receta_vales.fecha_registro), TO_CHAR(receta_vales.fecha_registro,'MM'),TO_CHAR(receta_vales.fecha_registro,'MM') ||'_'|| TO_CHAR(receta_vales.fecha_registro,'YYYY')"))
                ->orderBy(DB::raw("extract(year from receta_vales.fecha_registro), TO_CHAR(receta_vales.fecha_registro,'MM')"))
                ->get();
        //var_dump($data);
        $mes_anio = [];
        return $data;
    }

    public function ShowMedicosByFarma($fecha_inicio, $fecha_fin, $id_farmacia) {
        $datos = DB::table('receta_vales')
                ->join('medicos', 'receta_vales.id_medico', '=', 'medicos.id')
                ->join('consultorios', 'receta_vales.id_consultorio', '=', 'consultorios.id')
                ->select('medicos.*', 'consultorios.nombre as consultorio', DB::raw('count(*) as contador'))
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '>=', $fecha_inicio)
                ->where('receta_vales.fecha_registro', '<=', $fecha_fin)
                ->groupBy('medicos.id', 'consultorios.nombre')
                ->orderBy('contador', 'desc')
                ->get();
        //var_dump($datos); exit;
        return $datos;
    }

    public function ShowConsultoriosByFarma($fecha_inicio, $fecha_fin, $id_farmacia) {
        $datos = DB::table('receta_vales')
                ->join('consultorios', 'receta_vales.id_consultorio', '=', 'consultorios.id')
                ->select('consultorios.*', DB::raw('count(*) as contador'))
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '>=', $fecha_inicio)
                ->where('receta_vales.fecha_registro', '<=', $fecha_fin)
                ->where('receta_vales.estado', '>',0)
                ->groupBy('consultorios.id')
                ->orderBy('contador', 'desc')
                ->get();
        
        return $datos;
    }

    public function ShowDataBeneficiariosByFarma($fecha_inicio, $fecha_fin, $id_farmacia, $beneficiario) {
        $datos = DB::table('receta_vales')
                ->select(DB::raw('count(*) as contador'))
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)                
                ->where('receta_vales.fecha_expedicion', '>=', $fecha_inicio)
                ->where('receta_vales.fecha_expedicion', '<=', $fecha_fin)
                ->where('receta_vales.tipo_beneficiario', '=', $beneficiario)
                ->where('receta_vales.estado', '=',1)                
                ->get();
        return $datos;
    }

    public function ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, $beneficiario, $tipo_receta) {
        $datos = DB::table('receta_vales')               
                ->select(DB::raw('count(*) as contador')) 
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)                
                ->where('receta_vales.fecha_expedicion', '>=', $fecha_inicio)
                ->where('receta_vales.fecha_expedicion', '<=', $fecha_fin)
                ->where('receta_vales.tipo_beneficiario', '=', $beneficiario)
                ->where('receta_vales.tipo_receta', '=', $tipo_receta)
                ->where('receta_vales.estado', '=',1)             
                ->get();
        return $datos;
    }

    public function GetTotalDemanadaNoSatisfechaByFarma($fecha_inicio, $fecha_fin, $id_farmacia) {
        $datos = DB::table('producto_recetas')
                ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->join('receta_vales', 'producto_recetas.id_receta_vale', '=', 'receta_vales.id')
                ->select(DB::raw('sum(producto_recetas.saldo) as saldo'))
                ->where('producto_recetas.saldo', '>', 0)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '>=', $fecha_inicio)
                ->where('receta_vales.fecha_registro', '<=', $fecha_fin)
                ->where('receta_vales.estado', '>=',1)
                ->get();
        //var_dump($datos); exit;
        return $datos;
    }

    public function GetTotalProductosAtendidos($fecha_inicio, $fecha_fin, $id_farmacia) {
        $datos = DB::table('producto_recetas')
                ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->join('receta_vales', 'producto_recetas.id_receta_vale', '=', 'receta_vales.id')
                ->select(DB::raw('sum(producto_recetas.cantidad_dispensada) as saldo'))
                ->where('producto_recetas.saldo', '=', 0)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '>=', $fecha_inicio)
                ->where('receta_vales.fecha_registro', '<=', $fecha_fin)
                ->get();
        //var_dump($datos); exit;
        return $datos;
    }

    public function GetTotalProductosAtendidosAlterno($fecha_inicio, $fecha_fin, $nombre_farmacia) {
        $datos = DB::table('kardexes')
                ->select(DB::raw("sum(kardexes.cantidad) as saldo"))
                ->where('kardexes.tipo_movimiento', 'RC')
                ->where('kardexes.cantidad', '>', 0)
                ->where('kardexes.numero_movimiento', 'not like', "%i%")
                ->where('kardexes.fecha_movimiento', '>=', $fecha_inicio)
                ->where('kardexes.fecha_movimiento', '<=', $fecha_fin)
                ->where('kardexes.nombre_farmacia', $nombre_farmacia)
                ->get();
        return $datos;
    }

    public function ShowDemanadaNoSatisfechaByFarma($fecha_inicio, $fecha_fin, $id_farmacia) {
        $datos = DB::table('producto_recetas')
                ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->join('receta_vales', 'producto_recetas.id_receta_vale', '=', 'receta_vales.id')
                ->select('producto_recetas.id_producto', 'productos.codigo', 'productos.nombre', 'unidades.nombre as unidad', DB::raw('sum(producto_recetas.saldo) as saldo'))
                ->where('producto_recetas.saldo', '>', 0)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '>=', $fecha_inicio)
                ->where('receta_vales.fecha_registro', '<=', $fecha_fin)
                ->where('receta_vales.estado', '>=',1)
                ->groupBy('producto_recetas.id_producto', 'productos.codigo', 'productos.nombre', 'unidades.nombre')
                ->orderBy('saldo', 'desc')
                ->get();
        //var_dump($datos); exit;
        return $datos;
    }

    public function obternerProductosNoAtendidos($fecha_inicio, $fecha_fin, $id_farmacia) {
        $data = DB::table('producto_recetas')
                ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->join('receta_vales', 'producto_recetas.id_receta_vale', '=', 'receta_vales.id')
                ->select('producto_recetas.id_producto', 'productos.codigo as codigo_producto', 'productos.nombre as nombre_producto', 'unidades.nombre as unidad', DB::raw('sum(producto_recetas.saldo) as total'), DB::raw("extract(year from receta_vales.fecha_registro) as ano"), DB::raw("TO_CHAR(receta_vales.fecha_registro,'MM')as mes"), DB::raw("TO_CHAR(receta_vales.fecha_registro,'MM') ||'_'|| TO_CHAR(receta_vales.fecha_registro,'YYYY') as fecha_anio"))
                ->where('producto_recetas.saldo', '>', 0)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '>=', $fecha_inicio)
                ->where('receta_vales.fecha_registro', '<=', $fecha_fin)
                ->where('receta_vales.estado', '=', 1)
                ->groupBy('producto_recetas.id_producto', 'codigo_producto', 'unidades.nombre', 'nombre_producto', DB::raw("extract(year from receta_vales.fecha_registro), TO_CHAR(receta_vales.fecha_registro,'MM'),TO_CHAR(receta_vales.fecha_registro,'MM') ||'_'|| TO_CHAR(receta_vales.fecha_registro,'YYYY')"))
                ->orderBy(DB::raw("extract(year from receta_vales.fecha_registro), TO_CHAR(receta_vales.fecha_registro,'MM')"))
                ->get();
        //var_dump($data);
        $mes_anio = [];
        return $data;
    }

    public function ShowProductosAtendidos($fecha_inicio, $fecha_fin, $id_farmacia) {
        $datos = DB::table('producto_recetas')
                ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->join('receta_vales', 'producto_recetas.id_receta_vale', '=', 'receta_vales.id')
                ->select('producto_recetas.id_producto', 'productos.codigo', 'productos.nombre', 'unidades.nombre as unidad', DB::raw('sum(producto_recetas.cantidad_dispensada) as saldo'))
                ->where('producto_recetas.saldo', '=', 0)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '>=', $fecha_inicio)
                ->where('receta_vales.fecha_registro', '<=', $fecha_fin)
                ->where('receta_vales.estado', '=', 1)
                ->groupBy('producto_recetas.id_producto', 'productos.codigo', 'productos.nombre', 'unidades.nombre')
                ->orderBy('saldo', 'desc')
                ->get();
        //var_dump($datos); exit;
        return $datos;
    }

    public function ShowProductosAtendidosAlterno($fecha_inicio, $fecha_fin, $nombre_farmacia) {
        $datos = DB::table('kardexes')
                ->join('productos', 'kardexes.id_producto', '=', 'productos.id')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->join('kardex_lotes', 'kardex_lotes.id_kardex', '=', 'kardexes.id')
                ->select('productos.codigo as codigo', 'productos.nombre as nombre', DB::raw("sum(kardex_lotes.cantidad*kardex_lotes.presentacion_comercial) as saldo"), 'unidades.nombre as unidad','kardexes.id_producto as id_producto')
                ->where('kardexes.tipo_movimiento', 'RC')
				->where('kardexes.numero_movimiento', 'not like', "%i%")
                ->where('kardexes.cantidad', '>', 0)
                ->where('kardexes.fecha_movimiento', '>=', $fecha_inicio)
                ->where('kardexes.fecha_movimiento', '<=', $fecha_fin)
                ->where('kardexes.nombre_farmacia', $nombre_farmacia)
                ->groupBy('productos.codigo', 'productos.nombre', 'unidades.nombre','kardexes.id_producto')
                ->orderBy('saldo', 'desc')
                ->get();
        return $datos;
    }

    public function obtener_meses($fecha_inicio, $fecha_fin, $id_farmacia) {
        $datos = DB::table('receta_vales')
                ->select(DB::raw("TO_CHAR(receta_vales.fecha_registro,'MM')as mes"))
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '>=', $fecha_inicio)
                ->where('receta_vales.fecha_registro', '<=', $fecha_fin)
                ->get();
        return $datos;
    }

    public function obtener_mes() {
        $meses = [];
        $meses['01'] = 'ENE';
        $meses['02'] = 'FEB';
        $meses['03'] = 'MAR';
        $meses['04'] = 'ABR';
        $meses['05'] = 'MAY';
        $meses['06'] = 'JUN';
        $meses['07'] = 'JUL';
        $meses['08'] = 'AGO';
        $meses['09'] = 'SET';
        $meses['10'] = 'OCT';
        $meses['11'] = 'NOV';
        $meses['12'] = 'DIC';
        return $meses;
    }

    public function obternerProductos($fecha_inicio, $fecha_fin, $nombre_farmacia) {
        $datos = DB::table('kardexes')
                ->join('receta_vales', 'receta_vales.id', '=', 'kardexes.id_nro_movimiento')
                ->select('codigo_producto', 'nombre_producto', DB::raw("extract(year from fecha_movimiento) as ano"), DB::raw("TO_CHAR(fecha_movimiento,'MM')as mes"), DB::raw("sum(cantidad) as total"), DB::raw("TO_CHAR(fecha_movimiento,'MM') ||'_'|| TO_CHAR(fecha_movimiento,'YYYY') as fecha_anio"))
                ->where('tipo_movimiento', 'RC')
                ->where('cantidad', '>', 0)
                ->where('fecha_movimiento', '>=', $fecha_inicio)
                ->where('fecha_movimiento', '<=', $fecha_fin)
                ->where('nombre_farmacia', $nombre_farmacia)
                ->where('receta_vales.estado', 1)
                ->groupBy('id_producto','codigo_producto', 'nombre_producto', DB::raw("extract(year from fecha_movimiento), TO_CHAR(fecha_movimiento,'MM'),TO_CHAR(fecha_movimiento,'MM') ||'_'|| TO_CHAR(fecha_movimiento,'YYYY')"))
                ->orderBy(DB::raw("extract(year from fecha_movimiento), TO_CHAR(fecha_movimiento,'MM')"))
                ->get();
        //var_dump($datos); exit;
        $mes_anio = [];
        return $datos;
    }

    public function obternerStockActual($id_farmacia) {
        $data = DB::table('stock_farmacias')
                        ->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                        ->where('stock_farmacias.id_farmacia', '=', $id_farmacia)
                        ->select('productos.codigo', 'productos.nombre', 'stock_farmacias.cantidad')->get();
        return $data;
    }

    public function obtenerDatosProductos() {
        $data = Producto::all();
        return $data;
    }

    public function GetCountRecetasByBeneficiario2($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia,$tipo_receta) {
        if ($tipo_afiliado == "T") {
            $datos = Receta_vale::where('tipo_beneficiario', '=', 'TITULAR')
                            ->where('id_farmacia', '=', $id_farmacia)
                            ->where('tipo_receta', '=', $tipo_receta)
                            ->whereBetween('fecha_expedicion', [$fecha_inicio, $fecha_fin])
                            ->where('estado', '=', 1)->count();
        } else {
            $datos = Receta_vale::where('tipo_beneficiario', '!=', 'TITULAR')
                            ->where('id_farmacia', '=', $id_farmacia)
                            ->where('tipo_receta', '=', $tipo_receta)
                            ->whereBetween('fecha_expedicion', [$fecha_inicio, $fecha_fin])
                            ->where('estado', '=', 1)->count();            
        }
        
        //dd($datos);
        return $datos;
    }

    public function GetCountItemPrescritoOnRecetas2($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia,$tipo_receta) {
        if ($tipo_afiliado == "T") {
            $datos = DB::table('receta_vales')
                            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                            ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                            ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                            ->where('tipo_receta', '=', $tipo_receta)
                            ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                            ->where('receta_vales.estado', '=', 1)->count();
        } else {
            $datos = DB::table('receta_vales')
                            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                            ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                            ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                            ->where('tipo_receta', '=', $tipo_receta)
                            ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                            ->where('receta_vales.estado', '=', 1)->count();
        }
        return $datos;
    }

    public function GetCountItemPrescritoOnRecetasTipo($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia,$tipo_receta,$id_tipo) {
        if ($tipo_afiliado == "T") {
            if($id_tipo==1){
                $datos = DB::table('receta_vales')
                            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                            ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                            ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                            ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                            ->where('tipo_receta', '=', $tipo_receta)
                            ->where('productos.id_tipo', '=', 1)
                            ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                            ->where('receta_vales.estado', '=', 1)->count();    
            }
            else
            {
                $datos = DB::table('receta_vales')
                            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                            ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                            ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                            ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                            ->where('tipo_receta', '=', $tipo_receta)
                            ->where('productos.id_tipo', '>', 1)
                            ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                            ->where('receta_vales.estado', '=', 1)->count();
            }
            
        } else {
            if($id_tipo==1){
                $datos = DB::table('receta_vales')
                            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                            ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                            ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                            ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                            ->where('tipo_receta', '=', $tipo_receta)
                            ->where('productos.id_tipo', '=', 1)
                            ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                            ->where('receta_vales.estado', '=', 1)->count();
            }
            else
            {
                $datos = DB::table('receta_vales')
                            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                            ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                            ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                            ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                            ->where('tipo_receta', '=', $tipo_receta)
                            ->where('productos.id_tipo', '>', 1)
                            ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                            ->where('receta_vales.estado', '=', 1)->count();
            }
        }
        return $datos;
    }

    public function GetCountItemAtendidoOnRecetas2($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia, $tipo_receta) {
        if ($tipo_afiliado == "T") {
            $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->where('producto_recetas.cantidad_dispensada', '>', 0)
                    ->count();
        } else {
            $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->where('producto_recetas.cantidad_dispensada', '>', 0)
                    ->count();
        }
        return $datos;
    }

    public function GetCountItemAtendidoOnRecetasTipo($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia, $tipo_receta, $id_tipo) {
        if ($tipo_afiliado == "T") {
            if($id_tipo==1){                
                $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                    ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->where('productos.id_tipo', '=', 1)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->where('producto_recetas.cantidad_dispensada', '>', 0)
                    ->count();
            }
            else
            {
                $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                    ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->where('productos.id_tipo', '>', 1)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->where('producto_recetas.cantidad_dispensada', '>', 0)
                    ->count();   
            }
        } else {
            if($id_tipo==1){                
                $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                    ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->where('productos.id_tipo', '=', 1)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->where('producto_recetas.cantidad_dispensada', '>', 0)
                    ->count();
            }
            else
            {
                $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                    ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->where('productos.id_tipo', '>', 1)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->where('producto_recetas.cantidad_dispensada', '>', 0)
                    ->count();
            }
        }
        return $datos;
    }

    public function GetCountUnidadPrescritaOnRecetas2($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia, $tipo_receta) {
        if ($tipo_afiliado == "T") {
            $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_prescrita');
        } else {
            $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_prescrita');
        }
        return $datos;
    }

    public function GetCountUnidadPrescritaOnRecetasTipo($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia, $tipo_receta, $id_tipo) {
        if ($tipo_afiliado == "T") {
            if($id_tipo==1){                
                $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                    ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->where('productos.id_tipo', '=', 1)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_prescrita');
            }
            else
            {
                $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                    ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->where('productos.id_tipo', '>', 1)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_prescrita');
            }
        } else {
            if($id_tipo==1){                
                $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                    ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->where('productos.id_tipo', '=', 1)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_prescrita');
            }else
            {
                $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                    ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->where('productos.id_tipo', '>', 1)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_prescrita');
            }
              
        }
        return $datos;
            
    }

    public function GetCountUnidadAtendidaOnRecetas2($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia, $tipo_receta) {
        if ($tipo_afiliado == "T") {
            $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_dispensada');
        } else {
            $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_dispensada');
        }
        return $datos;
    }

    public function GetCountUnidadAtendidaOnRecetasTipo($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia, $tipo_receta, $id_tipo) {
        if ($tipo_afiliado == "T") {
            if($id_tipo==1){                
                $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                    ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->where('productos.id_tipo', '=', 1)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_dispensada');
            }
            else
            {
                $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                    ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->where('tipo_receta', '=', $tipo_receta)
                    ->where('productos.id_tipo', '>', 1)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_dispensada');
            }
        } else {
            if($id_tipo==1){      
                $datos = DB::table('receta_vales')
                        ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                        ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                        ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                        ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                        ->where('tipo_receta', '=', $tipo_receta)
                        ->where('productos.id_tipo', '=', 1)
                        ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                        ->where('receta_vales.estado', '=', 1)
                        ->sum('producto_recetas.cantidad_dispensada');
            }
            else
            {
                $datos = DB::table('receta_vales')
                        ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                        ->join('productos', 'productos.id', '=', 'producto_recetas.id_producto')
                        ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                        ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                        ->where('tipo_receta', '=', $tipo_receta)
                        ->where('productos.id_tipo', '>', 1)
                        ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                        ->where('receta_vales.estado', '=', 1)
                        ->sum('producto_recetas.cantidad_dispensada');
            }
        }
        return $datos;
    }

    public function GetCountRecetasByBeneficiario($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia) {
        if ($tipo_afiliado == "T") {
            $datos = Receta_vale::where('tipo_beneficiario', '=', 'TITULAR')
                            ->where('id_farmacia', '=', $id_farmacia)
                            ->whereBetween('fecha_expedicion', [$fecha_inicio, $fecha_fin])
                            ->where('estado', '=', 1)->count();
        } else {
            $datos = Receta_vale::where('tipo_beneficiario', '!=', 'TITULAR')
                            ->where('id_farmacia', '=', $id_farmacia)
                            ->whereBetween('fecha_expedicion', [$fecha_inicio, $fecha_fin])
                            ->where('estado', '=', 1)->count();            
        }
        return $datos;
    }

    public function GetCountItemPrescritoOnRecetas($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia) {
        if ($tipo_afiliado == "T") {
            $datos = DB::table('receta_vales')
                            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                            ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                            ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                            ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                            ->where('receta_vales.estado', '=', 1)->count();
        } else {
            $datos = DB::table('receta_vales')
                            ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                            ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                            ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                            ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                            ->where('receta_vales.estado', '=', 1)->count();
        }
        return $datos;
    }

    public function GetCountItemAtendidoOnRecetas($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia){
        if ($tipo_afiliado == "T") {
            $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->where('producto_recetas.cantidad_dispensada', '>', 0)
                    ->count();
        } else {
            $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->where('producto_recetas.cantidad_dispensada', '>', 0)
                    ->count();
        }
        return $datos;
    }

    public function GetCountUnidadPrescritaOnRecetas($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia) {
        if ($tipo_afiliado == "T") {
            $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_prescrita');
        } else {
            $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_prescrita');
        }
        return $datos;
    }

    public function GetCountUnidadAtendidaOnRecetas($fecha_inicio, $fecha_fin, $tipo_afiliado, $id_farmacia) {
        if ($tipo_afiliado == "T") {
            $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->where('receta_vales.tipo_beneficiario', '=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_dispensada');
        } else {
            $datos = DB::table('receta_vales')
                    ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                    ->where('receta_vales.tipo_beneficiario', '!=', 'TITULAR')
                    ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                    ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                    ->where('receta_vales.estado', '=', 1)
                    ->sum('producto_recetas.cantidad_dispensada');
        }
        return $datos;
    }


    public function GetCountRecetasByConsultorios($fecha_registro, $id_farmacia) {
        $datos = DB::table('receta_vales')
                ->join('consultorios', 'receta_vales.id_consultorio', '=', 'consultorios.id')
                ->select('consultorios.nombre', DB::raw("count(*) as contador"))
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_expedicion', '=', $fecha_registro)
                ->where('receta_vales.estado', '=', 1)
                ->groupBy('consultorios.nombre')
                ->get();
        return $datos;
    }
    public function GetCountRecetasByConsultoriosAndFecha($id_farmacia, $fecha_inicio, $fecha_fin) {
        $datos = DB::table('receta_vales')
                ->join('consultorios', 'receta_vales.id_consultorio', '=', 'consultorios.id')
                ->select('consultorios.nombre', DB::raw("count(*) as contador"))
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                //->where('receta_vales.fecha_expedicion', '=', $fecha_registro)
                ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                ->where('receta_vales.estado', '=', 1)
                ->groupBy('consultorios.nombre')
                ->get();

        return $datos;
    }
    public function GetCountRecetasByConsultoriosAndFechaTipo($id_farmacia, $fecha_inicio, $fecha_fin) {
        $datos = DB::table('receta_vales')
                ->join('consultorios', 'receta_vales.id_consultorio', '=', 'consultorios.id')
                ->select('consultorios.nombre','receta_vales.tipo_receta', DB::raw("count(*) as contador"))
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                //->where('receta_vales.fecha_expedicion', '=', $fecha_registro)
                ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                ->where('receta_vales.estado', '=', 1)
                ->groupBy('consultorios.nombre','receta_vales.tipo_receta')
                ->orderBy('consultorios.nombre','ASC')
                ->orderBy('receta_vales.tipo_receta','ASC')
                ->get();
        
        return $datos;
    }

    public function GetRolesByIdUser($id_user) {
        $datos = DB::table('assigned_roles')
                ->join('users', 'assigned_roles.user_id', '=', 'users.id')
                ->join('roles', 'assigned_roles.role_id', '=', 'roles.id')
                ->where('users.id', '=', $id_user)
                ->select('roles.id')
                ->orderBy('roles.id', 'asc')
                ->get();
        return $datos;
    }

    public function AllRecetasByFarmacia($id_farmacia) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->join('users', 'receta_vales.id_user', '=', 'users.id')
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.estado', '=', 1)
                ->select('receta_vales.*', 'users.name', 'users.email')
                ->orderBy('receta_vales.id', 'desc')
                ->get();
        return $datos;
    }

    public function CountAllRecetasByFarmacia($id_farmacia) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.estado', '=', 1)
                ->count();
        return $datos;
    }

    public function AllRecetasByFarmaciaAndFecha($id_farmacia, $fecha) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->join('users', 'receta_vales.id_user', '=', 'users.id')
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '=', $fecha)
                ->select('receta_vales.*', 'users.name', 'users.email')
                ->orderBy('receta_vales.updated_at', 'desc')
                ->get();
        return $datos;
    }

    public function CountAllRecetasByFarmaciaAndFecha($id_farmacia, $fecha_inicio ,$fecha_fin) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                //->where('receta_vales.fecha_expedicion', '=', $fecha)
                ->whereBetween('receta_vales.fecha_expedicion', [$fecha_inicio, $fecha_fin])
                ->where('receta_vales.estado', '=', 1)
                ->count();
        return $datos;
    }

    public function ValidarReceta($nro_receta, $id_farmacia) {
        $datos = DB::table('receta_vales')
                ->where('receta_vales.nro_receta', '=', $nro_receta)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.estado', '=', 1)
                ->count();
        return $datos;
    }

    public function ShowAllById($id) {
        $datos = DB::table('receta_vales as rv')
                ->join('farmacias as f', 'rv.id_farmacia', '=', 'f.id')
                ->join('consultorios as c', 'rv.id_consultorio', '=', 'c.id')
                ->join('medicos as m', 'rv.id_medico', '=', 'm.id')
                ->leftJoin('especializaciones as es', 'm.id_especializacion', '=', 'es.id')
                ->leftJoin('diagnosticos as d', 'rv.id_diagnostico', '=', 'd.id')
                ->join('establecimientos as e', 'f.id_establecimiento', '=', 'e.id')
				->leftJoin('prestaciones as p', 'rv.id_prestacion', '=', 'p.id')
               // ->leftJoin('asegurados as a1', 'rv.dni_beneficiario', '=', 'a1.nro_doc_ident')
               ->leftJoin('asegurados as a1', function($join) {
                        $join->on('rv.dni_beneficiario', '=', 'a1.nro_doc_ident')
                            ->on('rv.tipodoc_beneficiario', '=', 'a1.tipo_doc_ident');
                    })
                ->leftJoin('asegurado_historias as ah', function($join)
                {
                    $join->on('ah.id_asegurado', '=', 'a1.id')
                    ->on('ah.id_establecimiento', '=', 'e.id');
                })
                ->leftJoin('asegurados as a2', 'a1.id_asegurado_titular', '=', 'a2.id')
                ->join('users as u', 'rv.id_user', '=', 'u.id')
                ->where('rv.id', '=', $id)
                ->select('rv.id','rv.id_farmacia','rv.id_user','rv.id_consultorio','rv.nro_receta',
                'a1.nro_doc_ident as dni_beneficiario','a1.nombre as nombre_beneficiario','a1.paterno as paterno_beneficiario','a1.materno as materno_beneficiario','a1.cip as cip_beneficiario',
                'rv.tipo_beneficiario','rv.fecha_expedicion','rv.fecha_expiracion','rv.fecha_registro','rv.created_at','rv.updated_at',
                'ah.nro_historia as nro_historia','a1.grado','rv.id_medico','rv.tipo_receta','rv.turno','a2.nro_doc_ident as dni_titular',
                'rv.estado','rv.tipodoc_beneficiario','rv.tipodoc_titular','rv.nro_guia','rv.interconsulta','rv.interconsulta_dias','rv.tipo_via','rv.sub_via',
                'rv.final_via','a1.id as id_asegurado','rv.id_prestacion','rv.id_tipo_num','rv.fecha_firma','rv.ruta_receta','rv.updated_us','rv.codigo_upss',
                'a1.fecha_nac as fecha_nacimiento_beneficiario','a1.sexo as sexo_beneficiario','rv.flag','rv.id_evaluacion','rv.cod_cpt','rv.codigo_siteds','rv.id_interconsulta',
                'rv.updated_at_cod_siteds','rv.flag_docsinfirmar','rv.created_at as fecha_creacion',
                'e.id as id_establecimiento',
                'e.nombre as ipress',
                'f.nombre as farmacia',
                'e.codigo as codigoipress',
                'c.nombre as consultorio',
                'm.nombre as nombre_medico',
                'm.paterno as paterno_medico',
                'm.materno as materno_medico',
                'm.dni as dni_medico',
                'm.colegiatura as colegiatura_medico',
                'es.nombre as especializacion',
                'd.codigo as cie',
                'd.nombre as diagnostico', 
                'u.name as nombre_usuario',
                'u.paterno as paterno_usuario',
                'u.materno as materno_usuario',
                'a2.paterno as paterno_titular',
                'a2.materno as materno_titular',
                'a2.nombre as nombre_titular',
                'a2.id as id_asegurado_titular',
                'a1.tipo_doc_ident',
                'rv.nro_historia as nro_historia2',
                'p.cups_pnp as cups',
                'p.ruta_prestacion'
                )
                ->get();
        return $datos;
    }

    public function ShowAllByIdEvaluacion($id) {
        $datos = DB::table('receta_vales as rv')
                ->join('farmacias as f', 'rv.id_farmacia', '=', 'f.id')
                ->join('consultorios as c', 'rv.id_consultorio', '=', 'c.id')
                ->join('evaluaciones as ev', 'rv.id_evaluacion', '=', 'ev.id')
                ->join('medicos as m', 'ev.id_medico', '=', 'm.id')
                ->leftJoin('especializaciones as es', 'm.id_especializacion', '=', 'es.id')
                ->leftJoin('diagnosticos as d', 'rv.id_diagnostico', '=', 'd.id')
                ->join('establecimientos as e', 'f.id_establecimiento', '=', 'e.id')
                ->leftJoin('prestaciones as p', 'rv.id_prestacion', '=', 'p.id')
                ->leftJoin('asegurados as a1', 'rv.id_asegurado', '=', 'a1.id')
                ->leftJoin('asegurado_historias as ah', function($join)
                {
                    $join->on('ah.id_asegurado', '=', 'a1.id')
                    ->on('ah.id_establecimiento', '=', 'e.id');
                })
                ->leftJoin('asegurados as a2', 'a1.id_asegurado_titular', '=', 'a2.id')
                ->join('users as u', 'rv.id_user', '=', 'u.id')
                ->where('rv.id', '=', $id)
                ->select('rv.id','rv.id_farmacia','rv.id_user','rv.id_consultorio','rv.nro_receta',
                'a1.nro_doc_ident as dni_beneficiario','a1.nombre as nombre_beneficiario','a1.paterno as paterno_beneficiario','a1.materno as materno_beneficiario','a1.cip as cip_beneficiario',
                'rv.tipo_beneficiario','rv.fecha_expedicion','rv.fecha_expiracion','rv.fecha_registro','rv.created_at','rv.updated_at',
                'ah.nro_historia as nro_historia','a1.grado','rv.id_medico','rv.tipo_receta','rv.turno','a2.nro_doc_ident as dni_titular',
                'rv.estado','rv.tipodoc_beneficiario','rv.tipodoc_titular','rv.nro_guia','rv.interconsulta','rv.interconsulta_dias','rv.tipo_via','rv.sub_via',
                'rv.final_via','a1.id as id_asegurado','rv.id_prestacion','rv.id_tipo_num','rv.fecha_firma','rv.ruta_receta','rv.updated_us','rv.codigo_upss',
                'a1.fecha_nac as fecha_nacimiento_beneficiario','a1.sexo as sexo_beneficiario','rv.flag','rv.id_evaluacion','rv.cod_cpt','rv.codigo_siteds','rv.id_interconsulta',
                'rv.updated_at_cod_siteds','rv.flag_docsinfirmar','rv.created_at as fecha_creacion',
                'e.id as id_establecimiento',
                'e.nombre as ipress',
                'f.nombre as farmacia',
                'e.codigo as codigoipress',
                'c.nombre as consultorio',
                'm.nombre as nombre_medico',
                'm.paterno as paterno_medico',
                'm.materno as materno_medico',
                'm.dni as dni_medico',
                'm.colegiatura as colegiatura_medico',
                'es.nombre as especializacion',
                'd.codigo as cie',
                'd.nombre as diagnostico', 
                'u.name as nombre_usuario',
                'u.paterno as paterno_usuario',
                'u.materno as materno_usuario',
                'a2.paterno as paterno_titular',
                'a2.materno as materno_titular',
                'a2.nombre as nombre_titular',
                'a2.id as id_asegurado_titular',
                'a1.tipo_doc_ident',
                'rv.nro_historia as nro_historia2',
                'p.cups_pnp as cups',
                'p.ruta_prestacion'
                )
                ->get();
        return $datos;
    }

    public function ShowByIdRecetaAndId_farmacia($nro_receta, $id_farmacia) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->join('consultorios', 'receta_vales.id_consultorio', '=', 'consultorios.id')
                ->join('medicos', 'receta_vales.id_medico', '=', 'medicos.id')
                ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
                ->leftJoin('diagnosticos', 'receta_vales.id_diagnostico', '=', 'diagnosticos.id')
                ->join('establecimientos', 'farmacias.id_establecimiento', '=', 'establecimientos.id')
                ->join('users', 'receta_vales.id_user', '=', 'users.id')
                ->leftJoin('asegurados', 'asegurados.id', '=', 'receta_vales.id_asegurado')
                ->leftJoin('asegurado_historias', function($join)
                {
                    $join->on('asegurado_historias.id_asegurado', '=', 'asegurados.id')
                    ->on('asegurado_historias.id_establecimiento', '=', 'establecimientos.id');
                })
        ->where('receta_vales.id', '=', $nro_receta)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->select('receta_vales.*', 'establecimientos.nombre as ipress', 'establecimientos.codigo as codigoipress', 'consultorios.nombre as consultorio', 'medicos.nombre as nombre_medico', 'medicos.paterno as paterno_medico', 'medicos.materno as materno_medico', 'diagnosticos.codigo as cie', 'diagnosticos.nombre as diagnostico', 'users.name as nombre_usuario', 'users.paterno as paterno_usuario', 'users.materno as materno_usuario', 'receta_vales.created_at as fecha_creacion', 'farmacias.nombre as farmacia', 'medicos.colegiatura as colegiatura','especializaciones.nombre as especializacion','asegurados.nombre as nombre_titular','asegurados.paterno as paterno_titular','asegurados.materno as materno_titular','asegurados.id_tipo_beneficiario','asegurados.tipo_doc_ident','asegurado_historias.nro_historia as nro_historia2')
                ->get();
        return $datos;
    }

    public function ShowByNroRecetaAndId_farmacia($nro_receta, $id_farmacia, $id_receta = 0) {
        $datos = DB::table('receta_vales')
                ->leftJoin('admisiones', 'receta_vales.id_admision', '=', 'admisiones.id')
                ->leftJoin('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->leftJoin('consultorios', 'receta_vales.id_consultorio', '=', 'consultorios.id')
                ->leftJoin('medicos', 'receta_vales.id_medico', '=', 'medicos.id')
                ->leftJoin('diagnosticos', 'receta_vales.id_diagnostico', '=', 'diagnosticos.id')
                ->join('establecimientos', 'farmacias.id_establecimiento', '=', 'establecimientos.id')
                ->leftJoin('users', 'receta_vales.id_user', '=', 'users.id')
                //->where('receta_vales.nro_receta', '=', $nro_receta)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.id', '=', $id_receta)
                ->select('receta_vales.*', 'establecimientos.nombre as ipress', 'establecimientos.codigo as codigoipress', 'consultorios.nombre as consultorio', 'medicos.nombre as nombre_medico', 'medicos.paterno as paterno_medico', 'medicos.materno as materno_medico', 'diagnosticos.codigo as cie', 'diagnosticos.nombre as diagnostico', 'users.name as nombre_usuario', 'users.paterno as paterno_usuario', 'users.materno as materno_usuario', 'receta_vales.created_at as fecha_creacion', 'nro_admision', 'fecha_admision')
                ->get();
        return $datos;
    }

    public function ShowByNroRecetaAndId_farmaciaValida($nro_receta, $id_farmacia, $id_receta) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->join('users', 'receta_vales.id_user', '=', 'users.id')
                ->join('consultorios', 'receta_vales.id_consultorio', '=', 'consultorios.id')
                ->join('medicos', 'receta_vales.id_medico', '=', 'medicos.id')
                ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
                ->leftJoin('diagnosticos', 'receta_vales.id_diagnostico', '=', 'diagnosticos.id')
                ->join('establecimientos', 'farmacias.id_establecimiento', '=', 'establecimientos.id')
				->leftJoin('prestaciones', 'receta_vales.id_prestacion', '=', 'prestaciones.id')
                ->where('receta_vales.nro_receta', '=', $nro_receta)
                ->where('receta_vales.id', '=', $id_receta)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.estado', '=', 1) 
                ->select('receta_vales.*', 'establecimientos.id as id_establecimiento','establecimientos.nombre as ipress', 'consultorios.nombre as consultorio', 'especializaciones.nombre as especializacion', 'medicos.nombre as nombre_medico', 'medicos.paterno as paterno_medico', 'medicos.materno as materno_medico', 'medicos.dni as dni_medico', 'diagnosticos.codigo as cie', 'diagnosticos.nombre as diagnostico', 'users.name as nombre_usuario', 'users.paterno as paterno_usuario', 'users.materno as materno_usuario', 'receta_vales.created_at as fecha_creacion','prestaciones.ruta_prestacion')
                ->get();
        return $datos;
    }

    public function ShowByNroRecetaAndId_farmaciaValidaEvaluacion($nro_receta, $id_farmacia, $id_receta) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->join('users', 'receta_vales.id_user', '=', 'users.id')
                ->join('consultorios', 'receta_vales.id_consultorio', '=', 'consultorios.id')
                ->join('evaluaciones', 'receta_vales.id_evaluacion', '=', 'evaluaciones.id')
                ->join('medicos', 'evaluaciones.id_medico', '=', 'medicos.id')
                ->join('especializaciones', 'medicos.id_especializacion', '=', 'especializaciones.id')
                ->leftJoin('diagnosticos', 'receta_vales.id_diagnostico', '=', 'diagnosticos.id')
                ->join('establecimientos', 'farmacias.id_establecimiento', '=', 'establecimientos.id')
                ->leftJoin('prestaciones', 'receta_vales.id_prestacion', '=', 'prestaciones.id')
                ->where('receta_vales.nro_receta', '=', $nro_receta)
                ->where('receta_vales.id', '=', $id_receta)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.estado', '=', 1) 
                ->select('receta_vales.*', 'establecimientos.id as id_establecimiento','establecimientos.nombre as ipress', 'consultorios.nombre as consultorio', 'especializaciones.nombre as especializacion', 'medicos.nombre as nombre_medico', 'medicos.paterno as paterno_medico', 'medicos.materno as materno_medico', 'medicos.dni as dni_medico', 'diagnosticos.codigo as cie', 'diagnosticos.nombre as diagnostico', 'users.name as nombre_usuario', 'users.paterno as paterno_usuario', 'users.materno as materno_usuario', 'receta_vales.created_at as fecha_creacion','ruta_prestacion')
                ->get();
        return $datos;
    }

    public function ShowByNroRecetaAndId_farmaciaInvalidada($nro_receta, $id_farmacia, $id_receta) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->join('consultorios', 'receta_vales.id_consultorio', '=', 'consultorios.id')
                ->join('medicos', 'receta_vales.id_medico', '=', 'medicos.id')
                ->leftJoin('diagnosticos', 'receta_vales.id_diagnostico', '=', 'diagnosticos.id')
                ->join('establecimientos', 'farmacias.id_establecimiento', '=', 'establecimientos.id')
                ->join('receta_devueltas', 'receta_vales.id', '=', 'receta_devueltas.id_receta_vale')
                ->join('users', 'receta_devueltas.id_user', '=', 'users.id')
                ->where('receta_vales.nro_receta', '=', $nro_receta)
                ->where('receta_vales.id', '=', $id_receta)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.estado', '=', 0)
                ->select('receta_vales.*', 'establecimientos.id as id_establecimiento','establecimientos.nombre as ipress', 'consultorios.nombre as consultorio', 'medicos.nombre as nombre_medico', 'medicos.paterno as paterno_medico', 'medicos.materno as materno_medico', 'diagnosticos.codigo as cie', 'diagnosticos.nombre as diagnostico', 'users.name as nombre_usuario', 'users.paterno as paterno_usuario', 'users.materno as materno_usuario', 'receta_devueltas.created_at as fecha_creacion')
                ->get();
        //var_dump($datos); exit;
        return $datos;
    }

    public function ShowByDNIAndId_farmacia($dni, $id_farmacia) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->join('consultorios', 'receta_vales.id_consultorio', '=', 'consultorios.id')
                ->join('medicos', 'receta_vales.id_medico', '=', 'medicos.id')
                ->leftJoin('diagnosticos', 'receta_vales.id_diagnostico', '=', 'diagnosticos.id')
                ->join('establecimientos', 'farmacias.id_establecimiento', '=', 'establecimientos.id')
                ->where('receta_vales.dni_beneficiario', '=', $dni)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.estado', '=', 1)
                ->select('receta_vales.*', 'establecimientos.nombre as ipress', 'consultorios.nombre as consultorio', 'medicos.nombre as nombre_medico', 'medicos.paterno as paterno_medico', 'medicos.materno as materno_medico', 'diagnosticos.codigo as cie', 'diagnosticos.nombre as diagnostico')
                ->orderBy('receta_vales.id', 'desc')
                ->get();
        return $datos;
    }

    public function ShowByServicioAndId_farmacia($dni, $id_farmacia) {
        
        $cad = "select receta_vales.*, consultorios.nombre as consultorio, EXTRACT(DAY FROM age(date(fecha_final_interconsulta),timestamp 'now()' ) ) as dif_dias
                from receta_vales
                inner join farmacias on receta_vales.id_farmacia = farmacias.id
                inner join consultorios on receta_vales.id_consultorio = consultorios.id
                Where receta_vales.dni_beneficiario ='" . $dni . "'
                And receta_vales.id_farmacia ='" . $id_farmacia . "'
                And receta_vales.estado = 1
                And receta_vales.tipo_receta='HOSPITALIZACION'
                And receta_vales.interconsulta='SI'
                And EXTRACT(DAY FROM age(date(fecha_final_interconsulta),timestamp 'now()' ) ) > 0";

        $data = DB::select($cad);
        return $data;
        
    }

    public function ShowByServicioAndId_farmacia_Servicio($dni, $id_farmacia,$servicio_id) {
        
        $cad = "select receta_vales.*, consultorios.nombre as consultorio, EXTRACT(DAY FROM age(date(fecha_final_interconsulta),timestamp 'now()' ) ) as dif_dias
                from receta_vales
                inner join farmacias on receta_vales.id_farmacia = farmacias.id
                inner join consultorios on receta_vales.id_consultorio = consultorios.id
                Where receta_vales.dni_beneficiario ='" . $dni . "'
                And receta_vales.id_farmacia ='" . $id_farmacia . "'
                And receta_vales.id_consultorio ='" . $servicio_id . "'
                And receta_vales.estado = 1
                And receta_vales.tipo_receta='HOSPITALIZACION'
                And receta_vales.interconsulta='SI'
                And EXTRACT(DAY FROM age(date(fecha_final_interconsulta),timestamp 'now()' ) ) > 0";

        $data = DB::select($cad);
        if(count($data)>0)
            return $data[0];
        else
            return $data;
        
    }
    
    public function GetIdRecetaByNroReceta($nro_receta, $id_farmacia, $id_receta) {
        $datos = DB::table('receta_vales')
                //->where('receta_vales.nro_receta', '=', $nro_receta)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.id', '=', $id_receta)
                //->where('receta_vales.estado','=',1)
                ->select('receta_vales.id')
                ->first();
        return $datos;
    }

    public function GetIdRecetaByNroRecetaInvalidada($nro_receta, $id_farmacia, $id_receta) {
        $datos = DB::table('receta_vales')
                ->where('receta_vales.nro_receta', '=', $nro_receta)
                ->where('receta_vales.id', '=', $id_receta)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.estado', '=', 0)
                ->select('receta_vales.id')
                ->first();
        return $datos;
    }

    public function GetIdRecetaByNroRecetaValida($nro_receta, $id_farmacia) {
        $datos = DB::table('receta_vales')
                ->where('receta_vales.nro_receta', '=', $nro_receta)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.estado', '=', 1)
                ->select('receta_vales.id')
                ->first();
        return $datos;
    }

    public function GetRecetaInvalidada($nro_receta, $id_farmacia, $id_receta) {
        $datos = DB::table('receta_devueltas')
                ->join('users', 'receta_devueltas.id_user', '=', 'users.id')
                //->where('receta_devueltas.numero_movimiento', '=', $nro_receta)
                ->where('receta_devueltas.id_farmacia', '=', $id_farmacia)
                ->where('receta_devueltas.id_receta_vale', '=', $id_receta)
                //->where('receta_vales.estado','=',1)
                ->select('receta_devueltas.*', 'users.*')
                ->get();
        return $datos;
    }

    public function AllRecetasByUserAndFecha($id_user, $fecha) {
        $datos = DB::table('receta_vales')
                ->join('users', 'receta_vales.id_user', '=', 'users.id')
                //->join('farmacias','receta_vales.id_farmacia','=','farmacias.id')
                ->where('receta_vales.id_user', '=', $id_user)
                ->where('receta_vales.fecha_registro', '=', $fecha)
                ->select('receta_vales.*', 'users.name')
                ->orderBy('receta_vales.id', 'desc')
                ->get();
        return $datos;
    }

    public function AllRecetasByFarmaAndFecha($id_farmacia, $fecha) {
        $datos = DB::table('receta_vales')
                ->join('users', 'receta_vales.id_user', '=', 'users.id')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '=', $fecha)
                ->where('receta_vales.estado', '=', 1)
                ->select('receta_vales.*', 'users.*', 'users.paterno as paterno_usuario', 'users.materno as materno_usuario', 'receta_vales.created_at as fec_creacion')
                ->orderBy('receta_vales.id', 'desc')
                ->get();
        return $datos;
    }

    // TAGSITEDS: SE AGREGO c.id as consultorio_id, farmacias.id_establecimiento
    public function AllRecetasByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_receta = "", $iddispensador = "", $idconsultorio = "") {

        $cad = "select receta_vales.*, users.name nombre_usuario, users.paterno as paterno_usuario,users.materno as materno_usuario,receta_vales.created_at as fec_creacion, c.id as consultorio_id, farmacias.id_establecimiento, c.nombre consultorio, CASE 
        WHEN receta_vales.updated_at_cod_siteds < current_timestamp - interval '1 day' THEN 1
        ELSE 0
    END AS siteds_expirado
from receta_vales
Inner Join consultorios c On receta_vales.id_consultorio=c.id
inner join users on receta_vales.id_user=users.id
inner join farmacias on receta_vales.id_farmacia=farmacias.id
left join emergencias on receta_vales.dni_beneficiario=emergencias.dni_beneficiario
where receta_vales.id_farmacia=" . $id_farmacia;
        if ($dni_beneficiario):
            $cad .= " and receta_vales.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_receta):
            $cad .= " and receta_vales.nro_receta like '%" . $nro_receta . "%'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($iddispensador != "")
            $cad .= " and receta_vales.id_user = " . $iddispensador . "";

        if($id_farmacia == 19):
            if ($idconsultorio != ""):
                if ($idconsultorio != 0):
                    $cad .= " and emergencias.id_triaje = '" . $idconsultorio . "'";
                endif;
            endif;
        else:
            if ($idconsultorio != ""):
                if ($idconsultorio != 0):
                    $cad .= " and receta_vales.id_consultorio = " . $idconsultorio . "";
                endif;
            endif;
        endif;

        if ($fechaDesde != "")
            $cad .= " and receta_vales.fecha_expedicion >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and receta_vales.fecha_expedicion <= '" . $fechaHasta . "'";

        $cad .= " and receta_vales.estado=1
        group by receta_vales.id,users.id,c.id, farmacias.id
order By receta_vales.updated_at desc";
        //echo $cad;
        $data = DB::select($cad);
        return $data;
    }
	
	public function AllRecetasPrescritasByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_receta = "", $iddispensador = "", $idconsultorio = "") {

        $cad = "select receta_vales.*,users.name nombre_usuario, users.paterno as paterno_usuario,users.materno as materno_usuario,receta_vales.created_at as fec_creacion
, c.nombre consultorio,ruta_prestacion , farmacias.nombre 
from receta_vales
Inner Join consultorios c On receta_vales.id_consultorio=c.id
inner join users on receta_vales.id_user=users.id
inner join farmacias on receta_vales.id_farmacia=farmacias.id
left join admisiones on receta_vales.id_admision=admisiones.id
left Join prestaciones on receta_vales.id_prestacion=prestaciones.id
left join emergencias on receta_vales.dni_beneficiario=emergencias.dni_beneficiario
where farmacias.id_establecimiento=" . $id_establecimiento;
        if ($dni_beneficiario):
            $cad .= " and receta_vales.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_receta):
            $cad .= " and receta_vales.nro_receta like '%" . $nro_receta . "%'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($idconsultorio != ""):
                $cad .= " and receta_vales.id_consultorio = " . $idconsultorio . "";
        endif;

        if ($iddispensador != "")
            $cad .= " and receta_vales.id_user = " . $iddispensador . "";

        if ($fechaDesde != "")
            $cad .= " and receta_vales.fecha_registro >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and receta_vales.fecha_registro <= '" . $fechaHasta . "'";

        $cad .= " and receta_vales.estado=2
        group by receta_vales.id,users.id,c.id, farmacias.id, prestaciones.ruta_prestacion
order By receta_vales.id desc";

        $data = DB::select($cad);
        return $data;
    }

    public function AllRecetasPrescritasByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_receta = "", $iddispensador = "", $idconsultorio = "") {

        $cad = "select receta_vales.*,users.name nombre_usuario, users.paterno as paterno_usuario,users.materno as materno_usuario,receta_vales.created_at as fec_creacion
, c.nombre consultorio,ruta_prestacion , farmacias.nombre,  hospitalizaciones.estado as alta, prestaciones.flag flag_prestacion
from receta_vales
Inner Join consultorios c On receta_vales.id_consultorio=c.id
inner join users on receta_vales.id_user=users.id
inner join farmacias on receta_vales.id_farmacia=farmacias.id
left Join prestaciones on receta_vales.id_prestacion=prestaciones.id
left join emergencias on receta_vales.dni_beneficiario=emergencias.dni_beneficiario
left Join hospitalizaciones on receta_vales.dni_beneficiario=hospitalizaciones.dni_beneficiario and prestaciones.id_ext = hospitalizaciones.id
where 1 = 1 ";

        if ($id_farmacia):
            if($id_farmacia == 3 || $id_farmacia == 121):
                $cad .= " and farmacias.id_establecimiento = 76 and (farmacias.id = 3 or farmacias.id =121)  ";
            else:
                $cad .= " and farmacias.id = " . $id_farmacia . "";
            endif;
        endif;

        if($id_farmacia == 19):
            if ($idconsultorio != ""):
                $cad .= " and emergencias.id_triaje = '" . $idconsultorio . "'";
            endif;
        else:
            if ($idconsultorio != ""):
                $cad .= " and receta_vales.id_consultorio = " . $idconsultorio . "";
            endif;
        endif;

        if($id_farmacia == 128):
            $cad .= " and receta_vales.ruta_receta is not null ";
        endif;

        if ($dni_beneficiario):
            $cad .= " and receta_vales.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_receta):
            $cad .= " and receta_vales.nro_receta like '%" . $nro_receta . "%'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($iddispensador != "")
            $cad .= " and receta_vales.id_user = " . $iddispensador . "";

        if ($fechaDesde != "")
            $cad .= " and receta_vales.fecha_registro >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and receta_vales.fecha_registro <= '" . $fechaHasta . "'";

        $cad .= " and receta_vales.estado=2
                  group by receta_vales.id,users.id,c.id, farmacias.id, prestaciones.ruta_prestacion,hospitalizaciones.estado,prestaciones.flag
                  order By receta_vales.id desc";
        //echo $cad;
        $data = DB::select($cad);
        return $data;
    }

    public function AllRecetasByFarmaAndFechaDesdeHastaServicio($id_farmacia, $fechaDesde = "", $fechaHasta = "", $idconsultorio = "") {

        $cad = "select  receta_vales.tipo_receta,receta_vales.tipodoc_beneficiario, receta_vales.dni_beneficiario,receta_vales.tipo_beneficiario,receta_vales.nombre_beneficiario, receta_vales.paterno_beneficiario, receta_vales.materno_beneficiario, c.nombre,receta_vales.id_consultorio
from receta_vales
Inner Join consultorios c On receta_vales.id_consultorio=c.id
inner join farmacias on receta_vales.id_farmacia=farmacias.id
where receta_vales.id_farmacia=" . $id_farmacia;
        if ($idconsultorio != "")
            $cad .= " and receta_vales.id_consultorio = " . $idconsultorio . "";

        if ($fechaDesde != "")
            $cad .= " and receta_vales.fecha_expedicion >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and receta_vales.fecha_expedicion <= '" . $fechaHasta . "'";

        $cad .= " and receta_vales.estado=1
group By receta_vales.tipo_receta,receta_vales.tipodoc_beneficiario, receta_vales.dni_beneficiario,receta_vales.tipo_beneficiario,receta_vales.nombre_beneficiario, receta_vales.paterno_beneficiario, receta_vales.materno_beneficiario, c.nombre, receta_vales.id_consultorio
order By receta_vales.tipo_receta, receta_vales.dni_beneficiario desc";
        
        $data = DB::select($cad);
        return $data;
    }

    public function TramaAllRecetasByFarmaAndFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "") {
        
        $cad = "Select * From usp_tramasaludpol_listar_por_periodo ('".$id_establecimiento."','".$fechaDesde."','".$fechaHasta."');";
        $data = DB::select($cad);

        return $data;
    }


    public function TramaAllRecetasByFarmaAndFechaDesdeHasta_old($id_establecimiento, $fechaDesde = "", $fechaHasta = "") {

        $cad = "
        select  (Case When rv.tipodoc_beneficiario='DNI' Then 'DNI' When rv.tipodoc_beneficiario='CE' Then 'CE' End)::varchar paciente_tipo_documento, rv.dni_beneficiario as paciente_numero_documento,
rv.paterno_beneficiario, rv.materno_beneficiario, rv.nombre_beneficiario,rv.nro_receta, rv.tipo_receta as tipo_atencion, rv.fecha_expedicion, rv.fecha_registro,
m.dni as dni_medico, m.paterno as paterno_medico, m.materno as materno_medico, m.nombre as nomb_medico, es.nombre as especialidad_medico, c.nombre as servicio, d.codigo as cod_dx, d.nombre as diagnostico,   
(Case When p.id_tipo=1 Then 'C' When p.id_tipo In(2,3,4,5) Then 'I' When p.id_tipo In(6,7,8) Then 'O' End)::varchar tipo, t.nombre as tipo_producto, (SubString(p.codigo,1,2)
||'.'||SubsTring(p.codigo,3,3)||'.'||SubsTring(p.codigo,6,3))::varchar codigo_petitorio, p.nombre as principio_activo,   
Sum(pr.cantidad_dispensada)cantidad, k.precio_unitario, e.codigo ipress_codigo 
From receta_vales rv Inner Join producto_recetas pr On rv.id=pr.id_receta_vale And rv.estado=1 And pr.cantidad_dispensada>0 And rv.fecha_expedicion Between '".$fechaDesde."' And '".$fechaHasta."'
Inner Join productos p On pr.id_producto=p.id 
inner join tipo_productos t on t.id=p.id_tipo
Inner Join farmacias f On rv.id_farmacia=f.id 
Inner Join establecimientos e On f.id_establecimiento=e.id 
inner join medicos m on m.id=rv.id_medico
inner join especializaciones es on es.id = m.id_especializacion
inner join consultorios c on c.id = rv.id_consultorio
left join diagnosticos d on d.id= rv.id_diagnostico
Inner Join(Select k.id_nro_movimiento,k.id_producto,Sum(kl.cantidad*kl.presentacion_comercial)cantidad,Avg(kl.precio_unitario)precio_unitario 
From kardexes k Inner Join kardex_lotes kl On k.id=kl.id_kardex And k.id_tipo_movimiento=4 And k.numero_movimiento Not Like '%-i' 
Group By k.id_nro_movimiento,k.id_producto)k On rv.id=k.id_nro_movimiento And pr.id_producto=k.id_producto 
where e.id = ".$id_establecimiento."
Group by rv.tipodoc_beneficiario, rv.dni_beneficiario, rv.paterno_beneficiario, rv.materno_beneficiario, rv.nombre_beneficiario,rv.nro_receta, rv.tipo_receta,
rv.fecha_expedicion, rv.fecha_registro, m.dni, m.paterno, m.materno, m.nombre, es.nombre, c.nombre, d.codigo, d.nombre, p.id_tipo, t.nombre, p.codigo,  p.nombre,k.precio_unitario, e.codigo
--Order By 8,2,9";
        
        $data = DB::select($cad);
        return $data;
    }

/*    public function TramaGetCountRecetasByUserAndFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "") {

$cad = "Select count(1) cantidad 
From receta_vales rv Inner Join producto_recetas pr On rv.id=pr.id_receta_vale And rv.estado=1 And pr.cantidad_dispensada>0 And rv.fecha_expedicion Between '".$fechaDesde."' And '".$fechaHasta."'
Inner Join productos p On pr.id_producto=p.id 
Inner Join farmacias f On rv.id_farmacia=f.id 
Inner Join establecimientos e On f.id_establecimiento=e.id 
Inner Join(Select k.id_nro_movimiento,k.id_producto,Sum(kl.cantidad)cantidad,Avg(kl.precio_unitario)precio_unitario 
From kardexes k Inner Join kardex_lotes kl On k.id=kl.id_kardex And k.id_tipo_movimiento=4 And k.numero_movimiento Not Like '%-i' 
Group By k.id_nro_movimiento,k.id_producto)k On rv.id=k.id_nro_movimiento And pr.id_producto=k.id_producto 
where e.id = ".$id_establecimiento;

        $data = DB::select($cad);
        return $data[0]->cantidad;
    }
*/

    public function AllDispensadorByFarmacia_anterior($id_farmacia) {
        $cad = "Select rv.id_user, upper(u.name) nombre, upper(u.paterno) paterno, upper(u.materno) materno From receta_vales rv
Inner Join users u On rv.id_user = u.id
Where rv.id_farmacia =" . $id_farmacia . " and u.status=1
Group By rv.id_user, u.name, u.paterno, u.materno";
        $data = DB::select($cad);
        return $data;
    }   

    public function AllDispensadorByFarmacia($id_farmacia) {
        $cad = "Select u.id id_user, upper(u.name) nombre, upper(u.paterno) paterno, upper(u.materno) materno 
        From users u 
Where u.id_farmacia =" . $id_farmacia . " and u.status=1 and u.tipo_registro=2 
Group By u.id, u.name, u.paterno, u.materno";
        $data = DB::select($cad);
        return $data;
    }    

    public function GetCountRecetasMesActualByBeneficiario($dni_beneficiario) {
        $cad = "Select count(rv.id) cant From receta_vales rv
Inner Join farmacias f On rv.id_farmacia=f.id
Inner Join medicos me On rv.id_medico=me.id
Inner Join consultorios co On rv.id_consultorio=co.id
left Join diagnosticos di On rv.id_diagnostico = di.id
Where rv.dni_beneficiario = '" . $dni_beneficiario . "' And extract(year from rv.fecha_expedicion)='" . date("Y") . "' And extract(month from rv.fecha_expedicion) = '" . date("m") . "'
And rv.estado=1;";
        $data = DB::select($cad);
        return $data[0];
    }

    public function GetRecetasMesActualByBeneficiario($dni_beneficiario) {
        $cad = "Select rv.id id_receta_vale,rv.nro_receta, rv.fecha_expedicion, rv.fecha_registro, rv.tipo_receta, rv.id_consultorio, co.nombre nom_consultorio, rv.id_farmacia, f.nombre nom_farmacia, rv.id_medico, me.paterno apepat_medico, me.materno apemat_medico, me.nombre nom_medico, rv.id_diagnostico, di.nombre nom_diagnostico, (rv.id_consultorio||''||rv.id_medico||''||rv.dni_beneficiario||''||rv.fecha_registro) cod_atencion
            From receta_vales rv
            Inner Join farmacias f On rv.id_farmacia=f.id
            Inner Join medicos me On rv.id_medico=me.id
            Inner Join consultorios co On rv.id_consultorio=co.id
            Left Join diagnosticos di On rv.id_diagnostico = di.id
            Where rv.dni_beneficiario = '" . $dni_beneficiario . "' and (extract(day from (now()-rv.fecha_expedicion))) between 0 and 30 
            And rv.estado=1 ";

        if(Auth::user()->id_farmacia==128):
            $cad .= " And tipo_receta='HOSPITALIZACION'";
        else:
            if(Auth::user()->id_farmacia==19):
                $cad .= " And tipo_receta='EMERGENCIA'";
            else:
                if(Auth::user()->id_farmacia==18 || Auth::user()->id_farmacia==25):
                    $cad .= " And tipo_receta='CENTRO QUIRURGICO'";
                else:
                    if(Auth::user()->id_farmacia!=14 && Auth::user()->id_farmacia!=22 && Auth::user()->id_farmacia!=107 && Auth::user()->id_farmacia==111 ):
                        $cad .= " And tipo_receta='AMBULATORIO'";
                    endif;
                endif;
            endif;
        endif;

		$cad .= " Order By rv.fecha_expedicion Desc, rv.id_consultorio, rv.id_medico,rv.fecha_registro 
                 limit 5;";
        
        $data = DB::select($cad);
        return $data;
    }

/*    public function GetRecetasMesActualByBeneficiario($dni_beneficiario) {
        $cad = "Select rv.id id_receta_vale,rv.nro_receta, rv.fecha_expedicion, rv.fecha_registro, rv.tipo_receta, rv.id_consultorio, co.nombre nom_consultorio, rv.id_farmacia, f.nombre nom_farmacia, rv.id_medico, me.paterno apepat_medico, me.materno apemat_medico, me.nombre nom_medico, rv.id_diagnostico, di.nombre nom_diagnostico,
(rv.id_consultorio||''||rv.id_medico||''||rv.dni_beneficiario||''||rv.fecha_registro) cod_atencion
From receta_vales rv
Inner Join farmacias f On rv.id_farmacia=f.id
Inner Join medicos me On rv.id_medico=me.id
Inner Join consultorios co On rv.id_consultorio=co.id
Left Join diagnosticos di On rv.id_diagnostico = di.id
Where rv.dni_beneficiario = '" . $dni_beneficiario . "' And rv.fecha_expedicion = current_date - 29

And rv.estado=1 And (tipo_receta!='EMERGENCIA' Or tipo_receta!='HOSPITALIZACION' ) Order By rv.fecha_expedicion Desc, rv.id_consultorio, rv.id_medico,rv.fecha_registro;";

        $data = DB::select($cad);
        return $data;
    }
*/

    public function GetRecetasMesActualByBeneficiarioEmergencia($dni_beneficiario) {
        $cad = "Select rv.id id_receta_vale,rv.nro_receta, rv.fecha_expedicion, rv.fecha_registro, rv.tipo_receta, rv.id_consultorio, co.nombre nom_consultorio, rv.id_farmacia, f.nombre nom_farmacia, rv.id_medico, me.paterno apepat_medico, me.materno apemat_medico, me.nombre nom_medico, rv.id_diagnostico, di.nombre nom_diagnostico,
(rv.id_consultorio||''||rv.id_medico||''||rv.dni_beneficiario||''||rv.fecha_registro) cod_atencion
From receta_vales rv
Inner Join farmacias f On rv.id_farmacia=f.id
Inner Join medicos me On rv.id_medico=me.id
Inner Join consultorios co On rv.id_consultorio=co.id
Left Join diagnosticos di On rv.id_diagnostico = di.id
Where rv.tipo_receta='EMERGENCIA' And rv.dni_beneficiario = '" . $dni_beneficiario . "' And extract(year from rv.fecha_expedicion)='" . date("Y") . "' And extract(month from rv.fecha_expedicion) = '" . date("m") . "'
And rv.estado=1 Order By rv.fecha_expedicion Desc, rv.id_consultorio, rv.id_medico,rv.fecha_registro;";
        $data = DB::select($cad);
        return $data;
    }

    public function GetRecetasMesActualByBeneficiarioHospitalizacion($dni_beneficiario) {
        $cad = "Select rv.id id_receta_vale,rv.nro_receta, rv.fecha_expedicion, rv.fecha_registro, rv.tipo_receta, rv.id_consultorio, co.nombre nom_consultorio, rv.id_farmacia, f.nombre nom_farmacia, rv.id_medico, me.paterno apepat_medico, me.materno apemat_medico, me.nombre nom_medico, rv.id_diagnostico, di.nombre nom_diagnostico,
(rv.id_consultorio||''||rv.id_medico||''||rv.dni_beneficiario||''||rv.fecha_registro) cod_atencion
From receta_vales rv
Inner Join farmacias f On rv.id_farmacia=f.id
Inner Join medicos me On rv.id_medico=me.id
Inner Join consultorios co On rv.id_consultorio=co.id
Left Join diagnosticos di On rv.id_diagnostico = di.id
Where rv.tipo_receta='HOSPITALIZACION' And rv.dni_beneficiario = '" . $dni_beneficiario . "' And extract(year from rv.fecha_expedicion)='" . date("Y") . "' And extract(month from rv.fecha_expedicion) = '" . date("m") . "'
And rv.estado=1 Order By rv.fecha_expedicion Desc, rv.id_consultorio, rv.id_medico,rv.fecha_registro;";
        $data = DB::select($cad);
        return $data;
    }

public function GetRecetasMesActualByBeneficiarioServicio($dni_beneficiario,$fecha_ini,$fecha_fin,$id_farmacia,$id_consultorio) {
        $cad = "Select rv.id id_receta_vale,rv.nro_receta, rv.fecha_expedicion, rv.fecha_registro, rv.tipo_receta, rv.id_consultorio, co.nombre nom_consultorio, rv.id_farmacia, f.nombre nom_farmacia, rv.id_medico, me.paterno apepat_medico, me.materno apemat_medico, me.nombre nom_medico, rv.id_diagnostico, di.nombre nom_diagnostico,
(rv.id_consultorio||''||rv.id_medico||''||rv.dni_beneficiario||''||rv.fecha_registro) cod_atencion
From receta_vales rv
Inner Join farmacias f On rv.id_farmacia=f.id
Inner Join medicos me On rv.id_medico=me.id
Inner Join consultorios co On rv.id_consultorio=co.id
Left Join diagnosticos di On rv.id_diagnostico = di.id
Where rv.id_consultorio='" . $id_consultorio . "'  And rv.id_farmacia='" . $id_farmacia . "'  And rv.dni_beneficiario = '" . $dni_beneficiario . "' 
and rv.fecha_expedicion >= '" . $fecha_ini . "'
and rv.fecha_expedicion <= '" . $fecha_fin . "'
And rv.estado=1 Order By rv.fecha_expedicion Desc, rv.id_consultorio, rv.id_medico,rv.fecha_registro;";
        $data = DB::select($cad);
        return $data;
    }


    public function GetCantAtencionesMesActualbyBeneficiario($dni_beneficiario, $id_consultorio, $fecha_expedicion, $id_medico)
    {
        $data = DB::select("Select sp_valid_atenciones_servicio_periodo('" . $dni_beneficiario . "'," . $id_consultorio . "," . $id_medico . ",'" . $fecha_expedicion . "');");

        return $data[0]->sp_valid_atenciones_servicio_periodo;
    }

    public function GetCantAtencionesMesActualbyBeneficiarioHospitalizacion($dni_beneficiario, $id_consultorio, $fecha_expedicion, $id_medico)
    {
        $data = DB::select("Select sp_valid_atenciones_servicio_periodo_hospitalizacion('" . $dni_beneficiario . "'," . $id_consultorio . "," . $id_medico . ",'" . $fecha_expedicion . "');");

        return $data[0]->sp_valid_atenciones_servicio_periodo_hospitalizacion;
    }

    public function GetCantAtencionesMesActualbyBeneficiarioEmergencia($dni_beneficiario, $id_consultorio, $fecha_expedicion, $id_medico)
    {
        $data = DB::select("Select sp_valid_atenciones_servicio_periodo_emergencia('" . $dni_beneficiario . "'," . $id_consultorio . "," . $id_medico . ",'" . $fecha_expedicion . "');");

        return $data[0]->sp_valid_atenciones_servicio_periodo_emergencia;
    }

    public function GetCantAtencionesMesActualbyBeneficiarioQuirurgico($dni_beneficiario, $id_consultorio, $fecha_expedicion, $id_medico)
    {
        $data = DB::select("Select sp_valid_atenciones_servicio_periodo_quirurgico('" . $dni_beneficiario . "'," . $id_consultorio . "," . $id_medico . ",'" . $fecha_expedicion . "');");

        return $data[0]->sp_valid_atenciones_servicio_periodo_quirurgico;
    }

    public function GetCantAtencionesMesActualbyBeneficiarioNutricional($dni_beneficiario, $id_consultorio, $fecha_expedicion, $id_medico)
    {
        $data = DB::select("Select sp_valid_atenciones_servicio_periodo_nutricional('" . $dni_beneficiario . "'," . $id_consultorio . "," . $id_medico . ",'" . $fecha_expedicion . "');");

        return $data[0]->sp_valid_atenciones_servicio_periodo_nutricional;
    }

    public function GetDetAtencionesMesActualbyBeneficiario($dni_beneficiario, $id_consultorio, $fecha_expedicion, $id_medico) {
        $data = DB::select("Select * From sp_valid_det_atenciones_servicio_periodo('" . $dni_beneficiario . "'," . $id_consultorio . "," . $id_medico . ",'" . $fecha_expedicion . "');");
        return $data;
    }

    public function GetDetAtencionesInterconsultabyBeneficiario($dni_beneficiario, $id_consultorio) {
        $data = DB::select("Select * From sp_det_interconsultas_servicio_hospitalizacion('" . $dni_beneficiario . "'," . $id_consultorio . ");");
        return $data;
    }

    public function GetCountRecetasByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_receta = "", $iddispensador = "", $idconsultorio = "") {

        $cad = "select count(1) cantidad
from receta_vales
left join emergencias on emergencias.dni_beneficiario = receta_vales.dni_beneficiario
where receta_vales.id_farmacia=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and receta_vales.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_receta):
            $cad .= " and receta_vales.nro_receta = '" . $nro_receta . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($iddispensador != "")
            $cad .= " and receta_vales.id_user = " . $iddispensador . "";

        if($id_farmacia == 19):
            if ($idconsultorio != ""):
                $cad .= " and emergencias.id_triaje = '" . $idconsultorio . "'";
            endif;
        else:
            if ($idconsultorio != ""):
                $cad .= " and receta_vales.id_consultorio = " . $idconsultorio . "";
            endif;
        endif;

        if ($fechaDesde != "")
            $cad .= " and receta_vales.fecha_expedicion >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and receta_vales.fecha_expedicion <= '" . $fechaHasta . "'";

        $cad .= " and receta_vales.estado=1";

        $data = DB::select($cad);
        return $data[0]->cantidad;
    }
	
	public function GetCountRecetasPrescritasByUserAndFechaDesdeHasta($id_establecimiento, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_receta = "", $iddispensador = "") {

        $cad = "select count(1) cantidad
from receta_vales 
inner join farmacias on receta_vales.id_farmacia=farmacias.id
where farmacias.id_establecimiento=" . $id_establecimiento;

        if ($dni_beneficiario):
            $cad .= " and receta_vales.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_receta):
            $cad .= " and receta_vales.nro_receta = '" . $nro_receta . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($iddispensador != "")
            $cad .= " and receta_vales.id_user = " . $iddispensador . "";

        if ($fechaDesde != "")
            $cad .= " and receta_vales.fecha_registro >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and receta_vales.fecha_registro <= '" . $fechaHasta . "'";

        $cad .= " and receta_vales.estado=2";

        $data = DB::select($cad);
        return $data[0]->cantidad;
    }

    public function GetCountRecetasPrescritasByUserFarmaciaAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_receta = "", $iddispensador = "", $idconsultorio = "") {

        $cad = "select count(1) cantidad
from receta_vales 
inner join farmacias on receta_vales.id_farmacia=farmacias.id
left join emergencias on emergencias.dni_beneficiario = receta_vales.dni_beneficiario
where farmacias.id=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and receta_vales.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_receta):
            $cad .= " and receta_vales.nro_receta = '" . $nro_receta . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;
        if ($id_farmacia):
            if($id_farmacia == 3 || $id_farmacia == 121):
                $cad .= " and farmacias.id_establecimiento = 76 and (farmacias.id = 3 or farmacias.id =121)  ";
            else:
                $cad .= " and farmacias.id = " . $id_farmacia . "";
            endif;
        endif;
        if($id_farmacia == 19):
            if ($idconsultorio != ""):
                $cad .= " and emergencias.id_triaje = '" . $idconsultorio . "'";
            endif;
        else:
            if ($idconsultorio != ""):
                $cad .= " and receta_vales.id_consultorio = " . $idconsultorio . "";
            endif;
        endif;


        if ($iddispensador != "")
            $cad .= " and receta_vales.id_user = " . $iddispensador . "";

        if ($fechaDesde != "")
            $cad .= " and receta_vales.fecha_registro >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and receta_vales.fecha_registro <= '" . $fechaHasta . "'";

        $cad .= " and receta_vales.estado=2";

        $data = DB::select($cad);
        return $data[0]->cantidad;
    }

    public function GetCountRecetasByUserAndFechaDesdeHastaServicio($id_farmacia, $fechaDesde = "", $fechaHasta = "", $idconsultorio = "") {

        $cad = "select count(1) cantidad
from receta_vales
where receta_vales.id_farmacia=" . $id_farmacia;

        if ($idconsultorio != "")
            $cad .= " and receta_vales.id_consultorio = " . $idconsultorio . "";

        if ($fechaDesde != "")
            $cad .= " and receta_vales.fecha_expedicion >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and receta_vales.fecha_expedicion <= '" . $fechaHasta . "'";

        $cad .= " and receta_vales.estado=1 group By receta_vales.dni_beneficiario";

        $data = DB::select($cad);

        $contador=count($data);

        if($contador==0){
            return $contador;
        }
        else{
            return $data[0]->cantidad;
        }
    }

    
    public function AllAdmisionByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_admision = "") {

        $cad = "select 
admisiones.*,users.*,users.paterno as paterno_usuario,users.materno as materno_usuario,admisiones.created_at as fec_creacion
,consultorios.nombre as consultorio
,citas.nro_cita 
from admisiones
left join consultorios on admisiones.id_consultorio=consultorios.id
left join citas on admisiones.id_cita = citas.id 
inner join users on admisiones.id_user=users.id
inner join farmacias on admisiones.id_farmacia=farmacias.id
where admisiones.id_farmacia=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and admisiones.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_admision):
            $cad .= " and admisiones.nro_admision = '" . $nro_admision . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($fechaDesde != "")
            $cad .= " and admisiones.fecha_admision >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and admisiones.fecha_admision <= '" . $fechaHasta . "'";

        $cad .= " and admisiones.estado=1 
order By admisiones.fecha_admision desc";
        //echo $cad;
        $data = DB::select($cad);
        return $data;
    }

    public function GetCountAdmisionByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_admision = "") {

        $cad = "select count(1) cantidad
from admisiones
where admisiones.id_farmacia=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and admisiones.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_admision):
            $cad .= " and admisiones.nro_admision = '" . $nro_admision . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($fechaDesde != "")
            $cad .= " and admisiones.fecha_admision >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and admisiones.fecha_admision <= '" . $fechaHasta . "'";

        $cad .= " and admisiones.estado=1";

        $data = DB::select($cad);
        return $data[0]->cantidad;
    }

    public function AllQuirurgicoByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_admision = "") {

        $cad = "select 
centro_quirurgicos.*
,admisiones,nro_admision,admisiones,fecha_admision
,admisiones,dni_beneficiario,admisiones,nombre_beneficiario,admisiones,paterno_beneficiario,admisiones,materno_beneficiario
,admisiones,tipo_beneficiario,admisiones,nro_historia
,users.*,users.paterno as paterno_usuario,users.materno as materno_usuario,admisiones.created_at as fec_creacion
,consultorios.nombre as consultorio
,origen_pacientes.nombre as origen_paciente
,sala_quirurgicas.nombre as sala
from centro_quirurgicos
left join admisiones on centro_quirurgicos.id_admision=admisiones.id 
inner join consultorios on centro_quirurgicos.id_consultorio=consultorios.id
left join origen_pacientes on centro_quirurgicos.id_origen_paciente=origen_pacientes.id
left join sala_quirurgicas on centro_quirurgicos.id_sala=sala_quirurgicas.id
inner join users on admisiones.id_user=users.id
inner join farmacias on admisiones.id_farmacia=farmacias.id
where admisiones.id_farmacia=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and admisiones.dni_beneficiario = '" . $dni_beneficiario . "'";
        endif;

        if ($nro_admision):
            $cad .= " and admisiones.nro_admision = '" . $nro_admision . "'";
        endif;

        if ($fechaDesde != "")
            $cad .= " and centro_quirurgicos.fecha_quirurgico >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and centro_quirurgicos.fecha_quirurgico <= '" . $fechaHasta . "'";

        $cad .= " and centro_quirurgicos.estado=1
order By centro_quirurgicos.fecha_quirurgico desc";
        //echo $cad;
        $data = DB::select($cad);
        return $data;
    }

    public function AllKardexByFarmaAndProducto($id_farmacia,$nombre_farmacia, $id_producto = "", $fechaDesde = "", $fechaHasta = "") {
$cad = "Select kl.id, k.id_farmacia,k.fecha_movimiento,k.id_tipo_movimiento,k.tipo_movimiento abrev_tipo_movimiento,tm.nombre tipo_movimiento, replace(k.numero_movimiento, '-i', '') numero_movimiento,p.id id_producto,
p.codigo codigo_producto,p.nombre nombre_producto,coalesce(kl.cantidad*kl.presentacion_comercial,0) as cantidad,kl.registro_sanitario,kl.lote,kl.fecha_vencimiento,kl.precio_unitario, k.created_at, kl.presentacion_comercial
From kardexes k
Inner Join productos p On k.id_producto=p.id 
Left Join kardex_lotes kl On k.id=kl.id_kardex
Inner Join tipo_movimientos tm On k.id_tipo_movimiento=tm.id
Where k.estado=1 and kl.estado=1 and k.id_farmacia = " . $id_farmacia . " And p.id=" . $id_producto . "";
if ($fechaDesde != "")
$cad .= " And k.fecha_movimiento>='" . $fechaDesde . "'";
if ($fechaHasta != "")
$cad .= " And k.fecha_movimiento<='" . $fechaHasta . "'";
$cad .= " Order By k.id, replace(k.numero_movimiento, '-i', ''), k.id_tipo_movimiento;";    
        $data = DB::select($cad);
        return $data;
    }

    public function AllRecetaKardexByFarmaAndProducto($id_farmacia,$nombre_farmacia, $id_producto = "", $fechaDesde = "", $fechaHasta = "") {
$cad = "Select rv.id, rv.id_farmacia,rv.fecha_registro, rv.fecha_expedicion,rv.fecha_firma,rv.nro_receta,rv.dni_beneficiario, p.codigo codigo_producto,p.nombre nombre_producto,rv.estado , m.nombre as nombre_medico,m.paterno as paterno_medico, m.materno as materno_medico, m.dni as dni_medico,
pr.cantidad_prescrita, pr.cantidad_dispensada, pr.saldo 
From receta_vales rv 
left Join producto_recetas pr  On pr.id_receta_vale =rv.id 
left Join medicos m  On rv.id_medico =m.id 
Inner Join productos p On pr.id_producto=p.id 
Where rv.estado!=0 and rv.id_farmacia = " . $id_farmacia . " And p.id=" . $id_producto . "";
if ($fechaDesde != "")
$cad .= " And rv.fecha_registro>='" . $fechaDesde . "'";
if ($fechaHasta != "")
$cad .= " And rv.fecha_registro<='" . $fechaHasta . "'";
$cad .= " Order By rv.id desc;";   
        $data = DB::select($cad);
        return $data;
    }
    public function AllResumenRecetasAtendidasByFarmaAndFecha($id_farmacia, $fechaDesde = "", $fechaHasta = "") {
        $data = DB::select("select * From sp_consultation_indicators(" . $id_farmacia . ",'" . $fechaDesde . "','" . $fechaHasta . "');");
        return $data;

        /*
          $cad = "Select centro_atencion,ano,mes,fecha,usuario,Sum(nra)nra,Sum(nia)nia,Sum(nua)nua,Sum(nta)nta,Sum(nfa)nfa,Sum(nrna)nrna,Sum(nina)nina,Sum(nuna)nuna,Sum(ntna)ntna,Sum(nfna)nfna
          From (
          Select f.nombre centro_atencion,to_char(rv.fecha_expedicion,'yyyy')::int ano,to_char(rv.fecha_expedicion,'TMMonth')::varchar mes,rv.fecha_expedicion fecha,
          (u.paterno||' '||u.materno||', '||u.name)::varchar usuario,Count(Distinct rv.nro_receta)nra,Count(pr.id)nia,Sum(pr.cantidad_dispensada)nua,
          Case When rv.tipo_beneficiario='TITULAR' Then Count(Distinct rv.dni_beneficiario) Else 0 End nta,
          Case When rv.tipo_beneficiario<>'TITULAR' Then Count(Distinct rv.dni_beneficiario) Else 0 End nfa,0 nrna,0 nina,0 nuna,0 ntna,0 nfna
          From receta_vales rv Inner Join producto_recetas pr On rv.id=pr.id_receta_vale
          Inner Join farmacias f On rv.id_farmacia=f.id
          Inner Join users u On rv.id_user=u.id
          Where rv.estado=1 And rv.fecha_expedicion Between '".$fechaDesde."' And '".$fechaHasta."' And pr.cantidad_dispensada>0
          and f.id=".$id_farmacia."
          Group By f.nombre,to_char(rv.fecha_expedicion,'yyyy'),to_char(rv.fecha_expedicion,'TMMonth'),rv.fecha_expedicion,(u.paterno||' '||u.materno||', '||u.name),
          rv.tipo_beneficiario
          Union
          Select f.nombre centro_atencion,to_char(rv.fecha_expedicion,'yyyy')::int ano,to_char(rv.fecha_expedicion,'TMMonth')::varchar mes,rv.fecha_expedicion fecha,
          (u.paterno||' '||u.materno||', '||u.name)::varchar usuario,0 nra,0 nia,0 nua,0 nta,0 nfa,Count(Distinct rv.nro_receta)nrna,Count(pr.id)nina,Sum(pr.cantidad_prescrita-pr.cantidad_dispensada)nuna,
          Case When rv.tipo_beneficiario='TITULAR' Then Count(Distinct rv.dni_beneficiario) Else 0 End ntna,Case When rv.tipo_beneficiario<>'TITULAR' Then Count(Distinct rv.dni_beneficiario) Else 0 End nfna
          From receta_vales rv Inner Join producto_recetas pr On rv.id=pr.id_receta_vale
          Inner Join farmacias f On rv.id_farmacia=f.id
          Inner Join users u On rv.id_user=u.id
          Where rv.estado=1 And rv.fecha_expedicion Between '".$fechaDesde."' And '".$fechaHasta."' And pr.cantidad_dispensada=0
          and f.id=".$id_farmacia."
          Group By f.nombre,to_char(rv.fecha_expedicion,'yyyy'),to_char(rv.fecha_expedicion,'TMMonth'),rv.fecha_expedicion,(u.paterno||' '||u.materno||', '||u.name),
          rv.tipo_beneficiario,pr.cantidad_dispensada
          )x Group By centro_atencion,ano,mes,fecha,usuario
          Order By 1,4,5";
         */

        /*
          if($dni_beneficiario):
          $cad .= " and admisiones.dni_beneficiario = '".$dni_beneficiario."'";
          endif;

          if($nro_admision):
          $cad .= " and admisiones.nro_admision = '".$nro_admision."'";
          endif;

          if($fechaDesde != "")$cad .= " and centro_quirurgicos.fecha_quirurgico >= '".$fechaDesde."'";
          if($fechaHasta != "")$cad .= " and centro_quirurgicos.fecha_quirurgico <= '".$fechaHasta."'";
         */
        /*
          $cad .= " and centro_quirurgicos.estado=1
          order By centro_quirurgicos.fecha_quirurgico desc";
         */
        //echo $cad;
        $data = DB::select($cad);
        return $data;
    }

    public function GetCountQuirurgicoByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_admision = "") {

        $cad = "select count(1) cantidad
from centro_quirurgicos
where centro_quirurgicos.id_farmacia=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and admisiones.dni_beneficiario = '" . $dni_beneficiario . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($nro_admision):
            $cad .= " and admisiones.nro_admision = '" . $nro_admision . "'";
        //$fechaDesde="";$fechaHasta="";
        endif;

        if ($fechaDesde != "")
            $cad .= " and centro_quirurgicos.fecha_quirurgico >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and centro_quirurgicos.fecha_quirurgico <= '" . $fechaHasta . "'";

        $cad .= " and centro_quirurgicos.estado=1";

        $data = DB::select($cad);
        return $data[0]->cantidad;
    }

    public function AllCitaByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_cita = "") {

        $cad = "select 
citas.*,users.*,users.paterno as paterno_usuario,users.materno as materno_usuario,citas.created_at as fec_creacion
from citas
inner join users on citas.id_user=users.id
inner join farmacias on citas.id_farmacia=farmacias.id
where citas.id_farmacia=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and citas.dni_beneficiario = '" . $dni_beneficiario . "'";
            $fechaDesde = "";
            $fechaHasta = "";
        endif;

        if ($nro_cita):
            $cad .= " and citas.nro_cita = '" . $nro_cita . "'";
            $fechaDesde = "";
            $fechaHasta = "";
        endif;

        if ($fechaDesde != "")
            $cad .= " and citas.fecha_cita >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and citas.fecha_cita <= '" . $fechaHasta . "'";

        $cad .= " and citas.estado=1
order By citas.fecha_cita desc";
        //echo $cad;
        $data = DB::select($cad);
        return $data;
    }

    public function GetCountCitaByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_cita = "") {

        $cad = "select count(1) cantidad
from citas
where citas.id_farmacia=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and citas.dni_beneficiario = '" . $dni_beneficiario . "'";
            $fechaDesde = "";
            $fechaHasta = "";
        endif;

        if ($nro_cita):
            $cad .= " and citas.nro_cita = '" . $nro_cita . "'";
            $fechaDesde = "";
            $fechaHasta = "";
        endif;

        if ($fechaDesde != "")
            $cad .= " and citas.fecha_cita >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and citas.fecha_cita <= '" . $fechaHasta . "'";

        $cad .= " and citas.estado=1";

        $data = DB::select($cad);
        return $data[0]->cantidad;
        ;
    }

    public function AllHospitalizacionByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_admision = "") {

        $cad = "select 
hospitalizaciones.*
/*,admisiones.*/
,admisiones.nro_admision,admisiones.fecha_admision,admisiones.dni_beneficiario,admisiones.paterno_beneficiario,admisiones.materno_beneficiario
,admisiones.nombre_beneficiario,admisiones.tipo_beneficiario
,users.*,users.paterno as paterno_usuario,users.materno as materno_usuario,admisiones.created_at as fec_creacion
from hospitalizaciones 
inner join admisiones on hospitalizaciones.id_admision=admisiones.id 
inner join users on admisiones.id_user=users.id
inner join farmacias on admisiones.id_farmacia=farmacias.id
where hospitalizaciones.id_farmacia=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and admisiones.dni_beneficiario = '" . $dni_beneficiario . "'";
            $fechaDesde = "";
            $fechaHasta = "";
        endif;

        if ($nro_admision):
            $cad .= " and admisiones.nro_admision = '" . $nro_admision . "'";
            $fechaDesde = "";
            $fechaHasta = "";
        endif;

        if ($fechaDesde != "")
            $cad .= " and hospitalizaciones.fecha_ingreso >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and hospitalizaciones.fecha_salida <= '" . $fechaHasta . "'";

        $cad .= " and hospitalizaciones.estado=1
order By hospitalizaciones.id desc";
        //echo $cad;
        $data = DB::select($cad);
        return $data;
    }

    public function GetCountHospitalizacionByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_admision = "") {

        $cad = "select count(1) cantidad
from hospitalizaciones
inner join admisiones on hospitalizaciones.id_admision=admisiones.id 
where hospitalizaciones.id_farmacia=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and admisiones.dni_beneficiario = '" . $dni_beneficiario . "'";
            $fechaDesde = "";
            $fechaHasta = "";
        endif;

        if ($nro_admision):
            $cad .= " and admisiones.nro_admision = '" . $nro_admision . "'";
            $fechaDesde = "";
            $fechaHasta = "";
        endif;

        if ($fechaDesde != "")
            $cad .= " and hospitalizaciones.fecha_ingreso >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and hospitalizaciones.fecha_salida <= '" . $fechaHasta . "'";

        $cad .= " and hospitalizaciones.estado=1";

        $data = DB::select($cad);
        return $data[0]->cantidad;
        ;
    }

    public function AllRecetasInvalidasByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_receta = "", $iddispensador = "", $idconsultorio = "") {
        $cad = "select receta_vales.*,receta_vales.id as id_receta,users.name nombre_usuario, users.paterno as paterno_usuario,
users.materno as materno_usuario,receta_devueltas.motivo,receta_devueltas.fecha_devolucion as fecha_creacion, c.nombre consultorio ,farmacias.id_establecimiento, c.nombre consultorio,  CASE 
        WHEN receta_vales.updated_at_cod_siteds < current_timestamp - interval '1 day' THEN 1
        ELSE 0
    END AS siteds_expirado
from receta_vales
Inner Join consultorios c On receta_vales.id_consultorio=c.id
inner join farmacias on receta_vales.id_farmacia=farmacias.id
inner join receta_devueltas on receta_vales.id=receta_devueltas.id_receta_vale
inner join users on receta_devueltas.id_user=users.id
left join emergencias on receta_vales.dni_beneficiario=emergencias.dni_beneficiario
where 	receta_vales.estado=0 
AND 	receta_vales.id_farmacia=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and receta_vales.dni_beneficiario = '" . $dni_beneficiario . "'";
            $fechaDesde = "";
            $fechaHasta = "";
        endif;

        if ($nro_receta):
            $cad .= " and receta_vales.nro_receta like '%" . $nro_receta . "%'";
            $fechaDesde = "";
            $fechaHasta = "";
        endif;

        if ($iddispensador != ""):
            $cad .= " and receta_vales.id_user = " . $iddispensador . "";
        endif;

        if($id_farmacia == 19):
            if ($idconsultorio != ""):
                if ($idconsultorio != 0):
                    $cad .= " and emergencias.id_triaje = '" . $idconsultorio . "'";
                endif;
            endif;
        else:
            if ($idconsultorio != ""):
                if ($idconsultorio != 0):
                    $cad .= " and receta_vales.id_consultorio = " . $idconsultorio . "";
                endif;
            endif;
        endif;

        if ($fechaDesde != "")
            $cad .= " and receta_vales.fecha_expedicion >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and receta_vales.fecha_expedicion <= '" . $fechaHasta . "'";

        $cad .= " group by receta_vales.id,users.id,receta_devueltas.id, c.id, farmacias.id";
        $cad .= " order By receta_vales.id desc";
        //echo $cad;
        $data = DB::select($cad);
        return $data;
    }

    public function GetCountRecetasInvalidasByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde = "", $fechaHasta = "", $dni_beneficiario = "", $nro_receta = "", $iddispensador = "", $idconsultorio = "") {

        $cad = "select count(1) cantidad
from receta_vales
inner join farmacias on receta_vales.id_farmacia=farmacias.id
inner join receta_devueltas on receta_vales.id=receta_devueltas.id_receta_vale
inner join users on receta_devueltas.id_user=users.id
left  Join emergencias on receta_vales.dni_beneficiario=emergencias.dni_beneficiario
where 	receta_vales.estado=0 
and 	receta_vales.id_farmacia=" . $id_farmacia;

        if ($dni_beneficiario):
            $cad .= " and receta_vales.dni_beneficiario = '" . $dni_beneficiario . "'";
            $fechaDesde = "";
            $fechaHasta = "";
        endif;

        if ($nro_receta):
            $cad .= " and receta_vales.nro_receta = '" . $nro_receta . "'";
            $fechaDesde = "";
            $fechaHasta = "";
        endif;

        if($id_farmacia == 19):
            if ($idconsultorio != ""):
                $cad .= " and emergencias.id_triaje = '" . $idconsultorio . "'";
            endif;
        else:
            if ($idconsultorio != ""):
                $cad .= " and receta_vales.id_consultorio = " . $idconsultorio . "";
            endif;
        endif;

        if ($iddispensador != ""):
            $cad .= " and receta_vales.id_user = " . $iddispensador . "";
        endif;

        if ($fechaDesde != "")
            $cad .= " and receta_vales.fecha_expedicion >= '" . $fechaDesde . "'";
        if ($fechaHasta != "")
            $cad .= " and receta_vales.fecha_expedicion <= '" . $fechaHasta . "'";


        $data = DB::select($cad);
        return $data[0]->cantidad;
        ;
    }

    public function AllRecetasInvalidasByFarmaAndFecha($id_farmacia, $fecha) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->join('receta_devueltas', 'receta_vales.id', '=', 'receta_devueltas.id_receta_vale')
                ->join('users', 'receta_devueltas.id_user', '=', 'users.id')
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '=', $fecha)
                ->where('receta_vales.estado', '=', 0)
                ->select('receta_vales.*', 'users.*', 'receta_vales.id as id_receta', 'users.paterno as paterno_usuario', 'users.materno as materno_usuario', 'receta_devueltas.motivo', 'receta_devueltas.fecha_devolucion as fecha_creacion')
                ->orderBy('receta_vales.id', 'desc')
                ->get();
        return $datos;
    }

    public function CreadordeRecetaQueSeInvalido($id_farmacia, $fecha) {
        $datos = DB::table('receta_vales')
                ->join('users', 'receta_vales.id_user', '=', 'users.id')
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '=', $fecha)
                ->where('receta_vales.estado', '=', 0)
                ->select('receta_vales.id as id_receta_vale', 'users.*')
                ->orderBy('receta_vales.id', 'desc')
                ->get();
        return $datos;
    }

    public function GetCountRecetasByUserAndFecha($id_farmacia, $fecha) {
        $datos = DB::table('receta_vales')
                //->where('receta_vales.id_user','=',$id_user)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '=', $fecha)
                ->where('receta_vales.estado', '=', 1)
                ->count();
        return $datos;
    }

    public function GetCountRecetasInvalidadasByUserAndFecha($id_farmacia, $fecha) {
        $datos = DB::table('receta_vales')
                //->where('receta_vales.id_user','=',$id_user)
                ->where('receta_vales.id_farmacia', '=', $id_farmacia)
                ->where('receta_vales.fecha_registro', '=', $fecha)
                ->where('receta_vales.estado', '=', 0)
                ->count();
        return $datos;
    }

    public function ObtenerDatosRecetaVale($id) {
        $datos = Receta_vale::where('id', $id)->first();
        return $datos;
    }

    public function ValidacionxBeneficiarioxDiasxProducto($dni_beneficiario, $dias, $idProducto) {
		
		if($idProducto>0 && $dni_beneficiario!=""){
        $cad = "select  T3.codigo,T3.nombre producto,T4.nombre 
				farmacia,T1.nro_receta,T1.fecha_expedicion,T1.dni_beneficiario,T1.nombre_beneficiario,T1.paterno_beneficiario,T1.materno_beneficiario
				,T2.cantidad_prescrita,T2.cantidad_dispensada,T2.dia_farmacia,T2.tiempo_periodo,(extract(day from (now()-T1.fecha_expedicion)))dias_transcurridos   
				from 	receta_vales T1
				inner join producto_recetas T2 on T1.id=T2.id_receta_vale
				inner join productos T3 on T2.id_producto =T3.id
				inner join farmacias T4 on T1.id_farmacia =T4.id
				where T1.dni_beneficiario ='" . $dni_beneficiario . "'
				and T2.id_producto = " . $idProducto . "
				/*and T2.cantidad_dispensada > 0*/
				and T2.cantidad_prescrita > 0
				and T1.estado = 1
			    and ( (extract(day from (now()-T1.fecha_expedicion))) between 0 and (tiempo_periodo - 1) or ((extract(day from (now()-T1.fecha_expedicion))) between 0 and (dia_farmacia - 1)))
";
        $data = DB::select($cad);
        return $data;
		}
		
    }

    public function ValidacionxBeneficiarioxDiasxProductoMedico($dni_beneficiario, $dias, $idProducto) {
        
        if($idProducto>0 && $dni_beneficiario!=""){
        $cad = "select  T3.codigo,T3.nombre producto,T4.nombre 
                farmacia,T1.nro_receta,T1.fecha_expedicion,T1.dni_beneficiario,T1.nombre_beneficiario,T1.paterno_beneficiario,T1.materno_beneficiario
                ,T2.cantidad_prescrita,T2.cantidad_dispensada,T2.tiempo_periodo, T2.dia_farmacia, (extract(day from (now()-T1.fecha_expedicion)))dias_transcurridos   
                from    receta_vales T1
                inner join producto_recetas T2 on T1.id=T2.id_receta_vale
                inner join productos T3 on T2.id_producto =T3.id
                inner join farmacias T4 on T1.id_farmacia =T4.id
                where T1.dni_beneficiario ='" . $dni_beneficiario . "'
                and T2.id_producto = " . $idProducto . "
                and (T2.id_observacion < 8 or T2.id_observacion = 17 or T2.id_observacion = 18)
                /*and T2.cantidad_dispensada > 0*/
                and T2.cantidad_prescrita > 0
                and T1.estado = 1
                --and (extract(day from (now()-T1.fecha_expedicion))) between 0 and (tiempo_periodo - 1)
                and ( (extract(day from (now()-T1.fecha_expedicion))) between 0 and (tiempo_periodo - 1) or ((extract(day from (now()-T1.fecha_expedicion))) between 0 and (dia_farmacia - 1)))";
        $data = DB::select($cad);
        return $data;
        }
        
    }

    public function ValidacionxBeneficiarioxDiasxProductoHospitalizacion($dni_beneficiario, $dias, $idProducto,$id_farmacia) {
        $cad = "select  T3.codigo,T3.nombre producto,T4.nombre 
                farmacia,T1.nro_receta,T1.fecha_expedicion,T1.dni_beneficiario,T1.nombre_beneficiario,T1.paterno_beneficiario,T1.materno_beneficiario
                ,T2.cantidad_prescrita,T2.cantidad_dispensada,T2.tiempo_periodo,(extract(day from (now()-T1.fecha_expedicion)))dias_transcurridos   
                from    receta_vales T1
                inner join producto_recetas T2 on T1.id=T2.id_receta_vale
                inner join productos T3 on T2.id_producto =T3.id
                inner join farmacias T4 on T1.id_farmacia =T4.id
                where T1.dni_beneficiario ='" . $dni_beneficiario . "'
                and T2.id_producto = " . $idProducto . " 
                and T4.id = " . $id_farmacia . "
                /*and T2.cantidad_dispensada > 0*/
                and T2.cantidad_prescrita > 0
                and T1.estado = 1
                and (extract(day from (now()-T1.fecha_expedicion))) between 0 and (tiempo_periodo - 1)";
        $data = DB::select($cad);
        return $data;
    }

    /*
    public function ValidacionxProductoRestringido($id_medico,$idProducto) {
        $cad = "select  T3.codigo,T3.nombre producto,T4.nombre 
                farmacia,T1.nro_receta,T1.fecha_expedicion,T1.dni_beneficiario,T1.nombre_beneficiario,T1.paterno_beneficiario,T1.materno_beneficiario
                ,T2.cantidad_prescrita,T2.cantidad_dispensada,T2.tiempo_periodo,(extract(day from (now()-T1.fecha_expedicion)))dias_transcurridos   
                from    receta_vales T1
                inner join producto_recetas T2 on T1.id=T2.id_receta_vale
                inner join productos T3 on T2.id_producto =T3.id
                inner join farmacias T4 on T1.id_farmacia =T4.id
                where T1.dni_beneficiario ='" . $dni_beneficiario . "'
                and T2.id_producto = " . $idProducto . "
                and T2.cantidad_prescrita > 0
                and T1.estado = 1
                and (extract(day from (now()-T1.fecha_expedicion))) between 0 and (tiempo_periodo - 1)";
        $data = DB::select($cad);
        return $data;
    }
    */
    public function ValidacionxBeneficiarioxDiasxProductos($dni_beneficiario, $dias, $arrayProd) {

        $cadProd = "";

        foreach ($arrayProd as $rowProd) {
            $cadProd .= "," . $rowProd;
        }

        if ($cadProd != "")
            $cadProd = substr($cadProd, 1, strlen($cadProd));
        //echo $cadProd;
        $cad = "select  T3.codigo,T3.nombre producto,T4.nombre 
				farmacia,T1.nro_receta,T1.fecha_expedicion,T1.dni_beneficiario,T1.nombre_beneficiario,T1.paterno_beneficiario,T1.materno_beneficiario
				,T2.cantidad_prescrita,T2.cantidad_dispensada,T2.tiempo_periodo,(extract(day from (now()-T1.fecha_expedicion)))dias_transcurridos   
				from 	receta_vales T1
				inner join producto_recetas T2 on T1.id=T2.id_receta_vale
				inner join productos T3 on T2.id_producto =T3.id
				inner join farmacias T4 on T1.id_farmacia =T4.id
				where T1.dni_beneficiario ='" . $dni_beneficiario . "'
				and T2.id_producto in(" . $cadProd . ")
				and T2.cantidad_dispensada > 0
				and T1.estado = 1
				and (extract(day from (now()-T1.fecha_expedicion))) between 0 and (tiempo_periodo - 1)";
        /*
          $cad = "select  T3.codigo,T3.nombre producto,T4.nombre
          farmacia,T1.nro_receta,T1.fecha_expedicion,T1.dni_beneficiario,T1.nombre_beneficiario,T1.paterno_beneficiario,T1.materno_beneficiario
          from 	receta_vales T1
          inner join producto_recetas T2 on T1.id=T2.id_receta_vale
          inner join productos T3 on T2.id_producto =T3.id
          inner join farmacias T4 on T1.id_farmacia =T4.id
          where T1.dni_beneficiario ='09327397'
          and T2.id_producto in(10858,10675,10952,11078,11122)
          and T2.cantidad_dispensada > 0
          and T1.estado = 1
          and extract(day from (now()-T1.fecha_expedicion)) between 0 and 30";
         */
        $data = DB::select($cad);
        return $data;
    }

    /*public function AllRecetasByDniAndFechas($dni_beneficiario, $fecha_inicio, $fecha_fin, $id_farmacia = "") {
        $cad = "Select  T3.codigo,T3.nombre producto,T4.nombre farmacia,
T2.cantidad_dispensada,T1.nro_receta,T1.fecha_expedicion,T1.dni_beneficiario,T1.nombre_beneficiario,T1.paterno_beneficiario,T1.materno_beneficiario
,(Select avg(kl.precio_unitario) precio_unitario From kardexes k Inner Join kardex_lotes kl on k.id=kl.id_kardex
Inner Join receta_vales r On k.id_nro_movimiento=r.id Where k.id_producto=T2.id_producto And k.id_tipo_movimiento=4 And k.id_nro_movimiento=T1.id)precio_unitario 
From receta_vales T1
Inner Join producto_recetas T2 On T1.id=T2.id_receta_vale
Inner Join productos T3 On T2.id_producto =T3.id
Inner Join farmacias T4 On T1.id_farmacia =T4.id
Where T1.dni_beneficiario ='" . $dni_beneficiario . "'
And T2.cantidad_dispensada > 0
And T1.estado = 1
And T1.fecha_expedicion>='" . $fecha_inicio . "'
And T1.fecha_expedicion<='" . $fecha_fin . "'";
        if ($id_farmacia != "")
            $cad .= " and T1.id_farmacia=" . $id_farmacia;

        $data = DB::select($cad);
        return $data;
    }*/
public function AllRecetasByDniAndFechas($dni_beneficiario, $fecha_inicio, $fecha_fin, $id_farmacia = "") {
        $cad = "Select  T3.codigo,T3.nombre producto,T4.nombre farmacia,
T2.cantidad_dispensada,T1.nro_receta,T1.fecha_expedicion,T1.dni_beneficiario,T1.nombre_beneficiario,T1.paterno_beneficiario,T1.materno_beneficiario
,T3.precio_trama precio_unitario 
From receta_vales T1
Inner Join producto_recetas T2 On T1.id=T2.id_receta_vale
Inner Join productos T3 On T2.id_producto =T3.id
Inner Join farmacias T4 On T1.id_farmacia =T4.id
Where T1.dni_beneficiario ='" . $dni_beneficiario . "'
And T2.cantidad_dispensada > 0
And T1.estado = 1
And T1.fecha_expedicion>='" . $fecha_inicio . "'
And T1.fecha_expedicion<='" . $fecha_fin . "'";
        if ($id_farmacia != "")
            $cad .= " and T1.id_farmacia=" . $id_farmacia;

        $data = DB::select($cad);
        return $data;
    }
    //public function AllRecetasByDniAndFechasGroup($dni_beneficiario, $fecha_inicio, $fecha_fin, $id_farmacia = "") {
       /* $cad = "select count(1)cantidad_recetas,T3.codigo,T3.nombre producto,SUM(T2.cantidad_dispensada)cantidad_dispensada
				,(select precio_unitario from kardexes where codigo_producto = T3.codigo and precio_unitario > 0 limit 1)precio_unitario 
				from 	receta_vales T1
				inner join producto_recetas T2 on T1.id=T2.id_receta_vale
				inner join productos T3 on T2.id_producto =T3.id
				--inner join farmacias T4 on T1.id_farmacia =T4.id
				where T1.dni_beneficiario ='" . $dni_beneficiario . "'
				and T2.cantidad_dispensada > 0
				and T1.estado = 1
				and T1.fecha_expedicion>='" . $fecha_inicio . "'
				and T1.fecha_expedicion<='" . $fecha_fin . "'"; //Wilian
        $cad .= " group by T3.codigo,T3.nombre";*/
        
      /*  $cad = "Select count(1)cantidad_recetas,T3.codigo,T3.nombre producto,SUM(T2.cantidad_dispensada)cantidad_dispensada
,(Select avg(kl.precio_unitario) precio_unitario From kardexes k Inner Join kardex_lotes kl on k.id=kl.id_kardex
Inner Join receta_vales r On k.id_nro_movimiento=r.id Where k.id_producto=T2.id_producto)precio_unitario 
From receta_vales T1
Inner join producto_recetas T2 on T1.id=T2.id_receta_vale
Inner join productos T3 on T2.id_producto =T3.id
Where T1.dni_beneficiario ='" . $dni_beneficiario . "'
And T2.cantidad_dispensada > 0
And T1.estado = 1
And T1.fecha_expedicion>='" . $fecha_inicio . "'
And T1.fecha_expedicion<='" . $fecha_fin . "'";
        if ($id_farmacia != "")
            $cad .= " and T1.id_farmacia=" . $id_farmacia;
        $cad .= " Group by T2.id_producto,T3.codigo,T3.nombre";

        $data = DB::select($cad);
        return $data;
    }
    */

    public function AllRecetasByDniAndFechasGroup($dni_beneficiario, $fecha_inicio, $fecha_fin, $id_farmacia = "") {
        /* $cad = "select count(1)cantidad_recetas,T3.codigo,T3.nombre producto,SUM(T2.cantidad_dispensada)cantidad_dispensada
                 ,(select precio_unitario from kardexes where codigo_producto = T3.codigo and precio_unitario > 0 limit 1)precio_unitario 
                 from 	receta_vales T1
                 inner join producto_recetas T2 on T1.id=T2.id_receta_vale
                 inner join productos T3 on T2.id_producto =T3.id
                 --inner join farmacias T4 on T1.id_farmacia =T4.id
                 where T1.dni_beneficiario ='" . $dni_beneficiario . "'
                 and T2.cantidad_dispensada > 0
                 and T1.estado = 1
                 and T1.fecha_expedicion>='" . $fecha_inicio . "'
                 and T1.fecha_expedicion<='" . $fecha_fin . "'"; //Wilian
         $cad .= " group by T3.codigo,T3.nombre";*/
         
         $cad = "Select count(1)cantidad_recetas,T3.codigo,T3.nombre producto,SUM(T2.cantidad_dispensada)cantidad_dispensada
 ,T3.precio_trama precio_unitario 
 From receta_vales T1
 Inner join producto_recetas T2 on T1.id=T2.id_receta_vale
 Inner join productos T3 on T2.id_producto =T3.id
 Where T1.dni_beneficiario ='" . $dni_beneficiario . "'
 And T2.cantidad_dispensada > 0
 And T1.estado = 1
 And T1.fecha_expedicion>='" . $fecha_inicio . "'
 And T1.fecha_expedicion<='" . $fecha_fin . "'";
         if ($id_farmacia != "")
             $cad .= " and T1.id_farmacia=" . $id_farmacia;
         $cad .= " Group by T2.id_producto,T3.codigo,T3.nombre, T3.precio_trama";
 
         $data = DB::select($cad);
         return $data;
     }

    public function AllRecetasByFarmaciaAndFechas($id_farmacia, $fecha_inicio, $fecha_fin) {
        $cad = "select  T4.nombre farmacia
,T1.nro_receta,T1.fecha_expedicion,T1.dni_beneficiario,T1.nombre_beneficiario,T1.paterno_beneficiario,T1.materno_beneficiario
,T5.nombre servicio,tipo_beneficiario,sexoafiliado,edadafiliado

/*
,(select SUM(cantidad_dispensada 
*(select precio_unitario from kardexes where codigo_producto = TT2.codigo and precio_unitario > 0 limit 1))
from producto_recetas TT1
inner join productos TT2 on TT1.id_producto =TT2.id
where id_receta_vale=T1.id and cantidad_dispensada > 0
)importe
*/
from 	receta_vales T1
inner join farmacias T4 on T1.id_farmacia =T4.id
inner join consultorios T5 on T1.id_consultorio =T5.id

inner join (
Select numerodedocumentodelafiliado,sexoafiliado,edadafiliado From dblink ('dbname=" . env('DB_DATABASE_DBLINK_BENEFICIARIOS') . " port=5432 host=" . env('DB_HOST_DBLINK_BENEFICIARIOS') . " user=" . env('DB_USERNAME_DBLINK_BENEFICIARIOS') . " password=" . env('DB_PASSWORD_DBLINK_BENEFICIARIOS') . "',
'Select a.numerodedocumentodelafiliado,se.id_tipo sexoafiliado,date_part(''year'',age(to_date(a.fechanacimiento,''YYYYMMDD'')))::Int edadafiliado
From aprobadossusalud2016 a Inner Join aprobadossusaludns2016 ea On a.id=ea.id_beneficiario And a.validacionreniec Not In(''e'',''t'') And a.valid_dj=''''
Inner Join aprobadossusalud2016 t On ea.id_titular=t.id And t.validacionreniec Not In(''e'',''t'') And t.valid_dj='''' And ea.valid_dj=''''
Inner Join tablatipo se On a.sexo=se.val_abr And se.id_tabla=''13''
Where a.tipodedocumentodelafiliado=''1''
/*And a.numerodedocumentodelafiliado=''43051638''*/
And ea.estadodeafiliado=''1''
And (ea.fechadefindeafiliacion='''' Or ea.fechadefindeafiliacion Is Null)' )ret
(numerodedocumentodelafiliado varchar,sexoafiliado varchar,edadafiliado integer) 
)R on T1.dni_beneficiario = R.numerodedocumentodelafiliado

				where 1 = 1
				and T1.id_farmacia ='" . $id_farmacia . "'
				and T1.estado = 1
				and T1.fecha_expedicion>='" . $fecha_inicio . "'
				and T1.fecha_expedicion<='" . $fecha_fin . "' ";

        $data = DB::select($cad);
        return $data;
    }

    public static function ShowRecetasByProductoAndFechas($id_farmacia, $id_producto, $fecha_inicio, $fecha_fin) {
        $data = DB::select("select * From sp_report_prescriptionbyproduct(" . $id_farmacia . "," . $id_producto . ",'" . $fecha_inicio . "','" . $fecha_fin . "');");
        return $data;
    }

    public static function ShowbyDniBeneficiario($dni) {

        /* $cad = "Select sexoafiliado,edadafiliado From dblink ('dbname=".env('DB_DATABASE_DBLINK_BENEFICIARIOS')." port=5432 host=".env('DB_HOST_DBLINK_BENEFICIARIOS')." user=".env('DB_USERNAME_DBLINK_BENEFICIARIOS')." password=".env('DB_PASSWORD_DBLINK_BENEFICIARIOS')."','Select sexoafiliado,edadafiliado From sp_consult_beneficiaries (''1'',''".$dni."'')')ret
          (sexoafiliado varchar,edadafiliado integer)"; */

        $sql = "Select se.id_tipo sexoafiliado,date_part(''year'',age(to_date(a.fechanacimiento,''YYYYMMDD'')))::Int edadafiliado
From aprobadossusalud2016 a Inner Join aprobadossusaludns2016 ea On a.id=ea.id_beneficiario And a.validacionreniec Not In(''e'',''t'') And a.valid_dj=''''
Inner Join aprobadossusalud2016 t On ea.id_titular=t.id And t.validacionreniec Not In(''e'',''t'') And t.valid_dj='''' And ea.valid_dj=''''
Inner Join tablatipo se On a.sexo=se.val_abr And se.id_tabla=''13''
Where a.tipodedocumentodelafiliado=''1''
And a.numerodedocumentodelafiliado=''" . $dni . "''
And ea.estadodeafiliado=''1''
And (ea.fechadefindeafiliacion='''' Or ea.fechadefindeafiliacion Is Null) ";

        $cad = "Select sexoafiliado,edadafiliado From dblink ('dbname=" . env('DB_DATABASE_DBLINK_BENEFICIARIOS') . " port=5432 host=" . env('DB_HOST_DBLINK_BENEFICIARIOS') . " user=" . env('DB_USERNAME_DBLINK_BENEFICIARIOS') . " password=" . env('DB_PASSWORD_DBLINK_BENEFICIARIOS') . "','" . $sql . "')ret
			(sexoafiliado varchar,edadafiliado integer)";


        $data = DB::select($cad);
        return $data;
    }

    public function ShowByDNI($dni_beneficiario) {
        $cad = "select
				distinct tipodoc_beneficiario, dni_beneficiario,nombre_beneficiario,paterno_beneficiario,materno_beneficiario,tipo_beneficiario
				from receta_vales
				inner join farmacias on receta_vales.id_farmacia=farmacias.id
				inner join consultorios on receta_vales.id_consultorio=consultorios.id
				inner join medicos on receta_vales.id_medico=medicos.id
				Left join diagnosticos on receta_vales.id_diagnostico=diagnosticos.id
				inner join establecimientos on farmacias.id_establecimiento=establecimientos.id
				where receta_vales.dni_beneficiario = '" . $dni_beneficiario . "'
				and receta_vales.estado=1";

        $data = DB::select($cad);
        return $data;
    }

    public function getAdmisionByNroAdmisionAndId_farmacia($nro_admision, $id_farmacia) {
        $cad = "select 
admisiones.*,establecimientos.nombre as ipress,establecimientos.codigo as codigoipress,consultorios.nombre as consultorio,especializaciones.nombre as especializacion,
medicos.nombre as nombre_medico,medicos.paterno as paterno_medico, medicos.materno as materno_medico, medicos.dni as dni_medico, 
users.name as nombre_usuario,users.paterno as paterno_usuario,
users.materno as materno_usuario,admisiones.created_at as fecha_creacion
from admisiones
inner join farmacias on admisiones.id_farmacia=farmacias.id
inner join users on admisiones.id_user=users.id
inner join consultorios on admisiones.id_consultorio=consultorios.id
inner join medicos on admisiones.id_medico=medicos.id
inner join especializaciones on medicos.id_especializacion=especializaciones.id
inner join establecimientos on farmacias.id_establecimiento=establecimientos.id
where admisiones.nro_admision='" . $nro_admision . "'
and admisiones.id_farmacia=" . $id_farmacia . "
and admisiones.estado=1";

        $data = DB::select($cad);
        return $data;
    }

    public function getQuirurgicoByNroQuirurgicoAndId_farmacia($nro_quirurgico, $id_farmacia) {
        $cad = "select 
centro_quirurgicos.*
,admisiones,dni_beneficiario,admisiones,nombre_beneficiario,admisiones,paterno_beneficiario,admisiones,materno_beneficiario
,admisiones,tipo_beneficiario,admisiones,nro_historia
,consultorios.nombre as consultorio,

m1.nombre as nombre_medico_cirujano,m1.paterno as paterno_medico_cirujano,m1.materno as materno_medico_cirujano,m1.dni as dni_medico_cirujano, 
m2.nombre as nombre_medico_anestesiologo,m2.paterno as paterno_medico_anestesiologo,m2.materno as materno_medico_anestesiologo,m2.dni as dni_medico_anestesiologo, 
m3.nombre as nombre_medico_cirujano_ayudante,m3.paterno as paterno_medico_cirujano_ayudante,m3.materno as materno_medico_cirujano_ayudante,m3.dni as dni_medico_cirujano_ayudante, 
m4.nombre as nombre_enfermera_1,m4.paterno as paterno_enfermera_1,m4.materno as materno_enfermera_1,m4.dni as dni_enfermera_1, 
m5.nombre as nombre_enfermera_2,m5.paterno as paterno_enfermera_2,m5.materno as materno_enfermera_2,m5.dni as dni_enfermera_2, 

users.name as nombre_usuario,users.paterno as paterno_usuario,
users.materno as materno_usuario,admisiones.created_at as fecha_creacion

,diagnosticos.nombre as diagnostico
,procedimientos.descripcion as procedimiento
,origen_pacientes.nombre as origen_paciente
,sala_quirurgicas.nombre as sala

,EXTRACT(YEAR FROM age(now(),admisiones.fecha_nacimiento_beneficiario))edad 

from centro_quirurgicos
left join admisiones on centro_quirurgicos.id_admision=admisiones.id 
inner join farmacias on admisiones.id_farmacia=farmacias.id
inner join users on admisiones.id_user=users.id
inner join consultorios on centro_quirurgicos.id_consultorio=consultorios.id
left join diagnosticos on centro_quirurgicos.id_diagnostico=diagnosticos.id
left join procedimientos on centro_quirurgicos.id_procedimiento=procedimientos.id
left join origen_pacientes on centro_quirurgicos.id_origen_paciente=origen_pacientes.id
left join sala_quirurgicas on centro_quirurgicos.id_sala=sala_quirurgicas.id
inner join medicos m1 on centro_quirurgicos.id_medico_cirujano=m1.id
inner join medicos m2 on centro_quirurgicos.id_medico_anestesiologo=m2.id
inner join medicos m3 on centro_quirurgicos.id_medico_cirujano_ayudante=m3.id
inner join medicos m4 on centro_quirurgicos.id_enfermera_1=m4.id
inner join medicos m5 on centro_quirurgicos.id_enfermera_2=m5.id
where centro_quirurgicos.nro_quirurgico='" . $nro_quirurgico . "'
and centro_quirurgicos.id_farmacia=" . $id_farmacia . "
and centro_quirurgicos.estado=1";

        $data = DB::select($cad);
        return $data;
    }

    public function getCitaByNroCitaAndId_farmacia($nro_cita, $id_farmacia) {
        $cad = "select 
citas.*,establecimientos.nombre as ipress,establecimientos.codigo as codigoipress,consultorios.nombre as consultorio,especializaciones.nombre as especializacion,
medicos.nombre as nombre_medico,medicos.paterno as paterno_medico, medicos.materno as materno_medico, medicos.dni as dni_medico, 
users.name as nombre_usuario,users.paterno as paterno_usuario,
users.materno as materno_usuario,citas.created_at as fecha_creacion
from citas
inner join farmacias on citas.id_farmacia=farmacias.id
inner join users on citas.id_user=users.id
inner join consultorios on citas.id_consultorio=consultorios.id
inner join medicos on citas.id_medico=medicos.id
inner join especializaciones on medicos.id_especializacion=especializaciones.id
inner join establecimientos on farmacias.id_establecimiento=establecimientos.id
where citas.nro_cita='" . $nro_cita . "'
and citas.id_farmacia=" . $id_farmacia . "
and citas.estado=1";

        $data = DB::select($cad);
        return $data;
    }

    public function getHospitalizacionByNroHospitalizacionAndId_farmacia($nro_hospitalizacion, $id_farmacia) {
        $cad = "select 
T2.*,T8.nombre as ipress,T8.codigo as codigoipress,T5.nombre as consultorio,T7.nombre as especializacion,
T6.nombre as nombre_medico,T6.paterno as paterno_medico, T6.materno as materno_medico, T6.dni as dni_medico, 
T4.name as nombre_usuario,T4.paterno as paterno_usuario,
T4.materno as materno_usuario,T2.created_at as fecha_creacion
,fecha_ingreso,fecha_salida
,T9.nombre as nombre_medico_hospitalizacion,T9.paterno as paterno_medico_hospitalizacion, T9.materno as materno_medico_hospitalizacion, T9.dni as dni_medico_hospitalizacion
,T10.nombre cama,T11.nombre cuarto,T13.nombre consultorio_hospitalizacion,T14.nombre piso,T15.nombre pabellon
from hospitalizaciones T1
inner join admisiones T2 on T1.id_admision = T2.id
inner join farmacias T3 on T2.id_farmacia=T3.id
inner join users T4 on T2.id_user=T4.id
inner join consultorios T5 on T2.id_consultorio=T5.id
inner join medicos T6 on T2.id_medico=T6.id
inner join especializaciones T7 on T6.id_especializacion=T7.id
inner join establecimientos T8 on T3.id_establecimiento=T8.id

inner join medicos T9 on T1.id_medico=T9.id
inner join camas T10 on T1.id_cama=T10.id
inner join cuartos T11 on T1.id_cuarto=T11.id
inner join asignacion_servicios T12 on T11.id_asignacion_servicio = T12.id
inner join consultorios T13 on T12.id_consultorio=T13.id 
inner join pisos T14 on T12.id_piso=T14.id
inner join pabellones T15 on T14.id_pabellon=T15.id

				
where T1.nro_hospitalizacion='" . $nro_hospitalizacion . "'
and T1.id_farmacia=" . $id_farmacia . "
and T1.estado=1";

        $data = DB::select($cad);
        return $data;
    }

/*public function getHospitalizacionById_farmacia($id_hospitalizacion, $id_establecimiento) {
        $cad = "select 
T1.*,T8.nombre as ipress,T8.codigo as codigoipress,T5.nombre as consultorio,T7.nombre as especializacion,
T6.nombre as nombre_medico,T6.paterno as paterno_medico, T6.materno as materno_medico, T6.dni as dni_medico, 
T4.name as nombre_usuario,T4.paterno as paterno_usuario,
T4.materno as materno_usuario,T1.created_at as fecha_creacion
,fecha_ingreso,fecha_salida
,T9.nombre as nombre_medico_hospitalizacion,T9.paterno as paterno_medico_hospitalizacion, T9.materno as materno_medico_hospitalizacion, T9.dni as dni_medico_hospitalizacion
,T10.nombre cama,T11.nombre cuarto,T13.nombre consultorio_hospitalizacion,T14.nombre piso,T15.nombre pabellon, T16.nombre as nombre_diagnostico, T16.codigo as cie
from hospitalizaciones T1
inner join farmacias T3 on T1.id_farmacia=T3.id
inner join users T4 on T1.id_user=T4.id
inner join consultorios T5 on T1.id_consultorio=T5.id
inner join medicos T6 on T1.id_medico=T6.id
inner join especializaciones T7 on T6.id_especializacion=T7.id
inner join establecimientos T8 on T3.id_establecimiento=T8.id
left join diagnosticos T16 on T1.id_diagnostico_inicial=T16.id
inner join medicos T9 on T1.id_medico=T9.id
inner join camas T10 on T1.id_cama=T10.id
inner join cuartos T11 on T1.id_cuarto=T11.id
inner join asignacion_servicios T12 on T11.id_asignacion_servicio = T12.id
inner join consultorios T13 on T12.id_consultorio=T13.id 
inner join pisos T14 on T12.id_piso=T14.id
inner join pabellones T15 on T14.id_pabellon=T15.id                
where T1.id='" . $id_hospitalizacion . "'
and tipo_hospitalizacion='HO'
and T3.id_establecimiento=" . $id_establecimiento;

        
        $data = DB::select($cad);
        return $data;
    }
*/
public function getHospitalizacionById_farmacia($id_hospitalizacion, $id_farmacia) {
        $cad = "select 
T1.*,T8.nombre as ipress,T8.codigo as codigoipress,T5.nombre as consultorio,T7.nombre as especializacion,
T6.nombre as nombre_medico,T6.paterno as paterno_medico, T6.materno as materno_medico, T6.dni as dni_medico, 
T4.name as nombre_usuario,T4.paterno as paterno_usuario,
T4.materno as materno_usuario,T1.created_at as fecha_creacion
,fecha_ingreso,fecha_salida
,T9.nombre as nombre_medico_hospitalizacion,T9.paterno as paterno_medico_hospitalizacion, T9.materno as materno_medico_hospitalizacion, T9.dni as dni_medico_hospitalizacion
,T10.nombre cama,T11.nombre cuarto,T13.nombre consultorio_hospitalizacion,T14.nombre piso,T15.nombre pabellon, T16.nombre as nombre_diagnostico, T16.codigo as cie
from hospitalizaciones T1
inner join farmacias T3 on T1.id_farmacia=T3.id
inner join users T4 on T1.id_user=T4.id
inner join consultorios T5 on T1.id_consultorio=T5.id
inner join medicos T6 on T1.id_medico=T6.id
inner join especializaciones T7 on T6.id_especializacion=T7.id
inner join establecimientos T8 on T3.id_establecimiento=T8.id
left join diagnosticos T16 on T1.id_diagnostico_inicial=T16.id
inner join medicos T9 on T1.id_medico=T9.id
inner join camas T10 on T1.id_cama=T10.id
inner join cuartos T11 on T1.id_cuarto=T11.id
inner join asignacion_servicios T12 on T11.id_asignacion_servicio = T12.id
inner join consultorios T13 on T12.id_consultorio=T13.id 
inner join pisos T14 on T12.id_piso=T14.id
inner join pabellones T15 on T14.id_pabellon=T15.id                
where T1.id='" . $id_hospitalizacion . "'
and tipo_hospitalizacion='HO'";
/*and T1.id_farmacia=" . $id_farmacia; */
        $data = DB::select($cad);
        return $data;
    }

public function getHospitalizacionById_farmaciaHemodialisis($id_hospitalizacion, $id_farmacia) {
        $cad = "select 
T1.*,T8.nombre as ipress,T8.codigo as codigoipress,T5.nombre as consultorio,T7.nombre as especializacion,
T6.nombre as nombre_medico,T6.paterno as paterno_medico, T6.materno as materno_medico, T6.dni as dni_medico, 
T4.name as nombre_usuario,T4.paterno as paterno_usuario,
T4.materno as materno_usuario,T1.created_at as fecha_creacion
,fecha_ingreso,fecha_salida
,T9.nombre as nombre_medico_hospitalizacion,T9.paterno as paterno_medico_hospitalizacion, T9.materno as materno_medico_hospitalizacion, T9.dni as dni_medico_hospitalizacion,T16.nombre as nombre_diagnostico, T16.codigo as cie
from hospitalizaciones T1
inner join farmacias T3 on T1.id_farmacia=T3.id
inner join users T4 on T1.id_user=T4.id
inner join consultorios T5 on T1.id_consultorio=T5.id
inner join medicos T6 on T1.id_medico=T6.id
inner join especializaciones T7 on T6.id_especializacion=T7.id
inner join establecimientos T8 on T3.id_establecimiento=T8.id
left join diagnosticos T16 on T1.id_diagnostico_inicial=T16.id
inner join medicos T9 on T1.id_medico=T9.id
where T1.id='" . $id_hospitalizacion . "'
and tipo_hospitalizacion='HE'
and T3.id_establecimiento=" . $id_ipress;
        
        $data = DB::select($cad);
        

        return $data;
    }

public function getHospitalizacionById_farmaciaAltaHemodialisis($id_hospitalizacion, $id_ipress) {
        $cad = "select 
T1.*,T8.nombre as ipress,T8.codigo as codigoipress,T5.nombre as consultorio,T7.nombre as especializacion,
T6.nombre as nombre_medico,T6.paterno as paterno_medico, T6.materno as materno_medico, T6.dni as dni_medico, 
T4.name as nombre_usuario,T4.paterno as paterno_usuario,
T4.materno as materno_usuario,T1.created_at as fecha_creacion
,fecha_ingreso,fecha_salida
,T9.nombre as nombre_medico_hospitalizacion,T9.paterno as paterno_medico_hospitalizacion, T9.materno as materno_medico_hospitalizacion, T9.dni as dni_medico_hospitalizacion,T16.nombre as nombre_diagnostico, T16.codigo as cie
from hospitalizaciones T1
inner join farmacias T3 on T1.id_farmacia=T3.id
inner join users T4 on T1.id_user=T4.id
inner join consultorios T5 on T1.id_consultorio=T5.id
inner join medicos T6 on T1.id_medico=T6.id
inner join especializaciones T7 on T6.id_especializacion=T7.id
inner join establecimientos T8 on T3.id_establecimiento=T8.id
left join diagnosticos T16 on T1.id_diagnostico_inicial=T16.id
inner join medicos T9 on T1.id_medico=T9.id
where T1.id='" . $id_hospitalizacion . "'
and tipo_hospitalizacion='HE'
and T3.id_establecimiento=" . $id_ipress;

        
        $data = DB::select($cad);
        

        return $data;
    }

public function getHospitalizacionById_farmaciaAlta($id_hospitalizacion, $id_farmacia) {
        $cad = "select 
T1.*,T8.nombre as ipress,T8.codigo as codigoipress,T5.nombre as consultorio,T7.nombre as especializacion,
T6.nombre as nombre_medico,T6.paterno as paterno_medico, T6.materno as materno_medico, T6.dni as dni_medico, 
T4.name as nombre_usuario,T4.paterno as paterno_usuario,
T4.materno as materno_usuario,T1.created_at as fecha_creacion
,fecha_ingreso,fecha_salida
,T9.nombre as nombre_medico_hospitalizacion,T9.paterno as paterno_medico_hospitalizacion, T9.materno as materno_medico_hospitalizacion, T9.dni as dni_medico_hospitalizacion
,T10.nombre cama,T11.nombre cuarto,T13.nombre consultorio_hospitalizacion,T14.nombre piso,T15.nombre pabellon, T16.nombre as nombre_diagnostico, T16.codigo as cie
from hospitalizaciones T1
inner join farmacias T3 on T1.id_farmacia=T3.id
inner join users T4 on T1.id_user=T4.id
inner join consultorios T5 on T1.id_consultorio=T5.id
inner join medicos T6 on T1.id_medico_alta=T6.id
inner join especializaciones T7 on T6.id_especializacion=T7.id
inner join establecimientos T8 on T3.id_establecimiento=T8.id
left join diagnosticos T16 on T1.id_diagnostico_final=T16.id
inner join medicos T9 on T1.id_medico=T9.id
inner join camas T10 on T1.id_cama=T10.id
inner join cuartos T11 on T1.id_cuarto=T11.id
inner join asignacion_servicios T12 on T11.id_asignacion_servicio = T12.id
inner join consultorios T13 on T12.id_consultorio=T13.id 
inner join pisos T14 on T12.id_piso=T14.id
inner join pabellones T15 on T14.id_pabellon=T15.id                
where T1.id='" . $id_hospitalizacion . "'
and T1.id_farmacia=" . $id_farmacia;

        
        $data = DB::select($cad);
        return $data;
    }

    public function getHospitalizacionAltaById_farmacia($id_hospitalizacion, $id_ipress) {

        $cad = "select 
T1.*,T8.nombre as ipress,T8.codigo as codigoipress,T5.nombre as consultorio,T7.nombre as especializacion,
T6.nombre as nombre_medico,T6.paterno as paterno_medico, T6.materno as materno_medico, T6.dni as dni_medico, 
T4.name as nombre_usuario,T4.paterno as paterno_usuario,
T4.materno as materno_usuario,T1.created_at as fecha_creacion,fecha_ingreso,fecha_salida,T16.nombre as nombre_diagnostico, T16.codigo as cie, T17.nombre as nombre_diagnostico2, T16.codigo as cie2,T9.nombre as nombre_medico_hospitalizacion,T9.paterno as paterno_medico_hospitalizacion, T9.materno as materno_medico_hospitalizacion, T9.dni as dni_medico_hospitalizacion
,T10.nombre cama,T11.nombre cuarto,T13.nombre consultorio_hospitalizacion,T14.nombre piso,T15.nombre pabellon
from hospitalizaciones T1
inner join farmacias T3 on T1.id_farmacia=T3.id
inner join users T4 on T1.id_user_alta=T4.id
inner join consultorios T5 on T1.id_consultorio=T5.id
inner join medicos T6 on T1.id_medico_alta=T6.id
inner join especializaciones T7 on T6.id_especializacion=T7.id
inner join establecimientos T8 on T3.id_establecimiento=T8.id
left join diagnosticos T16 on T1.id_diagnostico_inicial=T16.id
left join diagnosticos T17 on T1.id_diagnostico_final=T17.id
inner join medicos T9 on T1.id_medico_alta=T9.id
inner join camas T10 on T1.id_cama=T10.id
inner join cuartos T11 on T1.id_cuarto=T11.id
inner join asignacion_servicios T12 on T11.id_asignacion_servicio = T12.id
inner join consultorios T13 on T12.id_consultorio=T13.id 
inner join pisos T14 on T12.id_piso=T14.id
inner join pabellones T15 on T14.id_pabellon=T15.id                
where T1.id='" . $id_hospitalizacion . "'
and T3.id_establecimiento=" . $id_ipress . "
and tipo_hospitalizacion='HO'
and T1.estado=2";

        $data = DB::select($cad);
        return $data;
    }


    function getFechaServidor() {

        $cad = "SELECT now()::date fechaservidor";
        $data = DB::select($cad);
        return $data[0]->fechaservidor;
    }

    function getAnoServidor() {

        $cad = "SELECT (extract(year from now())::text) fechaservidor";
        $data = DB::select($cad);
        return $data[0]->fechaservidor;
    }

    public static function GetExisteRecetaValeByIdConsultorio($id_servicio = 0) {
        // exit("Hola")
        $data = Receta_vale::where('id_consultorio', '=', $id_servicio)->where('estado', '=', '1')->first();
        return isset($data) ? true : false;
    }
    
	public static function postRegistrarRecetaVale($id_receta_vale, $id_farmacia, $id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipdoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular,$nombre_titular, $paterno_titular, $materno_titular, $fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $tipo_receta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss) {
		$cad = "Select sp_insert_recetavale(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$data = DB::select($cad, array($id_receta_vale, $id_farmacia, $id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipdoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular,$nombre_titular, $paterno_titular, $materno_titular, $fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $tipo_receta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss));//43 parametros
		//print_r($data);exit();
        return $data[0]->sp_insert_recetavale;
    }

    public static function postRegistrarRecetaValeNuevoConDiagnostico($id_receta_vale, $id_farmacia, $id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipdoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular,$nombre_titular, $paterno_titular, $materno_titular, $fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $tipo_receta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss) {
		$cad = "Select sp_insert_recetavalemodulorecetas(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$data = DB::select($cad, array($id_receta_vale, $id_farmacia, $id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipdoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular,$nombre_titular, $paterno_titular, $materno_titular, $fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $tipo_receta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss));//43 parametros
		//print_r($data);exit();
      
        return $data[0]->sp_insert_recetavalemodulorecetas;
    }

    public static function postRegistrarRecetaValeAltas($id_receta_vale, $id_farmacia, $id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipdoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular,$nombre_titular, $paterno_titular, $materno_titular, $fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $tipo_receta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss,$flag_origen) {
		$cad = "Select sp_insert_recetavalemodulorecetas_altas(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$data = DB::select($cad, array($id_receta_vale, $id_farmacia, $id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipdoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular,$nombre_titular, $paterno_titular, $materno_titular, $fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $tipo_receta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss,$flag_origen));//44 parametros
		//print_r($data);exit();
      
        return $data[0]->sp_insert_recetavalemodulorecetas_altas;
    }

    public static function postRegistrarRecetaValeNuevoConDiagnosticoEvaluacion($id_receta_vale, $id_farmacia, $id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipdoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular,$nombre_titular, $paterno_titular, $materno_titular, $fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $tipo_receta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss,$id_evaluacion) {
        $cad = "Select sp_insert_recetavalemodulorecetas_evaluacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_receta_vale, $id_farmacia, $id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipdoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular,$nombre_titular, $paterno_titular, $materno_titular, $fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $tipo_receta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss,$id_evaluacion));//44 parametros
        //print_r($data);exit();
      
        return $data[0]->sp_insert_recetavalemodulorecetas_evaluacion;
    }

    public static function postRegistrarRecetaValeNuevoConDiagnosticoEvaluacionAltas($id_receta_vale, $id_farmacia, $id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipdoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular,$nombre_titular, $paterno_titular, $materno_titular, $fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $tipo_receta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss,$id_evaluacion,$flag_origen) {
        $cad = "Select sp_insert_recetavalemodulorecetas_evaluacion_altas(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_receta_vale, $id_farmacia, $id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipdoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular,$nombre_titular, $paterno_titular, $materno_titular, $fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $tipo_receta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss,$id_evaluacion,$flag_origen));//45 parametros
        //print_r($data);exit();
      
        return $data[0]->sp_insert_recetavalemodulorecetas_evaluacion_altas;
    }

    /**
     * Registrar Receta desde una Interconsulta, copia de "postRegistrarRecetaValeNuevoConDiagnosticoEvaluacion" con parametro adicional
     */
    public static function postRegistrarRecetaValeNuevoConDiagnosticoInterconsulta($id_receta_vale, $id_farmacia, $id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipdoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular,$nombre_titular, $paterno_titular, $materno_titular, $fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $tipo_receta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss,$id_evaluacion, $id_interconsulta) {
        $cad = "Select sp_insert_receta_vale_interconsulta(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_receta_vale, $id_farmacia, $id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipdoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular,$nombre_titular, $paterno_titular, $materno_titular, $fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $tipo_receta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision,$tipo_via,$sub_via,$final_via,$nro_guia,$interconsulta,$interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss,$id_evaluacion, $id_interconsulta));//45 parametros
        //print_r($data);exit();
      
        return $data[0]->sp_insert_receta_vale_interconsulta;
    }
	
	public function GetRecetaByFarmaciaAndNroReceta($id_farmacia, $nro_receta) {
        $cad = "select t1.*,t2.nombre consultorio,t3.nombre,t3.paterno,t3.materno,
(case 
when t1.turno = 'MD' then 'MADRUGADA'
when t1.turno = 'M' then 'MA&Ntilde;ANA'
when t1.turno = 'T' then 'TARDE'
when t1.turno = 'N' then 'NOCHE'
end)nombre_turno
from receta_vales t1
inner join consultorios t2 on t1.id_consultorio=t2.id
inner join medicos t3 on t1.id_medico=t3.id
where substring(replace(nro_receta,'P-',''),1,strpos(replace(nro_receta,'P-',''),'-')-1)='".$nro_receta."' and id_farmacia=".$id_farmacia;

        $data = DB::select($cad);
        return $data;
    }
	
	public function GetRecetaByEstablecimientoAndNroReceta($id_establecimiento, $nro_receta, $ano) {
        $cad = "select t1.*,t2.nombre consultorio,t3.nombre,t3.paterno,t3.materno,
(case 
when t1.turno = 'MD' then 'MADRUGADA'
when t1.turno = 'M' then 'MA&Ntilde;ANA'
when t1.turno = 'T' then 'TARDE'
when t1.turno = 'N' then 'NOCHE'
end)nombre_turno
from receta_vales t1
inner join consultorios t2 on t1.id_consultorio=t2.id
inner join medicos t3 on t1.id_medico=t3.id
inner join farmacias t4 on t1.id_farmacia=t4.id
where substring(replace(nro_receta,'P-',''),1,strpos(replace(nro_receta,'P-',''),'-')-1)='".$nro_receta."' 
and substring(nro_receta,length(nro_receta)-3,length(nro_receta))='".$ano."'
and t4.id_establecimiento=".$id_establecimiento;
		//echo $cad;
        $data = DB::select($cad);
        return $data;
    }
	
/*	public function GetRecetaById($id) {
        $cad = "select t1.*,t2.nombre consultorio,t3.nombre,t3.paterno,t3.materno,
(case 
when t1.turno = 'MD' then 'MADRUGADA'
when t1.turno = 'M' then 'MA&Ntilde;ANA'
when t1.turno = 'T' then 'TARDE'
when t1.turno = 'N' then 'NOCHE'
end)nombre_turno
from receta_vales t1
inner join consultorios t2 on t1.id_consultorio=t2.id
inner join medicos t3 on t1.id_medico=t3.id
inner join farmacias t4 on t1.id_farmacia=t4.id
where t1.id=".$id;
		//echo $cad;
        $data = DB::select($cad);
        return $data;
    }
*/
	public function GetRecetaById($id) {
        $cad = "select t1.*,t2.nombre consultorio,t3.nombre,t3.paterno,t3.materno, t5.grado grado_paciente, t4.nombre nombre_farmacia,
(case 
when t1.turno = 'MD' then 'MADRUGADA'
when t1.turno = 'M' then 'MA&Ntilde;ANA'
when t1.turno = 'T' then 'TARDE'
when t1.turno = 'N' then 'NOCHE'
end)nombre_turno
from receta_vales t1
inner join consultorios t2 on t1.id_consultorio=t2.id
inner join medicos t3 on t1.id_medico=t3.id
inner join farmacias t4 on t1.id_farmacia=t4.id
left join asegurados t5 on t5.id = t1.id_asegurado  
where t1.estado=2 and t1.id=".$id;
		//echo $cad;
        $data = DB::select($cad);
        return $data;
    }

    public function GetRecetaByIdEmergencia($id) {
        $cad = "select t1.*,t2.nombre consultorio,t3.nombre,t3.paterno,t3.materno, t5.grado grado_paciente, t7.descripcion prioridad, t8.descripcion servicio_emergencia,
(case 
when t1.turno = 'MD' then 'MADRUGADA'
when t1.turno = 'M' then 'MA&Ntilde;ANA'
when t1.turno = 'T' then 'TARDE'
when t1.turno = 'N' then 'NOCHE'
end)nombre_turno
from receta_vales t1
inner join consultorios t2 on t1.id_consultorio=t2.id
inner join medicos t3 on t1.id_medico=t3.id
inner join farmacias t4 on t1.id_farmacia=t4.id
left join asegurados t5 on t5.id = t1.id_asegurado
left join emergencias t6 on t6.id_asegurado = t5.id
left join prioridades t7 on t6.ubica_prioridad = t7.id
left join triajes t8 on t6.ubica_topico = t8.id 
where t1.estado=2 and t1.id=".$id;
        //echo $cad;
        $data = DB::select($cad);
        return $data;
    }
	
	public function productividad_medico($id_establecimiento, $fecha_inicio, $fecha_fin)
  {
    $cad = "select 
    c2.id, max(c2.nombre) consultorio, 
    c.id_medico, max(m.dni) documento, concat_ws(' ',max(m.nombre), max(m.paterno), max(m.materno)) medico,
    count(*) citas_atendidas
    from citas c 
    inner join establecimientos e on e.id = c.id_establecimiento 
    inner join sub_consultorios sc on sc.id = c.id_sub_consultorio and sc.id_establecimiento = e.id 
    inner join consultorios c2 on c2.id = sc.id_consultorio 
    inner join estados_sistema es on es.tabla = 'citas' and es.estado_tabla::integer = c.id_estado_cita 
    left join medicos m on m.id = c.id_medico 
    left join tipo_cita tc on tc.id = c.id_tipo_cita 
    where 1 = 1
    and c.id_establecimiento = {$id_establecimiento}
    and fecha_hora between '{$fecha_inicio}' and '{$fecha_fin}'
    and id_estado_cita = 1
    group by c2.id, c.id_medico 
    order by c2.nombre, 5";

    $data = DB::select($cad);
    return $data;
  }

    public function ActualizaIdEvaluacion($id, $id_evaluacion) {

        $datos = Receta_vale::where('id', $id)->first();
        $datos->id_evaluacion = $id_evaluacion;
        return $datos->save();
        
        // return true;
    
    }   

    public function GetDatosRecetasByIdEvaluacion($id_evaluacion) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->select('receta_vales.*', 'farmacias.nombre as farmacia')
                ->where('receta_vales.id_evaluacion', '=', $id_evaluacion)
                ->where('receta_vales.estado', '<>', 0)
                ->orderBy('receta_vales.id', 'asc')
                ->get();
        return $datos;
    }

    public function GetDatosRecetasByIdEvaluacionAlta($id_evaluacion)
    {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->select('receta_vales.*', 'farmacias.nombre as farmacia')
                ->where('receta_vales.id_evaluacion', $id_evaluacion)
                ->where('receta_vales.estado', '<>', 0)
                ->where('receta_vales.flag_origen', 2)
                ->orderBy('receta_vales.id', 'asc')
                ->get();
        return $datos;
    }

  public function GetEscalas() 
  {
    $query = "select id, nombre, tipo from escalas where estado = 1 order by orden asc";
    $data = DB::select($query);
    return $data;
  }	

  public function GetEscalaOpciones() 
  {
    $query = '
      select id_escala, id_parent, orden, id, nombre, puntaje 
      from escala_opciones 
      order by id_escala asc, id_parent asc, orden asc
    ';
    $data = DB::select($query);
    return $data;
  }	

  public function GetEscalaResultados() 
  {
    $query = '
      select er.id_escala, e.nombre, er.resultado, er.minimo, er.maximo
      from escalas as e 
      inner join escala_resultados as er on e.id = er.id_escala 
      order by er.id_escala asc, er.minimo asc, er.maximo asc
    ';
    $data = DB::select($query);
    return $data;
  }

  public function GetDatosRecetasByIdCita($id_cita) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->join('prestaciones', 'prestaciones.id', '=', 'receta_vales.id_prestacion')
                ->select('receta_vales.*', 'farmacias.nombre as farmacia')
                ->where('prestaciones.id_cita', '=', $id_cita)
                ->where('receta_vales.estado', '<>', 0)
                ->orderBy('receta_vales.id', 'asc')
                ->get();
        return $datos;
    }
        
    public function getIdAsegurado($dni_beneficiario){
        $query = "select id, dni_beneficiario,nro_historia,id_asegurado  
                    from receta_vales rv 
                    where dni_beneficiario  = '". $dni_beneficiario ."'order by id desc limit 1";
        $datos = DB::select($query);
        return $datos;
    }

    public function ShowDemanadaNoSatisfechaByFarmaFD($fechaDesde = "", $fechaHasta = "", $id_farmacia) {
        
        $cad = "Select * From sp_reporte_no_dispensados ('".$fechaDesde."','".$fechaHasta."',".$id_farmacia.");";
        $data = DB::select($cad);

        return $data;
    }

    public function AllKardexByFarmaAndProductoIngreso($id_farmacia,$nombre_farmacia, $id_producto = "", $fechaDesde = "", $fechaHasta = "") {
$cad = "Select kl.id, k.id_farmacia,k.fecha_movimiento fecha_entrada,k.id_tipo_movimiento,k.tipo_movimiento abrev_tipo_movimiento,tm.nombre tipo_movimiento, replace(k.numero_movimiento, '-i', '') numero_movimiento, t.tipo,d.nombre_dependencia lugar_destino_dependencia, f.nombre lugar_destino_farmacia, e.nombre lugar_destino_establecimiento, co.nombre consultorio, k.origen,
p.codigo codigo_producto,p.nombre nombre_producto,coalesce(kl.cantidad*kl.presentacion_comercial,0) as cantidad,kl.registro_sanitario,kl.lote,kl.fecha_vencimiento,kl.precio_unitario, k.created_at, kl.presentacion_comercial, p.presentacion_comercial pc
From kardexes k
Inner Join productos p On k.id_producto=p.id 
Inner Join transferencias t On k.id_nro_movimiento = t.id  
Left Join kardex_lotes kl On k.id=kl.id_kardex
Inner Join tipo_movimientos tm On k.id_tipo_movimiento=tm.id
left join farmacias f on f.id=t.id_farmacia_destino
left join establecimientos e on t.id_ipress=e.id
left join dependencias d on t.id_farmacia_destino=d.id
left join consultorios co on t.id_consultorio=co.id
Where k.estado=1 and kl.estado=1 and (k.id_tipo_movimiento=2 or k.id_tipo_movimiento=9) and k.id_farmacia = " . $id_farmacia . " And p.id=" . $id_producto . "";
if ($fechaDesde != "")
$cad .= " And k.fecha_movimiento>='" . $fechaDesde . "'";
if ($fechaHasta != "")
$cad .= " And k.fecha_movimiento<='" . $fechaHasta . "'";
$cad .= " Order By k.created_at desc;";    
     
        $data = DB::select($cad);
        return $data;
    }

    public function productos()
    {
        return $this->hasMany('App\Models\Producto_receta', 'id_receta_vale', 'id');
    }

    public function diagnosticos()
    {
        return $this->hasMany('App\Models\Receta_diagnostico', 'id_receta_vale', 'id');
    }



    public function getHospitalizacionById_registrar_enfermeria($id_emergencia, $codigo_ubig_responsable, $codigo_ubig_apoderado) {  
        $cad = "SELECT 
            e.*
            ";
    
            if($codigo_ubig_responsable[0]->codigo_ubig_responsable !=null&& $codigo_ubig_responsable[0]->codigo_ubig_responsable!=''){
                $cad =$cad.
                ", dep4.descripcion as departamento_responsable, pro4.descripcion as pronvincia_responsable, dis4.descripcion as distrito_responsable 
                ";
            }
    
            if($codigo_ubig_apoderado[0]->codigo_ubig_apoderado !=null && $codigo_ubig_apoderado[0]->codigo_ubig_apoderado!=''){
                $cad =$cad.
                ", dep5.descripcion as departamento_apoderado, pro5.descripcion as pronvincia_apoderado, dis5.descripcion as distrito_apoderado 
                ";
            }
    
        $cad = $cad."
            
        from emergencias e  
 
 
        LEFT join asegurados a on e.id_asegurado  = a.id  
        
        LEFT JOIN asegurados as asegurados_titular ON  a.id_asegurado_titular = asegurados_titular.id
        
        LEFT join tipo_seguros ti_seg on a.id_tipo_seguro=ti_seg.id

        LEFT join tipo_etnias te on a.id_etnia = te.id 
        LEFT join tipo_idiomas ti on a.id_idioma = ti.id 
        LEFT join tipo_estado_civil tec  on a.id_estado_civil = tec.id 
        LEFT join tipo_religiones tr on a.id_religion = tr.id 
        LEFT join tipo_grados_instruccion tgi on a.id_grado_instruccion = tgi.id 
        LEFT join tipo_ocupaciones tocu on a.id_ocupacion = tocu.id 
        LEFT join tipo_sangres ti_sa on a.id_tipo_sangre = ti_sa.id 
        
    
        LEFT join departamentos dep on a.id_departamento = dep.id_departamento
        LEFT join provincias pro on a.id_provincia = pro.id_provincia
        LEFT join distritos dis on a.id_distrito = dis.id_distrito

    
        LEFT join departamentos dep1 on a.id_departamento_nacimiento = dep1.id_departamento
        LEFT join provincias pro1 on a.id_provincia_nacimiento = pro1.id_provincia
        LEFT join distritos dis1 on a.id_distrito_nacimiento = dis1.id_distrito

    
        LEFT join departamentos dep2 on a.id_departamento_procedencia = dep2.id_departamento
        LEFT join provincias pro2 on a.id_provincia_procedencia = pro2.id_provincia
            LEFT join distritos dis1 on a.id_distrito_nacimiento = dis1.id_distrito
    
        
          
        ";
    
        
    
        $cad =$cad." 
      
        LEFT join medicos med on e.id_medico_egreso =med.id
        LEFT join especializaciones esp_medi on med.id_especializacion=esp_medi.id
        here e.id='".$id_emergencia."'
    
           
   ";
            $data = DB::select($cad);
            return $data;
        }
        public function getEmergenciaById_farmacia_ver($id_emergencia) {  
            $cad = "select distinct e2.*, to_char(e.created_at ::date,'DD/MM/YYYY') AS fecha_creacion,t.descripcion as topico,c.nombre as cama,c2.nombre as cuarto,p3.nombre piso, p4.nombre pabellon,
            to_char(e.created_at, 'HH:MI:SS AM')  AS hora_creacion, p.descripcion as des_prioridades ,a.grado as grado_titular, a.telefono as telefono_titular,a.*,e3.codigo as codigo_ups
            ,e3.nombre as nombre_estab ,e2.dni_beneficiario ,a.paterno as paterno_beneficiario ,a.materno as materno_beneficiario,tb.descripcion as tipo_beneficiario,
            a.nombre as nombre_beneficiario , a.fecha_nac as fecha_nac_paciente,a.grado as grado_beneficiario,a.situacion_paciente , a.cip as cip_beneficiario ,a.cip as cip_beneficiario,
            a.tipo_sangre as sangre_beneficiario,a.fecha_nac as fecha_nacimiento_beneficiario,a.sexo,
            EXTRACT(YEAR FROM age(current_date, fecha_nac::date)) as edad_beneficiario ,tp.descripcion as conducio_por, e2.nombre_conducido ,e2.dni_conducido ,e2.telf_conducido
            ,med.paterno as profesional_paterno ,med.materno as profesional_materno ,med.dni as profesional_dni ,med.nombre as profesional_nombre,med.paterno as profesional_paterno ,med.materno as profesional_materno 
            ,e22.nombre as especialidad_medico ,p5.nombre as profesion_medico,e2.id_gestacion, med.colegiatura ,med.rne ,med.nombre as profesional_nombre
            ,g.fech_ult_regla ,g.fech_prob_parto ,g.semanas_gestacion ,g.num_controles,e2.id_motivo_ingreso,e3.nombre as esta_referencia ,ee.numero_oficio,
            to_char(ee.created_at ::date,'DD-MM-YYYY') AS fecha_referencia,ee.numero_oficio,ee.observacion as observacion_evacuados
            from enfermerias e 
            left join emergencias e2 on e.id_ext_enfermeria =e2.id 
            left join prioridades p on e2.id_prioridad =p.id 
            left join triajes t on t.id =e2.id_triaje 
            left join camas  c on c.id =e2.cama_estado 
            left join cuartos c2 on c2.id =c.id_cuarto 
            left join asignacion_servicios as2 on as2.id =c2.id_asignacion_servicio 
            left join pisos p3  on p3.id =as2.id_piso 
            left join pabellones p4 on p4.id =p3.id_pabellon 
            left join asegurados a on a.id =e2.id_asegurado  
            left join establecimientos e3 on e2.id_establecimiento =e3.id    
            left join tipo_beneficiarios tb on tb.id =a.id_tipo_beneficiario 
            left join tipo_parentescos tp on tp.id =e2.id_tipo_parentesco 
            left join prestaciones pres on pres.id_ext =e2.id 
            left join medicos m on m.id_consultorio =pres.id_medico 
            left join users u2s on u2s.id = e2.id_medico_triaje
            left join medicos med on med.dni =u2s.dni 
            left join especializaciones e22 on e22.id =med.id_especializacion 
            left join profesiones p5 on p5.id=med.id_profesion 
            left join gestaciones g on g.id =e2.id_gestacion
            left join emergencia_evacuados ee on ee.id_emergencia =e2.id 
            

            Where  e.id=" . $id_emergencia . " ";

         $data = DB::select($cad);
        return $data;
    }
        public function getEmergenciaById_farmacia_new($id_emergencia) {  
                $cad = "select distinct a.grado as grado_titular, a.telefono as telefono_titular, d4.nombre as nom_diagnostico , e.id_gestacion,g.fech_ult_regla ,g.fech_prob_parto ,g.semanas_gestacion ,g.num_controles ,ee.observacion as observacion_evacuados,e.id_motivo_ingreso ,to_char(ee.created_at ::date,'DD-MM-YYYY') AS fecha_referencia,ee.numero_oficio ,e4.nombre as esta_referencia, e3.nombre as nombre_estab , e3.codigo as codigo_ups, p6.descripcion as des_prioridades, e.dni_titular , a.cip, a.domicilio ,e.observacion_admision as observaciones_benefi,e.telf_conducido ,tp.descripcion as conducio_por, e.nombre_conducido ,e.dni_conducido ,EXTRACT(YEAR FROM age(current_date, fecha_nac::date)) as edad_beneficiario,e.id_tipo_beneficiario,a.tipo_sangre as sangre_beneficiario,a.grado as grado_beneficiario,a.situacion_paciente , a.cip as cip_beneficiario ,a.cip as cip_beneficiario,p5.nombre as profesion_medico,c4.nombre as servicio1,t.descripcion as topico,c4.nombre as especialidad,e.dni_beneficiario as nro_historiass ,e3.codigo as codigoipress,e3.nivel as nivel_ipress , a.fecha_nac as fecha_nac_paciente, a.paterno , a.nro_doc_ident , a.tipo_doc_ident as tipo_doc_ident_asegurado, e2.nombre as especialidad_medico , med.id_especializacion  , med.colegiatura ,med.rne ,med.nombre as profesional_nombre,med.paterno as profesional_paterno ,med.materno as profesional_materno ,med.dni as profesional_dni, e.id_medico_triaje ,e.telf_conducido as telefono_acompaniante,e.observacion_admision as observacion_acompaniante,e.tipo_doc_cond as tipo_documento_acompaniante, e.nombre_conducido as nombre_acompaniante, e.dni_conducido as numero_documento_acompaniante, tp.descripcion as tipo_acompaniante,c.nombre as cama,c2.nombre as cuarto,p3.nombre piso, p4.nombre pabellon,ta.id as origen,to_char(e.fecha_registro_ingreso ::date,'DD/MM/YYYY') AS fecha_ingreso, 
        to_char(e.hora_registro_ingreso ::time,'HH:MI:SS AM') AS hora_ingreso, 
         to_char(e.created_at ::date,'DD/MM/YYYY') AS fecha_creacion, 
        to_char(e.created_at, 'HH:MI:SS AM')  AS hora_creacion, 
        p2.descripcion as pronvincia_actual, p2.descripcion as pronvincia_actual,d2.descripcion as distrito_actual, d.descripcion as departamento_actual, a.domicilio as domicilio_actual,a.codigo_ubig as codigo_ubig,a.id_departamento , r.nombre as religion, a.id_religion , ts.descripcion_corta as descripcion_tipo_sangres, a.sexo ,a.fecha_nac as fecha_nacimiento_beneficiario, u2s.name as nombre_usuario,u2s.paterno paterno_usuario,u2s.materno materno_usuario, e.id_usuario_admision , a.tipo_doc_ident as tipo_doc_ident_beneficiario, a.nombre ,  ah.nro_historia , a.id, e.*, m.nombre, tb.descripcion as tipo_beneficiario ,c3.nombre as nombre_consultorio,e.fecha_registro_ingreso as fecha_ingreso ,e.estado , e.dni_beneficiario 
        ,a.nombre as nombre_beneficiario ,a.paterno as paterno_beneficiario ,a.materno as materno_beneficiario, c2.id ,
            c2.nombre AS nombre_cuarto,
        c.id ,c.nombre AS nombre_cama,
        e.cups_pnp as codigo_cups,
        e.id as id_emergencia
                from emergencias e
                left join camas  c on c.id =e.cama_estado 
                left join cuartos c2 on c2.id =c.id_cuarto 
                left join asignacion_servicios as2 on as2.id =c2.id_asignacion_servicio 
                left join pisos p3  on p3.id =as2.id_piso 
                left join pabellones p4 on p4.id =p3.id_pabellon 
                left join asegurados a on a.id =e.id_asegurado 
                left join prestaciones p on p.id_ext =e.id 
                left join sub_consultorios sc on p.id_sub_consultorio =sc.id 
                left join triajes t on t.id =e.id_triaje 
				left join consultorios c4 on t.id_servicio =c4.id 
                left join consultorios c3 on c3.id =sc.id_consultorio
                left join tipo_beneficiarios tb on tb.id =a.id_tipo_beneficiario 
                left join medicos m on m.id_consultorio =p.id_medico 
                left join asegurado_historias ah on ah.id =a.id 
                left join tipo_sangres ts on ts.id =a.id_tipo_sangre 
                left join religiones r on r.id =a.id_religion 
                left join departamentos d on a.id_departamento = d.id_departamento 
                left join distritos d2 on d2.id_distrito  =a.id_distrito  
                left join provincias p2 on p2.id_provincia =a.id_provincia 
                left join tipo_atenciones ta on ta.id =p.id_tipo_atencion 
                --para traer la data del profesional medico que se llama medico_triaje el campo deberia llamarse id_usuario no id_medico_triaje
                left join users u2s on u2s.id = e.id_medico_triaje
                left join medicos med on med.dni =u2s.dni 
                --esto es el tipo_acompaniante
                left join tipo_parentescos tp on tp.id =e.id_tipo_parentesco 
                left join especializaciones e2 on e2.id =med.id_especializacion 
                left join profesiones p5 on p5.id=med.id_profesion 
                left join prioridades p6 on p6.id =e.id_prioridad
                left join establecimientos e3 on e.id_establecimiento =e3.id 
                left join emergencia_evacuados ee on ee.id_emergencia =e.id 
                left join departamentos d3 on d3.id_departamento =e.id_departamento 
                left join establecimientos e4 on e4.id =ee.unidad_policial 
                left join gestaciones g on g.id =e.id_gestacion 
                left join receta_diagnosticos rd on rd.id_emergencia = e.id 
                left join diagnosticos d4 on d4.id =rd.id_diagnostico
                Where  e.id=" . $id_emergencia . " ";
    
             $data = DB::select($cad);
            return $data;
        }
        public function getHospitalizacionById_farmacia_new($id_hospitalizacion) {  
                $cad = "select distinct a.grado as grado_titular, a.telefono as telefono_titular, d4.nombre as nom_diagnostico ,ee.observacion as observacion_evacuados,
                to_char(ee.created_at ::date,'DD-MM-YYYY') AS fecha_referencia,ee.numero_oficio ,e4.nombre as esta_referencia, e3.nombre as nombre_estab , e3.codigo as codigo_ups, 
                h.dni_titular , a.cip, a.domicilio ,h.obs_traslado as observaciones_benefi,h.telefono_acompaniante,EXTRACT(YEAR FROM age(current_date, fecha_nac::date)) 
                as edad_beneficiario,h.tipo_beneficiario,a.tipo_sangre as sangre_beneficiario,a.grado as grado_beneficiario,a.situacion_paciente , a.cip as cip_beneficiario 
                ,a.cip as cip_beneficiario,p5.nombre as profesion_medico, h.dni_beneficiario as nro_historiass ,e3.codigo as codigoipress,e3.nivel as nivel_ipress , a.fecha_nac as fecha_nac_paciente, 
                a.paterno , a.nro_doc_ident , a.tipo_doc_ident as tipo_doc_ident_asegurado, e2.nombre as especialidad_medico , med.id_especializacion  , med.colegiatura ,
                med.rne ,med.nombre as profesional_nombre,med.paterno as profesional_paterno ,med.materno as profesional_materno ,med.dni as profesional_dni, h.id_medico ,
                h.telefono_acompaniante as telefono_acompaniante,h.obs_traslado as observacion_acompaniante, h.nombre_acompaniante as nombre_acompaniante, h.nro_doc_acompaniante as numero_documento_acompaniante, 
                c.nombre as cama,c2.nombre as cuarto,p3.nombre piso, p4.nombre pabellon,ta.id as origen,to_char(h.fecha_ingreso ::date,'DD/MM/YYYY') AS fecha_ingreso, 
                to_char(h.hora_ingreso ::time,'HH:MI:SS AM') AS hora_ingreso, 
                 to_char(h.created_at ::date,'DD/MM/YYYY') AS fecha_creacion, 
                to_char(h.created_at, 'HH:MI:SS AM')  AS hora_creacion, 
                tp.descripcion as conducio_por,
                p2.descripcion as pronvincia_actual, p2.descripcion as pronvincia_actual,d2.descripcion as distrito_actual, d.descripcion as departamento_actual, a.domicilio as domicilio_actual,
                a.codigo_ubig as codigo_ubig,a.id_departamento , r.nombre as religion, a.id_religion , ts.descripcion_corta as descripcion_tipo_sangres, a.sexo ,a.fecha_nac as fecha_nacimiento_beneficiario, 
                u2s.name as nombre_usuario,u2s.paterno paterno_usuario,u2s.materno materno_usuario, h.id_user , a.tipo_doc_ident as tipo_doc_ident_beneficiario, a.nombre ,  ah.nro_historia , a.id, h.*, m.nombre, 
                tb.descripcion as tipo_beneficiario ,c3.nombre as nombre_consultorio,h.fecha_ingreso as fecha_ingreso ,h.estado , h.dni_beneficiario 
                ,a.nombre as nombre_beneficiario ,a.paterno as paterno_beneficiario ,a.materno as materno_beneficiario, c2.id ,
                    c2.nombre AS nombre_cuarto,
                c.id ,c.nombre AS nombre_cama,
                h.id as id_hospitalizacion,
                h.cups_pnp as codigo_cups
                        from hospitalizaciones h 
                        left join camas  c on c.id =h.id_cama_estado
                        left join cuartos c2 on c2.id =c.id_cuarto 
                        left join asignacion_servicios as2 on as2.id =c2.id_asignacion_servicio 
                        left join pisos p3  on p3.id =as2.id_piso 
                        left join pabellones p4 on p4.id =p3.id_pabellon 
                        left join asegurados a on a.id =h.id_asegurado 
                        left join prestaciones p on p.id_ext =h.id 
                        left join sub_consultorios sc on p.id_sub_consultorio =sc.id 
                        left join consultorios c3 on c3.id =sc.id_consultorio
                        left join tipo_beneficiarios tb on tb.id =a.id_tipo_beneficiario 
                        left join medicos m on m.id_consultorio =p.id_medico 
                        left join asegurado_historias ah on ah.id =a.id 
                        left join tipo_sangres ts on ts.id =a.id_tipo_sangre 
                        left join religiones r on r.id =a.id_religion 
                        left join departamentos d on a.id_departamento = d.id_departamento 
                        left join distritos d2 on d2.id_distrito  =a.id_distrito  
                        left join provincias p2 on p2.id_provincia =a.id_provincia 
                        left join tipo_atenciones ta on ta.id =p.id_tipo_atencion 
                        --para traer la data del profesional medico que se llama medico_triaje el campo deberia llamarse id_usuario no id_medico_triaje
                        left join medicos med on med.id = h.id_medico 
                        left join users u2s on u2s.dni = med.dni
                        --esto es el tipo_acompaniante
                        left join tipo_parentescos tp on tp.id = h.id_tipo_acompaniante 
                        left join especializaciones e2 on e2.id =med.id_especializacion 
                        left join profesiones p5 on p5.id=med.id_profesion 
                        left join establecimientos e3 on h.id_establecimiento =e3.id 
                        left join emergencia_evacuados ee on ee.id_emergencia =h.id 
                        left join establecimientos e4 on e4.id =ee.unidad_policial 
                        left join receta_diagnosticos rd on rd.id_emergencia = h.id 
                        left join diagnosticos d4 on d4.id =rd.id_diagnostico
                Where  h.id=" . $id_hospitalizacion . " ";
    
             $data = DB::select($cad);
            return $data;
        }

        public function getHospitalizacionById_farmacia_new_hospitalizacion($id_hospitalizacion, $codigo_ubig_responsable, $codigo_ubig_apoderado) {  
            $cad = "SELECT 
                T1.*,a.fecha_nac as fecha_nac_paciente,T8.nivel as nivel_ipress,
                T8.nombre as ipress,T8.codigo as codigoipress,T5.nombre as consultorio,cons.nombre as servicio, esp_medi.nombre as especializacion,
                paren.descripcion as tipo_parentesco_responsable,med.nombre as nombre_medico,med.paterno as paterno_medico, med.materno as materno_medico, 
                med.dni as dni_medico, med.colegiatura as colegiatura_medico, med.rne as rne_medico, T4.name as nombre_usuario,T4.paterno as paterno_usuario,
                T4.materno as materno_usuario, a.tipo_doc_ident as tipo_doc_ident_asegurado,
                a.nombre as nombre_de_asegurado, a.paterno as paterno_de_asegurado, a.materno as materno_de_asegurado,
                to_char(T1.created_at::date,'DD/MM/YYYY') AS fecha_creacion, 
                to_char(T1.created_at, 'HH:MI:SS AM')  AS hora_creacion,  
     
                asegurados_titular.tipo_doc_ident  AS nomtipdoctitular, 
                asegurados_titular.nro_doc_ident  AS nrodoctitular, 
                asegurados_titular.paterno  AS apepattitular, 
                asegurados_titular.materno  AS apemattitular, 
                asegurados_titular.nombre  AS nomtitular, 
                asegurados_titular.cip  AS ciptitular,  
                asegurados_titular.grado  AS gradotitular,  
                asegurados_titular.situacion_paciente  AS situaciontitular,  
                asegurados_titular.unidad_pnptitular  AS unidad_pnptitular,
    
                to_char(T1.fecha_ingreso::date,'DD/MM/YYYY') AS fecha_ingreso, 
                to_char(T1.hora_ingreso::time,'HH:MI:SS AM') AS hora_ingreso, 
                T9.nombre as nombre_medico_hospitalizacion,T9.paterno as paterno_medico_hospitalizacion, T9.materno as materno_medico_hospitalizacion, 
                T9.dni as dni_medico_hospitalizacion,T10.nombre cama,T11.nombre cuarto,T13.nombre consultorio_hospitalizacion,T14.nombre piso,
                T15.nombre pabellon,te.descripcion as etnia , ti.descripcion as idioma, tec.descripcion as estado_civil,tr.descripcion as religion, 
                tgi.descripcion as grado_instruccion, tocu.descripcion as ocupacion, a.telefono2, a.telefono, 
                dep.descripcion as departamento_actual, pro.descripcion as pronvincia_actual, dis.descripcion as distrito_actual,
                a.domicilio as domicilio_actual, a.codigo_ubig as codigo_ubig, a.codigo_ubig_nacimiento as codigo_ubig_nacimiento,
                dep1.descripcion as departamento_nacimiento, pro1.descripcion as pronvincia_nacimiento, dis1.descripcion as distrito_nacimiento,
                a.direccion_nacimiento as direccion_nacimiento,  a.codigo_ubig_procedencia as codigo_ubig_procedencia, ti_seg.id as id_tipo_seguro,
                ti_sa.descripcion_corta as descripcion_tipo_sangres, a.situacion_paciente as situacion_paciente, ti_seg.descripcion as descripcion_tipo_seguro,
                dep2.descripcion as departamento_procedencia, pro2.descripcion as pronvincia_procedencia, dis2.descripcion as distrito_procedencia,
                a.direccion_procedencia as direccion_procedencia, a.tipo_doc_ident as tipo_doc_ident_beneficiario, ups.descripcion_upss as descripcion_topico_upss,
                dep3.descripcion as departamento_acompaniante, pro3.descripcion as pronvincia_acompaniante, dis3.descripcion as distrito_acompaniante,
                a.tipo_seguro_numero as tipo_seguro_numero,a.tipo_seguro_vencimiento as tipo_seguro_vencimiento,a.tipo_seguro_estadows as tipo_seguro_estadows
                ";
        
                if($codigo_ubig_responsable[0]->codigo_ubig_responsable !=null&& $codigo_ubig_responsable[0]->codigo_ubig_responsable!=''){
                    $cad =$cad.
                    ", dep4.descripcion as departamento_responsable, pro4.descripcion as pronvincia_responsable, dis4.descripcion as distrito_responsable 
                    ";
                }
        
                if($codigo_ubig_apoderado[0]->codigo_ubig_apoderado !=null && $codigo_ubig_apoderado[0]->codigo_ubig_apoderado!=''){
                    $cad =$cad.
                    ", dep5.descripcion as departamento_apoderado, pro5.descripcion as pronvincia_apoderado, dis5.descripcion as distrito_apoderado 
                    ";
                }
        
            $cad = $cad."
                
                from hospitalizaciones T1 
                LEFT join asegurados a on T1.dni_beneficiario = a.nro_doc_ident 
                LEFT JOIN asegurados as asegurados_titular ON  a.id_asegurado_titular = asegurados_titular.id
                LEFT join tipo_seguros ti_seg on a.id_tipo_seguro=ti_seg.id
                LEFT join tipo_parentescos paren on T1.id_tipo_parentesco_responsable = paren.id 
        
                LEFT join tipo_etnias te on a.id_etnia = te.id 
                LEFT join tipo_idiomas ti on a.id_idioma = ti.id 
                LEFT join tipo_estado_civil tec  on a.id_estado_civil = tec.id 
                LEFT join tipo_religiones tr on a.id_religion = tr.id 
                LEFT join tipo_grados_instruccion tgi on a.id_grado_instruccion = tgi.id 
                LEFT join tipo_ocupaciones tocu on a.id_ocupacion = tocu.id 
                LEFT join tipo_sangres ti_sa on a.id_tipo_sangre = ti_sa.id 
                
            
                LEFT join departamentos dep on a.id_departamento = dep.id_departamento
                LEFT join provincias pro on a.id_provincia = pro.id_provincia
                LEFT join distritos dis on a.id_distrito = dis.id_distrito
        
            
                LEFT join departamentos dep1 on a.id_departamento_nacimiento = dep1.id_departamento
                LEFT join provincias pro1 on a.id_provincia_nacimiento = pro1.id_provincia
                LEFT join distritos dis1 on a.id_distrito_nacimiento = dis1.id_distrito
        
            
                LEFT join departamentos dep2 on a.id_departamento_procedencia = dep2.id_departamento
                LEFT join provincias pro2 on a.id_provincia_procedencia = pro2.id_provincia
                LEFT join distritos dis2 on a.id_distrito_procedencia = dis2.id_distrito
        
    
                LEFT join departamentos dep3 on T1.id_departamento_acompaniante = dep3.id_departamento
                LEFT join provincias pro3 on T1.id_provincia_acompaniante = pro3.id_provincia
                LEFT join distritos dis3 on T1.id_distrito_acompaniante = dis3.id_distrito
            ";
        
            if($codigo_ubig_responsable[0]->codigo_ubig_responsable !=null&& $codigo_ubig_responsable[0]->codigo_ubig_responsable!=''){
                $cad =$cad."
                LEFT join departamentos dep4 on T1.id_departamento_responsable = dep4.id_departamento
                LEFT join provincias pro4 on T1.id_provincia_responsable = pro4.id_provincia
                LEFT join distritos dis4 on T1.id_distrito_responsable = dis4.id_distrito
                ";
            }
        
            if($codigo_ubig_apoderado[0]->codigo_ubig_apoderado !=null && $codigo_ubig_apoderado[0]->codigo_ubig_apoderado!=''){
                $cad =$cad."
                LEFT join departamentos dep5 on T1.id_departamento_apoderado = dep5.id_departamento
                LEFT join provincias pro5 on T1.id_provincia_apoderado = pro5.id_provincia
                LEFT join distritos dis5 on T1.id_distrito_apoderado = dis5.id_distrito
                ";
            }
        
            $cad =$cad." 
          
                LEFT join farmacias T3 on T1.id_farmacia=T3.id
                LEFT join users T4 on T1.id_user=T4.id
                LEFT join consultorios T5 on T1.id_consultorio=T5.id
                LEFT join consultorios cons on T1.id_consultorio_hosp=cons.id
                LEFT join medicos med on T1.id_medico=med.id
                LEFT join especializaciones esp_medi on med.id_especializacion=esp_medi.id
                LEFT join establecimientos T8 on T3.id_establecimiento=T8.id 
                LEFT join medicos T9 on T1.id_medico=T9.id
                LEFT join camas T10 on T1.id_cama=T10.id
                LEFT join cuartos T11 on T1.id_cuarto=T11.id
                LEFT join asignacion_servicios T12 on T11.id_asignacion_servicio = T12.id
                LEFT join consultorios T13 on T12.id_consultorio=T13.id 
                LEFT join pisos T14 on T12.id_piso=T14.id
                LEFT join pabellones T15 on T14.id_pabellon=T15.id  
                LEFT join upsses ups on T1.id_servicio_origen=ups.id  
        
                where T1.id='".$id_hospitalizacion."'
                and tipo_hospitalizacion='HO'";
        /*and T1.id_farmacia=" . $id_farmacia; */
                $data = DB::select($cad);
                return $data;
            }
    public function GetDatosRecetasByIdInterconsulta($id_interconsulta) {
        $datos = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->select('receta_vales.*', 'farmacias.nombre as farmacia')
                ->where('receta_vales.id_interconsulta', '=', $id_interconsulta)
                ->where('receta_vales.estado', '<>', 0)
                ->orderBy('receta_vales.id', 'asc')
                ->get();
        return $datos;
    }

    public function ActualizaIdInterconsulta($id, $id_interconsulta) {

        $datos = Receta_vale::where('id', $id)->first();
        $datos->id_interconsulta = $id_interconsulta;
        $datos->save();
        
        return true;
    
    }

    public function busca_Siteds_Receta($dni_beneficiario,$fecha_atencion)
    {
      $query_string = "SELECT codigo_siteds from receta_vales where dni_beneficiario = '{$dni_beneficiario}' and fecha_expedicion = '{$fecha_atencion}'";
      
      $data = DB::SELECT($query_string);
      if ($data != null || count($data) > 0){
        return $data[0];
      }else{
        return $data[0]='';
      }; 
    }

    public function buscar_siteds_generado_hoy($dni_beneficiario,$id_press)
    {
        $recetaValeSites = DB::table('receta_vales')
                        ->select('receta_vales.id', 'receta_vales.codigo_siteds', 'receta_vales.updated_at_cod_siteds')
                        ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                        ->where('receta_vales.dni_beneficiario', $dni_beneficiario)
                        ->where(DB::raw('DATE(receta_vales.updated_at_cod_siteds)'), '=', DB::raw('CURRENT_DATE'))
                        ->where('farmacias.id_establecimiento', $id_press)
                        ->orderBy('receta_vales.id', 'desc')
                        ->first(); 
        return $recetaValeSites;
    }

    public function validar_siteds_receta_vale($cod_siteds)
      {
        $query_string = "SELECT count(id) as cantidad_siteds from receta_vales where codigo_siteds = '{$cod_siteds}'";
        $data = DB::SELECT($query_string);
        if ($data != null || count($data) > 0){
          return $data[0];
        }else{
          return $data;
        }; 
      }

    public function GetRecetasByIdUser($id_user,$fecha_ini,$fecha_fin) {
        $datos = Receta_vale::where('id_user', '=', $id_user)->where('flag_dispensador', '=', 0)->where('estado', '<>', 0)->where('cod_cpt', '=', '99199.05')->where('fecha_expedicion', '>=', $fecha_ini)->where('fecha_expedicion', '<=', $fecha_fin)->get();
        return $datos;
    }

    public function getObtener_codigo_ubig_responsable($id_hospitalizacion) 
        {
          $query = "select codigo_ubig_responsable from hospitalizaciones where id='".$id_hospitalizacion."'"; 
          $data = DB::select($query); 
          return $data;
        }

        public function getObtener_codigo_ubig_responsable_emergencia($id_emergencia) 
        {
          $query = "select codigo_ubig_responsable from emergencias where id='".$id_emergencia."'"; 
          $data = DB::select($query); 
          return $data;
        }

    
        public function getObtener_codigo_ubig_apoderado($id_hospitalizacion) 
        {
          $query = "select codigo_ubig_apoderado from hospitalizaciones where id='".$id_hospitalizacion."'";
          $data = DB::select($query); 
          return $data;
        }
        public function getObtener_codigo_ubig_apoderado_emergencia($id_emergencia) 
        {
          $query = "select codigo_ubig_apoderado from emergencias where id='".$id_emergencia."'";
          $data = DB::select($query); 
          return $data;
        }

    function obtenerProductosPorPrestacionId($prestacion_id) 
    {
        $result = DB::table('receta_vales as rv')
            ->join('producto_recetas as pr', 'pr.id_receta_vale', '=', 'rv.id')
            ->join('productos as p', 'pr.id_producto', '=', 'p.id')
            ->leftJoin('unidades as u', 'p.id_unidad', '=', 'u.id')
            ->leftJoin('via_administracion as va', 'pr.id_via_administracion', '=', 'va.id')
            ->select(
                'rv.id as receta_vale_id',
                'pr.id as producto_receta_id',
                'p.id as id_producto',
                'p.nombre as producto_nombre',
                'u.id as unidad_id',
                'u.nombre as unidad_nombre',
                'u.abreviatura as unidad_abreviatura',
                'pr.cantidad_prescrita',
                'pr.cantidad_dispensada',
                'pr.tiempo_periodo',
                'pr.id_via_administracion',
                'va.descripcion as via_administracion_nombre',
                'pr.posologia'
            )
            ->where('rv.estado', '<>', 0)
            ->where('rv.id_prestacion', $prestacion_id)
            ->get();
        
       return $result;
    }

        public function getHospitalizacionById_farmacia_new_x($id_hospitalizacion) {  
        $cad = "SELECT 
            T1.*,
            a.tipo_doc_ident as tipo_doc_ident_asegurado,
            a.fecha_nac as fecha_nac_paciente,
            ts.descripcion_corta as descripcion_tipo_sangres,
            to_char(age(TO_DATE(a.fecha_nac , 'DD/MM/YYYY')), 'YY\" AÑOS CON \"MM\" MESES Y \"DD\" DÍAS') as fecha_nac_descripcion,
            to_char(t1.fecha_nacimiento_beneficiario,'DD/MM/YYYY') fecha_nacimiento_beneficiario,
            T8.nombre as ipress,
            T8.codigo as codigoipress,
            esp_medi.nombre as especializacion,
            prof.nombre profesion,
            med.nombre as nombre_medico,
            med.paterno as paterno_medico,
            med.materno as materno_medico, 
            med.dni as dni_medico,
            med.colegiatura as colegiatura_medico,
            med.rne as rne_medico,
            to_char(T1.created_at::date,'DD/MM/YYYY') as fecha_creacion, 
            to_char(T1.created_at,'HH:MI:SS AM') as hora_creacion,
            at2.tipo_doc_ident as nomtipdoctitular, 
            at2.nro_doc_ident as nrodoctitular, 
            at2.paterno as apepattitular, 
            at2.materno as apemattitular, 
            at2.nombre as nomtitular, 
            at2.cip as ciptitular,  
            at2.grado as gradotitular,  
            at2.situacion_paciente as situaciontitular,  
            at2.unidad_pnptitular as unidad_pnptitular,
            to_char(T1.fecha_ingreso::date,'DD/MM/YYYY') as fecha_ingreso, 
            to_char(T1.hora_ingreso::time,'HH:MI:SS AM') as hora_ingreso, 
            T9.nombre as nombre_medico_hospitalizacion,
            T9.paterno as paterno_medico_hospitalizacion,
            T9.materno as materno_medico_hospitalizacion, 
            T9.dni as dni_medico_hospitalizacion,
            T10.nombre cama,
            T11.nombre cuarto,
            T13.nombre consultorio_hospitalizacion,
            T14.nombre piso,
            T15.nombre pabellon,
            a.telefono2,
            a.telefono, 
            dep.descripcion as departamento_actual,
            pro.descripcion as provincia_actual,
            dis.descripcion as distrito_actual,
            a.domicilio as domicilio_actual,
            a.codigo_ubig as codigo_ubig,
            ups.descripcion_upss as descripcion_topico_upss,
            ta.nombre tipo_origen,
            tdi.descripcion tipo_doc_ident,
            d.codigo codigo_dx_1,
            d.nombre descripcion_dx_1,
            d2.codigo codigo_dx_2,
            d2.nombre descripcion_dx_2,
            d3.codigo codigo_dx_3,
            d3.nombre descripcion_dx_3,
            ts.descripcion as tipo_sangre,
            r.nombre as etnia,
            ec.nombre as estado_civil,
            r2.nombre as religion,
            gi.nombre as grado_instruccion,
            o.nombre as ocupacion,
            a.direccion_nacimiento,
            di2.descripcion as distrito_nacimiento,
            p2.descripcion as provincia_nacimiento,
            di3.descripcion as departamento_nacimiento,
            a.direccion_procedencia,
            d4.descripcion as distrito_procedencia,
            p3.descripcion as provincia_procedencia,
            d5.descripcion as departamento_procedencia,
            upss_origen.codigo as codigo_upss_origen,
            upss_origen.descripcion_upss as descripcion_upss_origen,
            servicio_origen.nombre as nombre_servicio_origen,
            upss_encargado.descripcion_upss as descripcion_upss_encargado,
            servicio_encargado.nombre as nombre_servicio_encargado

        from hospitalizaciones T1 
            LEFT join asegurados a on T1.dni_beneficiario = a.nro_doc_ident 
            LEFT JOIN asegurados as at2 ON a.id_asegurado_titular = at2.id
            LEFT join departamentos dep on a.id_departamento = dep.id_departamento
            LEFT join provincias pro on a.id_provincia = pro.id_provincia
            LEFT join distritos dis on a.id_distrito = dis.id_distrito
            LEFT join farmacias T3 on T1.id_farmacia=T3.id
            LEFT join medicos med on T1.id_medico=med.id
            LEFT join profesiones prof on med.id_profesion=prof.id
            LEFT join especializaciones esp_medi on med.id_especializacion=esp_medi.id
            LEFT join establecimientos T8 on T3.id_establecimiento=T8.id 
            LEFT join medicos T9 on T1.id_medico=T9.id
            LEFT join camas T10 on T1.id_cama=T10.id
            LEFT join cuartos T11 on T1.id_cuarto=T11.id
            LEFT join asignacion_servicios T12 on T11.id_asignacion_servicio = T12.id
            LEFT join consultorios T13 on T12.id_consultorio=T13.id 
            LEFT join pisos T14 on T12.id_piso=T14.id
            LEFT join pabellones T15 on T14.id_pabellon=T15.id  
            LEFT join upsses ups on T1.id_servicio_origen=ups.id  
            inner join tipo_atenciones ta on t1.id_tipo_origen = ta.id
            inner join tipo_documento_identidad tdi on t1.id_tipo_doc_ident = tdi.id
            inner join diagnosticos d on t1.id_diagnostico_inicial = d.id 
            left join diagnosticos d2 on t1.id_dx_2 = d2.id 
            left join diagnosticos d3 on t1.id_dx_3 = d3.id 
            inner join tipo_sangres ts on a.id_tipo_sangre = ts.id
            inner join razas r on a.id_raza = r.id
            inner join estado_civiles ec on a.id_estado_civil = ec.id
            inner join religiones r2 on a.id_religion = r2.id
            inner join grado_instrucciones gi on a.id_grado_instruccion = gi.id
            inner join ocupaciones o on a.id_ocupacion = o.id
            inner join distritos di2 on a.id_distrito_nacimiento = di2.id_distrito
            inner join provincias p2 on di2.id_provincia = p2.id_provincia
            inner join departamentos di3 on p2.id_departamento = di3.id_departamento
            inner join distritos d4 on a.id_distrito_procedencia = d4.id_distrito
            inner join provincias p3 on d4.id_provincia = p3.id_provincia
            inner join departamentos d5 on p3.id_departamento = d5.id_departamento
            left join upsses upss_origen on t1.id_upss_origen = upss_origen.id
            left join consultorios servicio_origen on t1.id_servicio_origen = servicio_origen.id
            left join upsses upss_encargado on t1.id_upss_encargado = upss_encargado.id
            left join consultorios servicio_encargado on t1.id_servicio_encargado = servicio_encargado.id
            
        where T1.id=$id_hospitalizacion
            and tipo_hospitalizacion='HO'";

        $data = DB::select($cad);
        return $data;
    }

    public function obtenerRecetasSitedsTemporal(){
        $query = "select 
                        rv.id,
                        rv.id_asegurado,
                        f.id_establecimiento,
                        rv.id_consultorio,
                        rv.codigo_siteds, 
                        rv.updated_at_cod_siteds
                    from receta_vales rv
                    inner join farmacias f on rv.id_farmacia = f.id 
                    where 
                        rv.codigo_siteds is not null and 
                        RIGHT(rv.codigo_siteds, 1) = 'T' 
                    order by rv.id asc 
                    limit 10;";
        $data = DB::select($query); 
        return $data;
    }

    public function GetRecetasByIdInterconsulta($id_interconsulta) {
        $datos = Receta_vale::where('id_interconsulta', '=', $id_interconsulta)->where('estado', '<>', 0)->get();
        return $datos;
    }

    public function listar_receta_vale($p){
        $sql = "SELECT * FROM fn_listar_receta_vale_rango(?, ?, ?, ?, ?)";
        return DB::select($sql, $p);
    }

}
