<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class Consejeria extends Model
{
    protected $table = 'consejerias';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_prestacion',
        'id_hospitalizacion',
        'id_evolucion',
        'id_asegurado',
        'id_tipo_atencion',
        'id_establecimiento',
        'id_servicio',
        'id_medico',
        'estado',
        'created_at',
        'created_us',
        'fecha_atencion',
        'updated_at',
        'updated_us',
        'deleted_at',
        'deleted_us',
        'upss',
        'id_servicio_especialista',
        'upss_especialista',
        'fecha_firma',
        'ruta_firma',
        'flag_firma',
        'observacion',
        'deleted_reason'
    ];

    public function insertConsejeria($arrayData)
    {   

        $consejeria = new Consejeria();
        $consejeria->id_prestacion = $arrayData[0]->id_prestacion;
        $consejeria->id_hospitalizacion = $arrayData[0]->id_hospitalizacion;
        $consejeria->id_evolucion = $arrayData[0]->id_evolucion;
        $consejeria->id_asegurado = $arrayData[0]->id_asegurado;
        $consejeria->id_tipo_atencion = $arrayData[0]->id_tipo_atencion;
        $consejeria->id_establecimiento = $arrayData[0]->id_establecimiento;
        $consejeria->id_servicio = $arrayData[0]->id_servicio;
        $consejeria->id_medico = $arrayData[0]->id_medico;
        $consejeria->estado = 1;
        $consejeria->created_at = Carbon::now();
        $consejeria->created_us = Auth::user()->id;
        $consejeria->fecha_atencion = $arrayData[0]->fecha_atencion;
        $consejeria->upss = $arrayData[0]->upss;
        $consejeria->id_servicio_especialista = $arrayData[0]->id_servicio_especialista;
        $consejeria->upss_especialista = $arrayData[0]->upss_especialista;
        $consejeria->observacion = $arrayData[0]->observacion;

        $consejeria->save();

        return $consejeria;
    }

    public function insertConsejeriaEmergencia($arrayData)
    {   

        $consejeria = new Consejeria();
        $consejeria->id_prestacion = $arrayData[0]->id_prestacion;
        $consejeria->id_emergencia = $arrayData[0]->id_emergencia;
        $consejeria->id_evolucion = $arrayData[0]->id_evolucion;
        $consejeria->id_asegurado = $arrayData[0]->id_asegurado;
        $consejeria->id_tipo_atencion = $arrayData[0]->id_tipo_atencion;
        $consejeria->id_establecimiento = $arrayData[0]->id_establecimiento;
        $consejeria->id_servicio = $arrayData[0]->id_servicio;
        $consejeria->id_medico = $arrayData[0]->id_medico;
        $consejeria->estado = 1;
        $consejeria->created_at = Carbon::now();
        $consejeria->created_us = Auth::user()->id;
        $consejeria->fecha_atencion = $arrayData[0]->fecha_atencion;
        $consejeria->upss = $arrayData[0]->upss;
        $consejeria->id_servicio_especialista = $arrayData[0]->id_servicio_especialista;
        $consejeria->upss_especialista = $arrayData[0]->upss_especialista;
        $consejeria->observacion = $arrayData[0]->observacion;

        $consejeria->save();

        return $consejeria;
    }

    public function addDiagnostico($id_prestacion, $diagnosticos, $user, $id_consejeria) {
        $cad = "Select sp_agregar_diagnostico_consejeria(?,?,?,?)";
        $data = DB::select($cad, array($id_prestacion, $diagnosticos, $user, $id_consejeria));
        return $data[0]->sp_agregar_diagnostico_consejeria;
    }

    public function addProcedimiento($id_prestacion, $diagnosticos, $user, $id_consejeria) {
        $cad = "Select sp_agregar_procedimiento_consejeria(?,?,?,?)";
        $data = DB::select($cad, array($id_prestacion, $diagnosticos, $user, $id_consejeria));
        return $data[0]->sp_agregar_procedimiento_consejeria;
    }

    public static function RegistrarProcedimientoxDiagnostico($idprestacionxDiagProc,  $idprocedimiento, $iddiagnostico,$estado,$id_consejeria)
    {
        $cad = "select * FROM sp_diagnosticoxprocedimiento_consejeria(?,?,?,?,?)";
        $data = DB::select($cad, array($idprestacionxDiagProc, $idprocedimiento, $iddiagnostico,$estado,$id_consejeria));
        return $data[0]->sp_diagnosticoxprocedimiento_consejeria;
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
    
    public function GetHospitalizadosConsejeria($p)
    {
        return $this->readFunctionPostgres('sp_hospitalizados_consejeria', $p);
    }

    public function GetHospitalizadosConsejeria2($p)
    {
        return $this->readFunctionPostgres('sp_hospitalizados_consejeria2', $p);
    }

    public function GetEmergenciasConsejeria($p)
    {
        return $this->readFunctionPostgres('sp_emergencias_consejeria', $p);
    }

    public function GetEmergenciasConsejeria2($p)
    {
        return $this->readFunctionPostgres('sp_emergencias_consejeria2', $p);
    }

    public function GetHistorialConsejeria($p)
    {
        return $this->readFunctionPostgres('sp_historial_consejeria', $p);
    }

    public function GetHistorialEmergenciaConsejeria($p)
    {
        return $this->readFunctionPostgres('sp_historial_emergencia_consejeria', $p);
    }

    public function getAtencionById($id) 
    {
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
            ->leftJoin('hospitalizacion_atenciones as ha', 'ha.id_ext', '=', 'h.id')
            ->where('h.id', $id)
            ->select(
                /*'t1.id as id_prestacion',
                't1.id_medico',
                't5.nombre as medico_nombre',
                't5.paterno as medico_paterno',
                't5.materno as medico_materno',
                't5.dni',*/
                DB::raw("to_char(t1.fecha_atencion, 'HH:MI AM') as hora_atencion"),
                DB::raw("to_char(t1.fecha_atencion, 'DD-MM-YYYY') as fecha_atencion")/*,
                't1.ruta_prestacion',
                DB::raw("(select array_agg(rv.ruta_receta order by rv.id) from receta_vales rv where rv.id_prestacion=t1.id and rv.estado<>0 and t1.id_ext=h.id and rv.id_evaluacion=0) as ruta_receta"),
                DB::raw("(select array_agg(rva.flag order by rva.id) from receta_vales rva where rva.id_prestacion=t1.id and rva.estado<>0 and t1.id_ext=h.id and rva.id_evaluacion=0) as flag_receta"),
                DB::raw("(select array_agg(la.ruta_laboratorio order by la.id) from laboratorios la where la.id_prestacion=t1.id and la.estado=1 and t1.id_ext=h.id and la.id_evaluacion=0) as ruta_laboratorio"),
                DB::raw("(select array_agg(lab.flag order by lab.id) from laboratorios lab where lab.id_prestacion=t1.id and lab.estado<>0 and t1.id_ext=h.id and lab.id_evaluacion=0) as flag_laboratorio"),
                DB::raw("(select array_agg(ra.ruta_radiologia order by ra.id) from radiologias ra where ra.id_prestacion=t1.id and ra.estado=1 and t1.id_ext=h.id and ra.id_evaluacion=0) as ruta_radiologia"),
                DB::raw("(select array_agg(rad.flag order by rad.id) from radiologias rad where rad.id_prestacion=t1.id and rad.estado<>0 and t1.id_ext=h.id and rad.id_evaluacion=0) as flag_radiologia"),
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
                'ha.hoja_terapeutica'*/
            )
            ->first();

        return $prestaciones;
    }

    public function getAtencionEmergenciaById($id) 
    {
        $prestaciones = DB::table('emergencias as e')
            ->join('prestaciones as t1', function($join) {
                $join->on('t1.id_ext', '=', 'e.id')
                    ->where('t1.id_tipo_atencion', '=', 2)
                    ->where('t1.id_estado_reg', '=', 1);
            })
            ->join('medicos as t5', 't1.id_medico', '=', 't5.id')
            ->leftjoin('cambio_camas as cc', function($join) {
                $join->on('t1.id', '=', 'cc.id_prestacion')
                    ->where('cc.id_evolucion', '=', 0);
            })
            ->leftjoin('camas as ca', 'cc.id_cama_atencion', '=', 'ca.id')
            ->leftjoin('cuartos as c2', 'ca.id_cuarto', '=', 'c2.id')
            ->leftjoin('asignacion_servicios as as2', 'c2.id_asignacion_servicio', '=', 'as2.id')
            ->leftjoin('triajes as t4', 'e.ubica_topico', '=', 't4.id')
            /*->leftJoin('hospitalizacion_atenciones as ha', 'ha.id_ext', '=', 'h.id')*/
            ->where('e.id', $id)
            ->select(
                /*'t1.id as id_prestacion',
                't1.id_medico',
                't5.nombre as medico_nombre',
                't5.paterno as medico_paterno',
                't5.materno as medico_materno',
                't5.dni',*/
                DB::raw("to_char(t1.fecha_atencion, 'HH:MI AM') as hora_atencion"),
                DB::raw("to_char(t1.fecha_atencion, 'DD-MM-YYYY') as fecha_atencion")/*,
                't1.ruta_prestacion',
                DB::raw("(select array_agg(rv.ruta_receta order by rv.id) from receta_vales rv where rv.id_prestacion=t1.id and rv.estado<>0 and t1.id_ext=h.id and rv.id_evaluacion=0) as ruta_receta"),
                DB::raw("(select array_agg(rva.flag order by rva.id) from receta_vales rva where rva.id_prestacion=t1.id and rva.estado<>0 and t1.id_ext=h.id and rva.id_evaluacion=0) as flag_receta"),
                DB::raw("(select array_agg(la.ruta_laboratorio order by la.id) from laboratorios la where la.id_prestacion=t1.id and la.estado=1 and t1.id_ext=h.id and la.id_evaluacion=0) as ruta_laboratorio"),
                DB::raw("(select array_agg(lab.flag order by lab.id) from laboratorios lab where lab.id_prestacion=t1.id and lab.estado<>0 and t1.id_ext=h.id and lab.id_evaluacion=0) as flag_laboratorio"),
                DB::raw("(select array_agg(ra.ruta_radiologia order by ra.id) from radiologias ra where ra.id_prestacion=t1.id and ra.estado=1 and t1.id_ext=h.id and ra.id_evaluacion=0) as ruta_radiologia"),
                DB::raw("(select array_agg(rad.flag order by rad.id) from radiologias rad where rad.id_prestacion=t1.id and rad.estado<>0 and t1.id_ext=h.id and rad.id_evaluacion=0) as flag_radiologia"),
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
                'ha.hoja_terapeutica'*/
            )
            ->first();

        return $prestaciones;
    }
    

    public function getAtencionByConsejeria($id,$id_consejeria)
     {
    //   $query = "SELECT 
    //               c.fecha_atencion,
    //               c.id_servicio_especialista,
    //               c2.nombre especialidad,
    //               c.upss_especialista,
    //               u.descripcion_upss 
    //             FROM consejerias c
    //             INNER JOIN medicos m ON c.id_servicio_especialista = m.id_especializacion
    //             INNER JOIN consultorios c2 ON c.id_servicio_especialista = c2.id
    //             INNER JOIN upsses u ON u.id_consultorio = c2.id
    //             WHERE c.id_prestacion = $id
    //             AND u.estado = 1
	// 			AND c.id = $id_consejeria
    //             ORDER BY c.id DESC";
          $query = "select 
                        c.fecha_atencion,
                        c.id_servicio_especialista,
                        c2.nombre especialidad,
                        c.upss_especialista,
                        u.id upss_id,
                        u.descripcion_upss 
                    from consejerias c 
                    inner join consultorios c2 on c.id_servicio = c2.id 
                    inner join upsses u on CAST(c.upss_especialista as INTEGER) = u.id 
                    WHERE c.id_prestacion = $id
                    AND u.estado = 1
                    AND c.id = $id_consejeria
                    ORDER BY c.id desc";
      $data = DB::select($query); 
      return $data;
      }  

    public function validarRegistroConsejeria($id_tipo_atencion, $fecha_atencion, $id_servicio_especialista, $id_medico)
    {
        $cad = "Select sp_consultar_registro_consejeria(?,?,?,?)";
        $data = DB::select($cad, array($id_tipo_atencion, $fecha_atencion, $id_servicio_especialista, $id_medico));
        return $data[0]->sp_consultar_registro_consejeria;
    }

    function getByHospitalizacionId($id_hospitalizacion) {
        $consejerias = DB::table('consejerias')
                            ->where('id_hospitalizacion', $id_hospitalizacion)
                            ->get();
        return $consejerias;
    }

    function getByEmergenciaId($id_emergencia) {
        $consejerias = DB::table('consejerias')
                            ->where('id_emergencia', $id_emergencia)
                            ->get();
        return $consejerias;
    }

    function getById($id) {
        $query = "select 
                    c.id as consejeria_id,
                    c.id_establecimiento as id_establecimiento,
                    ta.id as tipo_atencion_id,
                    ta.nombre as tipo_atencion,
                    e.id as establecimiento_id,
                    e.nombre as establecimiento,
                    p.id as id_prestacion,
                    p.fecha_atencion,
                    a.nro_doc_ident as asegurado_nro_doc_ident,
                    a.nombre as asegurado_nombre,
                    a.paterno as asegurado_paterno,
                    a.materno as asegurado_materno,
                    ah.nro_historia,
                    m.id as medico_id,
                    m.nombre as medico_nombre,
                    m.paterno as medico_paterno,
                    m.materno as medico_materno,
                    m.rne as medico_rne,
                    m.dni as medico_dni,
                    t7.nombre as especializacion_medico,
                    m.id_profesion as medico_profesion,
                    p2.nombre as medico_profesion,
                    m.colegiatura as medico_colegiatura,
                    c2.id as consultorio_id,
                    c2.nombre as consultorio,
                    c3.id as cama_id,
                    c3.nombre as cama,
                    tdi.id as tipo_documento_identidad_id,
                    tdi.descripcion as tipo_documento_identidad,
                    h.cod_siteds_susalud,
                    h.updated_at_cod_siteds,
                    h.updated_us_cod_siteds,
                    h.fecha_ingreso as hospitalizacion_fecha_ingreso, 
                    c.fecha_atencion as consejeria_fecha_atencion,
                    a.sexo as asegurado_sexo,
                    a.id_tipo_beneficiario,
                    a.fecha_nac as asegurado_fecha_nac,
                    a.situacion_paciente as asegurado_situacion_paciente,
                    tb.codigo as tipo_beneficiario_codigo,
                    tb.descripcion as tipo_beneficiario_descripcion,
                    a2.id as id_asegurado_titular,
                    a2.nro_doc_ident as titular_nro_doc_ident,
                    a2.cip as titular_cip,
                    a2.nombre as titular_nombre,
                    a2.paterno as titular_paterno,
                    a2.materno as titular_materno,
                    a2.grado as titular_grado,
                    tdi2.id as titular_id_tipo_doc_ident,
                    tdi2.codigo titular_tipo_doc_ident_codigo,
                    tdi2.descripcion as titular_tipo_doc_ident_descripcion,
                    h.id_servicio_encargado,
                    c.upss,
                    c.upss_especialista,
                    c.observacion,
                    e.nivel as establecimiento_nivel,
                    h.id as hospitalizacion_id,
                    u.id as upss_especialista_id,
                    u.codigo as upss_especialista_codigo,
                    u.descripcion_upss as upss_especialista_descripcion,
                    c.estado as consejeria_estado,
                    c.ruta_firma , 
                    c.fecha_firma, 
                    c.flag_firma,
                    c.created_at,
                    c.created_us,
                    h.id_cama_estado as hospitalizacion_id_cama_estado,
                    h.id_servicio_encargado as hospitalizacion_id_servicio_encargado,
                    h.id_diagnostico_inicial as hospitalizacion_id_diagnostico_inicial,
                    h.id_dx_2 as hospitalizacion_id_dx_2,
                    h.id_dx_3 as hospitalizacion_id_dx_3
                from consejerias c
                inner join hospitalizaciones h on c.id_hospitalizacion = h.id 
                left join prestaciones p on h.id = p.id_ext and p.id_tipo_atencion = 3
                left join tipo_atenciones ta on c.id_tipo_atencion = ta.id 
                left join establecimientos e on c.id_establecimiento = e.id 
                left join asegurados a on c.id_asegurado = a.id 
                left join asegurados a2 on a2.id = a.id_asegurado_titular
                left join tipo_documento_identidad tdi on a.id_tipo_doc_ident = tdi.id 
                left join tipo_documento_identidad tdi2 on tdi2.id = a2.id_tipo_doc_ident 
                left join medicos m on c.id_medico = m.id
                left join especializaciones t7 ON m.id_especializacion = t7.id
                inner join consultorios c2 on c.id_servicio_especialista = c2.id 
                left join upsses u on CAST(c.upss_especialista as INTEGER) = u.id 
                left join asegurado_historias ah on c.id_asegurado = ah.id_asegurado and c.id_establecimiento = ah.id_establecimiento 
                inner join tipo_beneficiarios tb on a.id_tipo_beneficiario  = tb.id 
                left join camas c3 on h.id_cama = c3.id 
                left join profesiones p2 on m.id_profesion = p2.id
                where c.id = $id
                limit 1";
        $data = DB::select($query);

        if (isset($data) && count($data) > 0) {
            return $data[0];
        }
        return null;
    }

    function getByIdEmergencia($id) {
        $query = "SELECT 
                    e.id AS emergencia_id,
                    c.id AS consejeria_id,
                    c.id_establecimiento AS id_establecimiento,
                    ta.id AS tipo_atencion_id,
                    ta.nombre AS tipo_atencion,
                    e.id AS establecimiento_id,
                    es.nombre AS establecimiento,
                    p.id AS id_prestacion,
                    p.fecha_atencion,
                    a.nro_doc_ident AS asegurado_nro_doc_ident,
                    a.nombre AS asegurado_nombre,
                    a.paterno AS asegurado_paterno,
                    a.materno AS asegurado_materno,
                    ah.nro_historia,
                    m.id AS medico_id,
                    m.nombre AS medico_nombre,
                    m.paterno AS medico_paterno,
                    m.materno AS medico_materno,
                    m.rne AS medico_rne,
                    m.dni AS medico_dni,
                    t7.nombre AS especializacion_medico,
                    m.id_profesion AS medico_profesion,
                    p2.nombre AS medico_profesion,
                    m.colegiatura AS medico_colegiatura,
                    t.descripcion AS area,
                    c2.id AS consultorio_id,
                    c2.nombre AS consultorio,
                    cl.nombre AS servicio,
                    c3.id AS cama_id,
                    c3.nombre AS cama,
                    tdi.id AS tipo_documento_identidad_id,
                    tdi.descripcion AS tipo_documento_identidad,
                    e.cod_siteds_susalud,
                    e.updated_at_cod_siteds,
                    e.updated_us_cod_siteds,
                    e.fecha_registro_admision AS emergencias_fecha_registro_admision,
                    c.fecha_atencion AS consejeria_fecha_atencion,
                    a.sexo AS asegurado_sexo,
                    a.id_tipo_beneficiario,
                    to_char(age(TO_DATE(a.fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\" \"y\" DD \"dias\"') AS edad,
                    a.fecha_nac AS asegurado_fecha_nac,
                    a.situacion_paciente AS asegurado_situacion_paciente,
                    tb.codigo AS tipo_beneficiario_codigo,
                    tb.descripcion AS tipo_beneficiario_descripcion,
                    a2.id AS id_asegurado_titular,
                    a2.nro_doc_ident AS titular_nro_doc_ident,
                    a2.cip AS titular_cip,
                    a2.nombre AS titular_nombre,
                    a2.paterno AS titular_paterno,
                    a2.materno AS titular_materno,
                    a2.grado AS titular_grado,
                    tdi2.id AS titular_id_tipo_doc_ident,
                    tdi2.codigo AS titular_tipo_doc_ident_codigo,
                    tdi2.descripcion AS titular_tipo_doc_ident_descripcion,
                    c.upss,
                    c.upss_especialista,
                    c.observacion,
                    es.nivel AS establecimiento_nivel,
                    e.id as emergencias_id,
                    u.id AS upss_especialista_id,
                    u.codigo AS upss_especialista_codigo,
                    u.descripcion_upss AS upss_especialista_descripcion,
                    e.id_triaje,
                    c.estado AS consejeria_estado,
                    c.ruta_firma, 
                    c.fecha_firma, 
                    c.flag_firma,
                    c.created_at,
                    c.created_us,
                    e.cama_estado AS emergencias_cama_estado,
                    e.id_triaje AS emergencias_id_triaje,
                    e.id_diag_cab AS emergencias_id_diag_cab,
                    e.id_diag_cuer1 AS emergencias_id_diag_cuer1,
                    e.id_diag_cuer2 AS emergencias_id_diag_cuer2
                FROM consejerias c
                INNER JOIN emergencias e ON c.id_emergencia = e.id 
                LEFT JOIN prestaciones p on e.id = p.id_ext and p.id_tipo_atencion = 2
                LEFT JOIN tipo_atenciones ta ON c.id_tipo_atencion = ta.id 
                LEFT JOIN establecimientos es ON c.id_establecimiento = es.id
                LEFT JOIN asegurados a ON c.id_asegurado = a.id 
                LEFT JOIN asegurados a2 ON a2.id = a.id_asegurado_titular
                LEFT JOIN tipo_documento_identidad tdi ON a.id_tipo_doc_ident = tdi.id 
                LEFT JOIN tipo_documento_identidad tdi2 ON tdi2.id = a2.id_tipo_doc_ident 
                LEFT JOIN medicos m ON c.id_medico = m.id
                LEFT JOIN especializaciones t7 ON m.id_especializacion = t7.id
                INNER JOIN triajes t ON e.ubica_topico = t.id 
                INNER JOIN consultorios c2 ON c.id_servicio_especialista = c2.id 
                INNER JOIN consultorios cl ON c.id_servicio = cl.id 
                LEFT JOIN upsses u ON CAST(c.upss_especialista AS INTEGER) = u.id 
                LEFT JOIN asegurado_historias ah ON c.id_asegurado = ah.id_asegurado AND c.id_establecimiento = ah.id_establecimiento 
                INNER JOIN tipo_beneficiarios tb ON a.id_tipo_beneficiario = tb.id 
                INNER JOIN camas c3 ON e.cama_estado = c3.id 
                LEFT JOIN profesiones p2 ON m.id_profesion = p2.id
                WHERE c.id = $id
                LIMIT 1";
        $data = DB::select($query);

        if (isset($data) && count($data) > 0) {
            return $data[0];
        }
        return null;
    }

    public function all_hospitalizado_consejeria_ajax_params(
        $fecha_ini,
        $fecha_fin,
        $fecha_ini_registro,
        $fecha_fin_registro,
        $ndocumento,
        $id_servicio_destino,
        $busqueda_datos,
        $estado,
        $NumeroPagina,
        $NumeroRegistros)
    {
      $model_consejeria = new Consejeria();
      
      $p = [
          Auth::user()->id_establecimiento,
          $fecha_ini,
          $fecha_fin,
          $fecha_ini_registro,
          $fecha_fin_registro,
          $ndocumento,
          $id_servicio_destino,
          $busqueda_datos,
          $estado,
          $NumeroPagina,
          $NumeroRegistros
      ];
  
      $data = $model_consejeria->GetHospitalizadosConsejeria($p);
      $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
  
      return $data;
  }

  public function all_emergencia_consejeria_ajax_params(
    $fecha_ini,
    $fecha_fin,
    $fecha_ini_registro,
    $fecha_fin_registro,
    $ndocumento,
    $id_servicio_destino,
    $busqueda_datos,
    $estado,
    $NumeroPagina,
    $NumeroRegistros)
{
  $model_consejeria = new Consejeria();
  
  $p = [
      Auth::user()->id_establecimiento,
      $fecha_ini,
      $fecha_fin,
      $fecha_ini_registro,
      $fecha_fin_registro,
      $ndocumento,
      $id_servicio_destino,
      $busqueda_datos,
      $estado,
      $NumeroPagina,
      $NumeroRegistros
  ];

  $data = $model_consejeria->GetEmergenciasConsejeria($p);
  $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;

  return $data;
}

}
