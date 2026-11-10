<?php

namespace App\Models;

use App\Models\Prestaciones;
use Illuminate\Database\Eloquent\Model;
use DB;

class SolicitudProgramacionProcedimientos extends Model
{
    protected $table = 'solicitud_programacion_procedimientos';

    protected $fillable = [
        'id_prestacion',
        'id_tipo_atencion',
        'fecha_solicitud',
        'created_us',
        'updated_us',
    ];

    public $timestamps = true; // Habilita created_at y updated_at

    // Relaciones

    public function detalles()
    {
        return $this->hasMany(SolicitudProgramacionProcDetalles::class, 'id_solicitud');
    }

    public function prestacion()
    {
        return $this->belongsTo(Prestaciones::class, 'id_prestacion');
    }

    public static function ppLimiteAgregar($id_prestacion){
        $prestacion = \DB::table('prestaciones')->where('id', intval($id_prestacion))->select('created_at', 'fecha_atencion')->first();
        if (!$prestacion) return null;
        $fecha = $prestacion->created_at ?: $prestacion->fecha_atencion;
        return $fecha ? \Carbon\Carbon::parse($fecha)->endOfDay() : null;
    }

    public function GetOrdenByIdPrestacion($id_prestacion)
    {
        $orden = SolicitudProgramacionProcedimientos::where("id_prestacion", $id_prestacion)->first();
        return $orden;
    }

    public function GetDatosAtencionPP($id_solicitud, $id_establecimiento)
    {
        $solicitud = DB::select("select s.id as id_solicitud, s.id_tipo_atencion, to_char(s.fecha_solicitud, 'DD/MM/YYYY HH24:MI') as fecha_solicitud,
            p.id as id_prestacion, to_char(p.fecha_atencion, 'DD/MM/YYYY HH24:MI:SS') as fecha_atencion_origen,
            ta.nombre as tipo_atencion, c.id as id_servicio, c.nombre as servicio_solicitante, ci.codigo_siteds,
            m.dni as medico_dni, m.paterno || ' ' || m.materno || ', ' || m.nombre as medico_origen, m.colegiatura as medico_cmp, m.rne as medico_rne,
            a.id as id_asegurado, a.nro_doc_ident, a.id_tipo_doc_ident, upper(coalesce(tdi.descripcion, 'DNI')) as tipo_doc,
            a.paterno, a.materno, a.nombre,
            a.fecha_nac, a.sexo, tb.descripcion as tipo_beneficiario,
            us.dni as solicitante_dni,
            coalesce(ms.paterno || ' ' || ms.materno || ', ' || ms.nombre, us.paterno || ' ' || us.materno || ', ' || us.name) as solicitante,
            ms.colegiatura as solicitante_cmp, ms.rne as solicitante_rne
          from solicitud_programacion_procedimientos s
          inner join prestaciones p on p.id = s.id_prestacion
          -- Médico solicitante: el usuario que registró la solicitud (created_us), buscado en medicos por DNI
          left join users us on us.id = s.created_us
          left join medicos ms on ms.id = (
            select md.id
            from medicos md
            where md.dni = us.dni
            order by (md.id_establecimiento = p.id_establecimiento) desc, md.estado desc, md.id desc
            limit 1
          )
          inner join asegurados a on a.id = p.id_asegurado
          left join tipo_atenciones ta on ta.id = s.id_tipo_atencion
          left join sub_consultorios sc on sc.id = p.id_sub_consultorio
          left join consultorios c on c.id = sc.id_consultorio
          left join citas ci on ci.id = p.id_cita
          left join medicos m on m.id = p.id_medico
          left join tipo_beneficiarios tb on tb.id = a.id_tipo_beneficiario
          left join tipo_documento_identidad tdi on tdi.id = a.id_tipo_doc_ident
          where s.id = ? and p.id_establecimiento = ?", [$id_solicitud, $id_establecimiento]);
        return count($solicitud) > 0 ? $solicitud[0] : null;
    }

    public function GetDetalleSolicitudPP($id_solicitud) {
        return DB::select("select pr.codigo, pr.descripcion, d.cantidad, d.indicacion, d.estado,
            dg.codigo || ' - ' || dg.nombre as diagnostico
          from solicitud_procedimientos_detalle d
          inner join procedimientos pr on pr.id = d.id_procedimiento
          left join diagnosticos dg on dg.id = d.id_diagnostico
          where d.id_solicitud = ?
          order by d.id", [$id_solicitud]);
    }
}
